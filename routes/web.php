<?php
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

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
    Route::get('/ConferencedetailEdit', function () {
        return view('ConferencedetailEdit');
    })->name('ConferencedetailEdit');
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
});

Route::get('/user-conference', function () {
    return view('user-conference');
})->name('user-conference');

Route::get('/user-vehicle', function () {
    return view('user-vehicle');
})->name('user-vehicle');