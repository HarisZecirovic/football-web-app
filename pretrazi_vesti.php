<?php require_once "header.php"; ?>

<?php
if (isset($_GET['pretraga'])) {
    $pretraga = $_GET['pretraga'];
}
require_once "db.php";
echo "
<form action='pretrazi_vesti.php' method='GET'>

<input class='form-control mb-4 mt-5' name = 'pretraga' id='tableSearch' type='text' placeholder='Pretrazite'> 
<button type='submit' class='btn btn-primary'>Pretrazi</button>
</form>";
echo "
<div>   
        
  <table class='table  mt-5 mr-5'>
    
    <tbody id='myTable'>
      
      
        
        ";
if (isset($_SESSION['korisnik']) && $_SESSION['tip_korisnika'] == 'sportista') {

    $korisnik = $_SESSION['korisnik'];
    $result = izvrsi_upit("SELECT * FROM clanovi where usernameSportiste = ?", "s", $korisnik);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $ime_tima = $row['ime_tima'];

    
    //$result = izvrsi_upit("SELECT * FROM vesti where ime_tima_vesti = ? or privatnost = 'javno' and ime_tima_vesti LIKE '%$pretraga%' or  privatnost= 'javno' and kategorija_vesti LIKE '%$pretraga%' or privatnost= 'javno' and pol_vesti LIKE '%$pretraga%' ORDER BY vreme_vesti DESC", "s", $ime_tima);
    if($pretraga == $ime_tima){
        $result = izvrsi_upit("SELECT * from vesti where ime_tima_vesti = ? ORDER BY vreme_vesti DESC", "s", $ime_tima);
    }
    else{
    $result = izvrsi_upit("SELECT * FROM vesti where ime_tima_vesti LIKE '%$pretraga%' and privatnost = 'javno' or privatnost = 'javno' and kategorija_vesti LIKE '%$pretraga%' or privatnost = 'javno' and pol_vesti LIKE '%$pretraga%' ORDER BY vreme_vesti DESC");
    }
    for ($i = 0; $i < $result->num_rows; $i++) {
        echo "<tr>";
        for ($j = 0; $j < 4; $j++) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $naslov = $row['naslov_vesti'];
            $slika_naslov = $row['slika_naslov'];
            $vreme_vesti = $row['vreme_vesti'];
            $id_vesti = $row['id_vesti'];
            $privatnost = $row['privatnost'];
            $ime_tima_vesti = $row['ime_tima_vesti'];
            $pol_vesti = $row['pol_vesti'];
            if ($naslov) {


                echo "
                    
                        <td> <a href = 'prikazi_vest.php?id_vesti=$id_vesti'> <h4>$naslov </h4>
                        <p> $vreme_vesti</p>
                        <img src='slikeZaVesti/$slika_naslov' height = '150' width = '180'>
                        <p>Pogledajte vest</p> <p> $ime_tima_vesti </p></a> </td>
                       
                    ";
            } else
                break;
        }
        echo " </tr>";
    }
    echo "  
              
            </tbody>
          </table>
        </div>
        ";
} elseif (isset($_SESSION['korisnik']) && $_SESSION['tip_korisnika'] == 'trener') {
    $korisnik = $_SESSION['korisnik'];
    $result = izvrsi_upit("SELECT * FROM tim where usernameTrenera = ?", "s", $korisnik);

    if ($result->num_rows >= 2) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $ime_tima = $row['ime_tima'];
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $ime_tima2 = $row['ime_tima'];


        $rezultat = izvrsi_upit("SELECT * FROM vesti where ime_tima_vesti = ? or ime_tima_vesti = ? or privatnost = 'javno' and ime_tima_vesti LIKE '%$pretraga%' or privatnost= 'javno' and kategorija_vesti LIKE '%$pretraga%' or privatnost= 'javno' and pol_vesti LIKE '%$pretraga%' ORDER BY vreme_vesti DESC", "ss", $ime_tima, $ime_tima2);
    } else {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $ime_tima = $row['ime_tima'];
        $rezultat = izvrsi_upit("SELECT * FROM vesti where ime_tima_vesti = ? or privatnost = 'javno' and ime_tima_vesti LIKE '%$pretraga%' or privatnost= 'javno' and kategorija_vesti LIKE '%$pretraga%' or privatnost= 'javno' and pol_vesti LIKE '%$pretraga%' ORDER BY vreme_vesti DESC", "s", $ime_tima);
    }
    for ($i = 0; $i < $rezultat->num_rows; $i++) {
        echo "<tr>";
        for ($j = 0; $j < 4; $j++) {
            $red = $rezultat->fetch_array(MYSQLI_ASSOC);
            $naslov = $red['naslov_vesti'];
            $slika_naslov = $red['slika_naslov'];
            $vreme_vesti = $red['vreme_vesti'];
            $id_vesti = $red['id_vesti'];
            $privatnost = $red['privatnost'];
            $ime_tima_vesti = $red['ime_tima_vesti'];
            if ($naslov) {


                echo "
                    <form action='indeks.php' method='POST'>
                <td> <a href = 'prikazi_vest.php?id_vesti=$id_vesti'> <h4>$naslov </h4>
                <p> $vreme_vesti</p>
                <img src='slikeZaVesti/$slika_naslov' height = '150' width = '180'>
                <p>Pogledajte vest </p> <p> $ime_tima_vesti </p></a> 
                <input type = 'hidden' name = 'id_vesti' value = '$id_vesti' >
                ";
                if ($ime_tima == $ime_tima_vesti || $ime_tima2 == $ime_tima_vesti) {
                    echo "
                <input type = 'submit' name = 'izbrisi' value = 'izbrisi vest' class = 'btn btn-danger'> </td>
            ";
                }
                echo "
                </form>
               
            ";
            }
        }
        echo " </tr>";
    }
    echo "  
      
    </tbody>
  </table>
