<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　プラン詳細ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';
$viewData = getPlanOne($p_id);
debug($viewData);
if(empty($viewData)){
    error_log('エラー発生:指定ページに不正な値が入りました');
    header("Location:index.php");
}
debug('取得したDBデータ：'.print_r($viewData,true));
debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');

?>
<?php
$siteTitle = '商品詳細';
require('head.php'); 
?>

    <style>
      .badge{
        padding: 5px 10px;
        color: white;
        background: #7acee6;
        margin-right: 10px;
        font-size: 16px;
        vertical-align: middle;
        position: relative;
        top: -4px;
      }
      #main .title{
        font-size: 28px;
        padding: 10px 0;
      }
      .plan-img-container{
        overflow: hidden;
      }
      .plan-img-container img{
        width: 100%;
      }
      .plan-img-container .img-main{
        width: 750px;
        float: left;
        padding-right: 15px;
        box-sizing: border-box;
      }
      .plan-img-container .img-sub{
        width: 230px;
        float: left;
        background: #f6f5f4;
        padding: 15px;
        box-sizing: border-box;
      }
      .plan-img-container .img-sub:hover{
        cursor: pointer;
      }
      .plan-img-container .img-sub img{
        margin-bottom: 15px;
      }
      .plan-img-container .img-sub img:last-child{
        margin-bottom: 0;
      }
      .plan-detail{
        background: #f6f5f4;
        padding: 15px;
        margin-top: 15px;
        min-height: 150px;
      }
      .plan-buy{
        overflow: hidden;
        margin-top: 15px;
        margin-bottom: 50px;
        height: 50px;
        line-height: 50px;
      }
      .plan-buy .item-left{
        float: left;
      }
      .plan-buy .item-right{
        float: right;
      }
      .plan-buy .price{
        font-size: 32px;
        margin-right: 30px;
      }
      .plan-buy .btn{
        border: none;
        font-size: 18px;
        padding: 10px 30px;
      }
      .plan-buy .btn:hover{
        cursor: pointer;
      }
    </style>

    <!-- ヘッダー -->
    <?php
      require('header.php'); 
    ?>

    <div id="contents" class="site-width">

    <div class="title">
    <span class="badge"><?php echo sanitize($viewData['category']); ?> </span>
<?php echo sanitize($viewData['planname']); ?>
</div>
<div class="plan-img container">
    <div class="img-main">
        <img src="<?php echo sanitize($viewData['pic1']); ?>" alt="メイン画像：<?php echo sanitize($viewData['planname']); ?>"  
    </div>
</div>
<div class="plan-detail">
    <p><?php echo sanitize($viewData['detail']); ?></p>
</div>
<a href="index.php<?php echo appendGetParam(array('p_id')); ?>">&lt; 一覧に戻る</a>
<p class="price"><?php echo sanitize(($viewData['price'])); ?></p>
</section>

</div>

<?php
require('footer.php'); 
?>


