<?php
  // timeline.phpの処理を記載
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


    //POST送信されたら空チェック
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

    //一覧データの取得
    $sql = 'SELECT * FROM `feeds` ORDER BY `created` DESC';
    //一覧データを取得するSELECT文を実行
    $sql = 'SELECT `f`.*,`u`.`name`,`u`.`img_name` AS `profile_image` FROM `feeds` AS `f` INNER JOIN `users` AS `u` ON `f`.`user_id`= `u`.`id` ORDER BY `created` DESC';
    //DESC 大きい数字から小さい数字に並べる
    //ASC 小さい数字から大きい数字に並べる
    $data = array();
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

    $feeds = []; //投稿データを全て格納する配列
    //繰り返す回数が決まっていないときはwhile文を使用
    while (true) {
        $feed = $stmt->fetch(PDO::FETCH_ASSOC); //１データずつ取得する
        //fetchはデータがないときにfalseを返す性質がある
        if ($feed == false) {
          //取得できるデータはすべてできているので、繰り返しを中断する
          break;
        }
        $feeds[] = $feed; //[]は配列の末尾にデータを追加するという意味
        //if文を下に書くとfalseも配列の中に入ってしまう
    }
    // v($feeds,'feeds');




?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <style>
  .error_msg{
    color:red;
    font-size:12px;
  }
  </style>
  <title>Learn SNS</title>
  <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="assets/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="assets/css/style.css">
</head>
<body style="margin-top: 60px; background: #E4E6EB;">
  <div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse1" aria-expanded="false">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">Learn SNS</a>
      </div>
      <div class="collapse navbar-collapse" id="navbar-collapse1">
        <ul class="nav navbar-nav">
          <li class="active"><a href="#">タイムライン</a></li>
          <li><a href="#">ユーザー一覧</a></li>
        </ul>
        <form method="GET" action="" class="navbar-form navbar-left" role="search">
          <div class="form-group">
            <input type="text" name="search_word" class="form-control" placeholder="投稿を検索">
          </div>
          <button type="submit" class="btn btn-default">検索</button>
        </form>
        <ul class="nav navbar-nav navbar-right">
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><img src="user_profile_img/<?php echo $signin_user['img_name']; ?>" width="30" class="img-circle"><?php echo $signin_user['name'] ?><span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="#">マイページ</a></li>
              <li><a href="signout.php">サインアウト</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="container">
    <div class="row">
      <div class="col-xs-3">
        <ul class="nav nav-pills nav-stacked">
          <li class="active"><a href="timeline.php?feed_select=news">新着順</a></li>
          <li><a href="timeline.php?feed_select=likes">いいね！済み</a></li>
          <!-- <li><a href="timeline.php?feed_select=follows">フォロー</a></li> -->
        </ul>
      </div>
      <div class="col-xs-9">
        <div class="feed_form thumbnail">
          <form method="POST" action="">
            <div class="form-group">
              <textarea name="feed" class="form-control" rows="3" placeholder="Happy Hacking!" style="font-size: 24px;"></textarea><br>
            </div>
            <input type="submit" value="投稿する" class="btn btn-primary">
              <?php if(isset($validations['feed']) && $validations['feed'] == 'blank'): ?>
                <span class="error_msg">投稿データを入力して下さい</span>
              <?php endif; ?><br>
          </form>
        </div>
        <?php foreach ($feeds as $feed_each) : //fetchの逆作業?>
          <div class="thumbnail">
            <div class="row">
              <div class="col-xs-1">
                <img src="user_profile_img/<?php echo $feed_each['profile_image'] ?>" width="40">
              </div>
              <div class="col-xs-11">
                <?php echo $feed_each['name'] ?><br>
                <a href="#" style="color: #7F7F7F;"><?php echo $feed_each["created"] ?></a>
              </div>
            </div>
            <div class="row feed_content">
              <div class="col-xs-12" >
                <span style="font-size: 24px;"><?php echo $feed_each["feed"]; ?></span>
              </div>
            </div>
            <div class="row feed_sub">
              <div class="col-xs-12">
                <form method="POST" action="" style="display: inline;">
                  <input type="hidden" name="feed_id" >
                    <input type="hidden" name="like" value="like">
                    <button type="submit" class="btn btn-default btn-xs"><i class="fa fa-thumbs-up" aria-hidden="true"></i>いいね！</button>
                </form>
                <span class="like_count"><?php echo $feed_each["like_count"] ?></span>
                <span class="comment_count">コメント数 <?php echo $feed_each["comment_count"] ?></span>
                  <?php if($_SESSION["id"] == $feed_each["user_id"]): ?>
                  <a href="edit.php?feed_id=<?php echo $feed_each['id'] ?>" class="btn btn-success btn-xs">編集</a>
                  <a onclick="return confirm('ほんとに消すの？');" href="delete.php?feed_id=<?php echo $feed_each['id'] ?>" class="btn btn-danger btn-xs">削除</a>
                <?php endif; ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
        <div aria-label="Page navigation">
          <ul class="pager">
            <li class="previous disabled"><a href="#"><span aria-hidden="true">&larr;</span> Newer</a></li>
            <li class="next"><a href="#">Older <span aria-hidden="true">&rarr;</span></a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <script src="assets/js/jquery-3.1.1.js"></script>
  <script src="assets/js/jquery-migrate-1.4.1.js"></script>
  <script src="assets/js/bootstrap.js"></script>
</body>
</html>

