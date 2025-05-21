<?php
require_once '../../../functions/keretaFunc.php';

$id = $_GET['id'] ?? null;

if ($id && deleteKereta($id)) {
    header("Location: kereta.php");
    exit;
} else {
    header("Location: kereta.php");
    exit;
}
?>
