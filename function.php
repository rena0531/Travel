<?php
//ログ
ini_set('log_errors','on');
ini_set('error_log','php.log');

//デバッグ
$debug_flg = true;
function debug($str){
  global $debug_flg;
  if(!empty($debug_flg)){
    error_log('デバッグ：'.$str);
  }
}


//セッション準備など
session_save_path("/var/tmp/");
ini_set('session.gc_maxlifetime',60*60*24*30);
ini_set('session.cookie_lifetime',60*60*24*30);
session_start();

//画面表示処理開始ログ吐き出し関数
function debugLogStart(){
    debug('>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>> 画面表示処理開始');
    debug('セッションID：'.session_id());
    debug('セッション変数の中身：'.print_r($_SESSION,true));
    debug('現在日時タイムスタンプ：'.time());
    if(!empty($_SESSION['login_date']) && !empty($_SESSION['login_limit'])){
      debug( 'ログイン期限日時タイムスタンプ：'.( $_SESSION['login_date'] + $_SESSION['login_limit']));
    }
}

//定数
define('MSG01','入力必須です');
define('MSG02','emailの形式で入力してください');
define('MSG03','パスワードが一致しません');
define('MSG04','半角数字で入力してください');
define('MSG05','6文字以上で入力してください');
define('MSG06','255文字以内で入力してください');
define('MSG07','エラーが発生しました。しばらく経ってからやり直してください。');
define('MSG08','そのEmailはすでに登録されています');
define('MSG09','Emailまたはパスワードが違います');
define('MSG10', '半角数字で入力してください');
define('MSG11', 'そのEmailは登録されていません');
define('MSG12', '認証キーが一致しません');
define('MSG13' ,'期限切れです、再発行してください');
define('MSG14', '文字で入力してください');
define('MSG15', 'パスワードが一致しません');
define('MSG16', '古いパスワードと新しいパスワードが同じです');
define('MSG17','正しくありません');


//関数
$err_msg = array();


//入力必須
function validRequired($str, $key){
    if($str === ''){
        global $err_msg;
        $err_msg[$key] = MSG01;
    }
}

//email形式
function validEmail($str, $key){
    if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\?\*\[|\]%'=~^\{\}\/\+!#&\$\._-])*@([a-zA-Z0-9_-])+\.([a-zA-Z0-9\._-]+)+$/", $str)){
        global $err_msg;
        $err_msg[$key] = MSG02;
    }
}
//パスワード一致
function validMatch($str1, $str2, $key){
    if($str1 !== $str2){
        global $err_msg;
        $err_msg[$key] = MSG03;
    }
}

function validPass($str,$key){
    validHalf($str,$key);
    validMinLen($str,$key);
    validMaxLen($str,$key);
}

//パスワード　半角数字
function validHalf($str, $key)
{
    if (!preg_match("/^[a-zA-Z0-9]+$/", $str)) {
        global $err_msg;
        $err_msg[$key] = MSG04;
    }
}

function validMinLen($str, $key, $min = 6)
    {
        if (mb_strlen($str) < $min) {
            global $err_msg;
            $err_msg[$key] = MSG05;
        }
    }

function validMaxLen($str, $key, $max = 255)
    {
        if (mb_strlen($str) > $max) {
            global $err_msg;
            $err_msg[$key] = MSG06;
        }
    }
function validLength($str, $key, $len = 8){
        if( mb_strlen($str) !== $len ){
          global $err_msg;
          $err_msg[$key] = $len . MSG14;
        }
      }

function validEmailDup($email)
    {
        global $err_msg;
        try {
            $dbh = dbConnect();
            $sql = 'SELECT count(*) FROM users WHERE email = :email AND delete_flg = 0';
            $data = array(':email' => $email);
            $stmt = queryPost($dbh, $sql, $data);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!empty(array_shift($result))) {
                $err_msg['email'] = MSG08;
            }
            return $stmt;
        } catch (Exception $e) {
            error_log('エラー発生:' . $e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }

function makeRandKey($length = 8) {
        static $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJLKMNOPQRSTUVWXYZ0123456789';
        $str = '';
        for ($i = 0; $i < $length; ++$i) {
            $str .= $chars[mt_rand(0, 61)];
        }
        return $str;
    }

function getPlan($u_id, $p_id){
        debug('商品情報を取得します。');
        debug('ユーザーID：'.$u_id);
        debug('プランID：'.$p_id);
   
        try {
            $dbh = dbConnect();
            $sql = 'SELECT * FROM plan WHERE user_id = :user_id AND id = :p_id AND delete_flg = 0';
            $data = array(':user_id' => $u_id, ':p_id' => $p_id);
            $stmt = queryPost($dbh, $sql, $data);

            if ($stmt) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }else{
                return false;
            }
        } catch (Exception $e) {
            error_log('エラー発生:' . $e->getMessage());
        }
    }

