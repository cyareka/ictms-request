<!DOCTYPE html>
<html lang="en">
<head>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conference Room Request Form</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
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
            height: 135px;
            overflow-y: auto;
            margin-bottom: 15px;
        }

        .row-group {
            display: flex;
            flex-direction: column;
            gap: 15px;
            position: relative;
        }

        .button-container {
            display: flex;
            align-items: center;
            padding: 3px;
            margin-bottom: 8px;
        }

        .add-btn {
            display: inline-block;
            margin-left: 10px;
            background-color: #0056b3;
            color: white;
            padding: 3px 8px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .add-btn:hover {
            background-color: #003d80;
        }

        .remove-btn {
            background-color: #ff4d4d;
            color: white;
            padding: 3px 8px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
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

        }

        .tb {
            display: flex;
            align-items: center;

        }

        .inline-field label {
            display: inline-block;
            width: 100px;
        }

        .tb label {
            display: inline-block;
            width: 100px;
        }

        .inline-field input,
        .inline-field select {
            width: 70%;
        }

        .form-footer {
            display: flex;
            justify-content: center;
            margin-top: 20px;
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

        #signature-preview {
            margin-top: 15px;
            max-width: 100px;
            max-height: 100px;
            display: none;
        }

        .inline-field label::after {
            content: "*";
            color: red;
            right: -15px;
            top: 0;
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

        .fac {
            display: flex;
            align-items: center;
        }

        .fac label {
            display: inline-block;
            width: 100px;
        }

        .fac select {
            width: 60%;
        }

        .fac input {
            width: 60%;
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

            .fac {
                flex-direction: column;
                align-items: flex-start;
            }

            .tb {
                flex-direction: column;
                align-items: flex-start;
            }

            .inline-field label {
                width: 100%;
                margin-bottom: 5px;
            }

            .tb label {
                width: 100%;
                margin-bottom: 15px;
            }

            .fac label {
                width: 100%;
                margin-bottom: 15px;
            }

            .inline-field input,
            .inline-field select {
                width: 100%;
            }

            .fac input {
                width: 100%;
            }

            .tb input {
                margin-left: 20px;
            }

            .add-btn {
                display: flex;
                align-items: center;
                justify-content: flex-end;
            }
        }

        .checkbox {
            margin-left: 10px;
            width: 30px;
            margin-bottom: 10px;
            position: relative;
        }

        .checkbox:hover::after {
            content: " Please Specify";
            position: absolute;
            top: -40px;
            left: 0;
            background-color: #65558F;
            color: white;
            padding: 3px 6px;
            border-radius: 5px;
            font-size: 12px;
            /* white-space: nowrap; */
        }

        #purposeTextBox {
            width: 70%;
            padding: 10px;
            border: 1px solid rgba(60, 54, 51, 0.5);
            border-radius: 15px;
            box-sizing: border-box;
            margin-top: 5px;
        }

        .checkbox input[type="checkbox"]:checked + #purposeTextBox {
            display: block;
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
        alert("Form submission failed. Please correct the following errors:\n\n" + errorMessages.join("\n"));
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
    <h1>Request For Use of Conference Room</h1>
    <p>(Note: Request should be made at least two (2) days before the date of actual use)</p>
    <form action="/conference-room/request" method="POST" enctype="multipart/form-data"
          onsubmit="return validateForm()">
        @csrf
        <div class="row">
            <div class="inline-field">
                <label for="officeName">Requesting Office</label>
                <select id="officeName" name="officeName" onchange="updateFocalPersons(this.value)" required>
                    <option disabled selected>Select Office</option>
                    @foreach(App\Models\Office::all() as $office)
                        <option
                            value="{{ $office->OfficeID }}" {{ old('officeName') == $office->OfficeID ? 'selected' : '' }}>
                            {{ $office->OfficeName }}
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- Purpose -->
            <div class="inline-field">
                <label for="purpose">Purpose</label>
                <select id="purposeSelect" name="purposeSelect" required>
                    <option disabled selected>Select Purpose</option>
                    @foreach(App\Models\PurposeRequest::where('request_p', 'Conference Room')->get() as $purpose)
                        <option
                            value="{{ $purpose->PurposeID }}" {{ old('purposeSelect') == $purpose->PurposeID ? 'selected' : '' }}>
                            {{ $purpose->purpose }}
                        </option>
                    @endforeach
                </select>
                <input type="text" id="purposeInput" name="purposeInput" style="display:none;"
                       placeholder="Enter Purpose" value="{{ old('purposeInput') }}">
                <div class="checkbox">
                    <input type="checkbox" id="purposeCheckbox" name="purposeCheckbox"
                           onclick="toggleInputField('purpose')" {{ old('purposeInput') ? 'checked' : '' }}>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="inline-field">
                <label for="conferenceRoom">Conference Room</label>
                <select id="conferenceRoom" name="conferenceRoom" required>
                    <option disabled selected>Select Room</option>
                    @foreach(App\Models\ConferenceRoom::all() as $room)
                        <option
                            value="{{ $room->CRoomID }}" {{ old('conferenceRoom') == $room->CRoomID ? 'selected' : '' }}>
                            {{ $room->CRoomName }}
                        </option>
                    @endforeach
                </select>
            </div>
            <!-- Focal Person -->
            <div class="inline-field">
                <label for="focalPerson">Focal Person</label>
                <select id="focalPersonSelect" name="focalPersonSelect">
                    <option disabled selected>Select Focal Person</option>
                    @foreach(App\Models\FocalPerson::all() as $fp)
                        <option
                            value="{{ $fp->FocalPID }}" {{ old('focalPersonSelect') == $fp->FocalPID ? 'selected' : '' }}>
                            {{ $fp->FPName }}
                        </option>
                    @endforeach
                    <input type="text" id="focalPersonInput" name="focalPersonInput" style="display:none;"
                           placeholder="Enter Focal Person" value="{{ old('focalPersonInput') }}">
                    <div class="checkbox">
                        <input type="checkbox" id="focalPersonCheckbox" name="focalPersonCheckbox"
                               onclick="toggleInputField('focalPerson')" {{ old('focalPersonInput') ? 'checked' : '' }}>
                    </div>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="inline-field">
                <label for="requesterName">Requester Name</label>
                <input type="text" id="requesterName" name="requesterName" placeholder="Enter Name of Requester"
                       value="{{ old('requesterName') }}" required>
            </div>
            <div class="inline-field">
                <label for="RequesterSignature">E-Signature </label>
                <div class="file-upload">
                    <input type="file" id="RequesterSignature" name="RequesterSignature" style="display: none;"
                           onchange="previewSignature(event)">
                    <div class="e-signature-text" onclick="document.getElementById('RequesterSignature').click();">
                        Click to upload e-sign.<br>Maximum file size: 32MB
                    </div>
                    <input type="hidden" id="hidden-signature" name="hiddenSignature">
                    <img id="signature-preview"
                         src=""
                         style="display: none; cursor: pointer;"
                         alt="Signature Preview"
                         onclick="document.getElementById('RequesterSignature').click();">
                </div>
                <div id="signature-error" style="color: red; display: none;">E-Signature is required.</div>
            </div>

        </div>
        <div class="row">
            <div class="tb">
                <label for="persons">No. of Persons</label>
                <input type="number" id="npersons" name="npersons" min="1" value="{{ old('npersons', 0) }}" step="1">
                <div class="tb">
                    <label for="tables">Tables</label>
                    <input type="number" id="tables" name="tables" min="0" value="{{ old('tables', 0) }}" step="1">
                    <div class="tb">
                        <label for="chairs">Chairs</label>
                        <input type="number" id="chairs" name="chairs" min="0" value="{{ old('chairs', 0) }}" step="1">
                    </div>
                </div>
            </div>
            <div class="fac">
                <label for="otherFacilitiesSelect">Other Facilities</label>
                <select id="otherFacilitiesSelect" name="otherFacilitiesSelect" class="selectpicker">
                    <option disabled selected>Select Facility</option>
                    <option value="Projector" {{ old('otherFacilitiesSelect') == 'Projector' ? 'selected' : '' }}>
                        Projector
                    </option>
                    <option value="Sound System" {{ old('otherFacilitiesSelect') == 'Sound System' ? 'selected' : '' }}>
                        Sound System
                    </option>
                    <option value="Microphone" {{ old('otherFacilitiesSelect') == 'Microphone' ? 'selected' : '' }}>
                        Microphone
                    </option>
                    <!-- Add more facilities here -->
                </select>
                <input type="text" id="otherFacilitiesInput" name="otherFacilitiesInput" style="display:none;"
                       placeholder="Enter Facility" value="{{ old('otherFacilitiesInput') }}">
                <div class="checkbox">
                    <input type="checkbox" id="otherFacilitiesCheckbox" name="otherFacilitiesCheckbox"
                           onclick="toggleInputField('otherFacilities')" {{ old('otherFacilitiesInput') ? 'checked' : '' }}>
                </div>
                <div id="otherFacilitiesError" class="error-message"></div>
            </div>
        </div>
        <div class="row-group-container">
            @foreach (old('date_start', [date('Y-m-d')]) as $index => $date_start)
                <div class="row-group">
                    <div class="row">
                        <div class="inline-field">
                            <label for="date_start">Date Start</label>
                            <input type="date" id="date_start" name="date_start[]"
                                   value="{{ old('date_start.' . $index) }}" required>
                        </div>
                        <div class="inline-field">
                            <label for="date_end">Date End</label>
                            <input type="date" id="date_end" name="date_end[]" value="{{ old('date_end.' . $index) }}"
                                   required>
                            <div class="button-container">
                                <button class="add-btn" type="button" onclick="handleFormActions('addRow')">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="inline-field">
                            <label for="time_start">Time Start</label>
                            <input type="time" id="time_start" name="time_start[]"
                                   value="{{ old('time_start.' . $index) }}" required>
                        </div>
                        <div class="inline-field">
                            <label for="time_end">Time End</label>
                            <input type="time" id="time_end" name="time_end[]" value="{{ old('time_end.' . $index) }}"
                                   required>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="form-footer">
            <button class="submit-btn" type="submit">Submit</button>
        </div>
    </form>
</div>
<script>
    var focalPersonsByOffice = {};
    @foreach(App\Models\Office::all() as $office)
        focalPersonsByOffice[{{ $office->OfficeID }}] = [
            @foreach(App\Models\FocalPerson::where('OfficeID', $office->OfficeID)->get() as $focalPerson)
        {
            value: '{{ $focalPerson->FocalPID }}', text: '{{ $focalPerson->FPName }}'
        },
        @endforeach
    ];
    @endforeach

    function updateFocalPersons(officeId) {
        var focalPersonSelect = document.getElementById('focalPersonSelect');
        focalPersonSelect.innerHTML = ''; // Clear existing options
        var defaultOption = document.createElement('option');
        defaultOption.disabled = true;
        defaultOption.selected = true;
        defaultOption.text = 'Select Focal Person';
        focalPersonSelect.appendChild(defaultOption);

        var focalPersons = focalPersonsByOffice[officeId];
        if (focalPersons) {
            focalPersons.forEach(function (focalPerson) {
                var option = document.createElement('option');
                option.value = focalPerson.value;
                option.text = focalPerson.text;
                focalPersonSelect.appendChild(option);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const today = new Date().toISOString().split('T')[0];
        document.querySelectorAll('input[type="date"]').forEach(function (input) {
            input.setAttribute('min', today);
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        const hiddenSignatureInput = document.getElementById('hidden-signature');
        const preview = document.getElementById('signature-preview');
        const uploadText = document.querySelector('.e-signature-text');

        // Clear signature input and preview on load (if there's any error, don't retain signature)
        hiddenSignatureInput.value = '';
        preview.src = '';
        preview.style.display = 'none';
        uploadText.style.display = 'block';
    });

    function previewSignature(event) {
        const input = event.target;
        const preview = document.getElementById('signature-preview');
        const reader = new FileReader();
        const uploadText = document.querySelector('.e-signature-text');
        const hiddenSignatureInput = document.getElementById('hidden-signature');

        reader.onload = function () {
            const result = reader.result;
            preview.src = result;
            preview.style.display = 'block';
            uploadText.style.display = 'none'; // Hide the upload text
            hiddenSignatureInput.value = result; // Store the image data URL in the hidden input
            document.getElementById('signature-error').style.display = 'none'; // Hide the error message
        };

        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }

    document.querySelector('form').addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent form submission by default
        if (!validateForm()) {
            resetSignature(); // Reset the signature input on error
        } else {
            console.log('Form is valid. Form will be submitted.');
            // You can submit the form via AJAX or proceed with normal form submission here.
            event.target.submit(); // Uncomment if you want to allow normal form submission.
        }
    });

    /**
     * Handles various form actions such as adding or removing rows and previewing the signature.
     *
     * @param {string} action - The action to be performed ('addRow', 'removeRow', 'previewSignature').
     * @param {Event} event - The event object associated with the action.
     */

    function handleFormActions(action, event) {
        switch (action) {
            case 'addRow':
                let rowGroupContainer = document.querySelector('.row-group-container');
                let newRowGroup = document.createElement('div');
                newRowGroup.className = 'row-group';
                newRowGroup.innerHTML = `
                <div class="row">
                    <div class="inline-field">
                        <label for="date_start">Date Start</label>
                        <input type="date" id="date_start" name="date_start[]" required>
                    </div>
                    <div class="inline-field">
                        <label for="date_end">Date End</label>
                        <input type="date" id="date_end" name="date_end[]" required>
                        <div class="remove-container">
                            <button class="remove-btn" onclick="handleFormActions('removeRow', event)">-</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="inline-field">
                        <label for="time_start">Time Start</label>
                        <input type="time" id="time_start" name="time_start[]" required>
                    </div>
                    <div class="inline-field">
                        <label for="time_end">Time End</label>
                        <input type="time" id="time_end" name="time_end[]" required>
                    </div>
                </div>
            `;
                rowGroupContainer.appendChild(newRowGroup);
                break;
            case 'removeRow':
                event.preventDefault();
                event.target.closest('.row-group').remove();
                break;
            case 'previewSignature':
                const input = event.target;
                const preview = document.getElementById('signature-preview');
                const reader = new FileReader();
                const uploadText = document.querySelector('.e-signature-text');
                reader.onload = function () {
                    preview.src = reader.result;
                    preview.style.display = 'block';
                    uploadText.style.display = 'none';
                };
                reader.onerror = function () {
                    console.error('Error reading file');
                };
                if (input.files && input.files[0]) {
                    reader.readAsDataURL(input.files[0]);
                }
                break;
        }
    }

document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');

    form.addEventListener('submit', function(event) {
        if (!validateForm()) {
            event.preventDefault();
            console.log('Form submission prevented due to validation errors.');
        } else {
            console.log('Form is valid. Form will be submitted.');
            submitButton.disabled = true; // Disable the submit button to prevent multiple submissions
        }
    });
});

function validateForm() {
    let isValid = true;
    let errorMessages = [];

    // Check required fields
    document.querySelectorAll('input[required], select[required]').forEach(function(element) {
        if (!element.value) {
            isValid = false;
            errorMessages.push(element.previousElementSibling.textContent + " is required.");
        }
    });

    // Check date fields
    document.querySelectorAll('.row-group').forEach(function(rowGroup) {
        let dateStart = rowGroup.querySelector('input[name="date_start[]"]').value;
        let dateEnd = rowGroup.querySelector('input[name="date_end[]"]').value;
        let timeStart = rowGroup.querySelector('input[name="time_start[]"]').value;
        let timeEnd = rowGroup.querySelector('input[name="time_end[]"]').value;

        if (dateStart && dateEnd && dateStart > dateEnd) {
            isValid = false;
            errorMessages.push("Date Start must be before Date End.");
        }

        if (timeStart && timeEnd && timeStart > timeEnd) {
            isValid = false;
            errorMessages.push("Time Start must be before Time End.");
        }
    });

    // Check if the signature is uploaded
    let hiddenSignatureInput = document.getElementById('hidden-signature').value;
    if (!hiddenSignatureInput) {
        isValid = false;
        errorMessages.push("E-Signature is required.");
        document.getElementById('signature-error').style.display = 'block'; // Show the error message
    }

    // Optional "Other Facilities" validation: skip if no selection is made
    let otherFacilitiesSelect = document.getElementById('otherFacilitiesSelect').value;
    let otherFacilitiesInput = document.getElementById('otherFacilitiesInput').value;

    if (document.getElementById('otherFacilitiesCheckbox').checked && !otherFacilitiesInput) {
        isValid = false;
        errorMessages.push("Please specify the other facility.");
    }

    // If there are errors, display them on the page
    if (!isValid) {
        displayErrorMessages(errorMessages);
    }

    return isValid;
}

function displayErrorMessages(messages) {
    alert("Form submission failed.\n\n" + messages.join("\n"));
}

    /**
     * Toggles between a select and an input field when a checkbox is clicked.
     *
     * @param {string} fieldName - The base name of the field ('purpose' or 'focalPerson').
     */

    document.addEventListener('DOMContentLoaded', function () {
        // Check if there is old input for the focal person and toggle the input field accordingly
        if ("{{ old('focalPersonInput') }}") {
            document.getElementById('focalPersonInput').style.display = 'block';
            document.getElementById('focalPersonSelect').style.display = 'none';
            document.getElementById('focalPersonCheckbox').checked = true;
        }

        // Check if there is old input for the purpose and toggle the input field accordingly
        if ("{{ old('purposeInput') }}") {
            document.getElementById('purposeInput').style.display = 'block';
            document.getElementById('purposeSelect').style.display = 'none';
            document.getElementById('purposeCheckbox').checked = true;
        }

        // Function to toggle the input field for focal person and purpose
        window.toggleInputField = function (field) {
            var inputField = document.getElementById(field + 'Input');
            var selectField = document.getElementById(field + 'Select');
            var checkbox = document.getElementById(field + 'Checkbox');

            if (checkbox.checked) {
                inputField.style.display = 'block';
                selectField.style.display = 'none';
            } else {
                inputField.style.display = 'none';
                selectField.style.display = 'block';
            }
        };
    });
</script>
</body>
</html>


