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
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/CalendarView', function () {
        return view('CalendarView');
    })->name('CalendarView');

    Route::get('/Logs', function () {
        return view('Logs');
    })->name('Logs');

    Route::get('/Statistics', function () {
        return view('Statistics');
    })->name('Statistics');

    Route::get('/VehicleTabular', function () {
        return view('VehicleTabular');
    })->name('VehicleTabular');

    Route::get('/VehicleLogs', function () {
        return view('VehicleLogs');
    })->name('VehicleLogs');

    Route::get('/VehiclecalendarView', function () {
        return view('VehiclecalendarView');
    })->name('VehiclecalendarView');

    Route::get('/VehicleLogs', function () {
        return view('VehicleLogs');
    })->name('VehicleLogs');

    Route::get('/VehicledetailEdit', function () {
        return view('VehicledetailEdit');
    })->name('VehicledetailEdit');

    Route::get('/VehicleDownload', function () {
        return view('VehicleDownload');
    })->name('VehicleDownload');

    Route::get('/ConferencelogDetail', function () {
        return view('ConferencelogDetail');
    })->name('ConferencelogDetail');

    Route::get('/VehiclelogDetail', function () {
        return view('VehiclelogDetail');
    })->name('VehiclelogDetail');

    Route::get('/management', function () {
        return view('management');
    })->name('Management');
});

Route::get('/user-conference', function () {
    return view('user-conference');
})->name('user-conference');

Route::get('/user-vehicle', function () {
    return view('user-vehicle');
})->name('user-vehicle');

Route::get('/UserconCalendar', function () {
    return view('UserconCalendar');
})->name('UserconCalendar');

Route::get('/UservehiCalendar', function () {
    return view('UservehiCalendar');
})->name('UservehiCalendar');

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// user form submission
Route::post('/conference-room/request', [ConferenceController::class, 'submitCForm']);

Route::post('/vehicle-request', [VehicleController::class, 'submitVForm']);

Route::get('/conference-requests', [ConferenceController::class, 'showRequests'])->name('conference.requests');

Route::post('/register', [AdminController::class, 'register'])->name('register');

Route::get('/conferencerequest/{CRequestID}/edit', [ConferenceController::class, 'getRequestData'])->name('ConferencedetailEdit');

Route::post('/conference-room/update',
    [ConferenceController::class, 'updateCForm']);

// adding new driver, vehicle, conference, and employee.
Route::post('/add-driver', [NDriverController::class, 'store'])->name('driver.store');
Route::post('/add-vehicle', [NVehicleController::class, 'store'])->name('vehicle.store');
Route::post('/conferences', [NConferenceRController::class, 'store'])->name('conferences.store');
Route::post('/employee', [EmployeeController::class, 'store'])->name('employee.store');

