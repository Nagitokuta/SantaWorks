<?php
$meta_page_title = 'login';

if (isset($_GET['p'])) {
    $reffer_path = $_GET['p'];
} else {
    $reffer_path = '/top';
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $user_name = '';
    $password = '';
} else {
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if ($password == '') {
        $err['password'] = 'パスワードを入力して下さい。';
    } else {

        $sql = "SELECT * FROM user WHERE user_name = :user_name LIMIT 1";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':user_name', encrypt($user_name), PDO::PARAM_STR);

        $stmt->execute();

        $password_check = $stmt->fetch();

        if (!$password_check || !password_verify($password, $password_check['password'])) {
            $err['password'] = 'ログインできませんでした。';
        }
    }

    if (empty($err)) {
        $user = $password_check;
        $_SESSION['USER'] = $user;

        session_regenerate_id(true);
        $_SESSION['USER'] = $user;

        redirect($reffer_path);
        exit;
    }
    unset($pdo);
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
            /* background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 30' preserveAspectRatio='none'%3E%3Cpath d='M0,0 C150,40 350,0 500,20 C650,40 800,10 1000,30 C1100,20 1200,0 1200,0 L1200,30 L0,30 Z' style='fill: %23a8d8ea;'/%3E%3C/svg%3E") repeat-x; */
        }

        .full-height-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem 1rem;
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

        @media (max-height: 800px) {
            .title-color {
                position: relative;
                top: -25px;
            }

            .title-color.mb-4 {
                margin-bottom: 0;
            }

            h2.mb-4 {
                margin-bottom: 0 !important;
            }

            .otaku-title.mb-4 {
                margin-bottom: -0.5rem !important;
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
            font-family: "WDXL Lubrifont TC", sans-serif;
            border-radius: 50px;
            font-size: 1.2rem;
            padding: 0.75rem 1.5rem;
            background-color: rgb(202, 189, 144);
            border-color: rgb(165, 154, 112);
        }

        .btn:hover {
            background-color: rgb(165, 154, 112);
            border-color: rgb(151, 142, 106);
        }

        .title-color {
            font-family: "WDXL Lubrifont TC", sans-serif;
            font-weight: 400;
            font-style: normal;
            font-size: 36px;
        }

        .py {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }

        .px {
            padding-right: 3rem !important;
            padding-left: 3rem !important;
        }
    </style>
</head>
<?php include 'templates/header.php'; ?>

<body class="pd-t">
    <main>
        <div class="container full-height-wrapper">
            <div class="w-100" style="max-width: 700px;">
                <div class="otaku-title mb-4">OtakuType</div>
                <div class="row justify-content-center text-center">
                    <div class="card px py col-lg-8 col-md-6 mt">
                        <div class="panel-body">
                            <h2 class="mb-4 title-color">ログイン</h2>
                            <form method="POST">
                                <div class="mb-3">
                                    <input type="text" class="form-control" name="user_name" value="<?= $user_name ?>" placeholder="ユーザーネーム" required />
                                </div>

                                <div class="b3rem" style="position: relative; display: inline-block; width: 100%; margin-bottom: 1rem;">
                                    <!-- 入力フォーム -->
                                    <input type="password"
                                        class="form-control <?php if (isset($err['password'])) echo 'is-invalid'; ?>"
                                        id="password"
                                        name="password"
                                        value="<?= $password ?>"
                                        placeholder="パスワード"
                                        style="padding-right: 40px;" /> <!-- アイコンのスペース確保 -->
                                    <!-- エラーメッセージ -->
                                    <span class="invalid-feedback" style="display: block; min-height: 1rem;">
                                        <?php if (isset($err['password'])) : ?>
                                            <?= $err['password']; ?>
                                        <?php endif; ?>
                                    </span>
                                    <!-- 目のアイコン -->
                                    <i class="fas fa-eye" id="togglePassword"
                                        style="position: absolute; top: 33%; right: 5px; transform: translateY(-50%); cursor: pointer; color: gray; background-color: white;padding: 5px;border-radius: 50%;">
                                    </i>
                                </div>
                                <div>
                                    <input type="submit" value="ログイン" class="btn btn-primary rounded-pill w-50" />
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="mt-5 fw-bold"><a href="/signup" style="text-decoration: none; color:rgba(0, 123, 255, 0.6);">新規登録（アカウントをお持ちでない方）</a></div>
                    <div class="mt-3 fw-bold"><a href="/top" style="text-decoration: none; color:rgba(0, 123, 255, 0.6);">トップに戻る</a></div>
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
        // アイコンをクリックしてパスワードの伏字を切り替える
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const isPassword = passwordField.type === 'password';

            // フィールドのタイプを切り替える
            passwordField.type = isPassword ? 'text' : 'password';

            // アイコンを切り替える
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    </script>
</body>

</html>