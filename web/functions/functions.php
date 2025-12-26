<?php

// 環境変数の読み込み
function loadEnv()
{
    $envFile = __DIR__ . '/../../.env';
    if (file_exists($envFile)) {
        $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos($line, '#') === 0) continue;
            list($name, $value) = explode('=', $line, 2);
            $_ENV[trim($name)] = trim($value);
            putenv(sprintf('%s=%s', trim($name), trim($value)));
        }
    }
}

// 環境変数の読み込みを実行
loadEnv();

// ログファイルのパスを環境変数から取得
$logFile = getenv('LOG_FILE');
if (!$logFile) {
    error_log('ログファイルのパスが設定されていません。');
    throw new Exception('ログ設定エラーが発生しました。', 500);
}

// ログディレクトリが存在しない場合は作成
if (!file_exists(dirname($logFile))) {
    mkdir(dirname($logFile), 0777, true);
}

define('LOG_FILE', $logFile);

function connectDb()
{
    $host = getenv('DB_HOST');
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASS');
    $db = getenv('DB_NAME');

    // 必要な環境変数が設定されているか確認
    if (!$host || !$user || !$pass || !$db) {
        error_log('データベース接続に必要な環境変数が設定されていません。');
        throw new Exception('データベース設定エラーが発生しました。', 500);
    }

    $param = "mysql:dbname=" . $db . ";host=" . $host;

    try {
        $pdo = new PDO($param, $user, $pass);
        $pdo->query('SET NAMES utf8;');

        return $pdo;
    } catch (PDOException $e) {
        error_log("データベース接続エラー: " . $e->getMessage());
        throw new Exception('データベース接続エラーが発生しました。', 500);
    }
}

// 暗号化設定を環境変数から読み込み
$algorithm = getenv('ENCRYPT_ALGORITHM');
$passphrase = getenv('ENCRYPT_PASSPHRASE');
$ivKey = getenv('ENCRYPT_IV_KEY');

// 必要な環境変数が設定されているか確認
if (!$algorithm || !$passphrase || !$ivKey) {
    error_log('暗号化に必要な環境変数が設定されていません。');
    throw new Exception('暗号化設定エラーが発生しました。', 500);
}

define('ENCRYPT_ALGORITHM', $algorithm);
define('ENCRYPT_PASSPHRASE', $passphrase);
define('ENCRYPT_IV_KEY', $ivKey);

// 指定された文字列を暗号化する
function encrypt($original_str)
{
    // 文字列を暗号化
    $encrypt_str = openssl_encrypt($original_str, ENCRYPT_ALGORITHM, ENCRYPT_PASSPHRASE, OPENSSL_RAW_DATA, ENCRYPT_IV_KEY);

    // IVキーと暗号化したデータをBASE64エンコード
    $base64_encrypt_str = base64_encode($encrypt_str);

    return $base64_encrypt_str;
}

// 指定された文字列を複合化する
function decrypt($encrypt_str)
{
    // BASE64デコード
    $base64_decrypt_str = base64_decode($encrypt_str);

    // 復号
    $decrypt_str = openssl_decrypt($base64_decrypt_str, ENCRYPT_ALGORITHM, ENCRYPT_PASSPHRASE, OPENSSL_RAW_DATA, ENCRYPT_IV_KEY);

    return $decrypt_str;
}

// サービスのドメインを取得する
function getCurrentDomain()
{
    return (empty($_SERVER['HTTPS']) ? 'http://' : 'https://') . $_SERVER['HTTP_HOST'];
}

// 文字列が指定文字で始まっているかどうかチェックする（全体文字列, 指定文字）
function startsWith($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}

// 文字列が指定文字で終わっているかどうかチェックする（全体文字列, 指定文字）
function endsWith($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}

