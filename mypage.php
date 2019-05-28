<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 マイページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require('auth.php');
$getLike = getLike();

?>



<?php
$siteTitle = 'マイページ';
require('head.php');
?>


<?php
require('header.php');

?>

<div class="mypage"> 
    <h1>マイページ</h1>
    <ul class="list">
        <h3>メニュー</h3>
        <li><a href="withdraw.php">退会</a></li>
        <li><a href="profEdit.php">プロフィール編集</a></li>
        <li><a href="passEdit.php">パスワード変更</a></li>
    </ul>
        <h2>お気に入り一覧</h2>
        <div class="contents-r">
        <?php  foreach($getLike as $key => $val): ?>
        <a class="content-r" href="planDetail.php?p_id=<?php echo $val['id']; ?>"><?php echo $val['planname'] ?>
        
        <img class="top-img-r" src="<?php echo $val['pic1'] ?>" width="60%">
        <p class="title" ></p>
    </a>
    <?php endforeach;?>
    </div>
</div>
</div>

<?php
require('footer.php');
?>
