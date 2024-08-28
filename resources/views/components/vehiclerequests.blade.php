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
                        <a href="{{ route('VehicledetailEdit', $request->VRequestID) }}"><i class="bi bi-pencil"
                                                                                            id="actions"></i></a>
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

            fetch(`/fetchSortedVRequests?${params}`)
                .then(response => response.json())
                .then(data => {
                    updateTable(data.data);
                    updatePagination(data.pagination);
                    currentPage = data.pagination.current_page;
                    lastPage = data.pagination.last_page;
                })
                .catch(error => {
                    console.error('There was a problem with the fetch operation:', error);
                });
            function updatePagination(pagination) {
                currentPage = pagination.current_page;
                lastPage = pagination.last_page;
                
        }
</script>
