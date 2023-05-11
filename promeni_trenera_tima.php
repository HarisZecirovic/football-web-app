<?php require_once "header.php" ?>

<?php
if(isset($_POST['promeni'])){
    $usernameTrenera = $_POST['usernameTrenera'];
    $ime_tima = $_POST['ime_tima'];
    require_once "db.php";
    izvrsi_upit("UPDATE tim set usernameTrenera = ? where ime_tima = ?", "ss", $usernameTrenera, $ime_tima);

}

if(isset($_GET['username'])){
    $username = $_GET['username'];
    $ime_tima = $_GET['ime_tima'];
    
}


echo "
<form action = 'promeni_trenera_tima.php' method = 'post'>
<div class='text-center col-xs-12 col-md-6 col-lg-4 ' id='tip' style=' margin:auto;'>
    <label>Izaberite tip</label>
    <select class='form-control' id='sel2' name='usernameTrenera' required>
    <option value='' disabled selected>Izaberite</option>
    ";
    require_once "db.php";
    $result = izvrsi_upit("SELECT * from user where tip = 'trener'");
    for($i=0; $i < $result->num_rows; $i++){
        $row= $result->fetch_array(MYSQLI_ASSOC);
        $username = $row['username'];
        $ime = $row['ime'];
        $prezime = $row['prezime'];
        #trebaju mi treneri koji imaju manje od 2 tima
        $rezultat = izvrsi_upit("SELECT * FROM tim where usernameTrenera =?", "s", $username);
        if($rezultat->num_rows < 2){
        echo"
        <option value='$username'>$ime $prezime</option>
        ";
        }
    }
echo "
    </select>
    <input type ='hidden' name = 'username' value = '$username'>
    <input type ='hidden' name = 'ime_tima' value = '$ime_tima'>
    <button type='submit' name = 'promeni' class='btn btn-success btn-lg btn-block mt-5'>Promeni</button>
</div>

</form>";



















?>











































<?php require_once "footer.php" ?>