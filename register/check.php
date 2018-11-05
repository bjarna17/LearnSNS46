<?php
    session_start();
    require('../functions.php');
    require('../dbconnect.php');

    if (!isset($_SESSION['46_LearnSNS'])) {
        header('Location: signup.php');
    }
    v($_POST,'$_POST');

    $name = $_SESSION['46_LearnSNS']['name'];
    $email = $_SESSION['46_LearnSNS']['email'];
    $password = $_SESSION['46_LearnSNS']['password'];
    $file_name = $_SESSION['46_LearnSNS']['file_name'];

    if (!empty($_POST)) {
        $hash_password = password_hash($password,PASSWORD_DEFAULT);
        $sql = 'INSERT INTO `users` SET `name`=?, `email`=?, `password`=?, `img_name`=?, `created`=NOW()';
        $stmt = $dbh->prepare($sql);
        $data = array($name,$email,$hash_password,$file_name);
        $stmt->execute($data);

        unset($_SESSION['46_LearnSNS']);
        header('Location: thanks.php');
        exit();
    }



?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <title></title>
  <meta charset="utf-8">
</head>
<body>
  <div>
    ユーザー名：<?= h($name); ?>
  </div>
  <div>
    メールアドレス：<?= h($email); ?>
  </div>
  <div>
    パスワード：入力されたパスワード
  </div>
  <div>
    プロフィール画像：
  <img src="../user_profile_img/<?= h($file_name); ?>" width="100">
  </div>

  <form method = "POST" action = "">
    <input type="hidden" name="hoge" value="fuga">
    <input type="submit" value="アカウント作成">
  </form>
</body>
</html>