</div>
";
} elseif (isset($_SESSION['korisnik']) && $_SESSION['tip_korisnika'] == 'admin') {
    $korisnik = $_SESSION['korisnik'];
    $rezultat = izvrsi_upit("SELECT * FROM vesti where ime_tima_vesti LIKE '%$pretraga%' or  kategorija_vesti LIKE '%$pretraga%' or  pol_vesti LIKE '%$pretraga%' ORDER BY vreme_vesti DESC");
    for ($i = 0; $i < $rezultat->num_rows; $i++) {
        echo "<tr>";
        for ($j = 0; $j < 4; $j++) {
            $red = $rezultat->fetch_array(MYSQLI_ASSOC);
            $naslov = $red['naslov_vesti'];
            $slika_naslov = $red['slika_naslov'];
            $vreme_vesti = $red['vreme_vesti'];
            $id_vesti = $red['id_vesti'];
            $privatnost = $red['privatnost'];
            $ime_tima_vesti = $red['ime_tima_vesti'];
            if ($naslov) {


                echo "
                    <form action='indeks.php' method='POST'>
                <td> <a href = 'prikazi_vest.php?id_vesti=$id_vesti'> <h4>$naslov </h4>
                <p> $vreme_vesti</p>
                <img src='slikeZaVesti/$slika_naslov' height = '150' width = '180'>
                <p>Pogledajte vest </p> <p> $ime_tima_vesti </p></a> 
                <input type = 'hidden' name = 'id_vesti' value = '$id_vesti' >
                ";
                echo "
                <input type = 'submit' name = 'izbrisi' value = 'izbrisi vest' class = 'btn btn-danger'> </td>
            ";

                echo "
                </form>
               
            ";
            }
        }
        echo " </tr>";
    }
    echo "  
      
    </tbody>
  </table>
</div>
";
} else {
    if ($pretraga != NULL) {
        $result = izvrsi_upit("SELECT * FROM vesti where privatnost = 'javno' and ime_tima_vesti LIKE '%$pretraga%' or privatnost= 'javno' and kategorija_vesti LIKE '%$pretraga%' or privatnost= 'javno' and pol_vesti LIKE '%$pretraga%' ORDER BY vreme_vesti DESC");
    } else {
        $result = izvrsi_upit("SELECT * from vesti where privatnost = 'javno' ORDER BY vreme_vesti");
    }
    for ($i = 0; $i < $result->num_rows; $i++) {
        echo "<tr>";
        for ($j = 0; $j < 4; $j++) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $naslov = $row['naslov_vesti'];
            $slika_naslov = $row['slika_naslov'];
            $vreme_vesti = $row['vreme_vesti'];
            $id_vesti = $row['id_vesti'];
            $privatnost = $row['privatnost'];
            $ime_tima_vesti = $row['ime_tima_vesti'];
            if ($naslov) {


                echo "
            
                <td> <a href = 'prikazi_vest.php?id_vesti=$id_vesti'> <h4>$naslov </h4>
                <p> $vreme_vesti</p>
                <img src='slikeZaVesti/$slika_naslov' height = '150' width = '180'>
                <p>Pogledajte vest </p> <p> $ime_tima_vesti </p></a>  </td>
               
            ";
            } else
                break;
        }
        echo " </tr>";
    }
    echo "  
      
    </tbody>
  </table>
</div>
";
}






?>










































<?php require_once "footer.php" ?>