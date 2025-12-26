<?php
if (isset($_FILES['icon']) && $_FILES['icon']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = getenv('UPLOADS') . '/icons/';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $extension = pathinfo($_FILES['icon']['name'], PATHINFO_EXTENSION);
    $newFileName = 'user_' . $_SESSION['USER']['id'] . '.' . $extension;
    $iconPath = pathinfo($newFileName, PATHINFO_FILENAME);
    $uploadPath = $upload_dir . $newFileName;

    if (move_uploaded_file($_FILES['icon']['tmp_name'], $uploadPath)) {
        $sql = "UPDATE user SET icon_path = :icon_path, extension = :extension WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':icon_path', $iconPath, PDO::PARAM_STR);
        $stmt->bindValue(':extension', $extension, PDO::PARAM_STR);
        $stmt->bindValue(':id', $_SESSION['USER']['id'], PDO::PARAM_INT);
        $stmt->execute();
        // リダイレクトで再表示
        header("Location: mypage");
        exit;
    }
}
