<div class="requests">
    <div class="filter">
        <div class="row height d-flex justify-content-left align-items-left">
            <div class="col-md-6">
                <div class="form">
                    <i class="fa fa-search"></i>
                    <input type="text" class="form-control form-input" placeholder="Search">
                </div>
            </div>
        </div>
        <div class="tableactions">
            <div id="divide">
                <i class="bi bi-arrow-left-short"></i>
                <i class="bi bi-arrow-right-short" id="iconborder"></i>
                <div class="dropdown" style="float:right;">
                    <button class="dropbtn"><i class="bi bi-filter"></i></button>
                    <form id="filterForm" method="GET" action="{{ route('fetchSortedRequests') }}">
                        <div class="dropdown-content">
                            <p id="filterlabel">Filter By</p>
                            <hr>
                            <p>Conference Room</p>
                            <a>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="conference_room" value="Maagap" id="flexRadioDefault1">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Maagap
                                    </label>
                                </div>
                            </a>
                            <a>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="conference_room" value="Magiting" id="flexRadioDefault2">
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Magiting
                                    </label>
                                </div>
                            </a>
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
        </div>
    </div>
    <br>
    <div class="tabview">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">
                    <a href="#" id="sort-date-requested" data-order="desc">
                        Date Requested
                    </a>
                </th>
                <th scope="col">Conference Room</th>
                <th scope="col">Requesting Office</th>
                <th scope="col">Date Needed</th>
                <th scope="col">Time Needed</th>
                <th scope="col">Availability</th>
                <th scope="col">Form Status</th>
                <th scope="col">Event Status</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @php
                $filteredRequests = App\Models\ConferenceRequest::whereIn('FormStatus', ['Approved', 'Pending'])
                    ->whereIn('EventStatus', ['Ongoing', '-'])
                    ->get();
            @endphp

            @foreach($filteredRequests as $request)
    @php
        // Default availability based on FormStatus
        if ($request->FormStatus == 'Approved') {
            $availability = 'Available';
        } elseif ($request->FormStatus == 'Pending') {
            $availability = app('App\Http\Controllers\ConferenceController')->checkAvailability(
                $request->CRoomID,
                $request->date_start,
                $request->time_start,
                $request->date_end,
                $request->time_end,
                $request->CRequestID
            );
        } else {
            // Default case for other statuses
            $availability = 'N/A';
        }
    @endphp
    <tr>
        <th scope="row">{{ $request->CRequestID }}</th>
        <td>{{ $request->created_at->format('m-d-Y') }}</td>
        <td>{{ $request->conferenceRoom->CRoomName }}</td>
        <td>{{ $request->office->OfficeName }}</td>
        <td>{{ $request->date_start }}</td>
        <td>{{ $request->time_start }}</td>
        <td>{{ $availability }}</td>
        <td><span class="{{ strtolower($request->FormStatus) }}">{{ $request->FormStatus }}</span></td>
        <td>{{ $request->EventStatus }}</td>
        <td>
            <a href="{{ route('ConferencedetailEdit', $request->CRequestID) }}"><i class="bi bi-pencil" id="actions"></i></a>
            <i class="bi bi-download" id="actions"></i>
        </td>
    </tr>
@endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="end"></div>

