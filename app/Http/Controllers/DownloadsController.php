<?php

namespace App\Http\Controllers;

use App\Models\ConferenceRequest;
use App\Models\VehicleRequest;
use Carbon\Carbon;
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
use App\Http\Controllers\VehicleController;
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
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetXY(160, 60); // Date Requested
            $pdf->Write(0, $conferenceRequest->created_at);

            $pdf->SetFont('Arial', '', 10);

            $pdf->SetXY(90, 68); // Requester Name
            $pdf->Write(0, $conferenceRequest->RequesterName);

            $pdf->SetXY(90, 75); // Requester Signature

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

            $pdf->SetXY(90, 110); // Focal Person
            if (is_null($conferenceRequest->FPName)) {
                $pdf->Write(0, $conferenceRequest->FPOthers);
            } else {
                $pdf->Write(0, $conferenceRequest->FPName);
            }

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
            $pdf->SetFont('Arial', '', 9);
            $pdf->SetXY(95, 112.3); // Date Requested
            $pdf->Write(0, $conferenceRequest->office->OfficeName);

            $pdf->SetFont('Arial', '', 10);

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

    public function downloadVRequestPDF(Request $request, $VRequestID): \Illuminate\Contracts\View\View|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application
    {
        ini_set('memory_limit', '256M');

        $pdf1 = $this->generateAuthoritytoTravelPDF($request, $VRequestID);
        $pdf2 = $this->generateVRequestPDF($request, $VRequestID);
        $pdf3 = $this->generateVRequestFormAPDF($request, $VRequestID);
        $pdf4 = $this->generateTravelOrderPDF($request, $VRequestID);

        $pdfs = [
            [
                'data' => 'data:application/pdf;base64,' . base64_encode($pdf1),
                'heading' => 'Authority to Travel'
            ],
            [
                'data' => 'data:application/pdf;base64,' . base64_encode($pdf2),
                'heading' => 'Vehicle Request'
            ],
            [
                'data' => 'data:application/pdf;base64,' . base64_encode($pdf3),
                'heading' => 'Vehicle Request Form A'
            ],
            [
                'data' => 'data:application/pdf;base64,' . base64_encode($pdf4),
                'heading' => 'Travel Order'
            ],
        ];

        return view('VehicleDownload', ['pdfs' => $pdfs]);
    }

    private function generateAuthoritytoTravelPDF(Request $request, $VRequestID)
    {
        try {
            $validated = $request->validate([
                'VRequestID' => 'string|exists:vehicle_requests,VRequestID',
            ]);
            Log::info('Validation successful.', ['validated' => $validated]);

            $vehicleRequest = VehicleRequest::findOrFail($VRequestID);

            $pdf = new Fpdi();
            $pdf->AddPage();
            $sourceFile = public_path('storage/uploads/templates/vehicle_forms/empty/VR_Authority-to-Travel.pdf');

            if (!file_exists($sourceFile)) {
                Log::error('Source file does not exist.', ['sourceFile' => $sourceFile]);
                return response()->json(['error' => 'Source file does not exist.'], 500);
            }

            $pdf->setSourceFile($sourceFile);
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx, 0, 0, 210);

            // Set text color and font
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 11);

            $pdf->SetXY(175, 51); // Date
            $pdf->Write(0, $vehicleRequest->created_at->format('Y-m-d'));

            // Purpose
            $pdf->SetFont('Arial', 'B', 13);
            $pdf->SetXY(25, 95.6);
            if (is_null($vehicleRequest->purpose_request)) {
                $pdf->Write(0, $vehicleRequest->PurposeOthers);
            } else {
                $pdf->Write(0, $vehicleRequest->purpose_request->purpose);
            }

            $pdf->SetFont('Arial', '', 12);

            // Driver
            $pdf->SetXY(25, 145);
            $pdf->Write(0, strtoupper($vehicleRequest->driver->DriverName));

            // Date Start
            $pdf->SetXY(126, 140);
            $pdf->Write(0, $vehicleRequest->date_start);

            $pdf->SetXY(135, 145);
            $pdf->Write(0, ' - ');

            $pdf->SetXY(126, 150);
            $pdf->Write(0, $vehicleRequest->date_end);

            $pdf->SetFont('Arial', '', 9);

            // Destination
            $pdf->SetXY(155, 145);
            $pdf->Write(0, $vehicleRequest->Destination);

            return $pdf->Output('S');
        } catch (Throwable $e) {
            Log::error('Error in generateAuthoritytoTravelPDF method.', [
                'exception' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'An error occurred while generating the PDF.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function generateVRequestPDF(Request $request, $VRequestID)
    {
        try {
            $validated = $request->validate([
                'VRequestID' => 'string|exists:vehicle_requests,VRequestID',
            ]);
            Log::info('Validation successful.', ['validated' => $validated]);

            $vehicleRequest = VehicleRequest::findOrFail($VRequestID);

            $pdf = new Fpdi();
            $pdf->AddPage('P', [215.9, 330.2]);
            $sourceFile = public_path('storage/uploads/templates/vehicle_forms/empty/VR_Request-for-use-of-Vehicle.pdf');

            if (!file_exists($sourceFile)) {
                Log::error('Source file does not exist.', ['sourceFile' => $sourceFile]);
                return response()->json(['error' => 'Source file does not exist.'], 500);
            }

            $pdf->setSourceFile($sourceFile);
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx, 0, 0, 210);

            // Set text color and font
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 10);

            $pdf->SetXY(96, 70); // Office Name
            $pdf->Write(0, $vehicleRequest->office->OfficeName);

            $pdf->SetXY(96, 74); // Purpose
            if (is_null($vehicleRequest->purpose_request)) {
                $pdf->Write(0, $vehicleRequest->PurposeOthers);
            } else {
                $pdf->Write(0, $vehicleRequest->purpose_request->purpose);
            }

            $vehicleController = new VehicleController(); // Passengers
            $passengers = $vehicleController->getPassengersByRequestId($VRequestID);
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetXY(96, 84);
            foreach ($passengers as $passenger) {
                $pdf->Write(0, $passenger->OfficeName . ', ' . $passenger->EmployeeName);
                $pdf->Ln();
            }

            $pdf->SetFont('Arial', '', 10);
            $pdf->SetXY(96, 99); // Destination
            $pdf->Write(0, $vehicleRequest->Destination);

            $pdf->SetXY(96, 109); // Date Start
            $pdf->Write(0, $vehicleRequest->date_start);

            $pdf->SetXY(115, 109); // Date End
            $pdf->Write(0, ' -  ' . $vehicleRequest->date_end);

            $pdf->SetXY(96, 114); // Time Start
            $pdf->Write(0, $vehicleRequest->time_start);

            $pdf->SetXY(40, 140); // Requester Name
            $pdf->Write(0, strtoupper($vehicleRequest->RequesterName));

            $pdf->SetXY(120, 140); // ReceivedBy
            $pdf->Write(0, strtoupper($vehicleRequest->receivedBy->name));

            $pdf->SetXY(55, 149.7); // Date Requested
            $pdf->Write(0, $vehicleRequest->created_at->format('Y-m-d'));

            $pdf->SetXY(140, 149.7); // Date Received
            $pdf->Write(0, $vehicleRequest->updated_at->format('Y-m-d'));

            $pdf->SetXY(55, 165.5); // Ticket Number
            $pdf->Write(0, $vehicleRequest->VRequestID);

            $pdf->SetXY(55, 189); // Vehicle Type
            $pdf->Write(0, $vehicleRequest->vehicle->VehicleType);

            $pdf->SetXY(55, 197.3); // Plate Number
            $pdf->Write(0, $vehicleRequest->vehicle->PlateNo);

            $pdf->SetXY(55, 205.5); // Remarks
            if (is_null($vehicleRequest->Remarks)) {
                $pdf->Write(0, '');
            } else {
                $pdf->Write(0, $vehicleRequest->Remarks);
            }

            $pdf->SetXY(130, 189); // Driver Name
            $pdf->Write(0, strtoupper($vehicleRequest->driver->DriverName));

            $pdf->SetXY(130, 197.3); //Driver Contact
            $pdf->Write(0, $vehicleRequest->driver->ContactNo);

            $pdf->SetXY(40, 239.6); // ASignatory
            $pdf->Write(0, strtoupper($vehicleRequest->asignatory->name));

            $pdf->SetFont('Arial', '', 9);
            $pdf->SetXY(145, 238.8); // Date Updated
            $pdf->Write(0, $vehicleRequest->updated_at->format('Y-m-d'));

            $pdf->SetXY(145, 242.6); // Time Updated
            $pdf->Write(0, $vehicleRequest->updated_at->format('H:i:s'));

            if($vehicleRequest->FormStatus === 'Approved'){
                $pdf->SetXY(26.4, 261.8); // Availability = true
                $pdf->Write(0, 'x');

                $pdf->SetXY(106.7, 261.8); // Form Status = Approved
                $pdf->Write(0, 'x');
            } else if ($vehicleRequest->FormStatus === 'Disapproved'){
                $pdf->SetXY(56.2, 261.8); // Availability = false
                $pdf->Write(0, 'x');

                $pdf->SetXY(106.7, 266.2); // Form Status = Disapproved
                $pdf->Write(0, 'x');
            } else {
                $pdf->SetXY(26.4, 261.8); // Availability = N/A
                $pdf->Write(0, '');

                $pdf->SetXY(106.7, 266.2); // Form Status = N/A
                $pdf->Write(0, '');
            }

            $pdf->SetFont('Arial', '', 10);

            $pdf->SetXY(35, 285); // ASignatory
            $pdf->Write(0, strtoupper($vehicleRequest->asignatory->name));

            return $pdf->Output('S');
        } catch (Throwable $e) {
            Log::error('Error in generateAuthoritytoTravelPDF method.', [
                'exception' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'An error occurred while generating the PDF.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function generateVRequestFormAPDF(Request $request, $VRequestID)
    {
        try {
            $validated = $request->validate([
                'VRequestID' => 'string|exists:vehicle_requests,VRequestID',
            ]);
            Log::info('Validation successful.', ['validated' => $validated]);

            $vehicleRequest = VehicleRequest::findOrFail($VRequestID);

            $pdf = new Fpdi();
            $pdf->AddPage('P', [215.9, 330.2]);
            $sourceFile = public_path('storage/uploads/templates/vehicle_forms/empty/VR_Request-Of-Use-FORM-A.pdf');

            if (!file_exists($sourceFile)) {
                Log::error('Source file does not exist.', ['sourceFile' => $sourceFile]);
                return response()->json(['error' => 'Source file does not exist.'], 500);
            }

            $pdf->setSourceFile($sourceFile);
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx, 0, 0, 210);

            // Set text color and font
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 10);

            $pdf->SetXY(65, 55.8); // Date Start
            $pdf->Write(0, $vehicleRequest->date_start);

            $pdf->SetXY(85, 55.8); // Date End
            $pdf->Write(0, ' -  ' . $vehicleRequest->date_end);

            $pdf->SetXY(170, 43.5); // Control Number
            $pdf->Write(0, $vehicleRequest->VRequestID);

            $pdf->SetXY(175, 55.7); // Plate Number
            $pdf->Write(0, $vehicleRequest->vehicle->PlateNo);

            $pdf->SetXY(65, 60); // Driver Name
            $pdf->Write(0, strtoupper($vehicleRequest->driver->DriverName));

            $vehicleController = new VehicleController(); // Passengers
            $passengers = $vehicleController->getPassengersByRequestId($VRequestID);
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetXY(65, 64);
            foreach ($passengers as $passenger) {
                $pdf->Write(0, $passenger->OfficeName . ', ' . $passenger->EmployeeName) . '; ';
                $pdf->Ln();
            }

            $pdf->SetFont('Arial', '', 10);

            $pdf->SetXY(65, 68); // Destination
            $pdf->Write(0, $vehicleRequest->Destination);

            $pdf->SetXY(65, 72); // Purpose
            if (is_null($vehicleRequest->purpose_request)) {
                $pdf->Write(0, $vehicleRequest->PurposeOthers);
            } else {
                $pdf->Write(0, $vehicleRequest->purpose_request->purpose);
            }

            $pdf->SetXY(20, 83); // ReceivedBy
            $pdf->Write(0, strtoupper($vehicleRequest->receivedBy->name));


            // Return the PDF content as a string
            return $pdf->Output('S');
        } catch (Throwable $e) {
            Log::error('Error in generateAuthoritytoTravelPDF method.', [
                'exception' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'An error occurred while generating the PDF.',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function generateTravelOrderPDF(Request $request, $VRequestID)
    {
        try {
            $validated = $request->validate([
                'VRequestID' => 'string|exists:vehicle_requests,VRequestID',
            ]);
            Log::info('Validation successful.', ['validated' => $validated]);

            $vehicleRequest = VehicleRequest::findOrFail($VRequestID);

            $pdf = new Fpdi();
            $pdf->AddPage();
            $sourceFile = public_path('storage/uploads/templates/vehicle_forms/empty/VR_Travel-Order-Form.pdf');

            if (!file_exists($sourceFile)) {
                Log::error('Source file does not exist.', ['sourceFile' => $sourceFile]);
                return response()->json(['error' => 'Source file does not exist.'], 500);
            }

            $pdf->setSourceFile($sourceFile);
            $tplIdx = $pdf->importPage(1);
            $pdf->useTemplate($tplIdx, 0, 0, 210);

            // Set text color and font
            $pdf->SetTextColor(0, 0, 0);
            $pdf->SetFont('Arial', '', 10);

            // Control Number
            $pdf->SetXY(155, 53); // Control Number
            $pdf->Write(0, $vehicleRequest->VRequestID);

            // Date
            $pdf->SetXY(155, 61); // Date
            $pdf->Write(0, $vehicleRequest->created_at->format('Y-m-d'));

            // Driver 1
            $pdf->SetXY(51, 75.7); // Driver 1
            $pdf->Write(0, strtoupper($vehicleRequest->driver->DriverName));

            // Vehicle Type 1
            $pdf->SetXY(125, 91); // Vehicle Type
            $pdf->Write(0, $vehicleRequest->vehicle->VehicleType . '  ' . $vehicleRequest->vehicle->PlateNo);

            // Destination 1
            $pdf->SetXY(110, 97); // Destination 1
            $pdf->Write(0, $vehicleRequest->Destination);

            $pdf->SetFont('Arial', '', 9);

            $pdf->SetXY(25, 104); // Date Start 1
            $pdf->Write(0, $vehicleRequest->date_start . ' - ' . $vehicleRequest->date_end);

            $pdf->SetFont('Arial', '', 10);

            $pdf->SetXY(25, 110); // Purpose 1
            if (is_null($vehicleRequest->purpose_request)) {
                $pdf->Write(0, $vehicleRequest->PurposeOthers);
            } else {
                $pdf->Write(0, $vehicleRequest->purpose_request->purpose);
            }

            // Driver 2
            $pdf->SetXY(80, 196); // Driver 2
            $pdf->Write(0, strtoupper($vehicleRequest->driver->DriverName));

            // Destination 2
            $pdf->SetXY(100, 201); // Destination 2
            $pdf->Write(0, $vehicleRequest->Destination);

            $pdf->SetXY(25, 206); // Date Start 2
            $pdf->Write(0, $vehicleRequest->date_start . ' - ' . $vehicleRequest->date_end);

            $pdf->SetXY(25, 211); // Purpose 2
            if (is_null($vehicleRequest->purpose_request)) {
                $pdf->Write(0, $vehicleRequest->PurposeOthers);
            } else {
                $pdf->Write(0, $vehicleRequest->purpose_request->purpose);
            }

            return $pdf->Output('S');
        } catch (Throwable $e) {
            Log::error('Error in generateAuthoritytoTravelPDF method.', [
                'exception' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'error' => 'An error occurred while generating the PDF.',
                'message' => $e->getMessage()
            ], 500);
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

        // Use startOfDay and endOfDay to cover the full day range
        $validated['startDate'] = Carbon::parse($validated['startDate'])->startOfDay();
        $validated['endDate'] = Carbon::parse($validated['endDate'])->endOfDay();

        // Debugging log for validation
        Log::info('Query parameters:', [
            'startDate' => $validated['startDate'],
            'endDate' => $validated['endDate'],
        ]);

        // Query to get vehicle requests within the specified date range using 'created_at'
        $vehicleRequests = VehicleRequest::whereBetween('created_at', [$validated['startDate'], $validated['endDate']])
            ->get();

        // Check if no results are found
        if ($vehicleRequests->isEmpty()) {
            Log::error('No vehicle requests found within the specified date range.');
            return response()->json(['error' => 'No vehicle requests found within the specified date range.'], 404);
        }

        // PDF generation logic
        $pdf = new FPDI();
        $pdf->AddPage();
        $sourceFile = public_path('storage/uploads/templates/vehicle_forms/VR_dailydispatchreport.pdf');

        // Check if the source file exists
        if (!file_exists($sourceFile)) {
            Log::error('Source file does not exist.', ['sourceFile' => $sourceFile]);
            return response()->json(['error' => 'Source file does not exist.'], 500);
        }

        $pdf->setSourceFile($sourceFile);
        $tplIdx = $pdf->importPage(1);
        $pdf->useTemplate($tplIdx, 0, 0, 210);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Arial', '', 10);

        // Example of filling in the PDF with vehicle request data
        $yPosition = 50; // Starting Y position for the data
        foreach ($vehicleRequests as $request) {
            $pdf->SetXY(10, $yPosition);
            $pdf->Write(0, $request->VRequestID);
            $pdf->SetXY(30, $yPosition);
            $pdf->Write(0, $request->created_at->format('Y-m-d')); // Show only the date part
            $pdf->SetXY(60, $yPosition);
            $pdf->Write(0, $request->Destination);
            $pdf->SetXY(90, $yPosition);
            $pdf->Write(0, $request->Purpose);
            $pdf->SetXY(120, $yPosition);
            $pdf->Write(0, $request->office->OfficeName);
            $yPosition += 10; // Move to the next line
        }

        // Output the PDF to the browser
        $pdf->Output('I', 'VR_DailyDispatchReport.pdf');
    }
}
