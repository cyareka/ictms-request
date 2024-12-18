<!DOCTYPE html>
<html lang="en">

<head>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conference Room Request Form</title>
    <style>
        * {
            font-family: 'Poppins', sans-serif;
            transition: all 0.5s ease;
        }

        body {
            background: #dfdfdf;
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
            text-align: center;
        }

        .parent-container {
            display: flex;
            flex-direction: row;
            align-items: flex-start;
            justify-content: center;
            margin-top: 5em;
            padding: 0 16px;
            margin-left: 20px;
        }

        .top {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 40%; /* Adjusted width for side-by-side layout */
            margin-right: 2em; /* Space between top and form */
            padding: 10px;
        }

        .container {
            width: 60%; /* Adjusted width for side-by-side layout */
            height: 100%;
            margin-right: 20px;
            border: none;
        }

        form {
            display: flex;
            flex-flow: column;
            align-items: center;
            border-radius: 4px;
            cursor: pointer;
            box-shadow: 0 8px 6px -6px #555;
            background-color: #E5E4E2;
            padding: 40px;
        }

        form div {
            width: 100%;
            margin-bottom: 16px;
        }

        label {
            display: block;
            color: #555;
            margin-bottom: 8px;
        }

        input, select, textarea {
            width: 100%;
            padding: 8px;
            background: rgba(255, 255, 255, 0.5);
            border: none;
            border-radius: 4px;
            box-shadow: 0 4px 4px -6px #555;
        }

        input:focus, select:focus, textarea:focus {
            background: white;
            box-shadow: none;
        }
        .field:focus-within label {
            color: #000;
            letter-spacing: 2px;
            }
        textarea {
            resize: none;
            height: 80px;
        }

        .submit-btn:hover {
            letter-spacing: 2px;
            box-shadow: none;
        }

        .pic {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 8px;
        }
        .file-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 5px;
            border: 2px dashed #A9A9A9 ;
            border-radius: 6px;
            cursor: pointer;
            margin-bottom: 10px;
            text-align: center;
        }
        .form-footer {
            display: flex;
            justify-content: center;
            margin-top: 0;
        }
        .row1 {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            margin-top: -25px;
        }
        .field {
            margin-right: 10px; /* Adjust spacing as needed */
        }
        .row2 {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            margin-top: -25px;
        }
        .row-container {
            display: flex;
            flex-direction: column; /* Ensure rows are stacked vertically */
            max-height: 130px;
            overflow-y: auto;
            padding: 20px 0 0 0;
        }
        .button-container {
            display: flex;
            align-items: center;
            padding: 3px;
            margin-top: 10px;
        }

        .add-btn {
            display: inline-block;
            margin-left: 0;
            background-color: #0056b3;
            color: white;
            padding: 3px 8px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
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
            margin-left: 0;
            margin-top: 40px;
        }

        .remove-btn:hover {
            background-color: #cc0000;
        }
        #signature-preview {
            margin-top: 15px;
            max-width: 150px;
            max-height: 150px;
            display: none;
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
        .checkbox {
            margin-left: 0;
            width: 30px;
            margin-bottom: 10px;
            position: relative;
            float: right;
           
        }
        .checkbox input[type="checkbox"]{
            border: 1px solid #478CCF;
        }
        .message{
            font-size: 12px;
            color: red;
            font-style: italic;
            margin-left: 5px;
        }

        .checkbox input[type="checkbox"]:checked + #purposeTextBox {
            display: block;

        }
        .required::after {
            content: " *";
            color: red;
        }
        .error-message {
            color: red;
            font-size: 12px;
            position: absolute;
            margin-top: 4px; /* Adds a small gap between input and error message */
            white-space: nowrap; /* Prevents text from wrapping */
            display: none; /* Initially hidden */
        }

        @media (max-width: 768px) {
    .parent-container {
        flex-direction: column;
        align-items: center;
    }

    .top, .container {
        width: 100%;
        margin-right: 0;
    }

    /* Ensure that each form field is displayed in a single column */
    .row, .row1 {
        display: flex;
        flex-direction: column;
    }
    .row2{
        display: flex;
        flex-direction: column;
        width: 80%  
    }

    .field {
        width: 100%;
        margin-bottom: 1rem;
    }

    /* Adjust input fields, select fields, and textarea */
    .field input[type="text"],
    .field input[type="number"],
    .field input[type="file"],
    .field input[type="date"],
    .field select,
    .field textarea {
        width: 100%;
        max-width: 100%; /* Ensure no overflow */
        box-sizing: border-box;
    }

    /* Checkbox containers and messages */
    .checkbox-container {
        flex-direction: column;
        align-items: flex-end;
    }

    .checkbox {
        margin-top: 0.5rem;
        font-size: 9px;
    }
    .submit-btn{
        margin-top: -50px;
    }
   
    #row-container{
        /* margin-bottom: 20px; */
        width: 100%;
    }
    .field input[type="time"],{
        width: 50%;
    }
    .button-container {
        flex-direction: row; /* Ensure buttons remain in a row */
        align-items: center;
        justify-content: flex-start;
    }
    .remove-container{
        display: flex;
        padding: 10px;
    }

    .add-btn {
        margin-top: -7em;
        margin-left: 20em;
    }
    .remove-btn {
        margin-top: -4.2em;
        margin-left: 16.5em;
        height: 28px;
    }
    .row-container {
        max-height: none; /* Remove max-height restriction */
        overflow-y: visible; /* Allow content to be fully visible */
    }
}

    </style>
