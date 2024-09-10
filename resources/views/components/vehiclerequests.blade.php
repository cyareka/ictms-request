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
                    <i class="bi bi-arrow-right-short" id="next-page"></i></div>
            </div>
            <div class="dropdown" style="float:right;">
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
                                <input class="form-check-input" type="checkbox" name="form_statuses[]" value="For Approval" id="flexCheckDefault2">
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
                <th scope="col">Availability</th>
                <th scope="col">Form Status</th>
                <th scope="col">Event Status</th>
                <th scope="col"></th>
            </tr>
            </thead>
            <tbody>
            @php
                $filteredRequests = App\Models\VehicleRequest::whereIn('FormStatus', ['Approved', 'Pending', 'For Approval'])
                    ->whereIn('EventStatus', ['Ongoing', '-'])
                    ->get();

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
                        <a href="{{ route('VehicledetailEdit', $request->VRequestID) }}"><i class="bi bi-pencil" id="actions"></i></a>
                        @if($request->FormStatus !== 'Pending')
                        <i class="bi bi-download" id="actions" data-request-id="{{ $request->VRequestID }}"></i>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
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

        const params = new URLSearchParams(formData).toString();

        // Fetch data with search and sort applied
        fetch(`/fetchSortedVRequests?${params}`)
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
        let tbody = document.querySelector('tbody');
        tbody.innerHTML = '';

        if (Array.isArray(data) && data.length > 0) {
            data.forEach(request => {
                let officeName = request.office ? request.office.OfficeName : 'N/A';
                let purposeName = request.PurposeOthers || request.purpose?.PurposeName || 'N/A';

                let row = `<tr>
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
                    <td>${purposeName}</td>
                    <td>${officeName}</td>
                    <td>${request.date_start}</td>
                    <td>${request.time_start}</td>
                    <td><span class="${request.FormStatus.toLowerCase()}">${request.FormStatus}</span></td>
                    <td>${request.EventStatus}</td>
                    <td>
                        <a href="/vehiclerequest/${request.VRequestID}/edit"><i class="bi bi-pencil" id="actions"></i></a>
                        <i class="bi bi-download" id="actions" data-request-id="${request.VRequestID}"></i>
                    </td>
                </tr>`;
                
                tbody.insertAdjacentHTML('beforeend', row);
            });
        } else {
            tbody.innerHTML = '<tr><td colspan="10">No requests found.</td></tr>';
        }

        updatePagination(pagination);
    }

    // Function to handle pagination
    function updatePagination(pagination) {
        currentPage = pagination.current_page;
        lastPage = pagination.last_page;

        document.getElementById('prev-page').style.visibility = currentPage > 1 ? 'visible' : 'hidden';
        document.getElementById('next-page').style.visibility = currentPage < lastPage ? 'visible' : 'hidden';
    }

    // Sort by date when clicking on the "Sort" button
    document.getElementById('sort-date-requested').addEventListener('click', function (e) {
        e.preventDefault();
        let order = this.getAttribute('data-order');
        let newOrder = order === 'asc' ? 'desc' : 'asc';
        this.setAttribute('data-order', newOrder);
        fetchSortedData(newOrder, currentPage, searchQuery);
    });

    // Handle pagination (previous and next buttons)
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

    // Handle form submission and search
    document.getElementById('filterForm').addEventListener('submit', function (event) {
        event.preventDefault();
        searchQuery = document.getElementById('search-input').value.trim(); // Update searchQuery from the input field
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
