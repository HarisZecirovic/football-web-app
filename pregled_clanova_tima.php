<?php require_once "header.php"; ?>
<?php
if (isset($_POST['izbaci']) &&  isset($_POST['ime_tima'])) {
    $ime_tima = $_POST['ime_tima'];
    $usernameSportiste = $_POST['usernameSportiste'];
    $kategorija = $_POST['kategorija'];
    $pol = $_POST['pol'];
    $usernameSportiste = $_POST['usernameSportiste'];
    require_once 'db.php';
    izvrsi_upit("DELETE from clanovi where usernameSportiste = ? and dozvola = 'odobreno'", "s", $usernameSportiste);
    echo " <script> alert('Uspesno ste izbacili sportistu iz tima $ime_tima') </script>";
}

?>
<script src="tabela.js"> </script>
<div class="container">
    <input class="form-control mb-4 mt-5" id="tableSearch" type="text" placeholder="Pretrazite">

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Ime tima</th>
                <th>Kategorija</th>
                <th>Pol</th>
                <th>Ime i prezime</th>

            </tr>
        </thead>
        <tbody id="myTable">
            <?php
            require_once "db.php";
            if ($_SESSION['tip_korisnika'] == 'trener') {

                $result = izvrsi_upit("SELECT * from tim JOIN clanovi on tim.ime_tima = clanovi.ime_tima where tim.usernameTrenera = ? and dozvola = 'odobreno' ", "s", $korisnik);
            }
            elseif($_SESSION['tip_korisnika'] == 'sportista'){
                if(isset($_GET['ime_tima'])){
                    $ime_tima = $_GET['ime_tima'];
                    $result = izvrsi_upit("SELECT * from clanovi where ime_tima = ?","s", $ime_tima);
                }
            }
            $rows = $result->num_rows;

            for ($i = 0; $i < $rows; ++$i) {

                $row = $result->fetch_array(MYSQLI_ASSOC);
                $ime_tima = $row['ime_tima'];
                $kategorija = $row['kategorija'];
                $pol = $row['polSportiste'];
                $usernameSportiste = $row['usernameSportiste'];
                echo " <form action = 'pregled_clanova_tima.php' method = 'post'>";
                $rezultat = izvrsi_upit("SELECT * from user where username = ?", "s", $usernameSportiste);
                $red = $rezultat->fetch_array(MYSQLI_ASSOC);
                $ime = $red['ime'];
                $prezime = $red['prezime'];
                $tip = $red['tip'];

                echo <<<_LOGGEDIN
                <tr>
                <td>$ime_tima</td>
                <td>$kategorija</td>
                <td>$pol</td>
                <td>$ime $prezime</td>
                
               _LOGGEDIN;


                echo "
                  
                <input type = 'hidden' name = 'ime_tima' value = '$ime_tima'>
                <input type = 'hidden' name = 'usernameSportiste' value = '$korisnik'>
                <input type = 'hidden' name = 'kategorija' value = '$kategorija'>
                <input type = 'hidden' name = 'usernameSportiste' value = '$usernameSportiste'>
                <input type = 'hidden' name = 'pol' value = '$pol'>";

                $upit = izvrsi_upit("SELECT * FROM clanovi where ime_tima = ?", "s", $ime_tima);
                #provera koliko ima clanova nekog tima, za izbacivanje ovog confirma u slucaju da ima samo 1 clana
                if($_SESSION['tip_korisnika'] == 'trener'){
                if ($upit->num_rows == 1) {


                    echo <<<_Izbaci
                <td><button type='submit' name = 'izbaci' onclick = 'return confirm("Ovim potezom necete imati dovoljno clanova tima $ime_tima?")' class='btn btn-danger'>Izbaci iz tima</button> </form> </td>
                _Izbaci;
                } else {
                    echo <<<_Izbaci
                <td><button type='submit' name = 'izbaci' onclick = 'return confirm("Da li ste sigurni")' class='btn btn-danger'>Izbaci iz tima</button> </form> </td>
                _Izbaci;
                }
            }
                echo "
                
                <td><a class = 'btn btn-outline-info' href ='pregled_podataka.php?username=$usernameSportiste&tip=$tip'> pregledaj podatke</a> </td> "; 
                if($_SESSION['tip_korisnika'] == 'trener'){
                    echo"
                <td><a class = 'btn btn-outline-info' href ='dodaj_beleske.php?username=$usernameSportiste&ime_tima=$ime_tima&ime=$ime&prezime=$prezime'>dodaj beleske</a> </td>";
                }
                echo "
              </tr>
              ";
            }

            ?>

        </tbody>
    </table>
</div>
<?php require_once 'footer.php' ?>