# Spotify Playlist Explorer

Spotify Playlist Explorer est une application qui permet de créer une playlist de recommandations basées sur un morceau, en garantissant que chaque titre proposé sera une véritable découverte. L'application filtre les recommandations pour éviter les morceaux déjà présents dans tes playlists existantes. De plus, il est posible d'affiner les suggestions selon divers critères : énergie, popularité, acousticité, et bien plus encore.

URL de démonstration : https://discover-playlist.ovh
Le nombre d'utilisateur étant limité par l'API Spotify, demander un accès pour tester l'application est possible à contact@robinberdier.com.

## Prérequis

- **Docker** et **Docker Compose** pour exécuter les services via Sail.
- **Node.js** et **npm** pour gérer les assets frontend.

## Commandes principales

**Lance le serveur :**
```bash
./vendor/bin/sail up -d
```

**Arrêter le serveur :**
```bash
./vendor/bin/sail down
```

**Lancer les migrations de base de données**
```bash
./vendor/bin/sail artisan migrate
```

**Générer les routes avec Ziggy**
```bash
./vendor/bin/sail artisan ziggy:generate
```

**Vider le cache de configuration**
```bash
./vendor/bin/sail php artisan config:clear
```

**Lancer le serveur de développement frontend**
```bash
npm run dev
```

**Générer les clés de l'application**
```bash
./vendor/bin/sail artisan key:generate
```
