<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Sitemap Generator</title>
</head>
<body>
<?php

date_default_timezone_set("Europe/Istanbul");

// Veritabanı ayarları
define("HOST", "localhost");
define("USER", "root");
define("PASS", "");
define("DBNAME", "efendidergi");
define("HOMEPATH", "efendi/");


function tarih_convert($datetime = 'now', $format = "j F Y - H:i")
{

    $z = date("$format", strtotime($datetime));

    $gun_dizi = array(
        'Monday' => 'Pazartesi',
        'Tuesday' => 'Salı',
        'Wednesday' => 'Çarşamba',
        'Thursday' => 'Perşembe',
        'Friday' => 'Cuma',
        'Saturday' => 'Cumartesi',
        'Sunday' => 'Pazar',
        'January' => 'Ocak',
        'February' => 'Şubat',
        'March' => 'Mart',
        'April' => 'Nisan',
        'May' => 'Mayıs',
        'June' => 'Haziran',
        'July' => 'Temmuz',
        'August' => 'Ağustos',
        'September' => 'Eylül',
        'October' => 'Ekim',
        'November' => 'Kasım',
        'December' => 'Aralık',
        'Mon' => 'Pts',
        'Tue' => 'Sal',
        'Wed' => 'Çar',
        'Thu' => 'Per',
        'Fri' => 'Cum',
        'Sat' => 'Cts',
        'Sun' => 'Paz',
        'Jan' => 'Oca',
        'Feb' => 'Şub',
        'Mar' => 'Mar',
        'Apr' => 'Nis',
        'Jun' => 'Haz',
        'Jul' => 'Tem',
        'Aug' => 'Ağu',
        'Sep' => 'Eyl',
        'Oct' => 'Eki',
        'Nov' => 'Kas',
        'Dec' => 'Ara',
    );
    foreach ($gun_dizi as $en => $tr) {
        $z = str_replace($en, $tr, $z);
    }
    if (strpos($z, 'Mayıs') !== false && strpos($format, 'F') === false) $z = str_replace('Mayıs', 'May', $z);
    return $z;
}

function hesapla($tarih, $currentYear)
{

    if ($tarih == $currentYear) {

        return "0.3";

    } else if ($tarih == $currentYear - 1) {

        return "0.2";

    } else {

        return "0.1";

    }

}

function cHesapla($p)
{

    switch ($p) {
        case "0.3":
            return "weekly";
            break;
        case "0.2":
            return "monthly";
            break;
        default:
            return "yearly";
            break;
    }
}

$currentYear = date("Y");

$app = "https://" . $_SERVER['SERVER_NAME'] . "/";
$time = explode(" ", microtime());
$time = $time[1];

include "../../vendor/thingengineer/mysqli-database-class/MysqliDb.php";

$db = new MysqliDb(HOST, USER, PASS, DBNAME);

function getIceriklerModel($last = null, $k = null, $e = null, $yazar = null, $l = 15, $db = null)
{

    $db->join("icerik_kategori", "icerik.icerik_ID = icerik_kategori.icerik_icerik_ID", "LEFT");
    $db->join("kategori", "icerik_kategori.kategori_kategori_ID = kategori.kategori_ID", "LEFT");
    $db->join("icerik_etiket", "icerik.icerik_ID = icerik_etiket.icerik_icerik_ID", "LEFT");
    $db->join("etiket", "icerik_etiket.etiket_etiket_ID = etiket.etiket_ID", "LEFT");
    $db->join("kullanici", "icerik.kullanici_ID = kullanici.kullanici_ID", "LEFT");
    $db->where("icerik_tip", "sayfa", "!=");
    $db->where("icerik_durum", "yayinda");
    $db->groupBy("icerik_ID");
    $db->orderBy("icerik_y_tarih", "DESC");

    if ($last == null) {

        if ($k != null) {

            $db->where("kategori_ID", $k);

        }

        if ($e != null) {

            $db->where("etiket_ID", $e);

        }

        if ($yazar != null) {

            $db->where("icerik.kullanici_ID", $yazar);

        }

        return $db->get("icerik", $l);


    } else {

        if ($k != null) {

            $db->where("kategori_ID", $k);

        }

        if ($e != null) {

            $db->where("etiket_ID", $e);

        }

        if ($yazar != null) {

            $db->where("icerik.kullanici_ID", $yazar);

        }

        $db->where("icerik_ID", $last, "<");
        return $db->get("icerik", $l);

    }

}

