import { route } from 'ziggy-js';
import toastr from 'toastr';

document.addEventListener('DOMContentLoaded', function () {

    tippy('[data-tippy-content]', {
        placement: 'bottom',
        animation: 'scale',
        popperOptions: {
            modifiers: [
                {
                    name: 'preventOverflow',
                    options: {
                        boundary: 'window'
                    },
                },
            ],
        },
    });

    const generatePlaylistButton = document.getElementById('generate-playlist');
    const playlistModal = document.getElementById('playlist-modal');
    const closeModalButton = document.getElementById('close-modal');
    const playlistForm = document.getElementById('playlist-settings-form');
    const playlistIframe = document.getElementById('spotify-playlist');
    const searchTracksInput = document.getElementById('search_tracks');
    const searchTracksInputId = document.getElementById('search_tracks_input_id');
    const toggleParamsCheckbox = document.querySelectorAll('input[id^="toggle_range_"]');
    const suggestionsList = document.createElement('ul');

    suggestionsList.classList.add('absolute', 'bg-white', 'w-full', 'max-h-48', 'overflow-y-auto');
    searchTracksInput.parentNode.appendChild(suggestionsList);

    generatePlaylistButton.addEventListener('click', function () {
        playlistModal.classList.remove('hidden');
    });

    closeModalButton.addEventListener('click', function () {
        playlistModal.classList.add('hidden');
    });

    // Envoi des données du formulaire
    playlistForm.addEventListener('submit', function (e) {
        e.preventDefault();
        if (!searchTracksInputId.value) {
            toastr.error('Veuillez sélectionner un morceau dans la liste pour continuer.', {
                timeOut: 3000,
                progressBar: true
            });
            return;
        }
        const formData = new FormData(playlistForm);

        // Convertir les données en objet JSON
        const settings = {};
        formData.forEach((value, key) => {
            settings[key] = value;
        });
        fetch(route('generate.playlist'), {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(settings)
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.success('La Discover Playlist a été générée avec succès !', {
                        timeOut: 3000,
                        progressBar: true
                    });
                    playlistIframe.src = playlistIframe.src;
                    playlistModal.classList.add('hidden');
                } else {
                    toastr.error('Une erreur est survenue lors du rechargement de la playlist.', {
                        timeOut: 3000,
                        progressBar: true
                    });
                }
                resetFormFields();
            })
            .catch(error => {
                toastr.error('Une erreur inattendue est survenue.', {
                    timeOut: 3000,
                    progressBar: true
                });
            });
    });

    // Requête AJAX pour les suggestions de morceaux
    searchTracksInput.addEventListener('input', function () {
        const query = searchTracksInput.value.trim();
        // N'effectuer la requête que si au moins 2 caractères sont entrés
        if (query.length >= 2) {
            fetch(route('search.tracks', { 'nameTrack': query }), {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                }
            })
                .then(response => response.json())
                .then(data => {
                    suggestionsList.innerHTML = '';
                    const tracks = Object.values(data.listTracks.tracks.items);
                    tracks.forEach(track => {
                        const listItem = document.createElement('li');
                        listItem.classList.add('p-2', 'hover:bg-gray-200', 'cursor-pointer', 'text-black');
                        listItem.textContent = track.name + ' - ' + track.artists[0].name;
                        listItem.addEventListener('click', function () {
                            searchTracksInput.value = track.name + ' - ' + track.artists[0].name;
                            searchTracksInputId.value = track.id;
                            suggestionsList.innerHTML = '';
                        });

                        suggestionsList.appendChild(listItem);
                    });

                    if (data.listTracks.tracks.length === 0) {
                        const noResultItem = document.createElement('li');
                        noResultItem.classList.add('p-2', 'text-gray-500');
                        noResultItem.textContent = 'Aucun morceau trouvé';
                        suggestionsList.appendChild(noResultItem);
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des morceaux:', error);
                });
        } else {
            suggestionsList.innerHTML = ''; // Vider les suggestions si moins de 2 caractères
        }
    });
    toggleParamsCheckbox.forEach(function (checkbox) {
        checkbox.addEventListener('change', function (field) {
            const fieldId = field.currentTarget.id;
            const fieldToToggle = document.getElementById(fieldId.replace('toggle_range_', ''));
            fieldToToggle.disabled = !field.currentTarget.checked;
        });
    })

    function resetFormFields() {
        // Réinitialiser le champ de recherche de morceaux
        searchTracksInput.value = '';
        searchTracksInputId.value = '';

        // Réinitialiser tous les input de type 'checkbox'
        const checkboxes = playlistForm.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = false;
        });

        // Réinitialiser tous les input de type 'range'
        const ranges = playlistForm.querySelectorAll('input[type="range"]');
        ranges.forEach(range => {
            range.value = range.defaultValue;
            range.disabled = true;
        });

        // Vider les suggestions de morceaux
        suggestionsList.innerHTML = '';
    }
});
