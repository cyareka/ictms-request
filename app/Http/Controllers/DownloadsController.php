<?php

namespace App\Http\Controllers;

use App\Models\ConferenceRequest;
use App\Models\VehicleRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\CrossReference\CrossReferenceException;
use setasign\Fpdi\PdfParser\Filter\FilterException;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\PdfParser\Type\PdfTypeException;
use setasign\Fpdi\PdfReader\PdfReaderException;
use Throwable;

class DownloadsController extends Controller
{
    /**
     * @throws PdfTypeException
     * @throws CrossReferenceException
     * @throws PdfReaderException
     * @throws PdfParserException
     * @throws FilterException
     */
    public function downloadCRequestPDF(Request $request, $CRequestID)
    {
        try {
            Log::info('Starting downloadCRequestPDF method.');

            // Validate the CRequestID parameter
            $validated = $request->validate([
                'CRequestID' => 'string|exists:conference_room_requests,CRequestID',
            ]);
            Log::info('Validation successful.', ['validated' => $validated]);

            $conferenceRequest = ConferenceRequest::with('conferenceRoom', 'purposeRequest', 'focalPerson')
                ->where('CRequestID', $CRequestID)
                ->firstOrFail();

            $pdf = new Fpdi();
            $pdf->AddPage();
            $sourceFile = public_path('storage/uploads/templates/croom_forms/CR_req_for_use.pdf');

            if (!file_exists($sourceFile)) {
                Log::error('Source file does not exist.', ['sourceFile' => $sourceFile]);
                return response()->json(['error' => 'Source file does not exist.'], 500);
            }

            $pdf->setSourceFile($sourceFile);
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx, 0, 0, 210);

            $pdf->SetTextColor(0, 0, 0);

            // TOP PART
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->SetXY(160, 60); // Date Requested
            $pdf->Write(0, $conferenceRequest->created_at);

            $pdf->SetFont('Helvetica', '', 10);

            $pdf->SetXY(90, 68); // Requester Name
            $pdf->Write(0, $conferenceRequest->RequesterName);

            $pdf->SetXY(90, 77);
            if (is_null($conferenceRequest->purposeRequest)) {
                // Purpose
                $pdf->Write(0, $conferenceRequest->PurposeOthers);
            } else {
                // Purpose
                $pdf->Write(0, $conferenceRequest->purposeRequest->purpose);
            }

            $pdf->SetXY(90, 85); // Date Start
            $pdf->Write(0, $conferenceRequest->date_start);

            $pdf->SetXY(108, 85); // Date End
            $pdf->Write(0, ' - ' . $conferenceRequest->date_end);

            $pdf->SetXY(90, 94); // Time Start
            $pdf->Write(0, $conferenceRequest->time_start);

            $pdf->SetXY(101, 94); // Time End
            $pdf->Write(0, ' - ' . $conferenceRequest->time_end);

            $pdf->SetXY(90, 102); // Number of Persons
            $pdf->Write(0, $conferenceRequest->npersons);

            $pdf->SetXY(90, 110);
            // Focal Person
            $pdf->Write(0, $conferenceRequest->focalPerson->FPName);


            // Conference Room 1 (Magiting)
            if ($conferenceRequest->conferenceRoom->CRoomName === 'Magiting') {
                $pdf->SetXY(70.4, 128.5);
            } else {
                $pdf->SetXY(70.4, 138.5);
            }
            $pdf->Write(0, 'x');

            if (!is_null($conferenceRequest->otherFacilities)) {
                $pdf->SetXY(70.4, 148.9); // Other Facilities
                $pdf->Write(0, 'x');

                $pdf->SetXY(135, 149); // Other Facilities
                $pdf->Write(0, $conferenceRequest->otherFacilities);
            }

            if (!is_null($conferenceRequest->tables) && $conferenceRequest->tables != '0') {
                $pdf->SetXY(70.4, 158.9); // Tables
                $pdf->Write(0, 'x');

                $pdf->SetXY(109.9, 159); // Tables
                $pdf->Write(0, $conferenceRequest->tables);
            }

            if (!is_null($conferenceRequest->chairs) && $conferenceRequest->chairs != '0') {
                $pdf->SetXY(70.4, 169.9); // Chairs
                $pdf->Write(0, 'x');

                $pdf->SetXY(109.9, 170); // Chairs
                $pdf->Write(0, $conferenceRequest->tables);
            }

            $pdf->SetXY(30.4, 225.9); // RequesterSignature
            $pdf->Write(0, $conferenceRequest->RequesterName);

            $signaturePath = 'uploads/signatures/' . basename($conferenceRequest->RequesterSignature);
            if (file_exists($signaturePath) && is_readable($signaturePath)) {
                $pdf->Image($signaturePath, 10, 10, 30, 30);
            } else {
                echo "Error: File not found or not readable";
            }

            ob_clean();
            // I instead of F to output the PDF to the browser
            $pdf->Output('I', $conferenceRequest->CRequestID . '_CR_Request.pdf');
        } catch (Throwable $e) {
            Log::error('Error in downloadCRequestPDF method.', [
                'exception' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'An error occurred while generating the PDF.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadFinalCRequestPDF(Request $request, $CRequestID)
    {
        $conferenceRequest = ConferenceRequest::with('conferenceRoom', 'purposeRequest', 'focalPerson')
            ->where('CRequestID', $CRequestID)
            ->firstOrFail();

        $certFilePath = $conferenceRequest['certfile-upload'];

        if (is_null($certFilePath)) {
            Log::error('No file uploaded for this request.', ['CRequestID' => $CRequestID]);
            return response()->json(['error' => 'No file uploaded for this request.'], 400);
        }

        if (Storage::disk('public')->exists($certFilePath)) {
            $fileContents = Storage::disk('public')->get($certFilePath);
            Log::info('File contents retrieved.', ['fileContents' => $fileContents]);

            return response($fileContents, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . basename($certFilePath) . '"');
        } else {
            Log::error('File not found.', ['filePath' => $certFilePath]);
            return response()->json(['error' => 'File not found'], 404);
        }
    }

    public function downloadUnavailableCRequestPDF(Request $request, $CRequestID)
    {
        try {
            Log::info('Starting downloadUnavailableCRequestPDF method.');

            // Validate the CRequestID parameter
            $validated = $request->validate([
                'CRequestID' => 'string|exists:conference_room_requests,CRequestID',
            ]);
            Log::info('Validation successful.', ['validated' => $validated]);

            $conferenceRequest = ConferenceRequest::with('conferenceRoom', 'purposeRequest', 'focalPerson')
                ->where('CRequestID', $CRequestID)
                ->firstOrFail();

            $pdf = new Fpdi();
            $pdf->AddPage();
            $sourceFile = public_path('storage/uploads/templates/croom_forms/CR_unavailability.pdf');

            if (!file_exists($sourceFile)) {
                Log::error('Source file does not exist.', ['sourceFile' => $sourceFile]);
                return response()->json(['error' => 'Source file does not exist.'], 500);
            }

            $pdf->setSourceFile($sourceFile);
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx, 0, 0, 210);
            $pdf->SetTextColor(0, 0, 0);

            // TOP PART
            $pdf->SetFont('Helvetica', '', 9);
            $pdf->SetXY(95, 112.3); // Date Requested
            $pdf->Write(0, $conferenceRequest->office->OfficeName);

            $pdf->SetFont('Helvetica', '', 10);

            $pdf->SetXY(95, 120);
            if (is_null($conferenceRequest->purposeRequest)) {
                // Purpose
                $pdf->Write(0, $conferenceRequest->PurposeOthers);
            } else {
                // Purpose
                $pdf->Write(0, $conferenceRequest->purposeRequest->purpose);
            }

            $pdf->SetXY(95, 127); // Number of Persons
            $pdf->Write(0, $conferenceRequest->npersons);

            $pdf->SetXY(95, 134); // Date Start
            $pdf->Write(0, $conferenceRequest->date_start);

            $pdf->SetXY(114, 134); // Time Start
            $pdf->Write(0, $conferenceRequest->time_start);

            $pdf->SetXY(125, 134); // Date End
            $pdf->Write(0, ' -   ' . $conferenceRequest->date_end);

            $pdf->SetXY(150, 134); // Time End
            $pdf->Write(0, $conferenceRequest->time_end);

            // I instead of F to output the PDF to the browser
            $pdf->Output('I', $conferenceRequest->CRequestID . '_CR_Certificate_of_Unavailability.pdf');
        } catch (Throwable $e) {
            Log::error('Error in downloadCRequestPDF method.', [
                'exception' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'An error occurred while generating the PDF.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function downloadVRequestPDF (Request$request, $VRequestID)
    {
        try {
            Log::info('Starting downloadVRequestPDF method.');

            // Validate the VRequestID parameter
            $validated = $request->validate([
                'VRequestID' => 'string|exists:vehicle_requests,VRequestID',
            ]);
            Log::info('Validation successful.', ['validated' => $validated]);

            $vehicleRequest = VehicleRequest::with('office')
                ->where('VRequestID', $VRequestID)
                ->firstOrFail();

            $pdf = new Fpdi();
            $pdf->AddPage();
            $sourceFile = public_path('storage/uploads/templates/vehicle_forms/VR_dailydispatchreport.pdf');

            if (!file_exists($sourceFile)) {
                Log::error('Source file does not exist.', ['sourceFile' => $sourceFile]);
                return response()->json(['error' => 'Source file does not exist.'], 500);
            }

            $pdf->setSourceFile($sourceFile);
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx, 0, 0, 210);

            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Helvetica', '', 10);

            $pdf->SetXY(10, 50); // VRequestID
            $pdf->Write(0, $vehicleRequest->VRequestID);

            $pdf->SetXY(30, 50); // Date Start
            $pdf->Write(0, $vehicleRequest->date_start);

            $pdf->SetXY(60, 50); // Destination
            $pdf->Write(0, $vehicleRequest->Destination);

            $pdf->SetXY(90, 50); // Purpose
            $pdf->Write(0, $vehicleRequest->Purpose);

            $pdf->SetXY(120, 50); // Office Name
            $pdf->Write(0, $vehicleRequest->office->OfficeName);

            // I instead of F to output the PDF to the browser
            $pdf->Output('I', $vehicleRequest->VRequestID . '_VR_DailyDispatchReport.pdf');
        } catch (Throwable $e) {
            Log::error('Error in downloadVRequestPDF method.', [
                'exception' => $e->getMessage(),
                'stack_trace' => $e->get;
            ]);
        }
    }

    public function downloadFinalVRequestPDF(Request $request, $VRequestID)
    {
        $vehicleRequest = VehicleRequest::with('office')
            ->where('VRequestID', $VRequestID)
            ->firstOrFail();

        $certFilePath = $vehicleRequest['certfile-upload'];

        if (Storage::disk('public')->exists($certFilePath)) {
            $fileContents = Storage::disk('public')->get($certFilePath);
            Log::info('File contents retrieved.', ['fileContents' => $fileContents]);

            return response($fileContents, 200)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="' . basename($certFilePath) . '"');
        } else {
            Log::error('File not found.', ['filePath' => $certFilePath]);
            return response()->json(['error' => 'File not found'], 404);
        }
    }


    public function downloadRangeVRequestPDF(Request $request)
    {
        // Validate the input parameters
        $validated = $request->validate([
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        $query = VehicleRequest::query()->where(function ($query) use ($validated) {
                $query->where(function ($query) use ($validated) {
                    $query->where('date_start', '<=', $validated['endDate'])
                        ->where('date_end', '>=', $validated['startDate']);
                });
            });

        Log::info('Query executed:', [
            'query' => $query->toSql(),
            'bindings' => $query->getBindings(),
        ]);

        $vehicleRequests = $query->get();
        dd($vehicleRequests);

//// Remove this unnecessary query
//// $vehicleRequests = VehicleRequest::select('date_start', 'date_end')->get();
//
//// Instead, use the retrieved data to log the dates
//        foreach ($vehicleRequests as $request) {
//            echo "Date Start: " . $request->date_start . "\n";
//            echo "Date End: " . $request->date_end . "\n";
//        }
//
//        if ($vehicleRequests->isEmpty()) {
//            Log::error('No vehicle requests found within the specified date range.');
//            return response()->json(['error' => 'No vehicle requests found within the specified date range.'], 404);
//        }
//
//        $pdf = new Fpdi();
//        $pdf->AddPage();
//        $sourceFile = public_path('storage/uploads/templates/vehicle_forms/VR_dailydispatchreport.pdf');
//
//        if (!file_exists($sourceFile)) {
//            Log::error('Source file does not exist.', ['sourceFile' => $sourceFile]);
//            return response()->json(['error' => 'Source file does not exist.'], 500);
//        }
//
//        $pdf->setSourceFile($sourceFile);
//        $tplIdx = $pdf->importPage(1);
//        $pdf->useTemplate($tplIdx, 0, 0, 210);
//
//        $pdf->SetTextColor(0, 0, 0);
//        $pdf->SetFont('Helvetica', '', 10);
//
//        // Example of filling in the PDF with vehicle request data
//        $yPosition = 50; // Starting Y position for the data
//        foreach ($vehicleRequests as $request) {
//            $pdf->SetXY(10, $yPosition);
//            $pdf->Write(0, $request->VRequestID);
//            $pdf->SetXY(30, $yPosition);
//            $pdf->Write(0, $request->date_start);
//            $pdf->SetXY(60, $yPosition);
//            $pdf->Write(0, $request->Destination);
//            $pdf->SetXY(90, $yPosition);
//            $pdf->Write(0, $request->Purpose);
//            $pdf->SetXY(120, $yPosition);
//            $pdf->Write(0, $request->office->OfficeName);
//            $yPosition += 10; // Move to the next line
//        }
//
//        // Output the PDF to the browser
//        $pdf->Output('I', 'VR_DailyDispatchReport.pdf');
    }
}