function validSelect($str, $key){
        if(!preg_match("/^[0-9]+$/", $str)){
          global $err_msg;
          $err_msg[$key] = MSG17;
        }
      }

function getCategoryPrefecture(){
        debug('カテゴリー情報を取得します。');
        try {
            $dbh =dbConnect();
            $sql = 'SELECT * FROM category_prefecture';
            $data = array();
            $stmt =queryPost($dbh, $sql, $data);

            if ($stmt) {
                return $stmt->fetchAll();
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log('エラー発生：' .$e->getMessage());
        }
    }

function getCategorySeason(){
        debug('カテゴリー情報を取得します。');
        try {
            $dbh =dbConnect();
            $sql = 'SELECT * FROM category_season';
            $data = array();
            $stmt =queryPost($dbh, $sql, $data);

            if ($stmt) {
                return $stmt->fetchAll();
            } else {
                return false;
            }
        } catch (Exception $e) {
            error_log('エラー発生：' .$e->getMessage());
        }
    }

function getPlanAll(){
        try {
            $dbh = dbConnect();
            $sql = 'SELECT p.id, p.planname, p.category_season, p.category_prefecture, p.price, p.access, p.belong, p.detail, p.pic1, p.user_id, p.delete_flg, p.update_date, c_p.name AS c_p_name, c_s.season AS c_s_season FROM plan AS p 
                LEFT JOIN category_season AS c_s ON p.category_season = c_s.id 
                LEFT JOIN category_prefecture AS c_p ON p.category_prefecture = c_p.id 
                WHERE p.delete_flg = 0';
            $data = array();
            $stmt = queryPost($dbh, $sql, $data);
            $result = $stmt->fetchAll();
            if($result){
              debug('クエリ成功');
              return $result;
            }
      
        } catch (Exception $e) {
            error_log('エラー発生:' . $e->getMessage());
        }
      }


function getPlanOne($p_id){
    try {
        $dbh = dbConnect();
        $sql = 'SELECT p.id, p.planname, p.category_season, p.category_prefecture, p.price, p.access, p.belong, p.detail, p.pic1, p.user_id, p.delete_flg, p.update_date, c_p.name, c_s.season FROM plan AS p 
            LEFT JOIN category_season AS c_s ON p.category_season = c_s.id 
            LEFT JOIN category_prefecture AS c_p ON p.category_prefecture = c_p.id 
            WHERE p.id = :p_id AND p.delete_flg = 0';
        $data = array(':p_id' => $p_id);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetchAll();
        
        if($result){
          debug('クエリ成功!!!');
          return $result;
        }else{
            return false;
        }
  
    } catch (Exception $e) {
        error_log('エラー発生:' . $e->getMessage());
    }
  }

  function dataSearch($categoryPrefecture, $categorySeason){
    try {
      $dbh = dbConnect();
      $sql = 'SELECT p.id, p.planname, p.category_season, p.category_prefecture, p.price, p.access, p.belong, p.detail, p.pic1, p.user_id, p.delete_flg, p.update_date, c_p.name AS c_p_name, c_s.season AS c_s_season FROM plan AS p 
      LEFT JOIN category_season AS c_s ON p.category_season = c_s.id 
      LEFT JOIN category_prefecture AS c_p ON p.category_prefecture = c_p.id' ;
  
      if (!empty($categoryPrefecture)) {
          $sql .= ' WHERE category_prefecture = '.$categoryPrefecture;
      }
      if (!empty($categorySeason)) {
          $sql .= ' WHERE category_season = '.$categorySeason;
      }
      $data = array();
      $stmt = queryPost($dbh, $sql, $data);
      $result = $stmt->fetchAll();
      if($result){
        debug('クエリ成功');
        return $result;
      }
  
    }catch(Exception $e){
    error_log('エラー発生：' .$e->getMessage());
  }  
}

//データベース
function dbConnect()
        {
            $dsn = 'mysql:dbname=trip;host=localhost;charset=utf8';
            $user = 'root';
            $password = 'root';
            $options = array(
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
    );
            $dbh = new PDO($dsn, $user, $password, $options);
            return $dbh;
        }

function queryPost($dbh, $sql, $data)
        {
            global $err_msg;
            $stmt = $dbh->prepare($sql);
            if (!$stmt->execute($data)) {
                debug('クエリに失敗しました。');
                debug('失敗したSQL：'.print_r($stmt->errorInfo(), true));
                $err_msg['common'] = MSG07;
                return 0;
            }
            debug('クエリ成功。');
            return $stmt;
        }

function getUser($user_id)
        {
            global $err_msg;
            try {
                $dbh = dbConnect();
                $sql = 'SELECT * FROM users WHERE id = :user_id';
                $data = array(':user_id' => $user_id);
                $stmt = queryPost($dbh, $sql, $data);
                if ($stmt) {
                    debug('クエリ成功。');
                } else {
                    debug('クエリ失敗。');
                }
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } catch (Excepton $e) {
                error_log('エラー発生：' . $e ->getMessage());
                $err_msg['commmon'] = MSG07;
            }
        }

function isLike($p_id, $u_id){
    debug('お気に入り情報があるかを確認します。');

    try{
        $dbh = dbConnect();
        $sql = 'SELECT * FROM recommend WHERE plan_id = :p_id AND user_id = :u_id';
        $data = array(':p_id' => $p_id, ':u_id' => $u_id);
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->rowCount(); 
        if($result){
            debug('お気に入りです');
            return $result;
        }else{
            debug('お気に入りではないです');
            return false;
        }

    }catch (Exception $e){
        error_log('エラー発生：'.$e->getMessage());
    }
}

function getLike(){
    try{
        $dbh = dbConnect();
        $sql = 'SELECT p.id, p.pic1, p.planname FROM plan AS p INNER JOIN recommend AS r on p.id = r.plan_id';
        $data = array();
        $stmt = queryPost($dbh, $sql, $data);
        $result = $stmt->fetchAll();
        if($result){
            debug('お気に入り！');
            return $result;
        }else{
            debug('お気に入りはないです');
            return false;
        }

    }catch (Exception $e){
        error_log('エラー発生：'.$e->getMessage());
    }
}

function isLogin(){
    if(!empty($_SESSION['login_date'])){
        debug('ログイン済みユーザーです');

        if($_SESSION['login_limit'] + $_SESSION['login_date'] < time()){
            debug('ログイン期限外です。');
            session_destroy();
            return false;
        }else{
            debug('ログイン期限内です');
            return true;
        }
    }else{
        debug('未ログインユーザーです');
        return false;
    }
}

///////
function sanitize($str)
        {
            return htmlspecialchars($str, ENT_QUOTES);
        }
function getFormData($str)
        {
            global $err_msg;
            global $dbFormData;
            if (!empty($dbFormData)) {
                if (!empty($err_msg[$str])) {
                    if (isset($_POST[$str])) {
                        return sanitize($_POST[$str]);
                    } else {
                        return sanitize($dbFormData[$str]);
                    }
                } else {
                    if (isset($_POST[$str]) && $_POST[$str] !== $dbFormData[$str]) {
                        return sanitize($_POST[$str]);
                    } else {
                        return sanitize($dbFormData[$str]);
                    }
                }
            } else {
                if (isset($_POST[$str])) {
                    return sanitize($_POST[$str]);
                }
            }
        }

function sendMail($from, $to, $subject, $comment)
        {
            if (!empty($to) && !empty($subject) && !empty($comment)) {
                mb_language("Japanese");
                mb_internal_encoding("UTF-8");
        
                $result = mb_send_mail($to, $subject, $comment, "From: ".$from);
                if ($result) {
                    debug('メールを送信しました。');
                } else {
                    debug('【エラー発生】メールの送信に失敗しました。');
                }
            }
        }

function uploadImg($file, $key)
        {
            debug('画像アップロード処理開始');
            debug('FILE情報：'.print_r($file, true));

            if (isset($file['error']) &&  is_int($file['error'])) {
                try {
                    switch ($file['error']) {
                case UPLOAD_ERR_OK:
                    break;
                case UPLOAD_ERR_NO_FILE:
                    throw new RuntimeException('ファイルが選択せれていません');
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    throw new RuntimeException('ファイルサイズが大きすぎます');
                default:
                    throw new RuntimeException('その他のエラーが発生しました');
            }

                    $type = @exif_imagetype($file['tmp_name']);
                    if (!in_array($type, [IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_GIF], true)){
                        throw new RuntimeException('画像形式が未対応です');
                    }
                    
                    $path = 'uploads/'.sha1_file($file['tmp_name']).image_type_to_extension($type);
                    if (!move_uploaded_file($file['tmp_name'], $path)) {
                        throw new Exception('ファイル保存時にエラーが発生しました');
                    }
                    chmod($path, 0644);

                    debug('ファイルは正常にアップロードされました');
                    debug('ファイルパス：'.$path);
                    return $path;
                } catch (RuntimeException $e) {
                    debug($e->getMessage());
                    global $err_msg;
                    $err_msg[$key] = $e->getMessage();
                }
            }
        }

       