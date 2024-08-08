<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conference Room Request Form</title>
    <style>
        body {
            font-family: sans-serif;
        }

        .container {
            width: 60em;
            padding: 2em 2em 3em 1em;
            border: 1px solid #ddd;
            border-radius: 15px;
            margin: 5em auto 0;
            margin-bottom: 3em;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
            margin-top: 0;
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
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 15px;
            box-sizing: border-box;
            margin-bottom: 15px;
        }

        .inline-field input[type="date"],
        .inline-field input[type="time"] {
            width: 10em; /* Adjusted width */
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 15px;
            box-sizing: border-box;
            margin-bottom: 15px;
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
            margin-bottom: 15px;
            margin-right: 10px;
        }

        .row-group {
            display: grid;
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
        }

        .submit-btn {
            background-color: #65558F;
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

            .row-multiple {
                grid-template-columns: 1fr;
            }

            .inline-field {
                flex-direction: column;
                align-items: flex-start;
            }

            .inline-field label {
                width: 100%;
                margin-bottom: 5px;
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

    </style>
</head>
<body>

<div class="container">
    <h1>Update Request for Conference Room</h1>
    <p>(Note: Request should be made at least two (2) days before the date of actual use)</p>

    {{-- display for edit --}}
    <form method="POST" action="" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="CRequestID" value="{{ $requestData-> CRequestID }}">
        <div class="row">
            <div class="inline-field">
                <label for="officeName">Name of Requesting Office</label>
                <select id="officeName" name="officeName" readonly>
                    <option value="-" {{ $requestData->office->OfficeName == '-' ? 'selected' : '' }}>-</option>
                </select>
            </div>
            <div class="inline-field ">
                <label for="purpose">Purpose</label>
                <input type="text" id="purpose" name="purpose" value="{{ $requestData->Purpose }}" placeholder="-"
                       readonly>
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
                        {{--                        <div class="button-container">--}}
                        {{--                            <button class="add-btn" onclick="addRow()">+</button>--}}
                        {{--                        </div>--}}
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
            <div class="inline-field" style=" width: 12em;">
                <label for="persons">No. of Persons</label>
                <input class="small-input" type="text" id="persons" name="persons" value="{{ $requestData->npersons }}"
                       placeholder="-" style="margin-left: 65px;" readonly>
            </div>
            <div class="inline-field">
                <label for="focalPerson">Focal Person</label>
                <input type="text" id="focalPerson" name="focalPerson" value="{{ $requestData->focalPerson }}"
                       placeholder="Enter Focal Person" readonly>
            </div>
        </div>

        <div class="row-multiple">
            <div class="inline-field" style=" width: 8em; margin-left: 43px;">
                <label for="tables">Tables</label>
                <input type="text" id="tables" name="tables" value="{{ $requestData->tables }}" placeholder="-"
                       readonly>
            </div>
            <div class="inline-field" style=" width: 8em;">
                <label for="chairs">Chairs</label>
                <input type="text" id="chairs" name="chairs" value="{{ $requestData->chairs }}" placeholder="-"
                       readonly>
            </div>
            <div class="inline-field">
                <label for="otherFacilities">Other Facilities</label>
                <input type="text" id="otherFacilities" name="otherFacilities"
                       value="{{ $requestData->otherFacilities }}" placeholder="-" readonly>
            </div>
        </div>

        <div class="row">
            <div class="inline-field">
                <label for="conferenceRoom">Select Conference Room</label>
                <select id="conferenceRoom" name="conferenceRoom">
                    <option value="MAAGAP" {{ $requestData->conferenceRoom->CRoomName == 'MAAGAP' ? 'selected' : '' }}>
                        Maagap
                    </option>
                    <option
                        value="MAGITING" {{ $requestData->conferenceRoom->CRoomName == 'MAGITING' ? 'selected' : '' }}>
                        Magiting
                    </option>
                </select>
            </div>
            <div class="inline-field">
                <label for="requesterName">Name of Requester</label>
                <input type="text" id="requesterName" name="requesterName" value="{{ $requestData->RequesterName }}"
                       placeholder="-">
            </div>
        </div>
        <div class="row">
            <div class="inline-field">
                <label for="e-signature">E-Signature</label>
                <div class="file-upload">
                    <input type="file" id="e-signature" style="display: none;" onchange="previewSignature(event)">
                    <div class="e-signature-text" onclick="document.getElementById('e-signature').click();">
                        Click to upload e-sign.<br>Maximum file size: 31.46MB
                    </div>
                    <img id="signature-preview"
                         src="{{ $requestData->RequesterSignature ? asset('storage/' . $requestData->RequesterSignature) : '' }}"
                         alt="Signature Preview"
                         style="{{ $requestData->RequesterSignature ? 'display: block;' : 'display: none;' }}">
                </div>
            </div>
            <div class="inline-field">
                <label for="availability">Availability</label>
                <select id="availability" name="availability">
                    <option
                        value="Available" {{ $requestData->conferenceRoom->Availability == 'Available' ? 'selected' : '' }}>
                        Available
                    </option>
                    <option
                        value="Not Available" {{ $requestData->conferenceRoom->Availability == 'Not Available' ? 'selected' : '' }}>
                        Not Available
                    </option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="inline-field">
                <label for="formStatus">Form Status</label>
                <select id="formStatus" name="formStatus">
                    <option disabled selected>Select Form Status</option>
                    <option value="Pending" {{ $requestData->FormStatus == 'Pending' ? 'selected' : '' }}>Pending
                    </option>
                    <option value="Approved" {{ $requestData->FormStatus == 'Approved' ? 'selected' : '' }}>Approved
                    </option>
                </select>
            </div>
            <div class="inline-field">
                <label for="eventStatus">Event Status</label>
                <select id="eventStatus" name="eventStatus">
                    <option value="-" {{ $requestData->EventStatus == '-' ? 'selected' : '' }}>-</option>
                    <option value="Approved" {{ $requestData->EventStatus == 'Approved' ? 'selected' : '' }}>Approved
                    </option>
                    <option value="Completed" {{ $requestData->EventStatus == 'Completed' ? 'selected' : '' }}>
                        Completed
                    </option>
                    <option value="Cancelled" {{ $requestData->EventStatus == 'Cancelled' ? 'selected' : '' }}>
                        Cancelled
                    </option>
                </select>
            </div>
        </div>

        <div class="form-footer">
            <button class="submit-btn" type="button" onclick="updateForm()">Update</button>
            <button class="cancel-btn" type="button" onclick="cancelForm()">Cancel</button>
        </div>
    </form>
</div>
<script>
    function addRow() {
        let rowGroupContainer = document.querySelector('.row-group-container');
        let newRowGroup = document.createElement('div');
        newRowGroup.className = 'row-group';
        newRowGroup.innerHTML = `
            <div class="row">
                <div class="inline-field">
                    <label for="dateStart">Date Start</label>
                    <input type="date" id="dateStart" name="dateStart">
                </div>
                <div class="inline-field">
                    <label for="dateEnd">Date End</label>
                    <input type="date" id="dateEnd" name="dateEnd">
                     <div class="remove-container">
                <button class="remove-btn" onclick="removeRow(this)">-</button>
            </div>
                </div>
            </div>
            <div class="row">
                <div class="inline-field">
                    <label for="timeStart">Time Start</label>
                    <input type="time" id="timeStart" name="timeStart">
                </div>
                <div class="inline-field">
                    <label for="timeEnd">Time End</label>
                    <input type="time" id="timeEnd" name="timeEnd">
                </div>
            </div>
        `;

        rowGroupContainer.appendChild(newRowGroup);
    }

    function removeRow(button) {
        let container = button.closest('.row-group');
        container.remove();
    }

    function updateForm() {
        alert('Your request has been successfully updated.');
    }

    function cancelForm() {
        let inputFields = document.querySelectorAll('input');
        inputFields.forEach((field) => {
            field.value = '';
        });

        let rowGroupContainer = document.querySelector('.row-group-container');
        rowGroupContainer.innerHTML = `
            <div class="row-group">
                <div class="row">
                    <div class="inline-field">
                        <label for="dateStart">Date Start</label>
                        <input type="date" id="dateStart" name="dateStart">
                    </div>
                    <div class="inline-field">
                        <label for="dateEnd">Date End</label>
                        <input type="date" id="dateEnd" name="dateEnd">
                        <div class="button-container">
                            <button class="add-btn" onclick="addRow()">+</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="inline-field">
                        <label for="timeStart">Time Start</label>
                        <input type="time" id="timeStart" name="timeStart">
                    </div>
                    <div class="inline-field">
                        <label for="timeEnd">Time End</label>
                        <input type="time" id="timeEnd" name="timeEnd">
                    </div>
                </div>
            </div>
        `;

        document.getElementById('signature-preview').style.display = 'none';
        document.querySelector('.e-signature-text').style.display = 'block'; // Show the upload text again
        document.getElementById('e-signature').value = ''; // Reset the e-signature field

        document.getElementById('conferenceRoom').selectedIndex = 0;
        document.getElementById('availability').selectedIndex = 0; // Reset the availability field
        document.getElementById('formStatus').selectedIndex = 0; // Reset the form status field
        document.getElementById('eventStatus').selectedIndex = 0; // Reset the event status field

        alert('Form has been reset.');
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
</script>

</body>
</html>
