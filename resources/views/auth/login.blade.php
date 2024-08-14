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
                    <div class="font-medium text-red-600">{{ __('Whoops! Something went wrong.') }}</div>
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

            <div class="mt-4">
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            </div>

            <!-- Show Password Checkbox with Custom Styling -->
            <div class="mt-2 flex items-center">
                <input id="show_password" type="checkbox" class="hidden" onclick="togglePassword()" />
                <label for="show_password" class="flex items-center cursor-pointer">
                    <span class="w-4 h-4 inline-block border border-gray-400 rounded-sm flex-shrink-0 mr-2"></span>
                    <span class="text-sm text-gray-600">{{ __('Show Password') }}</span>
                </label>
            </div>

            <div class="block mt-4">
                <label for="remember_me" class="flex items-center">
                    <x-checkbox id="remember_me" name="remember" />
                    <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>

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
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password';
        }
    }
</script>

<style>
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
