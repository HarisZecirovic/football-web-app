<?php require_once "header.php" ?>


<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function password_generate($chars)
{
    $data = '1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($data), 0, $chars);
}
$greska = "";
if (isset($_POST['sportista']) && isset($_POST['ime']) && isset($_POST['prezime']) && isset($_POST['prezime']) && isset($_POST['jmbg'])) {

    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $pol = $_POST['pol'];
    $mesto_rodjenja = $_POST['mesto_rodjenja'];
    $drzava_rodjenja = $_POST['drzava_rodjenja'];
    $jmbg = $_POST['jmbg'];
    $br_telefona = $_POST['br_telefona'];
    $el_posta = $_POST['el_posta'];
    $datum_rodjenja = $_POST['datum_rodjenja'];
    $pozicija = $_POST['pozicija'];
    $sutiranje = $_POST['sutiranje'];
    $ime1 = strtolower($ime);
    $prezime1 = strtolower(substr($prezime, 0, 2));
    $nrRand = rand(0, 100);
    $username =  $ime1 . $prezime1 . $nrRand;
    $password = password_generate(7);
    $img = $_FILES['image']['name'];
    //move_uploaded_file($_FILES['image']['tmp_name'],"ProfilneSlike/$username");
    if (isset($_FILES['image']['name'])) {
        $saveto = "ProfilneSlike/$username.jpg";
        move_uploaded_file($_FILES['image']['tmp_name'], "$saveto");
        $typeok = TRUE;

        switch ($_FILES['image']['type']) {
            case "image/jpeg":
                $src = imagecreatefromjpeg($saveto);
                break;
            case "image/png":
                $src = imagecreatefrompng($saveto);
                break;
            default:
                $typeok = FALSE;
                break;
        }

        if ($typeok) {
            list($w, $h) = getimagesize($saveto);

            $max = 100;
            $tw = $w;
            $th = $h;

            if ($w > $h && $max < $w) {
                $th = $max / $w * $h;
                $tw = $max;
            } elseif ($h > $w && $max < $h) {
                $tw = $max / $h * $w;
                $th = $max;
            } elseif ($max < $h) {
                $tw = $th = $max;
            }
            $tmp = imagecreatetruecolor($tw, $th);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
            imageconvolution($tmp, array(
                array(-1, -1, -1),
                array(-1, 16, -1), array(-1, -1, -1)
            ), 8, 0);
            imagejpeg($tmp, $saveto);
            imagedestroy($tmp);
            imagedestroy($src);
        }
    }

    try {
        require_once 'db.php';

        if (strlen($jmbg) > 13 || strlen($jmbg) < 13)
            throw new Exception("Neispravan jmbg!!");

        izvrsi_upit("INSERT INTO user VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?);", "sssssssssssss", $username, $ime, $prezime, $password, $pol, $mesto_rodjenja, $drzava_rodjenja, $jmbg, $br_telefona, $el_posta, "sportista", NULL, "$username.jpg");
        izvrsi_upit("INSERT INTO sportista VALUES(?,?,?,?);", "ssss", $username, $datum_rodjenja, $pozicija, $sutiranje);

        // require_once "vendor/autoload.php";

        // $mail = new PHPMailer(true);

        // //Enable SMTP debugging.
        // $mail->SMTPDebug = 3;
        // //Set PHPMailer to use SMTP.
        // $mail->isSMTP();
        // //Set SMTP host name                          
        // $mail->Host = "smtp.gmail.com";
        // //Set this to true if SMTP host requires authentication to send email
        // $mail->SMTPAuth = true;
        // //Provide username and password     
        // $mail->Username = "hariszecirovic19@gmail.com";
        // $mail->Password = "";
        // //If SMTP requires TLS encryption then set it
        // $mail->SMTPSecure = "tls";
        // //Set TCP port to connect to
        // $mail->Port = 587;

        // $mail->From = "hariszecirovic19@gmail.com";
        // $mail->FromName = "Haris Zecirovic";

        // $mail->addAddress($el_posta, $ime);

        // $mail->isHTML(true);

        // $mail->Subject = "Informacije o nalogu";
        // $mail->Body = "<i>Username je: $username i Lozinka je: $password</i>";
        // $mail->AltBody = "This is the plain text version of the email content";
        // header("Location:uspesno_kreiran_nalog.php");
        // try {
        //     $mail->send();
        //     echo "Message has been sent successfully";
        // } catch (Exception $e) {
        //     echo "Mailer Error: " . $mail->ErrorInfo;
        // }


        header("Location:uspesno_kreiran_nalog.php");
    } catch (Exception $e) {
        $greska = $e->getMessage();
    }
} elseif (isset($_POST['trener']) && isset($_POST['imeTrener']) && isset($_POST['prezimeTrener']) && isset($_POST['prezimeTrener']) && isset($_POST['jmbgTrener'])) {

    $ime = $_POST['imeTrener'];
    $prezime = $_POST['prezimeTrener'];
    $pol = $_POST['polTrener'];
    $mesto_rodjenja = $_POST['mesto_rodjenjaTrener'];
    $drzava_rodjenja = $_POST['drzava_rodjenjaTrener'];
    $jmbg = $_POST['jmbgTrener'];
    $br_telefona = $_POST['br_telefonaTrener'];
    $el_posta = $_POST['el_postaTrener'];
    $godina_rada = $_POST['godina_radaTrener'];
    $broj_klubova = $_POST['br_klubovaTrener'];

    $ime1 = strtolower($ime);
    $prezime1 = strtolower(substr($prezime, 0, 2));
    $nrRand = rand(0, 100);
    $username =  $ime1 . $prezime1 . $nrRand;
    $password = password_generate(7);
    $img = $_FILES['imageTrener']['name'];
    //move_uploaded_file($_FILES['image']['tmp_name'],"ProfilneSlike/$username");
    if (isset($_FILES['imageTrener']['name'])) {
        $saveto = "ProfilneSlike/$username.jpg";
        move_uploaded_file($_FILES['imageTrener']['tmp_name'], "$saveto");
        $typeok = TRUE;

        switch ($_FILES['imageTrener']['type']) {
            case "image/jpeg":
                $src = imagecreatefromjpeg($saveto);
                break;
            case "image/png":
                $src = imagecreatefrompng($saveto);
                break;
            default:
                $typeok = FALSE;
                break;
        }

        if ($typeok) {
            list($w, $h) = getimagesize($saveto);

            $max = 100;
            $tw = $w;
            $th = $h;

            if ($w > $h && $max < $w) {
                $th = $max / $w * $h;
                $tw = $max;
            } elseif ($h > $w && $max < $h) {
                $tw = $max / $h * $w;
                $th = $max;
            } elseif ($max < $h) {
                $tw = $th = $max;
            }
            $tmp = imagecreatetruecolor($tw, $th);
            imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
            imageconvolution($tmp, array(
                array(-1, -1, -1),
                array(-1, 16, -1), array(-1, -1, -1)
            ), 8, 0);
            imagejpeg($tmp, $saveto);
            imagedestroy($tmp);
            imagedestroy($src);
        }
    }

    try {
        require_once 'db.php';

        if (strlen($jmbg) > 13 || strlen($jmbg) < 13)
            throw new Exception("Neispravan jmbg!!");

        izvrsi_upit("INSERT INTO user VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?);", "sssssssssssss", $username, $ime, $prezime, $password, $pol, $mesto_rodjenja, $drzava_rodjenja, $jmbg, $br_telefona, $el_posta, "trener", NULL, "$username.jpg");
        izvrsi_upit("INSERT INTO trener VALUES(?,?,?);", "ssi", $username, $godina_rada, $broj_klubova);




        header("Location:uspesno_kreiran_nalog.php");
    } catch (Exception $e) {
        $greska = $e->getMessage();
    }
}

