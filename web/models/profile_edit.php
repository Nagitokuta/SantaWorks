<?php
$meta_page_title = 'profile_edit';

if (!isset($_SESSION['USER'])) {
    header('Location:/login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = $_POST['user_name'];
    $sql = "UPDATE user SET user_name = :user_name WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_name', encrypt($user_name), PDO::PARAM_STR);
    $stmt->bindValue(':id', $_SESSION['USER']['id'], PDO::PARAM_INT);
    $stmt->execute();

    if ($user_name == '暗黒神ダークエクソダス') {
        //暗黒新ダークエクソダス
        if (isset($_SESSION['USER'])) {
            $id = $_SESSION['USER']['id'];
            $sql = "SELECT id FROM with_title WHERE user_id = :user_id AND title_id = :title_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':title_id', 10, PDO::PARAM_INT);
            $stmt->execute();
            $existence = $stmt->fetchColumn();

            if ($existence == false) {
                $sql = "INSERT INTO with_title
    (user_id, title_id)
    VALUES
    (:user_id, :title_id)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                $stmt->bindValue(':title_id', 10, PDO::PARAM_INT);
                $stmt->execute();
                $get_title = true;
                $text = '暗黒神ダークエクソダス';
            }
        }

        header("Location: profile?get");
        exit;
    }

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
            header("Location: profile_edit");
            exit;
        }
    }
    header("Location: profile");
    exit;
}
$id = $_SESSION['USER']['id'];
$sql = "SELECT *
            FROM user
            WHERE id = :id;";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <?php include 'templates/head.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            scrollbar-width: none;
        }

        .full-height-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .otaku-title {
            font-family: 'UnifrakturCook', cursive;
            font-size: 80px;
            color: rgb(202, 189, 144);
        }

        @media (min-width: 768px) {
            .otaku-title {
                font-size: 100px;
            }
        }

        .difficulty-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
            max-height: 160px;
            object-fit: cover;
        }

        .difficulty-option {
            border: 7px solid transparent;
            border-radius: 50px;
            padding: 10px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .difficulty-option:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .difficulty-option.selected {
            border-color: #7a9fd5;
        }

        .difficulty-radio {
            display: none;
        }

        .btn {
            font-family: 'UnifrakturCook', cursive;
            border-radius: 50px;
            font-size: 1.2rem;
            padding: 0.75rem 1.5rem;
        }

        .btn-start:hover {
            color: #000;
        }

        .btn-start {
            background: linear-gradient(135deg, rgba(255, 235, 150, 0.8), rgba(255, 200, 60, 0.8));
            border: none;
            color: #333;
            font-weight: bold;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-start:hover {
            background: linear-gradient(135deg, rgba(255, 245, 180, 1), rgba(255, 180, 40, 1));
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        .btn-otaku {
            background: linear-gradient(135deg, rgba(0, 123, 255, 0.6), rgba(0, 93, 199, 0.6));
            border: none;
            color: #fff;
            font-weight: bold;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-otaku:hover {
            background: linear-gradient(135deg, rgba(30, 143, 255, 0.8), rgba(0, 93, 199, 0.8));
            box-shadow: 0 4px 12px rgba(0, 93, 199, 0.3);
            transform: translateY(-2px);
            color: white;
        }

        .btn-pro {
            background: linear-gradient(135deg, rgba(220, 0, 0, 0.6), rgba(150, 0, 0, 0.6));
            border: none;
            color: #fff;
            font-weight: bold;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
        }

        .btn-pro:hover {
            background: linear-gradient(135deg, rgba(240, 0, 0, 0.8), rgba(100, 0, 0, 0.8));
            box-shadow: 0 4px 12px rgba(150, 0, 0, 0.3);
            transform: translateY(-2px);
            color: white;
        }

        img#profileImage {
            display: block;
            max-width: none;
        }

        img {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;

        }

        .title-box {
            position: relative;
            padding: 0.75rem 1rem;
            border-radius: 20px;
            background-color: #ccc;
            text-align: center;
            font-weight: bold;
            cursor: pointer;
            min-height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .locked-title {
            background-color: #555;
            color: #eee;
        }

        .unlocked-title {
            background-size: cover;
            background-position: center;
            color: #fff;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.4);
        }

        .profile-title {
            background-size: cover;
            background-position: center;
            color: #fff;
            box-shadow: 0 0 8px rgba(0, 0, 0, 0.4);
        }

        .popover {
            max-width: 200px;
            font-size: 0.9rem;
        }

        hr {
            margin: 1rem 0;
            color: inherit;
            border: 0;
            border-top: var(--bs-border-width) solid;
            opacity: .25;
        }

        .level {
            font-family: "WDXL Lubrifont TC", sans-serif;
            font-weight: 400;
            font-style: normal;
            font-size: 50px;
        }

        .name-font {
            font-family: "WDXL Lubrifont TC", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .title-text {
            font-size: 22px;
            font-weight: 800;
        }

        .btn {
            background-color: rgba(230, 230, 230, 0.77);
            color: #000;
            font-family: "WDXL Lubrifont TC", sans-serif;
        }

        .btn:hover {
            background-color: rgb(228, 228, 228);
            color: #000;
        }

        .col-6 {
            position: relative;
        }

        .hover-float {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            cursor: pointer;
        }

        .hover-float:hover {
            transform: translateY(-5px) scale(1.03);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<?php include 'templates/header.php'; ?>

<body class="pd-t">
    <main>
        <div class="container full-height-wrapper">
            <div class="w-100" style="max-width: 700px;">
                <div class="container mt-5">
                    <form id="iconForm" action="profile_edit" method="POST" enctype="multipart/form-data">
                        <div class="d-flex align-items-center p-4 rounded shadow">
                            <div class="me-4 position-relative">
                                <label for="avatar-upload" class="d-inline-block position-relative">
                                    <img
                                        src="<?php if ($user['icon_path'] !== null) : ?>img?id=<?= $user['id'] ?><?php else : ?>../assets/img/initial_icon.png<?php endif; ?>"
                                        alt="プロフィール画像"
                                        id="iconPreview"
                                        class="rounded-circle border border-secondary hover-float"
                                        style="width: 96px; height: 96px; object-fit: cover; border-radius: 50%; cursor: pointer;" />
                                    <input type="file" id="avatar-upload"
                                        name='icon' class="d-none" accept="image/*" />
                                </label>
                            </div>
                            <div style="position: relative; width: 45%; left: 60px;">
                                <input type="text"
                                    name="user_name"
                                    value="<?= htmlspecialchars(decrypt($user['user_name']), ENT_QUOTES, 'UTF-8') ?>"
                                    class="name-font fw-bold mb-1"
                                    style="border: none; background: transparent; font-size: 1.75rem; outline: none; width: 100%; z-index: 99;" />
                                <hr style="margin: 0;">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-secondary w-50 rounded-0 rounded-bottom" style="border-right: 1px solid #ccc;">
                            フ*ロフィールを編集する
                        </button>
                    </form>
                </div>
                <div class="my-5 fw-bold"><a href="/profile" style="text-decoration: none; color:rgba(0, 123, 255, 0.6);">プロフィールに戻る</a></div>
            </div>
        </div>
    </main>

    <div class="image-left"></div>
    <div class="image-right"></div>

    <?php include 'templates/footer.php' ?>
    <?php include 'templates/script.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('avatar-upload').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('iconPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);

                document.getElementById('iconForm').submit();
            }
        });
    </script>
</body>

</html>