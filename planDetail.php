<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　詳細ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();


if(!empty($_GET)){
    $p_id = $_GET['p_id'];
}

$getPlanAll = getPlanAll();

$viewData = getPlanOne($p_id);


debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
$siteTitle = '詳細';
require('head.php');
?>

    <?php
      require('header.php');
    ?>
    <section>
    <a class="back" href="index.php">一覧へ戻る</a>
      <div class="content-detail">
      <?php
      foreach($viewData as $key => $val):
        ?>
      <i class="far fa-heart icn-like js-click-like <?php if(isLike($_SESSION['user_id'], $val['id'])){ echo 'active'; } ?>" aria-hidden="true" data-planid="<?php echo sanitize($val['id']); ?>" ></i>
      <p class="c_pre"><?php echo $val['name'] ?></p>
      <h1><?php echo $val['planname'] ?></h1>

      <img class="main-img" src="<?php echo $val['pic1'] ?>">

      <table>
            <tr><th>季節</th><th><?php echo $val['season'] ?>
            <tr><th>予算</th><th><?php echo $val['price'] ?></th></tr>
            <tr><th>交通手段</th><th><?php echo $val['access'] ?></th></tr>
            <tr><th>誰と？</th><th><?php echo $val['belong'] ?></th></tr>
            <tr><th>詳細</th><th><?php echo $val['detail'] ?></th></tr>
      </table>
      <?php
      endforeach;
      ?>
    </section>
    <div class="back-center"><a href="index.php">一覧へ戻る<a></div>

    
    <?php
      require('footer.php');
    ?>
<?php ?>