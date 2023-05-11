<?php require_once "header.php"; ?>

<?php
$hostname = "localhost";
$username = "root";
$password = "mysql";
$db_name = "sport";
$conn = new mysqli($hostname, $username, $password, $db_name);
if ($conn->connect_error)
    die("Connection failed");


if (isset($_POST['ime_tima'])) {
    $ime_tima = $_POST['ime_tima'];
    $naslov_vesti = $_POST['naslov_vesti'];
    $tekst_vesti = $_POST['tekst_vesti'];
    $privatnost = $_POST['privatnost'];
    date_default_timezone_set('Europe/Belgrade');
    $vreme = date('Y-m-d h:i:s', time());
    require_once "db.php";
    #uzimam iz tima podatke kategorija i pol
    $result = izvrsi_upit("SELECT * from tim where ime_tima = ? ", "s", $ime_tima);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $kategorija = $row['kategorija'];
    $pol = $row['pol'];

    #naslovna slika
    $img = $_FILES['image']['name'];
    //move_uploaded_file($_FILES['image']['tmp_name'],"ProfilneSlike/$username");
    if (isset($_FILES['image']['name'])) {
        $saveto = "slikeZaVesti/$img";
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

            // if ($w > $h && $max < $w) {
            //     $th = $max / $w * $h;
            //     $tw = $max;
            // } elseif ($h > $w && $max < $h) {
            //     $tw = $max / $h * $w;
            //     $th = $max;
            // } elseif ($max < $h) {
            //     $tw = $th = $max;
            // }
            $tw = 300;
            $th = 300;
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

    #u vesti tabelu pamtim ove podatke
    $query = "INSERT INTO vesti(id_vesti, naslov_vesti,tekst_vesti, ime_tima_vesti, kategorija_vesti, pol_vesti,slika_naslov, vreme_vesti,privatnost) values (NULL,'$naslov_vesti','$tekst_vesti','$ime_tima', '$kategorija', '$pol', '$img', '$vreme', '$privatnost')";
    $rezultat = $conn->query($query);
    
    if (!$rezultat)
        die("Fatal error");

    #uzimam zadnji id koji je insertovan zajedno sa ovim gore podacima u tabelu (id je auto increment )
    $id = mysqli_insert_id($conn);
    echo "Novi id je " . $id;

    #brojim koliko ima slika za vesti. Zatim pamtim sve te slika u novu tabelu slike_vesti koja je povezana sa tabelom vesti
    $fileCount = count($_FILES['file']['name']);
    for ($i = 0; $i < $fileCount; $i++) {
        $fileName = $_FILES['file']['name'][$i];
        izvrsi_upit("INSERT INTO slike_vesti VALUES(?,?);", "is", $id, $fileName);
        move_uploaded_file($_FILES['file']['tmp_name'][$i], 'slikeZaVesti/' . $fileName);
    }

    $conn->close();
}

?>
<?php
#vreme trenutno
date_default_timezone_set('Europe/Belgrade');
$vreme = date('m/d/Y h:i:s a', time());
echo $vreme;
?>


<form class="ml-5" action="dodaj_vest.php" method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="exampleFormControlSelect1">Izaberite tim</label>
        <select class="form-control" id="exampleFormControlSelect1" name="ime_tima">
            <option value='' disabled selected>Izaberite</option>
            <?php
            require_once "db.php";
            #uzimam iz baze timove koji vodi trenutno prijavljeni trener
            $result = izvrsi_upit("SELECT * from tim where usernameTrenera = ? ", "s", $korisnik);

            for ($i = 0; $i < $result->num_rows; ++$i) {
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $ime_tima = $row['ime_tima'];
                echo "
                <option value = '$ime_tima'>$ime_tima</option>
                    ";
            }

            ?>
        </select>
    </div>
    <div class="form-group">
        <label for="exampleFormControlSelect1">Privatnost</label>
        <select class="form-control" id="exampleFormControlSelect1" name="privatnost">
            <option value='' disabled selected>Izaberite</option>
            <option value="privatno">Privatno</option>
            <option value="javno">Javno</option>
        </select>
    </div>
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Naslov vesti</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="3" name="naslov_vesti"></textarea>
    </div>
    <div class="form-group">
        <label for="">Dodajte naslovnu sliku</label>
        <input type="file" size="14" name="image" placeholder="Slika">
    </div>
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Tekst vesti</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="10" name="tekst_vesti"></textarea>
    </div>
    <div class="form-group">
        <label for="">Dodajte slike za vesti</label>
        <input name="file[]" type="file" multiple="multiple" />
    </div>
    <div class="text-center">

        <button type="submit" class="btn btn-primary col-4" name="vest">Dodaj vest</button>
    </div>

</form>



































<?php require_once "footer.php"; ?>