<script>
    document.getElementById('sort-date-requested').addEventListener('click', function (e) {
        e.preventDefault();
        let order = this.getAttribute('data-order');
        let newOrder = order === 'asc' ? 'desc' : 'asc';
        this.setAttribute('data-order', newOrder);
        fetchSortedData(newOrder);
    });

    function fetchSortedData(order) {
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();
        fetch(`/fetchSortedRequests?sort=created_at&order=${order}&${params}`)
            .then(response => response.json())
            .then(data => {
                updateTable(data);
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
                alert(`An error occurred while fetching data: ${error.message}`);
            });
    }

    function updateTable(data) {
        let tbody = document.querySelector('tbody');
        tbody.innerHTML = '';
        data.forEach(request => {
            let conferenceRoomName = request.conference_room ? request.conference_room.CRoomName : 'N/A';
            let officeName = request.office ? request.office.OfficeName : 'N/A';
            let availability = request.conference_room ? request.conference_room.Availability : 'N/A';
            let row = `<tr>
                <th scope="row">${request.CRequestID}</th>
                <td>${new Date(request.created_at).toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\//g, '-')}</td>
                <td>${conferenceRoomName}</td>
                <td>${officeName}</td>
                <td>${request.date_start}</td>
                <td>${request.time_start}</td>
                <td>${availability}</td>
                <td><span class="${request.FormStatus.toLowerCase()}">${request.FormStatus}</span></td>
                <td>${request.EventStatus}</td>
                <td>
                    <a href="/conferencerequest/${request.CRequestID}/edit"><i class="bi bi-pencil" id="actions"></i></a>
                    <i class="bi bi-download" id="actions"></i>
                </td>
            </tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }

    document.getElementById('filterForm').addEventListener('submit', function(event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();
        const sortOrder = document.getElementById('sort-date-requested').getAttribute('data-order');

        fetch(`/fetchSortedRequests?sort=created_at&order=${sortOrder}&${params}`)
            .then(response => response.json())
            .then(data => {
                updateTable(data);
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
                alert(`An error occurred while fetching data: ${error.message}`);
            });
    });

    function resetFilters() {
        document.getElementById('filterForm').reset();
    }

    function updateAvailability(conferenceRoomId, dateStart, timeStart, dateEnd, timeEnd, createdAt) {
        fetch('/updateAvailability', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                conference_room_id: conferenceRoomId,
                date_start: dateStart,
                time_start: timeStart,
                date_end: dateEnd,
                time_end: timeEnd,
                created_at: createdAt
            })
        })
            .then(response => response.json())
            .then(data => {
                document.querySelectorAll('tbody tr').forEach(row => {
                    let rowConferenceRoomId = row.querySelector('td:nth-child(3)').textContent;
                    let rowDateStart = row.querySelector('td:nth-child(5)').textContent;
                    let rowTimeStart = row.querySelector('td:nth-child(6)').textContent;
                    let rowDateEnd = row.querySelector('td:nth-child(5)').textContent;
                    let rowTimeEnd = row.querySelector('td:nth-child(6)').textContent;
                    let rowCreatedAt = row.querySelector('td:nth-child(2)').textContent;

                    if (rowConferenceRoomId === conferenceRoomId && rowDateStart === dateStart && rowTimeStart === timeStart && rowDateEnd === dateEnd && rowTimeEnd === timeEnd && rowCreatedAt === createdAt) {
                        row.querySelector('td:nth-child(7)').textContent = data.availability;
                    }
                });
            })
            .catch(error => console.error('Error:', error));
    }

    document.querySelectorAll('tbody tr').forEach(row => {
        row.querySelector('a').addEventListener('click', function() {
            let conferenceRoomId = row.querySelector('td:nth-child(3)').textContent;
            let dateStart = row.querySelector('td:nth-child(5)').textContent;
            let timeStart = row.querySelector('td:nth-child(6)').textContent;
            let dateEnd = row.querySelector('td:nth-child(5)').textContent;
            let timeEnd = row.querySelector('td:nth-child(6)').textContent;
            let createdAt = row.querySelector('td:nth-child(2)').textContent;

            updateAvailability(conferenceRoomId, dateStart, timeStart, dateEnd, timeEnd, createdAt);
        });
    });

    // Availability
    function fetchSortedData(order) {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);
    const params = new URLSearchParams(formData).toString();
    fetch(`/fetchSortedRequests?sort=created_at&order=${order}&${params}`)
        .then(response => response.json())
        .then(data => {
            updateTable(data);
        })
        .catch(error => {
            console.error('There was a problem with the fetch operation:', error);
            alert(`An error occurred while fetching data: ${error.message}`);
        });
}

function updateTable(data) {
    let tbody = document.querySelector('tbody');
    tbody.innerHTML = '';

    data.forEach(request => {
        // Ensure that availability is fetched before rendering the row
        fetch('/checkAvailability', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                conference_room_id: request.conference_room_id,
                date_start: request.date_start,
                time_start: request.time_start,
                date_end: request.date_end,
                time_end: request.time_end,
                created_at: request.created_at
            })
        })
        .then(response => response.json())
        .then(availability => {
            let row = `<tr>
                <th scope="row">${request.CRequestID}</th>
                <td>${new Date(request.created_at).toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\//g, '-')}</td>
                <td>${request.conference_room ? request.conference_room.CRoomName : 'N/A'}</td>
                <td>${request.office ? request.office.OfficeName : 'N/A'}</td>
                <td>${request.date_start}</td>
                <td>${request.time_start}</td>
                <td>${availability}</td>
                <td><span class="${request.FormStatus.toLowerCase()}">${request.FormStatus}</span></td>
                <td>${request.EventStatus}</td>
                <td>
                    <a href="/conferencerequest/${request.CRequestID}/edit"><i class="bi bi-pencil" id="actions"></i></a>
                    <i class="bi bi-download" id="actions"></i>
                </td>
            </tr>`;
            tbody.insertAdjacentHTML('beforeend', row);
        })
        .catch(error => console.error('Error:', error));
    });
}

</script>
