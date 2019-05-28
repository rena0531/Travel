<?php

require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 プラン登録ページ　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require('auth.php');

$p_id = (!empty($_GET['p_id'])) ? $_GET['p_id'] : '';

$dbFormData = (!empty($p_id)) ? getplan($_SESSION['user_id'], $p_id) : '';

$edit_flg = (empty($dbFormData)) ? false: true;

$dbCategoryPrefecture = getCategoryPrefecture();
debug('プランID：'.$p_id);
debug('フォーム用DBデータ：'.print_r($dbFormData,true));
debug('カテゴリデータ：'.print_r($dbCategoryPrefecture,true));

$dbCategorySeason = getCategorySeason();
debug('プランID：'.$p_id);
debug('フォーム用DBデータ：'.print_r($dbFormData,true));
debug('カテゴリデータ：'.print_r($dbCategorySeason,true));

if(!empty($p_id) && empty($dbFormData)){
    debug('GETパラメータのプランIDが違います。マイページへ遷移します。');
    header("Location:mypage.php");
}

if(!empty($_POST)){
    debug('ポスト送信があります。');
    debug('POST情報：'.print_r($_POST,true));
    debug('FILE情報：'.print_r($_FILES,true));

    $planname = $_POST['planname'];
    $prefecture = $_POST['category_prefecture'];
    $price = $_POST['price'];
    $season = $_POST['category_season'];
    $access = $_POST['access'];
    $belong = $_POST['belong'];
    $detail = $_POST['detail'];

    $pic1 = (!empty($_FILES['pic1']['name'])) ? uploadImg($_FILES['pic1'],'pic1'): '';
    $pic1 = (empty($pic1) && !empty($dbFormData['pic1'])) ? $dbFormData['pic1']:$pic1;
   
    if(empty($dbFormData)){
        validRequired($planname, 'planname');
        validMaxLen($planname, 'planname');
        validSelect($prefecture,'category_prefecture');
        validRequired($detail, 'detail');
        validMaxLen($detail, 'detail');
        validSelect($season, 'category_season');
        validMaxLen($belong,'belong');
        validMaxLen($access,'access');
    }else{
        if($dbFormData['planname'] !== $planname){
            validRequired($planname, 'planname');
            validMaxLen($planname, 'planname');
        }
        if($dbFormData['category_prefecture'] !==$prefecture){
            validSelect($prefecture, 'category_prefecture');
        }
        if($dbFormData['detail'] !== $detail){
            validRequired($detail, 'detail');
            validMaxLen($detail, 'detail');
        }
        if($dbFormData['category_season'] !== $season){
            validSelect($season, 'category_season');
        }
        if($dbFormData['price'] !== $price){
            validHalf($price, 'price');
        }
        if($dbFormData['belong'] !== $belong){
            validMaxLen($belong,'belong');
        }
        if($dbFormData['access'] !== $access){
            validMaxLen($access,'access');
        }
    }

    if(empty($err_msg)){
        debug('バリデーションOK。');

        try{
            $dbh = dbConnect();

            if ($edit_flg) {
                debug('DB更新です。');
                
                $sql = 'UPDATE plan SET planname = :planname, category_season = :season, category_prefecture = :prefecture, price = :price, access = :access, belong = :belong, detail = :detail, pic1 = :pic1 WHERE user_id = :user_id AND id = :p_id' ;
                $data = array(':planname' => $planname,':season' => $season,':prefecture' => $prefecture, ':price' => $price, ':access' => $access, ':belong' => $belong, ':detail' =>$detail, ':pic1' => $pic1, ':user_id' => $_SESSION['user_id'], ':p_id' => $p_id);
            }else{
                debug('DB新規登録です。');
                $sql = 'INSERT INTO plan (planname, category_season, category_prefecture, price, access, belong, detail, pic1, user_id, create_date) VALUES (:planname, :season, :prefecture, :price, :access, :belong, :detail, :pic1, :user_id, :date)';
                $data = array(':planname' => $planname,':season' => $season,':prefecture' => $prefecture, ':price' => $price, ':access' => $access, ':belong' => $belong, ':detail' =>$detail, ':pic1' => $pic1, ':user_id' => $_SESSION['user_id'], ':date' => date('Y-m-d H:i:s'));
            }
            debug('SQL:'.$sql);
            debug('流し込みデータ：'.print_r($data,true));
            
            $stmt = queryPost($dbh, $sql, $data);

            if($stmt){
                debug('クエリ成功');
                debug('マイページへ遷移します。');
                header('Location:mypage.php');
            }else{
                debug('クエリ失敗');
            }
        }catch(Exception $e){
            error_log('エラー発生：' . $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}
debug('画面表示処理終了>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>');

?>
<?php
$siteTitle = (!$edit_flg) ? 'プラン登録' : 'プラン編集';
require('head.php');
?>



    <?php
    require('header.php');
    ?>

<div class="resist">
        <h1>プラン登録</h1>
        <div class="width">
        <form action="" method="post" class="form" enctype="multipart/form-data"
        >
        <div class="err_msg">
            <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
        </div>

        <label>プラン名＊必須<span class="err_msg"><?php if(!empty($err_msg['planname'])) echo $err_msg['planname']; ?></span>
        <input type="text" name="planname" value="<?php echo getFormData('planname'); ?>">
        </label>

        <label>地域＊必須<span class="err_msg"><?php if(!empty($err_msg['prefecture'])) echo $err_msg['prefecture']; ?></span>
        <select name="category_prefecture" id="">
            <option value="0" <?php if(getFormData('category_prefecture') == 0){ echo 'selected';} ?>>選択してください</option>
            <?php foreach($dbCategoryPrefecture as $key => $val){ ?>
            <option value="<?php echo $val['id']?>"<?php if(getFormData('category_prefecture') == $val['id']){
                echo 'selected';
            }?> >
            <?php echo $val['name'];?>
            </option>
        <?php } ?>
        </select>
        </label>
        <br />
        <label>予算<span class="err_msg"><?php if(!empty($err_msg['price'])) echo $err_msg['price']; ?></span>
        <input type="text" name="price" value="<?php (!empty(getFormData('price'))) ? getFormData('price') : 0;  ?>">
        </label>

        <label>季節<span class="err_msg"><?php if(!empty($err_msg['season'])) echo $err_msg['season']; ?></span>
        <select name="category_season" id="">
            <option value="0" <?php if(getFormData('category_season') == 0){ echo 'selected';} ?>>選択してください</option>
            <?php foreach($dbCategorySeason as $key => $val){ ?>
            <option value="<?php echo $val['id']?>" <?php if(getFormData('category_season') == $val['id']){
                echo 'selected';
            }?> >
            <?php echo $val['season'];?>
            </option>
        <?php } ?>
        </select>
        </label>
        <br />
        <label>交通手段<span class="err_msg"><?php if(!empty($err_msg['access'])) echo $err_msg['access']; ?></span>
        <input type="text" name="access" value="<?php if(!empty($_POST['access'])) echo $_POST['access']; ?>">
        </label>

        <label>誰と？<span class="err_msg"><?php if(!empty($err_msg['belong'])) echo $err_msg['belong']; ?></span>
        <input type="text" name="belong" value="<?php if(!empty($_POST['belong'])) echo $_POST['belong']; ?>">
        </label>

        <label>詳細＊必須<span class="err_msg"><?php if(!empty($err_msg['detail'])) echo $err_msg['detail']; ?></span>
        <br />
        <textarea name="detail" cols="100" rows="10" style="height:150px;"><?php echo getFormData('detail'); ?></textarea>
        </label>
        <br />

        <label>画像<span class="err_msg"><?php if(!empty($err_msg['pic1'])) echo $err_msg['pic1']; ?></span>
        <input type="file" name="pic1" class="input-file">	
        <img src="<?php echo getFormData('pic1'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic1'))) echo 'display:none;' ?>">	
        </label>  
        <br />
        <div class="submit"><input value="作成" type="submit" class="js-click-ok" ></div>
    </form>
        </div>
</div>
<?php
require('footer.php');
?>
