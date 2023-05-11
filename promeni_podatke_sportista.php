<?php require_once 'header.php'; ?>

<?php
$greska = '';
if (isset($_POST['sportista']) && isset($_POST['ime']) && isset($_POST['prezime']) && isset($_POST['jmbg'])) {

    $ime = $_POST['ime'];
    $prezime = $_POST['prezime'];
    $pol = $_POST['pol'];
    $mesto_rodjenja = $_POST['mesto_rodjenja'];
    $drzava_rodjenja = $_POST['drzava_rodjenja'];
    $jmbg = $_POST['jmbg'];
    $br_telefona = $_POST['br_telefona'];
    $el_posta = $_POST['el_posta'];
    if ($_SESSION['tip_korisnika'] == 'sportista') {
        $datum_rodjenja = $_POST['datum_rodjenja'];
        $pozicija = $_POST['pozicija'];
        $sutiranje = $_POST['sutiranje'];
        $img = $_FILES['image']['name'];

        //move_uploaded_file($_FILES['image']['tmp_name'],"ProfilneSlike/$username");
        if (isset($_FILES['image']['name'])) {
            $saveto = "ProfilneSlike/$img";
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

            if ($_FILES['image']['size'] > 0) {

                izvrsi_upit("UPDATE user set ime = ?, prezime = ?, pol = ?, mesto_rodjenja = ?, drzava_rodjenja = ?, JMBG = ?, telefon = ?, el_posta = ?, slika = ? where username = ?", "ssssssssss", $ime, $prezime, $pol, $mesto_rodjenja, $drzava_rodjenja, $jmbg, $br_telefona, $el_posta, $img, $korisnik);
                izvrsi_upit("UPDATE sportista set datum = ?, pozicija= ?, sutiranje = ? where username = ?", "ssss", $datum_rodjenja, $pozicija, $sutiranje, $korisnik);
            } else {

                izvrsi_upit("UPDATE user set ime = ?, prezime = ?, pol = ?, mesto_rodjenja = ?, drzava_rodjenja = ?, JMBG = ?, telefon = ?, el_posta = ? where username = ?", "sssssssss", $ime, $prezime, $pol, $mesto_rodjenja, $drzava_rodjenja, $jmbg, $br_telefona, $el_posta, $korisnik);
                izvrsi_upit("UPDATE sportista set datum = ?, pozicija= ?, sutiranje = ? where username = ?", "ssss", $datum_rodjenja, $pozicija, $sutiranje, $korisnik);
            }


            echo " <script> alert('Uspesno ste promenili podatke') </script>";
        } catch (Exception $e) {
            $greska = $e->getMessage();
        }
    }
    elseif($_SESSION['tip_korisnika'] == 'trener'){
        $godina_rada = $_POST['godina_radaTrener'];
        $broj_klubova = $_POST['br_klubovaTrener'];
        $img = $_FILES['image']['name'];

        //move_uploaded_file($_FILES['image']['tmp_name'],"ProfilneSlike/$username");
        if (isset($_FILES['image']['name'])) {
            $saveto = "ProfilneSlike/$img";
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

            if ($_FILES['image']['size'] > 0) {

                izvrsi_upit("UPDATE user set ime = ?, prezime = ?, pol = ?, mesto_rodjenja = ?, drzava_rodjenja = ?, JMBG = ?, telefon = ?, el_posta = ?, slika = ? where username = ?", "ssssssssss", $ime, $prezime, $pol, $mesto_rodjenja, $drzava_rodjenja, $jmbg, $br_telefona, $el_posta, $img, $korisnik);
                izvrsi_upit("UPDATE trener set godina_rada = ?, broj_klubova = ? where username = ?", "sss", $godina_rada,$broj_klubova,$korisnik);
            } else {

                izvrsi_upit("UPDATE user set ime = ?, prezime = ?, pol = ?, mesto_rodjenja = ?, drzava_rodjenja = ?, JMBG = ?, telefon = ?, el_posta = ? where username = ?", "sssssssss", $ime, $prezime, $pol, $mesto_rodjenja, $drzava_rodjenja, $jmbg, $br_telefona, $el_posta, $korisnik);
                izvrsi_upit("UPDATE trener set godina_rada = ?, broj_klubova = ? where username = ?", "sss", $godina_rada,$broj_klubova,$korisnik);
            }


            echo " <script> alert('Uspesno ste promenili podatke') </script>";
        } catch (Exception $e) {
            $greska = $e->getMessage();
        }
        

    }
}



?>
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
<?php

