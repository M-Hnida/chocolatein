# Chocolate'in - Implémentation de la fonctionnalité de recherche

Ce dépôt contient la base de code du site **Chocolate'in** (articles et boutique de chocolat à Bordeaux), développée en PHP 7 avec une architecture MVC (Modèle-Vue-Contrôleur) et PDO pour la gestion de la base de données.

Nous avons implémenté une **nouvelle fonctionnalité de recherche de produit** permettant aux utilisateurs de rechercher des chocolats, confiseries et dragées par mot-clé (dans le nom, la description ou les détails).

---

## Travaux Réalisés

Conformément au cahier des charges, l'implémentation a été structurée en 6 étapes :

### 1. Routage & Contrôleur Principal (`index.php`)
Ajout d'une nouvelle route `recherche` dans le aiguilleur principal pour intercepter les demandes de recherche et les transférer au nouveau contrôleur :
```php
case 'recherche':
    include './controleurs/c_recherche.php';
    break;
```

### 2. Contrôleur de Recherche (`controleurs/c_recherche.php`)
Création de ce nouveau contrôleur pour orchestrer la recherche :
- Extraction et filtrage du mot-clé via `filter_input(INPUT_GET, 'recherche', FILTER_SANITIZE_SPECIAL_CHARS)`.
- Récupération des résultats via la méthode du modèle `getListeProduits($recherche)`.
- Définition dynamique du titre de la page (ex: `Recherche : noir`).
- Inclusion de la vue liste des produits (`v_listeProduits.php`) pour l'affichage des résultats.

### 3. Requête et Modèle de Données (`modele/class.pdochoc.inc.php`)
Implémentation de la méthode `getListeProduits($recherche)` au sein de la classe d'accès aux données `PdoChoc` :
- Préparation de la recherche approximative en encadrant le mot-clé par des jokers SQL `%` (`%mot-clé%`).
- Exécution d'une requête SQL préparée avec jointure pour chercher dans le nom, la description et les détails des produits :
```sql
SELECT DISTINCT produit.* FROM details_produits 
JOIN produit ON (id=idproduit)
WHERE details LIKE :uneRecherche
OR nom LIKE :uneRecherche
OR description LIKE :uneRecherche
```

### 4. Conservation du contexte de recherche (`vues/v_listeProduits.php`)
Modification des boutons "Plus d'infos" de chaque produit pour conserver la variable de recherche dans l'URL. Cela permet de savoir que l'utilisateur consulte un produit issu d'une recherche en cours :
```html
<a href="?uc=produit&produit=<?= $idPdt ?><?php if (isset($recherche)) { echo "&recherche=".$recherche; } ?>" class="btn btn-choc mb-2 p-1">Plus d'infos</a>
```

### 5. Fil d'Ariane dynamique (`vues/v_unProduit.php`)
Ajustement du fil d'ariane sur la page détail du produit :
- Si l'utilisateur provient d'une recherche, le fil d'ariane affiche un lien de retour direct vers les résultats de sa recherche avec son mot-clé.
- Sinon, le fil d'ariane affiche le chemin classique (Gamme > Nom de la gamme).

### 6. Formulaire de Recherche (`vues/v_entete.php`)
Intégration d'une zone de saisie de recherche (formulaire avec champ textuel et bouton loupe) à la fin de la barre de navigation Bootstrap. Le formulaire envoie les paramètres `uc=recherche` et le mot-clé saisi en méthode `GET`.

---

## Structure du Projet
- `/controleurs` : Gestionnaires des routes et logique métier intermédiaire.
- `/modele` : Classe d'accès aux données PDO (`PdoChoc`).
- `/vues` : Fichiers d'interface HTML/PHP responsive (Bootstrap).
- `bdd_restore-chocolatein.sql` : Script de restauration de la base de données.
