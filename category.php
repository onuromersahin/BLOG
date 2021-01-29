<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>BLOG</title>
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="./assets/css/bootstrap.css">
</head>
<body>
    <?php

        try {
            $dbh = new PDO('mysql:host=localhost;dbname=idea_blog', 'ideasoft', 'root');
        }catch (PDOException $e){
            echo $e->getMessage();
        }
    ?>
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
    $blogCategoryId = (int)$_GET['blogCategoryId'];
    $offset = ($page-1)*$rpp;
    $dbh->query("SET NAMES utf8");
    $totalBlogCount  = getTotalBlogCount($dbh);
    $totalPageCount = ceil(intval($totalBlogCount / $rpp));
    $searchQuery = "";
    if(isset($_GET['search'])){
        $searchQuery = " AND ((title like :searchParam) OR (body like :searchParam))";
    }
    $blogCategoryQueryItem = " INNER JOIN blog_to_blog_category AS b2bc ON (b2bc.blog_id = blog.id) INNER JOIN blog_category AS bc ON (b2bc.blog_category_id = bc.id) WHERE bc.id = :blogCategoryId";
    $query = "SELECT blog.*, user.username FROM blog INNER JOIN user ON (blog.user_id=user.id) ".$blogCategoryQueryItem . $searchQuery." ORDER BY blog.id DESC LIMIT $offset,$rpp";
    $dbStmt = $dbh->prepare($query);
    $dbStmt->bindValue(':blogCategoryId', $blogCategoryId, PDO::PARAM_INT);
    if(isset($_GET['search'])){
        $dbStmt->bindValue('searchParam', $_GET['search']);
    }
    $dbStmt->execute();
    $blogs = $dbStmt->fetchAll(PDO::FETCH_ASSOC);


    /* ****TOPLAM SAYFA SAYTISI BULMANIN FARKLI YOLU****
    $a= $dbh->prepare("select * from blog");
    $a->execute(array());
    $toplam = $a->rowCount();
    echo $toplam; exit();
    */

    /**
     * @param PDO $dbh
     */
    function getTotalBlogCount($dbh)
    {
        if(isset($_GET['search'])){
            $searchQuery = " AND ((title like :searchParam) OR (body like :searchParam))";
        }
        $blogCategoryQueryItem = " INNER JOIN blog_to_blog_category AS b2bc ON (b2bc.blog_id = blog.id) INNER JOIN blog_category AS bc ON (b2bc.blog_category_id = bc.id) WHERE bc.id = :blogCategoryId";
        $query = "SELECT COUNT(blog.id) FROM blog INNER JOIN user ON (blog.user_id=user.id) ".$blogCategoryQueryItem . $searchQuery;
        $dbStmt = $dbh->prepare($query);
        $dbStmt->bindValue(':blogCategoryId', $_GET['blogCategoryId'], PDO::PARAM_INT);
        if(isset($_GET['search'])){
            $dbStmt->bindValue('searchParam', $_GET['search']);
        }
        $dbStmt->execute();
        return (int) $dbStmt->fetchColumn();
    }

    ?>


    <div class="ara">
        <form action="" method="get">
        <input type="text" name="search"><input type="submit" value="ara"/>
        </form>
    </div>

    <?php foreach ($blogs as $blog): ?>
        <div class="yazibaslik">
            <h1 id="baslikiki"><a href="detail.php?id=<?=$blog['id']?>"><?= $blog['title'] ?></a></h1>
            <h3 id="tarih"><?= $blog['created_at'] ?></h3>
            <h3 id="yazar"><?= $blog['username'] ?></h3>
        </div>
        <p><?=substr($blog['body'],0,450)?>...</p>
        <div class="devam"><a href="detail.php?id=<?=$blog['id']?>">Devamını Oku</a></div>
    <?php endforeach; ?>

    <?php
    if(isset($_GET['search'])){
        $search = $_GET["search"];
        $sorgu=$dbh->prepare("SELECT blog.*, user.username FROM blog INNER JOIN user ON (blog.user_id=user.id) WHERE (title like :searchParam) OR (body like :searchParam)");
        $sorgu->execute(array(":searchParam"=>"%".$search."%"));
        if($sorgu->rowCount()){
            echo $search." kelimesine ait(".$sorgu->rowCount().") adet sonuç bulundu";
            echo "<br><br><br>";
            foreach ($sorgu as $blog){
                ?>
                <div class="yazibaslik">
                    <h1 id="baslikiki"><?= $blog['title'] ?></h1>
                    <h3 id="tarih"><?= $blog['created_at'] ?></h3>
                    <h3 id="yazar"><?= $blog['username'] ?></h3>
                </div>
                <p><?=substr($blog['body'],0,450)?>...</p>
                <div class="devam"><a href="detail.php?id=<?=$blog['id']?>">Devamını Oku</a></div>

                <?php
            }
        }else{
            echo "Aranan kelimeye ait veri bulunmadı";
        }
    }
    ?>



    <div class="sayfalama">
    <?php

    if($page>1){
        echo "<span class='sayfa'><a href='index.php?page=1'>İlk<<</a></span>";
        echo "<span class='sayfa'><a href='index.php?page=".($page-1)."'>Önceki</a></span>";
    }
    for ( $i = $page-$forlimit; $i<$page+$forlimit+1;$i++){
        if($i>0 && $i<=$totalPageCount){
            if($i == $page){
                echo "<span class='aktif'>".$i."</span>";
            }else{
                echo "<span class='sayfa'><a href='index.php?page=".$i."'>".$i."</a></span>";
            }
        }
    }
    if($page != $totalPageCount) {
        echo "<span class='sayfa'><a href='index.php?page=".($page+1)."'>Sonraki</a></span>";
        echo "<span class='sayfa'><a href='index.php?page=" . $totalPageCount . "'>Son>></a> </span>";
    }
    ?>
    </div>

</div>

    <script src="assets/js/jquery-3.5.1.min.js" type="application/javascript"></script>
    <script src="assets/js/popper.js"</script>
    <script src="./assets/js/bootstrap.bundle.js" type="application/javascript"></script>
    <script src="ajax.js"></script>
</body>
</html>