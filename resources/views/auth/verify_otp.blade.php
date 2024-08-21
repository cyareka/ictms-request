<x-guest-layout>
    <x-authentication-card>
        <form method="POST" onsubmit="submitForm(event)">
            @csrf

            <!-- Add the logo here -->
            <div class="text-center mb-4">
                <x-authentication-card-logo />
            </div>

            <!-- Description -->
            <div class="mb-4 text-sm text-gray-600">
                {{ __('Please enter the OTP sent to your registered email to verify your account.') }}
            </div>

            <!-- Display error messages -->
            <div id="error" class="mb-4 text-sm text-red-600"></div>

            <div>
                <x-label for="otp" value="{{ __('Enter OTP') }}" />
                <x-input id="otp" class="block mt-1 w-full" type="text" name="otp" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4 space-x-2">
            <x-button id="resendButton" type="button" onclick="resendOtp()">
                    {{ __('Resend OTP') }}
                </x-button>
                
                <x-button class="ms-4">
                    {{ __('Verify') }}
                </x-button>
            </div>
        </form>

        <div id="message" class="mt-4 text-sm text-green-600 text-center"></div>
    </x-authentication-card>
</x-guest-layout>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    function submitForm(event) {
        event.preventDefault();
        const otp = document.getElementById('otp').value;
        axios.post('{{ route('verify.otp') }}', { otp: otp })
            .then(response => {
                window.location.href = response.data.redirect;
            })
            .catch(error => {
                document.getElementById('error').innerText = error.response.data.errors.otp[0];
            });
    }

    function resendOtp() {
        document.getElementById('resendButton').disabled = true;
        axios.post('{{ route('resend.otp') }}')
            .then(response => {
                document.getElementById('message').innerText = response.data.message;
            })
            .catch(error => {
                document.getElementById('message').innerText = 'Failed to resend OTP. Please try again.';
            })
            .finally(() => {
                document.getElementById('resendButton').disabled = false;
            });
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
