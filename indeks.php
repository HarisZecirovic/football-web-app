<?php
require_once 'header.php';

if (isset($_POST['id_vesti'])) {
    $id_vesti = $_POST['id_vesti'];
    require_once "db.php";

    izvrsi_upit("DELETE FROM vesti where id_vesti = ?", "i", $id_vesti);

    echo "<script> alert('Uspesno ste obrisali vesti') </script> ";
}

echo "
<form action='pretrazi_vesti.php' method='GET'>

<input class='form-control mb-4 mt-5' name = 'pretraga' id='tableSearch' type='text' placeholder='Pretrazite'> 
<button type='submit' class='btn btn-primary'>Pretrazi</button>
</form>
      <div>   ";
echo"        
  <table class='table  mt-5 mr-5'>
    
    <tbody id='myTable'>
      
      
        
        ";
require_once "db.php";
if (isset($_SESSION['korisnik']) && $_SESSION['tip_korisnika'] == 'sportista') {
    $page = $_GET['page'];

    if ($page == "" || $page == "1") {
        $page1 = 0;
    } else {
        $page1 = ($page * 4) - 4;
    }
    $korisnik = $_SESSION['korisnik'];
    $result = izvrsi_upit("SELECT * FROM clanovi where usernameSportiste = ?", "s", $korisnik);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $ime_tima = $row['ime_tima'];

    $result = izvrsi_upit("SELECT * FROM vesti where ime_tima_vesti = ? or privatnost = 'javno' ORDER BY vreme_vesti DESC LIMIT $page1,4", "s", $ime_tima);
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
    $result = izvrsi_upit("SELECT * FROM vesti where privatnost = 'javno' or ime_tima_vesti = ?", "s", $ime_tima);
    #koliko onih brojevnih linkova za strane za vesti
    $count = $result->num_rows;
    #koliko strana cu da imam. npr imam 8 vesti podeljeno sa 4 su 2 strane, znaci 4 vesti po strani
    $a = $count / 4;
    $a = ceil($a);
    echo "<div style = 'text-align:center'>";
    for ($i = 1; $i <= $a; $i++) {

        echo "
        <a href = 'indeks.php?page=$i' class = 'col-12 text-center'> $i  </a> ";
    }
    echo "</div>";
} elseif (isset($_SESSION['korisnik']) && $_SESSION['tip_korisnika'] == 'trener') {
    $page = $_GET['page'];

    if ($page == "" || $page == "1") {
        $page1 = 0;
    } else {
        $page1 = ($page * 4) - 4;
    }
    $korisnik = $_SESSION['korisnik'];
    $result = izvrsi_upit("SELECT * FROM tim where usernameTrenera = ?", "s", $korisnik);

    if ($result->num_rows >= 2) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $ime_tima = $row['ime_tima'];
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $ime_tima2 = $row['ime_tima'];


        $rezultat = izvrsi_upit("SELECT * FROM vesti where ime_tima_vesti = ? or ime_tima_vesti = ? or privatnost = 'javno' ORDER BY vreme_vesti DESC LIMIT $page1,4", "ss", $ime_tima, $ime_tima2);
    } else {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $ime_tima = $row['ime_tima'];
        $rezultat = izvrsi_upit("SELECT * FROM vesti where ime_tima_vesti = ? or privatnost = 'javno' ORDER BY vreme_vesti DESC LIMIT $page1,4 ", "s", $ime_tima);
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
    $result = izvrsi_upit("SELECT * FROM vesti where privatnost = 'javno' or ime_tima_vesti = ? or ime_tima_vesti = ?", "ss", $ime_tima, $ime_tima2);
    $count = $result->num_rows;
    $a = $count / 4;
    $a = ceil($a);
    echo "<div style = 'text-align:center'>";
    for ($i = 1; $i <= $a; $i++) {

        echo "
        <a href = 'indeks.php?page=$i' class = 'col-12 text-center'> $i  </a> ";
    }
    echo "</div>";
}elseif(isset($_SESSION['korisnik']) && $_SESSION['tip_korisnika'] == 'admin'){
    
    $page = $_GET['page'];

    if ($page == "" || $page == "1") {
        $page1 = 0;
    } else {
        $page1 = ($page * 4) - 4;
    }
    $korisnik = $_SESSION['korisnik'];

    
        $rezultat = izvrsi_upit("SELECT * FROM vesti ORDER BY vreme_vesti DESC LIMIT $page1,4");
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
    $result = izvrsi_upit("SELECT * FROM vesti");
    $count = $result->num_rows;
    $a = $count / 4;
    $a = ceil($a);
    echo "<div style = 'text-align:center'>";
    for ($i = 1; $i <= $a; $i++) {

        echo "
        <a href = 'indeks.php?page=$i' class = 'col-12 text-center'> $i  </a> ";
    }
    echo "</div>";
    

}
 else {
    $page = $_GET['page'];

    if ($page == "" || $page == "1") {
        $page1 = 0;
    } else {
        $page1 = ($page * 4) - 4;
    }
    $result = izvrsi_upit("SELECT * FROM vesti where privatnost = 'javno' ORDER BY vreme_vesti DESC LIMIT $page1, 4");

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
    $result = izvrsi_upit("SELECT * FROM vesti where privatnost = 'javno'");
    $count = $result->num_rows;
    $a = $count / 4;
    $a = ceil($a);
    echo "<div style = 'text-align:center'>";
    for ($i = 1; $i <= $a; $i++) {

        echo "
        <a href = 'indeks.php?page=$i' class = 'col-12 text-center'> $i  </a> ";
    }
    echo "</div>";
}


require_once 'footer.php';