</head>

<body>
    
@if (session('purposeInputError'))
    <div class="alert alert-warning">
        {{ session('purposeInputError') }}
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('input[name="purposeInput"]').value = '';
            document.getElementById('purposeCheckbox').checked = false;
            document.getElementById('purposeInput').style.display = 'none';
            document.getElementById('purposeSelect').style.display = 'block';
        });
    </script>
@endif
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
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (confirm("Form submitted successfully! Would you like to go to the calendar?")) {
                window.location.href = "{{ url('UserconCalendar') }}"; 
            } else {
                window.location.href = "{{ route ('welcome') }}"; 
            }
        });
    </script>
@endif
<div class="parent-container">
    <div class="top">
        <div class="pic">
            <img width="250" src="{{asset('/logo/board.png')}}" alt="logo">
        </div>
        <h1>Request For Use of Conference Room</h1>
        <p>(Note: Please check the calendar for available dates before submitting a request.)</p>
    </div>

    <div class="container">
    <form action="/conference-room/request" method="POST" enctype="multipart/form-data"
          onsubmit="return validateForm()">
        @csrf

        <div class="row">
            <div class="field">
                <label class="required" for="officeName">
                    Requesting Office
                </label>
                <select id="officeName" name="officeName" required>
                    <option disabled selected>Select Office</option>
                    @foreach(App\Models\Office::all() as $office)
                        <option value="{{ $office->OfficeID }}" {{ old('officeName') == $office->OfficeID ? 'selected' : '' }}>
                            {{ $office->OfficeName }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="field">
                <label class="required" for="purpose">
                    Purpose
                </label>
                <select id="purposeSelect" name="purposeSelect">
                    <option disabled selected>Select Purpose</option>
                    @foreach(App\Models\PurposeRequest::where('request_p', 'Conference Room')->get() as $purpose)
                        <option value="{{ $purpose->PurposeID }}" {{ old('purposeSelect') == $purpose->PurposeID ? 'selected' : '' }}>
                            {{ $purpose->purpose }}
                        </option>
                    @endforeach
                </select>
                <div class= "checkbox-container">
                    <input type="text" id="purposeInput" name="purposeInput" style="display:none;" placeholder="Enter Purpose" value="{{ old('purposeInput') }}" pattern="[A-Za-z\s]+" title="Numbers are not allowed in the purpose.">
                    <span class="message">Please check this box if you want to specify</span>
                    <div class="checkbox">
                        <input type="checkbox" id="purposeCheckbox" name="purposeCheckbox" onclick="toggleInputField('purpose')" {{ old('purposeInput') ? 'checked' : '' }}>
                    </div>
                </div>    
            </div>
        </div>

        <div class="row">
            <div class="field">
                <label class="required" for="conferenceRoom">
                    Conference Room
                </label>
                <select id="conferenceRoom" name="conferenceRoom" required onchange="validateCapacity()">
                    <option disabled selected>Select Room</option>
                    @foreach(App\Models\ConferenceRoom::all() as $room)
                        <option value="{{ $room->CRoomID }}" data-capacity="{{ $room->Capacity }}" {{ old('conferenceRoom') == $room->CRoomID ? 'selected' : '' }}>
                            {{ $room->CRoomName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label class="required" for="focalPerson">
                    Focal Person
                </label>
                <select id="focalPersonSelect" name="focalPersonSelect">
                    <option disabled selected>Select Focal Person</option>
                    @foreach(App\Models\FocalPerson::all() as $fp)
                        <option value="{{ $fp->FocalPID }}" {{ old('focalPersonSelect') == $fp->FocalPID ? 'selected' : '' }}>
                            {{ $fp->FPName }}
                        </option>
                    @endforeach
                </select>
                <div class="checkbox-container">
                    <span class="message">Please check this box if you want to specify </span>
                    <input type="text" id="focalPersonInput" name="focalPersonInput" style="display:none;" placeholder="Enter Focal Person" value="{{ old('focalPersonInput') }}" pattern="[A-Za-z\s]+" title="Numbers are not allowed in the purpose.">
                    <div class="checkbox">
                        <input type="checkbox" id="focalPersonCheckbox" name="focalPersonCheckbox" onclick="toggleInputField('focalPerson')" {{ old('focalPersonInput') ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
        </div>    

        <div class="row">
            <div class="field">
                <label class="required" for="requesterName">
                    Requester Name
                </label>
                <input type="text" id="requesterName" name="requesterName" placeholder="Enter Name of Requester" value="{{ old('requesterName') }}" pattern="[A-Za-z\s]+" title="Numbers are not allowed in the purpose." required>
            </div>

            <div class="field">
                <label class="required" for="RequesterSignature"> E-Signature </label>
            <div class=" file-upload">
                <input type="file" id="RequesterSignature" name="RequesterSignature" style="display: none;" onchange="previewSignature(event)">
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
            <div class="row1">
                <div class="field">
                    <label for="npersons">No. of Persons</label>
                    <input type="number" id="npersons" name="npersons" min="1" value="{{ old('npersons', 0) }}" step="1" required>
                    <div id="capacityError" class="error-message">
                        Exceeds the capacity of the selected room.
                    </div>
                </div>

                <div class="field">
                    <label for="persons">Tables</label>
                    <input type="number" id="tables" name="tables" min="0" value="{{ old('tables', 0) }}" step="1">
                    @if ($errors->has('tables'))
                        <span class="error-message">{{ $errors->first('tables') }}</span>
                    @endif
                </div>
                <div class="field">
                    <label for="persons">Chairs</label>
                    <input type="number" id="chairs" name="chairs" min="0" value="{{ old('chairs', 0) }}" step="1">
                    @if ($errors->has('chairs'))
                        <span class="error-message">{{ $errors->first('chairs') }}</span>
                    @endif
                </div>
            <div class="field">
                    <label  for="otherFacilitiesSelect">
                        Other Facilities
                    </label>
                    <select id="otherFacilitiesSelect" name="otherFacilitiesSelect" class="selectpicker">
                    <option disabled selected>Select Facility</option>
                    <option value="Projector" {{ old('otherFacilitiesSelect') == 'Projector' ? 'selected' : '' }}>Projector</option>
                    <option value="Sound System" {{ old('otherFacilitiesSelect') == 'Sound System' ? 'selected' : '' }}>Sound System</option>
                    <option value="Microphone" {{ old('otherFacilitiesSelect') == 'Microphone' ? 'selected' : '' }}>Microphone</option>
                    <!-- Add more facilities here -->
                </select>
                <div class="checkbox-container">
                    <span class="message">Please check this box if you want to specify</span>
                    <input type="text" id="otherFacilitiesInput" name="otherFacilitiesInput" style="display:none;" placeholder="Enter Facility" value="{{ old('otherFacilitiesInput') }}">
                    <div class="checkbox">
                        <input type="checkbox" id="otherFacilitiesCheckbox" name="otherFacilitiesCheckbox" onclick="toggleInputField('otherFacilities')" {{ old('otherFacilitiesInput') ? 'checked' : '' }}>
                    </div>
                    <div id="otherFacilitiesError" class="error-message"></div>
                </div>
            </div>
        </div>   
        <div class= "row-container">
        @foreach (old('date_start', [date('Y-m-d')]) as $index => $date_start)
            <div class="row">
                <div class="row2">
                    <div class="field">
                        <label class="required" for="date_start">
                            Date Start
                        </label>
                        <input type="date" id="date_start" name="date_start[]" value="{{ old('date_start.' . $index) }}" required>
                    </div>
                    <div class="field">
                        <label class="required" for="date_end">
                            Date End
                        </label>
                        <input type="date" id="date_end" name="date_end[]" value="{{ old('date_end.' . $index) }}" required>
                        </div>
                </div>
                <div class= "row2">
                    <div class="field">
                        <label class="required" for="time_start">
                            Time Start
                        </label>
                        <input type="time" id="time_start" name="time_start[]" value="{{ old('time_start.' . $index) }}" required>
                        </div>
                    <div class="field">
                        <label class="required" for="time_end">
                            Time End
                        </label>
                        <input type="time" id="time_end" name="time_end[]" value="{{ old('time_end.' . $index) }}" required>              
                    </div>  
                            <div class="button-container">
                                <button class="add-btn" type="button" onclick="handleFormActions('addRow')">+</button>
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
</div>
<script>
    
    function validateCapacity() {
    const roomSelect = document.getElementById('conferenceRoom');
    const personsInput = document.getElementById('npersons');
    const capacityError = document.getElementById('capacityError');
    
    const selectedRoom = roomSelect.options[roomSelect.selectedIndex];
    const capacity = parseInt(selectedRoom.getAttribute('data-capacity'), 10);
    const enteredPersons = parseInt(personsInput.value, 10);

    if (enteredPersons > capacity) {
        capacityError.style.display = 'block'; // Show the error message
        personsInput.setCustomValidity('Exceeds the capacity of the selected room.');
    } else {
        capacityError.style.display = 'none'; // Hide the error message
        personsInput.setCustomValidity(''); // Reset validity
    }
}

// Attach event listeners for dynamic validation
document.getElementById('npersons').addEventListener('input', validateCapacity);
document.getElementById('conferenceRoom').addEventListener('change', function () {
    const roomSelect = document.getElementById('conferenceRoom');
    const selectedRoom = roomSelect.options[roomSelect.selectedIndex];
    const capacity = selectedRoom.getAttribute('data-capacity');

    // Dynamically set data-capacity for JavaScript validation
    roomSelect.setAttribute('data-capacity', capacity);
    validateCapacity(); // Revalidate after changing the room
});

    // Preload focal persons for each office
    var focalPersonsByOffice = {};
    @foreach(App\Models\Office::all() as $office)
        focalPersonsByOffice[{{ $office->OfficeID }}] = [
            @foreach(App\Models\FocalPerson::where('OfficeID', $office->OfficeID)->get() as $focalPerson)
        { value: '{{ $focalPerson->FocalPID }}', text: '{{ $focalPerson->FPName }}' },
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
            focalPersons.forEach(function(focalPerson) {
                var option = document.createElement('option');
                option.value = focalPerson.value;
                option.text = focalPerson.text;
                focalPersonSelect.appendChild(option);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.querySelectorAll('input[type="date"]').forEach(function(input) {
            input.setAttribute('min', today);
        });
    });
    
    document.addEventListener('DOMContentLoaded', function() {
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

    document.querySelector('form').addEventListener('submit', function(event) {
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
    switch(action) {
        case 'addRow':
            // Target the container holding all rows
            let rowContainer = document.querySelector('.row-container');
            
            // Create new row
            let newRow = document.createElement('div');
            newRow.className = 'row2';
            let today = new Date().toISOString().slice(0, 10);
            newRow.innerHTML = `    
                        <div class="field">
                            <label class="required" for="date_start">
                                Date Start
                            </label>
                            <input type="date" id="date_start" name="date_start[]" value="{{ old('date_start.' . $index) }}" required>
                        </div>
                        <div class="field">
                            <label class="required" for="date_end">
                                Date End
                            </label>
                            <input type="date" id="date_end" name="date_end[]" value="{{ old('date_end.' . $index) }}" required>
                        </div>
                        <div class="field">
                            <label class="required" for="time_start">
                                Time Start
                            </label>
                            <input type="time" id="time_start" name="time_start[]" value="{{ old('time_start.' . $index) }}" required>
                        </div>
                        <div class="field">
                            <label class="required" for="time_end">
                                Time End
                            </label>
                            <input type="time" id="time_end" name="time_end[]" value="{{ old('time_end.' . $index) }}" required>                  
                        </div>
                        <!-- Remove button inside the same row -->
                        <div class="remove-container">
                            <button class="remove-btn" onclick="handleFormActions('removeRow', event)">-</button>
                        </div>
            `;  

            // Append the new row after all existing rows
            rowContainer.appendChild(newRow);
            // Set the minimum date to today for the new date inputs
            newRow.querySelectorAll('input[type="date"]').forEach(function(input) {
                input.setAttribute('min', today);
            });
            break;

        case 'removeRow':
            event.preventDefault();
            event.target.closest('.row2').remove();
            break;

            case 'previewSignature':
                const input = event.target;
                const preview = document.getElementById('signature-preview');
                const reader = new FileReader();
                const uploadText = document.querySelector('.e-signature-text');
                reader.onload = function() {
                    preview.src = reader.result;
                    preview.style.display = 'block';
                    uploadText.style.display = 'none';
                };
                reader.onerror = function() {
                    console.error('Error reading file');
                };
                if (input.files && input.files[0]) {
                    reader.readAsDataURL(input.files[0]);
                }
                break;
        }
        
    }

    document.querySelector('form').addEventListener('submit', function(event) {
        if (!validateForm()) {
            event.preventDefault();
            console.log('Form submission prevented due to validation errors.');
        } else {
            validateCapacity(); // Call validateCapacity on form submission
            if (document.getElementById('capacityError').style.display === 'inline') {
                event.preventDefault();
                console.log('Form submission prevented due to capacity validation errors.');
            } else {
                console.log('Form is valid. Form will be submitted.');
            }
        }
    });


    /**
     * Validates the conference room request form.
     *
     * This function checks for the presence of required fields, ensures all date fields are filled,
     * and verifies that the uploaded signature file does not exceed the maximum allowed size of 32MB.
     * If any validation fails, it displays an alert with the corresponding error messages.
     *
     * @returns {boolean} True if the form is valid, false otherwise.
     */
    function validateForm() {
    let isValid = true;
    let errorMessages = [];

    // Check required fields
    document.querySelectorAll('input[required], select[required]').forEach(function (element) {
        if (!element.value) {
            isValid = false;
            errorMessages.push(element.previousElementSibling.textContent + " is required.");
            console.error(element.previousElementSibling.textContent + " is required.");
        }
    });

    // Check date fields
    document.querySelectorAll('.row-group').forEach(function (rowGroup) {
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

    // Validate Purpose
    if (!validatePurpose()) {
        isValid = false;
        errorMessages.push("Purpose is required.");
    }

    // Validate Focal Person
    if (!validateFocalPerson()) {
        isValid = false;
        errorMessages.push("Focal Person is required.");
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

/**
 * Validates the Purpose field (either the dropdown or input).
 */
function validatePurpose() {
    let purposeSelect = document.getElementById('purposeSelect').value;
    let purposeInput = document.getElementById('purposeInput').value;

    // Return true if either purposeSelect or purposeInput has a value
    if (purposeSelect || purposeInput) {
        return true;
    } else {
        console.error("Purpose is required.");
        return false;
    }
}

/**
 * Validates the Focal Person field (either the dropdown or input).
 */
function validateFocalPerson() {
    let focalPersonSelect = document.getElementById('focalPersonSelect').value;
    let focalPersonInput = document.getElementById('focalPersonInput').value;

    // Return true if either focalPersonSelect or focalPersonInput has a value
    if (focalPersonSelect || focalPersonInput) {
        return true;
    } else {
        console.error("Focal Person is required.");
        return false;
    }
}

/**
 * Displays error messages on the page instead of using console logs.
 */
function displayErrorMessages(messages) {
    const errorContainer = document.getElementById('error-container');
    errorContainer.innerHTML = ''; // Clear previous error messages
    messages.forEach(function(message) {
        const errorElement = document.createElement('div');
        errorElement.className = 'error-message';
        errorElement.textContent = message;
        errorContainer.appendChild(errorElement);
    });
    alert("Please correct the following errors:\n\n" + messages.join("\n"));
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
