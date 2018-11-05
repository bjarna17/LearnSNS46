<?php
    session_start();
    require('functions.php');
    require('dbconnect.php');

    // v($_SESSION["id"],'$_SESSION["id"]');

    //ユーザー情報の取得
    $sql = 'SELECT * FROM `users` WHERE `id`=?';
    $data = array($_SESSION['id']);

    $stmt = $dbh->prepare($sql); //今からこのsql文を実行するよという宣言、左辺をオブジェクトとという
    $stmt->execute($data); //?に$dataを当てはめるよ

    $signin_user = $stmt->fetch(PDO::FETCH_ASSOC);

    // v($signin_user,'$_signin_user');



    $feed = '';
    if(!empty($_POST)){
      $feed = $_POST['feed'];
      if ($feed == '') {
          $validations['feed']='blank';
      }else{
        //データベースに投稿を保存
        $file_name= "";
        $user_id = $signin_user['id'];
        $sql = 'INSERT INTO `feeds` SET `feed`=?, `user_id`=?,`created`=NOW()';
        $stmt = $dbh->prepare($sql);
        $data = array($feed,$user_id);
        $stmt->execute($data);
      }
    }

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <title></title>
  <meta charset="utf-8">
  <style>
  .error_msg{
    color:red;
    font-size:12px;
  }
  </style>
</head>
<body>
  ユーザー情報 <br>
  <img width="150" src="user_profile_img/<?php echo $signin_user['img_name']; ?>">
  <?php echo $signin_user['name'] ?><br>
  <br>
  <a href="signout.php">サインアウト</a>
  <form method="POST" action="">
  <textarea row="5" name ="feed"></textarea>
  <?php if(isset($validations['feed']) && $validations['feed'] == 'blank'): ?>
  <span class="error_msg">投稿データを入力して下さい</span>
<?php endif; ?><br>
  <input type="s  ubmit" value="投稿">
  </form>
</body>
</html>