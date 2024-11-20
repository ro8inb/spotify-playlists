<div class="flex flex-col">
    <label for="{{ $id }}" class="block text-sm font-medium">
        {{ $label }}
        <span class="fas fa-info-circle fa-lg ml-2 text-gray-400 cursor-pointer"
            data-tippy-content="{{ $tooltip }}"></span>
    </label>
    <div class="flex items-center mt-2">
        <input type="checkbox" id="toggle_range_{{ $id }}" class="mr-2 checked:bg-green-500 rounded focus:outline-none focus:ring-0 border-none focus:shadow-none">
        <input type="range" step="{{ $step }}" min="{{ $min }}" max="{{ $max }}"
            id="{{ $id }}" name="{{ $name }}"
            class="w-full appearance-none pointer-events-none h-2 bg-transparent" disabled>
    </div>
</div>

@vite(['resources/css/range-input.css'])
