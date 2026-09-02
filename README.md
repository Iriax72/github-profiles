# github-profiles

## Philosophie du site
Mon but en développant cette application est de m’entraîner à utiliser des APIs externes (celle de github en l’occurrence) avec cURL

Le fichier `/style.css` ainsi qu'une partie du `/README.md` que vous lisez sont générés par IA.

Merci à Magnific sur Favicon pour son image libre de droits !

## Sous le capot

Cette application est un petit site PHP rendu côté serveur. Elle ne possède ni base de données ni système de session : chaque recherche déclenche une requête vers l'API publique de GitHub, puis la réponse est utilisée immédiatement pour générer le HTML.

### Parcours d'une recherche

1. `index.php` affiche le formulaire `POST` dont le champ s'appelle `gh-account`.
2. Lorsque le formulaire est envoyé, la page instancie `API` et appelle `search_accounts()` avec le texte saisi.
3. `API::search_accounts()` construit l'endpoint `search/users`, limite la recherche aux logins GitHub (`+in:login`) et demande au maximum 10 résultats (`per_page=10` par défaut).
4. Pour chaque résultat, `index.php` crée un lien vers `details.php?login=...`. Le login est encodé pour l'URL et échappé avant d'être affiché dans le HTML.
5. Si GitHub renvoie une erreur ou si cURL échoue, la méthode API renvoie `null` et la page affiche un message d'erreur.

### Parcours d'une fiche profil

`details.php` exige un paramètre `login`. En son absence, l'utilisateur est redirigé vers `index.php`. Le login est ensuite transmis à `API::get_account_details()`, qui appelle l'endpoint `users/{login}` de GitHub.

La réponse est affichée sous forme de fiche : nom, adresse e-mail, localisation, biographie, avatar, nombres de dépôts, followers et following, dates de création et de dernière activité, ainsi qu'un lien vers le profil GitHub. La fonction locale `info()` centralise l'échappement des noms et valeurs affichés. Les champs absents sont remplacés par une valeur lisible comme `Inconnue` ou `Pas de nom`.

### Rôle des fichiers

- `index.php` : point d'entrée, formulaire de recherche et liste des utilisateurs trouvés.
- `details.php` : validation minimale du login, récupération et affichage d'un profil.
- `class/API.php` : encapsulation des appels HTTP à `https://api.github.com/`. C'est l'endroit à modifier pour ajouter un endpoint, changer le User-Agent ou améliorer la gestion des erreurs.
- `elements/header.php` et `elements/footer.php` : squelette HTML partagé par les pages.
- `style.css` : présentation visuelle du site.
- `certificate.ca` : certificat CA disponible pour cURL si la configuration TLS l'exige ; l'option correspondante est actuellement commentée dans `API.php`.

### Contrat de la classe `API`

Les méthodes publiques renvoient un tableau associatif issu du JSON de GitHub en cas de succès, ou `null` en cas d'échec :

- `search_accounts(string $query, int $limit = 10)` renvoie normalement un objet de recherche contenant un tableau `items`.
- `get_account_details(string $login)` renvoie normalement l'objet représentant un utilisateur GitHub.

Les deux méthodes délèguent le transport à `callApi()`. Cette méthode configure cURL avec un timeout de 10 secondes, demande le retour de la réponse dans une chaîne et identifie l'application avec le User-Agent `github-profiles`. Seul un statut HTTP `200` est actuellement accepté. Le JSON est décodé en tableau PHP avec `json_decode(..., true)`.

### Sécurité et points de vigilance

- Les valeurs provenant de GitHub ou de l'URL sont échappées avec `htmlspecialchars()` avant insertion dans le HTML.
- Les logins destinés à une URL passent par `urlencode()`.
- La requête de recherche est envoyée en `POST`, mais aucune validation métier du champ n'est encore faite : une amélioration naturelle serait de refuser une recherche vide et de limiter sa longueur.
- Les erreurs cURL, les statuts HTTP et les erreurs JSON sont ramenés au même résultat `null`. Une évolution utile serait de journaliser la cause côté serveur tout en affichant un message générique à l'utilisateur.
- GitHub peut appliquer une limite de débit. Il faudra prévoir une gestion dédiée des statuts `403` ou `429` avant d'ajouter des fonctionnalités qui multiplient les appels.
- Les erreurs PHP sont actuellement affichées dans `index.php`, ce qui est pratique en développement mais ne doit pas rester activé en production.

### Ajouter une fonctionnalité

Pour ajouter des données GitHub, commencer par ajouter une méthode dans `class/API.php`, puis appeler cette méthode depuis la page PHP concernée. Conserver la séparation entre récupération des données et affichage, utiliser les champs optionnels avec des valeurs par défaut, et échapper toute donnée avant de l'insérer dans le HTML. Si la fonctionnalité nécessite une nouvelle page, réutiliser les fichiers dans `elements/` afin de garder la structure commune.