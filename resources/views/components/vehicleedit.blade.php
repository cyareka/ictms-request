<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vehicle Edit Form</title>
  <style>
    body {
      font-family:'Poppins';
      font-size: 18px;
    }
    .form-container {
      width: 60em;
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
    button {
      margin-bottom: 10px;
    }
    form {
      margin-bottom: 10px;

    }
    .dropdown-button {
      position: relative;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 16px;
      box-shadow: 0 1px 0 0 #ccc;
    }

    .dropdown-button::after {
      content: "\25BC"; /* Unicode character for a caret symbol */
      position: absolute;
      top: 40%;
      transform: translateY(-20%);
      font-size: 10px;
    }

    .dropdown-button, label {
      display: block; /* Make them stack vertically */
    }
    .dropdown-button span {
      margin-right: 10px; /* Add a margin between the label and the icon */
    }

    .form-row {
      display: flex;
      align-items: center; /* Align items vertically */
    }

    .form-row label {
      flex: 1; /* Allow the label to take up available space */
    }
    .form-row text{
      flex: 2;
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
        margin-bottom: 15px;
    }
    .inline-field input[type="date"],
    .inline-field input[type="time"] {
        width: 150px; /* adjust the width to your desired size */
    }
    .row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 15px;
        margin-bottom: 8px;
    }
    .row-2 {
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
    .input-field {
      display: flex;
      align-items: center;
      margin-bottom: 16px;
      width: 48%;
    }
    .input-field label {
      margin-right: 10px;
      width: 150px;
      position: relative;
    }
    .inline-field input,
    .inline-field select {
      height: 35px;
      padding: 8px;
      border-radius: 10px;
      width: calc(100% - 160px);
      box-sizing: border-box;
    }
    .button-container {
      display: flex;
      align-items: center;
    }
    .inline-group {
      display: flex;
      align-items: center;
      width: 100%;
      justify-content: space-between;
    }

    #passenger-container {
      width: 46%;
      max-height: 80px;
      overflow-y: auto;
      overflow-x: hidden;
      border-radius: 10px;
      margin-left: auto;
    }

    #passenger-container .passenger-field {
      width: 100%;
      margin-left: 8em;
    }
    .date-field {
        display: flex;
        flex-direction: column;
        width: 45%;
        align-items: center;
        margin-right: auto;
        margin-left: 1em;
      }

      .date-field input { 
        width: 68%;
        margin-left: -20px;
      }

      #date_start {
        margin-left: 30px;
      } 

      .below-label1 {
        display: block;
        margin-top: 5px;
        margin-left: 7em;
      }
      .below-label2 {
        display: block;
        margin-top: 5px;
        margin-left: 5em;
      }
      input[type="time"]
       {
            width: 30%;
            padding: 10px;
            margin-bottom:50px;
            margin-left: -40px;
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
    .submit-btn {
      background-color: #65558F;
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
    @media (max-width: 768px) {
      .form-container {
        width: 90%;
        padding: 20px;
        margin: 2em auto;
        margin-top: 1em;
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
  <h1>Request For Use of Vehicle</h1>
  <p>(Note: Request for use of vehicle shall be made at least (2) days from the intended date use.
    Failure to use the vehicle at the given date/time forfeits one’s right to use the vehicle assigned.)</p>
  <div class="form-body">
    <form action="/vehicle-request" method="POST" enctype="multipart/form-data">
      @csrf
      <div class="input-group">
        <div class="input-field">
          <label for="officeName">Name of Requesting Office</label>
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
          <label>Purpose of Trip</label>
          <input type="text" name="purpose" placeholder="Enter Purpose" required/>
        </div>
      </div>
      <div class="input-group">
        <div class="input-field">
          <label>Place of Travel</label>
          <input type="text" name="place_of_travel" placeholder="Enter Place" required/>
        </div>
        <div class="input-field passenger-field">
          <label>Name of Passenger</label>
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
            <label> Date</label>
            <div class="date-field">
              <input type="date" id="date_start" name="date_start[]" />
              <label for="date_start" class="below-label1">Start </label>
            </div>
            <div class="date-field">
              <input type="date" id="date_end" name="date_end[]" required/>
              <label for="date_end" class="below-label2">End</label>
            </div>
      </div>
            <div class="input-field">
              <label>Time Needed </label>
              <input type="time" name="time_start[]" required/>
            </div>
          </div>
        </div>
        <div class="input-group">
          <div class="input-field">
              <label>Requested By </label>
              <input type="text" name="contact_no" placeholder="Enter Name" required/>
            </div>
            <div class="input-field">
              <label>Email Requester</label>
              <input type="text" name="contact_no" placeholder="Enter Email" required/>
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
            <input type="file" id="e-signature" name="e-signature" style="display: none;" onchange="previewSignature(event)" required>
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
    <button class="dropdown-button" onclick="toggleDispatcher()">
      <span>TO BE FILLED BY DISPATCHER</span>
    </button>
    <div id="dispatcher-form" style="display: none;">
      <form class="row-dispatch">
        <div class="row">
            <div class="inline">
              <label for="name">Name</label>
              <input type="text" id="tables" name="person" placeholder="Enter Name"  required>
            </div>
            <div class="inline">
              <label for="contact">Contact No.</label>
              <input type="text" id="contact" name="contact" placeholder="Enter No."  required>
            </div>
            <div class="inline">
              <label for="email">Email </label>
              <input type="text" id="e-mail" name="e-mail" placeholder="Enter Email">
            </div>
        </div>

        <div class="row">
        <div class="inline">
            <label for="VName">Vehicle Type</label>
            <select id="VName" name="VName">
              <option disabled selected>Select Vehicle Type</option>
              <option>Mercedes</option>
              <option>Ford</option>
              <option>McLaren</option>
              <option>Ferrari</option>
              <option>LAMBO</option>
            </select>
          </div>
            <div class="inline">
              <label for="plate">Plate No.</label>
              <input type="text" id="plate" name="plate" placeholder="Enter No."  required>
            </div>
            <div class="inline">
            <label for="CkName">Check by</label>
            <select id="CkName" name="CkName">
              <option disabled selected>Select Office</option>
              <option>Office of the Regional Director</option>
              <option>Administrative Division</option>
            </select>
          </div>
        </div>

        <div class="row">
        <div class="inline">
                <label for="date">Date Start</label>
                <input type="date" id="date" name="date[]"  required>
              </div>
              <div class="inline">
                <label for="time">Time</label>
                <input type="time" id="time" name="time[]"  required>
              </div>
            <div class="inline">
              <label for="remark">Remarks</label>
              <input type="text" id="remark" name="remark" placeholder="Enter Remark">
            </div>
        </div>

      </form>
    </div>

    <!-- Administrative Service Section -->
    <button class="dropdown-button" onclick="toggleAdminService()">
      <span> TO BE FILLED BY ADMINISTRATIVE SERVICE - GENERAL SERVICES/DIVISION/SECTION </span>
    </button>
    <div id="admin-service-form" style="display: none;">
      <form class="row-dispatch">
          <div class="row">
              <div class="inline">
              <label for="availability">Availability</label>
                <select id="availability" name="availability">
                    <option disabled selected>Select Availability</option>
                    <option>Available</option>
                    <option>Not Available</option>
                </select>
              </div>
              <div class="inline">
              <label for="formStatus">Form Status</label>
                <select id="formStatus" name="formStatus">
                    <option disabled selected>Select Form Status</option>
                    <option>Pending</option>
                    <option>Approved</option>
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

          <div class="row-2">
          <div class="inline" style=" width: 25em;">
              <label for="VName">Approving Authority</label>
              <select id="VName" name="VName">
                <option disabled selected>Select Authority</option>
                <option>Rea May Manlunas</option>
                <option>Sheardeeh Fernandez</option>
                <option>Beverly Consolacion</option>
                <option>Inalyn Tamayo</option>
              </select>
            </div>
            <div class="inline" style= "width: 25em;">
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

          <div class="row-2">
          <div class="inline" style=" width: 25em;">
              <label for="VName">SO Approving Authority</label>
              <select id="VName" name="VName">
                <option disabled selected>Select Authority</option>
                <option>Rea May Manlunas</option>
                <option>Sheardeeh Fernandez</option>
                <option>Beverly Consolacion</option>
                <option>Inalyn Tamayo</option>
              </select>
            </div>
            <div class="inline" style= "width: 25em;">
              <label for="VName">SO Approving Authority Position</label>
              <select id="VName" name="VName">
                <option disabled selected>Select Authority</option>
                <option>Rea May Manlunas</option>
                <option>Sheardeeh Fernandez</option>
                <option>Beverly Consolacion</option>
                <option>Inalyn Tamayo</option>
              </select>
            </div>
          </div>

        <div class="row-2">
          <div class="inline" style=" width: 25em;">
              <label for="VName">Authorize  Signatory</label>
              <select id="VName" name="VName">
                <option disabled selected>Select Authority</option>
                <option>Rea May Manlunas</option>
                <option>Sheardeeh Fernandez</option>
                <option>Beverly Consolacion</option>
                <option>Inalyn Tamayo</option>
              </select>
          </div>
          <div class="inline" style= "width: 25em;">
            <label for="e-signature">File Upload</label>
              <div class="file-upload">
                <input type="file" id="e-signature" name="e-signature" style="display: none;" onchange="previewSignature(event)" required>
                  <div class="e-signature-text" onclick="document.getElementById('e-signature').click();">
                  Click to Upload Certificate of Non-Availability<br>Maximum file size: 31.46MB
                  </div>
                <img id="signature-preview" alt="Signature Preview">
              </div>
          </div>
        </div>
      </form>
  </div>
    <div class="form-footer">
          <button class="submit-btn" type="button" onclick="updateForm()">Update</button>
          <button class="cancel-btn" type="button" onclick="cancelForm()">Cancel</button>
      </div>
  </div>

  <script>
     function addPassenger() {
    const passengerField = document.querySelector('.passenger-field').cloneNode(true);
    passengerField.querySelector('label').remove();
    passengerField.querySelector('select').value = "";
    passengerField.querySelector('.add-passenger-btn').classList.replace('add-passenger-btn', 'remove-passenger-btn');
    passengerField.querySelector('.remove-passenger-btn').textContent = '-';
    passengerField.querySelector('.remove-passenger-btn').onclick = function() {
      passengerField.remove();
    };
    document.getElementById('passenger-container').appendChild(passengerField);
  }

  function addDateTime() {
    const dateTimeContainer = document.getElementById('date-time-container');
    const newDateTimeField = document.createElement('div');
    newDateTimeField.className = 'input-group datetime-group';
    newDateTimeField.innerHTML = `
      <div class="input-field">
        <label>Date Start</label>
        <input type="date" name="date_start[]" required/>
      </div>
      <div class="input-field">
        <label>Date End</label>
        <input type="date" name="date_end[]" required/>
      </div>
      <div class="input-field">
        <label>Time Start</label>
        <input type="time" name="time_start[]" required/>
      </div>
      <div class="button-container">
        <button class="remove-datetime-btn" type="button" onclick="removeDateTime(this)">-</button>
      </div>
    `;
    dateTimeContainer.appendChild(newDateTimeField);
  }

  function removeDateTime(element) {
    const parent = element.closest('.datetime-group');
    if (parent) {
      parent.remove();
    }
  }
  function updateForm() {
        alert('Your request has been successfully updated.');
    }

    function cancelForm() {
        let inputFields = document.querySelectorAll('input');
        inputFields.forEach((field) => {
            field.value = '';
        });
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

    function toggleDispatcher() {
      var dispatcherForm = document.getElementById("dispatcher-form");
      if (dispatcherForm.style.display === "block") {
        dispatcherForm.style.display = "none";
      } else {
        dispatcherForm.style.display = "block";
      }
    }

    function toggleAdminService() {
      var adminServiceForm = document.getElementById("admin-service-form");
      if (adminServiceForm.style.display === "block") {
        adminServiceForm.style.display = "none";
      } else {
        adminServiceForm.style.display = "block";
      }
    }

  </script>
</body>
</html>
