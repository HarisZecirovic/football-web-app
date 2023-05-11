<?php require_once 'header.php' ?>
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['potvrdi'])) {
    $pobednik = $_POST['pobednik'];
    $id_utakmice = $_POST['id_utakmice'];
    $golovi_tim1 = $_POST['golovi_tim1'];
    $golovi_tim2 = $_POST['golovi_tim2'];
    $id_takmicenja = $_POST['id_takmicenja'];
    require_once 'db.php';
    izvrsi_upit("UPDATE utakmica set tim1_golovi = ?, tim2_golovi = ?, pobednik = ? where id_utakmice = ? ", "sssi", $golovi_tim1, $golovi_tim2, $pobednik, $id_utakmice);
    header("Location:pregled_utakmica.php?id_takmicenja=$id_takmicenja");
}

?>
<?php
#pregled takmicanja na kojima on ucestvuje ili na kojima je ucestvovao
if (isset($_GET['id_takmicenja'])) {
    $id_takmicenja = $_GET['id_takmicenja'];
}
require_once 'db.php';

if (isset($_SESSION['korisnik']) && $_SESSION['tip_korisnika'] == 'trener') {
    #proveravam koliko ima timova
    $result = izvrsi_upit("SELECT * FROM tim where usernameTrenera = ?", "s", $korisnik);
    $rows = $result->num_rows;
    if ($rows >= 2) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $ime_tima1 = $row['ime_tima'];
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $ime_tima2 = $row['ime_tima'];
        $result = izvrsi_upit("SELECT * FROM takmicenje JOIN prijava_za_takmicenje on takmicenje.id_takmicenja = prijava_za_takmicenje.id_takmicenja where prijava_za_takmicenje.ime_tima = ? or prijava_za_takmicenje.ime_tima = ?", "ss", $ime_tima1, $ime_tima2);
        $rows = $result->num_rows;

    } elseif ($rows == 1) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $ime_tima1 = $row['ime_tima'];
        $result = izvrsi_upit("SELECT * FROM takmicenje JOIN prijava_za_takmicenje on takmicenje.id_takmicenja = prijava_za_takmicenje.id_takmicenja where prijava_za_takmicenje.ime_tima = ?", "s", $ime_tima1);
        $rows = $result->num_rows;
    }
}
#za sportistu provera za takmicenja na kojima on ucestvuje 
elseif (isset($_SESSION['korisnik']) && $_SESSION['tip_korisnika'] == 'sportista') {
    $result = izvrsi_upit("SELECT * FROM clanovi where usernameSportiste = ?", "s", $korisnik);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $ime_tima = $row['ime_tima'];
    $result = izvrsi_upit("SELECT * FROM takmicenje JOIN prijava_za_takmicenje on takmicenje.id_takmicenja = prijava_za_takmicenje.id_takmicenja where prijava_za_takmicenje.ime_tima = ?", "s", $ime_tima);
    $rows = $result->num_rows;
}

echo  "
<script src='tabela.js'> </script>
<input class='form-control mb-4 mt-5' id='tableSearch' type='text' placeholder='Pretrazite'>
 <table class='table mr-5 mt-5'>
<thead>
  <tr>
    <th scope='col'>#</th>
    <th scope='col'>Naziv takmicenja</th>
    <th scope='col'>Kategorija</th>
    <th scope='col'>Pol takmicenja</th>
    <th scope='col'>Datum prve utakmice</th>
    <th scope='col'>Ime tima</th>
  </tr>
</thead>
<tbody id='myTable'>

";
for ($i = 0; $i < $rows; ++$i) {

    $row = $result->fetch_array(MYSQLI_ASSOC);
    $naziv_takmicenja = $row['naziv_takmicenja'];
    $kategorija_takmicenja = $row['kategorija_takmicenja'];
    $pol_takmicenja = $row['pol_takmicenja'];
    $datum_utakmice = $row['datum_utakmice'];
    $id_takmicenja = $row['id_takmicenja'];
    $id_utakmice = $row['id_utakmice'];
    $ime_tima = $row['ime_tima'];
     if (isset($_SESSION['korisnik']) && ($_SESSION['tip_korisnika'] == 'trener') || $_SESSION['tip_korisnika'] == 'sportista') {
        echo " <tr>
    
    
    <th scope='row'>$i</th>
    <td>$naziv_takmicenja</td>
    <td>$kategorija_takmicenja</td>
    
    <td>$pol_takmicenja</td>
    <td>$datum_utakmice</td>
    <td>$ime_tima</td>
    <td><a class = 'btn btn-outline-info' href ='pregled_utakmica.php?id_takmicenja=$id_takmicenja'>Pregled</a>   </td>
    </tr>
    ";
    }
}
echo "

</tbody>
        </table>";


?>
<?php require_once 'footer.php' ?>