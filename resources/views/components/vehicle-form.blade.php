  <!DOCTYPE html>
  <html lang="en">
  <head>
  <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Request Form</title>
    <style>
      body {
        font-family:'Poppins';
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
        width: 160px;
        position: relative;
      }
      .input-field label::after {
        content: "*";
        color: red;
        right: -15px;
        top: 0;
      }
      .input-field input,
      .input-field select {
        height: 35px;
        padding: 5px;
        border: 1px solid rgba(60, 54, 51, 0.5);
        border-radius: 10px;
        width: calc(100% - 160px);
        box-sizing: border-box;
      }
      .button-container {
        display: flex;
        align-items: center;
      }
      .add-passenger-btn {
        background-color: #0056b3;
        color: white;
        padding: 3px 8px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        margin-left: 5px;
      }
      .remove-passenger-btn{
        background-color: #ff4d4d;
        color: white;
        padding: 3px 8px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 14px;
        margin-left: 5px;
      }
      .add-passenger-btn:hover,
      .add-datetime-btn:hover {
          background-color: #003d80;
      }
      .remove-passenger-btn:hover,
      .remove-datetime-btn:hover {
        background-color: #cc0000;
      }
      .add-datetime-btn {
        background-color: #0056b3;
        color: white;
        padding: 3px 8px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        margin-left: 5px;
      }
      .remove-datetime-btn{
        background-color: #ff4d4d;
        color: white;
        padding: 3px 8px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 14px;
        margin-left: 5px;
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
        display: flex;
        align-items: center;
      }
      #date-time-container {
        max-height: 130px;
        overflow-y: auto;
        padding: 10px;
        border-radius: 10px;

      }
      .datetime-group {
        display: flex;
        align-items: center;
        width: 100%;
      }
    
      .datetime-group .button-container {
        display: flex;
        align-items: center;
        margin-left: 5px;
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
      .date-field {
        display: flex;
        flex-direction: column;
        width: 45%;
        align-items: center;
        margin-right: auto;
        margin-left: 1em;
      }

      .date-field input {
        width: 70%;
      }
      #date_start {
        margin-left: 82px;
      }
      #date_end {
        margin-right: 64px;
      }  

      .below-label1 {
        display: block;
        margin-top: 5px;
        margin-left: 11em;
      }
      .below-label2 {
        display: block;
        margin-top: 5px;
        margin-left: 4em;
      }
      .row {
              display: grid;
              grid-template-columns: repeat(2, 1fr);
              gap: 15px;
              margin-bottom: 8px;
          }
      @media (max-width: 768px) {
        .form-container {
          width: 90%;
          padding: 20px;
          margin: 2em auto;
          margin-top: 5em;
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
            <label for="officeName">Requesting Office</label>
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
            <label>Passenger Name/s</label>
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
            <div class="button-container">
              <button class="add-passenger-btn" type="button" onclick="addPassenger()">+</button>
            </div>
          </div>
        </div>
        <div id="passenger-container">
          <!-- New passenger fields will be appended here -->
        </div>
      <div id="date-time-container">
        <div class="input-group datetime-group">
          <div class="input-field">
            <label for="date_start">Date</label>
            <div class="date-field">
              <input type="date" id="date_start" name="date_start[]" required/>
              <label for="date_start" class="below-label1">Start</label>
            </div>
            <div class="date-field">
              <input type="date" id="date_end" name="date_end[]" required/>
              <label for="date_end" class="below-label2">End</label>
            </div>
      </div>
            <div class="input-field">
              <label>Time Start</label>
              <input type="time" name="time_start[]" required/>
              <div class="button-container">
              <button class="add-datetime-btn" type="button" onclick="addDateTime()">+</button>
            </div>
            </div>
          </div>
        </div>

        <div class="input-group">
          <div class="input-field">
              <label>Requested Name</label>
              <input type="text" name="contact_no" placeholder="Enter Name" required/>
            </div>
            <div class="input-field">
              <label>Requester Email</label>
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
        <div class="form-footer">
          <button class="submit-btn" type="submit">Submit</button>
        </div>
      </form>
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
