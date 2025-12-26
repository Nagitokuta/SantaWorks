<?php
$meta_page_title = 'result';
$get_title = false;
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $score = $_POST['score'] ?? 0;
    $d = $_POST['d'] ?? 1;
    $correct = $_POST['correct'] ?? 0;
    $miss = $_POST['miss'] ?? 0;
    $avg = $_POST['avg'] ?? 0.0;
    $allClearNoMiss = $_POST['allClearNoMiss'] ?? false;
    $remainingCount = $_POST['remainingCount'];
    $havetime = $_POST['remainingTime'];

    $havetime *= 500;
    $score += $havetime;

    $hyakutext = '';

    if (isset($_SESSION['USER']) && !isset($_SESSION['play_count_added'])) {
        $id = $_SESSION['USER']['id'];
        //古いプレイ数取得
        $sql = "SELECT play_count FROM user WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $play_count = $stmt->fetchColumn();

        $added_play_count = $play_count + 1;

        if ($play_count == 99) {
            $id = $_SESSION['USER']['id'];
            $sql = "SELECT id FROM with_title WHERE user_id = :user_id AND title_id = :title_id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':title_id', 11, PDO::PARAM_INT);
            $stmt->execute();
            $existence = $stmt->fetchColumn();

            if ($existence == false) {
                $sql = "INSERT INTO with_title
    (user_id, title_id)
    VALUES
    (:user_id, :title_id)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                $stmt->bindValue(':title_id', 11, PDO::PARAM_INT);
                $stmt->execute();
                $get_title = true;
                $hyakutext = 'アイシクルロード';
            }
        }

        //+1したのを上書き
        $sql = "UPDATE user
    SET play_count = :play_count WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':play_count', $added_play_count, PDO::PARAM_INT);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $_SESSION['play_count_added'] = true;
    }

    if ($d == 1) {
        if ($score <= 4000) {
            $class = '栗松';
            $comment = '帰国の準備をしろ';
        } elseif ($score <= 7000) {
            $class = '染岡';
            $comment = '自分の力で這い上がってこい！！';
        } elseif ($score <= 9999) {
            $class = '豪炎寺';
            $comment = 'やる気がないフ*レーだけは絶対に許さない！';
        } elseif ($cleared = isset($_POST['cleared']) && $_POST['cleared'] == 'true') {
            $class = 'アフロディ';
            $comment = '来るがいい！雷門中サッカー部…！ いや… …イナズマイレブンよ！';
            if (isset($_SESSION['USER'])) {
                if ($allClearNoMiss === true) {
                    $class = 'オーガ';
                    $comment = 'フェーズ１… コンプリート。';
                    //ハイボルテージ
                    $id = $_SESSION['USER']['id'];
                    $sql = "SELECT id FROM with_title WHERE user_id = :user_id AND title_id = :title_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                    $stmt->bindValue(':title_id', 7, PDO::PARAM_INT);
                    $stmt->execute();
                    $existence = $stmt->fetchColumn();

                    if ($existence == false) {
                        $sql = "INSERT INTO with_title
    (user_id, title_id)
    VALUES
    (:user_id, :title_id)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                        $stmt->bindValue(':title_id', 7, PDO::PARAM_INT);
                        $stmt->execute();
                        $get_title = true;
                        $text = 'ハイボルテージ';
                    }
                } else {
                    if ($score >= 60000) {
                        $class = 'ベータ';
                        $comment = '見せてやるぜ！真の絶望って奴をな！！';
                        //シュートコマンドK02
                        if (isset($_SESSION['USER'])) {
                            $id = $_SESSION['USER']['id'];
                            $sql = "SELECT id FROM with_title WHERE user_id = :user_id AND title_id = :title_id";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                            $stmt->bindValue(':title_id', 12, PDO::PARAM_INT);
                            $stmt->execute();
                            $existence = $stmt->fetchColumn();

                            if ($existence == false) {
                                $sql = "INSERT INTO with_title
    (user_id, title_id)
    VALUES
    (:user_id, :title_id)";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                                $stmt->bindValue(':title_id', 12, PDO::PARAM_INT);
                                $stmt->execute();
                                $get_title = true;
                                $text = 'シュートコマンドK02';
                            }
                        }
                    } else {
                        //ゴッドノウズ
                        $id = $_SESSION['USER']['id'];
                        $sql = "SELECT id FROM with_title WHERE user_id = :user_id AND title_id = :title_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                        $stmt->bindValue(':title_id', 2, PDO::PARAM_INT);
                        $stmt->execute();
                        $existence = $stmt->fetchColumn();

                        if ($existence == false) {
                            $sql = "INSERT INTO with_title
    (user_id, title_id)
    VALUES
    (:user_id, :title_id)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                            $stmt->bindValue(':title_id', 2, PDO::PARAM_INT);
                            $stmt->execute();
                            $get_title = true;
                            $text = 'ゴッドノウズ';
                        } else {
                            //ゴッドハンド
                            $id = $_SESSION['USER']['id'];
                            $sql = "SELECT id FROM with_title WHERE user_id = :user_id AND title_id = :title_id";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                            $stmt->bindValue(':title_id', 1, PDO::PARAM_INT);
                            $stmt->execute();
                            $existence = $stmt->fetchColumn();

                            if ($existence == false) {
                                $sql = "INSERT INTO with_title
    (user_id, title_id)
    VALUES
    (:user_id, :title_id)";
                                $stmt = $pdo->prepare($sql);
                                $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                                $stmt->bindValue(':title_id', 1, PDO::PARAM_INT);
                                $stmt->execute();
                                $get_title = true;
                                $text = 'ゴッドハンド';
                            }
                        }
                    }
                }
            }
        } elseif ($score >= 10000 && $get_title === false) {
            $class = '円堂';
            $comment = '練習はおにぎりだ！';

            //ゴッドハンド
            if (isset($_SESSION['USER'])) {
                //同じ行は一度のみ
                $id = $_SESSION['USER']['id'];
                $sql = "SELECT id FROM with_title WHERE user_id = :user_id AND title_id = :title_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                $stmt->bindValue(':title_id', 1, PDO::PARAM_INT);
                $stmt->execute();
                $existence = $stmt->fetchColumn();

                if ($existence == false) {
                    $sql = "INSERT INTO with_title
    (user_id, title_id)
    VALUES
    (:user_id, :title_id)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                    $stmt->bindValue(':title_id', 1, PDO::PARAM_INT);
                    $stmt->execute();
                    $get_title = true;
                    $text = 'ゴッドハンド';
                }
            }
        }
        if (isset($_SESSION['USER']['id'])) {
            //ハイスコア挿入
            $id = $_SESSION['USER']['id'];
            $sql = "SELECT score FROM score WHERE user_id = :user_id AND d_id = :d_id ORDER BY score DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':d_id', 1, PDO::PARAM_INT);
            $stmt->execute();
            $old_score = $stmt->fetchColumn();

            if ($old_score == false) {
                $sql = "INSERT INTO score
                (user_id, d_id, score, correct, miss, avg, havetime)
                VALUES
                (:user_id, :d_id, :score, :correct, :miss, :avg, :havetime)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                $stmt->bindValue(':d_id', 1, PDO::PARAM_INT);
                $stmt->bindValue(':score', $score, PDO::PARAM_INT);
                $stmt->bindValue(':correct', $correct, PDO::PARAM_STR);
                $stmt->bindValue(':miss', $miss, PDO::PARAM_STR);
                $stmt->bindValue(':avg', $avg, PDO::PARAM_STR);
                $stmt->bindValue(':havetime', $havetime, PDO::PARAM_STR);
                $stmt->execute();
                $highscore = true;
            } elseif ($old_score < $score) {
                $sql = "UPDATE score SET score = :score, correct = :correct, miss = :miss,  avg = :avg, havetime = :havetime WHERE user_id = :user_id AND d_id = :d_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':score', $score, PDO::PARAM_INT);
                $stmt->bindValue(':correct', $correct, PDO::PARAM_STR);
                $stmt->bindValue(':miss', $miss, PDO::PARAM_STR);
                $stmt->bindValue(':avg', $avg, PDO::PARAM_STR);
                $stmt->bindValue(':havetime', $havetime, PDO::PARAM_STR);
                $stmt->bindValue(':user_id', $_SESSION['USER']['id'], PDO::PARAM_INT);
                $stmt->bindValue(':d_id', 1, PDO::PARAM_INT);
                $stmt->execute();
                $highscore = true;
            }
        }
    } elseif ($d == 2) {

        if ($score <= 7000) {
            $class = 'お日様';
            $comment = 'みんなと仲良くしましょう！';
        } elseif ($score <= 10000) {
            $class = 'イプシロン';
            $comment = '勝負とは 辛く険しく、そして厳しいものなのだ！';
        } elseif ($score <= 14999) {
            $class = 'ジェネシス';
            $comment = 'やっぱ、ザコはザコだっポ～!';
        } elseif ($cleared = isset($_POST['cleared']) && $_POST['cleared'] == 'true') {
            $class = 'グラン';
            $comment = '好きだよ円堂くん、君のその目!!';
            if (isset($_SESSION['USER'])) {
                if ($allClearNoMiss === true) {
                    //ダークフェニックス
                    $id = $_SESSION['USER']['id'];
                    $sql = "SELECT id FROM with_title WHERE user_id = :user_id AND title_id = :title_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                    $stmt->bindValue(':title_id', 8, PDO::PARAM_INT);
                    $stmt->execute();
                    $existence = $stmt->fetchColumn();

                    if ($existence == false) {
                        $sql = "INSERT INTO with_title
    (user_id, title_id)
    VALUES
    (:user_id, :title_id)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                        $stmt->bindValue(':title_id', 8, PDO::PARAM_INT);
                        $stmt->execute();
                        $get_title = true;
                        $text = 'ダークフェニックス';
                    }
                } else {
                    //流星ブレード
                    $id = $_SESSION['USER']['id'];
                    $sql = "SELECT id FROM with_title WHERE user_id = :user_id AND title_id = :title_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                    $stmt->bindValue(':title_id', 4, PDO::PARAM_INT);
                    $stmt->execute();
                    $existence = $stmt->fetchColumn();

                    if ($existence == false) {
                        $sql = "INSERT INTO with_title
    (user_id, title_id)
    VALUES
    (:user_id, :title_id)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                        $stmt->bindValue(':title_id', 4, PDO::PARAM_INT);
                        $stmt->execute();
                        $get_title = true;
                        $text = '流星ブレード';
                    } else {
                        $sql = "SELECT id FROM with_title WHERE user_id = :user_id AND title_id = :title_id";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                        $stmt->bindValue(':title_id', 3, PDO::PARAM_INT);
                        $stmt->execute();
                        $existence = $stmt->fetchColumn();

                        if ($existence == false) {
                            $sql = "INSERT INTO with_title
    (user_id, title_id)
    VALUES
    (:user_id, :title_id)";
                            $stmt = $pdo->prepare($sql);
                            $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                            $stmt->bindValue(':title_id', 3, PDO::PARAM_INT);
                            $stmt->execute();
                            $get_title = true;
                            $text = 'ウルフレジェンド';
                        }
                    }
                }
            }
        } elseif ($score >= 15000) {
            $class = 'ダークエンペラーズ';
            $comment = '強さにこそ意味があるでやんすよ';

            //ウルフレジェンド
            if (isset($_SESSION['USER'])) {
                $id = $_SESSION['USER']['id'];
                $sql = "SELECT id FROM with_title WHERE user_id = :user_id AND title_id = :title_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                $stmt->bindValue(':title_id', 3, PDO::PARAM_INT);
                $stmt->execute();
                $existence = $stmt->fetchColumn();

                if ($existence == false) {
                    $sql = "INSERT INTO with_title
    (user_id, title_id)
    VALUES
    (:user_id, :title_id)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                    $stmt->bindValue(':title_id', 3, PDO::PARAM_INT);
                    $stmt->execute();
                    $get_title = true;
                    $text = 'ウルフレジェンド';
                }
            }
        }
        if (isset($_SESSION['USER']['id'])) {
            //ハイスコア挿入
            $id = $_SESSION['USER']['id'];
            $sql = "SELECT score FROM score WHERE user_id = :user_id AND d_id = :d_id ORDER BY score DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':d_id', 2, PDO::PARAM_INT);
            $stmt->execute();
            $old_score = $stmt->fetchColumn();

            if ($old_score == false) {
                $sql = "INSERT INTO score
                (user_id, d_id, score, correct, miss, avg, havetime)
                VALUES
                (:user_id, :d_id, :score, :correct, :miss, :avg, :havetime)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                $stmt->bindValue(':d_id', 2, PDO::PARAM_INT);
                $stmt->bindValue(':score', $score, PDO::PARAM_INT);
                $stmt->bindValue(':correct', $correct, PDO::PARAM_STR);
                $stmt->bindValue(':miss', $miss, PDO::PARAM_STR);
                $stmt->bindValue(':avg', $avg, PDO::PARAM_STR);
                $stmt->bindValue(':havetime', $havetime, PDO::PARAM_STR);
                $stmt->execute();
                $highscore = true;
            } elseif ($old_score < $score) {
                $sql = "UPDATE score SET score = :score, correct = :correct, miss = :miss,  avg = :avg, havetime = :havetime WHERE user_id = :user_id AND d_id = :d_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':score', $score, PDO::PARAM_INT);
                $stmt->bindValue(':correct', $correct, PDO::PARAM_STR);
                $stmt->bindValue(':miss', $miss, PDO::PARAM_STR);
                $stmt->bindValue(':avg', $avg, PDO::PARAM_STR);
                $stmt->bindValue(':havetime', $havetime, PDO::PARAM_STR);
                $stmt->bindValue(':user_id', $_SESSION['USER']['id'], PDO::PARAM_INT);
                $stmt->bindValue(':d_id', 2, PDO::PARAM_INT);
                $stmt->execute();
                $highscore = true;
            }
        }
    } else {
        if ($score <= 10000) {
            $class = 'エドガー';
            $comment = 'あかん！やめて！エドガーーーーー！！';
        } elseif ($score <= 14000) {
            $class = 'フィディオ';
            $comment = '新たな最強はいつも俺たちの中にある！';
        } elseif ($score <= 19999) {
            $class = 'ワック・ロニージョ';
            $comment = '裕福なお前らには俺たちの気持ちが解らないダロ！';
        } elseif ($cleared = isset($_POST['cleared']) && $_POST['cleared'] == 'true') {
            $class = '剣城優一';
            $comment = '京介、シュートだ！';

            if ($allClearNoMiss === true) {
                $class = 'ロココ';
                $comment = 'マモルは『Ｘフ’’ラスト』を止めた！負けられない！マモルに・・・<br>“イナズマジャパン”に、勝ちたい！！';
                //タマシイ・ザ・ハンド
                $id = $_SESSION['USER']['id'];
                $sql = "SELECT id FROM with_title WHERE user_id = :user_id AND title_id = :title_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                $stmt->bindValue(':title_id', 9, PDO::PARAM_INT);
                $stmt->execute();
                $existence = $stmt->fetchColumn();

                if ($existence == false) {
                    $sql = "INSERT INTO with_title
    (user_id, title_id)
    VALUES
    (:user_id, :title_id)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                    $stmt->bindValue(':title_id', 9, PDO::PARAM_INT);
                    $stmt->execute();
                    $get_title = true;
                    $text = 'タマシイ・ザ・ハンド';
                }
            } else {
                //無頼ハンド
                $id = $_SESSION['USER']['id'];
                $sql = "SELECT id FROM with_title WHERE user_id = :user_id AND title_id = :title_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                $stmt->bindValue(':title_id', 5, PDO::PARAM_INT);
                $stmt->execute();
                $existence = $stmt->fetchColumn();

                if ($existence == false) {
                    $sql = "INSERT INTO with_title
    (user_id, title_id)
    VALUES
    (:user_id, :title_id)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                    $stmt->bindValue(':title_id', 5, PDO::PARAM_INT);
                    $stmt->execute();
                    $get_title = true;
                    $text = '無頼ハンド';
                } else {
                    $sql = "SELECT id FROM with_title WHERE user_id = :user_id AND title_id = :title_id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                    $stmt->bindValue(':title_id', 6, PDO::PARAM_INT);
                    $stmt->execute();
                    $existence = $stmt->fetchColumn();
                    if ($existence == false) {
                        $sql = "INSERT INTO with_title
    (user_id, title_id)
    VALUES
    (:user_id, :title_id)";
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                        $stmt->bindValue(':title_id', 6, PDO::PARAM_INT);
                        $stmt->execute();
                        $get_title = true;
                        $text = 'ディザスターブレイク';
                    }
                }
            }
        } else {
            $class = 'ザナーク';
            $comment = '弱い奴ほどよく吠える、俺は強いがよく吠える';
            //ディザスターブレイク
            if (isset($_SESSION['USER'])) {
                $id = $_SESSION['USER']['id'];
                $sql = "SELECT id FROM with_title WHERE user_id = :user_id AND title_id = :title_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                $stmt->bindValue(':title_id', 6, PDO::PARAM_INT);
                $stmt->execute();
                $existence = $stmt->fetchColumn();

                if ($existence == false) {
                    $sql = "INSERT INTO with_title
    (user_id, title_id)
    VALUES
    (:user_id, :title_id)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                    $stmt->bindValue(':title_id', 6, PDO::PARAM_INT);
                    $stmt->execute();
                    $get_title = true;
                    $text = 'ディザスターブレイク';
                }
            }
        }


        if (isset($_SESSION['USER']['id'])) {
            //ハイスコア挿入
            $id = $_SESSION['USER']['id'];
            $sql = "SELECT score FROM score WHERE user_id = :user_id AND d_id = :d_id ORDER BY score DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':d_id', 3, PDO::PARAM_INT);
            $stmt->execute();
            $old_score = $stmt->fetchColumn();

            if ($old_score == false) {
                $sql = "INSERT INTO score
                (user_id, d_id, score, correct, miss, avg, havetime)
                VALUES
                (:user_id, :d_id, :score, :correct, :miss, :avg, :havetime)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
                $stmt->bindValue(':d_id', 3, PDO::PARAM_INT);
                $stmt->bindValue(':score', $score, PDO::PARAM_INT);
                $stmt->bindValue(':correct', $correct, PDO::PARAM_STR);
                $stmt->bindValue(':miss', $miss, PDO::PARAM_STR);
                $stmt->bindValue(':avg', $avg, PDO::PARAM_STR);
                $stmt->bindValue(':havetime', $havetime, PDO::PARAM_STR);
                $stmt->execute();
                $highscore = true;
            } elseif ($old_score < $score) {
                $sql = "UPDATE score SET score = :score, correct = :correct, miss = :miss,  avg = :avg, havetime = :havetime WHERE user_id = :user_id AND d_id = :d_id";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':score', $score, PDO::PARAM_INT);
                $stmt->bindValue(':correct', $correct, PDO::PARAM_STR);
                $stmt->bindValue(':miss', $miss, PDO::PARAM_STR);
                $stmt->bindValue(':avg', $avg, PDO::PARAM_STR);
                $stmt->bindValue(':havetime', $havetime, PDO::PARAM_STR);
                $stmt->bindValue(':user_id', $_SESSION['USER']['id'], PDO::PARAM_INT);
                $stmt->bindValue(':d_id', 3, PDO::PARAM_INT);
                $stmt->execute();
                $highscore = true;
            }
        }
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
            overflow: auto;
            scrollbar-width: none;
        }

        .otaku-title {
            font-family: 'UnifrakturCook', cursive;
            text-align: center;
            font-size: 100px;
            color: rgb(202, 189, 144);
        }

        .card {
            max-width: 700px;
        }

        @media (max-height: 800px) {
            header {
                display: none;
            }

            body {
                padding-top: 0 !important;
            }

            .note {
                position: relative;
                top: -35px;
            }

            .level-font {
                top: 115px !important;
            }
        }

        .result-font {
            font-size: 20px;
        }

        .level-font {
            font-size: 62px;
            position: relative;
            top: 145px;
            right: 10px;
            z-index: 20;
        }

        .level {
            font-family: "WDXL Lubrifont TC", sans-serif;
            font-weight: 400;
            font-style: normal;
        }

        .title {
            font-family: "WDXL Lubrifont TC", sans-serif;
            font-size: 18px;
        }

        .get-title {
            font-family: "WDXL Lubrifont TC", sans-serif;
            font-size: 25px;
        }

        .title-content {
            font-family: "WDXL Lubrifont TC", sans-serif;
            font-size: 20px;
        }

        .difficulty-image {
            height: auto;
            border-radius: 10px;
            max-height: 160px;
            object-fit: cover;
            z-index: -9999999999999;
        }

        img {
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;

        }

        .pdt {
            padding-top: 150px;
        }
    </style>
