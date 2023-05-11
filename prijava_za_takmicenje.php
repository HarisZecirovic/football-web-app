<?php require_once 'header.php' ?>
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['prijavi_se']) && isset($_POST['ime_tima'])) {
    $ime_tima = $_POST['ime_tima'];
    $id_takmicenja = $_POST['id_takmicenja'];
    $kategorija_takmicenja = $_POST['kategorija_takmicenja'];
    $pol_takmicenja = $_POST['pol_takmicenja'];
    require_once 'db.php';
    #uzimamo podatke za tim za koji se salje zahtev za takmicenje
    $result = izvrsi_upit("SELECT * from tim where ime_tima = ? ", "s", $ime_tima);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $kategorija_tima = $row['kategorija'];
    $pol_tima = $row['pol'];
    #proveravamo da li se on vec prijavio sa tim timom za to takmicenje, znaci tim koji je izabrao da bi se prijavio za takmicenje
    $result = izvrsi_upit("SELECT * FROM  prijava_za_takmicenje where id_takmicenja = ? and ime_tima = ?", "is", $id_takmicenja, $ime_tima);
    if ($result->num_rows == 1) {
        echo " <script> alert('Vec ste se prijavili za ovo takmicenje') </script>";
    } 
    #provera da li on moze da se prijavi za to takmicenje
    elseif ($kategorija_takmicenja == $kategorija_tima && $pol_takmicenja == $pol_tima) {

        izvrsi_upit("INSERT INTO prijava_za_takmicenje values(?, ?, ?, ?)", "isss", $id_takmicenja, $ime_tima, $kategorija_tima, $pol_tima);
        echo " <script> alert('Uspesno ste se prijavili za takmicenje sa timom  $ime_tima') </script>";
    } else {
        echo " <script> alert('Ne ispunjavate uslove za tim  $ime_tima') </script>";
    }
}
elseif(isset($_POST['prekini'])){
    $id_takmicenja = $_POST['id_takmicenja'];
    require_once "db.php";

    izvrsi_upit("UPDATE takmicenje set status = 'zavrseno'");
    echo " <script> alert('Prekinuli ste takmicenje') </script>";

}
elseif(isset($_POST['obrisi'])){
    $id_takmicenja = $_POST['id_takmicenja'];
    require_once "db.php";
    izvrsi_upit("DELETE from takmicenje where id_takmicenja =?", "i", $id_takmicenja);
    echo " <script> alert('Izbrisali ste takmicenje') </script>";
}

?>
<?php
date_default_timezone_set('Europe/Belgrade');
$vreme = date('Y-m-d h:i:s', time());
require_once "db.php";
#selektuj iz takmicanje gde je moguca prijava za to takmicenje
$result = izvrsi_upit("SELECT * from takmicenje where dozvola_za_prijavu is NULL");
for ($i = 0; $i < $result->num_rows; $i++) {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $rok_za_prijavu = $row['rok_za_prijavu'];
    $id_takmicenja = $row['id_takmicenja'];
    $rok = new DateTime($rok_za_prijavu);
    $vreme_danas = new DateTime($vreme);
    echo "ROK" . $rok_za_prijavu . " DANAS" . $vreme;
    #ako je rok za prijavu manji od danasnjeg datuma to znaci da je prijava istekla
    if ($rok <= $vreme_danas) {
        izvrsi_upit("UPDATE takmicenje set dozvola_za_prijavu = 'isteklo' where id_takmicenja = ?", "i", $id_takmicenja);
    }
}




?>

<?php
require_once 'db.php';

if (isset($_SESSION['korisnik']) && $_SESSION['tip_korisnika'] == 'trener') {

    $result = izvrsi_upit("SELECT * FROM takmicenje where dozvola_za_prijavu is NULL");
    $rows = $result->num_rows;
} elseif (isset($_SESSION['korisnik']) && $_SESSION['tip_korisnika'] == 'admin') {
    $result = izvrsi_upit("SELECT * FROM takmicenje");
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
    <th scope='col'>Rok za prijavu</th>
    <th scope='col'>Datum prve utakmice</th>
    <th scope='col'>Kategorija</th>
    <th scope='col'>Pol</th>
    <th scope='col'>Status</th>
  </tr>
</thead>
<tbody id='myTable'>

";
for ($i = 0; $i < $rows; ++$i) {

    $row = $result->fetch_array(MYSQLI_ASSOC);
    $naziv_takmicenja = $row['naziv_takmicenja'];
    $rok_za_prijavu = $row['rok_za_prijavu'];
    $datum_utakmice = $row['datum_utakmice'];
    $kategorija_takmicenja = $row['kategorija_takmicenja'];
    $pol_takmicenja = $row['pol_takmicenja'];
    $id_takmicenja = $row['id_takmicenja'];
    $status = $row['status'];


    echo " <tr>
    <form action='prijava_za_takmicenje.php' method='POST'>
    
    <th scope='row'>$i</th>
    <td>$naziv_takmicenja</td>
    
    <td>$rok_za_prijavu</td>
    <td>$datum_utakmice</td>
    <td>$kategorija_takmicenja</td>
    <td>$pol_takmicenja</td>
    ";

    echo "<td>";



    if ($_SESSION['tip_korisnika'] == 'trener') {
        echo "
            <select class='' id='sel1' name = 'ime_tima' required>
            <option value='' disabled selected>Izaberite</option>";
        #selektujem trenerove timove gde on bira sa kojim timom ce da se prijavi za takmicenje
        $rezultat = izvrsi_upit("SELECT * from tim where usernameTrenera = ? ", "s", $korisnik);


        for ($j = 0; $j < $rezultat->num_rows; ++$j) {
            $red = $rezultat->fetch_array(MYSQLI_ASSOC);
            $ime_tima = $red['ime_tima'];
            echo "
                <option value ='$ime_tima'>$ime_tima</option>
                    ";
        }

        echo "       
             </select>
        ";

        echo "</td>";

        echo "
    <td>
    
    <input type = 'hidden' name = 'id_takmicenja' value = '$id_takmicenja'>
    <input type = 'hidden' name = 'kategorija_takmicenja' value = '$kategorija_takmicenja'>
    <input type = 'hidden' name = 'pol_takmicenja' value = '$pol_takmicenja'>
    <input type = 'submit' name = 'prijavi_se' value = 'Prijavi se' class = 'btn btn-success'> </td>
    ";
    } elseif (isset($_SESSION['tip_korisnika']) && $_SESSION['tip_korisnika'] == 'admin') {
        echo "
        $status
        
        <td>
        <input type = 'submit' name = 'obrisi' value = 'Obrisi' class = 'btn btn-danger'>
        </td>
        <td>
        <input type = 'hidden' name = 'id_takmicenja' value = '$id_takmicenja'>";
        #ako takmicenje nije zavrseno moze admin da ga prekine
        if($status != 'zavrseno'){
            echo"
        <input type = 'submit' name = 'prekini' value = 'Prekini takmicenje' class = 'btn btn-danger'>
        </td>";
        }
        echo"
        <td><a class = 'btn btn-outline-info' href ='organizuj_utakmice.php?id_takmicenja=$id_takmicenja'>utakmice</a></td>  
        <td><a class = 'btn btn-outline-info' href ='pregled_utakmica.php?id_takmicenja=$id_takmicenja'>Pregled</a>   </td>";
        
    }
    echo "

    </form>
    
    
    
    
    
    
  </tr>
    ";
}
echo "

</tbody>
        </table>";


?>
<?php require_once 'footer.php' ?>