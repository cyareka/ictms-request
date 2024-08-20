<!DOCTYPE html>
<html>
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
    <style>
        /* Ensure the calendar is centered and the body takes full viewport height */
        #calendar2 {
            width: 80%;
            height: 100%;
            margin: 0;
            padding: 40px;
            margin-top: 90px;
            background-color: white;
            box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.15);
            margin-top: 20px;
            z-index:2;
            margin-top: 10px;
        }
        .vehilabel {
            text-align: center;
            font-size: 30px;
            margin-top: 3em;
            margin-bottom: 0;
            color:#000000;
        }

          /* Responsive design adjustments for smaller screens */
      @media (max-width: 768px) {
        #calendar {
          width: 90%;
          height: 80vh; /* Adjust height on smaller screens */
          margin-left: 0;
        }
        .filtdropdown {
          margin-top: 20px;
        }
      }

      /* Flex container for dropdown and calendar */
      .cont {
        display: flex;
        align-items: flex-start;
        justify-content: center;
      }

      .cont i {
        font-size: 20px;
      }
     
      .filtdropbtn {
         color:black;
         font-size: 16px;
         border: none;
         cursor: pointer;
         position:relative; /* Ensure the button stays in a fixed position */
         }
      .filtdropdown {
         position: relative;
         display: inline-block;
         margin-top: 10px;
         z-index: 100; 
         }
         .filtdropdown-content {
         display: none;
         position: absolute;
         right: 0;
         background-color: #f9f9f9;
         min-width: 260px;
         box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
        
         }
         .filtdropdown-content p{
         padding-top:10px;
         padding-left:10px;
         color:black;
         font-weight:bold;
         font-size:18px;
         }
         #iconborder {
         margin-right: 10px;
         border-right: 1px solid #d1d5db;
         padding-right: 20px;
         }

         #margincheck{
         margin-bottom:10px;
         }
         #filterlabel{
         margin-bottom:10px;
         }
         .filtdropdown-content a {
         color: black;
         padding: 10px 16px;
         text-decoration: none;
         display: block;
         }
         .filtdropdown-content a:hover {
         background-color: #f1f1f1;
         }
         .filtdropdown:hover .filtdropdown-content {
         display: block;
         }
         .buttons{
         display:flex;
         justify-content:right;
         margin-top:20px;
         margin-bottom:10px;
         }
         .applybtn , .cancelbtn {
         background-color: #354e7d;
         color:white;
         border-radius:20px;
         padding:5px;
         margin: 0 10px 10px 0;
         width: 90px;
         }
         .approved {
         padding: 4px 13px;
         background-color: #CBDCF9;
         color: #103680;
         font-size: 12px;
         font-weight: bold;
         border-radius: 5px;
         }
         .disapproved {
         padding: 4px 13px;
         background-color: #ff6961;
         color: white;
         font-size: 12px;
         font-weight: bold;
         border-radius: 5px;
         }
         .pending {
         padding: 4px 13px;
         background-color: #FFF3DD;
         color: #aa8345;
         font-size: 12px;
         font-weight: bold;
         border-radius: 5px;
         }
         #actions {
         margin: 2px 8px;
         cursor: pointer;
         color: black;
         }

        /* Modal styling */
        .modal {
         display: none;
         position: fixed;
         z-index: 3;
         padding-top: 100px;
         left: 0;
         top: 0;
         width: 100%;
         height: 100%;
         overflow: auto;
         background-color: rgba(0, 0, 0, 0.4);
         transition: opacity 0.3s ease;
         margin-top: 1em;
         }
         .modal-content {
         background-color: #ffffff;
         margin: auto;
         padding: 20px;
         border: 1px solid #ccc;
         width: 60%;
         max-width: 600px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
         border-radius: 8px;
         position: relative;
         }
         .close {
         color: #aaa;
         float: right;
         font-size: 28px;
         font-weight: bold;
         }
         .close:hover,
         .close:focus {
         color: black;
         text-decoration: none;
         cursor: pointer;
         }
         .download-btn {
         background-color: #4CAF50;
         border: none;
         color: white;
         padding: 10px 20px;
         text-align: center;
         text-decoration: none;
         display: inline-block;
         font-size: 16px;
         margin: 4px 2px;
         cursor: pointer;
         border-radius: 5px;
         }
         .end {
         position: fixed;
         bottom: 0;
         left: 0;
         width: 100%;
         height: 100px; /* Adjust the height as needed */
         background: #354e7d;
         z-index: 1; /* Ensure the footer is below the table */
         }
         
    </style>
      <script>
         document.addEventListener('DOMContentLoaded', function() {
         const calendarEl2 = document.getElementById('calendar2');
         const calendar2 = new FullCalendar.Calendar(calendarEl2, {
          initialView: 'timeGridWeek',
          headerToolbar: {
              left: 'prev,next today',
              center: 'title',
              right: 'dayGridMonth,timeGridWeek,timeGridDay'
          },
          height: 'auto',
          aspectRatio: 1.35,
          events: function(fetchInfo, successCallback, failureCallback) {
              $.ajax({
                  url: '/calendar2/events', // Your route for fetching events
                  type: 'GET',
                  data: $('#filterForm').serialize(), // Include filter parameters
                  success: function(data) {
                      successCallback(data); // Pass events data to the calendar
                  },
                  error: function() {
                      failureCallback();
                  }
              });
          },
          eventDidMount: function(info) {
                      const status = info.event.extendedProps.EventStatus;
                      if (status === 'Pending') {
                          info.el.style.backgroundColor = '#DAA520'; // Pending events are yellow
                          info.el.style.color = '#fff';
                      } else if (status === 'Approved') {
                          info.el.style.backgroundColor = '#354e7d'; // Approved events are blue
                          info.el.style.color = '#fff';
                      }
                  },
          eventClick: function(info) {
              const modal = document.getElementById("eventModal");
              const modalContent = document.getElementById("modalContent");
         
              modalContent.innerHTML = `
                  <h2>${info.event.title}</h2>
                  <p><strong>Destination:</strong> ${info.event.extendedProps.Destination}</p>
                  <p><strong>Start:</strong> ${info.event.start.toLocaleString()}</p>
                  <p><strong>Status:</strong> ${info.event.extendedProps.EventStatus}</p>
                  ${info.event.extendedProps.fileUrl ? `<a href="${info.event.extendedProps.fileUrl}" class="download-btn" download>Download File</a>` : ''}
              `;
         
              modal.style.display = "block";
          }
         });
         calendar2.render();
         
         const closeBtn = document.getElementsByClassName("close")[0];
         closeBtn.onclick = function() {
          document.getElementById("eventModal").style.display = "none";
         };
         window.onclick = function(event) {
          const modal = document.getElementById("eventModal");
          if (event.target == modal) {
              modal.style.display = "none";
          }
         };
         
         // Add event listener for filter form submission
         $('#filterForm').on('submit', function(event) {
          event.preventDefault(); // Prevent default form submission
          calendar2.refetchEvents(); // Refresh events with new filters
         });
         
         // Function to reset filters
         window.resetFilters = function() {
          $('#filterForm')[0].reset(); // Reset form fields
          calendar2.refetchEvents(); // Refresh events with reset filters
         };
         });
         
      </script>
   </head>
   <body>
   <div class="vehilabel">
      Vehicle Calendar View
    </div>
      <div class="cont">
         <div id='calendar2'></div>
         <div class="filtdropdown">
            <button class="filtdropbtn"><i class="bi bi-filter"></i></button>
            <form id="filterForm" method="GET" action="{{ route('fetchCalendarEvents') }}">
               <div class="filtdropdown-content">
                  <p id="filterlabel">Filter By</p>
                  <hr>
                  <p>Status</p>
                  <a>
                     <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="form_statuses[]" value="Pending" id="flexCheckDefault1">
                        <label class="form-check-label" for="flexCheckDefault1">
                        Pending
                        </label>
                     </div>
                  </a>
                  <a>
                     <div class="form-check" id="margincheck">
                        <input class="form-check-input" type="checkbox" name="form_statuses[]" value="Approved" id="flexCheckDefault2">
                        <label class="form-check-label" for="flexCheckDefault2">
                        Approved and Ongoing
                        </label>
                     </div>
                  </a>
                  <hr>
                  <div class="buttons">
                     <button class="cancelbtn" type="button" onclick="resetFilters()">Remove</button>
                     <button class="applybtn" type="submit">Filter</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
      <!-- The Modal for event details -->
      <div id="eventModal" class="modal">
         <div class="modal-content" id="modalContent">
            <span class="close">&times;</span>
            <!-- Event details will be injected here -->
         </div>
      </div>
      <div class="end"></div>
   </body>
</html>
