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
                    <div class="dropdown-content">
                        <p id="filterlabel">Filter By</p>
                        <hr>
                        <p>Conference Room</p>
                        <a>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault"
                                       id="flexRadioDefault1">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    MAAGAP Conference
                                </label>
                            </div>
                        </a>
                        <a>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="flexRadioDefault"
                                       id="flexRadioDefault1">
                                <label class="form-check-label" for="flexRadioDefault1">
                                    MAGITING Conference
                                </label>
                            </div>
                        </a>
                        <p>Status</p>
                        <a>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Pending
                                </label>
                            </div>
                        </a>
                        <a>
                            <div class="form-check" id="margincheck">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Approved and Ongoing
                                </label>
                            </div>
                        </a>
                        <hr>
                        <div class="buttons">
                            <button class="cancelbtn">Remove</button>
                            <button class="applybtn">Filter</button>
                        </div>
                    </div>
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
                <tr>
                    <th scope="row">{{ $request->CRequestID }}</th>
                    <td>{{ $request->created_at->format('m-d-Y') }}</td>
                    <td>{{ $request->conferenceRoom->CRoomName }}</td>
                    <td>{{ $request->office->OfficeName }}</td>
                    <td>{{ $request->date_start }}</td>
                    <td>{{ $request->time_start }}</td>
                    <td>{{ $request->conferenceRoom->Availability }}</td>
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
    fetch(`/conference-requests?sort=created_at&order=${order}`)
        .then(response => {
            if (!response.ok) {
                return response.json().then(errorData => {
                    throw new Error(`Error: ${errorData.message}`);
                });
            }
            return response.json();
        })
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
        console.log(request);
        let conferenceRoomName = request.conferenceRoom ? request.conferenceRoom.CRoomName : 'N/A';
        let officeName = request.office ? request.office.OfficeName : 'N/A';
        let availability = request.conferenceRoom ? request.conferenceRoom.Availability : 'N/A';
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
                <a href="/conference-requests/${request.CRequestID}/edit"><i class="bi bi-pencil" id="actions"></i></a>
                <i class="bi bi-download" id="actions"></i>
            </td>
        </tr>`;
        tbody.insertAdjacentHTML('beforeend', row);
    });
}
</script>
