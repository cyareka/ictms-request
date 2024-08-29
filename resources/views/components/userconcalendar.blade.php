<!DOCTYPE html>
<html>
  <head>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
    <style>
      /* Ensure the calendar is centered and the body takes full viewport height */
      #calendar {
        width: 80%;
        height: 50%;
        margin: 0;
        padding: 50px;
        margin-top: 90px;
        background-color: white;
        box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.15);
        z-index:2;
        margin-top: 10px;
      }
      .calendar-label {
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
            top: 50px;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            transition: opacity 0.3s ease;
        }
        .modal-content {
            background-color: #ffffff;
            margin: auto;
            padding: 30px;
            border: 1px solid #ccc;
            width: 40%;
            max-width: 800px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            position: relative;
            overflow-y: auto;
            max-height: 80vh;
        }
        .modal-header {
            font-size: 24px;
            margin-bottom: 20px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            position: absolute;
            top: 15px;
            right: 15px;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }
        .event-container {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .event-container h2 {
            margin-top: 0;
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
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                height: 'auto',
                aspectRatio: 1.35,
                events: function(fetchInfo, successCallback, failureCallback) {
                    $.ajax({
                        url: '/calendar/events',
                        type: 'GET',
                        data: $('#filterForm').serialize(), // Pass filter parameters to the backend
                        success: function(data) {
                            // Group events by date
                            const eventsByDate = {};
                            data.forEach(event => {
                                const dateStr = event.start.split('T')[0]; // Extract date part
                                if (!eventsByDate[dateStr]) {
                                    eventsByDate[dateStr] = [];
                                }
                                eventsByDate[dateStr].push(event);
                            });

                            // Create calendar events showing the total number of events per day
                            const events = Object.keys(eventsByDate).map(date => ({
                                title: `${eventsByDate[date].length} Event(s)`,
                                start: date,
                                extendedProps: { events: eventsByDate[date] } // Store all events for that date
                            }));

                            successCallback(events);
                        },
                        error: function() {
                            failureCallback();
                        }
                    });
                },
                eventClick: function(info) {
                    const modal = document.getElementById("eventModal");
                    const modalContent = document.getElementById("modalContent");

                    // Clear previous content
                    modalContent.innerHTML = `
                        <span class="close">&times;</span>
                        <div class="modal-header">${info.event.startStr} Events</div>
                    `;

                    // Display all events for the clicked date
                    if (info.event.extendedProps.events) {
                        info.event.extendedProps.events.forEach(event => {
                            // Determine border color based on event status
                            let borderColor = '#ddd'; // Default border color
                            if (event.EventStatus === 'Pending') {
                                borderColor = 'gray';
                            } else if (event.EventStatus === 'Approved') {
                                borderColor = 'blue';
                            }
                            else if (event.EventStatus === 'For Approval') {
                                borderColor = 'yellow';
                            }

                            modalContent.innerHTML += `
                                <div class="event-container" style="border-left: 5px solid ${borderColor};">
                                    <h2>${event.title}</h2>
                                    <p><strong>Conference Room:</strong> ${event.conferenceRoom || 'N/A'}</p>
                                    <p><strong>Start:</strong> ${new Date(event.start).toLocaleString()}</p>
                                    <p><strong>End:</strong> ${event.end ? new Date(event.end).toLocaleString() : 'N/A'}</p>
                                    <p><strong>Status:</strong> ${event.EventStatus || 'N/A'}</p>
                                    ${event.fileUrl ? `<a href="${event.fileUrl}" class="download-btn" download>Download File</a>` : ''}
                                </div>`;
                        });
                    } else {
                        modalContent.innerHTML += `<p>No events available for this date.</p>`;
                    }

                    modal.style.display = "block";
                }
            });

            calendar.render();

            // Close modal functionality
            document.addEventListener('click', function(event) {
                const modal = document.getElementById("eventModal");
                const closeBtn = document.querySelector(".close");

                if (event.target === closeBtn || event.target === modal) {
                    modal.style.display = "none";
                }
            });

            // Add event listener for filter form submission
            $('#filterForm').on('submit', function(event) {
                event.preventDefault(); // Prevent default form submission
                calendar.refetchEvents(); // Refresh events with new filters
            });

            // Function to reset filters
            window.resetFilters = function() {
                $('#filterForm')[0].reset(); // Reset form fields
                calendar.refetchEvents(); // Refresh events with reset filters
            };
        });
    </script>
</head>
<body>
   <div class= " calendar-label" >
      <h1>Conference Calendar View</h1>
      </div>
    <div class="cont">
        <div id='calendar'></div>
        <div class="filtdropdown">
            <button class="filtdropbtn"><i class="bi bi-filter"></i></button>
            <form id="filterForm" method="GET" action="{{ route('fetchCalendarEvents') }}">
                <div class="filtdropdown-content">
                    <p id="filterlabel">Filter By</p>
                    <hr>
                    <p>Conference Room</p>
                    <a>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="conference_room" value="Maagap" id="flexRadioDefault1">
                            <label class="form-check-label" for="flexRadioDefault1">Maagap</label>
                        </div>
                    </a>
                    <a>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="conference_room" value="Magiting" id="flexRadioDefault2">
                            <label class="form-check-label" for="flexRadioDefault2">Magiting</label>
                        </div>
                    </a>
                    <p>Status</p>
                    <a>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="form_statuses[]" value="Pending" id="flexCheckDefault1">
                            <label class="form-check-label" for="flexCheckDefault1">Pending</label>
                        </div>
                    </a>
                    <a>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="form_statuses[]" value="For Approval" id="flexCheckDefault3">
                            <label class="form-check-label" for="flexCheckDefault3">For Approval</label>
                        </div>
                    </a>
                    <a>
                        <div class="form-check" id="margincheck">
                            <input class="form-check-input" type="checkbox" name="form_statuses[]" value="Approved" id="flexCheckDefault2">
                            <label class="form-check-label" for="flexCheckDefault2">Approved</label>
                        </div>
                    </a>
                    <div class="buttons">
                        <button class="cancelbtn" type="button" onclick="resetFilters()">Remove</button>
                        <button class="applybtn" type="submit">Filter</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="end"></div>
    
    <!-- The Modal -->
    <div id="eventModal" class="modal">
        <div class="modal-content" id="modalContent">
            <!-- Content will be dynamically inserted here -->
        </div>
    </div>
</body>
</html>
