<style>
    .modal {
        display: flex;
        justify-content: center;
        align-items: center;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Optional: for a semi-transparent background */
    }

    .modal-dialog {
        max-width: 500px; /* Adjust the width as needed */
        margin: auto;
    }
     .pagination_rounded, .pagination_square {
    display: inline-block;
    margin-left:470px;
    margin-top: 10px;
    margin-bottom: 0;
    }

    .pagination_rounded ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .pagination_rounded li:first-child {
        margin-left: 0px;
    }

    .pagination_rounded ul li {
        float: left;
        margin-left: 20px;
    }

    .pagination_rounded ul li a:hover {
        background: #4285f4;
        color: #fff;
        border: 1px solid #4285f4;
    }

    a:link {
    text-decoration: none;
    }

    .pagination_rounded .prev {
        margin-left: 0px;
        border-radius: 35px;
        width: 90px;
        height: 34px;
        line-height: 34px;
    }


    .pagination_rounded ul li a {
        float: left;
        color: #4285f4;
        border-radius: 50%;
        line-height: 30px;
        height: 30px;
        width: 30px;
        text-align: center;
        margin-bottom: 40px;
        border: 1px solid #e0e0e0;
    }

    .pagination_rounded .prev i {
        margin-right: 10px;
    }

    .pagination_rounded .next {
        border-radius: 35px;
        width: 90px;
        height: 34px;
        line-height: 34px;
    }

    .visible-xs {
        display: none!important;
    }
</style>

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
                                    <input class="form-check-input" type="checkbox" name="form_statuses[]"
                                           value="Pending" id="flexCheckDefault1">
                                    <label class="form-check-label" for="flexCheckDefault1">Pending</label>
                                </div>
                            </a>
                            <a>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="form_statuses[]"
                                           value="For Approval" id="flexCheckDefault2">
                                    <label class="form-check-label" for="flexCheckDefault2">For Approval</label>
                                </div>
                            </a>
                            <a>
                                <div class="form-check" id="margincheck">
                                    <input class="form-check-input" type="checkbox" name="form_statuses[]"
                                           value="Approved" id="flexCheckDefault3">
                                    <label class="form-check-label" for="flexCheckDefault3">Approved and Ongoing</label>
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
                    <a href="#" id="sort-date-requested" data-order="desc">Date/Time Requested</a>
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
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="pagination_rounded">
                        <ul>
                            <li>
                                <a href="#" class="prev"> <i class="fa fa-angle-left" aria-hidden="true"></i> Prev </a>
                            </li>
                            <li><a href="#">1</a>
                            </li>
							<li class="hidden-xs"><a href="#">2</a>
                            </li>
                            <li class="hidden-xs"><a href="#">3</a>
                            </li>
                            <li class="hidden-xs"><a href="#">4</a>
                            </li>
                            <li class="hidden-xs"><a href="#">5</a>
                            </li>
							<li class="visible-xs"><a href="#">...</a>
                            </li>
							<li><a href="#">6</a>
                            </li>
                            <li><a href="#" class="next"> Next <i class="fa fa-angle-right" aria-hidden="true"></i></a>
                            </li>
                        </ul>
         </div>
    </div>
</div>
<div class="end"></div>
<script>
    function convertAvailability(availability) {
        return availability > 0 ? 'Available' : 'Not Available';
    }
    document.querySelector('.form-input').addEventListener('input', function () {
        document.getElementById('searchInput').value = this.value;
    });

    document.addEventListener('DOMContentLoaded', function () {
        let currentPage = 1;
        const itemsPerPage = 5;
        let lastPage = 1;
        let searchQuery = '';

        function fetchSortedData(order = 'desc', page = currentPage,search = searchQuery) {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);

            formData.append('order', order);
            formData.append('sort', 'created_at');
            formData.append('page', page);
            formData.append('per_page', itemsPerPage);
            formData.append('search', search)

            const searchInput = document.querySelector('.form-input').value;
            formData.append('search', searchInput);

            const params = new URLSearchParams(formData).toString();

            fetch(`/fetchSortedRequests?${params}`)
                .then(response => response.json())
                .then(data => {
                    console.log(data);
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
            console.log('Received data:', data);
            let tbody = document.querySelector('tbody');
            tbody.innerHTML = '';

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(request => {
                    console.log("Full request object:", request);
                    console.log("Processing CRequestID:", request.CRequestID);

                    let formStatusClass = '';
                    switch (request.FormStatus.toLowerCase()) {
                        case 'approved':
                            formStatusClass = 'approved';
                            break;
                        case 'for approval':
                            formStatusClass = 'for-approval';
                            break;
                        case 'pending':
                            formStatusClass = 'pending';
                            break;
                    }

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
                <td>${request.conference_room ? request.conference_room.CRoomName : 'N/A'}</td>
                <td>${request.office ? request.office.OfficeName : 'N/A'}</td>
                <td>${request.date_start}</td>
                <td>${request.time_start}</td>
                <td>${convertAvailability(request.CAvailability)}</td>
                <td><span class="${formStatusClass}">${request.FormStatus}</span></td>
                <td>${request.EventStatus}</td>
                <td>
                    <a href="/conferencerequest/${request.CRequestID}/edit"><i class="bi bi-pencil" id="actions"></i></a>`;
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
            fetchSortedData(newOrder, currentPage, searchQuery);
        });

        document.getElementById('prev-page').addEventListener('click', function () {
            if (currentPage > 1) {
                fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'), currentPage - 1, searchQuery);
            }
        });

        document.getElementById('next-page').addEventListener('click', function () {
            if (currentPage < lastPage) {
                fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'), currentPage + 1, searchQuery);
            }
        });

        document.getElementById('filterForm').addEventListener('submit', function (event) {
            event.preventDefault();
            searchQuery = document.getElementById('search').value;
            fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'), currentPage, searchQuery);
        });

        document.querySelector('.cancelbtn').addEventListener('click', function () {
            document.getElementById('filterForm').reset();
            searchQuery = '';
            fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'));
        });

        document.querySelector('.form-input').addEventListener('input', function () {
            fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'));
        });

        fetchSortedData();
    });
</script>
