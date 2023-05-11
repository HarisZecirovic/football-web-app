<?php require_once "header.php" ?>


<?php
#uzimamo preko get metode id_vesti
if (isset($_GET['id_vesti'])) {
    $id_vesti = $_GET['id_vesti'];
}

require_once "db.php";

$result = izvrsi_upit("SELECT * from vesti JOIN slike_vesti on vesti.id_vesti = slike_vesti.id_vesti where vesti.id_vesti = ? and slike_vesti.id_vesti = ?", "ii", $id_vesti, $id_vesti);

$row = $result->fetch_array(MYSQLI_ASSOC);
$naslov_vesti = $row['naslov_vesti'];

$result = izvrsi_upit("SELECT * from vesti JOIN slike_vesti on vesti.id_vesti = slike_vesti.id_vesti where vesti.id_vesti = ? and slike_vesti.id_vesti = ?", "ii", $id_vesti, $id_vesti);
echo "<h1 class ='text-center'> $naslov_vesti</h1>";
for ($i = 0; $i < $result->num_rows; ++$i) {
    $red = $result->fetch_array(MYSQLI_ASSOC);
    $slike = $red['url_slike'];
    
    if($slike){
    echo "<img  src='slikeZaVesti/$slike' height = '150' width = '180' style = 'display: block;
    margin-left: auto;
    margin-right: auto;
    width: 50%; height:50%' class = 'mt-5' >";

    echo "<br>";
    }
}
$result = izvrsi_upit("SELECT * FROM vesti where id_vesti = ?", "i", $id_vesti);
$row = $result->fetch_array(MYSQLI_ASSOC);
$tekst_vesti = $row['tekst_vesti'];

echo "<p class = 'ml-5 mt-5'> $tekst_vesti </p>";



?>































<?php require_once "footer.php" ?>