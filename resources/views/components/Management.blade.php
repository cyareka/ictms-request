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
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .form-container {
            width: 55em;
            padding: 35px;
            border: 1px solid #ddd;
            border-radius: 15px;
            margin: 5em auto;
            margin-bottom: 3em;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
            margin-top: 0;
        }
        h1 {
            font-size: 30px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .dropdown-row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }
        .dropdown-button {
            position: relative;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 0 1px 0 0 #ccc;
            flex: 1;
            margin: 10px 5px;
            text-align: center;
        }
        .dropdown-button.active {
            border: 2px solid #354e7d; /* Change border color when active */
        }
        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }
        .inline-field {
            display: flex;
            align-items: center;
            width: 48%;
            margin-bottom: 15px;
        }
        .inline-field label {
            margin-right: 20px;
            flex-shrink: 0;
            width: 150px;
        }
        .inline-field input,
        .inline-field select {
            width: calc(100% - 170px);
            padding: 10px;
            border: 1px solid rgba(60, 54, 51, 0.5);
            border-radius: 15px;
            box-sizing: border-box;
        }
        input[type="number"] {
            width: 80px;
        }
        .submit-btn {
            background-color: #354e7d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            display: inline-block;
            align-items: center;
        }
        .form-footer {
            text-align: center;
            margin-top: 10px;
        }
        .toggle-section {
            display: none;
            width: 100%;
        }
        #signature-preview {
            margin-top: 15px;
            max-width: 100px;
            max-height: 100px;
            display: none;
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
        }
        @media (max-width: 768px) {
            .form-container {
                width: 90%;
                padding: 50px;
                margin: 2em auto;
                margin-top: 1em;
            }
            .dropdown-button,
            .toggle-button {
                margin: 10px 0;
                display: flex;
                border
                flex-direction: column;
                width: 100%;
            }
            .inline-field {
                width: 100%;
                display: flex;
                flex-direction: column;
            }
            .inline-field label {
                width: 100%;
                margin-bottom: 5px;
            }
            .inline-field input,
            .inline-field select {
                width: 100%;
                box-sizing: border-box;
                padding: 15px;
            }
            .form-row {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
<div class="form-container">
    <h1>MANAGEMENT</h1>
    <div id="app">
        <div class="dropdown-row">
            <button class="dropdown-button" onclick="toggleSection('addVehi', this)">ADD DRIVER</button>
            <button class="dropdown-button" onclick="toggleSection('vehicle', this)">ADD VEHICLE</button>
            <button class="dropdown-button" onclick="toggleSection('conference', this)">CONFERENCE ROOM</button>
            <button class="dropdown-button" onclick="toggleSection('employee', this)">EMPLOYEE</button>
        </div>

        <div id="addVehi" class="toggle-section">
    <form class="row-dispatch" method="POST" action="{{ route('driver.store') }}">
        @csrf
        <div class="form-row">
            <div class="inline-field">
                <label for="DriverName">Driver Name</label>
                <input type="text" id="DriverName" name="DriverName" placeholder="Enter Driver Name" required>
            </div>
            <div class="inline-field">
                <label for="DriverEmail">Driver Email</label>
                <input type="email" id="DriverEmail" name="DriverEmail" placeholder="Enter Driver Email" required>
            </div>
        </div>
        <div class="form-row">
            <div class="inline-field">
                <label for="ContactNo">Contact No.</label>
                <input type="tel" id="ContactNo" name="ContactNo" placeholder="Enter Contact No." required>
            </div>
        </div>
        <div class="form-footer">
            <button class="submit-btn" type="submit">Submit</button>
        </div>
    </form>
</div>

        <div id="vehicle" class="toggle-section">
            <form class="row-dispatch" method="POST" action="{{ route('vehicle.store') }}">
                @csrf
                <div class="form-row">
                    <div class="inline-field">
                        <label for="VehicleType">Vehicle Type</label>
                        <input type="text" id="VehicleType" name="VehicleType" placeholder="Enter Vehicle Type" required maxlength="50">
                    </div>
                    <div class="inline-field">
                        <label for="PlateNo">Plate No.</label>
                        <input type="text" id="PlateNo" name="PlateNo" required maxlength="15">
                    </div>
                </div>
                <div class="form-row">
                    <div class="inline-field">
                        <label for="Capacity">Capacity</label>
                        <input type="number" id="Capacity" name="Capacity" min="1" value="1" required>
                    </div>
                </div>
                <div class="form-footer">
                    <button class="submit-btn" type="submit">Submit</button>
                </div>
            </form>
        </div>

        <div id="conference" class="toggle-section">
            <form class="row-dispatch" method="POST" action="{{ route('conferences.store') }}">
                @csrf
                <div class="form-row">
                    <div class="inline-field">
                        <label for="CRoomName">Name</label>
                        <input type="text" id="CRoomName" name="CRoomName" placeholder="Enter Name" required>
                    </div>
                    <div class="inline-field">
                        <label for="Location">Location</label>
                        <input type="text" id="Location" name="Location" placeholder="Enter Location" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="inline-field">
                        <label for="Capacity">Capacity</label>
                        <input type="number" id="Capacity" name="Capacity" min="1" value="0" required>
                    </div>
                </div>
                <div class="form-footer">
                    <button class="submit-btn" type="submit">Submit</button>
                </div>
            </form>
        </div>

        <div id="employee" class="toggle-section">
            <form class="row-dispatch" method="POST" action="{{ route('employee.store') }}">
                @csrf
                <div class="form-row">
                    <div class="inline-field">
                        <label for="EmployeeName">Name</label>
                        <input type="text" id="EmployeeName" name="EmployeeName" placeholder="Enter Name" required>
                    </div>
                    <div class="inline-field">
                        <label for="EmployeeEmail">Email</label>
                        <input type="text" id="EmployeeEmail" name="EmployeeEmail" placeholder="Enter Email" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="inline-field">
                        <label for="officeName">Assingned Office</label>
                        <select id="officeName" name="officeName" required>
                            <option disabled selected>Select Office</option>
                            @foreach(App\Models\Office::all() as $office)
                                <option value="{{ $office->OfficeID }}">{{ $office->OfficeName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-footer">
                    <button class="submit-btn" type="submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateForm() {
        alert('You successfully added.');
    }

    function toggleSection(sectionId, button) {
        const section = document.getElementById(sectionId);
        const isVisible = section.style.display === 'block';
        const allSections = document.querySelectorAll('.toggle-section');
        const allButtons = document.querySelectorAll('.dropdown-button');

        // Hide all sections and remove active class from all buttons
        allSections.forEach(sec => sec.style.display = 'none');
        allButtons.forEach(btn => btn.classList.remove('active'));

        // Show the clicked section and add active class to the clicked button
        if (!isVisible) {
            section.style.display = 'block';
            button.classList.add('active');
        }
    }

</script>
</body>
</html>
