import { route } from 'ziggy-js';

document.addEventListener('DOMContentLoaded', function () {
    fetch(route('processing.data'), {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = "/dashboard";
        } else {
            alert('Une erreur est survenue lors de la récupération des playlists.');
        }
    })
    .catch(error => console.error('Erreur:', error));
});
