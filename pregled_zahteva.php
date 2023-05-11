<?php require_once 'header.php' ?>
<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['odobri']) && isset($_POST['username'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $el_posta = $_POST['el_posta'];
    $ime = $_POST['ime'];
    require_once 'db.php';
    izvrsi_upit("UPDATE user SET dozvola = 'odobreno' where username = ?;", 's', $username);
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
    $mail->Body = "<i>Vas zahtev za registraciju je odobren\n Username je: $username i Lozinka je: $password</i>";
    $mail->AltBody = "This is the plain text version of the email content";
    header("Location:pregled_zahteva.php");
    try {
        $mail->send();
        echo "Message has been sent successfully";
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
} else if (isset($_POST['odbij']) && isset($_POST['username'])) {
    $username = $_POST['username'];
    $el_posta = $_POST['el_posta'];
    $ime = $_POST['ime'];
    $tip = $_POST['tip'];
    require_once 'db.php';
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
    $mail->Body = "<i>Vas zahtev za registraciju nije odobren</i>";
    $mail->AltBody = "This is the plain text version of the email content";
    header("Location:pregled_zahteva.php");
    try {
        $mail->send();
        echo "Message has been sent successfully";
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
}

?>

<?php
require_once 'db.php';

$result = izvrsi_upit("SELECT * FROM user where dozvola is NULL");
//$result .= izvrsi_upit("SELECT * FROM user JOIN zahtev ON user.username = zahtev.usernama where user.username = zahtev.username");
$rows = $result->num_rows;
echo  " <table class='table mr-5 ml-5 mt-5'>
<thead>
  <tr>
    <th scope='col'>#</th>
    <th scope='col'>Ime i Prezime</th>
    <th scope='col'>Mesto rodjenja</th>
    <th scope='col'>Prijava za</th>
  </tr>
</thead>
<tbody>
";
for ($i = 0; $i < $rows; ++$i) {

    $row = $result->fetch_array(MYSQLI_ASSOC);
    $username = $row['username'];
    $password = $row['password'];
    $ime = $row['ime'];
    $prezime = $row['prezime'];
    $pol = $row['pol'];
    $mesto_rodjenja = $row['mesto_rodjenja'];
    $el_posta = $row['el_posta'];
    $tip = $row['tip'];
    
    $age = floor((time() - strtotime($datum_rodjenja)) / 31556926);
    $godine = $age - 10;

    echo " <tr>
    <th scope='row'>$i</th>
    <td>$ime $prezime</td>
    
    <td>$mesto_rodjenja</td>
    <td>$tip</td>
    <form action='pregled_zahteva.php' method='POST'>
    <td>
    <input type = 'submit' name = 'odobri' value = 'Odobri' class = 'btn btn-success'>
    <input type = 'hidden' name = 'username' value = '$username'>
    <input type = 'hidden' name = 'password' value = '$password'>
    <input type = 'hidden' name = 'el_posta' value = '$el_posta'>
    <input type = 'hidden' name = 'tip' value = '$tip'>
    <input type = 'hidden' name = 'ime' value = '$ime'>
    </td>
    <td>
    <input type = 'submit' name = 'odbij' value = 'Odbij' class = 'btn btn-danger'>
    </td>
    
    <td><a class = 'btn btn-outline-info' href ='pregled_podataka.php?username=$username&tip=$tip'> pregledaj podatke</a>   </td>
    
    </form>
  </tr>
    ";
}
echo "</tbody>
        </table>";


?>
<?php require_once 'footer.php' ?>