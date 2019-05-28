<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 パスワード変更メール送信ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

if($_POST){
    debug('POST送信があります');
    debug('POST送信情報：'.print_r($_POST,true));

    $email = $_POST['email'];

    validRequired($email, 'email');

    if(empty($err_msg)){
        debug('未入力チェックOK。');
            validMaxLen($email, 'email');
            validEmail($email, 'email');
        }

        if(empty($err_msg)){
            debug('バリデーションチェックOK。');
            try{
                $dbh = dbConnect();
                $sql ='SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
                $data = array(':email' => $email);
                $stmt = queryPost($dbh, $sql, $data);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if($stmt && array_shift($result)){
                    debug('クエリ成功。DB登録あり');

                    $auth_key = makeRandKey();

                    $from = 'choco.rena0609@gmail.com';
                    $to = $email;
                    $subject = 'パスワード再発行認証';
                    $comment = <<<EOT
本メールアドレス宛にパスワード再発行のご依頼がありました。
下記のURLにて認証キーをご入力頂くとパスワードが再発行されます。
                    
パスワード再発行認証キー入力ページ：http://localhost:8888/webservice_practice07/passRemindRecieve.php
認証キー：{$auth_key}
※認証キーの有効期限は30分となります
                    
認証キーを再発行されたい場合は下記ページより再度再発行をお願い致します。
http://localhost:8888/webservice_practice07/passRemindSend.php
                    
////////////////////////////////////////
ウェブカツマーケットカスタマーセンター
URL  http://webukatu.com/
E-mail info@webukatu.com
////////////////////////////////////////
EOT;

                    sendMail($from, $to, $subject, $comment);

                    $_SESSION['auth_key'] = $auth_key;
                    $_SESSION['auth_email'] = $email;
                    $_SESSION['auth_key_limit'] = time() + (60*30);
                    debug('セッション変数の中身：'.print_r($_SESSION,true));

                    header('Location:passRemindRecieve.php');
                }else{
                    debug('クエリに失敗したか、DBにないemailが入力されました。');
                    $err_msg['common'] = MSG11;
                }
            } catch (Exception $e){
                error_log('エラー発生：' . $e->getMessage());
                $err_msg['common'] = MSG07;
            }
        }
    }
debug('画面表示処理終了>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');
?>
<?php
$siteTitle = 'パスワード変更メール送信';
require('head.php');
?>


<?php
require('header.php');
?>

<div class="pass site-width">
    <form action="" method="post">
        <h1>パスワード変更メール</h1>
        <div class="err_msg">
            <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
        </div>
        <label>E-mail:<?php if(!empty($err_msg['email']))  echo $err_msg['email']; ?>
        <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
        </label>
        <input value="送信" type="submit">
    </form>
</div>

<?php
require('footer.php');
?>
