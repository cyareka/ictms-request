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
                    <form id="filterForm" method="GET" action="{{ route('fetchSortedVRequests') }}">
                        <div class="dropdown-content">
                            <p id="filterlabel">Filter By</p>
                            <hr>
                            <p>Status</p>
                            <a>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Pending" name="form_statuses[]" >
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Pending
                                    </label>
                                </div>
                            </a>
                            <a>
                                <div class="form-check" id="margincheck">
                                    <input class="form-check-input" type="checkbox" value="Approved" name="form_statuses[]">
                                    <label class="form-check-label" for="flexCheckDefault">
                                        Approved and Ongoing
                                    </label>
                                </div>
                            </a>
                            <hr>
                            <div class="buttons">
                                <button type="button" class="cancelbtn" onclick="resetFilters()">Remove</button>
                                <button type="submit" class="applybtn">Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <br>

    {{-- display the table of vehicle requests --}}
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
                <th scope="col">Destination</th>
                <th scope="col">Purpose</th>
                <th scope="col">Requesting Office</th>
                <th scope="col">Date</th>
                <th scope="col">Time</th>
                <th scope="col">Form Status</th>
                <th scope="col">Event Status</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
                @php
                    $filteredRequests = App\Models\VehicleRequest::whereIn('FormStatus', ['Approved', 'Pending'])
                        ->whereIn('EventStatus', ['Ongoing', '-'])
                        ->get();
                @endphp

                @foreach($filteredRequests as $request)
                    <tr>
                        <th scope="row">{{ $request->VRequestID }}</th>
                        <td>{{ $request->created_at->format('m-d-Y') }}</td>
                        <td>{{ $request->Destination }}</td>
                        <td>{{ $request->Purpose }}</td>
                        <td>{{ $request->office->OfficeName }}</td>
                        <td>{{ $request->date_start }}</td>
                        <td>{{ $request->time_start }}</td>
                        <td><span class="{{ strtolower($request->FormStatus) }}">{{ $request->FormStatus }}</span></td>
                        <td>{{ $request->EventStatus }}</td>
                        <td>
                            <a href="{{ route('VehicledetailEdit', $request->VRequestID) }}"><i class="bi bi-pencil" id="actions"></i></a>
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

        fetch(`/fetchSortedVRequests?sort=created_at&order=${order}&${params}`)
            .then(response => response.json())
            .then(data => {
                updateTable(data);
            })
            .catch(error => {
                console.error('There was a problem with the fetch operation:', error);
                alert(`An error occurred while fetching data: ${error.message}`);
            });
    }

    document.getElementById('filterForm').addEventListener('submit', function (event) {
        event.preventDefault();
        const form = event.target;
        const formData = new FormData(form);
        const params = new URLSearchParams(formData).toString();
        const sortOrder = document.getElementById('sort-date-requested').getAttribute('data-order');
        fetch(`/fetchSortedVRequests?sort=created_at&order=${sortOrder}&${params}`)
            .then(response => response.json())
            .then(data => {
                updateTable(data);
            })
            .catch(error => {
                console.error('Error fetching filtered data:', error);
            });
    });

    document.querySelector('.cancelbtn').addEventListener('click', function () {
        document.getElementById('filterForm').reset();
        const sortOrder = document.getElementById('sort-date-requested').getAttribute('data-order');
        fetch(`/fetchSortedVRequests?sort=created_at&order=${sortOrder}`)
            .then(response => response.json())
            .then(data => {
                updateTable(data);
            })
            .catch(error => {
                console.error('Error fetching unfiltered data:', error);
            });
    });

    function updateTable(data) {
        let tbody = document.querySelector('tbody');
        tbody.innerHTML = '';

        if (Array.isArray(data) && data.length > 0) {
            data.forEach(request => {
                let row = `<tr>
                    <th scope="row">${request.VRequestID}</th>
                    <td>${new Date(request.created_at).toLocaleDateString('en-US', { year: 'numeric', month: '2-digit', day: '2-digit' }).replace(/\//g, '-')}</td>
                    <td>${request.Destination}</td>
                    <td>${request.Purpose}</td>
                    <td>${request.office ? request.office.OfficeName : 'N/A'}</td>
                    <td>${request.date_start}</td>
                    <td>${request.time_start}</td>
                    <td><span class="${request.FormStatus.toLowerCase()}">${request.FormStatus}</span></td>
                    <td>${request.EventStatus}</td>
                    <td>
                        <a href="/vehiclerequest/${request.VRequestID}/edit"><i class="bi bi-pencil" id="actions"></i></a>
                        <i class="bi bi-download" id="actions"></i>
                    </td>
                </tr>`;
                tbody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            console.log("No requests found or data format is incorrect.");
            tbody.innerHTML = '<tr><td colspan="10">No requests found.</td></tr>';
        }
    }
</script>
