<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vehicle Request Form</title>
  <style>
   <style>
    body {
      font-family: sans-serif;
    }
    .form-container {
      width: 60em;
      padding: 30px;
      border: 1px solid #ddd;
      border-radius: 15px;
      margin: 5em auto;
      margin-bottom: 3em;
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
    .input-field {
      display: flex;
      align-items: center;
      margin-bottom: 16px;
      width: 48%;
    }
    .input-field label {
      margin-right: 10px;
      width: 150px;
    }
    .input-field input,
    .input-field select {
      height: 35px;
      padding: 8px;
      border: 1px solid #ccc;
      border-radius: 10px;
      width: calc(100% - 160px);
      box-sizing: border-box;
    }
    .submit-button {
      background-color: #4c1d95;
      color: #fff;
      padding: 10px 20px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      align-self: center;
      margin-top: 20px;
      margin-bottom: 10px;
    }
    .submit-button:hover {
      background-color: #3c1b75;
    }
    .file-upload {
      display: inline-block;
      flex-direction: column;
      align-items: center;
      padding: 16px;
      border: 2px dashed #5b21b6;
      border-radius: 6px;
      cursor: pointer;
      margin-bottom: 16px;
      text-align: center;
      width: calc(100% - 160px);
      box-sizing: border-box;
    }
    .button-container {
      display: flex;
      align-items: center;
    }
    .add-passenger-btn,
    .remove-passenger-btn {
      background-color: #747487;
      color: white;
      padding: 5px 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
      margin-left: 5px;
    }
    .add-passenger-btn:hover,
    .remove-passenger-btn:hover {
      background-color: #3e8e41;
    }
    .add-datetime-btn,
    .remove-datetime-btn {
      background-color: #747487;
      color: white;
      padding: 5px 10px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-size: 14px;
      margin-left: 5px;
      margin-bottom: 15px;
    }
    .add-datetime-btn:hover,
    .remove-datetime-btn:hover {
      background-color: #3e8e41;
    }
    .inline-group {
      display: flex;
      align-items: center;
      width: 100%;
      justify-content: space-between;
    }
    .passenger-group .input-field {
      display: flex;
      align-items: center;
      width: 50%;
    }
    .passenger-group .button-container {
      margin-left: 10px;
    }
    .passenger-group .date-field {
      width: 20%;
      display: flex;
      align-items: center;
    }
    .passenger-group .date-field label {
      width: auto;
    }
    .passenger-group .date-field input {
      width: calc(100% - 50px);
    }
    #passenger-container {
      max-height: 200px;
      overflow-y: auto;
      padding: 5px;
      border-radius: 10px;
      margin-bottom: 20px;
    }
    #date-time-container {
      max-height: 200px;
      overflow-y: auto;
      padding: 5px;
      border-radius: 10px;
      margin-bottom: 20px;
    }
    .datetime-group {
      display: flex;
      align-items: center;
      width: 100%;
    }
    .datetime-group .input-field {
      width: 30%;
    }
    .datetime-group .button-container {
      display: flex;
      align-items: center;
      margin-left: 5px;
    }
  </style>
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
          <label>Requesting Office/Unit</label>
          <input type="text" name="requesting_office" required/>
        </div>
        <div class="input-field">
          <label>Purpose of Trip</label>
          <input type="text" name="purpose" required/>
        </div>
      </div>
      <div id="passenger-container">
        <div class="input-group passenger-group">
          <div class="input-field">
            <label>Name of Passenger</label>
            <select name="passengers[]">
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
          <div class="button-container">
            <button class="add-passenger-btn" type="button" onclick="addPassenger()">+</button>
          </div>
        </div>
      </div>
      <div id="date-time-container">
        <div class="input-group datetime-group">
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
            <button class="add-datetime-btn" type="button" onclick="addDateTime()">+</button>
          </div>
        </div>
      </div>
      <div class="input-group">
        <div class="input-field">
          <label>Place of Travel</label>
          <input type="text" name="place_of_travel" required/>
        </div>
        <div class="input-field">
          <label>Requested by</label>
          <input type="text" name="requested_by" required/>
        </div>
      </div>
      <div class="input-group">
        <div class="input-field">
          <label>Email of Requester</label>
          <input type="email" name="email" required/>
        </div>
        <div class="input-field">
          <label>Contact No.</label>
          <input type="text" name="contact_no" required/>
        </div>
      </div>
      <div class="input-group">
        <div class="input-field">
          <label for="e-signature">E-Signature:</label>
          <div class="file-upload">
            <input type="file" id="e-signature" name="e_signature" style="display: none;" required/>
            <div class="e-signature-text" onclick="document.getElementById('e-signature').click();">
              Click to upload e-sign.<br />Maximum file size: 31.46MB
            </div>
          </div>
        </div>
      </div>
      <button class="submit-button" type="submit">Submit</button>
    </form>
  </div>
</div>

<script>
  function addPassenger() {
    const passengerContainer = document.getElementById('passenger-container');
    const newPassengerField = document.querySelector('.passenger-group').cloneNode(true);
    newPassengerField.querySelector('select').value = '';
    const removeButton = document.createElement('button');
    removeButton.className = 'remove-passenger-btn';
    removeButton.textContent = '-';
    removeButton.type = 'button';
    removeButton.onclick = function () {
      removePassenger(this);
    };
    newPassengerField.querySelector('.button-container').appendChild(removeButton);
    passengerContainer.appendChild(newPassengerField);
  }

  function removePassenger(element) {
    const parent = element.closest('.passenger-group');
    if (parent) {
      parent.remove();
    }
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

  function submitForm() {
    alert('Form submitted successfully!');
  }
</script>
</body>
</html>
