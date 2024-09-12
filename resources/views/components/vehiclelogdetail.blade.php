<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Edit Form</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
        }

        .form-container {
            width: 60em;
            padding: 35px;
            border: 1px solid #ddd;
            border-radius: 15px;
            margin: 5em auto;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
            margin-top: 10px;
        }

        h1 {
            font-size: 30px;
            text-align: center;
            margin-bottom: 10px;
            font-weight: 500;
        }

        p {
            margin-bottom: 15px;
            font-style: italic;
        }

        .form-body {
            display: flex;
            flex-direction: column;
        }

        .input-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            margin-bottom: 16px;
            width: 100%;
        }

        .input-field {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
            width: 48%;
            margin-bottom: 10px;
        }

        .input-field label {
            margin-right: 5px;
            width: 130px; /* Adjust width to your preference */
            text-align: left;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        select {
            width: calc(100% - 90px); /* Adjust input width accordingly */
            padding: 10px;
            border: 1px solid rgba(60, 54, 51, 0.5);
            border-radius: 15px;
            box-sizing: border-box;
        }

        input[type="date"] {
            margin-left: 30px;
        }

        input[type="time"] {
            width: 25%;
            margin-left: -10px;
            padding: 8px; /* Ensure consistent padding */
            box-sizing: border-box; /* Ensure padding and border are included in the element's total width and height */
        }

        #date_end {
            margin-left: 20px;
            width: 65%;
        }

        #date_start {
            width: 55%;
            margin-left: 80px;
        }

        .below-label1 {
            display: block;
            margin-top: 5px;
            margin-left: 7.2em;
        }

        .below-label2 {
            display: block;
            margin-top: 5px;
            margin-left: 5em;
        }

        .file-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 16px;
            border: 2px dashed #5b21b6;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 16px;
            width: 100%;
        }

        .submit-btn {
            background-color: #354e7d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px; /* Add margin to the right side of the update button */
        }

        .cancel-btn {
            background-color: #ff4d4d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
        }

        .form-footer {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        #signature-preview {
            margin-top: 15px;
            max-width: 100px;
            max-height: 100px;
            display: none;
        }

        .button-container {
            display: flex;
            align-items: center;
        }

        .row-dispatch {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
            width: 100%;
            gap: 10px;
        }

        .row-dispatch .inline {
            flex: 1 1 calc(33% - 20px);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
        }

        .row-dispatch .inline label {
            /* margin-right: 10px; */
            width: 120px; /* Align with other fields */
            text-align: left;
        }

        .row-dispatch .inline input,
        .row-dispatch .inline select {
            width: calc(100% - 10px); /* Match other input fields */
            padding: 10px;
        }

        #dispatcher-form, #admin-service-form {
            display: none;
            margin-top: 10px;
            padding: 20px;
            /* border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9; */
        }

        .dropdown-button-container {
            position: relative;
            width: 100%;
            text-align: left;
            margin-bottom: 10px;
        }

        .dropdown-button {
            width: auto;
            padding: 10px 20px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-size: 16px;
            border-bottom: 1px solid #354e7d;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 1px 0 0 #ccc;
        }

        .dropdown-button::after {
            content: "\25BC"; /* Unicode character for a caret symbol */
            font-size: 10px;
            margin-left: 10px;
        }

        @media (max-width: 768px) {
            .form-container {
                width: 90%;
                padding: 20px;
                margin: 2em auto;
                margin-top: 5px;
            }

            .input-group,
            .input-field {
                width: 100%;
            }

            .input-field label {
                width: 100%;
                text-align: left;
                margin-bottom: 5px;
            }

            .input-group,
            .input-field,
            .row-dispatch {
                width: 100%;
                flex-direction: column;
                align-items: flex-start;
            }

            input[type="text"],
            input[type="date"],
            input[type="time"],
            select {
                width: 100%;
            }

            .row-dispatch .inline {
                width: 100%;
            }

            #date_end, #date_start {
                width: 100%; /* Adjust width to account for the label width and margin */
                padding: 10px;
                border: 1px solid rgba(60, 54, 51, 0.5);
                border-radius: 15px;
                box-sizing: border-box;
                margin-left: 0;
            }

            .below-label1 {
                display: block;
                margin-top: 5px;
                margin-left: 0;
            }

            .below-label2 {
                display: block;
                margin-top: 5px;
                margin-left: 0;
            }

            input[type="time"] {
                width: 45%;
                margin-left: 0;
                padding: 8px; /* Ensure consistent padding */
                box-sizing: border-box; /* Ensure padding and border are included in the element's total width and height */
            }
        }
        .icon{
            display: flex;
            justify-content: flex-end;
            margin-top: -1.5em;
            margin-right: -1em;
        }
        .bi {
        font-size: 2em;
        }
        .bi-dl{
            font-size: 1.3em;
        }
    </style>
