<?php

require_once 'header.php'; ?>
<?php
$greska = "";

if (isset($_POST['ime_tima1']) && isset($_POST['ime_tima2']) && isset($_POST['datum_utakmice'])) {

    $ime_tima1 = $_POST['ime_tima1'];
    $ime_tima2 = $_POST['ime_tima2'];
    $datum_utakmice = $_POST['datum_utakmice'];
    $id_takmicenja = $_POST['id_takmicenja'];
    $datum_utakmice = $_POST['datum_utakmice'];
    $tip = $_POST['tip'];
    echo $id_takmicenja;
    echo $datum_utakmice;
    echo $ime_tima1;
    echo $ime_tima2;
    echo $tip;
    require_once 'db.php';


    try {
        //$result = izvrsi_upit("INSERT INTO utakmica values(?,?,?,?,?,?,?,?);", "iissssss",NULL, $id_takmicenja, $ime_tima1, $ime_tima2, $datum_utakmice, NULL,NULL,NULL);
        izvrsi_upit("INSERT INTO utakmica(id_takmicenja,ime_tima1, ime_tima2,datum_utakmice,tip) VALUES (?,?,?,?,?)", "issss", $id_takmicenja, $ime_tima1, $ime_tima2, $datum_utakmice,$tip);
        echo " <script> alert('Uspesno ste organizovali utakmicu izmedju $ime_tima1 i $ime_tima2') </script>";
        header("Location:organizuj_utakmice.php?id_takmicenja=$id_takmicenja");
    } catch (Exception $e) {
        $greska = $e->getMessage();
    }
}

?>
<?php
#uzimamo id-takmicenja
if (isset($_GET['id_takmicenja'])) {
    $id_takmicenja = $_GET['id_takmicenja'];
}


echo "
<div class='text-center col-xs-12 col-md-6 col-lg-4 mt-5' style=' margin:auto;'>
    <h1>Unesite podatke za kreiranje takmicenja</h1><br>
    <form action='organizuj_utakmice.php' method='POST'>
        
        <div class='form-group text-left'>
        <label>Datum utakmice</label>
        <input type='date' class='form-control' name='datum_utakmice' placeholder='' required>
    </div>
    <div class='form-group text-left'>
        <label>Tip utakmice</label>
        <input type='text' class='form-control' name='tip' placeholder='' required>
    </div>
        <div class='form-group text-left'>
        <label>Tim1</label>
           ";

require_once "db.php";
echo "
            <select class='form-control' id='sel1' name = 'ime_tima1' required>
            <option value='' disabled selected>Izaberite</option>";

#uzimamo sve timove koji su se prijavili za takmicenje
$rezultat = izvrsi_upit("SELECT * from prijava_za_takmicenje where id_takmicenja = ? ", "i", $id_takmicenja);


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
echo "
        </div>
        <div class='form-group text-left'>
        <label>Tim2</label>";
echo "
        <select class='form-control' id='sel2' name = 'ime_tima2' required>
        <option value='' disabled selected>Izaberite</option>";
#uzimamo sve timove koji su se prijavili za takmicenje
$rezultat = izvrsi_upit("SELECT * from prijava_za_takmicenje where id_takmicenja = ? ", "i", $id_takmicenja);


for ($j = 0; $j < $rezultat->num_rows; ++$j) {
    $red = $rezultat->fetch_array(MYSQLI_ASSOC);
    $ime_tima = $red['ime_tima'];
    echo "
            <option value ='$ime_tima'>$ime_tima</option>
                ";
}

echo "       
         </select>
         </div>
         <input type = 'hidden' name = 'id_takmicenja' value = '$id_takmicenja'>";



?>

<?php
if ($greska != "")
    echo    '<div class="text-center">
                        <p class="text-center text-danger">' . $greska . '</p>
                    </div>';
?>


<button type="submit" class="btn btn-success btn-lg btn-block">Unesi podatke</button>
</form>

</div>

<?php require_once 'footer.php' ?>