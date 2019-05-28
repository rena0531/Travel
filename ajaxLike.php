<?php
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debug('「　Ajax　');
debug('「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

if(isset($_POST['planId']) && isset($_SESSION['user_id']) && isLogin()){
    debug('POST送信があります。');
    $p_id = $_POST['planId'];
    debug('商品ID：'.$p_id);

    try {
     
      $dbh = dbConnect();
      $sql = 'SELECT * FROM recommend WHERE plan_id = :p_id AND user_id = :u_id';
      $data = array(':u_id' => $_SESSION['user_id'], ':p_id' => $p_id);
      $stmt = queryPost($dbh, $sql, $data);
      $resultCount = $stmt->rowCount();
      debug($resultCount);
      if(!empty($resultCount)){
        $sql = 'DELETE FROM recommend WHERE plan_id = :p_id AND user_id = :u_id';
        $data = array(':u_id' => $_SESSION['user_id'], ':p_id' => $p_id);
        $stmt = queryPost($dbh, $sql, $data);
      }else{
        $sql = 'INSERT INTO recommend (plan_id, user_id, create_date) VALUES (:p_id, :u_id, :date)';
        $data = array(':u_id' => $_SESSION['user_id'], ':p_id' => $p_id, ':date' => date('Y-m-d H:i:s'));
        $stmt = queryPost($dbh, $sql, $data);
      }
  
    } catch (Exception $e) {
      error_log('エラー発生:' . $e->getMessage());
    }
  }

debug('Ajax処理終了 <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<<');
?>