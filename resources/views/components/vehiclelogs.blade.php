
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
    /* Add custom styles for the refresh button */
    #refreshBtn {
        cursor: pointer;
        color: #000;
        font-size: 24px;
        transition: color 0.3s ease;
        margin-left:800px;
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

        <!-- refresh icon -->
        <div class="tableactions">
            <div id="divide"></div>
            <div style="float:right;">
                <!-- Refresh button -->
                <i id="refreshBtn" class="bi bi-arrow-clockwise" onclick="refreshPage()" title="Refresh"></i>
            </div>
        </div>

        <div class="tableactions">
            <div id="divide">
                <!-- <i class="bi bi-arrow-left-short"></i>
                <i class="bi bi-arrow-right-short" id="iconborder"></i> -->
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
                $filteredRequests = App\Models\VehicleRequest::whereIn('FormStatus', ['Approved', 'Not Approved'])
                    ->whereIn('EventStatus', ['Finished', 'Cancelled', '-'])
                    ->get();
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
                        <a href="{{ route('vehiclelogDetail', $request->VRequestID) }}"><i class="bi bi-person-vcard" id="actions"></i></a>
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
        const modal = document.getElementById('dateRangeModal');
        const downloadLink = document.getElementById('downloadLink');
        const span = document.querySelector('.close');

        downloadLink.onclick = function () {
            modal.style.display = 'block';
        }

        span.onclick = function () {
            modal.style.display = 'none';
        }

        window.onclick = function (event) {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        }

        document.getElementById('dateRangeForm').addEventListener('submit', function (event) {
            event.preventDefault();
            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;

            const url = `/vehiclerequest/view-logs?startDate=${startDate}&endDate=${endDate}`;
            window.open(url, '_blank');
            modal.style.display = 'none';
        });

        let currentPage = 1;
        const itemsPerPage = 5;
        let lastPage = 1;

        function fetchSortedData(order = 'desc', page = currentPage, search = searchQuery) {
            const form = document.getElementById('filterForm');
            const formData = new FormData(form);

            formData.append('order', order);
            formData.append('sort', 'created_at');
            formData.append('page', page);
            formData.append('per_page', itemsPerPage);

            const params = new URLSearchParams(formData).toString();

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

        function updateTable(data, pagination) {
            let tbody = document.querySelector('tbody');
            
            tbody.innerHTML = '';

            if (Array.isArray(data) && data.length > 0) {
                data.forEach(request => {
                    let officeName = request.office ? request.office.OfficeName : 'N/A';
                    let purposeName = request.PurposeOthers || (App\Models\PurposeRequest::find($request->PurposeID))->purpose || 'N/A';
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
                <a href="/vehiclerequest/${request.VRequestID}/log"><i class="bi bi-person-vcard" id="actions"></i></a>
            </td>
        </tr>`;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            } else {
                tbody.innerHTML = '<tr><td colspan="10">No requests found.</td></tr>';
            }

            updatePagination(pagination);
        }

        function updatePagination(pagination) {
            currentPage = pagination.current_page;
            lastPage = pagination.last_page;

            document.getElementById('prev-page').style.visibility = currentPage > 1 ? 'visible' : 'hidden';
            document.getElementById('next-page').style.visibility = currentPage < lastPage ? 'visible' : 'hidden';
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
            fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'), currentPage, searchQuery);
        });

        document.querySelector('.cancelbtn').addEventListener('click', function () {
            document.getElementById('filterForm').reset();
            let searchQuery = '';
            fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'));
        });

        document.querySelector('.form-input').addEventListener('input', function () {
            let searchQuery = this.value;
            fetchSortedData(document.getElementById('sort-date-requested').getAttribute('data-order'), currentPage, searchQuery);
        });

        fetchSortedData();
    });
</script>
