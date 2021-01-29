<?php

try {
    $dbh = new PDO('mysql:host=localhost;dbname=idea_blog', 'ideasoft', 'root');
} catch (PDOException $e) {
    echo $e->getMessage();
}
$ayarsor=$dbh->prepare("select * from ayarlar where ayar_id=?");
$ayarsor->execute(array(0));
$ayarcek=$ayarsor->fetch(PDO::FETCH_ASSOC);
$userQuery = $dbh->prepare('SELECT * FROM user');
$userQuery->execute(array(0));
$users = $userQuery->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Benim Blogum-Admin</title>
    <link href="../admin/admintema/dist/css/styles.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css" rel="stylesheet" crossorigin="anonymous" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.ckeditor.com/4.15.1/standard/ckeditor.js"></script>
</head>
<body class="sb-nav-fixed">
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">BENİM BLOGUM</a>
    <button class="btn btn-link btn-sm order-1 order-lg-0" id="sidebarToggle" href="#"><i class="fas fa-bars"></i></button>
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ml-auto mr-0 mr-md-3 my-2 my-md-0">    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ml-auto ml-md-0">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="userDropdown" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="index.php?cikisYap=1">Logout</a>
            </div>
        </li>
    </ul>
</nav>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav mt-5">
                    <a class="nav-link" href="index.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-home"></i></div>
                        Anasayfa
                    </a>
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                        Ayarlar
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-parent="#sidenavAccordion">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link" href="genelayar.php">Genel Ayarlar</a>
                            <a class="nav-link" href="sosyalayar.php">Sosyal Medya Ayarlar</a>
                        </nav>
                    </div>
                    <a class="nav-link" href="icerik.php">
                        <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                        İçerik İşlemleri
                    </a>
                </div>
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid">
                <h1 class="mt-5">Ayarlar</h1><hr>
                <div class="alert alert-primary" role="alert">
                    <h5>İçerik İşlemleri</h5>
                    <small>
                        <?php if($_GET["durum"]=="ok") {echo "<b style='color:green;'>İşlem başarılı...</b>";}
                        elseif ($_GET["durum"]=="no") {echo "<b style='color:red;'>İşlem yapılamadı..!</b>";}?></small>
                </div>
            </div>
            <div class="container">
                <form class="form-horizontal" action="islem.php" method="post">
                    <div class="form-group row">
                        <label class="control-label col-sm-2" for="email">Blog User ID:</label>
                        <div class="col-sm-10">
                            <select name="user_id" class="form-group">
                                <?php
                                    foreach ($users as $user){
                                        echo "<option value='".$user['id']."'>".$user['username']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-sm-2" for="email">Blog Başlık:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="title" value="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="control-label col-sm-2" for="email">	Blog İçerik:</label>
                        <div class="col-sm-10">
                            <textarea class="ckediyor" id="body" name="body"></textarea>
                        </div>
                    </div>
                    <script type="text/javascript">
                        CKEDITOR.replace('body',
                            {
                                filebrowserBrowseUrl:'ckfinder/ckfinder.html',
                                filebrowserImageBrowserUrl:'ckfinder/ckfinder.html?type=Images',
                                filebrowserFlashBrowserUrl:'ckfinder/ckfinder.html?type=Flash',
                                filebrowserUploadUrl:'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
                                filebrowserImageUploadUrl:'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images',
                                filebrowserFlashUploadUrl:'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash',
                                forcePasteAsPlainText:true
                            });
                    </script>
                    <div class="form-group row">
                        <label class="control-label col-sm-2" for="email">	Blog Tarih:</label>
                        <div class="col-sm-10">
                            <input type="datetime-local" class="form-control" name="created_at" value="">
                        </div>
                    </div><div class="form-group row">
                        <label class="control-label col-sm-2" for="email">	Blog  Update Tarih:</label>
                        <div class="col-sm-10">
                            <input type="datetime-local" class="form-control" name="updated_at" value="">
                        </div>
                    </div>
                    <div class="form-group mt-4" align="right">
                        <div class="col-sm-10 offset-sm-2">
                            <button type="submit" name="icerikkaydet" class="btn btn-success">Ekle</button>
                        </div>
                    </div>
                </form>
            </div>
        </main>
        <footer class="py-4 bg-light mt-auto">
            <div class="container-fluid">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Copyright &copy; Benim Blogum 2021</div>
                    <div>
                        <a href="#">Privacy Policy</a>
                        &middot;
                        <a href="#">Terms &amp; Conditions</a>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="../admin/admintema/dist/js/scripts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="../admin/admintema/dist/assets/demo/chart-area-demo.js"></script>
<script src="../admin/admintema/dist/assets/demo/chart-bar-demo.js"></script>
<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js" crossorigin="anonymous"></script>
<script src="../admin/admintema/dist/assets/demo/datatables-demo.js"></script>
</body>
</html>


