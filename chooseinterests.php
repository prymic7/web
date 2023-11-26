<?php
include_once 'header.php';

if (!isset($_SESSION['allowedAccess']) || $_SESSION['allowedAccess'] !== true) {
    echo "RIP";
    // Pokud není povolený přístup, přesměrujte na jinou stránku
    header("location: registerpage.php");
    exit();
}
?>






















<?php
include_once 'footer.php'
?>