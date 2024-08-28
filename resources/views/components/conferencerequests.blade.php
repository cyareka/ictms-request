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
                <div id="pagination-links">
                    <i class="bi bi-arrow-left-short" id="prev-page"></i>
                    <i class="bi bi-arrow-right-short" id="next-page"></i>
                </div>
                <div class="dropdown" style="float:right;">
                    <button class="dropbtn"><i class="bi bi-filter"></i></button>
                    <form id="filterForm" method="GET" action="{{ route('fetchSortedRequests') }}">
                        <div class="dropdown-content">
                            <p id="filterlabel">Filter By</p>
                            <hr>
                            <p>Conference Room</p>
                            <a>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="conference_room" value="Maagap"
                                           id="flexRadioDefault1">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Maagap
                                    </label>
                                </div>
                            </a>
                            <a>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="conference_room" value="Magiting"
                                           id="flexRadioDefault2">
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Magiting
                                    </label>
                                </div>
                            </a>
                            <p>Status</p>
                            <a>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="form_statuses[]"
                                           value="Pending" id="flexCheckDefault1">
                                    <label class="form-check-label" for="flexCheckDefault1">
                                        Pending
                                    </label>
                                </div>
                            </a>
                            <a>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="form_statuses[]" value="For Approval" id="flexCheckDefault2">
                                    <label class="form-check-label" for="flexCheckDefault2">
                                        For Approval
                                    </label>
                                </div>
                            </a>
                            <a>
                                <div class="form-check" id="margincheck">
                                    <input class="form-check-input" type="checkbox" name="form_statuses[]"
                                           value="Approved" id="flexCheckDefault2">
                                    <label class="form-check-label" for="flexCheckDefault3">
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
                        Date/Time Requested
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
                $filteredRequests = App\Models\ConferenceRequest::whereIn('FormStatus', ['Approved', 'Pending', 'For Approval'])
                    ->whereIn('EventStatus', ['Ongoing', '-'])
                    ->get();

                function convertAvailability($availability): string
                {
                    return $availability > 0 ? 'Available' : 'Not Available';
                }
            @endphp

            @foreach($filteredRequests as $request)
                <tr>
                    <th scope="row">{{ $request->CRequestID }}</th>
                    <td>{{ $request->created_at->format('m/d/Y (h:i A)') }}</td>
                    <td>{{ $request->conferenceRoom->CRoomName }}</td>
                    <td>{{ $request->office->OfficeName }}</td>
                    <td>{{ $request->date_start }}</td>
                    <td>{{ $request->time_start }}</td>
                    <td>{{ convertAvailability($request->CAvailability) }}</td>
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
    function convertAvailability(availability) {
        return availability > 0 ? 'Available' : 'Not Available';
    }

    document.addEventListener('DOMContentLoaded', function () {
        let currentPage = 1;
        const itemsPerPage = 5;
        let lastPage = 1;

        function fetchSortedData(order = 'desc', page = currentPage) {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);

            formData.append('order', order);
            formData.append('sort', 'created_at');
            formData.append('page', page);
            formData.append('per_page', itemsPerPage);

            const params = new URLSearchParams(formData).toString();

            fetch(`/fetchSortedRequests?${params}`)
                .then(response => response.json())
                .then(data => {
                    updateTable(data.data);
                    updatePagination(data.pagination);
                    currentPage = data.pagination.current_page;
                    lastPage = data.pagination.last_page;
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                    alert(`An error occurred while fetching data: ${error.message}`);
                });
        }

        function updatePagination(pagination) {
            currentPage = pagination.current_page;
            lastPage = pagination.last_page;

            document.getElementById('prev-page').style.visibility = currentPage > 1 ? 'visible' : 'hidden';
            document.getElementById('next-page').style.visibility = currentPage < lastPage ? 'visible' : 'hidden';
        }

        function updateTable(data) {
            let tbody = document.querySelector('tbody');
            tbody.innerHTML = '';

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(request => {
                    let conferenceRoomName = request.conference_room ? request.conference_room.CRoomName : 'N/A';
                    let officeName = request.office ? request.office.OfficeName : 'N/A';
                    let row = `<tr>
                    <th scope="row">${request.CRequestID}</th>
                    <td>
                        ${new Date(request.created_at).toLocaleDateString('en-US', {
                            year: 'numeric',
                            month: '2-digit',
                            day: '2-digit'
                        })}
                        <br>
                        ${new Date(request.created_at).toLocaleTimeString('en-US', {
                            hour: '2-digit',
                            minute: '2-digit',
                            hour12: true
                        })}
                    </td>
                    <td>${conferenceRoomName}</td>
                    <td>${officeName}</td>
                    <td>${request.date_start}</td>
                    <td>${request.time_start}</td>
                    <td>${convertAvailability(request.CAvailability)}</td>
                    <td><span class="${request.FormStatus.toLowerCase()}">${request.FormStatus}</span></td>
                    <td>${request.EventStatus}</td>
                    <td>
                        <a href="/conferencerequest/${request.CRequestID}/edit"><i class="bi bi-pencil" id="actions"></i></a>
                        <i class="bi bi-download" id="actions"></i>
                    </td>
                </tr>`;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="10">No requests found.</td></tr>';
            }
        }

        document.getElementById('sort-date-requested').addEventListener('click', function (e) {
            e.preventDefault();
            let order = this.getAttribute('data-order');
            let newOrder = order === 'asc' ? 'desc' : 'asc';
            this.setAttribute('data-order', newOrder);
            fetchSortedData(newOrder);
        });

        document.getElementById('prev-page').addEventListener('click', function () {
            if (currentPage > 1) {
                fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'), currentPage - 1);
            }
        });

        document.getElementById('next-page').addEventListener('click', function () {
            if (currentPage < lastPage) {
                fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'), currentPage + 1);
            }
        });

        document.getElementById('filterForm').addEventListener('submit', function (event) {
            event.preventDefault();
            fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'));
        });

        document.querySelector('.cancelbtn').addEventListener('click', function () {
            document.getElementById('filterForm').reset();
            fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'));
        });

        fetchSortedData();
    });
</script>
