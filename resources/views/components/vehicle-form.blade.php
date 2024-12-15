<!DOCTYPE html>
<html lang="en">
    <head>
        <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Vehicle Request Form</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css">
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
            margin-top: 3.5em;
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
            margin-bottom: 15px;
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
            box-shadow: 0 5px 6px -6px #555;
        }

        input:focus, select:focus, textarea:focus {
            background: white;
            box-shadow: none;
        }
        .field:focus-within label {
            color: #000;
            letter-spacing: 2px;
            }
        .passenger-field:focus-within label {
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
        .row , .datetime-group {
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
        .submit-btn {
            background-color: #354e7d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
        }

        .row1, .input-field {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            margin-top: -25px;
        }
        .field, .date-field {
            margin-right: 10px; /* Adjust spacing as needed */
        }
        .passenger-container {
            display: flex;
            flex-direction: column; /* Align items vertically */
        }
        .passenger-field select{
            width: 90%;
        }
        #passenger-container{
            margin-top: 10px;
            margin-bottom: 10px;
            padding: 15px;
            width: 100%;
        }
        .row2 {
            display: flex;
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            margin-top: -25px;
        }
    
        .button-container {
            display: flex;
            align-items: center;
            padding: 3px;
            margin-top: 0;
        }
        .add-datetime-btn {
            background-color: #0056b3;
            color: white;
            padding: 3px 8px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 33px;
        }

        .remove-datetime-btn {
            background-color: #ff4d4d;
            color: white;
            padding: 3px 8px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-left: 1px;
            margin-top: 33px;
        }
        .add-passenger-btn,
        .remove-passenger-btn {
            padding: 3px 8px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        .add-passenger-btn {
            background-color: #0056b3;
            color: white;
            align-self: flex-end;
            margin-top: 5px;
        }

        .remove-passenger-btn {
            background-color: #ff4d4d;
            color: white;
        }

        .add-passenger-btn:hover {
            background-color: #003d80;
        }

        .remove-passenger-btn:hover {
            background-color: #cc0000;
        }
        #date-time-container {
            margin-top: -15px;
            max-height: 130px;
            overflow-y: auto;
            padding: 20px 0 0 0; /* Add padding to create space around the content */   
        }
        
        #signature-preview {
            margin-top: 15px;
            max-width: 150px;
            max-height: 150px;
            display: none;
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
        #passenger-container {
                display: flex;
                flex-direction: column;
                max-height: 100px; /* Increase max height if needed */
                overflow-y: auto;
                margin-bottom: 10px;
                margin-top: 8px; /* Add some margin for spacing */
                width: 100%; /* Ensure it takes the full width in responsive view */
            }
        .iti__flag-container{
            margin-top: 20px;
            padding: 5px;
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
    .row, .row1, .datetime-group {
        display: flex;
        flex-direction: column;
    }
    .row2{
        display: flex;
        flex-direction: column;
        width: 80%  
    }

    .field{
        width: 100%;
        margin-bottom: 1rem;
    }
    .input-field {
        flex-direction: column;
        width: 80%;
    }
    #date-time-container{
        overflow-y: visible;
        max-height: none;
    }
    /* .submit-btn{
        margin-top: 9em;
    } */

    /* Adjust input fields, select fields, and textarea */
    .field input[type="text"],
    .field input[type="number"],
    .field input[type="file"],
    .input-field input[type="date"],
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
    
    .button-container {
        flex-direction: row; /* Ensure buttons remain in a row */
        align-items: center;
        justify-content: flex-start;
    }
   
    .add-datetime-btn, .remove-datetime-btn {
        margin-top: -6em;
        margin-left: 17em;
    }
    .remove-passenger-btn{
        display: flex;
        margin-top: -2.5em;
        margin-left: 18em;
    }
    #passenger-container{
        overflow-y: vissible;
        max-height: none;
        width: 100%;
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
                window.location.href = "{{ url('UservehiCalendar') }}"; 
            } else {
                window.location.href = "{{ route ('welcome') }}"; 
            }
        });
    </script>
