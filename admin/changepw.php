<?php
$db=new PDO("mysql:host=localhost;dbname=idea_blog","ideasoft","root");

$kod = $_GET["kod"];
if(!$kod){
    echo '<div class="alert alert-danger">
                    Sıfırlama kodu hatalı.
                </div>';
}else{
    if($_POST){
        $email = $_POST["email"];
        $password1 = md5($_POST["password1"]);
        $password2 = md5($_POST["password2"]);

        if(!$email || !$password1 || !$password2){
            echo '<div class="alert alert-danger">
                    Boş alan bıkakmayınız.
                </div>';
        }else{
            if($password1!=$password2){
                echo '<div class="alert alert-danger">
                    Şifreler uyuşmuyor.
                </div>';
            }else{
                $varmi = $db->prepare("select * from members where code=:k and member_email=:e");
                $varmi->execute([':k'=>$kod, ':e'=>$email]);
                if($varmi->rowCount()){
                     $sifreguncelle= $db->prepare("update members set code=:sifirla,member_password=:p where code=:k and member_email=:e");
                     $sifreguncelle->execute([':sifirla'=>"",':p'=>$password1,':k'=>$kod,':e'=>$email]);
                     if($sifreguncelle){
                         echo '<div class="alert alert-success d-flex justify-content-center">
                    Şifreniz başarıyla güncellendi.
                        </div>';
                         header("Refresh: 2; url=login.php");
                     }else{
                         echo '<div class="alert alert-danger">
                    Hata oluştu.
                        </div>';
                     }

                }else{
                    echo '<div class="alert alert-danger">
                    Girilen bilgilere göre kayıt bulunamadı.
                </div>';
                }
            }
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Giriş Yap</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <!--Fontawesome CDN-->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">

    <!--Custom styles-->
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="container">
    <div class="d-flex justify-content-center h-100">
        <div class="card">
            <div class="card-header d-flex justify-content-center">
                <h3>Şifre Değiştir</h3>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="input-group form-group mt-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="email" name="email" class="form-control" placeholder="Email" required="">
                    </div>
                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input type="password" name="password1" class="form-control" placeholder="Şifre" required="">
                    </div>

                    <div class="input-group form-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-key"></i></span>
                        </div>
                        <input type="password" name="password2" class="form-control" placeholder="Şifre" required="">
                    </div>

                    <div class="form-group">
                        <input type="submit" value="Değiştir" class="btn float-right login_btn">
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <div class="d-flex justify-content-center links">
                    <a href="login.php" class="text-white">Giriş Yap</a>
                </div>
        </div>
    </div>
</div>
</body>
</html>