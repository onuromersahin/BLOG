<?php

try {
    $dbh = new PDO('mysql:host=localhost;dbname=idea_blog', 'ideasoft', 'root');
} catch (PDOException $e) {
    echo $e->getMessage();
}
$id = $_GET["id"];
$query = "SELECT blog.*, user.username FROM blog INNER JOIN user ON (blog.user_id=user.id) WHERE blog.id=:id";
$dbStmt = $dbh->prepare($query);
$dbStmt->bindValue(':id', $id, PDO::PARAM_INT);
$dbStmt->execute();
$blog = $dbStmt->fetch(PDO::FETCH_ASSOC);
?>


<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="./assets/css/bootstrap.css">
</head>
<body>
<div class="sol_menu">
    <div class="kategoribaslik">
        <p>
        <h1 id="baslikbir">Kategoriler</h1>
        <p>
        <ul>
            <?php
            $sorgu=$dbh->prepare("SELECT * FROM blog_category");
            $sorgu->execute();
            $blogCategories = $sorgu->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <?php foreach ($blogCategories as $blogCategory): ?>
                <li><a href=<?="/BLOG/category.php?blogCategoryId=".$blogCategory['id']?>><?=$blogCategory['title']?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>


<div class="sag_icerik">
    <div class="ozetbaslik">
        <h1 id="ozetadi"><?= $blog['title'] ?></h1>
        <h3 id="yazar1">Yazan: <?= $blog['username'] ?></h3>
        <h3 id="tarih1">Tarih: <?= $blog['created_at'] ?></h3>
    </div>
    <p><?=$blog['body'] ?></p>

    <?php
    $c = $dbh->prepare("select * from blog_comment where blog_id=?");
    $c->execute(array($id));
    $x = $c->fetchAll();
    $xx= $c->rowCount();


    if($xx){

        echo "<div class='yorumSayisi'>Bu konuya ".$xx." yorum yapılmış.</div>";
        echo '<div id="comment-container">';
        foreach ($x as $b){
            ?>
            <div class="yorum">
                <h4>ekleyen: <?php echo $b["nickname"];?><span>tarih: <?php echo $b["created_at"]?></span></h4>
                <p><?php echo $b["body"]?></p>
            </div>
            <?php
        }
        echo '</div>';
    }else{
        echo "<div class='mesajiniz'>Bu konuya hiç yorum yazılmamış.</div>";
    }
    ?>

    <div class="alert"></div>
    <table cellpadding="5" cellspacing="5">
        <tr>
            <td class="x">Nickname:</td>
            <td class="x"><input type="text" id="nickname" name="nickname" placeholder="Nickname.."></td>
        </tr>
        <tr>
            <td class="x">Yorum:</td>
            <td class="x"><textarea name="Yorum" id="body" cols="50" rows="10" placeholder="Yorumunuz.."></textarea></td>
        </tr>
        <tr>
            <td></td>
            <td class="x"><input id="commentSubmitButton" type="button" name="" value="Gönder"></td>
        </tr>
    </table>

</div>


<script src="assets/js/jquery-3.5.1.min.js" type="application/javascript"></script>
<script src="./assets/js/bootstrap.bundle.js" type="application/javascript"></script>
<script src="ajax.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#commentSubmitButton").on('click', function () {
            var nickname = jQuery("#nickname").val();
            var body = jQuery("#body").val();
            var blogId = "<?=$id?>";

            jQuery.ajax({
                method: "POST",
                url: "comment.php",
                data: {"nickname": nickname, "body": body, "blog_id": blogId}
            }).done(function (msg) {
                var template = '<div class="yorum"><h4>ekleyen: {nickname}<span>tarih: {created_at}</span></h4><p>{body}</p></div>';
                var response = template.replace('{nickname}', msg.nickname).replace('{created_at}', msg.created_at).replace('{body}', msg.body);
                jQuery("#comment-container").prepend(response);
            });
        });
    });

</script>
</body>
</html>