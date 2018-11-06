<?php
    require('functions.php');
    v($_GET['feed_id'],"feed_id");
    require('dbconnect.php');

    $feed_id = $_GET['feed_id'];


    $sql = 'SELECT `f`.*,`u`.`name`,`u`.`img_name` AS `profile_image` FROM `feeds` AS `f` INNER JOIN `users` AS `u` ON `f`.`user_id`= `u`.`id` where `f`.`id` = ?';

    $data = array($feed_id);

    $stmt = $dbh->prepare($sql); //今からこのsql文を実行するよという宣言、左辺をオブジェクトとという
    $stmt->execute($data); //?に$dataを当てはめるよ

    //取得できた編集対象のデータを$feedに格納

    $feed = $stmt->fetch(PDO::FETCH_ASSOC);


    //今データを格納した$feedを使って、画面に編集データを表示
    //アップロード機能
    //更新ボタンを押した時に、sqlを更新させる
        if (!empty($_POST)) {

        //変更したつぶやきをDBに上書き保存をする
        $update_sql = "UPDATE `feeds` SET `feed` = ? WHERE `feeds`.`id` = ?";
        //prepareを使うために情報を格納する
        $data = array($_POST["feed"],$feed_id);

        //sql文の実行
        $stmt = $dbh->prepare($update_sql);
        $stmt->execute($data);

        //タイムラインへ遷移
        header("Location: timeline.php");
        exit();
    }



?>

 

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>Learn SNS</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/font-awesome/css/font-awesome.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body style="margin-top: 60px;">
    <div class="container">
        <div class="row">
            <!-- ここにコンテンツ -->
            <div class="col-xs-4 col-xs-offset-4">
                <form class="form-group" method="post">
                    <img src="user_profile_img/<?php echo $feed['profile_image']; ?>" width="60">
                    <?php echo $feed['name']; ?><br>
                    <?php echo $feed['created']; ?><br>
                    <textarea name="feed" class="form-control"><?php echo $feed['feed']; ?></textarea>
                    <input type="submit" value="更新" class="btn btn-warning btn-xs">

                </form>
            </div>
        </div>
    </div>
    <script src="assets/js/jquery-3.1.1.js"></script>
    <script src="assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="assets/js/bootstrap.js"></script>
</body>
</html>
