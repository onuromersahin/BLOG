<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Blog Home</title>

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

<?php
$page = 1;
$forlimit = 5;  //aktif sayfanın sağında ve solunda kaç tane görüneceği
$rpp = 8;       //Bir sayfada kaç yazı görüneceği
if(isset($_GET['rpp']) && ((int)$_GET['rpp'] > 0)){
    $rpp = $_GET['rpp'];
}
if(isset($_GET['page']) && ((int)$_GET['page'] > 0)){
    $page = (int)$_GET['page'];
}
if(isset($_GET['search'])){
    if(strlen($_GET['search']) < 2){
        unset($_GET['search']);

    }
}
$offset = ($page-1)*$rpp;
$dbh->query("SET NAMES utf8");
$totalBlogCount  = getTotalBlogCount($dbh);
$totalPageCount = ceil(intval($totalBlogCount / $rpp));
$searchQuery = "";
if(isset($_GET['search'])){
    $searchQuery = "(blog.user_id=user.id) WHERE (title like :searchParam) OR (body like :searchParam)";
}

$query = "SELECT blog.*, user.username FROM blog INNER JOIN user ON (blog.user_id=user.id) ".$searchQuery." ORDER BY id DESC LIMIT $offset,$rpp";
$dbStmt = $dbh->prepare($query);
$dbStmt->execute();
$blogs = $dbStmt->fetchAll(PDO::FETCH_ASSOC);

/**
 * @param PDO $dbh
 */
function getTotalBlogCount($dbh)
{
    $searchQuery = "";
    if(isset($_GET['search'])){
        $searchQuery = "WHERE (blog.user_id=user.id) WHERE (title like :searchParam) OR (body like :searchParam)";
    }
    $query = "SELECT COUNT(blog.id) FROM blog ".$searchQuery;
    $dbStmt = $dbh->prepare($query);
    $dbStmt->execute();
    return (int) $dbStmt->fetchColumn();
}

?>


<!-- Page Content -->
<div class="container">

    <div class="row mt-5">

        <!-- Blog Entries Column -->
        <div class="col-md-8 mt-5">
            <!-- Blog Post -->
            <?php foreach ($blogs as $blog): ?>
            <div class="card mb-4">
                <img class="card-img-top" src="../img/blog2.jpg" alt="Card image cap">
                <div class="card-body">
                    <h2 class="card-title text-uppercase"><a class="text-decoration-none text-primary" href="detail2.php?id=<?=$blog['id']?>"><?= $blog['title'] ?></a></h2>
                    <p class="card-text"><?=substr($blog['body'],0,450)?>...</p>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="detail2.php?id=<?=$blog['id']?>" class="btn btn-primary">Devamını Oku&rarr;</a>
                    </div>
                </div>
                <div class="card-footer text-muted text-uppercase d-flex justify-content-between">
                    <span>Yazar: <?= $blog['username'] ?></span><span class="">Tarih: <?= $blog['created_at'] ?></span>
                </div>
            </div>
            <?php endforeach; ?>

            <?php
            if(isset($_GET['search'])){
                $search = $_GET["search"];
                $sorgu=$dbh->prepare("SELECT blog.*, user.username FROM blog INNER JOIN user ON (blog.user_id=user.id) WHERE (title like :searchParam) OR (body like :searchParam)");
                $sorgu->execute(array(":searchParam"=>"%".$search."%"));
                $sayi = $sorgu->rowCount();
                if($sayi){
                    echo "<div class='alert alert-dark' role='alert' style='max-width: 53em'><h6 class='text-center'>$search kelimesine ait <span class='badge rounded-pill bg-secondary fs-6'>".$sorgu->rowCount()."</span> adet sonuç bulunmuştur</h6></div>";
                    echo "<br><br><br>";
                    foreach ($sorgu as $blog){
                        ?>
                        <div class="card mb-4">
                            <img class="card-img-top" src="../img/blog2.jpg" alt="Card image cap">
                            <div class="card-body">
                                <h2 class="card-title text-uppercase"><a class="text-decoration-none" href="detail2.php?id=<?=$blog['id']?>"><?= $blog['title'] ?></a></h2>
                                <p class="card-text"><?=substr($blog['body'],0,450)?>...</p>
                                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                    <a href="detail2.php?id=<?=$blog['id']?>" class="btn btn-primary">Devamını Oku&rarr;</a>
                                </div>
                            </div>
                            <div class="card-footer text-muted text-uppercase d-flex justify-content-between">
                                <span>Yazar: <?= $blog['username'] ?></span><span class="">Tarih: <?= $blog['created_at'] ?></span>
                            </div>
                        </div>

                        <?php
                    }
                }else{
                    echo "<div class='alert alert-dark' role='alert' style='max-width: 53em'><h6 class='text-center'>Aranan kelimeye ait veri bulunamadı.</h6></div>";
                }
            }
            ?>
            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <?php
                    if($page>1){
                        echo "<li class='page-item'><a class='page-link' href='blog2.php?page=".($page-1)."'>Önceki</a></li>";
                    }
                    for ( $i = $page-$forlimit; $i<$page+$forlimit+1;$i++){
                        if($i>0 && $i<=$totalPageCount){
                            if($i == $page){
                                echo "<li class='page-item active'><a class='page-link' href='#'>".$i."</a></li>"; }
                            else{
                                echo "<li class='page-item'><a class='page-link' href='blog2.php?page=".$i."'>".$i."</a></li>";
                            }}}
                    if($page != $totalPageCount ) {
                        echo "<li class='page-item'><a class='page-link' href='blog2.php?page=" . ($page+1) . "'>Sonraki</a></li>"; }
                    ?>
                </ul>
            </nav>

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
                        <button class="btn btn-dark" type="submit">&nbsp&nbsp&nbsp<i class="bi bi-search"></i>&nbsp&nbsp&nbsp</button>
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
                    <p class="fs-5"><a class="text-dark fs-5 text-decoration-none" href="detail2.php?id=<?=$vericek['id']?>"><i class="bi bi-caret-right"></i><?=$vericek["title"]?></a></p>
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
        <p class="m-0 text-center text-white">Copyright &copy; My Website 2021</p>
    </div>
    <!-- /.container -->
</footer>

<!-- Bootstrap core JavaScript -->
<script src="../assets/js/jquery-3.5.1.min.js" type="application/javascript"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>

</body>
</html>