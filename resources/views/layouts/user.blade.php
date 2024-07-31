<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
            <meta charset="utf-8" content="">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="csrf-token" content="{{ csrf_token() }}">

            <title>{{ config('app.name', 'Laravel') }}</title>

            <!-- Fonts -->
            <link rel="preconnect" href="https://fonts.bunny.net">
            <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

            <!-- Scripts -->
            @vite(['resources/css/app.css', 'resources/js/app.js'])

            <!-- Styles -->
            <style>
                .header {
                    position: fixed;
                    top: 0;
                    right: 0;
                    width: 100%;
                    background-color: #ffffff;
                    padding: 10px;
                    text-align: right;
                    border-bottom-left-radius: 15px;
                    border-bottom-right-radius: 15px;
                }
                .nav {
                    display: flex;
                    align-items: center;
                }
                .logo {
                    margin-right: auto;
                    margin-left: 20px;
                    margin-top: 8px;
                }
                .dropdown {
                    position: relative;
                    display: inline-block;
                    margin-right: 10px;
                }
                .dropdown-content {
                    display: none;
                    position: absolute;
                    background-color: #ffff;
                    min-width: 160px;
                    box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
                    z-index: 1;
                }
                .dropdown-content a {
                    color: black;
                    padding: 12px 16px;
                    text-decoration: none;
                    display: block;
                }
                .dropdown-content a:hover {
                    background-color: #f1f1f1;
                }
                .dropdown:hover .dropdown-content {
                    display: block;
                }
                .dropdown:hover .dropbtn {
                    background-color: transparent;
                }
                .dropbtn {
                    background-color:transparent;
                    color: #747487; 
                    padding: 10px;
                    font-size: 16px;
                    border: none;
                    cursor: pointer;
                    border-radius: 25px;
                }

                .dropbtn i {
                    color: #fffff;
                }
                .dropbtn:hover {
                    background-color: transparent;
                }
                /* Add some space between dropdown buttons */
                .dropdown + .dropdown {
                    margin-left: 10px;
                }
                .dropbtn i.fa-caret-down {
                    transform: scale(0.5, 1);
                }
            </style>
            <!-- Import Font Awesome for icons -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css" />
    </head>
            <body style="background-image: linear-gradient(to bottom, #02225d,#677a9e)">
                <div class="header">
                    <div class="nav">
                        <div class="logo">
                        <a href="/">
                            <img width="200" src="{{asset('/logo/logo.jpg')}}">
                            </a>

                        </div>
                        <div class="dropdown">
                        <button class="dropbtn"><i class="fa-solid fa-calendar-day"></i> Calendar View <i class="fa-solid fa-chevron-down"></i></button>
                            <div class="dropdown-content">
                                <a href="#">Conference Room</a>
                                <a href="#">Vehicle</a>
                            </div>
                        </div>
                        <div class="dropdown">
                        <button class="dropbtn"><i class="fa-solid fa-file-alt"></i> Request Forms <i class="fa-solid fa-chevron-down"></i></button>                            <div class="dropdown-content">
                                <a href="#">Conference Room</a>
                                <a href="#">Vehicle</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="font-sans text-black-900 antialiased">
                    {{ $slot }}
                </div>

                @livewireScripts
            </body>
</html>