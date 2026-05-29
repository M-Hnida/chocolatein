<?php

/**
 * Gestion de la recherche de produits
 *
 * PHP Version 7
 *
 * @category  B13
 * @package   ChocolateIn
 * @author    Hnida M
 * @copyright 2026 Hnida M
 * @license   Hnida M
 * @version   GIT: <0>
 * @link      https://chocolatein.gil83.fr Contexte « Chocolate'In »
 */
$recherche = filter_input(INPUT_GET, 'recherche', FILTER_SANITIZE_SPECIAL_CHARS);

if (!empty($recherche)) {
    $lesProduits = $pdo->getListeProduits($recherche);
    $title = "Recherche : " . $recherche;
    include './vues/v_listeProduits.php';
} else {
    include './controleurs/c_404.php';
}
