<?php
ini_set('log_errors','on');
ini_set('error_log','php.log');

if(!empty($_SESSION['login_date'])){
    debug('ログイン済みユーザーです。');

    if($_SESSION['login_date'] + $_SESSION['login_limit'] < time()){
        debug('ログイン期限オーバーです。');
        session_destroy();
        header('Location:login.php');
    }else{
        debug('ログイン期限以内です。');
        $_SESSION['login_date'] = time();
        if (basename($_SERVER['PHP_SELF']) === 'login.php' ) {
            debug('マイページへ遷移します。');
            header('Locaton:mypage.php');
        }
    }

}else{
    debug('未ログインユーザーです。');
    if(basename($_SERVER['PHP_SELF']) !== 'login.php'){
        header("Location:login.php");
    }
}

?>