<?php
$meta_page_title = 'profile';

if (!isset($_SESSION['USER'])) {
    header('Location:/login');
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

//獲得済み称号取得
$sql = "SELECT title_id
            FROM with_title
            WHERE user_id = :id;";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);
$stmt->execute();
$get_titles = $stmt->fetchAll(PDO::FETCH_ASSOC);

$unlocked_ids = array_column($get_titles, 'title_id');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titleId = isset($_POST['selected_title_id']) ? (int)$_POST['selected_title_id'] : null;
    if ($titleId !== null) {
        // データベース更新処理
        $pdo = connectDB();
        $stmt = $pdo->prepare("UPDATE user SET set_title = :titleId WHERE id = :id");
        $stmt->execute([':titleId' => $titleId, ':id' => $id]);

        header('Location: profile');
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
            header("Location: profile");
            exit;
        }
    }
}
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
            z-index: 30;
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

        /* ゴッドハンド */
        .title-1 {
            top: 48px;
            left: -48px;
            width: 140%;
        }

        .title-1-col {
            top: -33px;
        }

        .title-1-position {
            top: -26px;
        }

        /* ゴッドノウズ */
        .title-2 {
            top: 60px;
        }

        .title-2-col {
            top: -75px;
        }

        .title-2-position {
            top: 22px;
        }

        /* ウルフレジェンド*/
        .title-3 {
            top: 19px;
        }

        .title-3-col {
            top: -125px;
        }

        .title-3-position {
            top: 15px;
        }

        .wolf {
            top: 19px;
            z-index: 2;
        }

        .wolf-col {
            top: -22px;
        }

        .wolf-position {
            top: 15px;
            z-index: 2;
        }


        /* 流星ブレード*/
        .title-4 {
            top: 50px;
            width: 132%;
            left: -34px;
            z-index: 1;
        }

        .title-4-col {
            top: -79px;
        }

        .title-4-position {
            top: 22px;
            z-index: 100;
        }

        /* ぶろっこり  */
        .title-5 {
            top: 66px;
            width: 257%;
            left: -293px;
            z-index: 1;
        }

        .title-5-col {
            top: -170px;
        }

        .title-5-position {
            top: -61px;
            z-index: 10;
        }

        /* ざなーく  */
        .title-6 {
            top: 41px;
            width: 140%;
            left: -103px;
            z-index: 2;
        }

        .title-6-col {
            top: -66px;
        }

        .title-6-position {
            top: -61px;
            z-index: 10;
        }

        /* 座後メル  */
        .title-7 {
            top: 6px;
            width: 140%;
            left: -84px;
        }

        .zago-col {
            top: -120px;
        }

        .title-7-position {
            top: -61px;
            z-index: 10;
        }

        .title-7-col {
            position: relative;
            top: 40px;
        }

        /* だーふぇに  */
        .title-8 {
            top: -50px;
            width: 130%;
            left: -30px;
            z-index: 0;
        }

        .title-8-col {
            position: relative;
            top: 68px;
        }

        .title-8-position {
            top: -61px;
            z-index: 10;
        }

        .dark-col {
            top: -126px;
        }

        /* たましい  */
        .title-9 {
            top: -50px;
            width: 120%;
            z-index: 0;
        }

        .title-9-col {
            position: relative;
            top: 69px;
        }

        .title-9-position {
            top: -61px;
            z-index: 10;
        }

        .rokoko-col {
            top: -60px;
        }

        /* 暗黒  */
        .title-10 {
            top: -50px;
            width: 120%;
            z-index: 0;
        }

        .title-10-col {
            position: relative;
            top: 50px;
        }

        .ekuso-col {
            position: relative;
            top: -150px;
        }

        .title-10-position {
            top: -61px;
        }

        /* さいあ  */
        .title-11 {
            top: -50px;
            width: 130%;
            left: -45px;
            z-index: 0;
        }

        .title-11-col {
            position: relative;
            top: 70px;
        }

        .ice-col {
            position: relative;
            top: -80px;
        }

        .title-11-position {
            top: -95px;
            padding: 45px;
        }

        /* アテナ  */
        .title-12 {
            top: -50px;
            width: 130%;
            left: -97px;
            z-index: 0;
        }

        .title-12-col {
            position: relative;
            top: 70px;
        }

        .atena-col {
            position: relative;
            top: -80px;
        }

        .title-12-position {
            top: -65px;
            padding: 35px !important;
        }


        img {
            position: relative;
            width: 100%;
        }

        .unlocked-title {
            display: none;
            padding: 45px;
        }

        .unlocked-title.selected {
            outline: 4px solid #007bff;
            border-radius: 8px;
        }
    </style>
