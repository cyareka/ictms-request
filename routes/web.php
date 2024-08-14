<?php
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\NVehicleController;
use App\Http\Controllers\NDriverController;
use App\Http\Controllers\NConferenceRController;
use App\Http\Controllers\EmployeeController;
Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    // GENERAL ROUTES
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/CalendarView', function () {
        return view('CalendarView');
    })->name('CalendarView');
    Route::get('/Logs', function () {
        return view('Logs');
    })->name('Logs');
    Route::get('/management', function () {
        return view('management');
    })->name('Management');
    Route::post('/register', [AdminController::class, 'register'])->name('register');

    // CONFERENCE ROUTES
    // Conference Statistics
    Route::get('/ConferenceStatistics', function () {
        return view('ConferenceStatistics');
    })->name('ConferenceStatistics');

    // Conference Calendar View

    // Conference Table View
    Route::get('/conference-requests',
        [ConferenceController::class, 'fetchSortedRequests'])->name('conference.requests');

    // Conference Edit
    Route::post('/conference-room/update',
        [ConferenceController::class, 'updateCForm']);
    Route::get('/conferencerequest/{CRequestID}/edit',
        [ConferenceController::class, 'getRequestData'])->name('ConferencedetailEdit');
    Route::get('/fetchSortedRequests',
        [ConferenceController::class, 'fetchSortedRequests'])->name('fetchSortedRequests');

    // CONFERENCE LOG ROUTES
    Route::get('/conferencerequest/{CRequestID}/log',
        [ConferenceController::class, 'getLogData'])->name('ConferencelogDetail');
    Route::get('/ConferencelogDetail', function () {
        return view('ConferencelogDetail');
    })->name('ConferencelogDetail');

    // VEHICLE ROUTES
    // Vehicle Statistics
    Route::get('/VehicleStatistics', function () {
        return view('VehicleStatistics');
    })->name('VehicleStatistics');

    // Vehicle Calendar View
    Route::get('/VehiclecalendarView', function () {
        return view('VehiclecalendarView');
    })->name('VehiclecalendarView');

    // Vehicle Table View
    Route::get('/VehicleTabular', function () {
        return view('VehicleTabular');
    })->name('VehicleTabular');

    // Vehicle Edit
    Route::get('/VehicledetailEdit', function () {
        return view('VehicledetailEdit');
    })->name('VehicledetailEdit');

    // VEHICLE LOG ROUTES
    Route::get('/VehicleLogs', function () {
        return view('VehicleLogs');
    })->name('VehicleLogs');

    Route::get('/VehicleDownload', function () {
        return view('VehicleDownload');
    })->name('VehicleDownload');

    Route::get('/VehiclelogDetail', function () {
        return view('VehiclelogDetail');
    })->name('VehiclelogDetail');


    // Management Routes
    Route::post('/add-driver',
        [NDriverController::class, 'store'])->name('driver.store');
    Route::post('/add-vehicle',
        [NVehicleController::class, 'store'])->name('vehicle.store');
    Route::post('/conferences',
        [NConferenceRController::class, 'store'])->name('conferences.store');
    Route::post('/employee',
        [EmployeeController::class, 'store'])->name('employee.store');

});

// USER ROUTES
// General
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Conference
Route::get('/user-conference', function () {
    return view('user-conference');
})->name('user-conference');
Route::post('/conference-room/request',
    [ConferenceController::class, 'submitCForm']);


Route::get('/UserconCalendar', function () {
    return view('UserconCalendar');
})->name('UserconCalendar');

// Vehicle
Route::get('/user-vehicle', function () {
    return view('user-vehicle');
})->name('user-vehicle');
Route::post('/vehicle-request',
    [VehicleController::class, 'submitVForm']);
Route::get('/UservehiCalendar', function () {
    return view('UservehiCalendar');
})->name('UservehiCalendar');

