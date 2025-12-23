<x-guest-layout>
    <!-- DaisyUI Steps -->
    <div class="flex justify-center">
        <ul class="steps steps-horizontal mb-8 w-full max-w-2xl">
            <li class="step step-primary text-base-content" id="step1-indicator"></li>
            <li class="step text-base-content" id="step2-indicator"></li>
            <li class="step text-base-content" id="step3-indicator"></li>
            <li class="step text-base-content" id="step4-indicator"></li>
        </ul>
    </div>

    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf
        <!-- Step 1 -->
        <div id="step1" class="relative overflow-visible" style="display:block;">
            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required
                    autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Password')" />
                <div class="relative">
                    <x-text-input id="password" class="block mt-1 w-full pr-10" type="password" name="password" required
                        autocomplete="new-password" />
                    <button type="button" id="togglePassword"
                        class="absolute inset-y-0 end-0 flex items-center pr-3 focus:outline-none hover:opacity-70 transition">
                        <span id="eyeOnPassword" style="display:none;">
                            <x-monoicon-eye class="h-5 w-5 text-base-content opacity-60" />
                        </span>
                        <span id="eyeOffPassword">
                            <x-monoicon-eye-off class="h-5 w-5 text-base-content opacity-60" />
                        </span>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <div class="relative">
                    <x-text-input id="password_confirmation" class="block mt-1 w-full pr-10" type="password"
                        name="password_confirmation" required autocomplete="new-password" />
                    <button type="button" id="toggleConfirmPassword"
                        class="absolute inset-y-0 end-0 flex items-center pr-3 focus:outline-none hover:opacity-70 transition">
                        <span id="eyeOnConfirmPassword" style="display:none;">
                            <x-monoicon-eye class="h-5 w-5 text-base-content opacity-60" />
                        </span>
                        <span id="eyeOffConfirmPassword">
                            <x-monoicon-eye-off class="h-5 w-5 text-base-content opacity-60" />
                        </span>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
            <div class="flex items-center justify-end mt-6">
                <button type="button" class="btn btn-primary w-full" onclick="nextStep(1)">Next</button>
            </div>
        </div>

        <!-- Step 2 -->
        <div id="step2" style="display:none;">
            <div>
                <x-input-label for="birthday" :value="__('When is your birthday?')" />
                <x-text-input id="birthday" class="block mt-1 w-full" type="date" name="birthday"
                    :value="old('birthday')" required />
                <x-input-error :messages="$errors->get('birthday')" class="mt-2" />
            </div>
            <div class="flex items-center justify-between mt-6 gap-4">
                <button type="button" class="btn btn-outline btn-secondary flex-1" onclick="prevStep(2)">Back</button>
                <button type="button" class="btn btn-primary flex-1" onclick="nextStep(2)">Next</button>
            </div>
        </div>

        <!-- Step 3 -->
        <div id="step3" class="relative overflow-visible" style="display:none;">
            <div>
                <x-input-label for="languages_teach" :value="__('I speak (select any)')" />
                <select id="languages_teach" name="languages_teach[]" class="absolute" multiple required>
                    @foreach(config('languages') as $code => $language)
                        <option value="{{ $language }}">{{ $language }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('languages_teach')" class="mt-2" />
            </div>
            <div class="flex items-center justify-between mt-6 gap-4">
                <button type="button" class="btn btn-outline btn-secondary flex-1" onclick="prevStep(3)">Back</button>
                <button type="button" class="btn btn-primary flex-1" onclick="nextStep(3)">Next</button>
            </div>
        </div>

        <!-- Step 4 -->
        <div id="step4" class="relative overflow-visible" style="display:none;">
            <div>
                <x-input-label for="languages_learn" :value="__('I want to learn (select any)')" />
                <select id="languages_learn" name="languages_learn[]" multiple="multiple" required>
                    @foreach(config('languages') as $code => $language)
                        <option value="{{ $language }}">{{ $language }}</option>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('languages_learn')" class="mt-2" />
            </div>
            <div class="flex items-center justify-between mt-6 gap-4">
                <button type="button" class="btn btn-outline btn-secondary flex-1" onclick="prevStep(4)">Back</button>
                <button type="submit" class="btn btn-primary flex-1">Register</button>
            </div>
        </div>

        <div class="flex items-center justify-center mt-4">
            <a class="text-sm text-base-content opacity-70 hover:underline hover:opacity-100 hover:text-primary transition"
                href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>
        </div>
    </form>


    <script>
        const options = {
            plugins: ['remove_button'],
            closeAfterSelect: true,
            maxItems: 8,
        };

        function initTomSelect() {
            new TomSelect('#languages_teach', options);
            new TomSelect('#languages_learn', options);
        };

        window.onload = function () {
            updateStepIndicator(1);
            initTomSelect();
        };

        function validateStep1() {
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;

            if (!name || !email || !password || !passwordConfirmation) {
                alert('Please fill in all fields');
                return false;
            }

            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
                alert('Please enter a valid email address');
                return false;
            }

            if (password.length < 8) {
                alert('Password must be at least 8 characters long');
                return false;
            }

            if (password !== passwordConfirmation) {
                alert('Passwords do not match');
                return false;
            }

            return true;
        }

        function validateStep2() {
            const birthday = document.getElementById('birthday').value;
            if (!birthday) {
                alert('Please enter your birthday');
                return false;
            }
            return true;
        }

        function validateStep3() {
            const languageSelect = document.getElementById('languages_teach');
            const selectedLanguages = Array.from(languageSelect.selectedOptions).map(option => option.value);

            if (selectedLanguages.length === 0) {
                alert('Please select at least one language you speak');
                return false;
            }
            if (selectedLanguages.length > 8) {
                alert('You can select a maximum of 8 languages you speak');
                return false;
            }
            return true;
        }

        function validateStep4() {
            const languageSelect = document.getElementById('languages_learn');
            const selectedLanguages = Array.from(languageSelect.selectedOptions).map(option => option.value);

            if (selectedLanguages.length === 0) {
                alert('Please select at least one language you want to learn');
                return false;
            }
            if (selectedLanguages.length > 8) {
                alert('You can select a maximum of 8 languages you want to learn');
                return false;
            }

            return true;
        }

        function nextStep(current) {
            let isValid = false;

            if (current === 1) isValid = validateStep1();
            if (current === 2) isValid = validateStep2();
            if (current === 3) isValid = validateStep3();

            if (!isValid) return;
            document.getElementById('step' + current).style.display = 'none';
            document.getElementById('step' + (current + 1)).style.display = 'block';
            updateStepIndicator(current + 1);
        }

        function prevStep(current) {
            document.getElementById('step' + current).style.display = 'none';
            document.getElementById('step' + (current - 1)).style.display = 'block';
            updateStepIndicator(current - 1);
        }

        function updateStepIndicator(step) {
            for (let i = 1; i <= 4; i++) {
                const indicator = document.getElementById(`step${i}-indicator`);
                indicator.classList.toggle('step-primary', i <= step);
            }
        }

        document.getElementById('registerForm').addEventListener('submit', function (e) {
            if (!validateStep4()) {
                e.preventDefault();
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const togglePassword = document.getElementById("togglePassword");
            const password = document.getElementById("password");
            const eyeOnPassword = document.getElementById("eyeOnPassword");
            const eyeOffPassword = document.getElementById("eyeOffPassword");

            const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");
            const confirmPassword = document.getElementById("password_confirmation");
            const eyeOnConfirmPassword = document.getElementById("eyeOnConfirmPassword");
            const eyeOffConfirmPassword = document.getElementById("eyeOffConfirmPassword");

            togglePassword.addEventListener("click", function () {
                const type = password.getAttribute("type") === "password" ? "text" : "password";
                password.setAttribute("type", type);

                if (type === "text") {
                    eyeOnPassword.style.display = "";
                    eyeOffPassword.style.display = "none";
                } else {
                    eyeOnPassword.style.display = "none";
                    eyeOffPassword.style.display = "";
                }
            });

            toggleConfirmPassword.addEventListener("click", function () {
                const type = confirmPassword.getAttribute("type") === "password" ? "text" : "password";
                confirmPassword.setAttribute("type", type);

                if (type === "text") {
                    eyeOnConfirmPassword.style.display = "";
                    eyeOffConfirmPassword.style.display = "none";
                } else {
                    eyeOnConfirmPassword.style.display = "none";
                    eyeOffConfirmPassword.style.display = "";
                }
            });
        });
    </script>
</x-guest-layout>