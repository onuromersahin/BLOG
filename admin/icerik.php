<?php
try {
    $dbh = new PDO('mysql:host=localhost;dbname=idea_blog', 'ideasoft', 'root');
} catch (PDOException $e) {
    echo $e->getMessage();
}
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
                    <h1 class="mt-5">İçerik İşlemleri</h1><small>
                </div>
                <table class="table table-bordered mt-4" id="dataTable" width="100%" cellspacing="0">
                    <div align="right" class="mb-3 mr-2">
                    <a href="icerikekle.php"><button class="btn btn-success btn-sm" type="submit"><i class="fas fa-plus"></i>&nbsp;Yeni ekle</button></a>
                     </div>
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col" width="63px">Blog ID</th>
                        <th scope="col" width="90px">Blog Başlık</th>
                        <th scope="col" >Blog İçerik</th>
                        <th scope="col" width="90px">Blog Tarih</th>
                        <th scope="col" width="110px">Blog Güncelleme</th>
                        <th scope="col" width="90px">Blog Yazar</th>
                        <th scope="col" width="80px"></th>
                        <th scope="col" width="80px"></th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php
                    $iceriksor=$dbh->prepare("SELECT blog.*, user.username FROM blog INNER JOIN user ON (blog.user_id=user.id)  ORDER BY id DESC ");
                    $iceriksor->execute(array());
                    $icerikcek=$iceriksor->fetchAll(PDO::FETCH_ASSOC);
                    ?>
                    <?php foreach ($icerikcek as $blog): ?>
                    <tr>
                        <th class="text-center"><?php echo $blog["id"];?></th>
                        <td class="text-center"><?php echo $blog["title"];?></td>
                        <td><?php echo $blog["body"];?></td>
                        <td class="text-center"><?php echo $blog["created_at"];?></td>
                        <td class="text-center"><?php echo $blog["updated_at"];?></td>
                        <td class="text-center"><?php echo $blog["username"];?></td>
                        <td class="text-center"><a href="icerikduzenle.php?blog_id=<?php echo $blog["id"];?>"><button class="btn btn-primary btn-sm" type="submit"><i class="fas fa-pencil-alt"></i>&nbsp;Düzenle</button></a></td>
                        <td class="text-center"><a href="islem.php?iceriksil=ok&blog_id=<?php echo $blog["id"];?>"><button class="btn btn-danger btn-sm" style="width: 85px"  type="submit"><i class="fas fa-trash-alt"></i>&nbsp;Sil</button></a></td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
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