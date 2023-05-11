<?php require_once "header.php"; ?>
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['obrisi'])) {
    $username = $_POST['username'];
    $tip = $_POST['tip'];
    $el_posta = $_POST['el_posta'];
    $ime = $_POST['ime'];
    require_once 'db.php';
    #proveravamo da li admin brise trenera koji ima neki tim
    #ako on ima neki tim, a admin zeli da ga obrise, da ne bi obrisao i njegov tim 
    #on brise trenera, a na mestu gde je on bio trener upisuje s slovo to random slovo
    #da bi taj tim ostao da se ne bi izbrisao zajedno sa trenerom
    $result = izvrsi_upit("SELECT * from tim where usernameTrenera = ?", "s", $username);
    if ($result->num_rows > 0) {
        for ($i = 0; $i < $result->num_rows; $i++) {
            $row = $result->fetch_array(MYSQLI_ASSOC);
            $ime_tima = $row['ime_tima'];
            izvrsi_upit("UPDATE tim set usernameTrenera = ? where ime_tima = ?", "ss", "s", $ime_tima);
        }
    }
    # i ovde posle vrsi brisanje
    izvrsi_upit("DELETE user,$tip from user INNER JOIN $tip on user.username= $tip.username where user.username= ?;", 's', $username);
    //izvrsi_upit("UPDATE user  SET dozvola = 'nije odobreno' where username = ? ;", 's', $username);
    require_once "vendor/autoload.php";
    $mail = new PHPMailer(true);

    //Enable SMTP debugging.
    $mail->SMTPDebug = 3;
    //Set PHPMailer to use SMTP.
    $mail->isSMTP();
    //Set SMTP host name                          
    $mail->Host = "smtp.gmail.com";
    //Set this to true if SMTP host requires authentication to send email
    $mail->SMTPAuth = true;
    //Provide username and password     
    $mail->Username = "hariszecirovic19@gmail.com";
    $mail->Password = "";
    //If SMTP requires TLS encryption then set it
    $mail->SMTPSecure = "tls";
    //Set TCP port to connect to
    $mail->Port = 587;

    $mail->From = "hariszecirovic19@gmail.com";
    $mail->FromName = "Haris Zecirovic";

    $mail->addAddress($el_posta, $ime);

    $mail->isHTML(true);

    $mail->Subject = "Informacije o nalogu";
    $mail->Body = "<i>Izbaceni ste sa stranice fudbal</i>";
    $mail->AltBody = "This is the plain text version of the email content";
    header("Location:pregled_svih_korisnika.php");
    try {
        $mail->send();
        echo "Message has been sent successfully";
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}

?>
<script src="tabela.js"> </script>
<div class="container">
    <input class="form-control mb-4 mt-5" id="tableSearch" type="text" placeholder="Pretrazite">

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Ime i Prezime</th>
                <th>Tip</th>
                <th>Pol</th>

            </tr>
        </thead>
        <tbody id="myTable">
            <?php
            require_once "db.php";


            $result = izvrsi_upit("SELECT * from user where tip = 'trener' or tip = 'sportista'");


            $rows = $result->num_rows;

            for ($i = 0; $i < $rows; ++$i) {
                echo "<form action  = 'pregled_svih_korisnika.php' method = 'post'>";
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $username = $row['username'];
                $ime = $row['ime'];
                $prezime = $row['prezime'];
                $el_posta = $row['el_posta'];
                $pol = $row['pol'];
                $tip = $row['tip'];

                echo <<<_LOGGEDIN
                <tr>
                <td>$ime $prezime</td>
                <td>$tip</td>
                <td>$pol</td>
                
                
               _LOGGEDIN;


                echo "
                  
                <input type = 'hidden' name = 'ime_tima' value = '$ime_tima'>
                <input type = 'hidden' name = 'username' value = '$username'>
                <input type = 'hidden' name = 'tip' value = '$tip'>
                <input type = 'hidden' name = 'el_posta' value = '$el_posta'>
                <input type = 'hidden' name = 'ime' value = '$ime'>
                <input type = 'hidden' name = 'pol' value = '$pol'>";
                echo "
                <td> <button type='submit' name = 'obrisi' class='btn btn-danger'>obrisi</button></td>";

                echo "
                <td><a class = 'btn btn-outline-info' href ='pregled_podataka.php?username=$username&tip=$tip'> pregledaj podatke</a> </td> ";

                echo " 
                
              </tr>
              </form>
              ";
            }

            ?>

        </tbody>
    </table>
</div>
<?php require_once 'footer.php' ?>