// include class
include 'sitemapGenerator.php';
// create object
$sitemap = new SitemapGenerator($app, "../../");
// will create also compressed (gzipped) sitemap
$sitemap->createGZipFile = true;
// determine how many urls should be put into one file
$sitemap->maxURLsPerSitemap = 10000;
// sitemap file name
$sitemap->sitemapFileName = "sitemap.xml";
// sitemap index file name
$sitemap->sitemapIndexFileName = "sitemap-index.xml";
// robots file name
$sitemap->robotsFileName = "robots.txt";


$sitemap->addUrl($app, date("Y-m-d\TH:i:s\Z"), 'daily', '1');

$db->where("icerik_durum", "yayinda");
$icerikler = $db->get("icerik");

foreach ($icerikler as $icerik) {

    $tarih = tarih_convert($icerik["icerik_y_tarih"], "Y");

    $p = hesapla($tarih, $currentYear);

    $sitemap->addUrl($app . HOMEPATH . $icerik["icerik_tip"] . "/" . $icerik["icerik_url"], tarih_convert($icerik["icerik_g_tarih"], "Y-m-d\TH:i:s\Z"), "yearly", ($icerik["icerik_tip"] == "sayfa" ? "0.5" : $p));

}

$db->where("kullanici_durum", "aktif");
$kullanicilar = $db->get("kullanici");

foreach ($kullanicilar as $k) {

    $db->where("kullanici_ID", $k["kullanici_ID"]);
    $db->where("icerik_durum", "yayinda");
    $tarih = $db->getValue("icerik", "MAX(icerik_y_tarih)", 1);

    if (!empty($tarih)) {

        $p = hesapla($tarih, $currentYear);

    } else {

        $p = "0.1";

    }

    $c = cHesapla($p);

    $sitemap->addUrl($app . HOMEPATH . "yazar/" . $k["kullanici_adi"], tarih_convert($tarih, "Y-m-d\TH:i:s\Z"), $c, $p);

}

$kategoriler = $db->get("kategori");

foreach ($kategoriler as $kat) {

    $last = getIceriklerModel(null, $kat["kategori_ID"], null, null, 1, $db);

    if (!empty($last)) {

        $tarih = $last[0]["icerik_y_tarih"];

    } else {

        continue;

    }

    $sitemap->addUrl($app . HOMEPATH . "kategori/" . $kat["kategori_adi"], tarih_convert($tarih, "Y-m-d\TH:i:s\Z"), "monthly", "0.5");

}

$etiketler = $db->get("etiket");

foreach ($etiketler as $et) {

    $last = getIceriklerModel(null, null, $et["etiket_ID"], null, 1, $db);

    if (!empty($last)) {

        $tarih = $last[0]["icerik_y_tarih"];

    } else {

        continue;

    }


    $sitemap->addUrl($app . HOMEPATH . "etiket/" . $et["etiket_url"], tarih_convert($tarih, "Y-m-d\TH:i:s\Z"), "monthly", "0.4");

}

try {
    // create sitemap
    $sitemap->createSitemap();
    // write sitemap as file
    $sitemap->writeSitemap();
    // update robots.txt file
    $sitemap->updateRobots();
    // submit sitemaps to search engines
    $result = $sitemap->submitSitemap("oZKlMC5i");
    // shows each search engine submitting status
    echo "<pre>";
    print_r($result);
    echo "</pre>";

} catch (Exception $exc) {

    echo $exc->getTraceAsString();

}

echo "Memory peak usage: " . number_format(memory_get_peak_usage() / (1024 * 1024), 2) . "MB";
$time2 = explode(" ", microtime());
$time2 = $time2[1];

echo "<br>Execution time: " . number_format($time2 - $time) . "s";

?>
</body>
</html>