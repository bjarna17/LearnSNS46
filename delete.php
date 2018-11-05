<?php
    require('functions.php');
    //DBに接続
    require('dbconnect.php');
    v($_GET['feed_id'],"feed_id");
    //削除したいfeedのIDを取得
    $feed_id = $_GET['feed_id'];

    //Delete文を作成
    $sql = "DELETE FROM `feeds` WHERE `feeds`.`id`=?";
    //Delete実行
    $stmt = $dbh->prepare($sql); //ここを対象にしますよ
    $data = array($feed_id); //範囲を選択します
    $stmt->execute($data); //ここを範囲でお願いします
    //タイムライン一覧に戻る
    header('Location: timeline.php');
    exit();

?>