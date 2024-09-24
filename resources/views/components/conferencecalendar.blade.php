<!DOCTYPE html>
<html>
<head>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar/index.global.min.js'></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery -->
    <style>
        /* Ensure the calendar is centered and the body takes full viewport height */
        #calendar {
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
            #calendar {
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
   document.addEventListener('DOMContentLoaded', function () {
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
        events: function (fetchInfo, successCallback, failureCallback) {
            $.ajax({
                url: '/calendar/events',
                type: 'GET',
                data: $('#filterForm').serialize(),
                success: function (data) {
                    // Group events by date
                    const eventsByDate = {};
                    data.forEach(event => {
                        const dateStr = event.start.split('T')[0];
                        if (!eventsByDate[dateStr]) {
                            eventsByDate[dateStr] = [];
                        }
                        eventsByDate[dateStr].push(event);
                    });

                    // Create calendar events showing the total number of events per day
                    const events = Object.keys(eventsByDate).map(date => ({
                        title: `${eventsByDate[date].length} Event(s)`,
                        start: date,
                        extendedProps: { events: eventsByDate[date] }
                    }));

                    successCallback(events);
                },
                error: function () {
                    failureCallback();
                }
            });
        },
        eventClick: function (info) {
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
                    let borderColor = '#ddd';
                    if (event.EventStatus === 'Pending') {
                        borderColor = 'gray';
                    } else if (event.EventStatus === 'Approved') {
                        borderColor = 'blue';
                    } else if (event.EventStatus === 'For Approval') {
                        borderColor = 'yellow';
                    }

                    // Add event details to the modal
                    modalContent.innerHTML += `
                        <div class="event-container" style="border-left: 5px solid ${borderColor};">
                            <h2>${event.title}</h2>
                            <p><strong>Conference Room:</strong> ${event.conferenceRoom || 'N/A'}</p>
                            <p><strong>Start:</strong> ${new Date(event.start).toLocaleString()}</p>
                            <p><strong>End:</strong> ${event.end ? new Date(event.end).toLocaleString() : 'N/A'}</p>
                            ${event.EventStatus === 'For Approval' ? `
                                <button class="btn btn-primary download-btn"  style="background-color: #354e7d; color: white; border: none; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; border-radius: 10px; font-size: 16px;" data-request-form-url="/conferencerequest/${event.CRequestID}/view-pdf" data-unavailability-url="/conferencerequest/${event.CRequestID}/view-unavailable-pdf">Download</button>
                            ` : ''}
                            ${event.EventStatus === 'Approved' ? `
                                <a href="/conferencerequest/${event.CRequestID}/view-final-pdf" class="btn btn-primary" style="background-color: #354e7d; color: white; border: none; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; border-radius: 10px; font-size: 16px;" target="_blank">Download</a>
                            ` : ''}
                            ${event.EventStatus === 'Pending' && event.CAvailability === 0 ? `
                                <a href="/conferencerequest/${event.CRequestID}/view-unavailable-pdf" class="btn btn-primary" style="background-color: #354e7d; color: white; border: none; padding: 10px 20px; text-align: center; text-decoration: none; display: inline-block; border-radius: 10px; font-size: 16px;" target="_blank">Download</a>
                            ` : ''}
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
    document.addEventListener('click', function (event) {
        const modal = document.getElementById("eventModal");
        if (event.target.classList.contains('close') || event.target === modal) {
            modal.style.display = "none";
        }
    });

    // Add event listener for filter form submission
    $('#filterForm').on('submit', function (event) {
        event.preventDefault();
        calendar.refetchEvents();
    });

    // Function to reset filters
    window.resetFilters = function () {
        $('#filterForm')[0].reset();
        calendar.refetchEvents();
    };

    // Function to show download modal
    window.showDownloadModal = function (requestFormUrl, unavailabilityUrl) {
        const modalHtml = `
        <div class="modal" id="downloadModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document" style="max-width: 1800px; width: 100%; overflow-x: hidden;">
                <div class="modal-content"  style="overflow-x: hidden;">
                    <div class="modal-header">
                        <h5 class="modal-title">Download Options</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Which document would you like to download?</p>
                    </div>
                    <div class="modal-footer">
                        <a href="${requestFormUrl}" class="btn btn-primary" target="_blank">Request Form</a>
                        <a href="${unavailabilityUrl}" class="btn btn-secondary" target="_blank">Certificate of Unavailability</a>
                    </div>
                </div>
            </div>
        </div>
        `;
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        const downloadModal = document.getElementById('downloadModal');
        downloadModal.style.display = 'flex';

        downloadModal.querySelector('.close').addEventListener('click', function () {
            downloadModal.style.display = 'none';
            downloadModal.remove();
        });

        window.addEventListener('click', function (event) {
            if (event.target === downloadModal) {
                downloadModal.style.display = 'none';
                downloadModal.remove();
            }
        });
    };

    // Add event delegation for dynamically created buttons
    document.body.addEventListener('click', function (event) {
        if (event.target.classList.contains('download-btn')) {
            const requestFormUrl = event.target.getAttribute('data-request-form-url');
            const unavailabilityUrl = event.target.getAttribute('data-unavailability-url');
            showDownloadModal(requestFormUrl, unavailabilityUrl);
        }
    });
});
</script>
</head>
<body>
<div class="cont">
    <div id='calendar'></div>
    <div class="dropdown">
        <button class="dropbtn"><i class="bi bi-filter"></i></button>
        <form id="filterForm" method="GET" action="{{ route('fetchCalendarEvents') }}">
            <div class="dropdown-content">
                <p id="filterlabel">Filter By</p>
                <hr>
                <p>Conference Room</p>
                <a>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="conference_room" value="Maagap"
                               id="flexRadioDefault1">
                        <label class="form-check-label" for="flexRadioDefault1">Maagap</label>
                    </div>
                </a>
                <a>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="conference_room" value="Magiting"
                               id="flexRadioDefault2">
                        <label class="form-check-label" for="flexRadioDefault2">Magiting</label>
                    </div>
                </a>
                <p>Status</p>
                <a>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="form_statuses[]" value="Pending"
                               id="flexCheckDefault1">
                        <label class="form-check-label" for="flexCheckDefault1">Pending</label>
                    </div>
                </a>
                <a>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="form_statuses[]" value="For Approval"
                               id="flexCheckDefault3">
                        <label class="form-check-label" for="flexCheckDefault3">For Approval</label>
                    </div>
                </a>
                <a>
                    <div class="form-check" id="margincheck">
                        <input class="form-check-input" type="checkbox" name="form_statuses[]" value="Approved"
                               id="flexCheckDefault2">
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