</head>
<body>
<div class="form-container">
<div class="icon">
        @if (
            (in_array($requestLogData->FormStatus, ['Approved', 'Not Approved']) ||
            in_array($requestLogData->EventStatus, ['Finished', 'Cancelled', '-']))
        )
            <button class="float-right" id="downloadButton">
                <i class="bi-dl bi-download"></i>
            </button>

            <script>
                document.getElementById('downloadButton').addEventListener('click', function() {
                    window.location.href = "{{ route('downloadFinalVRequestPDF', $requestLogData->VRequestID) }}";
                });
            </script>
       @endif
        <button class="btn float-right" type="button" onclick="history.back()">
            <i class="bi bi-x"></i>
        </button>
    </div>
    <h1>View Details for Request For Use of Vehicle</h1>
    <p>(Note: Request for use of vehicle shall be made at least (2) days from the intended date use. Failure to use the
        vehicle at the given date/time forfeits one’s right to use the vehicle assigned.)</p>
    <div class="form-body">
        <form action="/vehicle-request" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="VRequestID" value="{{ $requestLogData-> VRequestID }}">
            <div class="input-group">
                <div class="input-field">
                    <label for="officeName">Requesting Office</label>
                    <input type="text" id="officeName" name="officeName"
                           value="{{ $requestLogData->office->OfficeName ?? '' }}" placeholder="-" readonly>
                </div>
                <div class="input-field">
                    <label>Purpose</label>
                    <input type="text" id="purpose" name="purpose"
                           value="{{ optional(App\Models\PurposeRequest::find($requestLogData->PurposeID))->purpose ?? $requestLogData->PurposeOthers }}"
                           placeholder="-"
                           readonly>
                </div>
            </div>
            <div class="input-group">
                <div class="input-field">
                    <label>Destination</label>
                    <input type="text" name="Destination" placeholder="Enter Place"
                           value="{{ $requestLogData->Destination }}" readonly/>
                </div>
                <div class="input-field">
                    <label>Passenger/s</label>
                    <ul>
                        @if(isset($passengers) && $passengers->isNotEmpty())
                            <p>Total Passengers: {{ $passengers->count() }}</p>
                            @foreach($passengers as $passenger)
                                <li id="passengers" name="passengers[]"
                                    value="{{ $passenger->EmployeeID }}">{{ $passenger->EmployeeName }}</li>
                            @endforeach
                        @else
                            <li>No passengers found</li>
                        @endif
                    </ul>
                </div>
            </div>
            <div id="passenger-container">
                <!-- New passenger fields will be appended here -->
            </div>
            <div id="date-time-container">
                <div class="input-group datetime-group">
                    <div class="input-field">
                        <label>Date</label>
                        <div class="date-field">
                            <input type="date" id="date_start" name="date_start[]"
                                   value="{{ $requestLogData->date_start }}" readonly/>
                            <label for="date_start" class="below-label1">Start</label>
                        </div>
                        <div class="date-field">
                            <input type="date" id="date_end" name="date_end[]" value="{{ $requestLogData->date_end }}"
                                   readonly/>
                            <label for="date_end" class="below-label2">End</label>
                        </div>
                    </div>
                    <div class="input-field">
                        <label>Time</label>
                        <input type="time" name="time_start[]" value="{{ $requestLogData->time_start }}" readonly/>
                    </div>
                </div>
            </div>
            <div class="input-group">
                <div class="input-field">
                    <label>Requester Name</label>
                    <input type="text" name="RequesterName" placeholder="Enter Name"
                           value="{{ $requestLogData->RequesterName }}" required/>
                </div>
                <div class="input-field">
                    <label>Requester Email</label>
                    <input type="text" name="RequesterEmail" placeholder="Enter Email"
                           value="{{ $requestLogData->RequesterEmail }}" readonly/>
                </div>
            </div>
            <div class="input-group">
                <div class="input-field">
                    <label>Contact No.</label>
                    <input type="text" name="RequesterContact" placeholder="Enter No."
                           value="{{ $requestLogData->RequesterContact }}" readonly/>
                </div>
                <div class="input-field">
                    <label for="RequesterSignature">E-Signature</label>
                    <div class="file-upload">
                        <img id="signature-preview"
                             src="{{ $requestLogData->RequesterSignature ? asset('storage/' . $requestLogData->RequesterSignature) : '' }}"
                             alt="Signature Preview"
                             style="{{ $requestLogData->RequesterSignature ? 'display: block;' : 'display: none;' }}"
                             readonly>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div id="app">
        <!-- Dispatcher Section -->
        <div class="dropdown-button-container">
            <button class="dropdown-button" onclick="toggleDispatcher()">
                <span>TO BE FILLED BY DISPATCHER</span>
            </button>
        </div>
        <div id="dispatcher-form">
        <form action="/vehicle-request" method="POST" enctype="multipart/form-data">
                <div class="row-dispatch">
                    <div class="inline">
                        <label for="name">Driver Name</label>
                        <input type="text" id="tables" name="person" value="{{ $requestLogData->driver->DriverName }}"
                               placeholder="Enter Name" readonly>
                    </div>
                    <div class="inline">
                        <label for="contact">Contact No.</label>
                        <input type="text" id="contact" name="contact" value="{{ $requestLogData->driver->ContactNo }}"
                               placeholder="Enter No." readonly>
                    </div>
                    <div class="inline">
                        <label for="email">Email</label>
                        <input type="text" id="e-mail" name="e-mail" value="{{ $requestLogData->driver->DriverEmail }}"
                               placeholder="Enter Email" readonly>
                    </div>
                </div>
                <div class="row-dispatch">
                    <div class="inline">
                        <label for="VName">Vehicle Type</label>
                        <input type="text" id="VName" name="VName" value="{{ $requestLogData->vehicle->VehicleType  ?? '-' }}"
                               placeholder="Enter Vehicle Type" readonly>
                    </div>
                    <div class="inline">
                        <label for="plate">Plate No.</label>
                        <input type="text" id="plate" name="plate" value="{{ $requestLogData->vehicle->PlateNo  ?? '-'}}"
                               placeholder="Enter No." readonly>
                    </div>
                    <div class="inline">
                        <label for="CkName">Received by</label>
                        <input type="text" id="CkName" name="CkName" value="{{ $requestLogData->receivedBy->name  ?? '-' }}"
                               placeholder="Enter Office" readonly>
                    </div>
                </div>
                <div class="row-dispatch">
                    <div class="inline">
                        <label for="date">Date Start</label>
                        <input type="date" id="date" name="date[]" value="{{ $requestLogData-> date_start ?? '-'}}" readonly>
                    </div>
                    <div class="inline">
                        <label for="time">Time</label>
                        <input type="time" id="dtime" name="time[]" value="{{ $requestLogData->time_start }}" readonly>
                    </div>
                    <div class="inline">
                        <label for="remark">Remarks</label>
                        <input type="text" id="remark" name="remark" value="{{ $requestLogData->Remarks  ?? '-' }}" readonly>
                    </div>
                </div>
            </form>
        </div>

        <!-- Administrative Service Section -->
        <div class="dropdown-button-container">
            <button class="dropdown-button" onclick="toggleAdminService()">
                <span>TO BE FILLED BY ADMINISTRATIVE SERVICE - GENERAL SERVICES/DIVISION/SECTION</span>
            </button>
        </div>
        <div id="admin-service-form">
             <form action="/vehicle-request" method="POST" enctype="multipart/form-data">
                <div class="row-dispatch">
{{--                    <div class="inline">--}}
{{--                        <label for="availability">Availability</label>--}}
{{--                        <input type="text" id="availability" name="availability"--}}
{{--                               value="{{ $requestLogData->VAvailability }}" placeholder="Enter Availability" readonly>--}}
{{--                    </div>--}}
                    <div class="inline">
                        <label for="formStatus">Form Status</label>
                        <input type="text" id="formStatus" name="formStatus" value="{{ $requestLogData->FormStatus }}" placeholder="Enter Form Status" readonly>
                    </div>
                    <div class="inline">
                        <label for="eventStatus">Event Status</label>
                        <input type="text" id="eventStatus" name="eventStatus" value="{{ $requestLogData->EventStatus }}" placeholder="Enter Event Status" readonly>
                    </div>
                </div>
                <div class="row-dispatch">
                    <div class="inline">
                        <label for="VName">Approving Authority</label>
                        <input type="text" id="VName" name="VName" value="{{ $requestLogData->AAuth->AAName ?? '-' }}"
                               placeholder="Enter Authority" readonly>
                    </div>
                    <div class="inline">
                        <label for="VName">Approving Authority Position</label>
                        <input type="text" id="VName" name="VName" value="{{ $requestLogData->AAuth->AAPosition  ?? '-' }}" placeholder="Enter Position" readonly>
                    </div>
                </div>
                <div class="row-dispatch">
                    <div class="inline">
                        <label for="VName">SO Approving Authority</label>
                        <input type="text" id="VName" name="VName" value="{{ $requestLogData->SOAuth->SOName ?? '-' }}" placeholder="Enter Authority" readonly>
                    </div>
                    <div class="inline">
                        <label for="VName">SO Approving Authority Position</label>
                        <input type="text" id="VName" name="VName"
                               value="{{ $requestLogData->SOAuth->SOPosition  ?? '-' }}" placeholder="Enter Position" readonly>
                    </div>
                </div>
                <div class="row-dispatch">
                    <div class="inline">
                        <label for="VName">Authorized Signatory</label>
                        <input type="text" id="VName" name="VName" value="{{ $requestLogData->asignatory->name ?? '-'}}"
                               placeholder="Enter Signatory" readonly>
                    </div>
                    <div class="inline">
{{--                        <label for="e-signature">File Upload</label>--}}
{{--                        <div class="file-upload" style="pointer-events: none;">--}}
{{--                            <input type="file" id="e-signature" name="e-signature" style="display: none;" disabled>--}}
{{--                            <div class="e-signature-text">--}}
{{--                                Click to Upload Certificate of Non-Availability<br>Maximum file size: 31.46MB--}}
{{--                            </div>--}}
{{--                            <img id="signature-preview" alt="Signature Preview">--}}
{{--                        </div>--}}
                    </div>
                </div>
            </form>
        </div>
        <div class="form-footer">
            <button class="cancel-btn" type="button" onclick="cancelForm()" hidden>Back</button>
        </div>
    </div>
</div>
</body>
</html>
<script>
    function cancelForm() {
        window.location.href = '/VehicleLogs';
    }

    // Attach the cancelForm function to the cancel button
    document.querySelector('.btn').addEventListener('click', cancelForm);


    function showDispatcher() {
        var dispatcherForm = document.getElementById("dispatcher-form");
        dispatcherForm.style.display = "block";
    }

    function showAdminService() {
        var adminServiceForm = document.getElementById("admin-service-form");
        adminServiceForm.style.display = "block";
    }

    document.addEventListener("DOMContentLoaded", function () {
        showDispatcher();
        showAdminService();
    });
</script>
