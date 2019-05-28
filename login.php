<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 ログインページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require('auth.php');

if(!empty($_POST)){
    debug('POST送信があります');
    debug('POST送信情報：'.print_r($_POST,true));

    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_save = (!empty($_POST['pass_save'])) ? true : false;

    validRequired($email, 'email');
    validRequired($pass, 'pass');

    if(empty($err_msg)){
        debug('未入力チェックOK。');

        validEmail($email, 'email');

        validHalf($pass, 'pass');
        validMinLen($pass, 'pass');
        validMaxLen($pass, 'pass');

        if(empty($err_msg)){
            debug('入力形式チェックOK。');
            try{
                $dbh = dbConnect();
                $sql ='SELECT password,id FROM users WHERE email = :email AND delete_flg = 0';
                $data = array(':email' => $email);
                $stmt = queryPost($dbh, $sql, $data);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!empty($result) && password_verify($pass, $result['password'])) {
                    debug('パスワードがマッチしました。');

                    $_SESSION['login_date'] = time();

                    if(!empty($pass_save)){
                        debug('ログイン保持にチェックがあります。');
                        $_SESSION['login_limit'] = 60*60*24*30;
                    }else{
                        debug('ログイン保持にチェックがありません。');
                        $_SESSION['login_limit'] = 60*60;
                    }
                    $_SESSION['user_id'] = $result['id'];
                    debug('セッション変数の中身：' . print_r($_SESSION,true));
                    debug('マイページへ遷移します。');
                    header('Location:mypage.php');
                }else{
                    debug('パスワードがアンマッチです。');
                    $err_msg['common'] = MSG09;
                }

            } catch (Exception $e){
                error_log('エラー発生：' . $e->getMessage());
                $err_msg['common'] = MSG07;
            }
        }
    }
}
debug('画面表示処理終了>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');
?>
<?php
$siteTitle = 'ログイン';
require('head.php');
?>


<body class="all">
<?php
require('header.php');
?>
<div class="bg-mask">
<div class="login">

<div class="site-width">
<h1>ログイン</h1>
    <form action="" method="post">
        
        <div class="err_msg">
            <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
        </div>
        <label>E-mail:<span class="err_msg"><?php if(!empty($err_msg['email']))  echo $err_msg['email']; ?></span>
        <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
        </label>
        <label>パスワード:<span class="err_msg"><?php if(!empty($err_msg['pass']))  echo $err_msg['pass']; ?></span>
        <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
        </label>
        <label>
        <input type="checkbox" name="pass_save">次回ログインを省略する
        <div class="">パスワードを忘れた方は<a href="passRemindSend.php">こちら</a></div>
        </label>
        <input value="ログイン" type="submit">
    </form>
</div>
</div>