</head>
<?php include 'templates/header.php'; ?>

<body class="pd-t">
    <main>
        <div class="container full-height-wrapper">
            <div class="w-100" style="max-width: 700px;">
                <div class="container mt-5">
                    <form method="POST" action="profile" id="titleForm" class="mb-0">
                        <div class="d-flex align-items-center p-4 rounded shadow">
                            <div class="me-4 position-relative">
                                <label for="avatar-upload" class="d-inline-block position-relative">
                                    <img
                                        src="<?php if ($user['icon_path'] !== null) : ?>img?id=<?= $user['id'] ?><?php else : ?>../assets/img/initial_icon.png<?php endif; ?>"
                                        alt="プロフィール画像"
                                        id='iconInput'
                                        class="rounded-circle border border-secondary"
                                        style="width: 96px; height: 96px; object-fit: cover; border-radius: 50%;"
                                        id="profileImage" />
                                    <input type="file" id="avatar-upload" class="d-none" accept="image/*" disabled />
                                </label>
                            </div>
                            <div style="position: relative; width: 45%; left: 60px;">
                                <h3 class="mb-1 name-font fw-bold"><?= decrypt($user['user_name']) ?></h3>
                                <hr>
                                <div class="title-<?= $user['set_title'] ?>-col">
                                    <p class="text-muted mb-0"><?php if ($user['set_title'] == null) : ?>称号：設定なし<?php else : ?>
                                    <div style="position:relative; top: -35px; z-index: -1;"><img class="title-<?= $user['set_title'] ?>" src="../assets/img/title-<?= $user['set_title'] ?>.jpg">
                                        <div class="title-box profile-title title-<?= $user['set_title'] ?>-position" data-id="<?= $user['set_title'] ?>" style="background-image: url('../assets/img/title-<?= $user['set_title'] ?>_title.jpg'); cursor: default; padding: 35px;">
                                        </div>
                                    </div><?php endif; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex" <?php if ($user['set_title'] != 0) : ?>style="position: relative; top: -56px;" <?php endif; ?>>
                            <button type="button" class="btn btn-secondary w-50 rounded-0 rounded-bottom" style="border-right: 1px solid #ccc;"
                                onclick="location.href='profile_edit'">
                                フ*ロフィール編集
                            </button>
                            <input type="hidden" name="selected_title_id" id="selectedTitleId" value="">
                            <button type="submit" class="btn btn-secondary w-50 rounded-0 rounded-bottom">
                                称号を変更
                            </button>
                        </div>
                    </form>
                </div>
                <div class="mt-4 level">獲得称号</div>
                <p>@2024 LEVEL5 INC.</p>
                <hr>
                <div class="container mt-4">
                    <div class="row g-3">
                        <!-- ループする要素 -->
                        <div class="col-6">
                            <div class="title-box locked-title" data-id="1" data-bs-toggle="popover" data-bs-content="改級スコア10000点以上">
                                <span class="title-text">？？？</span>
                            </div>
                        </div>
                        <div class="col-6 title-1-col">
                            <img class="title-1" src="../assets/img/title-1.jpg">
                            <div class="title-box unlocked-title title-1-position" data-id="1" data-bs-toggle="popover" data-bs-content="改級スコア10000点以上" style="background-image: url('../assets/img/title-1_title.jpg');">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="title-box locked-title" data-id="2" data-bs-toggle="popover" data-bs-content="改級クリア">
                                <span class="title-text">？？？</span>
                            </div>
                        </div>
                        <div class="col-6 title-2-col">
                            <img class="title-2" src="../assets/img/title-2.png">
                            <div class="title-box unlocked-title title-2-position" data-id="2" data-bs-toggle="popover" data-bs-content="改級クリア" style="background-image: url('../assets/img/title-2_title.jpg');">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="title-box locked-title" data-id="3" data-bs-toggle="popover" data-bs-content="真級10000点以上">
                                <span class="title-text">？？？</span>
                            </div>
                        </div>
                        <div class="col-6 wolf-col">
                            <img class="wolf" src="../assets/img/title-3.jpg">
                            <div class="title-box unlocked-title wolf-position" data-id="3" data-bs-toggle="popover" data-bs-content="真級10000点以上" style="background-image: url('../assets/img/title-3_title.jpg');">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="title-box locked-title" data-id="4" data-bs-toggle="popover" data-bs-content="真級クリア">
                                <span class="title-text">？？？</span>
                            </div>
                        </div>
                        <div class="col-6 title-4-col">
                            <img class="title-4" src="../assets/img/title-4.jpg">
                            <div class="title-box unlocked-title title-4-position" data-id="4" data-bs-toggle="popover" data-bs-content="真級クリア" style="background-image: url('../assets/img/title-4_title.jpg');">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="title-box locked-title" style="z-index: 20;" data-id="6" data-bs-toggle="popover" data-bs-content="絶級20000点以上">
                                <span class="title-text">？？？</span>
                            </div>
                        </div>
                        <div class="col-6 title-6-col">
                            <img class="title-6" src="../assets/img/title-6.jpg">
                            <div class="title-box unlocked-title title-6-position" data-id="6" data-bs-toggle="popover" data-bs-content="絶級10000点以上" style="background-image: url('../assets/img/title-6_title.jpg');">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="title-box locked-title" data-id="5" data-bs-toggle="popover" data-bs-content="絶級クリア">
                                <span class="title-text">？？？</span>
                            </div>
                        </div>
                        <div class="col-6 title-5-col">
                            <img class="title-5" src="../assets/img/title-5.jpg">
                            <div class="title-box unlocked-title title-5-position" data-id="5" data-bs-toggle="popover" data-bs-content="絶級クリア" style="background-image: url('../assets/img/title-5_title.jpg');">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="title-box locked-title" data-id="7" data-bs-toggle="popover" data-bs-content="改級ノーミスクリア">
                                <span class="title-text">？？？</span>
                            </div>
                        </div>
                        <div class="col-6 zago-col">
                            <img class="title-7" src="../assets/img/title-7.jpg">
                            <div class="title-box unlocked-title title-7-position" data-id="7" data-bs-toggle="popover" data-bs-content="改級ノーミスクリア" style="background-image: url('../assets/img/title-7_title.jpg');">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="title-box locked-title" data-id="8" data-bs-toggle="popover" data-bs-content="真級ノーミスクリア">
                                <span class="title-text">？？？</span>
                            </div>
                        </div>
                        <div class="col-6 dark-col">
                            <img class="title-8" src="../assets/img/title-8.png">
                            <div class="title-box unlocked-title title-8-position" data-id="8" data-bs-toggle="popover" data-bs-content="真級ノーミスクリア" style="background-image: url('../assets/img/title-8_title.jpg');">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="title-box locked-title" data-id="9" data-bs-toggle="popover" data-bs-content="絶級ノーミスクリア">
                                <span class="title-text">？？？</span>
                            </div>
                        </div>
                        <div class="col-6 rokoko-col">
                            <img class="title-9" src="../assets/img/title-9.jpg">
                            <div class="title-box unlocked-title title-9-position" data-id="9" data-bs-toggle="popover" data-bs-content="絶級ノーミスクリア" style="background-image: url('../assets/img/title-9_title.jpg');">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="title-box locked-title" data-id="10" data-bs-toggle="popover" data-bs-content="？">
                                <span class="title-text">？？？</span>
                            </div>
                        </div>
                        <div class="col-6 ekuso-col">
                            <img class="title-10" src="../assets/img/title-10.png">
                            <div class="title-box unlocked-title title-10-position" data-id="10" data-bs-toggle="popover" data-bs-content="ユーザーネームを暗黒神ダークエクソダスにする" style="background-image: url('../assets/img/title-10_title.jpg'); padding: 35px;">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="title-box locked-title" data-id="12" data-bs-toggle="popover" data-bs-content="？">
                                <span class="title-text">？？？</span>
                            </div>
                        </div>
                        <div class="col-6 atena-col">
                            <img class="title-12" src="../assets/img/title-12.png">
                            <div class="title-box unlocked-title title-12-position" data-id="12" data-bs-toggle="popover" data-bs-content="改級60000点以上" style="background-image: url('../assets/img/title-12_title.jpg');">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="title-box locked-title" data-id="11" data-bs-toggle="popover" data-bs-content="？">
                                <span class="title-text">？？？</span>
                            </div>
                        </div>
                        <div class="col-6 ice-col">
                            <img class="title-11" src="../assets/img/title-11.png">
                            <div class="title-box unlocked-title title-11-position" data-id="11" data-bs-toggle="popover" data-bs-content="100回プレイする" style="background-image: url('../assets/img/title-11_title.jpg'); z-index: 2;">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="my-5 fw-bold"><a href="/top" style="text-decoration: none; color:rgba(0, 123, 255, 0.6);">トップに戻る</a></div>
            </div>
        </div>
    </main>
    <div class="modal fade" id="getTitleModal" tabindex="-1" aria-labelledby="getTitleLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="getTitleLabel">🎉 称号獲得！</h5>
                </div>
                <div class="modal-body">
                    新しい称号を獲得しました！
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

    <div class="image-left"></div>
    <div class="image-right"></div>

    <?php include 'templates/footer.php' ?>
    <?php include 'templates/script.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php if (isset($_GET['get'])): ?>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const modal = new bootstrap.Modal(document.getElementById('getTitleModal'));
                modal.show();
            });
        </script>
    <?php endif; ?>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const unlockedIds = <?php echo json_encode($unlocked_ids); ?>.map(Number);

            // 表示・非表示の切り替え
            const lockedElements = document.querySelectorAll('.locked-title');
            const unlockedElements = document.querySelectorAll('.unlocked-title');

            lockedElements.forEach(lockedEl => {
                const id = Number(lockedEl.dataset.id);
                const lockedParent = lockedEl.closest('.col-6');

                if (unlockedIds.includes(id)) {
                    if (lockedParent) lockedParent.remove();

                    unlockedElements.forEach(unlockedEl => {
                        if (Number(unlockedEl.dataset.id) === id) {
                            unlockedEl.style.display = 'block';
                            const unlockedParent = unlockedEl.closest('.col-6');
                            if (unlockedParent) unlockedParent.style.display = 'block';
                        }
                    });
                } else {
                    if (lockedParent) lockedParent.style.display = 'block';
                    unlockedElements.forEach(unlockedEl => {
                        const unlockedParent = unlockedEl.closest('.col-6');
                        if (Number(unlockedEl.dataset.id) === id && unlockedParent) {
                            unlockedParent.style.display = 'none';
                        }
                    });
                }
            });

            // ポップオーバー初期化
            const unlockedTitles = document.querySelectorAll('.unlocked-title');
            unlockedTitles.forEach(el => {
                new bootstrap.Popover(el, {
                    trigger: 'manual',
                    placement: 'top',
                });
            });
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(function(el) {
                return new bootstrap.Popover(el, {
                    trigger: 'click',
                    placement: 'top'
                });
            });

            // 画像クリックでポップオーバーを表示
            const images = document.querySelectorAll('img[class^="title-"], img.wolf');
            images.forEach(img => {
                img.style.cursor = 'pointer';
                img.addEventListener('click', () => {
                    const classList = Array.from(img.classList);
                    const match = classList.find(c => c.startsWith('title-') || c === 'wolf');
                    if (!match) return;

                    const id = match === 'wolf' ? '3' : match.split('-')[1];

                    // すべてのポップオーバーを閉じる
                    unlockedTitles.forEach(t => {
                        const instance = bootstrap.Popover.getInstance(t);
                        if (instance) instance.hide();
                    });

                    // 対象の title-box にポップを表示
                    const target = document.querySelector(`.unlocked-title[data-id="${id}"]`);
                    if (target) {
                        const pop = bootstrap.Popover.getInstance(target);
                        if (pop) pop.toggle();
                    }
                    hiddenInput.value = id;
                });
            });

            // 外部クリックでポップオーバーを閉じる
            document.addEventListener('click', function(e) {
                const popovers = document.querySelectorAll('.unlocked-title,.locked-title');
                popovers.forEach(el => {
                    const popover = bootstrap.Popover.getInstance(el);
                    if (
                        popover &&
                        !el.contains(e.target) &&
                        !document.querySelector('.popover')?.contains(e.target)
                    ) {
                        popover.hide();
                    }
                });
            });

            // タイトル選択チェック（未選択なら警告）
            const titleForm = document.getElementById('titleForm');
            const hiddenInput = document.getElementById('selectedTitleId');
            if (titleForm) {
                titleForm.addEventListener('submit', (e) => {
                    if (!hiddenInput.value) {
                        e.preventDefault();
                        alert('称号が未選択です。クリックすると選択状態になります。');
                    }
                });
            }
        });
    </script>
</body>

</html>