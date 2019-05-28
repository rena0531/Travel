<?php
ini_set('log_errors','on');
ini_set('error_log','php.log');

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 ログアウトページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

debug('ログアウトします。');
session_destroy();
debug('ログインページへ遷移します。');
header("Location:login.php");

?>