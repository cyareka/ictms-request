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
            width: 50em;
            padding: 20px;
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
            border: 1px solid #ddd;
            border-radius: 15px;
            box-sizing: border-box;
            margin-bottom: 15px;
        }
        .inline-field input[type="date"],
        .inline-field input[type="time"] {
            width: 150px; /* adjust the width to your desired size */
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
        /*.full-width {*/
        /*    grid-column: span 2;*/
        /*}*/
        .row-group-container {
            height: 200px; /* Adjust height as necessary */
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
            margin-bottom: 15px;
        }
        .inline-field label {
            display: inline-block;
            width: 100px;
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
        .inline-field label::after {
            content: "*";
            color: red;
            right: -15px;
            top: 0;
        }
    </style>
    <script>
        function addRow() {
            let rowGroupContainer = document.querySelector('.row-group-container');
            let newRowGroup = document.createElement('div');
            newRowGroup.className = 'row-group';
            let today = new Date().toISOString().slice(0, 10); // get current date in YYYY-MM-DD format
            newRowGroup.innerHTML = `
                <div class="row">
                    <div class="inline-field">
                        <label for="dateStart">Date Start</label>
                        <input type="date" id="dateStart" name="dateStart[]" value="${today}" required>
                    </div>
                    <div class="inline-field" style="display: flex; align-items: center;">
                        <label for="dateEnd" style="margin-right: 10px;">Date End</label>
                        <input type="date" id="dateEnd" name="dateEnd[]" value="${today}" required>
                        <div class="remove-container">
                            <button class="remove-btn" onclick="removeRow(this)">-</button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="inline-field">
                        <label for="timeStart">Time Start</label>
                        <input type="time" id="timeStart" name="timeStart[]" required>
                    </div>
                    <div class="inline-field">
                        <label for="timeEnd">Time End</label>
                        <input type="time" id="timeEnd" name="timeEnd[]" required>
                    </div>
                </div>
            `;
            rowGroupContainer.appendChild(newRowGroup);
        }
        function removeRow(button) {
            let container = button.closest('.row-group');
            container.remove();
        }
        function previewSignature(event) {
            const input = event.target;
            const preview = document.getElementById('signature-preview');
            const reader = new FileReader();
            const uploadText = document.querySelector('.e-signature-text');
            reader.onload = function() {
                preview.src = reader.result;
                preview.style.display = 'block';
                uploadText.style.display = 'none'; // Hide the upload text
            };
            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]);
            }
        }
        function showError(message) {
            alert(message);
        }
        function showSuccess(message) {
            alert(message);
        }
    </script>
</head>
<body>
@if (session('success'))
    <script>
        showSuccess("{{ session('success') }}");
    </script>
@endif
@if ($errors->any())
    <script>
        let errorMessages = '';
        @foreach ($errors->all() as $error)
            errorMessages += '{{ $error }}\n';
        @endforeach
        showError(errorMessages);
    </script>
@endif
@if(session('error'))
    <script>
        showError("{{ session('error') }}");
    </script>
