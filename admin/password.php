<?php
$db=new PDO("mysql:host=localhost;dbname=idea_blog","ideasoft","root");

if($_POST){
    require_once "class.phpmailer.php";
    $email = $_POST['email'];
    if(!$email){
        echo '<div class="alert alert-danger">
                    Boş alan bırakmayınız.
                </div>';
    }else{
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)){
            echo '<div class="alert alert-danger">
                    Email formatı yanlış girildi.
                </div>';
        }else{
            $varmi = $db->prepare("select member_name,member_email from members where member_email=:e");
            $varmi->execute([":e"=>$email]);
            if($varmi->rowCount()){
                $row = $varmi->fetch(PDO::FETCH_ASSOC);
                $sifirlamakodu = uniqid();
                $sifirlamalinki = "http://omer.com/BLOG/admin/changepw.php?kod=".$sifirlamakodu;
                $sifirlamakodunuekle = $db->prepare("update members set code=:k where member_email=:e");
                $sifirlamakodunuekle->execute([':k'=>$sifirlamakodu,':e'=>$email]);

                $mail = new PHPMailer();
                $mail->IsSMTP();
                $mail->SMTPAuth = true;
                $mail->Host = 'smtp.yandex.com.tr';
                $mail->Port = 587;
                $mail->SMTPSecure = 'tls';
                $mail->Username = 'kontolhesab@yandex.com';
                $mail->Password = 'Onur.1993';
                $mail->AddAddress($email,$row["member_name"]);
                $mail->SetFrom($mail->Username, 'Onur Ömer ŞAHİN');
                $mail->FromName= 'Şifremi Unuttum';
                $mail->CharSet = 'UTF-8';
                $mail->Subject = 'Şifremi Sıfırla';
                $content = "<div style='background: #eee; padding: 10px; font-size: 14px'>Sayın:".$row["member_name"]." şifre sıfırlama linkiniz: ".$sifirlamalinki."</div>";
                $mail->MsgHTML($content);
                if($mail->Send()){
                    echo '<div class="alert alert-success d-flex justify-content-center">
                    Şifre sıfırlama linkiniz mail adresinize gönderilmiştir.
                </div>';
                }else{
                    echo '<div class="alert alert-danger d-flex justify-content-center">
                    Hata Oluştu
                </div>';}
            }else{
                echo '<div class="alert alert-danger">
                    Girilen email adresi sistemde mevcut değildir
                </div>';
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
                    <div class="input-group form-group mt-5">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="email" name="email" class="form-control" placeholder="Email" required="">
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Gönder" class="btn float-right login_btn">
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