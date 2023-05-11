<?php require_once "header.php"; ?>
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['odbij']) && isset($_POST['ime_tima'])) {
  $ime_tima = $_POST['ime_tima'];
  $usernameSportiste = $_POST['usernameSportiste'];
  $kategorija = $_POST['kategorija'];
  
  require_once "db.php";
  izvrsi_upit("DELETE FROM clanovi where ime_tima = ?" ,"s", $ime_tima);
}
elseif(isset($_POST['prihvati'])){
  $ime_tima = $_POST['ime_tima'];
  $usernameSportiste = $_POST['usernameSportiste'];
  require_once "db.php";
  $result = izvrsi_upit("SELECT * FROM user where username = ?", "s", $usernameSportiste);
  $row = $result->fetch_array(MYSQLI_ASSOC);
  $el_posta = $row['el_posta'];
  $ime = $row['ime'];
  izvrsi_upit("UPDATE clanovi SET dozvola = 'odobreno' where ime_tima = ? and usernameSportiste = ?;", 'ss', $ime_tima, $usernameSportiste);
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

    $mail->Subject = "Informacije o zahtevu za klub $ime_tima";
    $mail->Body = "<i>Vas zahtev za klub $ime_tima je odobren\n</i>";
    $mail->AltBody = "This is the plain text version of the email content";
    header("Location:pregled_zahteva_za_tim.php");
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
        <th>Ime i prezime</th>
        <th>Ime tima</th>
        <th>Kategorija</th>
        <th>Pol tima</th>
        <th>Pol sportiste</th>
        <th>Godine Sportiste</th>

      </tr>
    </thead>
    <tbody id="myTable">
      <?php
      require_once "db.php";
      #selektujem iz tima i joinam clanovi je je dozvola za tim je NULL
      $result = izvrsi_upit("SELECT * from tim JOIN clanovi ON tim.ime_tima = clanovi.ime_tima where tim.usernameTrenera = ? AND clanovi.dozvola is NULL", "s", $korisnik);

      $rows = $result->num_rows;

      for ($i = 0; $i < $rows; ++$i) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $ime_tima = $row['ime_tima'];
        $kategorija = $row['kategorija'];
        $pol = $row['pol'];
        $pol_sportiste = $row['polSportiste'];
        $godine_sportiste = $row['godine_sportiste'];
        $usernameSportiste = $row['usernameSportiste'];
        #uzimam dodatne podatke sportiste koji salje zahtev
        $rezultat = izvrsi_upit("SELECT * from user where username = ?", "s", $usernameSportiste);
        $red = $rezultat->fetch_array(MYSQLI_ASSOC);
        $tip = $red['tip'];
        $ime = $red['ime'];
        $prezime = $red['prezime'];
        #ovo izracunavanje njegovih godina na osnovu datuma rodjenja
        $age = floor((time() - strtotime($godine_sportiste)) / 31556926);
        echo " <form action = 'pregled_zahteva_za_tim.php' method = 'post'>";
        echo <<<_LOGGEDIN
                <tr>
                <td>$ime $prezime</td>
                <td>$ime_tima</td>
                <td>$kategorija</td>
                <td>$pol</td>
                <td>$pol_sportiste</td>
                <td>$age</td>
                
                
                
                <td>  
                <input type = 'hidden' name = 'ime_tima' value = '$ime_tima'>
                <input type = 'hidden' name = 'usernameSportiste' value = '$korisnik'>
                <input type = 'hidden' name = 'kategorija' value = '$kategorija'>
                <input type = 'hidden' name = 'usernameSportiste' value = '$usernameSportiste'>
                <button type='submit' name = 'prihvati' onclick = 'return confirm("Da li ste sigurni?")' class='btn btn-success'>Prihvati</button> 
                <button type='submit' name = 'odbij' onclick = 'return confirm("Da li ste sigurni?")'class='btn btn-danger'>Odbij</button> </form> </td>
                <td><a class = 'btn btn-outline-info' href ='pregled_podataka.php?username=$usernameSportiste&tip=$tip'> pregledaj podatke</a> </td>
              </tr>
              _LOGGEDIN;
      }

      ?>

    </tbody>
  </table>
</div>
<?php require_once 'footer.php' ?>