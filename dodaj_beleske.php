<?php require_once "header.php"; ?>

<?php


if (isset($_POST['ime_tima']) && isset($_POST['tekst_beleske'])) {
    
    $ime_tima = $_POST['ime_tima'];
    $tekst_beleske = $_POST['tekst_beleske'];
    $usernameSportiste = $_POST['usernameSportiste'];
    require_once "db.php";
    izvrsi_upit("INSERT INTO beleske(usernameSportiste,ime_tima,tekst_beleske) values(?,?,?)", "sss", $usernameSportiste,$ime_tima, $tekst_beleske);
    echo " <script> alert('Uspesno ste dodali belesku') </script>";

}

?>
<?php
date_default_timezone_set('Europe/Belgrade');
$vreme = date('m/d/Y h:i:s a', time());
echo $vreme;

#iz url-a uzimam username, ime itd..
if (isset($_GET['username'])) {
    $usernameSportiste = $_GET['username'];
    $ime_tima = $_GET['ime_tima'];
    $ime = $_GET['ime'];
    $prezime = $_GET['prezime'];
}
?>


<form class="ml-5" action="dodaj_beleske.php" method="POST" enctype="multipart/form-data">
    <?php echo "

<div class='form-group text-left'>
        <label>Ime i prezime</label>
        <input type='text' class='form-control' name='ime' placeholder='' value = '$ime $prezime' disabled required>
        <input type ='hidden' name = 'ime_tima' value = '$ime_tima'> 
        <input type ='hidden' name = 'usernameSportiste' value = '$usernameSportiste'> 
    </div>";
    ?>
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Tekst beleske</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="10" name="tekst_beleske"></textarea>
    </div>

    <div class="text-center">

        <button type="submit" class="btn btn-primary col-4" name="vest">Dodaj belesku</button>
    </div>

</form>



































<?php require_once "footer.php"; ?>