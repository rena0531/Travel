<header>
    <div>
        <h1><a href="index.php">Travel</a></h1>
        <div class="plan"><a href="planRegist.php">プラン登録</a></div>
         <nav id="nav" class="top-nav">
        <ul>
            <?php 
            if (empty($_SESSION['user_id'])) {
            ?>
            <li><a href="login.php">ログイン</a></li>
            <li><a href="signup.php">ユーザー登録</a></li>
            <?php
            }else{
                ?>
            <li><a href="mypage.php">マイページ</a></li>
            <li><a href="logout.php">ログアウト</a></li>
            <?php
            }
            ?>
            
        </ul>
    </nav>
    </div>
</header>
