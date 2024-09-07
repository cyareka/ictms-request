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

            $pdf->SetFont('Helvetica', '', 10);
            $pdf->SetTextColor(0, 0, 0);

            // TOP PART
            $pdf->SetXY(160, 60); // Date Requested
            $pdf->Write(0, $conferenceRequest->created_at);

            $pdf->SetXY(90, 68); // Requester Name
            $pdf->Write(0, $conferenceRequest->RequesterName);

            $pdf->SetXY(90, 77);
            if (is_null($conferenceRequest->purposeRequest)) {
                // Purpose
                $pdf->Write(0, $conferenceRequest->PurposeOthers);
            } else {
                // Purpose
                $pdf->Write(0, $conferenceRequest->purposeRequest->PurposeID);
            }

            $pdf->SetXY(90, 85); // Date Needed
            $pdf->Write(0, $conferenceRequest->date_start);

            $pdf->SetXY(90, 94); // Time Needed
            $pdf->Write(0, $conferenceRequest->time_start);

            $pdf->SetXY(90, 102); // Number of Persons
            $pdf->Write(0, $conferenceRequest->npersons);

            // TODO - focalpid is null when focalperson is fpothers
//        $pdf->SetXY(90, 110); // Focal Person
//        $pdf->Write(0, $conferenceRequest->focal_person->FocalPID);

            // Conference Room 1 (Magiting)
            if ($conferenceRequest->conferenceRoom->CRoomName === 'Magiting') {
                $pdf->SetXY(70.4, 128.5);
            } else {
                $pdf->SetXY(70.4, 138.5);
            }
            $pdf->Write(0, 'x');

            if (!is_null($conferenceRequest->otherFacilities)) {
                $pdf->SetXY(90, 147); // Other Facilities
                $pdf->Write(0, 'x');

                $pdf->SetXY(90, 147); // Other Facilities
                $pdf->Write(0, $conferenceRequest->otherFacilities);
            }

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
}
