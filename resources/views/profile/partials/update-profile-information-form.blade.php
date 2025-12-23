<section x-init="initTomSelect()">
    <header>
        <h2 class="text-lg font-medium text-base-content">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-base-content">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="profile_picture" :value="__('Profile Picture')" />
            <div class="mt-1 space-y-4">
                <!-- Current profile picture preview -->
                <div class="flex items-center space-x-6">
                    <div
                        class="w-20 h-20 rounded-full overflow-hidden bg-base-200 flex items-center justify-center border-2 border-base-300">
                        @if($user->profile_picture && Storage::disk('public')->exists($user->profile_picture))
                            <img src="{{ Storage::url($user->profile_picture) }}" alt="Profile"
                                class="w-full h-full object-cover">
                        @else
                            <span class="text-xl font-bold text-base-content">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Custom file input -->
                <div>
                    <input id="profile_picture" name="profile_picture" type="file" class="hidden" accept="image/*"
                        onchange="handleFileSelect(this)" />

                    <!-- Custom button -->
                    <button type="button" onclick="document.getElementById('profile_picture').click()"
                        class="btn btn-outline">
                        Choose File
                    </button>

                    <!-- Selected filename display (only shown when file is selected) -->
                    <div id="selected-file-display" class="hidden mt-2">
                        <div class="flex items-center space-x-2">
                            <svg class="w-4 h-4 text-success" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span id="selected-filename" class="text-sm text-base-content"></span>
                            <button type="button" onclick="clearFileSelection()"
                                class="text-error hover:text-error-focus text-sm ml-2">
                                Remove
                            </button>
                        </div>
                    </div>

                    <x-input-error class="mt-2" :messages="$errors->get('profile_picture')" />
                    <p class="mt-1 text-sm text-base-content/60">
                        {{ __('Choose a new profile picture. JPG, PNG, GIF, or WebP. Maximum 2MB.') }}
                    </p>
                </div>
            </div>
        </div>

        <script>
            function handleFileSelect(input) {
                const display = document.getElementById('selected-file-display');
                const filename = document.getElementById('selected-filename');

                if (input.files && input.files[0]) {
                    filename.textContent = input.files[0].name;
                    display.classList.remove('hidden');
                } else {
                    display.classList.add('hidden');
                }
            }

            function clearFileSelection() {
                const input = document.getElementById('profile_picture');
                const display = document.getElementById('selected-file-display');

                input.value = '';
                display.classList.add('hidden');
            }
        </script>

        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="input input-bordered w-full mt-1" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="input input-bordered w-full mt-1"
                :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-base-content">
                        {{ __('Your email address is unverified.') }}
                        <button form="send-verification" class="link text-sm">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>

                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-2 font-medium text-sm text-success">
                                {{ __('A new verification link has been sent to your email address.') }}
                            </p>
                        @endif
                </div>
            @endif
        </div>

        <div>
            <x-input-label for="languages_teach" :value="__('Languages You Teach')" />

            <select id="languages_teach" name="languages_teach[]" multiple>
                @foreach(config('languages') as $code => $name)
                    <option value="{{ $code }}" @if(in_array($code, old('languages_teach', $user->languages_teach ?? [])))
                    selected @endif>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <x-input-label for="languages_learn" :value="__('Languages You Wish to Learn')" />

            <select id="languages_learn" name="languages_learn[]" multiple>
                @foreach(config('languages') as $code => $name)
                    <option value="{{ $code }}" @if(in_array($code, old('languages_learn', $user->languages_learn ?? [])))
                    selected @endif>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <x-input-label for="description" :value="__('Description')" />
            <textarea id="description" name="description" class="textarea textarea-bordered w-full border border-base-100 bg-base-300 rounded-lg placeholder-gray-600 dark:placeholder-gray-400
                           transition duration-150 ease-out
                           focus:ring-indigo-600 focus:outline-none focus:ring-2" rows="4" maxlength="1000"
                placeholder="{{ __('Tell us about yourself...') }}">{{ old('description', $user->description) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('description')" />
            <p class="mt-1 text-sm text-base-content/60">
                {{ __('Maximum 1000 characters') }}
            </p>
        </div>

        <div>
            <x-input-label for="hobbies" :value="__('Hobbies & Interests')" />
            <x-text-input id="hobbies" name="hobbies" type="text" class="input input-bordered w-full mt-1"
                :value="old('hobbies', $user->hobbies ? implode(', ', $user->hobbies) : '')" maxlength="500"
                placeholder="{{ __('e.g. Reading, Gaming, Cooking, Travel') }}" />
            <x-input-error class="mt-2" :messages="$errors->get('hobbies')" />
            <p class="mt-1 text-sm text-base-content/60">
                {{ __('Separate multiple hobbies with commas. Maximum 500 characters total.') }}
            </p>
        </div>

        <div>
            <x-input-label for="location" :value="__('Location')" />

            <select id="location" name="location" class="select w-full lg:w-1/2 border border-base-100 bg-base-300 rounded-lg placeholder-gray-600 dark:placeholder-gray-400
                           transition duration-150 ease-out
                           focus:ring-indigo-600 focus:outline-none focus:ring-2">
                <option value="">{{ __('-- Select your location --') }}</option>
                @foreach(config('countries') as $code => $name)
                    <option value="{{ $code }}" {{ old('location', $user->location) === $code ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>

            <p class="mt-1 text-sm text-base-content/60">
                {{ __('Select the country you currently reside in.') }}
            </p>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-base-content">{{ __('Saved.') }}</p>
            @endif
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
    </script>
</section>