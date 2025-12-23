<article class="space-y-8">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Languages Section -->
        <section class="space-y-5">
            <div class="bg-base-200 rounded-lg p-5">
                <div class="flex items-center gap-2 mb-4">
                    <x-tabler-book class="w-5 h-5 text-blue-600" />
                    <h3 class="font-semibold text-base-content">Teaches in</h3>
                </div>
                @if(!empty($user->languages_teach))
                    <ul class="flex flex-wrap gap-2">
                        @foreach($user->languages_teach as $code)
                            <li
                                class="px-4 py-2 bg-blue-500/10 text-blue-600 dark:bg-blue-900/30 text-sm font-medium rounded-lg border border-blue-500/20">
                                {{ config('languages')[$code] }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-base-content/50 text-sm italic">
                        No languages listed yet.
                    </p>
                @endif
            </div>

            <div class="bg-base-200 rounded-lg p-5">
                <div class="flex items-center gap-2 mb-4">
                    <x-tabler-school class="w-5 h-5 text-amber-600" />
                    <h3 class="font-semibold text-base-content">Learning</h3>
                </div>
                @if(!empty($user->languages_learn))
                    <ul class="flex flex-wrap gap-2">
                        @foreach($user->languages_learn as $code)
                            <li
                                class="px-4 py-2 bg-amber-500/10 text-amber-600 dark:bg-amber-900/30 text-sm font-medium rounded-lg border border-amber-500/20">
                                {{ config('languages')[$code] }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-base-content/50 text-sm italic">
                        No languages listed yet.
                    </p>
                @endif
            </div>
        </section>

        <!-- Description & Hobbies Section -->
        <section class="space-y-5">
            <div class="bg-base-200 rounded-lg p-5">
                <div class="flex items-center gap-2 mb-4">
                    <x-tabler-user class="w-5 h-5 text-primary" />
                    <h3 class="font-semibold text-base-content">About</h3>
                </div>
                @if($user->description)
                    <p class="text-base-content/80 text-sm leading-relaxed">
                        {{ $user->description }}
                    </p>
                @else
                    <p class="text-base-content/50 text-sm italic">
                        No description provided yet.
                    </p>
                @endif
            </div>

            <div class="bg-base-200 rounded-lg p-5">
                <div class="flex items-center gap-2 mb-4">
                    <x-tabler-heart class="w-5 h-5 text-pink-600" />
                    <h3 class="font-semibold text-base-content">Hobbies & Interests</h3>
                </div>
                @if($user->hobbies && count($user->hobbies) > 0)
                    <div class="flex flex-wrap gap-2">
                        @foreach($user->hobbies as $hobby)
                            <span
                                class="px-4 py-2 bg-pink-500/10 text-pink-600 dark:bg-pink-900/30 text-sm font-medium rounded-lg border border-pink-500/20">
                                {{ $hobby }}
                            </span>
                        @endforeach
                    </div>
                @else
                    <p class="text-base-content/50 text-sm italic">
                        No hobbies listed yet.
                    </p>
                @endif
            </div>
        </section>
    </div>

    <!-- Additional Information -->
    <footer class="bg-base-200 rounded-lg p-5">
        <div class="flex items-center gap-2 mb-4">
            <x-tabler-info-circle class="w-5 h-5 text-primary" />
            <h3 class="font-semibold text-base-content">Additional Information</h3>
        </div>
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div class="flex items-center gap-2">
                <x-tabler-map-pin class="w-4 h-4 text-base-content/60" />
                <dt class="text-base-content/60">Location:</dt>
                <dd class="text-base-content font-medium">{{ config('countries')[$user->location] ?? 'Not provided' }}</dd>
            </div>
            <div class="flex items-center gap-2">
                <x-tabler-calendar-event class="w-4 h-4 text-base-content/60" />
                <dt class="text-base-content/60">Joined:</dt>
                <dd class="text-base-content font-medium">
                    <time datetime="{{ $user->created_at->format('Y-m-d') }}">
                        {{ $user->created_at->format('F j, Y') }}
                    </time>
                </dd>
            </div>
        </dl>
    </footer>
</article>
