<div class="flex flex-col" id="multi-range-container">
    <label for="{{ $id }}" class="block text-sm font-medium">
        {{ $label }}
        <span class="fas fa-info-circle fa-lg ml-2 text-gray-400 cursor-pointer"
            data-tippy-content="{{ $tooltip }}"></span>
    </label>
    <div class="flex items-center mt-2">
        <input type="checkbox" id="toggle_multi_range_{{ $id }}" class="mr-2 checked:bg-green-500 rounded focus:outline-none focus:ring-0 border-none focus:shadow-none">
        <div class="relative w-full items-center">
            <!-- Barre de plage avec deux poignées -->
            <div class="relative h-2 bg-gray-300 rounded">
                <div id="range-bar" class="absolute h-2 bg-transparent rounded"></div>
            </div>

            <!-- Poignées pour min et max -->
            <input type="range" min="{{ $min }}" max="{{ $max }}" step="1" id="{{ $firstRangeId }}_range" disabled
                class="absolute top-0 w-full appearance-none pointer-events-none h-2 bg-transparent" value="0" />
            <input type="range" min="{{ $min }}" max="{{ $max }}" step="1" id="{{ $secondRangeId }}_range" disabled
                class="absolute top-0 w-full appearance-none pointer-events-none h-2 bg-transparent" value="100" />

            <!-- Champs cachés pour soumettre les valeurs min et max -->
            <input type="hidden" id="{{ $firstRangeId }}" name="{{ $firstRangeName }}" value="0" />
            <input type="hidden" id="{{ $secondRangeId }}" name="{{ $secondRangeName }}" value="100" />
        </div>
    </div>
</div>

@vite(['resources/js/multi-range-input.js', 'resources/css/range-input.css'])
