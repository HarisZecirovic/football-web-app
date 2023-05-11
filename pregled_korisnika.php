<?php require_once "header.php"; ?>
<?php
if (isset($_POST['izbaci']) &&  isset($_POST['ime_tima'])) {
    $ime_tima = $_POST['ime_tima'];
    $usernameTrenera = $_POST['usernameTrenera'];
    require_once 'db.php';
    #kada izbacujemo trenera
    izvrsi_upit("UPDATE tim set usernameTrenera = ? where ime_tima = ?", "ss","s", "$ime_tima");
    echo " <script> alert('Uspesno ste izbacili trenera iz tima $ime_tima') </script>";
}

?>
<script src="tabela.js"> </script>
<div class="container">
    <input class="form-control mb-4 mt-5" id="tableSearch" type="text" placeholder="Pretrazite">

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Ime i Prezime</th>
                <th>Ime Tima</th>
                <th>Pol</th>

            </tr>
        </thead>
        <tbody id="myTable">
            <?php
            require_once "db.php";


            //$result = izvrsi_upit("SELECT * from user JOIN trener on user.username = trener.username JOIN tim on trener.username = tim.usernameTrenera where user.tip = 'trener'");
            #selektujemo sve iz tima i uzimamo username trenera
            $result = izvrsi_upit("SELECT * FROM tim");

            $rows = $result->num_rows;

            for ($i = 0; $i < $rows; ++$i) {

                echo "<form action = 'pregled_korisnika.php' method = 'post'>";
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $username = $row['usernameTrenera'];
                $ime_tima = $row['ime_tima'];
                #i ovde posle uzimamo podatke toga trenera
                $rezultat = izvrsi_upit("SELECT * from user where username = ?","s", $username);
                $red = $rezultat->fetch_array(MYSQLI_ASSOC);
                $ime = $red['ime'];
                $prezime = $red['prezime'];
                $pol = $red['pol'];
                $tip = $red['tip'];

                echo <<<_LOGGEDIN
                <tr>
                <td>$ime $prezime</td>
                <td>$ime_tima</td>
                <td>$pol</td>
                
                
               _LOGGEDIN;


                echo "
                  
                <input type = 'hidden' name = 'ime_tima' value = '$ime_tima'>
                <input type = 'hidden' name = 'usernameTrenera' value = '$username'>
                <input type = 'hidden' name = 'pol' value = '$pol'>";
                #ako je s upisano na mestu username za trenera onda nema taj tim trenera, u suprotnom ima
                if($username != "s"){
                    echo "<td> <button type='submit' name = 'izbaci' class='btn btn-danger'>izbaci sa mesta trenera</button></td>";
                }
                echo "
                
                <td><a class = 'btn btn-outline-info' href ='promeni_trenera_tima.php?username=$username&ime_tima=$ime_tima'>Promeni trenera</a> </td>
                <td><a class = 'btn btn-outline-info' href ='pregled_podataka.php?username=$username&tip=$tip'> pregledaj podatke</a> </td>  
                
              </tr>
              </form>
              ";
            }

            ?>

        </tbody>
    </table>
</div>
<?php require_once 'footer.php' ?>