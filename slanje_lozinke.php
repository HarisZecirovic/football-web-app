<?php require_once "header.php" ?>

<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
if(isset($_POST['posalji'])){
    $el_posta = $_POST['el_posta'];
    require_once "db.php";
    $result = izvrsi_upit("SELECT * from user where el_posta = ?", "s", $el_posta);
    if($result->num_rows > 0){
        echo $el_posta;
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $username = $row['username'];
        $password = $row['password'];
        $ime = $row['ime'];
        $prezime = $row['prezime'];
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

    $mail->addAddress($el_posta, "$ime $prezime");

    $mail->isHTML(true);

    $mail->Subject = "Informacije o nalogu";
    $mail->Body = "<i>Vasi podaci: \n Username je: $username i Lozinka je: $password</i>";
    $mail->AltBody = "This is the plain text version of the email content";
    header("Location:uspesno_lozinka.php");
    try {
        $mail->send();
        echo "Message has been sent successfully";
    } catch (Exception $e) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    }
    }

}

if(isset($_GET['username'])){
    $username = $_GET['username'];
    $ime_tima = $_GET['ime_tima'];
    
}


echo "
<form action = 'slanje_lozinke.php' method = 'post'>
<div class='text-center col-xs-12 col-md-6 col-lg-4 ' id='tip' style=' margin:auto;'>
    <label class = 'mt-5'>Unesite email adresu</label>
    <input type='text' class='form-control' name='el_posta' autofocus required>
    ";
    
    
echo "
    
    <button type='submit' name = 'posalji' class='btn btn-success btn-lg btn-block mt-5'>Posalji</button>
    </div>

</form>";



















?>











































<?php require_once "footer.php" ?>