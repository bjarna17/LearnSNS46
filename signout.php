<?php
    session_start();

//SESSION変数の破棄
    $_SESSION = [];
//サーバー内の$_SESSIONのクリア,箱そのものを破棄する関数,session変数専用のunset
    session_destroy();
//signin.phpの移動
    header('Location: signin.php');
    exit();//これ以降の処理をしない
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <title></title>
  <meta charset="utf-8">
</head>
<body>

</body>
</html>