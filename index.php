<?php
require_once(__DIR__ . '/./functions.php');
session_start();
date_default_timezone_set('Asia/Tokyo');

createToken();
$pdo = getPdoInstance();
$stmts = getDb($pdo);

if($_SERVER['REQUEST_METHOD'] === 'POST')
{
  validateToken();
  postMessage($pdo);
    }


?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>PHPとSqlite3で掲示板を作ってみる</h1>
  <main>
      <?php foreach ($stmts as $stmt): ?>
      <div class="post">
        <div class="meta">
          <span class="number">
            <?= h($stmt->id); ?>
          </span>
          <span class="name">
            <?= h($stmt->name); ?>
          <span class="date">
            <?= h($stmt->date); ?>
        </div>
        </span>
        <div class="message"><?= h($stmt->message); ?></div>
      </div>
      <?php endforeach; ?>
  </main>

  <div class="formbox">
    <span>レスを投稿する</span>
    <!-- <form action="?action=message" method="post"> -->
    <form action="" method="post">
      <ul>
        <li>
          <input type="text" name="name" placeholder="名前(省略可)">
        </li>
        <li>
          <textarea type="text" name="message" placeholder="コメント内容" rows="5" cols="7"></textarea>
        </li>
        <li>
          <input type="hidden" name="token" value="<?= h($_SESSION['token']); ?>">
          <input type="hidden" name="date" value="<?= h(date("Y/m/d H:i:s")); ?>">
          <button type="submit">送信</button>
        </li>
      </ul>
  </form>
  </div>

</body>
</html>