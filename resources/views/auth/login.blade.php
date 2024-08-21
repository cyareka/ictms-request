<x-guest-layout>
    <x-authentication-card>
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Add the logo here -->
            <div class="text-center mb-4">
                <x-authentication-card-logo />
            </div>

            <!-- Display error messages -->
            @if ($errors->any())
                <div class="mb-4">
                    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            </div>

            <div class="mt-4 relative">
                <x-label for="password" value="{{ __('Password') }}" />
                <!-- Set initial type to "password" to hide the password -->
                <x-input id="password" class="block mt-1 w-full pr-10" type="password" name="password" required autocomplete="current-password" />

                <!-- Initial setup: slashed eye icon visible, open eye icon hidden -->
                <button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3 mt-2" onclick="togglePassword()" aria-label="Toggle password visibility">
                    <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500 hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg id="eye-slash-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>

            <!-- Remember Me Checkbox -->
            <!-- <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div> -->

            <div class="flex items-center justify-end mt-4">
                <x-button class="ms-4">
                    {{ __('Log in') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

<script>
    function togglePassword() {
        const passwordField = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        const eyeSlashIcon = document.getElementById('eye-slash-icon');

        // Toggle password visibility
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.remove('hidden');      // Show the open eye
            eyeSlashIcon.classList.add('hidden');    // Hide the slashed eye
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.add('hidden');        // Hide the open eye
            eyeSlashIcon.classList.remove('hidden'); // Show the slashed eye
        }
    }
</script>

<style>
    /* Style for password input container */
    .relative {
        position: relative;
    }

    .pr-10 {
        padding-right: 2.5rem;
    }

    /* Positioning the eye icon inside the password field with margin-top */
    .relative button {
        top: 50%;
        transform: translateY(-50%);
        right: 1rem;
        margin-top: 10px; /* Adjusted margin-top */
    }

    /* Style for custom checkbox */
    input[type="checkbox"].hidden {
        display: none;
    }

    input[type="checkbox"].hidden + label span:first-child {
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 1px solid #d1d5db;
        border-radius: 3px;
        position: relative;
    }

    input[type="checkbox"].hidden:checked + label span:first-child::before {
        content: "\2713";
        position: absolute;
        top: 0;
        left: 3px;
        color: #1f2937;
        font-size: 12px;
    }

    label span.text-sm {
        color: #4b5563;
        margin-left: 10px;
    }

    label span.text-sm:hover {
        color: #111827;
    }
</style>
