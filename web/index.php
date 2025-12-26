<?php
require_once(dirname(__FILE__) . '/functions/functions.php');
require_once(dirname(__FILE__) . '/mapping.php');
try {
    session_start();

    $pdo = connectDb();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $err = array();
    $complete_message = NULL;
    class CustomException extends Exception {}
    $meta_page_title = '';
    $meta_page_description = '';
    $meta_page_eyecatch = getCurrentDomain() . '/assets/images/ogimage.png';

    $request_path = $_REQUEST['path'];


    // 末尾にスラッシュが付いていない場合は強制的に付ける
    if (!endsWith($request_path, '/')) {
        $request_path .= '/';
    }

    if (isset($_SESSION['cart'])) {
        // カート内のアイテムが期限切れか確認
        foreach ($_SESSION['cart'] as $id => $item) {
            // 現在の時刻とアイテムの期限を比較
            if (time() > $item['expiry_timestamp']) {
                // 期限が過ぎている場合、アイテムを削除
                unset($_SESSION['cart'][$id]);
            }
        }
    }

    // ログイン要／不要ページに関わらず、ログインされている場合はログイン中のユーザーを取得
    if (check_login()) {
        $session_user = get_login_user();
    }
    // mapping.phpに従って対象PHPに処理を移譲
    if (isset($url_list[$request_path])) {
        // アクセスされたURLのプログラムに処理を移譲
        include(dirname(__FILE__) . $url_list[$request_path]);
    } else {
        // 存在しないパスへのアクセスはエラーページへ
        throw new Exception('存在しないパスへのアクセス:' . $request_path, 700);
    }
    unset($pdo);
} catch (Exception $e) {
    unset($pdo);
    error_process($e);
    exit;
}
