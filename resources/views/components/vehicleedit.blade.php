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
    </style>
</head>
<body>
<div class="form-container">
    <h1>Request For Use of Vehicle</h1>
    <p>(Note: Request for use of vehicle shall be made at least (2) days from the intended date use. Failure to use the
        vehicle at the given date/time forfeits one’s right to use the vehicle assigned.)</p>
    <div class="form-body">
        <form action="/vehicle-request" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="input-group">
                <div class="input-field">
                    <label for="officeName">Requesting Office</label>
                    <select id="officeName" name="officeName" placeholder="Enter Purpose" required>
                        <option disabled selected>Select Office</option>
                        <option>Office of the Regional Director</option>
                        <option>Administrative Division</option>
                        <option>Finance Division</option>
                        <option>Planning Division</option>
                        <option>Technical Division</option>
                    </select>
                </div>
                <div class="input-field">
                    <label>Purpose</label>
                    <input type="text" name="purpose" placeholder="Enter Purpose" required/>
                </div>
            </div>
            <div class="input-group">
                <div class="input-field">
                    <label>Destination</label>
                    <input type="text" name="place_of_travel" placeholder="Enter Place" required/>
                </div>
                <div class="input-field">
                    <label>Passenger/s</label>
                    <select name="passengers[]" required>
                        <option disabled selected>Select a passenger</option>
                        <option>Rea May Manlunas</option>
                        <option>Sheardeeh Zurrielle Fernandez</option>
                        <option>Inalyn Kim Tamayo</option>
                        <option>Beverly Consolacion</option>
                        <option>Ryu Colita</option>
                        <option>Justin Misajon</option>
                        <option>Elmer John Catalan</option>
                    </select>
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
                            <input type="date" id="date_start" name="date_start[]"/>
                            <label for="date_start" class="below-label1">Start</label>
                        </div>
                        <div class="date-field">
                            <input type="date" id="date_end" name="date_end[]" required/>
                            <label for="date_end" class="below-label2">End</label>
                        </div>
                    </div>
                    <div class="input-field">
                        <label>Time</label>
                        <input type="time" name="time_start[]" required/>
                    </div>
                </div>
            </div>
            <div class="input-group">
                <div class="input-field">
                    <label>Requested By</label>
                    <input type="text" name="requested_by" placeholder="Enter Name" required/>
                </div>
                <div class="input-field">
                    <label>Requester Email</label>
                    <input type="text" name="email_requester" placeholder="Enter Email" required/>
                </div>
            </div>
            <div class="input-group">
                <div class="input-field">
                    <label>Contact No.</label>
                    <input type="text" name="contact_no" placeholder="Enter No." required/>
                </div>
                <div class="input-field">
                    <label for="e-signature">E-Signature</label>
                    <div class="file-upload">
                        <input type="file" id="e-signature" name="e-signature" style="display: none;"
                               onchange="previewSignature(event)" required>
                        <div class="e-signature-text" onclick="document.getElementById('e-signature').click();">
                            Click to upload e-sign.<br>Maximum file size: 31.46MB
                        </div>
                        <img id="signature-preview" alt="Signature Preview">
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
            <form class="row-dispatch">
                <div class="row-dispatch">
                    <div class="inline">
                        <label for="name">Driver Name</label>
                        <select id="tables" name="driver" required>
                            @foreach(App\Models\Driver::all() as $driver)
                                <option value="{{ $driver->DriverID }}">{{ $driver->DriverName }}</option>
                            @endforeach
                        </select>
                        <input type="text" >
                    </div>
                    <div class="inline">
                        <label for="contact">Driver Contact No.</label>
                        <input type="text" id="contact" name="contact" placeholder="N/A" readonly>
                    </div>
                    <div class="inline">
                        <label for="email">Driver Email</label>
                        <input type="text" id="driveremail" name="driveremail" placeholder="N/A" readonly>
                    </div>
                </div>
                <div class="row-dispatch">
                    <div class="inline">
                        <label for="VName">Vehicle Type</label>
                        <select id="VName" name="VName">
                            @foreach(App\Models\Vehicle::all() as $vehicle)
                                <option value="{{ $vehicle->VehicleID }}">{{ $vehicle->VehicleType }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="inline">
                        <label for="plate">Plate No.</label>
                        <input type="text" id="plate" name="plate" placeholder="N/A" readonly>
                    </div>
                    <div class="inline">
                        {{-- depends on admin logged in --}}
                        <label for="RbName">Received by</label>
                        <input type="text" id="RbName" name="RbName" value="{{ Auth::user()->name }}">{{ Auth::user()->name }}">
                    </div>
                </div>
                <div class="row-dispatch ">
                    <div class="inline">
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date[]" required>
                    </div>
                    <div class="inline">
                        <label for="time">Time</label>
                        <input type="time" id="dtime" name="time[]" required>
                    </div>
                    <div class="inline">
                        <label for="remark">Remarks</label>
                        <input type="text" id="remark" name="remark" placeholder="Enter Remark">
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
            <form class="row-dispatch">
                <div class="row-dispatch">
                    <div class="inline">
                        <label for="availability">Availability</label>
                        <input type="text" id="availability" name="availability" value="{{ $requestData->vehicle->Availability }}" placeholder="-" readonly>
                    </div>
                    <div class="inline">
                        <label for="formStatus">Form Status</label>
                        <select id="formStatus" name="formStatus" onchange="updateEventStatus()">
                            <option value="Pending" {{ $requestData->FormStatus == 'Pending' ? 'selected' : '' }}>Pending</option>
                            <option value="Approved" {{ $requestData->FormStatus == 'Approved' ? 'selected' : '' }}>Approved</option>
                            <option value="Not Approved" {{ $requestData->FormStatus == 'Not Approved' ? 'selected' : '' }}>Not Approved</option>
                        </select>
                    </div>
                    <div class="inline">
                        <label for="eventStatus">Event Status</label>
                        <select id="eventStatus" name="eventStatus">
                            <option disabled selected>Select Event Status</option>
                            <option>-</option>
                            <option>Approved</option>
                            <option>Completed</option>
                            <option>Cancelled</option>
                        </select>
                    </div>
                </div>
                <div class="row-dispatch">
                    <div class="inline">
                        <label for="VName">Approving Authority</label>
                        <select id="VName" name="VName">
                            <option disabled selected>Select Authority</option>
                            <option>Rea May Manlunas</option>
                            <option>Sheardeeh Fernandez</option>
                            <option>Beverly Consolacion</option>
                            <option>Inalyn Tamayo</option>
                        </select>
                    </div>
                    <div class="inline">
                        <label for="VName">Approving Authority Position</label>
                        <select id="VName" name="VName">
                            <option disabled selected>Select Authority</option>
                            <option>Rea May Manlunas</option>
                            <option>Sheardeeh Fernandez</option>
                            <option>Beverly Consolacion</option>
                            <option>Inalyn Tamayo</option>
                        </select>
                    </div>
                </div>
                <div class="row-dispatch">
                    <div class="inline">
                        <label for="VName">SO Approving Authority</label>
                        <select id="VName" name="VName">
                            <option disabled selected>Select Authority</option>
                            @foreach(App\Models\SOAuthority::all() as $SOauth)
                                <option value="{{ $SOauth->SOID }}">{{ $SOauth->SOName }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="inline">
                        <label for="VName">SO Approving Authority Position</label>
                        <input type="text" id="VName" name="VName" value=" {{  }} ">
                    </div>
                </div>
                <div class="row-dispatch">
                    <div class="inline">
                        <label for="VName">Authorized Signatory</label>
                        <select id="VName" name="VName">
                            <option disabled selected>Select Authority</option>
                            <option>Rea May Manlunas</option>
                            <option>Sheardeeh Fernandez</option>
                            <option>Beverly Consolacion</option>
                            <option>Inalyn Tamayo</option>
                        </select>
                    </div>
                    <div class="inline">
                        <label for="RequesterSignature">E-Signature</label>
                        <div class="file-upload">
                            <img id="signature-preview"
                                 src="{{ $requestData->RequesterSignature ? asset('storage/' . $requestData->RequesterSignature) : '' }}"
                                 alt="Signature Preview"
                                 style="{{ $requestData->RequesterSignature ? 'display: block;' : 'display: none;' }}" readonly>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="form-footer">
            <button class="cancel-btn" type="button" onclick="cancelForm()">Back</button>
            <button class="submit-btn" type="submit">Update</button>
        </div>
    </div>
</div>

<script>
    /**
     * Sets up form change detection and handles the cancel action with a confirmation prompt.
     */
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
            window.location.href = '/dashboard';
        }

        // Attach the cancelForm function to the cancel button
        document.querySelector('.cancel-btn').addEventListener('click', cancelForm);
    }

    // Call the setup function to initialize everything
    setupFormChangeDetectionAndCancel();

    function updateEventStatus() {
        const FormStatus = document.getElementById('FormStatus').value;
        const EventStatus = document.getElementById('EventStatus');

        if (FormStatus === 'Approved') {
            EventStatus.value = 'Ongoing';
        } else if (FormStatus === 'Not Approved') {
            EventStatus.value = '-';
        } else {
            EventStatus.value = '-';
        }
    }

    /**
     * Updates the event status based on the selected form status.
     *
     * This function is triggered when the form status select element changes.
     * It sets the event status to 'Ongoing' if the form status is 'Approved'.
     * Otherwise, it sets the event status to '-'.
     */
    function updateFormStatus() {
        const EventStatus = document.getElementById('EventStatus').value;
        const FormStatus = document.getElementById('FormStatus');

        switch(EventStatus) {
            case 'Ongoing':
                FormStatus.value = 'Approved';
                break;
            case 'Finished':
                FormStatus.value= 'Approved';
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

    function toggleDispatcher() {
        var dispatcherForm = document.getElementById("dispatcher-form");
        dispatcherForm.style.display = (dispatcherForm.style.display === "block") ? "none" : "block";
    }

    function toggleAdminService() {
        var adminServiceForm = document.getElementById("admin-service-form");
        adminServiceForm.style.display = (adminServiceForm.style.display === "block") ? "none" : "block";
    }
</script>
</body>
</html>
