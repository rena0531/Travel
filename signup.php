<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 ユーザー登録ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();


//バリデーションチェック
if(!empty($_POST)){
    debug('POST送信があります');
    debug('POST情報：'.print_r($_POST,true));

    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_re = $_POST['pass_re'];  

    validRequired($email, 'email');
    validRequired($pass, 'pass');
    validRequired($pass_re, 'pass_re');

if (empty($err_msg)) {
    debug('未入力チェックOK。');

    validEmail($email, 'email');
    validEmailDup($email);

    validHalf($pass, 'pass');
    validMinLen($pass, 'pass');
    validMaxLen($pass, 'pass');

    validHalf($pass_re, 'pass_re');
    validMinLen($pass_re, 'pass_re');
    validMaxLen($pass_re, 'pass_re');

    if (empty($err_msg)) {
        debug('入力形式チェックOK。');

        validMatch($pass, $pass_re, 'pass');

        if (empty($err_msg)) {
            try {
                $dbh = dbConnect();
                $sql = 'INSERT INTO users (email,password,login_time,create_date) VALUES (:email, :pass, :login_time, :create_date) ';
                $data = array(':email' => $email, ':pass' => password_hash($pass, PASSWORD_DEFAULT),
                                ':login_time' => date('Y-m-d H:i:s'),
                                ':create_date' => date('Y-m-d H:i:s'));
                $stmt = queryPost($dbh, $sql, $data);

                $_SESSION['user_id'] = $dbh->lastInsertId();
                debug('セッション変数の中身：'.print_r($_SESSION,true));
                header("Location:mypage.php");
                
            } catch (Exception $e) {
                error_log('エラー発生：'.$e->getMessage());
                $err_msg['common'] = MSG07;
            }
        }
    }
}
}
debug('画面表示処理終了>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');

?>
<?php
$siteTilte = "ユーザー登録";
require('head.php');
?>

    <?php
    require('header.php');
    ?>
<div class="signup site-width">
    <form action="" method="post">
        <h1>ユーザー登録</h1>
        <div class="err_msg">
            <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
        </div>
        <label>E-mail:<?php if(!empty($err_msg['email']))  echo $err_msg['email']; ?>
        <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
        </label>
        <label>パスワード:<?php if(!empty($err_msg['pass']))  echo $err_msg['pass']; ?>
        <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
        </label>
        <label>パスワード（再入力）:<?php if(!empty($err_msg['pass_re']))  echo $err_msg['pass_re']; ?>
        <input type="password" name="pass_re" value="<?php if(!empty($_POST['pass_re'])) echo $_POST['pass_re']; ?>">
        </label>
        <input value="登録" type="submit">
    </form>
</div>
<?php
require('footer.php');
?>
