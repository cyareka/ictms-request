<style>
    .pagination_rounded, .pagination_square {
        display: inline-block;
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
        color: #000000;
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
        display: none !important;
    }

    /* Add custom styles for the refresh button */
    #refreshBtn {
        cursor: pointer;
        color: #000;
        font-size: 24px;
        transition: color 0.3s ease;
        margin-left: 850px;
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
            <div id="divide"></div>
            <div class="dropdown" style="float:right;">
                <i id="refreshBtn" class="bi bi-arrow-clockwise" onclick="refreshPage()" title="Refresh"
                   style="font-size: 16px; margin-right: 10px;"></i>
                <button class="dropbtn"><i class="bi bi-filter"></i></button>
                <form id="filterForm" method="GET" action="{{ route('fetchSortedVRequests') }}">
                    <div class="dropdown-content">
                        <p id="filterlabel">Filter By</p>
                        <hr>
                        <p>Status</p>
                        <a>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="Pending" name="form_statuses[]">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Pending
                                </label>
                            </div>
                        </a>
                        <a>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="form_statuses[]"
                                       value="For Approval" id="flexCheckDefault2">
                                <label class="form-check-label" for="flexCheckDefault2">
                                    For Approval
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
    <br>

    {{-- display the table of vehicle requests --}}
    <div class="tabview">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">
                    <a href="#" id="sort-date-requested" data-order="desc">
                        Date & Time Requested
                    </a>
                </th>
                <th scope="col">Destination</th>
                <th scope="col">Purpose</th>
                <th scope="col">Requesting Office</th>
                <th scope="col">Date Needed</th>
                <th scope="col">Time Needed</th>
                <th scope="col">Form Status</th>
                <th scope="col">Event Status</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @php
                use Carbon\Carbon;
                use App\Models\VehicleRequest;
                use App\Mail\VehicleRequestApprovedMail;
                use Illuminate\Support\Facades\Mail;

                // Get the current date and time
                $now = Carbon::now('Asia/Manila');

                // Fetch requests that need to be updated
                $filteredRequests = VehicleRequest::whereIn('FormStatus', ['Approved', 'Pending', 'For Approval'])
                    ->whereIn('EventStatus', ['Ongoing', '-'])
                    ->with('purpose_request')
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
                    if ($request->FormStatus == 'Approved') {
                        if ($request->date_end < $now->toDateString()) {

                            // Update EventStatus to Finished if date/time exceeded
                            $request->EventStatus = 'Finished';
                            $request->save();
                        }

                        // Send email to requester if it hasn't been sent yet
            if (!$request->is_email_sent) {
                // Send an email to the requester
                Mail::to($request->RequesterEmail)->send(new VehicleRequestApprovedMail($request));

                // Mark email as sent
                $request->is_email_sent = true;
                $request->save();
            }
                    }
                }
            @endphp

            @foreach($filteredRequests as $request)
                <tr>
                    <th scope="row">{{ $request->VRequestID }}</th>
                    <td>{{ $request->created_at->format('m-d-Y (h:i A)') }}</td>
                    <td>{{ $request->Destination }}</td>
                    <td>{{ optional(App\Models\PurposeRequest::find($request->PurposeID))->purpose ?? $request->PurposeOthers }}</td>
                    <td>{{ $request->office->OfficeName }}</td>
                    <td>{{ $request->date_start }}</td>
                    <td>{{ $request->time_start }}</td>
                    <td><span class="{{ strtolower($request->FormStatus) }}">{{ $request->FormStatus }}</span></td>
                    <td>{{ $request->EventStatus }}</td>
                    <td>
                        <a href="{{ route('VehicledetailEdit', $request->VRequestID) }}"><i class="bi bi-pencil"
                                                                                            id="actions"></i></a>
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
    document.addEventListener('DOMContentLoaded', function () {
        let currentPage = 1;
        const itemsPerPage = 10;
        let lastPage = 1;
        let searchQuery = '';

        function fetchSortedData(order = 'desc', page = currentPage, search = searchQuery) {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);

            // Append necessary data
            formData.append('order', order);
            formData.append('sort', 'created_at');
            formData.append('page', page);
            formData.append('per_page', itemsPerPage);
            formData.append('search_query', search);

            // Serialize the form data into query parameters
            const params = new URLSearchParams();
            formData.forEach((value, key) => {
                params.append(key, value);
            });

            // Fetch data with search and sort applied
            fetch(`/fetchSortedVRequests?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    updateTable(data.data, data.pagination);
                    currentPage = data.pagination.current_page;
                    lastPage = data.pagination.last_page;
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
        }

        // Function to update the table with the fetched data
        function updateTable(data, pagination) {
            console.log('updateTable called with data:', data);
            console.log('updateTable called with pagination:', pagination);

            let tbody = document.querySelector('tbody');
            tbody.innerHTML = '';

            if (Array.isArray(data) && data.length > 0) {
                console.log('Data is an array with', data.length, 'items');
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

                    let officeName = request.office ? request.office.OfficeName : 'N/A';
                    let purposeName = request.PurposeOthers || request.PurposeID || 'N/A';

                    let row = `
                    <tr>
                      <th scope="row">${request.VRequestID}</th>
                      <td>${new Date(request.created_at).toLocaleDateString('en-US', {
                                    year: 'numeric',
                                    month: '2-digit',
                                    day: '2-digit'
                                })} ${new Date(request.created_at).toLocaleTimeString('en-US', {
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    hour12: true
                                })}</td>
                      <td>${request.Destination}</td>

                    <td>{{ isset($request) ? optional(App\Models\PurposeRequest::find($request->PurposeID))->purpose ?? $request->PurposeOthers : '' }}</td>

                      <td>${officeName}</td>
                      <td>${request.date_start}</td>
                      <td>${request.time_start}</td>
                      <td><span class="${formStatusClass}">${request.FormStatus}</span></td>
                      <td>${request.EventStatus}</td>
                      <td>
                        <a href="/vehiclerequest/${request.VRequestID}/edit"><i class="bi bi-pencil" id="actions"></i></a>
                      </td>
                    </tr>`;

                    tbody.insertAdjacentHTML('beforeend', row);
                });
            } else {
                console.log('No data found');
                tbody.innerHTML = '<tr><td colspan="10">No requests found.</td></tr>';
            }

            updatePagination(pagination);
        }

        // Function to handle pagination
        function updatePagination(pagination) {
            const paginationContainer = document.querySelector('.pagination_rounded ul');
            paginationContainer.innerHTML = ''; // Clear the current pagination

            // Previous button
            let prevDisabled = pagination.current_page <= 1 ? 'disabled' : '';
            paginationContainer.insertAdjacentHTML('beforeend', `<li><a href="#" class="prev ${prevDisabled}">Prev</a></li>`);

            // Page numbers
            for (let page = 1; page <= pagination.last_page; page++) {
                let activeClass = page === pagination.current_page ? 'active' : '';

                // Create the list item element
                let listItem = document.createElement('li');
                listItem.className = activeClass;

                // Create the anchor element
                let pageLink = document.createElement('a');
                pageLink.href = '#';
                pageLink.textContent = page;

                // If it's the current page, change the font color
                if (page === pagination.current_page) {
                    pageLink.style.color = 'white';  // Change font color to white (or any color you prefer)
                    pageLink.style.backgroundColor = '#4285f4'; // Change background color to the desired active color
                }

                // Append the anchor to the list item
                listItem.appendChild(pageLink);

                // Append the list item to the pagination container
                paginationContainer.appendChild(listItem);
            }

            // Next button
            let nextDisabled = pagination.current_page >= pagination.last_page ? 'disabled' : '';
            paginationContainer.insertAdjacentHTML('beforeend', `<li><a href="#" class="next ${nextDisabled}">Next</a></li>`);
        }

        // Event listeners for pagination links
        document.querySelector('.pagination_rounded').addEventListener('click', function (e) {
            if (e.target.tagName === 'A') {
                e.preventDefault();
                let text = e.target.textContent.trim();

                if (text === 'Prev' && currentPage > 1) {
                    fetchSortedData('desc', currentPage - 1, searchQuery);
                } else if (text === 'Next' && currentPage < lastPage) {
                    fetchSortedData('desc', currentPage + 1, searchQuery);
                } else if (!isNaN(text)) {
                    fetchSortedData('desc', parseInt(text), searchQuery);
                }
            }
        });

        // Sort by date when clicking on the "Sort" button
        document.getElementById('sort-date-requested').addEventListener('click', function (e) {
            e.preventDefault();
            let order = this.getAttribute('data-order');
            let newOrder = order === 'asc' ? 'desc' : 'asc';
            this.setAttribute('data-order', newOrder);
            fetchSortedData(newOrder, currentPage, searchQuery);
        });

        // Handle form submission and search
        document.getElementById('filterForm').addEventListener('submit', function (event) {
            event.preventDefault();
            searchQuery = document.querySelector('.form-input').value.trim(); // Update searchQuery from the input field
            fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'), currentPage, searchQuery);
        });

        // Handle reset functionality
        document.querySelector('.cancelbtn').addEventListener('click', function () {
            document.getElementById('filterForm').reset();
            searchQuery = ''; // Reset searchQuery
            fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'));
        });

        // Instant search functionality while typing
        document.querySelector('.form-input').addEventListener('input', function () {
            searchQuery = this.value.trim(); // Update searchQuery on input change
            fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'), currentPage, searchQuery);
        });

        // Initial fetch when page loads
        fetchSortedData();
    });
</script>
