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
    $posed_tim1 = $_POST['posed_tim1'];
    $posed_tim2 = $_POST['posed_tim2'];
    require_once 'db.php';
    //izvrsi_upit("UPDATE utakmica set tim1_golovi = ?, tim2_golovi = ?, pobednik = ? , posed_tim1 = ?, posed_tim2 = ? where id_utakmice = ? ", "sssiss", $golovi_tim1, $golovi_tim2, $pobednik, $id_utakmice, $posed_tim1,$posed_tim2);
    izvrsi_upit("UPDATE utakmica SET tim1_golovi = ?, tim2_golovi= ?, pobednik = ?, posed_tim1 = ?, posed_tim2 = ? where id_utakmice = ?", "sssssi", $golovi_tim1, $golovi_tim2, $pobednik,$posed_tim1,$posed_tim2,$id_utakmice);
    echo "<script>alert('Uspesno ste izmenili') </script>";
    header("Location:pregled_utakmica.php?id_takmicenja=$id_takmicenja");
}

?>
<?php
if (isset($_GET['id_takmicenja'])) {
    $id_takmicenja = $_GET['id_takmicenja'];
}
require_once 'db.php';

if (isset($_SESSION['korisnik']) && $_SESSION['tip_korisnika'] == 'trener' || $_SESSION['tip_korisnika'] == 'sportista') {

    $result = izvrsi_upit("SELECT * FROM utakmica where id_takmicenja = ?", "i", $id_takmicenja);
    $rows = $result->num_rows;
} elseif (isset($_SESSION['korisnik']) && $_SESSION['tip_korisnika'] == 'admin') {
    $result = izvrsi_upit("SELECT * FROM utakmica where id_takmicenja = ?", "i", $id_takmicenja);
    $rows = $result->num_rows;
} else {
    $result = izvrsi_upit("SELECT * FROM utakmica where id_takmicenja = ?", "i", $id_takmicenja);
    $rows = $result->num_rows;
}

echo  "
<script src='tabela.js'> </script>
<input class='form-control mb-4 mt-5' id='tableSearch' type='text' placeholder='Pretrazite'>
 <table class='table mr-5 mt-5'>
<thead>
  <tr>
    <th scope='col'>#</th>
    <th scope='col'>Ime Tima 1</th>
    <th scope='col'>Ime Tima 2</th>
    <th scope='col'>Datum utakmice</th>
    <th scope='col'>Pobednik</th>
    <th scope='col'>Golovi Tim 1</th>
    <th scope='col'>Golovi Tim 2</th>
    <th scope='col'>Posed Tim 1</th>
    <th scope='col'>Posed Tim 2</th>
    <th scope='col'>Tip</th>
  </tr>
</thead>
<tbody id='myTable'>

";
for ($i = 0; $i < $rows; ++$i) {

    $row = $result->fetch_array(MYSQLI_ASSOC);
    $ime_tima1 = $row['ime_tima1'];
    $ime_tima2 = $row['ime_tima2'];
    $datum_utakmice = $row['datum_utakmice'];
    $id_takmicenja = $row['id_takmicenja'];
    $id_utakmice = $row['id_utakmice'];
    $pobednik = $row['pobednik'];
    $golovi_tim1 = $row['tim1_golovi'];
    $golovi_tim2 = $row['tim2_golovi'];
    $tip_utakmice = $row['tip'];
    $posed_tim1 = $row['posed_tim1'];
    $posed_tim2  = $row['posed_tim2'];
    if ($_SESSION['tip_korisnika'] == 'admin') {

        echo " <tr>
    <form action='pregled_utakmica.php' method='POST'>
    
    <th scope='row'>$i</th>
    <td>$ime_tima1</td>
    
    <td>$ime_tima2</td>
    <td>$datum_utakmice</td>
    
    ";

        echo "<td>";





        echo "
            <select class='' id='sel1' name = 'pobednik' required>";
            #provera da li ima upisan pobednik
        if ($pobednik == NULL) {
            echo "
            <option value='' disabled selected>Izaberite</option>";
        } elseif ($pobednik != NULL) {
            echo "
            <option value='$pobednik' selected>$pobednik</option>";
        }




        echo "
                <option value ='$ime_tima1'>$ime_tima1</option>
                <option value ='$ime_tima2'>$ime_tima2</option>
                    ";


        echo "       
             </select>
             </td>
        ";
        #da li su upisani golovi
        if ($golovi_tim1 == NULL && $golovi_tim2 == NULL) {
            echo "
        <td> <input type = 'text' name = 'golovi_tim1'> </td>
        <td><input type = 'text' name = 'golovi_tim2'> </td>
        <td><input type = 'text' name = 'posed_tim1'> </td>
        <td><input type = 'text' name = 'posed_tim2' > </td>
        ";
        } elseif ($golovi_tim1 != NULL && $golovi_tim2 != NULL) {
            echo "
        <td> <input type = 'text' name = 'golovi_tim1' value = '$golovi_tim1'> </td>
        <td><input type = 'text' name = 'golovi_tim2' value = '$golovi_tim2'> </td>
        <td><input type = 'text' name = 'posed_tim1' value = '$posed_tim1'> </td>
        <td><input type = 'text' name = 'posed_tim2' value = '$posed_tim2'> </td>
    
    
    ";
        }


        echo "
        <td> $tip_utakmice</td>
        <td>
        <input type ='hidden' name = 'id_utakmice' value = '$id_utakmice'>
        <input type ='hidden' name = 'id_takmicenja' value = '$id_takmicenja'>
        <input type = 'submit' name = 'potvrdi' value = 'Potvrdi' class = 'btn btn-success'>
        </td>
        <td><a class = 'btn btn-outline-info' href ='grafovi.php?id_utakmice=$id_utakmice'>Graficki prikaz</a></td>  ";

        echo "

    </form>
    
    
    
    
    
    
  </tr>
    ";
    } elseif (isset($_SESSION['korisnik']) && ($_SESSION['tip_korisnika'] == 'trener') || $_SESSION['tip_korisnika'] == 'sportista') {
        echo " <tr>
    
    
    <th scope='row'>$i</th>
    <td>$ime_tima1</td>
    
    <td>$ime_tima2</td>
    <td>$datum_utakmice</td>
    <td>$pobednik </td>
    <td> $golovi_tim1 </td>
    <td> $golovi_tim2 </td>
    <td> $posed_tim1 </td>
    <td> $posed_tim2 </td>
    <td> $tip_utakmice </td>
    <td><a class = 'btn btn-outline-info' href ='grafovi.php?id_utakmice=$id_utakmice'>Graficki prikaz</a></td> 
    </tr>
    ";
    } else {
        echo " <tr>
    
    
    <th scope='row'>$i</th>
    <td>$ime_tima1</td>
    
    <td>$ime_tima2</td>
    <td>$datum_utakmice</td>
    <td>$pobednik </td>
    <td> $golovi_tim1 </td>
    <td> $golovi_tim2 </td>
    <td> $posed_tim1 </td>
    <td> $posed_tim2 </td>
    <td> $tip_utakmice </td>
    <td><a class = 'btn btn-outline-info' href ='grafovi.php?id_utakmice=$id_utakmice'>Graficki prikaz</a></td> 
    </tr>
    ";
    }
}
echo "

</tbody>
        </table>";


?>
<?php require_once 'footer.php' ?>