</head>
<?php include 'templates/header.php'; ?>

<body class="pdt">
    <main>
        <div class="container-fluid d-flex justify-content-center align-items-center" style="text-align: center;">
            <div style="width: 100%; max-width: 700px; position: relative; top: -135px;;">
                <div class="level-font" style="display: flex; align-items: center; justify-content: center; gap: 4px; z-index: 2;">
                    <span class="level">難易度</span>
                    <?php if ($d == 1) : ?>
                        <img src="../assets/img/kai.png" alt="初級" class="difficulty-image">
                    <?php elseif ($d == 2) : ?>
                        <img src="../assets/img/sin.png" alt="中級" class="difficulty-image">
                    <?php else : ?>
                        <img src="../assets/img/zetu.png" alt="上級" class="difficulty-image">
                    <?php endif; ?>
                    <span class="level">級</span>
                </div>
                <div class="mb-3" style="font-weight: bold; font-size: 1.2rem;position: relative; top: 145px;"><?php if (isset($_POST['cleared']) && $_POST['cleared'] == 'true') : ?>クリア！！<?php else : ?>しっぱい。。。<?= $remainingCount ?>/50<?php endif; ?></div>
                <div class="card p-5 w-100" style="position:relative;">
                    <div class="card p-3 w-100 rounded-xl">
                        <div>
                            <div class="mb-3" style="font-weight: bold; font-size: 1.2rem;position: relative; z-index:99;"><?php if (isset($_POST['cleared']) && $_POST['cleared'] == 'true') : ?>クリア！！<?php else : ?>しっぱい。。。<?= (50 - $remainingCount) ?>/50<?php endif; ?></div>
                            <hr style="border: 1px solid #333; margin-top: 4px;">
                        </div>
                        <div class="flex gap-6 justify-center items-center flex-wrap text-lg">
                            <div class="d-flex justify-content-center w-100">
                                <div class="text-start">
                                    <div class="title mb-2" <?php if (isset($highscore)) : ?>style='color:red;' <?php endif; ?>><?php if ((isset($highscore))) : ?>ハイスコア！！<?php else : ?>スコア<?php endif; ?>：<span class="result-font"><?= $score ?> 点</span></div>
                                    <div class="title mb-2">タイムボーナス：<span class="result-font">+<?= $havetime ?> 点</span></div>
                                    <div class="title mb-2">クラス：<span class="result-font"><?= $class ?> 級</span></div>
                                    <div class="title">コメント：<span class="result-font"><?= $comment ?></span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="my-3 p-3 rounded" style="background-color: #fff; border: 1px solid #ccc;">
                        <div class="row text-center">
                            <div class="col">
                                <div style="font-weight: bold;">正しく打ったキーの数</div>
                                <div><?= $correct ?>回</div>
                            </div>
                            <div class="col">
                                <div style="font-weight: bold;">平均タイプ数</div>
                                <div><?= $avg ?>回/秒</div>
                            </div>
                            <div class="col">
                                <div style="font-weight: bold;">ミスタイプ数</div>
                                <div><?= $miss ?>回</div>
                            </div>
                        </div>
                    </div>
                    <div class="" style="font-weight: bold; font-size: 1.2rem;position: relative; z-index:99;">スコア内訳</div>
                    <div class="my-3 p-3 rounded" style="background-color: #fff; border: 1px solid #ccc;">
                        <div class="row text-center">
                            <div class="col">
                                <div style="font-weight: bold;">正確タイプボーナス</div>
                                <div>+<?= ($correct *= 50) ?>点</div>
                            </div>
                            <div class="col">
                                <div style="font-weight: bold;">タイムボーナス</div>
                                <div>+<?= ($havetime) ?>点</div>
                            </div>
                            <div class="col">
                                <div style="font-weight: bold;">ミス</div>
                                <div><?= ($miss *= -20) ?>点</div>
                            </div>
                        </div>
                    </div>
                    <?php if ($get_title == true) : ?>
                        <div class="text-center get-title">称号を獲得しました！</div>
                        <div class="mb-3 p-3 rounded" style="background-color: #fff; border: 1px solid #ccc;">
                            <div class="title-content"><?= $text ?> <?php if ($hyakutext == 'アイシクルロード') : ?>・<?= $hyakutext ?><?php endif; ?></div>
                        </div>
                    <?php endif; ?>
                    <div class="mt-4">
                        <a href="/main?d=<?= $d ?>" class="btn btn-dark me-2">もう一度</a>
                        <a href="/top" class="btn btn-outline-dark">トップに戻る</a>
                    </div>
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