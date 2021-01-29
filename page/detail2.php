
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

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Blog Post</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="style2.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">

</head>

<body>

<header id="header">
<!-- Navigation -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
    <?php
    try {
        $dbh = new PDO('mysql:host=localhost;dbname=idea_blog', 'ideasoft', 'root');
    } catch (PDOException $e) {
        echo $e->getMessage();
    } ?>
    <div class="container">
        <a class="navbar-brand fs-3 text-uppercase" href="blog2.php">Benim Blogum</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse d-flex justify-content-md-end" id="navbarResponsive">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="blog2.php">Ana sayfa
                    </a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Kategoriler
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDropdown">
                        <?php
                        $sorgu=$dbh->prepare("SELECT * FROM blog_category");
                        $sorgu->execute();
                        $blogCategories = $sorgu->fetchAll(PDO::FETCH_ASSOC);
                        ?>
                        <?php foreach ($blogCategories as $blogCategory): ?>
                            <li><a class="dropdown-item" href=<?="/BLOG/category2.php?blogCategoryId=".$blogCategory['id']?>><?=$blogCategory['title']?></a></li>
                        <?php endforeach; ?>
                    </ul>
                <li>
                <a class="nav-link" href="blog2.php">Hakkımda</a>
                </li>
                <li>
                <a class="nav-link" href="blog2.php">İletişim</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
</header>

<!-- Page Content -->
<div class="container">

    <div class="row mt-5">

        <!-- Post Content Column -->
        <div class="col-lg-8 mt-5">



            <!-- Title -->
            <h1 class="mt-4"><?= $blog['title'] ?></h1>

            <hr>

            <!-- Date/Time -->
            <div class="d-flex justify-content-between">
            <span class="text-uppercase"> Yazar: <?= $blog['username'] ?></span><span class="text-uppercase">Tarih: <?= $blog['created_at'] ?> </span>
            </div>

            <hr>

            <!-- Preview Image -->
            <img class="img-fluid rounded" src="../img/bloger.jpg" alt="">

            <hr>

            <!-- Post Content -->
            <p class="lead"><?=$blog['body'] ?></p>

            <hr>

            <!-- Comments Form -->
            <?php
            $c = $dbh->prepare("select * from blog_comment where blog_id=?");
            $c->execute(array($id));
            $x = $c->fetchAll();
            $xx= $c->rowCount();

            if($xx){
                echo "<div class='alert alert-dark' role='alert' style='max-width: 19em'><h6 class='text-center'>Bu konuya <span class='badge rounded-pill bg-secondary fs-6'>".$xx."</span> yorum yapılmıştır.</h6></div>";
                echo '<div class="media mb-4" id="comment-container">';
                foreach ($x as $b){
                    ?>
                    <div class="card mt-3">
                        <div class="card-header d-flex justify-content-between">
                            <span>Ekleyen: <?php echo $b["nickname"];?></span><span>Tarih: <?php echo $b["created_at"]?></span>
                        </div>
                        <div class="card-body">
                            <p><?php echo $b["body"]?></p>
                        </div>
                    </div>
                <?php }
                echo '</div>';
            }else{
                echo "<div class='alert alert-dark' role='alert' style='max-width: 19em'><h6 class='text-center'>Bu konuya hiç yorum yapılmamıştır.</h6></div>";
            }
            ?>

            <div class="input-group mb-3">
                <span class="input-group-text">Kullanıcı adı:</span>
                <input type="text" class="form-control" id="nickname" aria-label="Username" aria-describedby="basic-addon1">
            </div>

            <div class="input-group">
                <span class="input-group-text">Yorumunuz: </span>
                <textarea class="form-control" id="body"  rows="5" aria-label="With textarea"></textarea>
            </div>
            <input class="btn btn-primary mt-3 mb-3" id="commentSubmitButton" type="submit" value="Gönder">


        </div>

        <!-- Sidebar Widgets Column -->
        <div class="col-md-4">

            <!-- Search Widget -->
            <div class="card my-4 mt-5">
                <h5 class="card-header">Ara</h5>
                <div class="card-body">
                    <form action="search.php" method="get">
                        <div class="input-group">
                            <input type="text"  name="search" class="form-control" placeholder="Arama...">
                            <button class="btn btn-secondary" type="submit">&nbsp&nbsp&nbsp<i class="bi bi-search"></i>&nbsp&nbsp&nbsp</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Side Widget -->
            <?php
            $sonveri = $dbh->prepare("select * from blog  order by id desc limit 0,5");
            $sonveri->execute(array());
            $sonvericek = $sonveri->fetchAll(PDO::FETCH_ASSOC);
            ?>
            <div class="card my-4">
                <h5 class="card-header ">Son Eklenen Yazılar</h5>
                <div class="card-body">
                    <?php foreach ($sonvericek as $vericek): ?>
                        <p class="fs-5"><i class="bi bi-caret-right"></i><a class="text-dark fs-5 text-decoration-none" href="detail2.php?id=<?=$vericek['id']?>"><?=$vericek["title"]?></a></p>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="card my-4">
                <h5 class="card-header">Sosyal Medyada Hesapları</h5>
                <div class="card-body">
                    <p class="fs-5"><a class="text-decoration-none text-#3B5998" href="blog2.php"><i class="bi bi-facebook"></i> | Facebook</a></p>
                    <p class="fs-5"><a class="text-decoration-none text-info" href="blog2.php"><i class="bi bi-twitter"></i> | Twitter</a></p>
                    <p class="fs-5"><a class="text-decoration-none text-danger" href="blog2.php"><i class="bi bi-youtube"></i> | Youtube</a></p>
                    <p class="fs-5"><a class="text-decoration-none text-#007BB6" href="blog2.php"><i class="bi bi-linkedin"></i> | Linkedin</a></p>
                    <p class="fs-5"><a class="text-decoration-none text-#3f729b" href="blog2.php"><i class="bi bi-instagram"></i> | İnstagram</a></p>
                </div>
            </div>

        </div>

    </div>
    <!-- /.row -->

</div>
<!-- /.container -->

<!-- Footer -->
<footer class="py-4 bg-dark">
    <div class="container">
        <p class="m-0 text-center text-white">Copyright &copy; Your Website 2020</p>
    </div>
    <!-- /.container -->
</footer>

<!-- Bootstrap core JavaScript -->
<script src="../assets/js/jquery-3.5.1.min.js" type="application/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
<script src="ajax.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function () {
        jQuery("#commentSubmitButton").on('click', function () {
            var nickname = jQuery("#nickname").val();
            var body = jQuery("#body").val();
            var blogId = "<?=$id?>";

            jQuery.ajax({
                method: "POST",
                url: "comment2.php",
                data: {"nickname": nickname, "body": body, "blog_id": blogId}
            }).done(function (msg) {
                var template ='<div class="card mt-3"> <div class="card-header d-flex justify-content-between"> <span>Ekleyen:{nickname}</span><span>Tarih:{created_at}</span> </div> <div class="card-body"> <p>{body}</p> </div> </div>';
                var response = template.replace('{nickname}', msg.nickname).replace('{created_at}', msg.created_at).replace('{body}', msg.body);
                jQuery("#comment-container").prepend(response);
            });
        });
    });

</script>
</body>

</html>
