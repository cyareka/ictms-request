<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .alert-danger {
            color: #721c24;
            background-color: #f8d7da;
            border-color: #f5c6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }

        a, a:hover {
            text-decoration: none;
        }

        body {
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .form-container {
            width: 55em;
            padding: 35px;
            border: 1px solid #ddd;
            border-radius: 15px;
            margin: 5em auto;
            margin-bottom: 3em;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
            margin-top: 0;
        }

        h1 {
            font-size: 30px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .dropdown-row {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .dropdown-button {
            position: relative;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            box-shadow: 0 1px 0 0 #ccc;
            flex: 1;
            margin: 10px 5px;
            text-align: center;
        }

        .dropdown-button.active {
            border: 2px solid #354e7d; /* Change border color when active */
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            flex-wrap: wrap;
        }

        .inline-field {
            display: flex;
            align-items: center;
            width: 48%;
            margin-bottom: 15px;
            position: relative;
        }

        .inline-field label {
            margin-right: 20px;
            flex-shrink: 0;
            width: 150px;
        }

        .inline-field input,
        .inline-field select {
            width: calc(100% - 170px);
            padding: 10px;
            border: 1px solid rgba(60, 54, 51, 0.5);
            border-radius: 15px;
            box-sizing: border-box;
        }

        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
            position: absolute;
            bottom: -45px;
            left: 0;
        }

        input[type="number"] {
            width: 80px;
        }

        .submit-btn {
            background-color: #354e7d;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 20px;
            cursor: pointer;
            font-size: 16px;
            display: inline-block;
            align-items: center;
        }

        .form-footer {
            text-align: center;
            margin-top: 10px;
        }

        .toggle-section {
            display: none;
            width: 100%;
        }

        .phone-prefix {
            padding: 10px;
        }

        @media (max-width: 768px) {
            .form-container {
                width: 90%;
                padding: 50px;
                margin: 2em auto;
                margin-top: 1em;
            }

            .dropdown-button,
            .toggle-button {
                margin: 10px 0;
                display: flex;
                flex-direction: column;
                width: 100%;
            }

            .inline-field {
                width: 100%;
                display: flex;
                flex-direction: column;
                position: relative;
            }

            .inline-field label {
                width: 100%;
                margin-bottom: 5px;
            }

            .inline-field input,
            .inline-field select {
                width: 100%;
                box-sizing: border-box;
                padding: 15px;
            }

            .form-row {
                display: flex;
                flex-direction: column;
                align-items: flex-start;
            }
        }

        .custom-modal-header {
            background-color: #354e7d;
            color: white;
            padding: 14px;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }

        .custom-close-button {
            color: white;
            font-size: 1.5em;
        }

        .modal-body {
            padding: 20px;
            font-size: 16px;
            text-align: center;
        }

        .custom-modal-footer {
            display: flex;
            justify-content: space-around;
            padding: 15px 20px;
            border-bottom-left-radius: 5px;
            border-bottom-right-radius: 5px;
        }

        .custom-cancel-button {
            background-color: #d9534f;
            color: white;
            border-radius: 15px;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        .custom-cancel-button:hover {
            background-color: #c9302c;
        }

        .custom-submit-button {
            background-color: #354e7d;
            color: white;
            border-radius: 15px;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }

        .custom-submit-button:hover {
            background-color: #354e7d;
        }

        .custom-modal-size {
            max-width: 350px;
        }

        .modal-body {
            padding: 15px;
            font-size: 16px;
            text-align: center;
        }

        .custom-modal-footer {
            padding: 10px 20px;
        }

        .error-message {
            margin-left: 173px;
        }

        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="form-container">
    <h1>MANAGEMENT</h1>
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-error" role="alert">
            {{ session('error') }}
        </div>
    @endif
    <div id="app">
        <div class="dropdown-row">
            <button class="dropdown-button" onclick="toggleSection('conference', this)">CONFERENCE ROOM</button>
            <button class="dropdown-button" onclick="toggleSection('focalP', this)">FOCAL PERSON</button>
            <button class="dropdown-button" onclick="toggleSection('porpose', this)">PURPOSE</button>
            <button class="dropdown-button" onclick="toggleSection('addVehi', this)">DRIVER</button>
            <button class="dropdown-button" onclick="toggleSection('vehicle', this)">VEHICLE</button>
            <button class="dropdown-button" onclick="toggleSection('superp', this)">SUPERIOR</button>
            <!-- <button class="dropdown-button" onclick="toggleSection('employee', this)">EMPLOYEE</button> -->
        </div>

        <div id="addVehi" class="toggle-section">
            <form class="row-dispatch" method="POST" action="{{ route('driver.store') }}" id="addVehiForm">
                @csrf
                <div class="form-row">
                    <div class="inline-field">
                        <label for="DriverName">Driver Name</label>
                        <input type="text" id="DriverName" name="DriverName" placeholder="Enter Driver Name" required>
                    </div>
                    <div class="inline-field">
                        <label for="DriverEmail">Driver Email</label>
                        <input type="email" id="DriverEmail" name="DriverEmail" placeholder="Enter Driver Email"
                               required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="inline-field">
                        <label for="ContactNo">Contact No.</label>
                        <span class="phone-prefix">+63</span>
                        <input type="tel" id="ContactNo" name="ContactNo" placeholder="Enter Contact No." required
                               maxlength="10">
                    </div>
                </div>
                <div class="form-footer">
                    <button class="submit-btn" type="button" onclick="setCurrentForm('addVehiForm')" data-toggle="modal"
                            data-target="#confirmationModal">Submit
                    </button>
                </div>
            </form>
            <div class="container mt-5">
                @php
                    $driver = App\Models\Driver::all();
                @endphp
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Driver Name</th>
                        <th scope="col">Driver Email</th>
                        <th scope="col">Contact No.</th>
                        <th scope="col">Status</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($driver->isEmpty())
                        <tr>
                            <td colspan="6">Data is not available.</td>
                        </tr>
                    @else
                        @foreach($driver as $item)
                            <tr>
                                <th scope="row">{{ $item->DriverID }}</th>
                                <td>{{ $item->DriverName }}</td>
                                <td>{{ $item->DriverEmail }}</td>
                                <td>{{ $item->ContactNo }}</td>
                                <td>{{ $item->status ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <form action="{{ route('driver.toggleStatus', $item->DriverID) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $item->status ? 'btn-danger' : 'btn-success' }}">
                                            {{ $item->status ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>

        </div>

        <div id="vehicle" class="toggle-section">
            <form class="row-dispatch" method="POST" action="{{ route('vehicle.store') }}" id="vehicleForm">
                @csrf
                <div class="form-row">
                    <div class="inline-field">
                        <label for="VehicleType">Vehicle Type</label>
                        <input type="text" id="VehicleType" name="VehicleType" placeholder="Enter Vehicle Type" required
                               maxlength="50">
                    </div>
                    <div class="inline-field">
                        <label for="PlateNo">Plate No.</label>
                        <input type="text" id="PlateNo" name="PlateNo" required maxlength="15"
                               placeholder="Enter Plate No.">
                    </div>
                </div>
                <div class="form-row">
                    <div class="inline-field">
                        <label for="Capacity">Capacity</label>
                        <input type="number" id="Capacity" name="Capacity" min="1" value="1" required>
                    </div>
                </div>
                <div class="form-footer">
                    <button class="submit-btn" type="button" onclick="setCurrentForm('vehicleForm')" data-toggle="modal"
                            data-target="#confirmationModal">Submit
                    </button>
                </div>
            </form>
            <div class="container mt-5">
                @php
                    $vehicle = App\Models\Vehicle::all();
                @endphp
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Conference Room</th>
                        <th scope="col">Location</th>
                        <th scope="col">Capacity</th>
                        <th scope="col">Status</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($vehicle->isEmpty())
                        <tr>
                            <td colspan="6">Data is not available.</td>
                        </tr>
                    @else
                        @foreach($vehicle as $item)
                            <tr>
                                <th scope="row">{{ $item->VehicleID }}</th>
                                <td>{{ $item->VehicleType }}</td>
                                <td>{{ $item->PlateNo }}</td>
                                <td>{{ $item->Capacity }}</td>
                                <td>{{ $item->status ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <form action="{{ route('vehicle.toggleStatus', $item->VehicleID) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $item->status ? 'btn-danger' : 'btn-success' }}">
                                            {{ $item->status ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div id="conference" class="toggle-section">
            <form class="row-dispatch" method="POST" action="{{ route('conferences.store') }}" id="conferenceForm">
                @csrf
                <div class="form-row">
                    <div class="inline-field">
                        <label for="CRoomName">Name</label>
                        <input type="text" id="CRoomName" name="CRoomName" placeholder="Enter Name" required>
                    </div>
                    <div class="inline-field">
                        <label for="Location">Location</label>
                        <input type="text" id="Location" name="Location" placeholder="Enter Location" required>
                    </div>
                </div>
                <div class="form-row">
                    <div class="inline-field">
                        <label for="Capacity">Capacity</label>
                        <input type="number" id="Capacity" name="Capacity" min="1" value="1" required>
                    </div>
                </div>
                <div class="form-footer">
                    <button class="submit-btn" type="button" onclick="setCurrentForm('conferenceForm')"
                            data-toggle="modal" data-target="#confirmationModal">Submit
                    </button>
                </div>
            </form>
            <div class="container mt-5">
                @php
                    $conferenceRoom = App\Models\ConferenceRoom::all();
                @endphp
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Conference Room</th>
                        <th scope="col">Location</th>
                        <th scope="col">Capacity</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($conferenceRoom->isEmpty())
                        <tr>
                            <td colspan="4">Data is not available.</td>
                        </tr>
                    @else
                        @foreach($conferenceRoom as $item)
                            <tr>
                                <th scope="row">{{ $item->CRoomID }}</th>
                                <td>{{ $item->CRoomName }}</td>
                                <td>{{ $item->Location }}</td>
                                <td>{{ $item->Capacity }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div id="porpose" class="toggle-section">
            <form class="row-dispatch" method="POST" action="{{ route('porpose.store') }}" id="RequestPform">
                @csrf
                <div class="form-row">
                    <div class="inline-field">
                        <label for="request_p">Request Form</label>
                        <select id="request_p" name="request_p" required>
                            <option disabled selected>Select Form</option>
                            <option value="Vehicle">Vehicle</option>
                            <option value="Conference Room">Conference Room</option>
                        </select>
                    </div>
                    <div class="inline-field">
                        <label for="purpose">Purpose</label>
                        <input type="text" id="purpose" name="purpose" placeholder="Enter Purpose" required>
                    </div>
                </div>

                <div class="form-footer">
                    <button class="submit-btn" type="button" onclick="setCurrentForm('RequestPform')"
                            data-toggle="modal" data-target="#confirmationModal">Submit
                    </button>
                </div>
            </form>
            <br>
            <br>
            <!-- Added Table with Bootstrap classes -->
            <div class="mb-3">
                @php
                    $purposeRequests = App\Models\PurposeRequest::all();
                @endphp

                <table id="dataTable" class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Request Form</th>
                        <th scope="col">Purpose</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($purposeRequests->isEmpty())
                        <tr>
                            <td colspan="3">Data is not available.</td>
                        </tr>
                    @else
                        @foreach($purposeRequests as $request)
                            <tr>
                                <th scope="row">{{ $request->PurposeID }}</th>
                                <td>{{ $request->request_p }}</td>
                                <td>{{ $request->purpose }}</td>
{{--                            <td>--}}
{{--                                <form action="{{ route('purpose.requests.destroy', $request->PurposeID) }}" method="POST" style="display:inline;">--}}
{{--                                    @csrf--}}
{{--                                    @method('DELETE')--}}
{{--                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>--}}
{{--                                </form>--}}
{{--                                <a href="#" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#editModal" data-id="{{ $request->PurposeID }}" data-request_p="{{ $request->request_p }}" data-purpose="{{ $request->purpose }}">Edit</a>--}}
{{--                            </td>--}}
                        </tr>
                        @endforeach
                    @endif
                    </tbody>
{{--                    <script>--}}
{{--                        $('#editModal').on('show.bs.modal', function (event) {--}}
{{--                            var button = $(event.relatedTarget);--}}
{{--                            var id = button.data('id');--}}
{{--                            var request_p = button.data('request_p');--}}
{{--                            var purpose = button.data('purpose');--}}

{{--                            var modal = $(this);--}}
{{--                            modal.find('.modal-body #editRequestForm').val(request_p);--}}
{{--                            modal.find('.modal-body #editPurpose').val(purpose);--}}
{{--                            modal.find('#editForm').attr('action', '/purpose-requests/' + id);--}}
{{--                        });--}}
{{--                    </script>--}}
                </table>
                <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <input type="hidden" id="editPurposeID" name="PurposeID">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Purpose Request</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form id="editForm" method="POST" action="">
                                @csrf
                                @method('POST')
                                <div class="modal-body">
                                    <input type="hidden" id="editPurposeID" name="PurposeID">
                                    <div class="form-group">
                                        <label for="editRequestForm">Request Form</label>
                                        <select id="editRequestForm" name="request_p" class="form-control" required>
                                            <option value="Vehicle">Vehicle</option>
                                            <option value="Conference Room">Conference Room</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="editPurpose">Purpose</label>
                                        <input type="text" id="editPurpose" name="purpose" class="form-control" required>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- <div id="employee" class="toggle-section">
            <form class="row-dispatch" method="POST" action="{{ route('employee.store') }}" id="employeeForm">
                @csrf
        <div class="form-row">
            <div class="inline-field">
                <label for="EmployeeName">Name</label>
                <input type="text" id="EmployeeName" name="EmployeeName" placeholder="Enter Name" required>
            </div>
            <div class="inline-field">
                <label for="EmployeeEmail">Email</label>
                <input type="text" id="EmployeeEmail" name="EmployeeEmail" placeholder="Enter Email" required oninput="validateEmail()">
                <div id="emailError" class="error-message"></div>
            </div>
        </div>
        <div class="form-row">
            <div class="inline-field">
                <label for="officeName">Assigned Office</label>
                <select id="officeName" name="officeName" required>
                    <option disabled selected>Select Office</option>
@foreach(App\Models\Office::all() as $office)
            <option value="{{ $office->OfficeID }}">{{ $office->OfficeName }}</option>

        @endforeach
        </select>
    </div>
</div>
<div class="form-footer">
    <button class="submit-btn" type="button" onclick="setCurrentForm('employeeForm')" data-toggle="modal" data-target="#confirmationModal">Submit</button>
</div>
</form>
</div> -->

        <div id="focalP" class="toggle-section">
            <form class="row-dispatch" method="POST" action="{{ route('focalP.store') }}" id="focalPForm">
                @csrf
                <div class="form-row">
                    <div class="inline-field">
                        <label for="FPName">Name</label>
                        <input type="text" id="FPName" name="FPName" placeholder="Enter Name" required>
                    </div>
                    <div class="inline-field">
                        <label for="officeName">Assigned Office</label>
                        <select id="officeName" name="officeName" required>
                            <option disabled selected>Select Office</option>
                            @foreach(App\Models\Office::all() as $office)
                                <option value="{{ $office->OfficeID }}">{{ $office->OfficeName }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-footer">
                    <button class="submit-btn" type="button" onclick="setCurrentForm('focalPForm')" data-toggle="modal"
                            data-target="#confirmationModal">Submit
                    </button>
                </div>
            </form>

            <div class="container mt-5">
                @php
                    $focalPerson = App\Models\FocalPerson::all();
                @endphp
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Office</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($focalPerson->isEmpty())
                        <tr>
                            <td colspan="3">Data is not available.</td>
                        </tr>
                    @else
                        @foreach($focalPerson as $item)
                            <tr>
                                <th scope="row">{{ $item->FocalPID }}</th>
                                <td>{{ $item->FPName }}</td>
                                <td>{{ $item->office->OfficeName }}</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>

        <div id="superp" class="toggle-section">
            <form class="row-dispatch" method="POST" action="{{ route('superp.store') }}" id="superiorForm">
                @csrf
                <div class="form-row">
                    <div class="inline-field">
                        <label for="SName">Name</label>
                        <input type="text" id="SName" name="SName" placeholder="Enter Name" required>
                    </div>
                    <div class="inline-field">
                        <label for="Designation">Designation</label>
                        <input type="text" id="Designation" name="Designation" placeholder="Enter Designation" required>
                    </div>
                </div>
                <div class="form-footer">
                    <button class="submit-btn" type="button" onclick="setCurrentForm('superiorForm')"
                            data-toggle="modal" data-target="#confirmationModal">Submit
                    </button>
                </div>
            </form>

            <div class="container mt-5">
                @php
                    $super = App\Models\Superior::all();
                @endphp
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Designation</th>
                        <th scope="col">Status</th>   <!--1 is active , 0 is inactive--!>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($super->isEmpty())
                        <tr>
                            <td colspan="5">Data is not available.</td>
                        </tr>
                    @else
                        @foreach($super as $item)
                            <tr>
                                <th scope="row">{{ $item->SuperiorID }}</th>
                                <td>{{ $item->SName }}</td>
                                <td>{{ $item->Designation }}</td>
                                <td>{{ $item->status == 1 ? 'Active' : 'Inactive' }}</td>
                                <td>
                                    <form action="{{ route('superior.toggleStatus', $item->SuperiorID) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $item->status == 1 ? 'btn-danger' : 'btn-success' }}">
                                            {{ $item->status == 1 ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1" role="dialog" aria-labelledby="confirmationModalTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered custom-modal-size" role="document">
        <div class="modal-content">
            <div class="modal-header custom-modal-header">
                <h5 class="modal-title" id="confirmationModalTitle">Confirm Submission</h5>
                <button type="button" class="close custom-close-button" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to submit this form?
            </div>
            <div class="modal-footer custom-modal-footer">
                <button type="button" class="btn custom-cancel-button" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn custom-submit-button" onclick="submitForm()">Submit</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    let currentForm = null;

    function toggleSection(sectionId, button) {
        const section = document.getElementById(sectionId);
        const isVisible = section.style.display === 'block';
        const allSections = document.querySelectorAll('.toggle-section');
        const allButtons = document.querySelectorAll('.dropdown-button');

        allSections.forEach(sec => sec.style.display = 'none');
        allButtons.forEach(btn => btn.classList.remove('active'));

        if (!isVisible) {
            section.style.display = 'block';
            button.classList.add('active');
        }
    }

    function setCurrentForm(formId) {
        currentForm = document.getElementById(formId);
    }

    function validateEmail() {
        const emailInput = document.getElementById('EmployeeEmail');
        const emailValue = emailInput.value;
        const emailError = document.getElementById('emailError');
        const domain = emailValue.split('@')[1];

        if (domain !== 'dswd.gov.ph') {
            emailInput.setCustomValidity('Please use a dswd.gov.ph email address.');
            emailError.textContent = 'Only dswd.gov.ph email addresses are allowed.';
        } else {
            emailInput.setCustomValidity('');
            emailError.textContent = '';
        }
    }

    function submitForm() {
        if (currentForm) {
            if (currentForm.id === 'employeeForm') {
                validateEmail();
                const emailInput = document.getElementById('EmployeeEmail');
                if (!emailInput.checkValidity()) {
                    emailInput.reportValidity();
                    return;
                }
            }

            if (validateForm(currentForm)) {
                currentForm.submit();
            } else {
                alert('Please fill in all required fields.');
            }
        }
    }

    function displayAlert(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-danger';
        alertDiv.textContent = message;
        document.querySelector('.form-container').prepend(alertDiv);

        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }

    function validateForm(form) {
        let isValid = true;
        const inputs = form.querySelectorAll('input[required], select[required], textarea[required]');
        inputs.forEach(input => {
            if (!input.value.trim()) {
                isValid = false;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });
        return isValid;
    }
</script>

</body>
</html>
