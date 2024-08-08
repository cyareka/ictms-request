<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vehicle Edit Form</title>
  <style>
    body {
      font-family: 'Poppins';
      font-size: 18px;
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
      margin: 0 10px; /* Added margin for better spacing */
    }
    .dropdown-button:first-child {
      margin-left: 0;
    }
    .dropdown-button:last-child {
      margin-right: 0;
    }
    .dropdown-button, label {
      display: block;
    }
    .form-row {
      display: flex;
      align-items: center;
    }
    .form-row label {
      flex: 1;
    }
    .inline-field input[type="date"],
    .inline-field input[type="time"] {
      width: 150px;
    }
    .row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 15px;
      margin-bottom: 8px;
    }
    .inline {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
    }
    .inline label {
      display: inline-block;
      width: 100px;
      margin-right: 10px;
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
      margin-bottom: 15px;
    }
    .submit-btn {
      background-color: #65558F;
      color: white;
      padding: 10px 20px;
      border: none;
      border-radius: 20px;
      cursor: pointer;
      font-size: 16px;
      margin-right: 10px;
      align-items: center;
    }
   
    #addVehi, #conference, #employee, #vehicle {
      display: none;
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
        padding: 20px;
        margin: 2em auto;
        margin-top: 1em;
      }
      .dropdown-row {
        flex-direction: column;
      }
      .dropdown-button {
        margin: 10px 0; /* Adjust margin for better spacing on small screens */
      }
      .input-group,
      .input-field {
        width: 100%;
        flex-direction: column;
        align-items: flex-start;
      }
      .input-field input,
      .input-field select {
        width: 100%;
      }
    }
  </style>
</head>
<body>
<div class="form-container">
  <h1>DASHBOARD</h1>
  <div id="app">
    <div class="dropdown-row">
      <button class="dropdown-button" onclick="toggleSection('addVehi')">
        <span>ADD DRIVER</span>
      </button>
      <button class="dropdown-button" onclick="toggleSection('conference')">
        <span>CONFERENCE ROOM</span>
      </button>
      <button class="dropdown-button" onclick="toggleSection('employee')">
        <span>EMPLOYEE</span>
      </button>
      <button class="dropdown-button" onclick="toggleSection('vehicle')">
        <span>VEHICLE</span>
      </button>
    </div>
    
    <div id="addVehi">
      <form class="row-dispatch">
        <div class="row">
          <div class="inline">
            <label for="driverName">Driver Name</label>
            <input type="text" id="driverName" name="driverName" placeholder="Enter Driver Name" required>
          </div>
          <div class="inline">
            <label for="driverEmail">Driver Email</label>
            <input type="text" id="driverEmail" name="driverEmail" placeholder="Enter Driver Email" required>
          </div>
          <div class="inline">
            <label for="driverContact">Contact No.</label>
            <input type="text" id="driverContact" name="driverContact" placeholder="Enter Contact No." required>
          </div>
        </div>
        <div class="form-footer">
          <button class="submit-btn" type="button" onclick="updateForm()">Submit</button>
        </div>
      </form>
    </div>
    
    <div id="conference">
      <form class="row-dispatch">
        <div class="row">
          <div class="inline">
            <label for="name">Name</label>
            <input type="text" id="tables" name="person" placeholder="Enter Name" required>
          </div>
          <div class="inline">
            <label for="contact">Location</label>
            <input type="text" id="contact" name="contact" placeholder="Enter Location" required>
          </div>
          <div class="inline">
            <label for="capacity">Capacity</label>
            <input type="text" id="capacity" name="capacity" placeholder="Enter Capacity">
          </div>
        </div>
        <div class="form-footer">
          <button class="submit-btn" type="button" onclick="updateForm()">Submit</button>
        </div>
      </form>
    </div>

    <div id="employee">
      <form class="row-dispatch">
        <div class="row">
          <div class="inline">
            <label for="employeeName">Name</label>
            <input type="text" id="employeeName" name="employeeName" placeholder="Enter Name" required>
          </div>
          <div class="inline">
            <label for="employeeEmail">Email</label>
            <input type="text" id="employeeEmail" name="employeeEmail" placeholder="Enter Email" required>
          </div>
          <div class="inline">
            <label for="e-signature">E-Signature</label>
            <div class="file-upload">
              <input type="file" id="e-signature" name="e-signature" style="display: none;" onchange="previewSignature(event)" required>
              <div class="e-signature-text" onclick="document.getElementById('e-signature').click();">
                Click to upload e-sign.<br>Maximum file size: 31.46MB
              </div>
              <img id="signature-preview" alt="Signature Preview">
            </div>
          </div>
        </div>
        <div class="form-footer">
          <button class="submit-btn" type="button" onclick="updateForm()">Submit</button>
        </div>
      </form>
    </div>

    <div id="vehicle">
      <form class="row-dispatch">
        <div class="inline">
          <label for="vehicleType">Vehicle Type</label>
          <input type="text" id="vehicleType" name="vehicleType" placeholder="Enter Vehicle Type" required>
        </div>
        <div class="inline">
          <label for="plateNo">Plate No.</label>
          <input type="text" id="plateNo" name="plateNo" placeholder="Enter Plate No." required>
        </div>
        <div class="inline">
          <label for="vehicleCapacity">Capacity</label>
          <input type="text" id="vehicleCapacity" name="vehicleCapacity" placeholder="Enter Capacity">
        </div>
        <div class="form-footer">
          <button class="submit-btn" type="button" onclick="updateForm()">Submit</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function updateForm() {
      alert('You successfully added.');
    }

    function toggleSection(sectionId) {
      const section = document.getElementById(sectionId);
      const isVisible = section.style.display === 'block';
      const sections = ['addVehi', 'conference', 'employee', 'vehicle'];
      
      sections.forEach(id => {
        document.getElementById(id).style.display = 'none';
      });
      
      if (!isVisible) {
        section.style.display = 'block';
      }
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
  </script>
</body>
</html>
