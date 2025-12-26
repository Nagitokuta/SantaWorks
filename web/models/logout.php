<?php
if (!isset($_SESSION['USER'])) {
    header('Location: /top');
    exit;
}
$user = $_SESSION['USER'];
unset($_SESSION['USER']);
unset($pdo);
header('Location: /top');
exit;
