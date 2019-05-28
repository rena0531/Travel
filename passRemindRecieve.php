<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 パスワード再発行認証キーページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

if(empty($_SESSION['auth_key'])){
    header('Location:passRemindSend.php');
}

if (!empty($_POST)) {
    debug('POST送信があります');
    debug('POST送信情報：'.print_r($_POST, true));

    $auth_key = $_POST['token'];

    validRequired($auth_key, 'token');

    if (empty($err_msg)) {
        debug('未入力チェックOK。');
        validLength($auth_key, 'token');

        if (empty($err_msg)) {
            debug('バリデーションチェックOK。');
            if ($_SESSION['auth_key'] !== $auth_key) {
                $err_msg['common'] = MSG12;
            }
            if ($_SESSION['auth_key_limit'] < time()) {
                $err_msg['common'] = MSG13;
            }
        
            if (empty($err_msg)) {
                debug('認証OK。');
                $pass = makeRandKey();
                try {
                    $dbh = dbConnect();
                    $sql = 'UPDATE users SET password = :pass WHERE email = :email AND delete_flg=0';
                    $data = array(':email' => $_SESSION['auth_email'],
                        ':pass' => password_hash($pass, PASSWORD_DEFAULT));
                    $stmt = queryPost($dbh, $sql, $data);

                    if ($stmt) {
                        debug('クエリ成功。');

                        $from = 'choco.rena0609@gmail.com';
                        $to = $_SESSION['auth_email'];
                        $subject = 'パスワード再発行';
                        $comment = <<<EOT
本メールアドレス宛にパスワードの再発行を致しました。
下記のURLにて再発行パスワードをご入力頂き、ログインください。
ログインページ：http://localhost:8888/webservice_practice07/login.php
再発行パスワード：{$pass}
※ログイン後、パスワードのご変更をお願い致します
            
////////////////////////////////////////
ウェブカツマーケットカスタマーセンター
URL  http://webukatu.com/
E-mail info@webukatu.com
////////////////////////////////////////
EOT;
                        sendMail($from, $to, $subject, $comment);
                        session_unset();
                        debug('セッション変数の中身：'.print_r($_SESSION,true));
                        header('Location:login.php');
                    } else {
                        debug('クエリに失敗しました。');
                        $err_msg['common'] = MSG07;
                    }
                } catch (Exception $e) {
                    error_log('エラー発生:' . $e->getMessage());
                    $err_msg['common'] = MSG07;
                }
            }
        }
    }
}
debug('画面表示処理終了>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');
?>
<?php
$siteTitle = 'パスワード変更';
require('head.php');
?>


<?php
require('header.php');
?>

<div class="pass site-width">
    <form action="" method="post">
        <h1>認証キー入力</h1>
        <div class="err_msg">
            <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
        </div>
        <div class="area-msg">
             <?php if(!empty($err_msg['token'])) echo $err_msg['token']; ?>
        </div>
        <label>
        <input type="text" name="token" value="<?php if(!empty($_POST['token'])) echo $_POST['token']; ?>">
        </label>
        <input value="認証" type="submit">
    </form>
</div>

<?php
require('footer.php');
?>