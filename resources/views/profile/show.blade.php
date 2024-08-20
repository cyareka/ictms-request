<x-app-layout>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
       
        body {
            font-family: 'Poppins', sans-serif;
            font-size: 18px;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .form-container {
            width: 70em;
            padding: 35px;
            border: 1px solid #ddd;
            border-radius: 15px;
            margin: 5em auto;
            margin-bottom: 3em;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: #f9f9f9;
            margin-top: 30px;
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
       
        .toggle-section {
            display: none;
            width: 100%;
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
        }
    </style>
</head>
<body>
<div class="form-container">
    <h1>PROFILE</h1>
    <div id="app">
        <div class="dropdown-row">
            <button class="dropdown-button" onclick="toggleSection('employee', this)">Browser Sessions</button>
            <button class="dropdown-button" onclick="toggleSection('newad', this)">New Admin</button>
            <button class="dropdown-button" onclick="toggleSection('addVehi', this)">Profile Information</button>
            <button class="dropdown-button" onclick="toggleSection('conference', this)">Two Factor Authentication</button>
            <button class="dropdown-button" onclick="toggleSection('vehicle', this)">Update Password</button>
        </div>

        <div id="addVehi" class="toggle-section">
        @if (Laravel\Fortify\Features::canUpdateProfileInformation())
                @livewire('profile.update-profile-information-form')

                <x-section-border />
            @endif
        </div>

        <div id="vehicle" class="toggle-section">
        @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.update-password-form')
                </div>

                <x-section-border />
            @endif
        </div>

        <div id="conference" class="toggle-section">
        @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
                <div class="mt-10 sm:mt-0">
                    @livewire('profile.two-factor-authentication-form')
                </div>

                <x-section-border />
            @endif
        </div>

        <div id="employee" class="toggle-section">
        <div class="mt-10 sm:mt-0">
                @livewire('profile.logout-other-browser-sessions-form')
            </div>

                <x-section-border />
        </div>
    </div>
    <div id="newad" class="toggle-section">
    <div class="mt-10 sm:mt-0">
                    @livewire('profile.add-admin')
                </div>

                <x-section-border />
        </div>
    </div>
</div>


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
</script>
</body>

</x-app-layout>
