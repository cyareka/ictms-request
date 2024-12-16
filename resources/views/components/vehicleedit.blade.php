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
            margin-left: 20px;
        }

        .cancel-btn {
            background-color: #E1C16E;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 5px; /* Add margin to the left side of the cancel button */

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

        #certfile-upload {
            display: none;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        #certificate-preview-label {
            display: block;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        #certificate-preview-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50px;
            text-align: center;
        }

        #default-text {
            font-size: 14px;
            color: #000;
        }

        #certificate-preview {
            font-size: 14px;
            color: #333;
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

            /* Ensure the file upload section is clearly defined */
            .file-upload {
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 16px;
                border: 2px dashed #5b21b6;
                border-radius: 15px;
                cursor: pointer;
                margin-bottom: 16px;
                width: 100%;
                background-color: #ffffff; /* Light background for better visibility */
                text-align: center;
                transition: background-color 0.3s ease; /* Add a transition effect */
            }

            .file-upload:hover {
                background-color: #f0f0f0; /* Slightly darker on hover for feedback */
            }

            #certfile-upload {
                display: none;
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                opacity: 0;
                cursor: pointer;
            }

            #certificate-preview-label {
                display: block;
                width: 100%;
                height: 100%;
                cursor: pointer;
            }

            #certificate-preview-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                height: 50px;
                text-align: center;
                color: #5b21b6; /* Text color matching the border */
            }

            #default-text {
                font-size: 14px;
                color: #5b21b6; /* Text color to match the border */
            }

            #certificate-preview {
                font-size: 14px;
                color: #333;
                margin-top: 10px; /* Space between the default text and preview text */
            }


        }

        .fa-duotone {
            font-size: 1.5em;
        }

        .btn {
            margin-top: -1.2em;
            margin-right: -1.2em;
        }
    </style>
</head>
<body>
@if ($errors->any())
    <script>
        let errorMessages = [];
        @foreach ($errors->all() as $error)
        errorMessages.push("{{ $error }}");
        @endforeach
        alert("Updating request failed. Please correct the following errors:\n\n" + errorMessages.join("\n"));
    </script>
@endif

@if(session('error'))
    <script>
        alert(" {{ session('error') }}");
    </script>
@endif

@if(session('success'))
    <script>
        alert(" {{ session('success') }}");
    </script>
