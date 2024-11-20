document.addEventListener('DOMContentLoaded', function () {
    const toggleMultiRange = document.querySelectorAll('input[type="range"][id*="_popularity_range"]');
    const toggleParamsCheckbox = document.querySelectorAll('input[id^="toggle_multi_range_"]');
    const rangebar = document.getElementById('range-bar');
    toggleMultiRange.forEach(function (input) {
        input.addEventListener('input', function () {
            updatePopularityRange();
        });
    })
    toggleParamsCheckbox.forEach(function (checkbox) {
        checkbox.addEventListener('change', function (field) {
            toggleMultiRange.forEach(function (range) {
                range.disabled = !checkbox.checked;
            })
            rangebar.classList.toggle('bg-green-500', checkbox.checked);
            rangebar.classList.toggle('bg-transparent', !checkbox.checked);
        });
    })
    function updatePopularityRange() {
        const minRange = document.getElementById('min_popularity_range');
        const maxRange = document.getElementById('max_popularity_range');
        const minValue = parseInt(minRange.value);
        const maxValue = parseInt(maxRange.value);

        const rangeBar = document.getElementById('range-bar');
        const hiddenMin = document.getElementById('min_popularity');
        const hiddenMax = document.getElementById('max_popularity');

        // Empêcher le chevauchement des poignées strictement
        if (minRange === document.activeElement && minValue >= maxValue) {
            minRange.value = maxValue - 1; // Verrouiller minValue à maxValue - 1
            minValue = parseInt(minRange.value);
        }

        if (maxRange === document.activeElement && maxValue <= minValue) {
            maxRange.value = minValue + 1; // Verrouiller maxValue à minValue + 1
            maxValue = parseInt(maxRange.value);
        }

        // Mettre à jour la position et la largeur de la barre de plage
        const rangeWidth = maxValue - minValue;
        rangeBar.style.left = `${minValue}%`;
        rangeBar.style.width = `${rangeWidth}%`;

        // Mettre à jour les champs cachés
        hiddenMin.value = minRange.value;
        hiddenMax.value = maxRange.value;
    }
    updatePopularityRange();
});
