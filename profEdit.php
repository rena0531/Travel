<?php
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「 プロフィール編集ページ');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

require('auth.php');

$dbFormData = getUser($_SESSION['user_id']);

debug('取得したユーザー情報：'.print_r($dbFormData,true));

if(!empty($_POST)){
    debug('POST送信があります。');

    $name = $_POST['name'];
    $age = $_POST['age'];
    $addr = $_POST['addr'];
    $email = $_POST['email'];

    if($dbFormData['name'] !== $name){
        validMaxLen($name, 'name');
    }
    if($dbFormData['age'] !== $age){
        validHalf($age, 'age');
        validMaxLen($age, 'age');
    }
    if($dbFormData['email'] !== $email){
        validMaxLen($email, 'email');
        if(empty($err_msg['email'])){
          validEmailDup($email);
        }
        validEmail($email, 'email');
        validRequired($email, 'email');
      }

    if(empty($err_msg)){
        debug('バリデーションチェックOKです。');

        try{
            $dbh = dbConnect();
            $sql = 'UPDATE users SET name = :name, age = :age, addr = :addr, email = :email WHERE id = :user_id ';
            $data = array(':name' => $name, ':age' => $age, ':addr' => $addr, ':email' => $email, ':user_id' =>$dbFormData['id']);
            $stmt = queryPost($dbh, $sql, $data);

            if($stmt){
                debug('マイページへ遷移します');
                header('Location:mypage.php');
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
$siteTitle = 'プロフィール編集';
require('head.php');
?>


<?php
require('header.php');
?>

<div class="profedit site-width">
    <form action="" method="post">
        <h1>プロフィール編集</h1>
        <div class="err_msg">
            <?php if(!empty($err_msg['common'])) echo $err_msg['common']; ?>
        </div>
        <label>名前:<?php if(!empty($err_msg['name']))  echo $err_msg['name']; ?>
        <input type="text" name="name" value="<?php echo getFormData('name'); ?>">
        </label>
        <label>年齢:<?php if(!empty($err_msg['age']))  echo $err_msg['age']; ?>
        <input type="text" name="age" value="<?php echo getFormData('age'); ?>">
        </label>
        <label>都道府県:<?php if(!empty($err_msg['addr']))  echo $err_msg['addr']; ?>
        <input type="text" name="addr" value="<?php echo getFormData('addr'); ?>">
        </label>
        <label>E-mail:<?php if(!empty($err_msg['email']))  echo $err_msg['email']; ?>
        <input type="text" name="email" value="<?php echo getFormData('email'); ?>">
        </label>
        <input value="変更する" type="submit">
    </form>
</div>

<?php
require('footer.php');
?>

