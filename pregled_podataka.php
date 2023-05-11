<?php require_once 'header.php' ?>

<?php
if (isset($_GET['username'])) {
  $username = $_GET['username'];
  $tip = $_GET['tip'];
}
if ($tip == "trener") {
  require_once 'db.php';
  $result = izvrsi_upit("SELECT * from user JOIN $tip on user.username = $tip.username where user.username = ?;", "s", $username);
  $rows = $result->num_rows;

  echo  " <table class='table'>
<thead>
  <tr>
    <th scope='col'>#</th>
    <th scope='col'>Ime i Prezime</th>
    <th scope='col'>Slika</th>
    <th scope='col'>Godina staza</th>
  </tr>
</thead>
<tbody>
";
  for ($i = 0; $i < $rows; ++$i) {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $ime = $row['ime'];
    $prezime = $row['prezime'];
    $slika = $row['slika'];
    $godina_rada =  $row['godina_rada'];
    $age = floor((time() - strtotime($godina_rada)) / 31556926);
    echo " <tr>
    <th scope='row'></th>
    <td>$ime $prezime</td>
    
    <td><img src='ProfilneSlike/$slika' alt='Italian Trulli'></td>
    <td>$age</td>
    <td><a class = 'btn btn-primary' href =pregled_zahteva.php> Vrati se na pregled zahteva</a>   </td>
    </tr>
    ";
  }
  echo "</tbody>
        </table>";
} elseif ($tip == "sportista") {
  require_once 'db.php';
  $result = izvrsi_upit("SELECT * from user JOIN $tip on user.username = $tip.username where user.username = ?;", "s", $username);
  $rows = $result->num_rows;

  echo  " <table class='table'>
<thead>
  <tr>
    <th scope='col'>#</th>
    <th scope='col'>Ime i Prezime</th>
    <th scope='col'>Slika</th>
    <th scope='col'>Godine</th>
    <th scope='col'>Datum rodjenja</th>
  </tr>
</thead>
<tbody>
";
  for ($i = 0; $i < $rows; ++$i) {
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $ime = $row['ime'];
    $prezime = $row['prezime'];
    $slika = $row['slika'];
    $datum_rodjenja =  $row['datum'];
    $age = floor((time() - strtotime($datum_rodjenja)) / 31556926);
    echo " <tr>
    <th scope='row'></th>
    <td>$ime $prezime</td>
    
    <td><img src='ProfilneSlike/$slika' alt='Italian Trulli'></td>
    <td>$age</td>
    <td>$datum_rodjenja</td>";
    
    if (isset($_SESSION['korisnik']) && $_SESSION['tip_korisnika'] == 'admin') {
      echo "
    <td><a class = 'btn btn-primary' href =pregled_zahteva.php> Vrati se na pregled zahteva</a>   </td>";
    } elseif (isset($_SESSION['korisnik']) && $_SESSION['tip_korisnika'] == 'trener') {
      echo "
    <td><a class = 'btn btn-primary' href =pregled_clanova_tima.php> Vrati se na pregled clanova</a>   </td>";
    }
    echo "
    </tr>
    ";
  }
  echo "</tbody>
        </table>";
}
if($_SESSION['tip_korisnika']== 'admin' || $_SESSION['tip_korisnika'] == 'trener'){
  echo  " <table class='table'>
<thead>
  <tr>
    <th scope='col'>#</th>
    <th scope='col'>Tekst beleske</th>
    
  </tr>
</thead>
<tbody>
";
require_once "db.php";
$result = izvrsi_upit("SELECT * from beleske where usernameSportiste = ?", "s" ,$username);
for($i=0; $i < $result->num_rows;$i++){
  $row = $result->fetch_array(MYSQLI_ASSOC);
  $tekst_beleske = $row['tekst_beleske'];
echo " <tr>
    <th scope='row'>$i</th>
    <td>$tekst_beleske</td>
    ";



echo "</tr>";
}
echo "</tbody>
        </table>";

}

?>
<?php require_once 'footer.php' ?>