?>



<script>
    function myFunction() {
        var tip = document.getElementById("sel2").value
        var x = document.getElementById("sportista")
        var y = document.getElementById("tip")
        var trener = document.getElementById("trener");
        if (tip == "sportista")
            x.hidden = false
        else
            x.hidden = true

        if (tip == "trener")
            trener.hidden = false;

        else
            trener.hidden = true;






    }
</script>
<script>
    function checkJMBG(jmbg) {
        var broj = document.getElementById("jmbg123").value
        var trener = document.getElementById("jmbgTrener").value
        console.log(broj)
        var x = document.getElementById("jmbg1")
        if (broj.length < 13 || broj.length > 13) {

            x.style.color = "red"
            x.innerHTML = "Neispravan jmbg"
        } else {
            x.style.color = "green"
            x.innerHTML = "ispravan jmbg"
        }
        var x = document.getElementById("jmbgTrener1")
        if (trener.length < 13 || trener.length > 13) {

            x.style.color = "red"
            x.innerHTML = "Neispravan jmbg"
        } else {
            x.style.color = "green"
            x.innerHTML = "ispravan jmbg"
        }




    }
</script>

<div class="text-center col-xs-12 col-md-6 col-lg-4 " id="tip" style=" margin:auto;">
    <label>Izaberite tip</label>
    <select class="form-control" id="sel2" name="tip" onchange="myFunction()" required>
        <option value="" disabled selected>Izaberite</option>
        <option value="sportista">sportista</option>
        <option value="trener">trener</option>

    </select>