@endif
<div class="form-container">
    <button class="btn float-right">
        <i class="fa-duotone fa-solid fa-xmark"></i>
    </button>

    <h1>Request For Use of Vehicle</h1>
    <p>(Note: Request for use of vehicle shall be made at least (2) days from the intended date use. Failure to use the
        vehicle at the given date/time forfeits one’s right to use the vehicle assigned.)</p>
    <div class="form-body">
        <form action="{{ url('/vehicle-request/update/' . $requestData->VRequestID) }}" method="POST"
              enctype="multipart/form-data">
            @csrf

            <input type="hidden" name="VRequestID" value="{{ $requestData-> VRequestID }}">
            <div class="input-group">
                <div class="input-field">
                    <label for="officeName">Requesting Office</label>
                    <input type="text" id="officeName" name="officeName"
                           value="{{ $requestData->office->OfficeName ?? '' }}" placeholder="-" readonly>
                </div>
                <div class="input-field">
                    <label>Purpose</label>
                    <input type="text" id="purpose" name="purpose"
                           value="{{ optional(App\Models\PurposeRequest::find($requestData->PurposeID))->purpose ?? $requestData->PurposeOthers }}"
                           placeholder="-"
                           readonly>
                </div>
            </div>
            <div class="input-group">
                <div class="input-field">
                    <label>Destination</label>
                    <input type="text" name="Destination" placeholder="Enter Place"
                           value="{{ $requestData->Destination }}" readonly/>
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
                                   value="{{ $requestData->date_start }}" readonly/>
                            <label for="date_start" class="below-label1">Start</label>
                        </div>
                        <div class="date-field">
                            <input type="date" id="date_end" name="date_end[]" value="{{ $requestData->date_end }}"
                                   readonly/>
                            <label for="date_end" class="below-label2">End</label>
                        </div>
                    </div>
                    <div class="input-field">
                        <label>Time</label>
                        <input type="time" name="time_start[]" value="{{ $requestData->time_start }}" readonly/>
                    </div>
                </div>
            </div>
            <div class="input-group">
                <div class="input-field">
                    <label>Requester Name</label>
                    <input type="text" name="RequesterName" placeholder="Enter Name"
                           value="{{ $requestData->RequesterName }}" required/>
                </div>
                <div class="input-field">
                    <label>Requester Email</label>
                    <input type="text" name="RequesterEmail" placeholder="Enter Email"
                           value="{{ $requestData->RequesterEmail }}" readonly/>
                </div>
            </div>
            <div class="input-group">
                <div class="input-field">
                    <label>Contact No.</label>
                    <input type="text" name="RequesterContact" placeholder="Enter No."
                           value="{{ $requestData->RequesterContact }}" readonly/>
                </div>
                <div class="input-field">
                    <label for="RequesterSignature">E-Signature</label>
                    <div class="file-upload">
                        <img id="signature-preview"
                             src="{{ $requestData->RequesterSignature ? asset('storage/' . $requestData->RequesterSignature) : '' }}"
                             alt="Signature Preview"
                             style="{{ $requestData->RequesterSignature ? 'display: block;' : 'display: none;' }}"
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
            <form id="dispatcherForm" action="{{ url('/vehicle-request/update/' . $requestData->VRequestID) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf

                <div class="row-dispatch">
                    <div class="inline">
                        <label for="driver">Driver Name</label>
                        <select id="DriverID" name="DriverID" required>
                            <option disabled {{ !$requestData->DriverID ? 'selected' : '' }}>Select Driver</option>
                            @foreach(App\Models\Driver::all() as $driver)
                                <option value="{{ $driver->DriverID }}" data-contact="{{ $driver->ContactNo }}"
                                        data-email="{{ $driver->DriverEmail }}" {{ $requestData->DriverID == $driver->DriverID ? 'selected' : '' }}>
                                    {{ $driver->DriverName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="inline">
                        <label for="ContactNo">Contact No.</label>
                        <input type="text" id="ContactNo" name="ContactNo" placeholder="N/A" readonly>
                    </div>
                    <div class="inline">
                        <label for="email">Email</label>
                        <input type="text" id="DriverEmail" name="DriverEmail" placeholder="N/A" readonly>
                    </div>
                </div>
                <div class="row-dispatch">
                    <div class="inline">
                        <label for="vehicle">Vehicle Type</label>
                        <select id="VehicleID" name="VehicleID">
                            <option disabled {{ !$requestData->VehicleID ? 'selected' : '' }}>Select Vehicle</option>
                            @foreach(App\Models\Vehicle::all() as $Vehicle)
                                <option value="{{ $Vehicle->VehicleID }}" data-plate="{{ $Vehicle->PlateNo }}"
                                        data-capacity="{{ $Vehicle->Capacity }}">
                                    {{ $Vehicle->VehicleType }} - Capacity: {{ $Vehicle->Capacity }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="inline">
                        <label for="PlateNo">Plate No.</label>
                        <input type="text" id="PlateNo" name="PlateNo" placeholder="N/A" readonly>
                    </div>
                    <div class="inline">
                        {{-- depends on admin logged in --}}
                        <label for="ReceivedBy">Received by</label>
                        <input type="text" id="ReceivedBy" name="ReceivedBy" value="{{ Auth::user()->name }}" readonly>
                    </div>
                </div>
                <div class="row-dispatch">
                    <div class="inline">
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date[]" readonly>
                    </div>
                    <div class="inline">
                        <label for="time">Time</label>
                        <input type="time" id="dtime" name="time[]" readonly>
                    </div>
                    <div class="inline">
                        <label for="remark">Remarks</label>
                        <input type="text" id="remark" name="remark" value="{{ $requestData->Remarks }}" placeholder="Enter Remark" autocapitalize="sentences">
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
            <form id="adminServiceForm" action="{{ url('/vehicle-request/update/' . $requestData->VRequestID) }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                <div class="row-dispatch">
                    {{--                    <div class="inline">--}}
                    {{--                        <label for="availability">Availability</label>--}}
                    {{--                        <input type="text" id="availability" name="availability" placeholder="-" value="{{ $requestData->VAvailability === 1 ? 'Available' : ($requestData->VAvailability === 0 ? 'Not Available' : '-') }}" readonly>--}}
                    {{--                    </div>--}}
                    <div class="inline">
                        <label for="FormStatus">Form Status</label>
                        <select id="FormStatus" name="FormStatus">
                            @if($requestData->FormStatus == 'Pending')
                                <option value="Pending" {{ $requestData->FormStatus == 'Pending' ? 'selected' : '' }} hidden>
                                    Pending
                                </option>
                                <option value="For Approval" {{ $requestData->FormStatus == 'For Approval' ? 'selected' : '' }}>
                                    For Approval
                                </option>
                            @elseif ($requestData->CAvailability == '0')
                                <option value="Not Approved" {{ $requestData->FormStatus == 'Not Approved' ? 'selected' : '' }}>
                                    Not Approved
                                </option>
                            @elseif ($requestData->FormStatus == 'For Approval')
                                <option value="For Approval" {{ $requestData->FormStatus == 'For Approval' ? 'selected' : '' }} hidden>
                                    For Approval
                                </option>
                                <option value="Approved" {{ $requestData->FormStatus == 'Approved' ? 'selected' : '' }} style="display: none;">Approved</option>
                                <option value="Not Approved" {{ $requestData->FormStatus == 'Not Approved' ? 'selected' : '' }} style="display: none;">Not Approved</option>
                            @elseif($requestData->FormStatus == 'Approved')
                                <option value="Approved" {{ $requestData->FormStatus == 'Approved' ? 'selected' : '' }}>
                                    Approved
                                </option>
                            @else
                                <option value="Not Approved" {{ $requestData->FormStatus == 'Not Approved' ? 'selected' : '' }}>
                                    Not Approved
                                </option>
                            @endif
                        </select>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const formStatus = document.getElementById('FormStatus');
                                let previousValue = formStatus.value;

                                formStatus.addEventListener('change', function() {
                                    if (formStatus.value === 'Approved' || formStatus.value === 'Not Approved') {
                                        const confirmed = confirm('Changing the form status should be final. Do you want to proceed?');
                                        if (!confirmed) {
                                            formStatus.value = 'For Approval';
                                            formStatus.dataset.previousValue = 'For Approval';
                                            document.getElementById('file-upload-section').style.display = 'none';
                                        } else {
                                            formStatus.dataset.previousValue = formStatus.value;
                                        }
                                    } else {
                                        formStatus.dataset.previousValue = formStatus.value;
                                    }
                                });

                                updateFormStatusOptions();
                            });
                        </script>
                    </div>
                    <div class="inline">
                        <label for="EventStatus">Event Status</label>
                        <input type="text" id="EventStatus" name="EventStatus" value="{{ $requestData->EventStatus }}"
                               readonly>
                    </div>
                </div>
                <div class="row-dispatch">
                    <div class="inline">
                        <label for="AAuth">Approving Authority</label>
                        <select id="AAuth" name="AAuth" required>
                            <option disabled {{ !$requestData->AAID ? 'selected' : '' }}>Select Authority</option>
                            @foreach(App\Models\AAuthority::all() as $AAuth)
                                <option value="{{ $AAuth->AAID }}"
                                        data-position="{{ $AAuth->AAPosition }}"
                                    {{ $requestData->AAID == $AAuth->AAID ? 'selected' : '' }}>
                                    {{ $AAuth->AAName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="inline">
                        <label for="AAPosition">Approving Authority Position</label>
                        <input type="text" id="AAPosition" name="AAPosition" value="{{ $AAuth->AAPosition }}" readonly>
                    </div>
                </div>
                <div class="row-dispatch">
                    <div class="inline">
                        <label for="SOAuthority">SO Approving Authority</label>
                        <select id="SOAuth" name="SOAuth" required>
                            <option disabled {{ !$requestData->SOID ? 'selected' : '' }}>Select Authority</option>
                            @foreach(App\Models\SOAuthority::all() as $SOAuth)
                                <option value="{{ $SOAuth->SOID }}"
                                        data-position="{{ $SOAuth->SOPosition }}"
                                    {{ $requestData->SOID == $SOAuth->SOID ? 'selected' : '' }}>
                                    {{ $SOAuth->SOName }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="inline">
                        <label for="SOPosition">SO Approving Authority Position</label>
                        <input type="text" id="SOPosition" name="SOPosition" value="{{ $SOAuth->SOPosition }}" readonly>
                    </div>
                </div>
                <div class="row-dispatch">
                    <div class="inline">
                        <label for="ASignatory">Authorized Signatory</label>
                        <input type="text" id="ASignatory" name="ASignatory" value="{{ Auth::user()->name }}" readonly>
                    </div>
                    <div id="file-upload-section" style="display: none;">
                        <div class="inline">
                            <label for="certificate-upload">File Upload</label>
                            <div class="file-upload">
                                <label for="certfile-upload" id="certificate-preview-label">
                                    <div id="certificate-preview-container">
                                        <div id="default-text">Uploaded the need file<br> for Certificate of Non-Avalabilty/Request for Use</div>
                                        <div id="certificate-preview"></div>
                                    </div>
                                </label>
                                <input type="file" id="certfile-upload" name="certfile-upload" accept="application/pdf"
                                       onchange="previewCertificate(event)"
                                       style="display: none;">
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
        <div class="form-footer">
            @if($requestData->FormStatus === 'For Approval' || $requestData->FormStatus === 'Approved' || $requestData->FormStatus === 'Not Approved')
                <a href="{{ route('downloadVRequestPDF', $requestData->VRequestID) }}">
                    <button class="cancel-btn" type="button">Download</button>
                </a>
            @endif
            <button class="submit-btn" type="button" onclick="submitAllForms()">Update</button>
        </div>
    </div>
</div>
<script>
    function toggleFileUploadSection() {
        const formStatus = document.getElementById('FormStatus').value;
        const fileUploadSection = document.getElementById('file-upload-section');

        if ((formStatus === 'Approved') || (formStatus === 'Not Approved')) {
            fileUploadSection.style.display = 'block';
        } else {
            fileUploadSection.style.display = 'none';
        }
    }

    document.getElementById('FormStatus').addEventListener('change', toggleFileUploadSection);

    // Call the function on page load to set the initial state
    document.addEventListener('DOMContentLoaded', toggleFileUploadSection);

    document.addEventListener('DOMContentLoaded', function () {
        const vehicleSelect = document.getElementById('VehicleID');
        const passengerCount = {{ $passengers->count() }}; // Assuming you have the passenger count available

        // Filter vehicle options based on capacity
        Array.from(vehicleSelect.options).forEach(option => {
            const vehicleCapacity = parseInt(option.getAttribute('data-capacity'), 10);
            if (vehicleCapacity < passengerCount) {
                option.style.display = 'none';
            }
        });
    });

    function validateForm(form) {
        const requiredFields = form.querySelectorAll('[required]');
        let allFieldsFilled = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                allFieldsFilled = false;
                field.style.borderColor = 'red'; // Highlight the empty field
            } else {
                field.style.borderColor = ''; // Reset the border color if filled
            }
        });

        return allFieldsFilled;
    }

    function submitAllForms() {
        // Get the forms
        const dispatcherForm = document.getElementById('dispatcherForm');
        const adminServiceForm = document.getElementById('adminServiceForm');

        // Check if forms are present
        if (dispatcherForm && adminServiceForm) {
            // Validate both forms
            const isDispatcherFormValid = validateForm(dispatcherForm);
            const isAdminServiceFormValid = validateForm(adminServiceForm);

            if (!isDispatcherFormValid || !isAdminServiceFormValid) {
                alert('Please fill out all required fields.');
                return; // Prevent form submission
            }

            // Create a FormData object for both forms
            const dispatcherFormData = new FormData(dispatcherForm);
            const adminServiceFormData = new FormData(adminServiceForm);

            // Submit the Dispatcher Form
            fetch(dispatcherForm.action, {
                method: 'POST',
                body: dispatcherFormData,
                headers: {
                    'X-CSRF-TOKEN': dispatcherForm.querySelector('input[name="_token"]').value
                }
            })
                .then(response => response.ok ? response.text() : Promise.reject(response))
                .then(() => {
                    // Submit the Administrative Service Form
                    return fetch(adminServiceForm.action, {
                        method: 'POST',
                        body: adminServiceFormData,
                        headers: {
                            'X-CSRF-TOKEN': adminServiceForm.querySelector('input[name="_token"]').value
                        }
                    });
                })
                .then(response => response.ok ? response.text() : Promise.reject(response))
                .then(() => {
                    alert('Form submitted successfully');
                    window.location.reload(); // Refresh the page after successful submission
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while submitting the forms');
                });
        } else {
            alert('Forms not found');
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const driverSelect = document.getElementById('tables');
        const contactInput = document.getElementById('ContactNo');
        const emailInput = document.getElementById('DriverEmail');

        function updateDriverFields() {
            const selectedOption = driverSelect.options[driverSelect.selectedIndex];
            const contact = selectedOption.getAttribute('data-contact');
            const email = selectedOption.getAttribute('data-email');

            contactInput.value = contact || 'N/A';
            emailInput.value = email || 'N/A';
        }

        driverSelect.addEventListener('change', updateDriverFields);

        updateDriverFields();
    });

    document.addEventListener('DOMContentLoaded', function () {
        const vehicleSelect = document.getElementById('vehicle');
        const plateInput = document.getElementById('plate');

        function updateVehicleFields() {
            const selectedOption = vehicleSelect.options[vehicleSelect.selectedIndex];
            const plate = selectedOption.getAttribute('data-plate');

            plateInput.value = plate || 'N/A';
        }

        vehicleSelect.addEventListener('change', updateVehicleFields);

        updateVehicleFields();
    });

    document.addEventListener('DOMContentLoaded', function () {
        const soAuthSelect = document.getElementById('SOAuth');
        const soPositionInput = document.getElementById('SOPosition');

        function updateSOAuthorityFields() {
            const selectedOption = soAuthSelect.options[soAuthSelect.selectedIndex];
            const position = selectedOption.getAttribute('data-position');

            soPositionInput.value = position || 'N/A';
        }

        soAuthSelect.addEventListener('change', updateSOAuthorityFields);

        updateSOAuthorityFields();
    });

    document.addEventListener('DOMContentLoaded', function () {
        const aaAuthSelect = document.getElementById('AAuth');
        const aaPositionInput = document.getElementById('AAPosition');

        function updateAAAuthorityFields() {
            const selectedOption = aaAuthSelect.options[aaAuthSelect.selectedIndex];
            const position = selectedOption.getAttribute('data-position');

            aaPositionInput.value = position || 'N/A';
        }

        aaAuthSelect.addEventListener('change', updateAAAuthorityFields);

        updateAAAuthorityFields();
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.getElementById('date').value = new Date().toISOString().split('T')[0];

        const now = new Date();
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        document.getElementById('dtime').value = `${hours}:${minutes}`;
    });

    function setupFormChangeDetectionAndCancel() {
        let formChanged = false;

        // Add event listeners to all form fields to detect changes
        document.querySelectorAll('input, select').forEach(element => {
            element.addEventListener('change', () => {
                formChanged = true;
            });
        });

        // Define the cancelForm function
        function cancelForm() {
            if (formChanged) {
                const confirmDiscard = confirm("You have unsaved changes. Do you really want to go back? Changes will be discarded.");
                if (!confirmDiscard) {
                    return;
                }
            }
            window.location.href = '/VehicleTabular';
        }

        // Attach the cancelForm function to the cancel button
        document.querySelector('.btn').addEventListener('click', cancelForm);
    }

    setupFormChangeDetectionAndCancel();

    function updateEventStatus() {
        const FormStatus = document.getElementById('FormStatus');
        const EventStatus = document.getElementById('EventStatus');

        if (FormStatus.value === 'Approved') {
            // // Hide "For Approval" and "Not Approved" options
            // Array.from(FormStatus.options).forEach(option => {
            //     if (option.value === 'For Approval' || option.value === 'Not Approved') {
            //         option.style.display = 'none';
            //     }
            // });

            // Update EventStatus dropdown
            EventStatus.outerHTML = `
        <select id="EventStatus" name="EventStatus">
            <option value="Ongoing" ${EventStatus.value === 'Ongoing' ? 'selected' : ''}>Ongoing</option>
            <option value="Cancelled" ${EventStatus.value === 'Cancelled' ? 'selected' : ''}>Cancelled</option>
        </select>`;
        } else {
            // Show all options
            Array.from(FormStatus.options).forEach(option => {
                option.style.display = 'block';
            });

            // Update EventStatus input
            EventStatus.outerHTML = `<input type="text" id="EventStatus" name="EventStatus" value="-" readonly>`;
        }
    }

    // Attach the function to the change event of the FormStatus dropdown
    document.getElementById('FormStatus').addEventListener('change', updateEventStatus);

    // Call the function on page load to set the initial state
    document.addEventListener('DOMContentLoaded', updateEventStatus);

    document.querySelector('form').addEventListener('submit', function (e) {
        console.log('Form is being submitted');
    });

    function updateFormStatus() {
        const EventStatus = document.getElementById('EventStatus').value;
        const FormStatus = document.getElementById('FormStatus');

        switch (EventStatus) {
            case 'Ongoing':
                FormStatus.value = 'Approved';
                break;
            case 'Finished':
                FormStatus.value = 'Approved';
                break;
            case 'Cancelled':
                FormStatus.value = 'Approved';
                break;
            default:
                FormStatus.value = 'Pending';
                break;
        }
    }

    function previewSignature(event) {
        const input = event.target;
        const preview = document.getElementById('signature-preview');
        const reader = new FileReader();
        const uploadText = document.querySelector('.e-signature-text');

        reader.onload = function () {
            preview.src = reader.result;
            preview.style.display = 'block';
            uploadText.style.display = 'none'; // Hide the upload text
        };

        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }

    // JavaScript: Update the previewCertificate function
    function previewCertificate(event) {
        const file = event.target.files[0];
        const filePreview = document.getElementById('certificate-preview');
        const defaultText = document.getElementById('default-text');
        const filePathInput = document.getElementById('certfile-path');

        // Clear any existing content in the preview box
        filePreview.innerHTML = '';

        if (file) {
            // Hide the default text when a file is uploaded
            defaultText.style.display = 'none';

            // Check if the file is a PDF
            if (file.type === 'application/pdf') {
                // Create an anchor element to make the file name clickable
                const fileLink = document.createElement('a');
                fileLink.textContent = `${file.name}`;
                fileLink.style.color = 'green';
                fileLink.href = URL.createObjectURL(file);
                fileLink.target = '_blank'; // Open in a new tab

                // Append the clickable file name to the preview area
                filePreview.appendChild(fileLink);

                // Store the file path in the hidden input field
                filePathInput.value = file.name;
            } else {
                // Display a message if the file is not a PDF
                filePreview.textContent = 'Please upload a valid PDF file.';
                filePreview.style.color = 'red';
            }
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const existingFilePath = '{{ $requestData["certfile-upload"] ?? "" }}';
        const filePreview = document.getElementById('certificate-preview');
        const defaultText = document.getElementById('default-text');
        const filePathInput = document.getElementById('certfile-path');

        // Function to preview the file
        function previewFile(file) {
            filePreview.innerHTML = '';

            if (file.type === 'application/pdf') {
                const fileLink = document.createElement('a');
                fileLink.textContent = `${file.name}`;
                fileLink.style.color = 'green';
                fileLink.href = URL.createObjectURL(file);
                fileLink.target = '_blank';
                filePreview.appendChild(fileLink);
            } else if (file.type.startsWith('image/')) {
                const img = document.createElement('img');
                img.src = URL.createObjectURL(file);
                img.style.maxWidth = '100px';
                img.style.maxHeight = '100px';
                filePreview.appendChild(img);
            } else {
                filePreview.textContent = 'Please upload a valid PDF or image file.';
                filePreview.style.color = 'red';
            }

            filePathInput.value = file.name;
        }

        // Display existing file if present
        if (existingFilePath) {
            defaultText.style.display = 'none';
            const fileLink = document.createElement('a');
            fileLink.textContent = existingFilePath.split('/').pop();
            fileLink.style.color = 'green';
            fileLink.href = `{{ asset('storage/' . $requestData['certfile-upload']) }}`;
            fileLink.target = '_blank';
            filePreview.appendChild(fileLink);
        }

        // Event listener for file upload
        document.getElementById('certfile-upload').addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                defaultText.style.display = 'none';
                previewFile(file);
            }
        });
    });

    function toggleDispatcher() {
        var dispatcherForm = document.getElementById("dispatcher-form");
        var isOpen = dispatcherForm.style.display === "block";
        dispatcherForm.style.display = isOpen ? "none" : "block";
        localStorage.setItem('dispatcherFormOpen', !isOpen);
    }

    function toggleAdminService() {
        var adminServiceForm = document.getElementById("admin-service-form");
        var isOpen = adminServiceForm.style.display === "block";
        adminServiceForm.style.display = isOpen ? "none" : "block";
        localStorage.setItem('adminServiceFormOpen', !isOpen);
    }

    function setInitialFormState() {
        var dispatcherFormOpen = localStorage.getItem('dispatcherFormOpen') === 'true';
        var adminServiceFormOpen = localStorage.getItem('adminServiceFormOpen') === 'true';

        document.getElementById("dispatcher-form").style.display = dispatcherFormOpen ? "block" : "none";
        document.getElementById("admin-service-form").style.display = adminServiceFormOpen ? "block" : "none";
    }

    // Set the initial state of the forms on page load
    document.addEventListener('DOMContentLoaded', setInitialFormState);

    document.addEventListener('DOMContentLoaded', function () {
        // Function to update driver contact and email
        function updateDriverInfo() {
            var driverSelect = document.getElementById('DriverID');
            var selectedDriver = driverSelect.options[driverSelect.selectedIndex];
            document.getElementById('ContactNo').value = selectedDriver.getAttribute('data-contact') || 'N/A';
            document.getElementById('DriverEmail').value = selectedDriver.getAttribute('data-email') || 'N/A';
        }

        // Function to update vehicle plate number
        function updateVehicleInfo() {
            var vehicleSelect = document.getElementById('VehicleID');
            var selectedVehicle = vehicleSelect.options[vehicleSelect.selectedIndex];
            document.getElementById('PlateNo').value = selectedVehicle.getAttribute('data-plate') || 'N/A';
        }

        // Initial update on page load
        updateDriverInfo();
        updateVehicleInfo();

        // Driver select change event
        document.getElementById('DriverID').addEventListener('change', updateDriverInfo);

        // Vehicle select change event
        document.getElementById('VehicleID').addEventListener('change', updateVehicleInfo);
    });


</script>
</body>
</html>
