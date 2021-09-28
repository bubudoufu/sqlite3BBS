<?php
// エスケープ処理
function h($str)
{
return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
// トークン作成
function createToken()
{
    if (!isset($_SESSION['token'])) {
      $_SESSION['token'] = bin2hex(random_bytes(32));
    }
}
// トークンの検証
function validateToken()
{
if (
    empty($_SESSION['token']) ||
    $_SESSION['token'] !== filter_input(INPUT_POST, 'token')
) {
exit('無効なリクエストです');
}
}
// pdoインスタンス生成
function getPdoInstance()
{
try{
    $pdo = new PDO('sqlite:データベースのパス'); //  PDOの引数に(sqlite:データベースのパス)で指定する
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // エラーが起きた時例外を投げる
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); // 連想配列形式でデータを取得する
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // 指定した型に合わせる

    return $pdo;
    
    }catch(PDOException $e){
    //echo $e->getMessage();
    exit('エラーが発生しました');
    }
}
// フォームから値を受け取った時の処理
function postMessage($pdo)
{   // 空文字が送られてきた時の処理
    $name = trim(filter_input(INPUT_POST, 'name'));
    if($name === ''){
      $name = '名無しさん';
    }
    $date = filter_input(INPUT_POST, 'date');
    if($date === ''){
      $date = '日時を取得できませんでした';
    }
    $message = trim(filter_input(INPUT_POST, 'message'));
    if($message === ''){
      echo '<script>alert("コメントを入力してください。")</script>';
      return;
    }
    // SQL文の実行
    $stmt = $pdo->prepare("INSERT INTO test (name, date, message) VALUES (:name, :date, :message)");
    $stmt->bindValue('name', $name);
    $stmt->bindValue('date', $date);
    $stmt->bindValue('message', $message);
    $stmt->execute();
  
    header("Location:" . $_SERVER['PHP_SELF']);
    exit;
}
//データーベースからデーターを取得
function getDb($pdo)
{
    $stmt = $pdo->prepare("SELECT * FROM test");
    $stmt->execute();
    $stmts = $stmt->fetchAll();
    return $stmts;
}