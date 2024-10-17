<style>
    .pagination_rounded, .pagination_square {
        display: block;
        margin-top: 15px;
        text-align: center; /* Center the pagination buttons */
        width: 100%; /* Ensure the container takes the full width */
    }

    .pagination_rounded ul {
        margin: 0;
        padding: 0;
        list-style: none;
        display: inline-block; /* Make the list inline-block to center it */
    }

    .pagination_rounded ul li {
        display: inline; /* Display list items inline */
        margin-left: 20px;
    }

    .pagination_rounded ul li a {
        float: none; /* Remove float */
        display: inline-block; /* Ensure links are inline-block */
        color: #000000;
        border-radius: 50%;
        line-height: 30px;
        height: 30px;
        width: 30px;
        text-align: center;
        margin-bottom: 40px;
        border: 1px solid #e0e0e0;
    }

    .pagination_rounded ul li a:hover {
        background: #4285f4;
        color: #fff;
        border: 1px solid #4285f4;
    }

    .pagination_rounded .prev, .pagination_rounded .next {
        border-radius: 35px;
        width: 90px;
        height: 34px;
        line-height: 34px;
    }

    a:link {
        text-decoration: none;
    }

    .visible-xs {
        display: none !important;
    }
    /* Add custom styles for the refresh button */
    #refreshBtn {
        cursor: pointer;
        color: #000;
        font-size: 24px;
        transition: color 0.3s ease;
        margin-left:-10px;
        transition: transform 0.5s ease;
        cursor: pointer;
    }

    #refreshBtn:hover {
        transform: rotate(360deg);
    }

    /* #refreshBtn.disabled {
        color: #ccc;
        cursor: not-allowed;
    } */
</style>
<script>
    let isCooldown = false;

    function refreshPage() {
        const refreshBtn = document.getElementById('refreshBtn');
        // Perform refresh action
        location.reload(); // or any specific refresh logic if needed
    }
</script>

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
                <div class="dropdown" style="float:right;">
                    <i id="refreshBtn" class="bi bi-arrow-clockwise" onclick="refreshPage()" title="Refresh" style="font-size: 16px; margin-right: 10px;"></i>
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
    use Carbon\Carbon;
    use App\Models\ConferenceRequest;

    // Get the current date and time
    $now = Carbon::now('Asia/Manila');

    // Fetch requests that need to be updated
    $filteredRequests = ConferenceRequest::whereIn('FormStatus', ['Approved', 'Pending', 'For Approval'])
        ->whereIn('EventStatus', ['Ongoing', '-'])
        ->get();

    foreach ($filteredRequests as $request) {
        // Check if the FormStatus is Pending or For Approval and date/time has passed
        if (in_array($request->FormStatus, ['Pending', 'For Approval'])) {
            if ($request->date_start < $now->toDateString() ||
                ($request->date_start == $now->toDateString() && $request->time_start < $now->toTimeString())) {

                // Update FormStatus to Not Approved if date/time exceeded
                $request->FormStatus = 'Not Approved';
                $request->save();
            }
        }

        // Check if the FormStatus is Approved and EventStatus is Ongoing, and date/time has passed
        if ($request->FormStatus == 'Approved' && $request->EventStatus == 'Ongoing') {
            if ($request->date_end < $now->toDateString() ||
                ($request->date_end == $now->toDateString() && $request->time_end < $now->toTimeString())) {

                // Update EventStatus to Finished if date/time exceeded
                $request->EventStatus = 'Finished';
                $request->save();
            }
        }
    }

    // Function to convert availability
    function convertAvailability($availability): string
    {
        if (is_null($availability)) {
            return '-';
        }
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
                <ul id="pagination-list">
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
</style>
<script>
    function convertAvailability(availability) {
        return availability > 0 ? 'Available' : 'Not Available';
    }

    document.addEventListener('DOMContentLoaded', function () {
        let currentPage = 1;
        const itemsPerPage = 5;
        let lastPage = 1;
        let searchQuery = '';

        function fetchSortedData(order = 'desc', page = currentPage, search = searchQuery) {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);

            // Append ordering and pagination data
            formData.append('order', order);
            formData.append('sort', 'created_at');
            formData.append('page', page);
            formData.append('per_page', itemsPerPage);

            // Fetch the search query from the input field (this avoids duplication)
            const searchInput = document.querySelector('.form-input').value;
            formData.set('search', searchInput); // Use set instead of append to prevent duplicates

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

    const paginationList = document.getElementById('pagination-list');
    paginationList.innerHTML = '';

    // Add "Prev" button
    const prevPageItem = document.createElement('li');
    const prevPageLink = document.createElement('a');
    prevPageLink.href = '#';
    prevPageLink.classList.add('prev');
    prevPageLink.innerHTML = `<i class="fa fa-angle-left" aria-hidden="true"></i> Prev`;
    prevPageLink.addEventListener('click', function (e) {
        e.preventDefault();
        if (currentPage > 1) {
            fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'), currentPage - 1, searchQuery);
        }
    });
    prevPageItem.appendChild(prevPageLink);
    paginationList.appendChild(prevPageItem);

    // Add numbered page links
    for (let i = 1; i <= lastPage; i++) {
        if (i === currentPage || 
            i === currentPage - 1 || 
            i === currentPage - 2 || 
            i === currentPage + 1 || 
            i === currentPage + 2) {
            
            const pageItem = document.createElement('li');
            const pageLink = document.createElement('a');
            pageLink.href = '#';
            pageLink.textContent = i;
            if (i === currentPage) {
                pageLink.style.color = 'white';
                pageLink.style.backgroundColor = '#4285f4';
            }
            pageLink.addEventListener('click', function (e) {
                e.preventDefault();
                fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'), i, searchQuery);
            });
            pageItem.appendChild(pageLink);
            paginationList.appendChild(pageItem);
        }
    }

    // Add "Next" button
    const nextPageItem = document.createElement('li');
    const nextPageLink = document.createElement('a');
    nextPageLink.href = '#';
    nextPageLink.classList.add('next');
    nextPageLink.innerHTML = `Next <i class="fa fa-angle-right" aria-hidden="true"></i>`;
    nextPageLink.addEventListener('click', function (e) {
        e.preventDefault();
        if (currentPage < lastPage) {
            fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'), currentPage + 1, searchQuery);
        }
    });
    nextPageItem.appendChild(nextPageLink);
    paginationList.appendChild(nextPageItem);
}

        function updateTable(data) {
            let tbody = document.querySelector('tbody');
            tbody.innerHTML = '';

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(request => {
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
                    row += `</td></tr>`;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="10">No requests found.</td></tr>';
            }
        }

        // Sorting event handler
        document.getElementById('sort-date-requested').addEventListener('click', function (e) {
            e.preventDefault();
            let order = this.getAttribute('data-order');
            let newOrder = order === 'asc' ? 'desc' : 'asc';
            this.setAttribute('data-order', newOrder);
            fetchSortedData(newOrder, currentPage, searchQuery);
        });

        // Form submit event handler for filtering
        document.getElementById('filterForm').addEventListener('submit', function (event) {
            event.preventDefault();
            searchQuery = document.querySelector('.form-input').value;
            fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'), currentPage, searchQuery);
        });

        // Reset filters event handler
        document.querySelector('.cancelbtn').addEventListener('click', function () {
            document.getElementById('filterForm').reset();
            searchQuery = '';
            fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'));
        });

        // Search input event handler
        document.querySelector('.form-input').addEventListener('input', function () {
            fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'));
        });

        // Initial fetch
        fetchSortedData();
    });
</script>
