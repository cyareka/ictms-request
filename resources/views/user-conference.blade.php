<!-- resources/views/user-conferenceconference.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Conference Form</title>
    <link rel= "shortcut icon" type="image/png" href="{{('/Logo/logo.png')}}">
</head>

<body>
    <x-user-layout>
        <x-slot name="header">
            <!-- <h1 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Conference Room Form') }}
            </h1> -->
        </x-slot>

        <div class="py-12">
            <!-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8"> -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <x-conference-form/>
                </div>
        </div>
    </x-user-layout>
</body>
</html>