@endif
    <div class= "parent-container">
            <div class="top">
                <div class="pic">
                    <img width="250" src="{{asset('/logo/travel.png')}}" alt="logo">
                </div>
                <h1>Request For Use of Vehicle</h1>
                <p>(Note: Please check the calendar for available dates before submitting a request.)
                </p>
            </div>
        <div class="container">
            <form action="/vehicle/request" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
            @csrf
                <div class="row">
                    <div class="field">
                        <label class="required" > Requesting Office</label>
                            <select id="officeName" name="officeName" placeholder="Enter Purpose" required>
                                <option disabled selected>Select Office</option>
                                @foreach(App\Models\Office::all() as $office)
                                    <option value="{{ $office->OfficeID }}" {{ old('officeName') == $office->OfficeID ? 'selected' : '' }}>
                                        {{ $office->OfficeName }}
                                    </option>
                                @endforeach
                            </select>
                    </div>
                    <div class="field">
                        <label for="purpose" class="required">Purpose </label>
                            <select id="purposeSelect" name="purposeSelect" required>
                                <option disabled selected>Select Purpose</option>
                                @foreach(App\Models\PurposeRequest::where('request_p', 'Vehicle')->get() as $purpose)
                                    <option value="{{ $purpose->PurposeID }}" {{ old('purposeSelect') == $purpose->PurposeID ? 'selected' : '' }}>{{ $purpose->purpose }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="checkbox-container">
                                <span class="message">Please check this box if you want to specify</span>
                                <input type="text" id="purposeInput" name="purposeInput" value="{{ old('purposeInput') }}" style="display:none;" placeholder="Enter Purpose" pattern="[A-Za-z\s]+" title="Numbers are not allowed in the purpose.">
                                <div class="checkbox">
                                <input type="checkbox" id="purposeCheckbox" name="purposeCheckbox" onclick="toggleInputField('purpose')" {{ old('purposeInput') ? 'checked' : '' }}>
                                </div>
                            </div>
                    </div>
                </div>
                <div class= "row">
                    <div class="field">
                        <label class="required"> Requester Name</label>
                        <input type="text" name="RequesterName" placeholder="Enter Name" value="{{ old('RequesterName') }}" autocapitalize="words" pattern="[A-Za-z\s]+" title="Numbers are not allowed in the purpose." required/>
                        </div>
                    <div class="field">
                        <label class="required" >Requester Email</label>
                        <input type="text" name="RequesterEmail" placeholder="Enter Email" value="{{ old('RequesterEmail') }}"  required/>
                    </div>
                </div>
                <div class= "row">
                    <div class="field">
                    <label for="ContactNo" class="required" >Contact No.</label>
                    <input type="tel" id="ContactNo" name="RequesterContact" placeholder="956 566 5678" value="{{ old('RequesterContact') }}" required maxlength="10" pattern="\d{10}" title="Please enter a valid 10-digit phone number." inputmode="numeric">
                    </div>

                    <div class="field">
                        <label for="RequesterSignature" class="required"> E-Signature </label>
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
                <div class= "row">
                    <div class="field">
                        <label class="required" >Destination </label>
                        <input type="text" name="Destination" placeholder="Enter Place" value="{{ old('Destination') }}" autocapitalize="words" required/>
                    </div>
                    <div class="passenger-field">
                        <label class="required">Passenger Name/s </label>
                            <select name="passengers[]" required>
                                <option disabled selected>Select a passenger</option>
                                @php
                                    $employees = App\Models\Employee::all()->sortBy('EmployeeName');
                                @endphp

                                @foreach($employees as $passenger)
                                    <option value="{{ $passenger->EmployeeID }}" {{ in_array($passenger->EmployeeID, old('passengers', [])) ? 'selected' : '' }}>
                                        {{ $passenger->EmployeeName }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="add-passenger-btn" type="button" onclick="addPassenger()">+</button>
                                <div id="passenger-container">
                                <!-- New passenger fields will be appended here -->
                                @if(old('passengers'))
                                @foreach(old('passengers') as $index => $passengerID)
                                    @if($index > 0) <!-- Skip the first passenger as it is already rendered above -->
                                        <div class="passenger-field">
                                            <select name="passengers[]" required>
                                                <option disabled selected>Select a passenger</option>
                                                @foreach($employees as $passenger)
                                                    <option value="{{ $passenger->EmployeeID }}" {{ $passenger->EmployeeID == $passengerID ? 'selected' : '' }}>
                                                        {{ $passenger->EmployeeName }}
                                                    </option>
                                                @endforeach
                                            </select>
                                                <button class="remove-passenger-btn" type="button" onclick="removePassenger(this)">-</button>
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                            </div>
                    </div>
                            
                </div>
                <div id="date-time-container">
                    <div class="input-group datetime-group">
                        <div class="input-field">
                            <div class="date-field">
                            <label class="required" for="date_start"> Date Start</label>
                                <input type="date" id="date_start" name="date_start[]" value="{{ old('date_start.0') }}" required/>
                            </div>
                            <div class="date-field">
                            <label class="required" for="date_end">Date End</label>
                                <input type="date" id="date_end" name="date_end[]" value="{{ old('date_end.0') }}" required/>
                            </div>
                        </div>
                        <div class="input-field">
                            <div class="date-field">
                                <label class="required" >Time </label>
                                <input type="time" name="time_start[]" value="{{ old('time_start.0') }}" required/>
                            </div>
                            <div class="button-container">
                                    <button class="add-datetime-btn" type="button" onclick="addDateTime()">+</button>
                            </div>
                            </div>  
                    </div>
                    @if(old('date_start'))
                        @foreach(old('date_start') as $index => $dateStart)
                            @if($index > 0) <!-- Skip the first date/time as it is already rendered above -->
                                <div class="input-group datetime-group">
                                    <div class="input-field">
                                        <div class="date-field">
                                            <label class="required" for="date_start_{{ $index }}">Date Start</label>
                                            <input type="date" id="date_start_{{ $index }}" name="date_start[]" value="{{ old('date_start.' . $index) }}" required/>
                                        </div>
                                        <div class="date-field">
                                            <label class="required" for="date_end_{{ $index }}">Date End</label>
                                            <input type="date" id="date_end_{{ $index }}" name="date_end[]" value="{{ old('date_end.' . $index) }}" required/>
                                        </div>
                                    </div>
                                    <div class="input-field">
                                        <div class="date-field">
                                            <label class="required">Time</label>
                                            <input type="time" name="time_start[]" value="{{ old('time_start.' . $index) }}" required/>
                                        </div>
                                        <div class="button-container">
                                            <button class="remove-datetime-btn" type="button" onclick="removeDateTime(this)">-</button>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
                    <div class="form-footer">
                        <button class="submit-btn" type="submit">Submit</button>
                    </div> 
            </div>
            </form>    
        </div>       
    </div>    

        <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const ContactInputField = document.querySelector("#ContactNo");
            if (ContactInputField) {
                const phoneInput = window.intlTelInput(ContactInputField, {
                    onlyCountries: ['ph'], // Restrict to Philippines
                    initialCountry: 'ph',
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
                });

                // Update the placeholder with a custom format
                const updatePlaceholder = () => {
                    let exampleNumber = phoneInput.getExampleNumber(phoneInput.getSelectedCountryData().iso2, true, intlTelInputUtils.numberFormat.INTERNATIONAL);
                    if (exampleNumber.startsWith("+63 0")) {
                        exampleNumber = exampleNumber.replace("+63 0", "+63 9");
                    }
                    ContactInputField.placeholder = exampleNumber;
                };

                // Initial placeholder update
                updatePlaceholder();

                // Update placeholder on country change
                ContactInputField.addEventListener("countrychange", updatePlaceholder);
            } else {
                console.error("Element with ID 'ContactNo' not found.");
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
        const passengerContainer = document.getElementById('passenger-container');
        const passengerSelects = document.querySelectorAll('select[name="passengers[]"]');

        function updatePassengerOptions() {
            const selectedPassengers = Array.from(passengerSelects)
                .map(select => select.value)
                .filter(value => value !== '');

            passengerSelects.forEach(select => {
                const currentValue = select.value;
                const options = Array.from(select.options);

                options.forEach(option => {
                    if (selectedPassengers.includes(option.value) && option.value !== currentValue) {
                        option.style.display = 'none';
                    } else {
                        option.style.display = 'block';
                    }
                });
            });
        }

        passengerSelects.forEach(select => {
            select.addEventListener('change', updatePassengerOptions);
        });

        function addPassenger() {
        let passengerContainer = document.getElementById('passenger-container');
        let newPassengerField = document.createElement('div');
        newPassengerField.className = 'passenger-field';
        newPassengerField.innerHTML = `
            <select name="passengers[]" required>
                <option disabled selected>Select a passenger</option>
                @foreach($employees as $passenger)
                    <option value="{{ $passenger->EmployeeID }}">{{ $passenger->EmployeeName }}</option>
                @endforeach
            </select>
            <button class="remove-passenger-btn" type="button" onclick="removePassenger(this)">-</button>
        `;
        passengerContainer.appendChild(newPassengerField);
    }

        function removePassenger(button) {
            const passengerField = button.parentElement;
            passengerField.remove();
            updatePassengerOptions();
        }

        // Initialize passenger options
        updatePassengerOptions();

    });

    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.querySelectorAll('input[type="date"]').forEach(function(input) {
            input.setAttribute('min', today);
        });
    });

    function addPassenger() {
            const passengerContainer = document.getElementById('passenger-container');
            const newPassengerField = document.createElement('div');
            newPassengerField.className = 'passenger-field';
            newPassengerField.innerHTML = `
                <select name="passengers[]" required>
                    <option disabled selected>Select a passenger</option>
                    @foreach($employees as $passenger)
                        <option value="{{ $passenger->EmployeeID }}">{{ $passenger->EmployeeName }}</option>
                    @endforeach
                </select>
                <button type="button" class="remove-passenger-btn" onclick="removePassenger(this)">-</button>
            `;
            passengerContainer.appendChild(newPassengerField);
        }

        function removePassenger(button) {
            const passengerField = button.parentElement;
            passengerField.remove();
        }

    function addDateTime() {
        const dateTimeContainer = document.getElementById('date-time-container');
        const defaultDateTimeField = document.querySelector('.datetime-group');
        const newDateTimeField = defaultDateTimeField.cloneNode(true);
        newDateTimeField.querySelectorAll('input').forEach(input => input.value = ''); // reset input values


        const addButton = newDateTimeField.querySelector('.add-datetime-btn');
        addButton.classList.replace('add-datetime-btn', 'remove-datetime-btn');
        addButton.textContent = '-';
        addButton.onclick = function () {
            removeDateTime(this);
        };

        dateTimeContainer.appendChild(newDateTimeField);
    }

    function removeDateTime(element) {
        const parent = element.closest('.datetime-group');
        if (parent) {
            parent.remove();
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
            document.getElementById('signature-error').style.display = 'none'; // Hide the error message
        };

        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }
     // event listener for submit
     document.querySelector('form').addEventListener('submit', function (event) {
        let datesValid = true;
        document.querySelectorAll('input[type="date"]').forEach(function (input) {
            if (!input.value) {
                datesValid = false;
            }
        });
        if (!datesValid) {
            event.preventDefault();
            alert('Please fill in all date fields.');
        }
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

        // Check dynamic passenger fields
        const passengerSelects = document.querySelectorAll('select[name="passengers[]"]');
        const passengerValues = Array.from(passengerSelects).map(select => select.value).filter(value => value !== '');

        if (passengerValues.length === 0) {
            isValid = false;
            errorMessages.push("At least one passenger must be selected.");
        }

        // Check if passengers are unique
        if (new Set(passengerValues).size !== passengerValues.length) {
            isValid = false;
            errorMessages.push("Duplicate passengers are not allowed.");
        }
        // Check date fields
        document.querySelectorAll('input[type="date"]').forEach(function (input) {
                if (!input.value) {
                    isValid = false;
                    let errorMessage = "All date fields must be filled.";
                    errorMessages.push(errorMessage);
                    console.error(errorMessage);  // Log the error to the console
                }
            });

        // Check file size
        let signatureFile = document.getElementById('RequesterSignature').files[0];
        if (signatureFile && signatureFile.size > 32000000) { // 32MB in bytes
            isValid = false;
            errorMessages.push("Signature file size must be less than 32MB.");
        }

         // Check purpose field
    const purposeCheckbox = document.getElementById('purposeCheckbox');
    if (purposeCheckbox.checked) {
        const purposeInput = document.getElementById('purposeInput');
        if (!purposeInput.value) {
            isValid = false;
            let errorMessage = "Purpose input is required.";
            errorMessages.push(errorMessage);
            console.error(errorMessage);  // Log the error to the console
        } else {
            console.debug("Purpose input value:", purposeInput.value);
        }
    } else {
        const purposeSelect = document.getElementById('purposeSelect');
        if (!purposeSelect.value) {
            isValid = false;
            let errorMessage = "Purpose select is required.";
            errorMessages.push(errorMessage);
            console.error(errorMessage);  // Log the error to the console
        } else {
            console.debug("Purpose select value:", purposeSelect.value);
        }
    }

    if (!isValid) {
        alert("Please correct the following errors:\n\n" + errorMessages.join("\n"));
        return false;
    }
    return true;
}

    document.querySelector('form').addEventListener('submit', function(event) {
        if (!validateForm()) {
            event.preventDefault();
        }
    });

document.addEventListener('DOMContentLoaded', function () {
        // Check if there is old input for the purpose and toggle the input field accordingly
        if ("{{ old('purposeInput') }}") {
            document.getElementById('purposeInput').style.display = 'block';
            document.getElementById('purposeSelect').style.display = 'none';
            document.getElementById('purposeCheckbox').checked = true;
        }

        // Function to toggle the input field for purpose
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
