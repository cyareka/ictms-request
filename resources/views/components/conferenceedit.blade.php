<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conference Room Request Form</title>
    <style>
        .modal {
            display: flex;
            justify-content: center;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1050;
            overflow: hidden;
        }

        .modal-dialog {
            max-width: 500px;
            margin: auto;
        }

        .modal-footer {
            display: flex;
            justify-content: space-evenly;
        }

        body {
            font-family: 'Poppins';
            font-size: 18px;
        }

        .container {
            width: 60em;
            padding: 35px;
            border: 1px solid #ddd;
            border-radius: 15px;
            margin: 5em auto 3em;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
            margin-top: 15px;
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

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid rgba(60, 54, 51, 0.5);
            border-radius: 15px;
            box-sizing: border-box;
            margin-bottom: 5px;
        }

        .inline-field input[type="date"],
        .inline-field input[type="time"] {
            width: 150px;
        }

        button {
            background-color: #65558F;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            display: block;
        }

        .row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 8px;
        }

        .row-group-container {
            height: 200px; /* Adjust height as necessary */
            overflow-y: auto;
            overflow-x: hidden;
            margin-bottom: -40px;
            margin-right: 10px;
        }

        .row-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
            position: relative;
        }

        .remove-container {
            position: absolute;
            top: 0;
            right: 0;
        }

        .button-container {
            display: flex;
            align-items: center;
            padding: 3px;
            margin-bottom: 8px;
        }

        .add-btn {
            background-color: #0056b3;
            color: white;
            padding: 3px 8px;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 20px;
            margin-left: 5px;
        }

        .add-btn:hover {
            background-color: #003d80;
        }

        .remove-btn {
            background-color: #ff4d4d;
            color: white;
            padding: 5px 10px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 20px;
            margin-left: 5px;
        }

        .remove-btn:hover {
            background-color: #cc0000;
        }

        .inline-field {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            margin-left: 20px;
        }

        .tb {
            display: flex;
            align-items: center;

        }

        .inline-field label {
            display: inline-block;
            width: 120px;
            margin-right: 10px;
        }

        .inline-field input,
        .inline-field select {
            width: 70%;
        }

        .tb label {
            display: inline-block;
            width: 100px;
        }

        .form-footer {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            gap: 20px;
        }

        .submit-btn {
            background-color: #354e7d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            width: 11%;
        }

        .cancel-btn {
            background-color: #E1C16E;
        }

        #signature-preview {
            margin-top: 15px;
            max-width: 100px;
            max-height: 100px;
            display: none;
        }

        input[type="number"] {
            width: 40px;
            height: 25px;
            padding: 0;
            border: none;
            background-color: transparent;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button {
            width: 25px;
            height: 25px;
            padding: 0;
            margin: 0;
            background-color: #ccc;
            cursor: pointer;
            justify-content: space-between;
            opacity: 1;
            visibility: visible;
        }

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
            color: #000; /* Text color to match the border */
        }

        #certificate-preview {
            font-size: 14px;
            color: #333;
            margin-top: 10px; /* Space between the default text and preview text */
        }

        @media (max-width: 768px) {
            .container {
                width: 90%;
                flex-direction: column;
                gap: 15px;
                position: relative;
            }

            .remove-container {
                position: absolute;
                top: 0;
                right: 0;
            }

            .button-container {
                display: flex;
                align-items: center;
                padding: 3px;
                margin-bottom: 8px;
            }

            .add-btn {
                background-color: #0056b3;
                color: white;
                padding: 3px 8px;
                border: none;
                border-radius: 50px;
                cursor: pointer;
                font-size: 20px;
                margin-left: 5px;
            }

            .add-btn:hover {
                background-color: #003d80;
            }

            .remove-btn {
                background-color: #ff4d4d;
                color: white;
                padding: 5px 10px;
                border: none;
                border-radius: 20px;
                cursor: pointer;
                font-size: 20px;
                margin-left: 5px;
            }

            .remove-btn:hover {
                background-color: #cc0000;
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
                text-align: center;
            }

            .inline-field {
                display: flex;
                align-items: center;
                margin-bottom: 15px;
                margin-left: 20px;
            }

            .inline-field label {
                display: inline-block;
                width: 120px;
                margin-right: 10px;
            }

            .inline-field input,
            .inline-field select {
                width: 70%;
            }

            .form-footer {
                display: flex;
                justify-content: center;
                margin-top: 20px;
                gap: 20px;
                width: 100%;
            }

            .submit-btn {
                background-color: #65558F;
                color: white;
                padding: 10px 20px;
                border: none;
                border-radius: 20px;
                cursor: pointer;
                font-size: 16px;
                width: 100%;
            }

            #signature-preview {
                margin-top: 15px;
                max-width: 100px;
                max-height: 100px;
                display: none;
            }

            .row-multiple {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 15px;
                margin-bottom: 8px;
            }

            @media (max-width: 768px) {
                .container {
                    width: 90%;
                    flex-direction: column;
                }

                .button-container {
                    display: flex;
                    align-items: center;
                    padding: 3px;
                    margin-bottom: 8px;
                }

                .add-btn {
                    background-color: #0056b3;
                    color: white;
                    padding: 3px 8px;
                    border: none;
                    border-radius: 50px;
                    cursor: pointer;
                    font-size: 20px;
                    margin-left: 5px;
                }

                .add-btn:hover {
                    background-color: #003d80;
                }

                .remove-btn {
                    background-color: #ff4d4d;
                    color: white;
                    padding: 5px 10px;
                    border: none;
                    border-radius: 20px;
                    cursor: pointer;
                    font-size: 20px;
                    margin-left: 5px;
                }

                .remove-btn:hover {
                    background-color: #cc0000;
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
                    text-align: center;
                }

                .inline-field {
                    display: flex;
                    align-items: center;
                    margin-bottom: 15px;
                    margin-left: 20px;
                }

                .inline-field label {
                    display: inline-block;
                    width: 120px;
                    margin-right: 10px;
                }

                .inline-field input,
                .inline-field select {
                    width: 70%;
                }

                .form-footer {
                    display: flex;
                    justify-content: center;
                    margin-top: 20px;
                    gap: 20px;
                }

                .submit-btn {
                    background-color: #354e7d;
                    color: white;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 20px;
                    cursor: pointer;
                    font-size: 16px;
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

                #signature-preview {
                    margin-top: 15px;
                    max-width: 100px;
                    max-height: 100px;
                    display: none;
                }

                .row-multiple {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 15px;
                    margin-bottom: 8px;
                }

                @media (max-width: 768px) {
                    .container {
                        width: 90%;
                        flex-direction: column;
                    }

                    .row {
                        grid-template-columns: 1fr;

                    }

                    .row-group-container {
                        flex-direction: column;
                        align-items: flex-start;
                        margin-bottom: 20px;
                        overflow-y: hidden;
                        height: auto;
                    }

                    .row-group-container label {
                        width: 100%;
                        margin-bottom: 5px;
                    }

                    .row-group-container input[type="date"],
                    .row-group-container input[type="time"] {
                        width: 50%;
                    }

                    .row-group-container label {
                        display: block;
                        margin-bottom: 5px;
                    }

                    .row-group-container input,
                    .row-group-container select {
                        width: 100%;
                    }

                    .inline-field {
                        flex-direction: column;
                        align-items: flex-start;
                    }

                    #npersons {
                        width: 19%; /* Make the input take full width */
                        box-sizing: border-box; /* Ensure padding and border are included in the width */
                    }

                    .inline-field label[for="person"] {
                        margin-right: -150px; /* adjust the value as needed */
                    }

                    .tb {
                        flex-direction: column;
                        align-items: flex-start;
                    }

                    .tb input {
                        margin-right: -150px;
                    }

                    .inline-field label {
                        width: 100%;
                        margin-bottom: 5px;
                    }

                    .tb label {
                        width: 100%;
                        margin-bottom: 15px;
                    }

                    .inline-field input,
                    .inline-field select {
                        width: 100%;
                    }

                    .add-btn {
                        display: flex;
                        align-items: center;
                        justify-content: flex-end;
                    }
                }
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
<div class="container">
    <button class="btn float-right">
        <i class="fa-duotone fa-solid fa-xmark"></i>
    </button>
    <h1>Update Request for Conference Room</h1>
    <p>(Note: Request should be made at least two (2) days before the date of actual use)</p>

    {{-- display for edit --}}
    <form method="POST" action="/conference-room/update" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="CRequestID" value="{{ $requestData-> CRequestID }}">
        <div class="row">
            <div class="inline-field">
                <label for="officeName">Requesting Office</label>
                <input type="text" id="officeName" name="officeName" value="{{ $requestData->office->OfficeName }}"
                       placeholder="-" readonly>
            </div>
            <div class="inline-field">
                <label for="purpose">Purpose</label>
                `<input type="text" id="purpose" name="purpose"
                        value="{{ optional(App\Models\PurposeRequest::find($requestData->PurposeID))->purpose ?? $requestData->PurposeOthers }}"
                        placeholder="-" readonly>`
            </div>
        </div>
        <div class="row-group-container">
            <div class="row-group">
                <div class="row">
                    <div class="inline-field">
                        <label for="dateStart">Date Start</label>
                        <input type="date" id="dateStart" name="dateStart" value="{{ $requestData->date_start }}"
                               readonly>
                    </div>
                    <div class="inline-field">
                        <label for="dateEnd">Date End</label>
                        <input type="date" id="dateEnd" name="dateEnd" value="{{ $requestData->date_end }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="inline-field">
                        <label for="timeStart">Time Start</label>
                        <input type="time" id="timeStart" name="timeStart" value="{{ $requestData->time_start }}"
                               readonly>
                    </div>
                    <div class="inline-field">
                        <label for="timeEnd">Time End</label>
                        <input type="time" id="timeEnd" name="timeEnd" value="{{ $requestData->time_end }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="inline-field">
                <label for="conferenceRoom">Conference Room</label>
                <input type="text" id="conferenceRoom" name="conferenceRoom"
                       value="{{ $requestData->conferenceRoom->CRoomName }}" placeholder="-" readonly>
            </div>
            <div class="inline-field">
                <label for="focalPerson">Focal Person</label>
                <input type="text" id="focalPerson" name="focalPerson"
                       value="{{ optional(App\Models\FocalPerson::find($requestData->FocalPID))->FPName ??  $requestData->FPOthers }}"
                       placeholder="Enter Focal Person" readonly>
            </div>
            <div class="row">
            </div>
        </div>
        <div class="row">
            <div class="inline-field">
                <label for="person">No. of Persons</label>
                <input type="number" id="npersons" name="npersons" value="{{ $requestData->npersons }}" placeholder="0"
                       readonly>
                <div class="tb">
                    <label for="tables">Tables</label>
                    <input type="number" id="tables" name="tables" value="{{ $requestData->tables }}" placeholder="0"
                           readonly>
                    <div class="tb">
                        <label for="chairs">Chairs</label>
                        <input type="number" id="chairs" name="chairs" value="{{ $requestData->chairs }}"
                               placeholder="0" readonly>
                    </div>
                </div>
            </div>
            <div class="inline-field">
                <label for="otherFacilities">Other Facilities</label>
                <input type="text" id="otherFacilities" name="otherFacilities"
                       value="{{ $requestData->otherFacilities }}" placeholder="-" readonly>
            </div>
        </div>

        <div class="row">
            <div class="inline-field">
                <label for="requesterName">Requester Name</label>
                <input type="text" id="requesterName" name="requesterName" value="{{ $requestData->RequesterName }}"
                       placeholder="-" readonly>
            </div>
            <div class="inline-field">
                <label for="RequesterSignature">E-Signature</label>
                <div class="file-upload">
                    <img id="signature-preview"
                         src="{{ $requestData->RequesterSignature ? asset('storage/' . $requestData->RequesterSignature) : '' }}"
                         alt="Signature Preview"
                         style="{{ $requestData->RequesterSignature ? 'display: block;' : 'display: none;' }}" readonly>
                </div>
            </div>
            <div class="inline-field">
                @php
                    function convertAvailability($availability): string
                    {
                        return $availability > 0 ? 'Available' : 'Not Available';
                    }
                @endphp

                <label for="availability">Availability</label>
                <input type="text" id="CAvailability" name="CAvailability"
                       value="{{ convertAvailability($requestData->CAvailability) }}" readonly>
            </div>
            <div class="inline-field">
                <label for="FormStatus">Form Status</label>
                <input type="hidden" id="downloadClicked" name="downloadClicked" value="0">
                <select id="FormStatus" name="FormStatus" onchange="updateEventStatus()">
                    <option value="Pending" {{ $requestData->FormStatus == 'Pending' ? 'selected' : '' }} hidden>
                        Pending
                    </option>
                    @if ($requestData->CAvailability == '0')
                        <option value="Not Approved" {{ $requestData->FormStatus == 'Not Approved' ? 'selected' : '' }}>
                            Not Approved
                        </option>
                    @else
                        <option value="For Approval" {{ $requestData->FormStatus == 'For Approval' ? 'selected' : '' }}>
                            For Approval
                        </option>
                        <option value="Approved" {{ $requestData->FormStatus == 'Approved' ? 'selected' : '' }}>
                            Approved
                        </option>
                        <option value="Not Approved" {{ $requestData->FormStatus == 'Not Approved' ? 'selected' : '' }}>
                            Not Approved
                        </option>
                    @endif
                </select>
            </div>
        </div>
        <div class="row">
            <div class="inline-field">
                <label for="EventStatus">Event Status</label>
                <input type="text" id="EventStatus" name="EventStatus" value="{{ $requestData->EventStatus }}" readonly>
            </div>
            <div id="file-upload-section" style="display: none;">
                <div class="inline-field">
                    <label for="certificate-upload">File Upload</label>
                    <div class="file-upload">
                        <label for="certfile-upload" id="certificate-preview-label">
                            <div id="certificate-preview-container">
                                <div id="default-text">No file uploaded</div>
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
        <div class="form-footer">
            @if($requestData->FormStatus === 'For Approval')
                <a onclick="showDownloadModal('{{ route('downloadCRequestPDF', $requestData->CRequestID) }}', '{{ route('downloadUnavailableCRequestPDF', $requestData->CRequestID) }}')">
                    <button class="cancel-btn" type="button" onclick="setDownloadClicked()">Download</button>
                </a>
            @elseif($requestData->FormStatus === 'Approved')
                <a href="{{ route('downloadFinalCRequestPDF', $requestData->CRequestID) }}" target="_blank">
                    <button class="cancel-btn" type="button">Download</button>
                </a>
            @elseif($requestData->FormStatus === 'Pending' && $requestData->CAvailability === 0)
                <a href="{{ route('downloadUnavailableCRequestPDF', $requestData->CRequestID) }}" target="_blank">
                    <button class="cancel-btn" type="button">Download</button>
                </a>
            @endif
            <button class="submit-btn" type="submit">Update</button>
        </div>
        <input type="text" id="AuthRep" name="AuthRep" value="{{ Auth::user()->name }}" hidden>
    </form>