</div>
<div id="sportista" hidden>
    <form action="registracija.php" method="post" class="mx-5" enctype="multipart/form-data">
        <h1>Popunite registraciju za sportistu</h1>
        <div class="form-row">
            <div class="form-group col-md-6 mt-5">
                <label>Ime*</label>
                <input type="text" class="form-control" name="ime" placeholder="Ime" required>
            </div>
            <div class="form-group col-md-6 mt-5">
                <label>Prezime*</label>
                <input type="text" class="form-control" name="prezime" placeholder="Prezime" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Izaberite pol</label>
                <select class="form-control" id="sel1" name="pol" required>
                    <option value="" disabled selected>Izaberite</option>
                    <option value="muski">muski</option>
                    <option value="zenski">zenski</option>

                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Mesto rodjenja</label>
                <input type="text" class="form-control" name="mesto_rodjenja" placeholder="Mesto rodjenja" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Drzava rodjenja</label>
                <input type="text" class="form-control" name="drzava_rodjenja" placeholder="Drzava rodjenja" required>
            </div>
            <div class="form-group col-md-6">
                <label id="jmbg1">JMBG</label>
                <input type="text" class="form-control" name="jmbg" id="jmbg123" placeholder="JMBG" onblur="checkJMBG(this)" required>

            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Broj telefona</label>
                <input type="text" class="form-control" name="br_telefona" placeholder="Broj telefona" required>
            </div>
            <div class="form-group col-md-3">
                <label>El-posta</label>
                <input type="text" class="form-control" name="el_posta" placeholder="El-posta">
            </div>
            <div class="form-group col-md-3">
                <label>Slika</label>
                <input type="file" class="form-control" size="14" name="image" placeholder="Slika" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Datum rodjenja</label>
                <input type="date" class="form-control" name="datum_rodjenja" placeholder="Broj telefona" required>
            </div>
            <div class="form-group col-md-3">
                <label>Pozicija</label>
                <select class="form-control" id="sel3" name="pozicija" required>
                    <option value="" disabled selected>Izaberite</option>
                    <option value="golman">golman</option>
                    <option value="stoper">stoper</option>
                    <option value="centar">centar</option>
                    <option value="napad">napad</option>

                </select>
            </div>
            <div class="form-group col-md-3">
                <label>Sutiranje</label>
                <select class="form-control" id="sel4" name="sutiranje" required>
                    <option value="" disabled selected>Izaberite</option>
                    <option value="desna">desna</option>
                    <option value="lijeva">lijeva</option>
                    <option value="obe">obe</option>


                </select>
            </div>
        </div>
        <small>Polja koja su oznacena sa * su obavezna</small>
        <?php
        if ($greska != "")
            echo    '<div class="text-center">
                        <p class="text-center text-danger">' . $greska . '</p>
                    </div>';
        ?>
        <br><br>
        <div class="text-center">
            <button type="submit" class="btn btn-primary col-4" name="sportista">Registruj se</button>
        </div>
    </form>
</div>
<div id="trener" hidden>
    <form action="registracija.php" method="post" class="mx-5" enctype="multipart/form-data">
        <h1>Popunite registraciju za trenera</h1>
        <div class="form-row">
            <div class="form-group col-md-6 mt-5">
                <label>Ime*</label>
                <input type="text" class="form-control" name="imeTrener" placeholder="Ime" required>
            </div>
            <div class="form-group col-md-6 mt-5">
                <label>Prezime*</label>
                <input type="text" class="form-control" name="prezimeTrener" placeholder="Prezime" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Izaberite pol</label>
                <select class="form-control" id="selPol" name="polTrener" required>
                    <option value="" disabled selected>Izaberite</option>
                    <option value="muski">muski</option>
                    <option value="zenski">zenski</option>

                </select>
            </div>
            <div class="form-group col-md-6">
                <label>Mesto rodjenja</label>
                <input type="text" class="form-control" name="mesto_rodjenjaTrener" placeholder="Mesto rodjenja" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Drzava rodjenja</label>
                <input type="text" class="form-control" name="drzava_rodjenjaTrener" placeholder="Drzava rodjenja" required>
            </div>
            <div class="form-group col-md-6">
                <label id="jmbgTrener1">JMBG</label>
                <input type="text" class="form-control" name="jmbgTrener" id="jmbgTrener" placeholder="JMBG" onblur="checkJMBG(this)" required>

            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Broj telefona</label>
                <input type="text" class="form-control" name="br_telefonaTrener" placeholder="Broj telefona" required>
            </div>
            <div class="form-group col-md-3">
                <label>El-posta</label>
                <input type="text" class="form-control" name="el_postaTrener" placeholder="El-posta">
            </div>
            <div class="form-group col-md-3">
                <label>Slika</label>
                <input type="file" class="form-control" size="14" name="imageTrener" placeholder="Slika" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label>Godina pocetka rada</label>
                <input type="date" class="form-control" name="godina_radaTrener" placeholder="Broj telefona" required>
            </div>

            <div class="form-group col-md-6">
                <label>Broj klubova koje ste dosad trenirali</label>
                <input type="text" class="form-control" name="br_klubovaTrener" placeholder="Broj klubova" required>
            </div>

        </div>
        <small>Polja koja su oznacena sa * su obavezna</small>
        <?php
        if ($greska != "")
            echo    '<div class="text-center">
                        <p class="text-center text-danger">' . $greska . '</p>
                    </div>';
        ?>
        <br><br>
        <div class="text-center">
            <button type="submit" class="btn btn-primary col-4" name="trener">Registruj se</button>
        </div>
    </form>
</div>
<?php require_once 'footer.php' ?>