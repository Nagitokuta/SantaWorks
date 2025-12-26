<?php
$meta_page_title = 'ranking';

if (isset($_GET['d'])) {
    $d = $_GET['d'];
    if ($d == 'ea') {
        $d = 1;
        $sql = "SELECT s.user_id, s.score, s.correct, s.miss, s.avg, u.user_name, u.icon_path, u.set_title 
        FROM score AS s
        JOIN user AS u ON s.user_id = u.id
        WHERE s.d_id = :d_id ORDER BY s.score DESC LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':d_id', $d, PDO::PARAM_INT);
        $stmt->execute();
        $rank_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($d == 'no') {
        $d = 2;
        $sql = "SELECT s.user_id, s.score, s.correct, s.miss, s.avg, u.user_name, u.icon_path, u.set_title 
        FROM score AS s
        JOIN user AS u ON s.user_id = u.id
        WHERE s.d_id = :d_id ORDER BY s.score DESC LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':d_id', $d, PDO::PARAM_INT);
        $stmt->execute();
        $rank_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } elseif ($d == 'ha') {
        $d = 3;
        $sql = "SELECT s.user_id, s.score, s.correct, s.miss, s.avg, u.user_name, u.icon_path, u.set_title 
        FROM score AS s
        JOIN user AS u ON s.user_id = u.id
        WHERE s.d_id = :d_id ORDER BY s.score DESC LIMIT 10";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':d_id', $d, PDO::PARAM_INT);
        $stmt->execute();
        $rank_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    header('Location:/top');
    exit;
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

        .note {
            display: none;
        }

        @media(max-height :800px) {
            .note {
                text-decoration: none;
                border: none;
                display: block !important;
            }

            .difficulty-image {
                width: 20% !important;
            }

            .level {
                font-size: 20px;
            }
        }

        .difficulty-image {
            width: 30%;
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

        /* ゴッドハンド */
        .title-1 {
            top: 48px;
            width: 165%;
            left: -72px;
        }

        .title-1-col {
            top: -33px;
        }

        .title-1-position {
            top: -14px;
        }

        /* ゴッドノウズ */
        .title-2 {
            top: 53px;
        }

        .title-2-col {
            top: -75px;
        }

        .title-2-position {
            top: 34px;
        }

        /* ウルフレジェンド*/
        .title-3 {
            top: 48px;
        }

        .title-3-col {
            top: -125px;
        }

        .title-3-position {
            top: 40px;
        }

        /* 流星ブレード*/
        .title-4 {
            top: 50px;
            width: 132%;
            left: -34px;
            z-index: -1;
        }

        .title-4-col {
            top: -125px;
        }

        .title-4-position {
            top: 22px;
        }

        /* ぶろっこり  */
        .title-5 {
            top: 38px;
            width: 257%;
            left: -245px;
            z-index: -5 !important;
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
            z-index: -3 !important;
        }

        .title-6-col {
            top: -66px;
        }

        .title-6-position {
            top: -30px;
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
            top: 70px;
        }

        .title-8-position {
            top: -61px;
            z-index: 10;
        }

        .dark-col {
            top: 25px;
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
            top: -3px;
        }

        /* 暗黒  */
        .title-10 {
            top: -50px;
            width: 120%;
            z-index: 0;
        }

        .title-10-col {
            position: relative;
            top: 82px !important;
        }

        .ekuso-col {
            position: relative;
            top: -150px;
        }

        .title-10-position {
            top: -61px;
            padding: 0 !important;
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
            padding: 0px !important;
        }

        img {
            position: relative;
            width: 100%;
        }

        .unlocked-title {
            display: none;
        }

        .unlocked-title.selected {
            outline: 4px solid #007bff;
            border-radius: 8px;
        }

        .score {
            color: #000;
            font-family: "WDXL Lubrifont TC", sans-serif;
            font-size: 50px;
        }

        .rank_font {
            font-family: "WDXL Lubrifont TC", sans-serif;
            font-size: 80px;
        }

        .top-font {
            display: inline-block;
            font-weight: bold;
            font-size: 80px;
            color: #ffd700;
            -webkit-text-fill-color: transparent;
            animation: shimmer 2s infinite linear;
            text-shadow:
                -1px -1px 0 #000,
                1px -1px 0 #000,
                -1px 1px 0 #000,
                1px 1px 0 #000;
        }

        footer {
            margin-top: 45px;
        }


        @keyframes shimmer {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 200% 50%;
            }
        }
    </style>
</head>
<?php include 'templates/header.php'; ?>

<body class="pd-t">
    <main>
        <div class="container full-height-wrapper">
            <div class="w-100" style="max-width: 700px;">
                <div class="container mt-5">
                    <div class="level-font" style="display: flex; align-items: center; justify-content: center; gap: 4px; z-index: 2;
            position: relative;
            left: -20px;">
                        <?php if ($d == 1) : ?> <?php elseif ($d == 2) : ?>
                            <a href="ranking?d=ea" class="note"><span class="level me-5">＜</span></a>
                        <?php else : ?>
                            <a href="ranking?d=no" class="note"><span class="level me-5">＜</span></a>
                        <?php endif; ?>
                        <span class="level">ランキング</span>
                        <?php if ($d == 'ALL') : ?><span class="level">すべて</span>
                        <?php elseif ($d == 1) : ?>
                            <img src="../assets/img/kai.png" alt="初級" class="difficulty-image">
                            <span class="level">級</span>
                        <?php elseif ($d == 2) : ?>
                            <img src="../assets/img/sin.png" alt="中級" class="difficulty-image">
                            <span class="level">級</span>
                        <?php else : ?>
                            <img src="../assets/img/zetu.png" alt="上級" class="difficulty-image">
                            <span class="level">級</span>
                        <?php endif; ?>
                        <?php if ($d == 1) : ?>
                            <a href="ranking?d=no" class="note"><span class="level ms-5">＞</span></a>
                        <?php elseif ($d == 2) : ?>
                            <a href="ranking?d=ha" class="note"><span class="level ms-5">＞</span></a>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <?php foreach ($rank_users as $index => $rank_user) : ?>
                        <div class="row">
                            <div class="col-2 d-flex align-items-center justify-content-center">
                                <div class="rank_font <?php if ($index == 0) : ?>top-font<?php elseif ($index == 1) : ?>second-font<?php elseif ($index == 2) : ?>third-font<?php else : ?><?php endif; ?>"><?= $index + 1 ?>位</div>
                            </div>
                            <div class="col-10">
                                <div class="d-flex align-items-center p-4 rounded shadow">
                                    <div class="me-4 position-relative">
                                        <label for="avatar-upload" class="d-inline-block position-relative">
                                            <img
                                                src="<?php if (isset($rank_user['icon_path']) && $rank_user['icon_path'] !== null) : ?>img?id=<?= $rank_user['user_id'] ?><?php else : ?>../assets/img/initial_icon.png<?php endif; ?>"
                                                alt="プロフィール画像"
                                                id='iconInput'
                                                class="rounded-circle border border-secondary"
                                                style="width: 96px; height: 96px; object-fit: cover; border-radius: 50%;"
                                                id="profileImage" />
                                            <input type="file" id="avatar-upload" class="d-none" accept="image/*" disabled />
                                        </label>
                                    </div>
                                    <div style="position: relative; width: 45%; left: 60px;">
                                        <h3 class="mb-1 name-font fw-bold"><?= decrypt($rank_user['user_name']) ?></h3>
                                        <hr>
                                        <div class="title-<?= $rank_user['set_title'] ?>-col">
                                            <p class="text-muted mb-0"><?php if ($rank_user['set_title'] == null) : ?>称号：設定なし<?php else : ?>
                                            <div style="position:relative; top: -35px;"><img class="title-<?= $rank_user['set_title'] ?>" src="../assets/img/title-<?= $rank_user['set_title'] ?>.jpg">
                                                <div class="title-box profile-title title-<?= $rank_user['set_title'] ?>-position" data-id="<?= $rank_user['set_title'] ?>" style="background-image: url('../assets/img/title-<?= $rank_user['set_title'] ?>_title.jpg'); cursor: default; padding: 35px;">
                                                </div>
                                            </div><?php endif; ?></p>
                                        </div>
                                        <div class="my-3 p-3 rounded" style="background-color: #fff; border: 1px solid #ccc;">
                                            <div class="row text-center">
                                                <div class="col">
                                                    <div style="font-weight: bold;">正確</div>
                                                    <div><?= $rank_user['correct'] ?>回</div>
                                                </div>
                                                <div class="col">
                                                    <div style="font-weight: bold;">平均</div>
                                                    <div><?= $rank_user['avg'] ?>回/秒</div>
                                                </div>
                                                <div class="col">
                                                    <div style="font-weight: bold;">ミス</div>
                                                    <div><?= $rank_user['miss'] ?>回</div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="score"><?= $rank_user['score'] ?>点</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    <?php endforeach; ?>
                    <div class="my-5 fw-bold"><a href="/top" style="text-decoration: none; color:rgba(0, 123, 255, 0.6);">トップに戻る</a></div>
                </div>
            </div>
        </div>
    </main>

    <div class="image-left"></div>
    <div class="image-right"></div>

    <?php include 'templates/footer.php' ?>
    <?php include 'templates/script.php' ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    </script>
</body>

</html>