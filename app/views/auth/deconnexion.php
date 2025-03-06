<?php
session_start();
session_destroy();

// Redirection vers la page d'accueil ou la page de connexion
header("Location: index.php");
exit;
