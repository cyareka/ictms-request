<style>
     .modal-container {
        display: flex;
        justify-content: center;
        align-items: center;
        position: fixed;
        top: -10%;
        left: 73%;
        width: 20%;
        height: 100%;
    }

    .modal-content {
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }
    .close {
        color: #aaa;
        position: absolute;
        top: 10px;
        right: 10px;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
    }

    .form-group input {
        width: 100%;
        padding: 8px;
        box-sizing: border-box;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        outline: none;
        color: #fff;
        background-color: #007bff;
        border: none;
        border-radius: 5px;
    }

    .btn:hover {
        background-color: #0056b3;
    }
    .pagination_rounded, .pagination_square {
    display: inline-block;
    margin-left:460px;
    margin-top:10px;
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
                <a href="javascript:void(0);" id="downloadLink"><i class="bi bi-download"
                                                                   style="font-size:16px; margin-right:14px;"></i></a>
                <div id="dateRangeModal" class="modal" style="display: none;">
                <div class="modal-container">
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <h2>Select Date Range</h2>
                        <form id="dateRangeForm" action="{{ route('downloadRangeVRequestPDF') }}">
                            @csrf
                            <div class="form-group">
                                <label for="startDate">Start Date:</label>
                                <input type="date" id="startDate" name="startDate" required>
                            </div>
                            <div class="form-group">
                                <label for="endDate">End Date:</label>
                                <input type="date" id="endDate" name="endDate" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Download</button>
                            </div>
                        </form>
                    </div>
                </div>
                </div>
                <div class="dropdown" style="float:right;">
                    <button class="dropbtn"><i class="bi bi-filter"></i></button>
                    <div class="dropdown-content">
                        <p id="filterlabel">Filter By</p>
                        <hr>
                        <p>Status</p>
                        <a>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Not Approved
                                </label>
                            </div>
                        </a>
                        <a>
                            <div class="form-check" id="margincheck">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Approved and Cancelled
                                </label>
                            </div>
                        </a>
                        <a>
                            <div class="form-check" id="margincheck">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                                <label class="form-check-label" for="flexCheckDefault">
                                    Approved and Finished
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
                        Date & Time Requested
                    </a>
                </th>
                <th scope="col">Place of Travel</th>
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
            $filteredRequests = App\Models\VehicleRequest::with('office')
                ->whereIn('FormStatus', ['Approved', 'Not Approved'])
                ->whereIn('EventStatus', ['Finished', 'Cancelled', '-'])
                ->get();

            @endphp

            @foreach($filteredRequests as $request)
                <tr>
                    <th scope="row">{{ $request->VRequestID }}</th>
                    <td>{{ $request->created_at->format('m-d-Y (h:i A)') }}</td>
                    <td>{{ $request->Destination }}</td>
                    <td>{{ optional(App\Models\PurposeRequest::find($request->PurposeID))->purpose ?? $request->PurposeOthers }}</td>
                    <td>{{ $request->office->OfficeName ?? 'N/A' }}</td>
                    <td>{{ $request->date_start }}</td>
                    <td>{{ $request->time_start }}</td>
                    <td><span class="{{ strtolower($request->FormStatus) }}">{{ $request->FormStatus }}</span></td>
                    <td>{{ $request->EventStatus }}</td>
                    <td>
                        <a href="{{ route('vehiclelogDetail', $request->VRequestID) }}"><i class="bi bi-person-vcard"
                                                                                           id="actions"></i></a>
                        <i class="bi bi-download" id="actions"></i>
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
<div class="end"></div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
    let currentPage = 1;
    const itemsPerPage = 10;
    let lastPage = 1;
    let searchQuery = '';

    // Fetch sorted and paginated data
    function fetchSortedData(order = 'desc', page = currentPage, search = searchQuery) {
        const formData = new FormData();
        formData.append('order', order);
        formData.append('sort', 'created_at');
        formData.append('page', page);
        formData.append('per_page', itemsPerPage);
        formData.append('search_query', search); // Attach the search query here

        const params = new URLSearchParams(formData).toString();

        fetch(`/fetchSortedVLogRequests?${params}`)
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

    // Update table data
    function updateTable(data, pagination) {
        let tbody = document.querySelector('tbody');
        tbody.innerHTML = '';

        if (Array.isArray(data) && data.length > 0) {
            data.forEach(request => {
                let officeName = request.office ? request.office.OfficeName : 'N/A';
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
                    <td>{{ optional(App\Models\PurposeRequest::find($request->PurposeID))->purpose ?? $request->PurposeOthers }}</td>
                    <td>${request.office?.OfficeName || 'N/A'}</td>
                    <td>${request.date_start}</td>
                    <td>${request.time_start}</td>
                    <td><span class="${request.FormStatus.toLowerCase()}">${request.FormStatus}</span></td>
                    <td>${request.EventStatus}</td>
                    <td>
                        <a href="/vehiclerequest/${request.VRequestID}/log"><i class="bi bi-person-vcard" id="actions"></i></a>
                        <i class="bi bi-download" id="actions"></i>
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
    const paginationContainer = document.querySelector('.pagination_rounded ul');
    paginationContainer.innerHTML = ''; // Clear the current pagination

    // Previous button
    let prevDisabled = pagination.current_page <= 1 ? 'disabled' : '';
    paginationContainer.insertAdjacentHTML('beforeend', `
        <li>
            <a href="#" class="prev ${prevDisabled}">
                <i class="fa fa-angle-left" aria-hidden="true"></i> Prev
            </a>
        </li>
    `);

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
    paginationContainer.insertAdjacentHTML('beforeend', `
        <li>
            <a href="#" class="next ${nextDisabled}">
                Next <i class="fa fa-angle-right" aria-hidden="true"></i>
            </a>
        </li>
    `);
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

    // Sorting event
    document.getElementById('sort-date-requested').addEventListener('click', function (e) {
        e.preventDefault();
        let order = this.getAttribute('data-order');
        let newOrder = order === 'asc' ? 'desc' : 'asc';
        this.setAttribute('data-order', newOrder);
        fetchSortedData(newOrder, currentPage, searchQuery);
    });

    // Search query
    document.querySelectorAll('.form-input').forEach(input => {
        input.addEventListener('input', function () {
            searchQuery = document.querySelector('.form-input').value;
            fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'), currentPage, searchQuery);
        });
    });

    // Initial fetch
    fetchSortedData();
});

</script>
