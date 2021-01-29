<?php

try {
    $dbh = new PDO('mysql:host=localhost;dbname=idea_blog', 'ideasoft', 'root');
} catch (PDOException $e) {
    echo $e->getMessage();
}


$nickname = $_POST['nickname'];
$body = $_POST['body'];
$blogId = $_POST['blog_id'];

$now = new \DateTime();
$query = "INSERT INTO blog_comment(blog_id, nickname, body, created_at) VALUES(:blog_id, :nickname, :body, :created_at)";
$dbStmt = $dbh->prepare($query);
$dbStmt->bindValue(':blog_id', $blogId, PDO::PARAM_INT);
$dbStmt->bindValue(':nickname', $nickname);
$dbStmt->bindValue(':body', $body);
$dbStmt->bindValue(':created_at', $now->format('Y-m-d H:i:s') );
$dbStmt->execute();

$response = [
    'nickname' => $nickname,
    'body' => $body,
    'blog_id' => $blogId,
    'created_at' => $now->format('Y-m-d H:i:s')
];
header('Content-Type: text/json');
echo json_encode($response);

/*
 <h1>Hoşgeldin, <?php echo $kullanici->member_name ?></h1>