</div>
<script>
    function setDownloadClicked() {
        document.getElementById('downloadClicked').value = '1';
        showApprovalOptions();
    }

    function showApprovalOptions() {
        const formStatus = document.getElementById('FormStatus');
        const options = formStatus.options;

        for (let i = 0; i < options.length; i++) {
            if (options[i].value === 'Approved' || options[i].value === 'Not Approved') {
                options[i].style.display = 'block';
            }
        }
    }

    // Call showApprovalOptions on page load if downloadClicked is already set
    document.addEventListener('DOMContentLoaded', function() {
        if (document.getElementById('downloadClicked').value === '1') {
            showApprovalOptions();
        }
    });

    function toggleFileUploadSection() {
        const formStatus = document.getElementById('FormStatus').value;
        const fileUploadSection = document.getElementById('file-upload-section');

        if ((formStatus === 'Approved' && {{ $requestData->CAvailability }} == 1) ||
            (formStatus === 'Not Approved' && {{ $requestData->CAvailability }} == 0)) {
            fileUploadSection.style.display = 'block';
        } else {
            fileUploadSection.style.display = 'none';
        }
    }

    // Attach the function to the change event of the FormStatus dropdown
    document.getElementById('FormStatus').addEventListener('change', toggleFileUploadSection);

    // Call the function on page load to set the initial state
    document.addEventListener('DOMContentLoaded', toggleFileUploadSection);

    function showDownloadModal(requestFormUrl, unavailabilityUrl) {
        const modalHtml = `
    <div class="modal" id="downloadModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Download Options</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Which document would you like to download?</p>
                </div>
                <div class="modal-footer">
                    <a href="${requestFormUrl}" class="btn btn-primary" target="_blank">Request Form</a>
                    <a href="${unavailabilityUrl}" class="btn btn-secondary" target="_blank">Certificate of Unavailability</a>
                </div>
            </div>
        </div>
    </div>
    `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        const downloadModal = document.getElementById('downloadModal');
        downloadModal.style.display = 'flex';

        downloadModal.querySelector('.close').addEventListener('click', function () {
            downloadModal.style.display = 'none';
            downloadModal.remove();
        });

        window.addEventListener('click', function (event) {
            if (event.target === downloadModal) {
                downloadModal.style.display = 'none';
                downloadModal.remove();
            }
        });
    }

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
        document.querySelector('.btn').addEventListener('click', cancelForm);
    }

    // Call the setup function to initialize everything
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

        switch (EventStatus) {
            case 'Ongoing':
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

    /**
     * Previews the signature image when a file is selected.
     *
     * This function is triggered when a file input changes.
     * It reads the selected file and displays it in the signature preview element.
     * Additionally, it hides the upload text.
     *
     * @param {Event} event - The event object from the file input change.
     */
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

</script>
</body>
</html>
