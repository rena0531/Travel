<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　トップページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

$currentPageNum = (!empty($_GET['p'])) ? $_GET['p']:1;

$categoryPrefecture = (!empty($_GET['c_p'])) ? $_GET['c_p']:'';
$categorySeason = (!empty($_GET['c_s'])) ? $_GET['c_s']:'';

$getPlanAll = getPlanAll();

$dataSearch = dataSearch($categoryPrefecture, $categorySeason);


debug('画面表示処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>
<?php
$siteTitle = 'HOME';
require('head.php');
?>

    <?php
      require('header.php');
    ?>
<section id="bar">
  <form name="" methofd="get">
    <h1 class="cate">カテゴリー</h1>
    <div class="where">
    <div class="selectbox">
      <span>どこ？</span>
      <select name="c_p" id="">
        <option value="0">選択してください</option>
        <?php
        foreach(getCategoryPrefecture() as $key=>$val):
          ?>
          <option value="<?php echo $val['id'] ?>" <?php  if(getFormData('c_id',true) == $val['id'] ){ echo 'selected'; } ?> >
          <?php echo $val['name']; ?>
        </option>
        <?php
        endforeach;
        ?>
        </select>
      </div>

      <div class="selectbox">
      <span>季節</span>
      <select name="c_s" id="">
        <option value="0" <?php if(getFormData('c_s',true) == 0) {echo 'selected';} ?>>選択してください</option>
        <?php
        foreach(getCategorySeason() as $key=>$val):
          ?>
          <option value="<?php echo $val['id'] ?>" <?php  if(getFormData('c_s',true) == $val['id'] ){ echo 'selected'; } ?> >
          <?php echo $val['season']; ?>
        </option>
        <?php
        endforeach;
        ?>
        </select>
      </div>
          <input type="submit" value="検索">
      </div>
        </form>
      </section>

    <section class="contents" id="main">
      <div class="index-width">
      <?php
      if ($categoryPrefecture == '' && $categorySeason == '') {
          foreach ($getPlanAll as $key => $val):
        ?><a href="planDetail.php?p_id=<?php echo $val['id']; ?>">
      <div class="content">
      <p class="c_p"><?php echo $val['c_p_name'] ?></p>
      <img class="top-img" src="<?php echo $val['pic1'] ?> ">
      <p class="title"><?php echo $val['planname'] ?></p></a>
          </div>
      <?php
      endforeach;
    }else{
      foreach ($dataSearch as $key => $val):
        ?>
        <a href="planDetail.php?p_id=<?php echo $val['id']; ?>">
        <div class="content">
      <p class="c_p"><?php echo $val['c_p_name'] ?></p>
      <img class="top-img" src="<?php echo $val['pic1'] ?>" >
      <p class="title"><?php echo $val['planname'] ?></p></a>
      </div>
      <?php
      endforeach;
    }
      ?>
      </div>
    </section>
    <footer style="margin-top:100px;background-color:rgba(0, 0, 0, 0.69);text-align: center;color: white;width: 100%;">copyright Rena All Rights Reserved.</footer>
