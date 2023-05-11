<?php
require_once 'header.php';
    $greska =false;
    if(isset($_POST['korisnickoIme'])){
        require_once 'db.php';
        $user = $_POST['korisnickoIme'];
        $pass = $_POST['lozinka'];

        
    
            $result = izvrsi_upit("SELECT * FROM user WHERE username = ? AND password = ?" , "ss", $user,$pass);

            if($result->num_rows > 0)
            {
               $row = $result->fetch_assoc();
               if(!isset($_SESSION))
                session_start();
            
                $_SESSION['korisnik'] = $user;
                $_SESSION['tip_korisnika'] = $row['tip'];
                
                header("Location:indeks.php");
            }
            else
            $greska = true;
        
    }

?>

<div class="text-center col-xs-12 col-md-6 col-lg-4 mt-5" style=" margin:auto;">
    <h1>Prijava</h1><br>
    <form action="login.php" method="POST">
        <div class="form-group text-left">
        
            <label>Korisnicko ime</label>
            <input type="text" class="form-control" name="korisnickoIme" autofocus>
        </div>
        <div class="form-group text-left">
            <label>Lozinka</label>
            <input type="password" class="form-control" name="lozinka">
        </div>
        <?php
            echo "<p class='text-danger'>$greska</p>";
        ?>
        <?php
        if ($greska)
            echo "<p class='text-danger'>Neispravno korisnicko ime ili lozinka!</p>"
        
        ?>
        
        
        <button type="submit" class="btn btn-primary">Prijavi se</button>
    </form>
    <br><a href="slanje_lozinke.php">Zaboravili ste lozinku?</a><br>
    <br>Nemate nalog? <a href="registracija.php">Registrujte se</a><br>
</div>


<?php require_once 'footer.php' ?>


