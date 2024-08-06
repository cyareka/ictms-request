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
            height: 200px;
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
<div class="container">
    <h1>Request For Use of Conference Room</h1>
    <p>(Note: Request should be made at least two (2) days before the date of actual use)</p>
    <form action="/conference-room/request" method="POST" enctype="multipart/form-data" onsubmit="return validateForm()">
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
            @foreach (old('date_start', [date('Y-m-d')]) as $index => $date_start)
                <div class="row-group">
                    <div class="row">
                        <div class="inline-field">
                            <label for="date_start">Date Start</label>
                            <input type="date" id="date_start" name="date_start[]" value="{{ $date_start }}" required>
                        </div>
                        <div class="inline-field" style="display: flex; align-items: center;">
                            <label for="date_end" style="margin-right: 10px;">Date End</label>
                            <input type="date" id="date_end" name="date_end[]" value="{{ old('date_end.' . $index, date('Y-m-d')) }}" required>
                            <div class="button-container">
                                <button class="add-btn" type="button" onclick="handleFormActions('addRow')">+</button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="inline-field">
                            <label for="time_start">Time Start</label>
                            <input type="time" id="time_start" name="time_start[]" value="{{ old('time_start.' . $index) }}" required>
                        </div>
                        <div class="inline-field">
                            <label for="time_end">Time End</label>
                            <input type="time" id="time_end" name="time_end[]" value="{{ old('time_end.' . $index) }}" required>
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
                    <input type="file" id="RequesterSignature" name="RequesterSignature" style="display: none;" onchange="handleFormActions('previewSignature', event)" required>
                    <div class="e-signature-text" onclick="document.getElementById('RequesterSignature').click();">
                        Click to upload e-sign.<br>Maximum file size: 32MB
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
    function handleFormActions(action, event) {
        switch(action) {
            case 'addRow':
                addRow();
                break;
            case 'removeRow':
                event.target.closest('.row-group').remove();
                break;
            case 'previewSignature':
                previewSignature(event);
                break;
        }
    }

    function addRow() {
        let rowGroupContainer = document.querySelector('.row-group-container');
        let newRowGroup = document.createElement('div');
        newRowGroup.className = 'row-group';
        let today = new Date().toISOString().slice(0, 10);
        newRowGroup.innerHTML = `
        <div class="row">
            <div class="inline-field">
                <label for="date_start">Date Start</label>
                <input type="date" id="date_start" name="date_start[]" value="${today}" required>
            </div>
            <div class="inline-field" style="display: flex; align-items: center;">
                <label for="date_end" style="margin-right: 10px;">Date End</label>
                <input type="date" id="date_end" name="date_end[]" value="${today}" required>
                <div class="remove-container">
                    <button class="remove-btn" type="button" onclick="handleFormActions('removeRow', event)">-</button>
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
    }

    function previewSignature(event) {
        const input = event.target;
        const preview = document.getElementById('signature-preview');
        const reader = new FileReader();
        const uploadText = document.querySelector('.e-signature-text');
        reader.onload = function() {
            preview.src = reader.result;
            preview.style.display = 'block';
            uploadText.style.display = 'none';
        };
        if (input.files && input.files[0]) {
            reader.readAsDataURL(input.files[0]);
        }
    }

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
        document.querySelectorAll('input[type="date"]').forEach(function(input) {
            if (!input.value) {
                isValid = false;
                errorMessages.push("All date fields must be filled.");
            }
        });

        // Check file size
        let signatureFile = document.getElementById('RequesterSignature').files[0];
        if (signatureFile && signatureFile.size > 32000000) { // 32MB in bytes
            isValid = false;
            errorMessages.push("Signature file size must be less than 32MB.");
        }

        if (!isValid) {
            alert("Please correct the following errors:\n\n" + errorMessages.join("\n"));
            return false;
        }

        return true;
    }

    // function logFormData(event) {
    //     event.preventDefault(); // Prevent the form from submitting immediately
    //
    //     const formData = new FormData(event.target);
    //     const formEntries = Object.fromEntries(formData.entries());
    //
    //     console.log("Form Data:", formEntries);
    //
    //     fetch(event.target.action, {
    //         method: 'POST',
    //         body: formData,
    //         headers: {
    //             'X-Requested-With': 'XMLHttpRequest',
    //             'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
    //         }
    //     })
    //         .then(response => response.json())
    //         .then(data => {
    //             if (data.errors) {
    //                 console.log("Errors:", data.errors);
    //                 let errorMessages = [];
    //                 for (let field in data.errors) {
    //                     errorMessages.push(data.errors[field].join("\n"));
    //                 }
    //                 alert("Form submission failed. Please correct the following errors:\n\n" + errorMessages.join("\n"));
    //             } else {
    //                 event.target.submit(); // Submit the form if no errors
    //             }
    //         })
    //         .catch(error => {
    //             console.error("Error:", error);
    //             alert("An error occurred while submitting the form. Please try again.\n\n" + error.message);
    //         });
    // }
    //
    // document.querySelector('form').addEventListener('submit', logFormData);
</script>
</body>
</html>