if (isset($_SESSION['korisnik']) && $_SESSION['tip_korisnika'] == 'sportista' || $_SESSION['tip_korisnika'] == 'trener') {

    $korisnik = $_SESSION['korisnik'];

    require_once 'db.php';
    if ($_SESSION['tip_korisnika'] == 'sportista') {
        $result = izvrsi_upit("SELECT * FROM user join sportista on user.username = sportista.username where user.username = ?", "s", $korisnik);

        $row = $result->num_rows;

        $row = $result->fetch_array(MYSQLI_ASSOC);

        $username = $row['username'];
        $ime = $row['ime'];
        $prezime = $row['prezime'];
        $lozinka = $row['password'];
        $slika = $row['slika'];
        $pol = $row['pol'];
        $mesto_rodjenja = $row['mesto_rodjenja'];
        $drzava_rodjenja = $row['drzava_rodjenja'];
        $jmbg = $row['JMBG'];
        $telefon = $row['telefon'];
        $el_posta = $row['el_posta'];
        $grad = $row['grad'];
        $datum_rodjenja = $row['datum'];
        $pozicija = $row['pozicija'];
        $sutiranje = $row['sutiranje'];
    } elseif ($_SESSION['tip_korisnika'] == 'trener') {
        $result = izvrsi_upit("SELECT * FROM user join trener on user.username = trener.username where user.username = ?", "s", $korisnik);

        $row = $result->num_rows;

        $row = $result->fetch_array(MYSQLI_ASSOC);

        $username = $row['username'];
        $ime = $row['ime'];
        $prezime = $row['prezime'];
        $lozinka = $row['password'];
        $slika = $row['slika'];
        $pol = $row['pol'];
        $mesto_rodjenja = $row['mesto_rodjenja'];
        $drzava_rodjenja = $row['drzava_rodjenja'];
        $jmbg = $row['JMBG'];
        $telefon = $row['telefon'];
        $el_posta = $row['el_posta'];
        $grad = $row['grad'];
        $godina_rada = $row['godina_rada'];
        $broj_klubova = $row['broj_klubova'];
    }


    echo "
    
    <form action='promeni_podatke_sportista.php' method='post' class='mx-5' enctype='multipart/form-data'>
        <h1>Vasi Podaci</h1>
        <img src='ProfilneSlike/$slika' alt='Italian Trulli'>
        <div class='form-row'>
            <div class='form-group col-md-6 mt-5'>
                <label>Ime*</label>
                <input type='text' class='form-control' name='ime' value = '$ime'required>
            </div>
            <div class='form-group col-md-6 mt-5'>
                <label>Prezime*</label>
                <input type='text' class='form-control' name='prezime' value = '$prezime' required>
            </div>
        </div>
        <div class='form-row'>
            <div class='form-group col-md-6'>
                <label>Izaberite pol</label>
                <select class='form-control' id='sel1' name='pol' required>
                    <option value='$pol' selected>$pol</option>
                    <option value='muski'>muski</option>
                    <option value='zenski'>zenski</option>

                </select>
            </div>
            <div class='form-group col-md-6'>
                <label>Mesto rodjenja</label>
                <input type='text' class='form-control' name='mesto_rodjenja' value = '$mesto_rodjenja' required>
            </div>
        </div>
        <div class='form-row'>
            <div class='form-group col-md-6'>
                <label>Drzava rodjenja</label>
                <input type='text' class='form-control' name='drzava_rodjenja' value = '$drzava_rodjenja' required>
            </div>
            <div class='form-group col-md-6'>
                <label id='jmbg1'>JMBG</label>
                <input type='text' class='form-control' name='jmbg' id='jmbg123' value = '$jmbg' onblur='checkJMBG(this)' required>

            </div>
        </div>
        <div class='form-row'>
            <div class='form-group col-md-6'>
                <label>Broj telefona</label>
                <input type='text' class='form-control' name='br_telefona' value = '$telefon' required>
            </div>
            <div class='form-group col-md-3'>
                <label>El-posta</label>
                <input type='text' class='form-control' name='el_posta' value = '$el_posta'>
            </div>
            <div class='form-group col-md-3'>
                <label>Slika</label>
                <input type='file' class='form-control' size='14' name='image' value = '$slika'>
                
            </div>
        </div>";
    if ($_SESSION['tip_korisnika'] == 'sportista') {
        echo "
        <div class='form-row'>
        
            <div class='form-group col-md-6'>
                <label>Datum rodjenja</label>
                <input type='date' class='form-control' name='datum_rodjenja' value = '$datum_rodjenja' required>
            </div>
            <div class='form-group col-md-3'>
                <label>Pozicija</label>
                <select class='form-control' id='sel3' name='pozicija' required>
                    <option value='$pozicija' selected>$pozicija</option>
                    <option value='golman'>golman</option>
                    <option value='stoper'>stoper</option>
                    <option value='centar'>centar</option>
                    <option value='napad'>napad</option>

                </select>
            </div>
            <div class='form-group col-md-3'>
                <label>Sutiranje</label>
                <select class='form-control' id='sel4' name='sutiranje' required>
                    <option value='$sutiranje'  selected>$sutiranje</option>
                    <option value='desna'>desna</option>
                    <option value='lijeva'>lijeva</option>
                    <option value='obe'>obe</option>


                </select>
            </div>
        </div>";
    } elseif ($_SESSION['tip_korisnika'] == 'trener') {
        echo "
        <div class='form-row'>
            <div class='form-group col-md-6'>
                <label>Godina pocetka rada</label>
                <input type='date' class='form-control' name='godina_radaTrener' value = '$godina_rada' required>
            </div>

            <div class='form-group col-md-6'>
                <label>Broj klubova koje ste dosad trenirali</label>
                <input type='text' class='form-control' name='br_klubovaTrener' value = '$broj_klubova' required>
            </div>
            </div>";
    }
    echo "
        <small>Polja koja su oznacena sa * su obavezna</small>";


    if ($greska != '')
        echo    "<div class='text-center'>
                        <p class='text-center text-danger'>' . $greska . '</p>
                    </div>";
?>
<?php
    echo "
        <br><br>
        <div class='text-center'>
    <a class='btn btn-primary col-4' href='promeni_lozinku.php'>Promeni lozinku</a>
    </div>
    <br>
    <div class='text-center'>
        <button type='submit' class='btn btn-primary col-4' name = 'sportista'>Potvrdi</button>
    </div>
    </form>";
}



?>
<?php require_once 'footer.php' ?>