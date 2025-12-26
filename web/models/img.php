<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT icon_path, extension FROM user WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $file_path = $stmt->fetch(PDO::FETCH_ASSOC);

    $extension = $file_path['extension'];
    $file_path = getenv('UPLOADS') . '/icons/' . $file_path['icon_path'] . '.' . $extension;
}
header('Content-Type: image/' . $extension);
readfile($file_path);
exit;
