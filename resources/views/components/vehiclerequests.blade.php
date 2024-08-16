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
                    <form id="filterForm" method="GET" action="{{ route('fetchSortedRequests') }}">
                        <div class="dropdown-content">
                            <p id="filterlabel">Filter By</p>
                            <hr>
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
                <th scope="col">Date Requested</th>
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
            <tr>
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
{{--                        <td>{{ $request->vehicle->Availability }}</td>--}}
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
