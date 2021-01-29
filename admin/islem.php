<?php
ob_start();
try {
    $dbh = new PDO('mysql:host=localhost;dbname=idea_blog', 'ideasoft', 'root');
} catch (PDOException $e) {
    echo $e->getMessage();
}

if (isset($_POST["genelayarkaydet"])) {
    $ayarkaydet = $dbh->prepare("update ayarlar set 
        ayar_siteurl=:siteurl,
        ayar_title=:title, 
        ayar_description=:description, 
        ayar_keyword=:keyword, 
        ayar_author=:author 
        where ayar_id=0");

    $update = $ayarkaydet->execute(array(
        'siteurl' => $_POST['ayar_siteurl'],
        'title' => $_POST['ayar_title'],
        'description' => $_POST['ayar_description'],
        'keyword' => $_POST['ayar_keyword'],
        'author' => $_POST['ayar_author']
    ));

    if ($update) {
        Header("Location:genelayar.php?durum=ok");
    } else {
        Header("Location:genelayar.php?durum=no");
    }
}

if (isset($_POST["sosyalayarkaydet"])) {
    $ayarkaydet = $dbh->prepare("update ayarlar set 
        ayar_facebook=:facebook,
        ayar_twitter=:twitter, 
        ayar_youtube=:youtube, 
        ayar_linkedin=:linkedin, 
        ayar_instagram=:instagram 
        where ayar_id=0");

    $update = $ayarkaydet->execute(array(
        'facebook' => $_POST['ayar_facebook'],
        'twitter' => $_POST['ayar_twitter'],
        'youtube' => $_POST['ayar_youtube'],
        'linkedin' => $_POST['ayar_linkedin'],
        'instagram' => $_POST['ayar_instagram']
    ));

    if ($update) {
        Header("Location:sosyalayar.php?durum=ok");
    } else {
        Header("Location:sosyalayar.php?durum=no");
    }
}


if (isset($_POST["icerikkaydet"])) {
    $kaydet = $dbh->prepare("insert into blog set 
        user_id=:id,
        title=:baslik, 
        body=:icerik, 
        created_at=:tarih,
        updated_at=:tarih2");

    $insert = $kaydet->execute(array(
        'id' => $_POST['user_id'],
        'baslik' => $_POST['title'],
        'icerik' => $_POST['body'],
        'tarih' => $_POST['created_at'],
        'tarih2' => $_POST['updated_at']
    ));


    if ($insert) {
        Header("Location:icerik.php?durum=ok");
    } else {
        Header("Location:icerik.php?durum=no");
    }
}

if($_GET["iceriksil"]=="ok"){
    $sil =$dbh->prepare("delete from blog where id=:blog_id");
    $kontrol=$sil->execute(array(
        'blog_id'=>$_GET['blog_id']
    ));
    if ($kontrol) {
        Header("Location:icerik.php?durum=ok");
    } else {
        Header("Location:icerik.php?durum=no");
    }
}


if (isset($_POST["icerikduzenle"])) {
    $duzenle = $dbh->prepare("update blog set title=:baslik, body=:icerik, updated_at=:tarih2 where id=:blog_id");
    $duzenle->bindValue(':blog_id', $_POST['blog_id'], PDO::PARAM_INT);
    $duzenle->bindValue(':baslik', $_POST['title']);
    $duzenle->bindValue(':icerik', $_POST['body']);
    $duzenle->bindValue(':tarih2', (new \DateTime())->format('Y-m-d H:i:s'));
    $update = $duzenle->execute();

    if ($update) {
        Header("Location:icerik.php?&durum=ok");
    } else {
        Header("Location:icerik.php?durum=no");
    }
}

?>