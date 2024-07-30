<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
   <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <title>{{ config('app.name', 'Laravel') }}</title>
      <!-- Fonts -->
      <link rel="preconnect" href="https://fonts.bunny.net">
      <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
      <!-- Bootstrap CSS (for table styles) -->
      <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
      <!-- Font Awesome Icons -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
      <!-- Scripts -->
      @vite(['resources/css/app.css', 'resources/js/app.js'])
      <!-- Styles -->
      <style>
         a:hover {
         text-decoration: none;
         }
         .requests {
         padding: 0px 50px;
         position: relative;
         z-index: 2; /* Ensure this content is above the footer */
         }
         .filter {
         display: flex;
         justify-content: space-between;
         }
         .form {
         position: relative;
         width: 250px;
         }
         .form .fa-search {
         position: absolute;
         top: 10px;
         left: 20px;
         color: #747487;
         }
         .form span {
         position: absolute;
         right: 17px;
         top: 13px;
         padding: 2px;
         border-left: 1px solid #d1d5db;
         }
         .left-pan {
         padding-left: 7px;
         }
         .left-pan i {
         padding-left: 10px;
         }
         .form-input {
         height: 35px;
         text-indent: 33px;
         border-radius: 5px;
         background-color: white;
         border: none;
         color: #6E6E71;
         }
         .form-input:focus {
         box-shadow: none;
         border: none;
         }
         .tableactions i {
         display: inline-block;
         margin-right: 10px;
         font-size: 20px;
         z-index: 3;
         }
         #iconborder {
         margin-right: 30px;
         border-right: 1px solid #d1d5db;
         padding-right: 20px;
         }
         @media (max-width: 768px) {
         .tableactions i {
         margin-top: 10px;
         font-size: 15px;
         margin-right: 5px;
         }
         }
         .tabview {
         font-family: Arial, sans-serif;
         border-collapse: collapse;
         width: 100%;
         font-size: 15px;
         overflow-x: auto;
         border-radius: 10px;
         background-color: #FFFFFF;
         box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.15);
         position: relative;
         z-index: 2; /* Ensure the table is on top of the footer */
         }
         .tabview table {
         width: 100%;
         padding-top: 10px;
         border-spacing: 0;
         }
         .tabview th, .tabview td {
         padding: 15px;
         text-align: left;
         border-bottom: 1px solid #E5E5E5;
         }
         .tabview th {
         font-weight: bold;
         color: #333;
         }
         .tabview td {
         color: #666;
         }
         .tabview tr:hover {
         background-color: #F7F7F7;
         }
         .approved {
         padding: 4px 13px;
         background-color: #CBDCF9;
         color: #103680;
         font-size: 12px;
         font-weight: bold;
         border-radius: 5px;
         }
         .disapproved {
         padding: 4px 13px;
         background-color: #ff6961;
         color: white;
         font-size: 12px;
         font-weight: bold;
         border-radius: 5px;
         }
         .pending {
         padding: 4px 13px;
         background-color: #FFF3DD;
         color: #aa8345;
         font-size: 12px;
         font-weight: bold;
         border-radius: 5px;
         }
         #actions {
         margin: 2px 8px;
         cursor: pointer;
         color: black;
         }
         .end {
         position: fixed;
         bottom: 0;
         left: 0;
         width: 100%;
         height: 100px; /* Adjust the height as needed */
         background: #354e7d;
         z-index: 1; /* Ensure the footer is below the table */
         }
         .dropbtn {
         color:black;
         font-size: 16px;
         border: none;
         cursor: pointer;
         }
         .dropdown {
         position: relative;
         display: inline-block;
         z-index: 1000; /* Ensure dropdown is on top of the table */
         }
         .dropdown-content {
         display: none;
         position: absolute;
         right: 0;
         background-color: #f9f9f9;
         min-width: 260px;
         box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
         z-index: 1000; /* Ensure dropdown content is on top */
         }
         .dropdown-content p{
         padding-top:10px;
         padding-left:10px;
         color:black;
         font-weight:bold;
         font-size:18px;
         }
         #margincheck{
         margin-bottom:10px;
         }
         #filterlabel{
         margin-bottom:10px;
         }
         .dropdown-content a {
         color: black;
         padding: 10px 16px;
         text-decoration: none;
         display: block;
         }
         .dropdown-content a:hover {
         background-color: #f1f1f1;
         }
         .dropdown:hover .dropdown-content {
         display: block;
         }
         .buttons{
         display:flex;
         justify-content:right;
         margin-top:20px;
         margin-bottom:10px;
         }
         .applybtn , .cancelbtn {
         background-color: #354e7d;
         color:white;
         border-radius:20px;
         padding:5px;
         margin: 0 10px 10px 0;
         width: 90px;
         }
         @media screen and (max-width: 484px) {
         .form {
         width:160px;
         }
         }
      </style>
   </head>
   <body class="font-sans antialiased">
      <x-banner />
      <div class="min-h-screen bg-gray-100">
         @livewire('navigation-menu')
         <!-- Page Heading -->
         @if (isset($header))
         <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
               {{ $header }}
            </div>
         </header>
         @endif
         <!-- Page Content -->
         <main>
            {{ $slot }}
         </main>
      </div>
      @stack('modals')
      @livewireScripts
      <script src="popper/popper.min.js"></script>
      <script src="bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
   </body>
</html>