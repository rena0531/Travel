<?php
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 退会ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require('auth.php');

if(!empty($_POST)){
    debug('POST送信があります。');
    try{
        $dbh = dbConnect();
        $sql1 = 'UPDATE users SET delete_flg = 1 WHERE id = :user_id';
        $sql2 = 'UPDATE plan SET delete_flg = 1 WHERE id = :user_id';
        $sql3 = 'UPDATE recommend SET delete_flg = 1 WHERE user_id = :user_id';
    
        $data = array(':user_id' => $_SESSION['user_id']);
        $stmt1 = queryPost($dbh,$sql1,$data);
        $stmt2 = queryPost($dbh,$sql2,$data);
        $stmt3 = queryPost($dbh,$sql3,$data);
    
        if($stmt1){
            session_destroy();
            debug('セッション変数の中身:' . print_r($_SESSION,true));
            debug('トップページへ遷移します。');
            header('Location:index.php');
        }else{
            debug('クエリが失敗しました。');
            $err_msg['common'] = MSG07;
        }
        
    }catch(Exception $e){
        error_log('エラー発生：' . $e->getMessage());
        $err_msg['common'] = MSG07;
    }
}
debug('画面表示処理終了>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');
?>
<?php
$siteTitle = '退会';
require('head.php');

?>


<?php
require('header.php');
?>

<div class="withdraw site-width">
<h1>退会</h1>
    <form action="" method="post">
        
        <div class="err_msg">
            <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
        </div>
        <input type="submit" class="btn btn-mid" value="退会する" name="submit">
    </form>
</div>

<?php
require('footer.php');
?>