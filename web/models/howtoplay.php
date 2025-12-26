<?php
$meta_page_title = 'top';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['difficulty'])) {
        header('Location:/top');
        exit;
    }
    $difficulty = $_POST['difficulty'];
    if ($difficulty == 'easy') {
        header('Location:/main?d=1');
        exit;
    } elseif ($difficulty == 'normal') {
        header('Location:/main?d=2');
        exit;
    } elseif ($difficulty == 'hard') {
        header('Location:/main?d=3');
        exit;
    }
}
unset($_SESSION['play_count_added']);
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
            .how {
                font-size: 40px !important;
            }

            .h {
                font-size: 20px !important;
            }
        }

        .how {
            font-family: "WDXL Lubrifont TC", sans-serif;
            font-size: 60px;
        }

        .h {
            font-family: "WDXL Lubrifont TC", sans-serif;
            font-size: 30px;
        }
    </style>
</head>
<?php include 'templates/header.php'; ?>

<body class="pd-t">
    <main>
        <div class="container full-height-wrapper">
            <div class="w-100" style="max-width: 700px;">
                <div class="card p-4">
                    <div class="how">遊び方</div>
                    <hr style="margin: 0;">
                    <div class="h mt-3 mb-1">ゲームルール</div>
                    <div>OtakuTypeは高スコアを目指してタイピングをするゲームです。</div>
                    <div>スコアは、1文字正しく入力するごとに +50 点、ミスタイプするごとに −20 点という基準で計算されます。</div>
                    <div>お題はランダムに出題されます。</div>
                    <div>制限時間は60秒で50問すべてを時間内に入力できるとクリアになります。</div>
                    <div>お題をすべて正確に打てると制限時間に秒数が追加されます。</div>
                    <div>["tsu"と"tu"]、["ji"と"zi"]、などキーが違くても同じ文字になるものはどちらでも正解とします。</div>
                    <div class="h mt-2 mb-1">難易度</div>
                    <div>改級はタイプ数12文字以内、真級は19文字以内、絶級はそれ以上という基準です。</div>
                    <div>難易度が変わると、追加秒数が変わります。</div>
                    <div>難易度ごとにリザルトでのクラス分け、コメントが変わります。</div>
                    <div class="h mt-2 mb-1">ユーザー登録</div>
                    <div>ユーザー登録をすると、ランキングに参加、称号取得ができるようになります。</div>
                    <div>トップ画面のプロフィールボタン、または右上のユーザーアイコンから登録できます。</div>
                    <div>ユーザーネームとパスワードだけなのでぜひ登録して100%ゲームを楽しんでください！</div>
                    <div class="mb-2">※ユーザーネームをエンシャントダークのキャプテンの化身にすると何かが起こるカモ...</div>
                </div>
                <div class="mt-5 fw-bold"><a href="/top" style="text-decoration: none; color:rgba(0, 123, 255, 0.6);">トップに戻る</a></div>
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