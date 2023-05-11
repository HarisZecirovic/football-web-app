<?php require_once 'header.php'; 

$greska = "";   ?>
<?php
    if(isset($_POST['stara_lozinka']) && isset($_POST['lozinka1']) && isset($_POST['lozinka2'])){
        $stara_lozinka = $_POST['stara_lozinka'];
        $lozinka1 = $_POST['lozinka1'];
        $lozinka2 = $_POST['lozinka2'];

        if(isset($_SESSION['korisnik'])){
            $korisnik = $_SESSION['korisnik'];

        }
        require_once 'db.php';
        $result = izvrsi_upit("SELECT * FROM user where username = '$korisnik'");
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $password = $row['password'];

        if($stara_lozinka == $password && $lozinka1 == $lozinka2 ){
            izvrsi_upit("UPDATE USER set password = ? where username = ?; ", "ss", $lozinka1, $korisnik);
            echo" <script> alert('Uspesno ste promenili lozinku') </script>";
        }
        else{
            $greska = "NISU TACNI PODACI";
        }

    }


?>

<div class="text-center col-xs-12 col-md-6 col-lg-4 mt-5" style=" margin:auto;">
    <h1>Promenite lozinku</h1><br>
    <form action="promeni_lozinku.php" method="POST">
        <div class="form-group text-left">
        
            <label>Stara lozinka</label>
            <input type="password" class="form-control" name="stara_lozinka" autofocus>
        </div>
        <div class="form-group text-left">
            <label>Nova Lozinka</label>
            <input type="password" class="form-control" name="lozinka1">
        </div>
        <div class="form-group text-left">
            <label>Potvrdite lozinku</label>
            <input type="password" class="form-control" name="lozinka2">
        </div>
        
        
        
        <?php
        if ($greska != "")
            echo    '<div class="text-center">
                        <p class="text-center text-danger">' . $greska . '</p>
                    </div>';
        ?>
        
        
        <button type="submit" class="btn btn-primary btn-lg">Potrvrdi</button>
    </form>
    
</div>





<?php require_once 'footer.php'; ?>