<?php

require_once 'header.php'; ?>
<?php
$greska = "";

if (isset($_POST['ime_tima']) && isset($_POST['kategorija_tima']) && isset($_POST['pol_tima'])) {

    $ime_tima = $_POST['ime_tima'];
    $kategorija_tima = $_POST['kategorija_tima'];
    $pol_tima = $_POST['pol_tima'];
    $usernameTrenera = $_POST['trener'];
    $broj_igraca = $_POST['broj_igraca'];

    try {
        require_once 'db.php';
        #proveravam da li trener vec ima 2 prijavljena tima
        $result = izvrsi_upit("SELECT * from tim where usernameTrenera = '$usernameTrenera'");
        if ($result->num_rows >= 2)
            throw new Exception("Vec imate dva tima!");
        else{
        izvrsi_upit("INSERT INTO tim values(?,?,?,?,?);", "sssss", $ime_tima, $kategorija_tima, $usernameTrenera, $pol_tima, $broj_igraca);



        echo " <script> alert('Uspesno ste dodali tim') </script>";
        }
    } catch (Exception $e) {
        $greska = $e->getMessage();
    }
}

?>
<?php

echo "
<div class='text-center col-xs-12 col-md-6 col-lg-4 mt-5' style=' margin:auto;'>
    <h1>Unesite podatke za kreiranje tima</h1><br>
    <form action='dodaj_tim_trener.php' method='POST'>
        <div class='form-group text-left'>
        
            <label>Ime tima</label>
            <input type='text' class='form-control' name='ime_tima' autofocus required>
        </div>
        <input type = 'hidden' name = 'trener' value = '$korisnik'>
        <div class='form-group text-left'>
        
        <label>Minimalan broj igraca</label>
        <input type='text' class='form-control' name='broj_igraca' autofocus required>
    </div>
        <div class='form-group text-left'>
        <label>Izaberite kategoriju</label>
            <select class='form-control' id='sel1' name = 'kategorija_tima' required>
            <option value='' disabled selected>Izaberite</option>
        <option value= 'decija'>decija</option>
        <option value = 'srednja'>srednja</option>
        <option value = 'odrasli'>odrasli</option>
        
        
             </select>
        </div>
        <div class='form-group text-left'>
        <label>Izaberite pol</label>
            <select class='form-control' id='sel1' name = 'pol_tima' required>
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