// ログイン状態をチェックする
function check_login()
{
    global $pdo;

    // ログインチェックのたびにDBアクセスしたくないため、ログインされていない場合のみCookieをチェック
    if (empty($_SESSION['USER'])) {
        // 自動ログイン情報があるかどうかCookieをチェック
        if (isset($_COOKIE['auto_login'])) {
            // 自動ログイン情報があればキーを取得
            $auto_login_key = $_COOKIE['auto_login'];

            // 自動ログインキーをDBに照合
            $sql = "SELECT * FROM auto_login WHERE auto_login_key = :auto_login_key AND expire >= :expire LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':auto_login_key', $auto_login_key, PDO::PARAM_STR);
            $stmt->bindValue(':expire', date('Y-m-d H:i:s'), PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch();

            if ($row) {
                $sql = "SELECT * FROM user WHERE status = 1 AND id = :id LIMIT 1";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(':id', (int)$row['user_id'], PDO::PARAM_INT);
                $stmt->execute();
                $user = $stmt->fetch();

                if ($user) {
                    // セッションハイジャック対策
                    session_regenerate_id(true);
                    $_SESSION['USER'] = $user;
                }
            }
        }
    }

    return isset($_SESSION['USER']);
}

// 現在ログインしているユーザーをDBから取得する
function get_login_user()
{
    global $pdo, $request_path;

    // セッションからデータを取得
    $user = $_SESSION['USER'];

    // 最新の情報を取得するためにDBから再取得
    $sql = "SELECT id FROM user WHERE id = :id LIMIT 1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', (int)$user['id'], PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch();

    if (!$user) {
        header('Location: /logout');
        exit;
    }

    return $user;
}

// エラー処理を行う
function error_process($e)
{
    error_log(date("Y/m/d H:i:s") . " " . $e->getMessage() . "[" . $e->getFile() . ":" . $e->getLine() . "]" . PHP_EOL, 3, LOG_FILE);

    // $_SESSION[SESSION_ERROR_MESSAGE] = $e->getMessage();
    // $_SESSION[SESSION_ERROR_CODE] = $e->getCode();

    switch ($e->getCode()) {
        case 400:
            header('Location: /error-400.html');
            break;
        case 403:
            header('Location: /error-403.html');
            break;
        case 404:
        default:
            header('Location: /error-404.html');
            break;
        case 500:
            header('Location: /error-500.html');
            break;
    }
    exit;
}

function redirect($url)
{
    global $pdo;

    unset($pdo);
    header('Location:' . $url);
    exit;
}

function required_login()
{
    // ログインチェック
    if (!check_login()) {
        // ログインされていない場合はログイン画面へ
        redirect('/login?p=' . urlencode($_SERVER["REQUEST_URI"]));
    }
}

function recordExists(PDO $pdo, string $tableName, string $idColumn, $id): bool
{
    // SQL文を作成（テーブル名や列名は動的に埋め込むため、SQLインジェクション対策に注意）
    $sql = "SELECT COUNT(*) FROM $tableName WHERE $idColumn = :id";
    // SQLを準備
    $stmt = $pdo->prepare($sql);
    // パラメータをバインド
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    // クエリを実行
    $stmt->execute();
    // レコード数を取得
    $count = $stmt->fetchColumn();
    // 1件以上のレコードがあれば true を返す
    return $count > 0;
}
function form_check($check_value, $id, $title, $required, $format, $min_byte, $max_byte, $err)
{
    // 未入力チェック実施
    if ($required == 'required' && ($check_value == '')) {
        $err[$id] = $title . 'を入力してください。';
        return $err;
    }

    // 最小サイズチェック実施（文字数チェックを行うため文字数：カラム長の半分で指定）
    if ($min_byte > 0) {
        if (mb_strlen($check_value, 'utf-8') < ($min_byte)) {
            $err[$id] = $title . 'は' . ($min_byte) . '文字以上で入力してください。';
            return $err;
        }
    }

    // 最大サイズチェック実施（文字数チェックを行うため文字数：カラム長の半分で指定）
    if ($max_byte > 0) {
        if (mb_strlen($check_value, 'utf-8') > ($max_byte)) {
            $err[$id] = $title . 'は' . ($max_byte) . '文字以内で入力してください。';
            return $err;
        } else if (strlen(mb_convert_encoding($check_value, 'SJIS', 'UTF-8')) > $max_byte * 2) {
            // 最大値チェックに関しては指定文字数x2でバイトチェックも行っておく
            $err[$id] = $title . 'が長すぎます。';
            return $err;
        }
    }

    // 形式チェック実施
    if ($format && !filter_var($check_value, $format)) {
        $err[$id] = $title . 'の形式が正しくありません。';
        return $err;
    }

    return $err;
}
function renderVideo($url)
{
    // URLをエスケープ
    $escapedUrl = htmlspecialchars($url, ENT_QUOTES, 'UTF-8');

    // YouTube処理
    if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
        if (strpos($url, 'watch?v=') !== false) {
            parse_str(parse_url($url, PHP_URL_QUERY), $params);
            $videoId = $params['v'] ?? '';
        } else {
            $videoId = basename(parse_url($url, PHP_URL_PATH));
        }

        if ($videoId) {
            return '<div class="iframe-container">
                <iframe class="iframe" src="https://www.youtube.com/embed/' . htmlspecialchars($videoId, ENT_QUOTES, 'UTF-8') . '" frameborder="0" allowfullscreen></iframe>
            </div>';
        } else {
            return '無効なYouTubeリンク';
        }

        // Twitter (X) 処理
    } elseif (strpos($url, 'twitter.com') !== false || strpos($url, 'x.com') !== false) {
        // x.com → twitter.com に置き換え（widgets.jsの互換性対応）
        $embedUrl = str_replace('x.com', 'twitter.com', $url);
        $embedUrlEscaped = htmlspecialchars($embedUrl, ENT_QUOTES, 'UTF-8');

        return '<blockquote class="twitter-tweet"><a href="' . $embedUrlEscaped . '"></a></blockquote>
                <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>';

        // 動画ファイル直接指定
    } elseif (preg_match('/\.(mp4|webm|ogg)$/i', $url)) {
        return '<video width="340" height="180" controls>
                    <source src="' . $escapedUrl . '" type="video/mp4">
                    お使いのブラウザは video タグをサポートしていません。
                </video>';

        // 対応外
    } else {
        return '';
    }
}
