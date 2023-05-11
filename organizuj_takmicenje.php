<?php

require_once 'header.php'; ?>
<?php
$greska = "";

if (isset($_POST['naziv_takmicenja']) && isset($_POST['rok_za_prijavu']) && isset($_POST['pol_takmicenja'])) {

    $naziv_takmicenja = $_POST['naziv_takmicenja'];
    $kategorija_takmicenja = $_POST['kategorija_takmicenja'];
    $pol_takmicenja = $_POST['pol_takmicenja'];
    $rok_za_prijavu = $_POST['rok_za_prijavu'];
    $datum_utakmice = $_POST['datum_utakmice'];


    require_once 'db.php';


    try{
    $result = izvrsi_upit("INSERT INTO takmicenje values(?,?,?,?,?,?,?,?);", "isssssss",NULL, $naziv_takmicenja, $rok_za_prijavu, $datum_utakmice, $kategorija_takmicenja, $pol_takmicenja,'u toku', NULL);

    echo " <script> alert('Uspesno ste organizovali takmicenje') </script>";
    }
    catch (Exception $e) {
        $greska = $e->getMessage();
    }

    
}

?>
<?php

echo "
<div class='text-center col-xs-12 col-md-6 col-lg-4 mt-5' style=' margin:auto;'>
    <h1>Unesite podatke za kreiranje takmicenja</h1><br>
    <form action='organizuj_takmicenje.php' method='POST'>
        <div class='form-group text-left'>
        
            <label>Naziv takmicenja</label>
            <input type='text' class='form-control' name='naziv_takmicenja' autofocus required>
        </div>
        
        <div class='form-group text-left'>
        <label>Rok za prijavu</label>
        <input type='date' class='form-control' name='rok_za_prijavu' placeholder='' required>
    </div>
    <div class='form-group text-left'>
        <label>Datum prve utakmice</label>
        <input type='date' class='form-control' name='datum_utakmice' placeholder='' required>
    </div>
        <div class='form-group text-left'>
        <label>Izaberite kategoriju takmicenja</label>
            <select class='form-control' id='sel1' name = 'kategorija_takmicenja' required>
            <option value='' disabled selected>Izaberite</option>
        <option value= 'decija'>decija</option>
        <option value = 'srednja'>srednja</option>
        <option value = 'odrasli'>odrasli</option>
        
        
             </select>
        </div>
        <div class='form-group text-left'>
        <label>Izaberite pol za takmicenje</label>
            <select class='form-control' id='sel1' name = 'pol_takmicenja' required>
            <option value='' disabled selected>Izaberite</option>
        <option value= 'muski'>muski</option>
        <option value = 'zenski'>zenski</option>
        
        
             </select>
        </div>";

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