@endif
<div class="container">
    <h1>Request For Use of Conference Room</h1>
    <p>(Note: Request should be made at least two (2) days before the date of actual use)</p>
    <form action="/conference-room/request" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="inline-field">
                <label for="officeName">Name of Requesting Office</label>
                <select id="officeName" name="officeName">
                    <option disabled selected>Select Office</option>
                    @foreach(App\Models\Office::all() as $office)
                        <option value="{{ $office->OfficeID }}">{{ $office->OfficeName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="inline-field">
                <label for="purpose">Purpose</label>
                <input type="text" id="purpose" name="purpose" placeholder="Enter Purpose"  required>
            </div>
        </div>
        <div class="row-group-container">
            @foreach (old('dateStart', [date('Y-m-d')]) as $index => $dateStart)
                <div class="row-group">
                    <div class="row">
                        <div class="inline-field">
                            <label for="dateStart">Date Start</label>
                            <input type="date" id="dateStart" name="dateStart[]" value="{{ $dateStart }}" required>
                        </div>
                        <div class="inline-field" style="display: flex; align-items: center;">
                            <label for="dateEnd" style="margin-right: 10px;">Date End</label>
                            <input type="date" id="dateEnd" name="dateEnd[]" value="{{ old('dateEnd.' . $index, date('Y-m-d')) }}" required>
                            <div class="button-container">
                                <button class="add-btn" type="button" onclick="addRow()">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="inline-field">
                            <label for="timeStart">Time Start</label>
                            <input type="time" id="timeStart" name="timeStart[]" value="{{ old('timeStart.' . $index) }}" required>
                        </div>
                        <div class="inline-field">
                            <label for="timeEnd">Time End</label>
                            <input type="time" id="timeEnd" name="timeEnd[]" value="{{ old('timeEnd.' . $index) }}" required>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="row">
            <div class="inline-field">
                <label for="persons">No. of Persons</label>
                <input class="small-input" type="text" id="npersons" name="npersons" placeholder="Enter"  required>
            </div>
            <div class="inline-field">
                <label for="focalPerson">Focal Person</label>
                <input type="text" id="focalPerson" name="focalPerson" placeholder="Enter Focal Person"  required>
            </div>
        </div>
        <div class="row-multiple">
            <div class="inline-field" style="width: 8em;">
                <label for="tables">Tables</label>
                <input type="text" id="tables" name="tables" placeholder="Enter"  required>
            </div>
            <div class="inline-field" style="width: 8em;">
                <label for="chairs">Chairs</label>
                <input type="text" id="chairs" name="chairs" placeholder="Enter"  required>
            </div>
            <div class="inline-field">
                <label for="otherFacilities">Other Facilities</label>
                <input type="text" id="otherFacilities" name="otherFacilities" placeholder="Specify Others">
            </div>
        </div>
        <div class="row">
            <div class="inline-field">
                <label for="conferenceRoom">Select Conference Room</label>
                <select id="conferenceRoom" name="conferenceRoom">
                    <option disabled selected>Select Room</option>
                    @foreach(App\Models\ConferenceRoom::all() as $room)
                        <option value="{{ $room->CRoomID }}">{{ $room->CRoomName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="inline-field">
                <label for="requesterName">Name of Requester</label>
                <input type="text" id="requesterName" name="requesterName" placeholder="Enter Name of Requester"  required>
            </div>
        </div>
        <div class="row">
            <div class="inline-field">
                <label for="RequesterSignature">E-Signature</label>
                <div class="file-upload">
                    <input type="file" id="RequesterSignature" name="RequesterSignature" style="display: none;" onchange="previewSignature(event)"  required>
                    <div class="e-signature-text" onclick="document.getElementById('RequesterSignature').click();">
                        Click to upload e-sign.<br>Maximum file size: 31.46MB
                    </div>
                    <img id="signature-preview" alt="Signature Preview">
                </div>
            </div>
        </div>
        <div class="form-footer">
            <button class="submit-btn" type="submit">Submit</button>
        </div>
    </form>
</div>
<script>
    function addRow() {
        let rowGroupContainer = document.querySelector('.row-group-container');
        let newRowGroup = document.createElement('div');
        newRowGroup.className = 'row-group';
        let today = new Date().toISOString().slice(0, 10); // get current date in YYYY-MM-DD format
        newRowGroup.innerHTML = `
            <div class="row">
                <div class="inline-field">
                    <label for="dateStart">Date Start</label>
                    <input type="date" id="dateStart" name="dateStart[]" value="${today}" required>
                </div>
                <div class="inline-field" style="display: flex; align-items: center;">
                    <label for="dateEnd" style="margin-right: 10px;">Date End</label>
                    <input type="date" id="dateEnd" name="dateEnd[]" value="${today}" required>
                    <div class="remove-container">
                        <button class="remove-btn" onclick="removeRow(this)">-</button>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="inline-field">
                    <label for="timeStart">Time Start</label>
                    <input type="time" id="timeStart" name="timeStart[]" required>
                </div>
                <div class="inline-field">
                    <label for="timeEnd">Time End</label>
                    <input type="time" id="timeEnd" name="timeEnd[]" required>
                </div>
            </div>
        `;
        rowGroupContainer.appendChild(newRowGroup);
    }
    function removeRow(button) {
        let container = button.closest('.row-group');
        container.remove();
    }
    function previewSignature(event) {
        const input = event.target;
        const preview = document.getElementById('signature-preview');
        const reader = new FileReader();
        const uploadText = document.querySelector('.e-signature-text');
        reader.onload = function() {
            preview.src = reader.result;
            preview.style.display = 'block';
            uploadText.style.display = 'none'; // Hide the upload text
        };
        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }
    // Place the validation script here
    document.querySelector('form').addEventListener('submit', function(event) {
        let datesValid = true;
        document.querySelectorAll('input[type="date"]').forEach(function(input) {
            if (!input.value) {
                datesValid = false;
            }
        });
        if (!datesValid) {
            event.preventDefault();
            alert('Please fill in all date fields.');
        }
    });
</script>
</body>
</html>
