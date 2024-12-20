<!DOCTYPE html>
<html>
<head>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
    <style>
        /* Ensure the calendar is centered and the body takes full viewport height */
        #calendar2 {
            width: 80%;
            height: 100%;
            margin: 0;
            padding: 40px;
            margin-top: 20px;
            background-color: white;
            box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.15);
            z-index: 2;
        }
        /* Responsive design adjustments for smaller screens */
        @media (max-width: 768px) {
            #calendar2 {
                width: 90%;
                margin-left: 0;
            }
            .dropdown {
                margin-top: 20px;
            }
        }
        /* Flex container for dropdown and calendar */
        .cont {
            display: flex;
            align-items: flex-start;
            justify-content: center;
        }
        .dropdown {
            margin-top: 20px; /* Align with the calendar top */
        }
        .cont i {
            font-size: 20px;
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
        @media (max-width: 768px) {
            .modal-content {
                width: 80%; /* Adjust width for smaller screens */
                padding: 20px; /* Adjust padding */
            }
            .modal-header {
                font-size: 20px; /* Adjust header font size */
            }
        }
        @media (max-width: 480px) {
            .modal-content {
                width: 90%; /* Further adjustment for very small screens */
                padding: 15px; /* Adjust padding */
            }
            .modal-header {
                font-size: 18px; /* Further adjust header font size */
            }
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
            const calendarEl2 = document.getElementById('calendar2');
            const calendar2 = new FullCalendar.Calendar(calendarEl2, {
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
                        url: '/calendar2/events',
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
                                title: `${eventsByDate[date].length} Travel(s)`,
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
                        <div class="modal-header">${info.event.startStr} Travel(s)</div>
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
                            } else if (event.EventStatus === 'For Approval') {
                                borderColor = 'yellow';
                            }

                            modalContent.innerHTML += `
                                <div class="event-container" style="border-left: 5px solid ${borderColor};">
                                    <h2>${event.title}</h2>
                                    <p><strong>Destination:</strong> ${event.Destination || 'N/A'}</p>
                                    <p><strong>Start:</strong> ${new Date(event.start).toLocaleString()}</p>
                                    <p><strong>Status:</strong> ${event.EventStatus || 'N/A'}</p>
                                     ${(event.FormStatus === 'For Approval' || event.FormStatus === 'Approved' || event.FormStatus === 'Not Approved') ?
            `<a href="/vehiclerequest/${event.VRequestID}/view-pdf">
                <button class="download-btn" style="background-color: #354e7d; color: white; border: none; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; border-radius: 10px; font-size: 16px;" type="button">Download</button>
            </a>` : ''}
                                </div>`;
                        });
                    } else {
                        modalContent.innerHTML += `<p>No events available for this date.</p>`;
                    }

                    modal.style.display = "block";
                }
            });

            calendar2.render();

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
    <div class="cont">
        <div id='calendar2'></div>
        <div class="dropdown">
            <button class="dropbtn"><i class="bi bi-filter"></i></button>
            <form id="filterForm" method="GET" action="{{ route('fetchCalendarEvents') }}">
                <div class="dropdown-content">
                    <p id="filterlabel">Filter By</p>
                    <hr>
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
                    <hr>
                    <div class="buttons">
                        <button class="cancelbtn" type="button" onclick="resetFilters()">Remove</button>
                        <button class="applybtn" type="submit">Filter</button>
                    </div>
            </form>
        </div>
    </div>
    <div class="end"></div>

    <!-- Modal -->
    <div id="eventModal" class="modal">
        <div class="modal-content" id="modalContent"></div>
    </div>
</body>
</html>
