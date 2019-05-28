<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 パスワード変更ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require('auth.php');

$userData = getUser($_SESSION['user_id']);

if (!empty($_POST)) {
    debug('POST送信があります');
    debug('POST送信情報：'.print_r($_POST, true));

    $pass = $_POST['pass'];
    $n_pass = $_POST['n_pass'];
    $n_pass_re = $_POST['n_pass_re'];

    validRequired($pass, 'pass');
    validRequired($n_pass, 'n_pass');
    validRequired($n_pass_re, 'n_pass_re');

    if(empty($err_msg)){
        debug('未入力チェックOK。');

        validPass($pass, 'pass');
        validPass($n_pass, 'n_pass');

        if(!password_verify($pass, $userData['password'])){
            $err_msg['common'] = MSG15;
        }

        if($pass === $n_pass){
            $err_msg['common'] = MSG16;
        }

        validMatch($n_pass, $n_pass_re, 'n_pass_re');

          if(empty($err_msg)){
                        debug('バリデーションOK。');
            try{
                        $dbh = dbConnect();
                        $sql = 'UPDATE users SET password = :pass WHERE id = :id';
                        $data = array(':id' => $_SESSION['user_id'],':pass' => password_hash($n_pass, PASSWORD_DEFAULT));
                        $stmt = queryPost($dbh, $sql, $data);

                        if($stmt){
                            debug('クエリ成功。');

                            $username = ($userData['name']) ? $userData['name']: '名無し';
                            $from = 'choco.rena@gmail.com';
                            $to = $userData['email'];
                            $subject = 'パスワード変更通知';
                            $comment = <<<EOT
{$username}さん
パスワードが変更されました。
EOT;

                            sendMail($from, $to, $subject, $comment);
                            header("Location:mypage.php");
                        }else{
                            debug('クエリ失敗。');
                            $err_msg['common'] = MSG07;
                        }
            }catch(Exception $e){
                error_log('エラー発生：' .$e->getMessage());
                $err_msg['common'] = MSG07;
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

<div class="passEdit site-width">
    <form action="" method="post">
        <h1>パスワード変更</h1>
        <div class="err_msg">
            <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
        </div>
        <label>古いパスワード:<span class="err_msg"><?php if(!empty($err_msg['pass']))  echo $err_msg['pass']; ?></span>
        <input type="password" name="pass" value="<?php echo getFormData('pass'); ?>">
        </label>
        <label>新しいパスワード:<span class="err_msg"><?php if(!empty($err_msg['n_pass']))  echo $err_msg['n_pass']; ?></span>
        <input type="password" name="n_pass" value="<?php echo getFormData('n_pass'); ?>">
        </label>
        <label>新しいパスワード（再入力）:<span class="err_msg"><?php if(!empty($err_msg['n_pass_re']))  echo $err_msg['n_pass_re']; ?></span>
        <input type="password" name="n_pass_re" value="<?php echo getFormData('n_pass_re'); ?>">
        </label>
        <input value="変更" type="submit">
    </form>
</div>

<?php
require('footer.php');
?>
