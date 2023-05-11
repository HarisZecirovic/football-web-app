<?php

session_start(); ?>
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href = "style.css"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.1/css/bootstrap.min.css" integrity="sha384-VCmXjywReHh4PwowAiWNagnWcLhlEJLA5buUprzK8rxFgeH0kww/aWY76TfkUoSX" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    
    <title>Fudbal</title>
    
  </head>
  <body>
  <div class = "haris">
  <nav class="navbar navbar-expand-lg navbar-light bg-light navbar-fixed-top">
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#startupNavbar" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
    <a class="navbar-brand" href="#"> <img src="logo.png" alt="startup.logo" height = "40"></a>
  <div class="collapse navbar-collapse" id="startupNavbar">
    
    <ul class="nav navbar-nav ml-auto">
      <li class="nav-item active">
        <a class="nav-link" href="indeks.php">Pocetna stranica <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="kontakt.php">Kontakt</a>
      </li>
      
      
      <?php 
       
       

        // if (isset($_SESSION["tip_korisnika"]) && $_SESSION["tip_korisnika"] == "admin") {
          
        //   echo '<li class="nav-item">
        //           <a class="nav-link" href="dodaj_donatora.php">Admin</a>
        //         </li>';
        // }
        // if(isset($_SESSION["korisnik"])){
        //   $k = $_SESSION["korisnik"];
        //   echo <<<_END
          
        //    <li class = 'nav-item'> <a class = 'btn btn-primary' href = 'odjavi_se.php' onclick = 'alert('Odjavili ste se')'> Odjavi se </a>
          
        //   _END;
        // }
        // else{
        //   echo  '<li class="nav-item"><a class="btn btn-primary" href="login.php">Prijavi se</a></li>';
      
        // }
        if(isset($_SESSION["korisnik"])){
            $korisnik = $_SESSION["korisnik"];
        }

         if(isset($_SESSION["korisnik"])){
           
        
         if(isset($_SESSION['tip_korisnika']) && $_SESSION['tip_korisnika'] == 'admin'){
           echo <<<_LOGGEDIN
           <li class="nav-item dropdown ml-auto">
           <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">Admin</a>
           <div class="dropdown-menu dropdown-menu-right">
               <a href="organizuj_takmicenje.php" class="dropdown-item">Organizuj takmicenje</a>
               <a href="prijava_za_takmicenje.php" class="dropdown-item">Prijave za takmicenje</a>
               <a href="pregled_zahteva.php" class="dropdown-item">Pogledaj zahteve</a>
               <a href="pregled_svih_korisnika.php" class="dropdown-item">Pregled korisnika</a>
               <a href="pregled_korisnika.php" class="dropdown-item">Pregled trenera</a>
               <a href="registracija.php" class="dropdown-item">Registruj novog korisnika</a>
               <div class="dropdown-divider"></div>
               <a href="odjavi_se.php"class="dropdown-item">Odjavi se</a>
           </div>
       </li>
       _LOGGEDIN;
         }
         else if(isset($_SESSION['tip_korisnika']) && $_SESSION['tip_korisnika'] == 'sportista'){
           $k = $_SESSION['korisnik'];
          echo <<<_LOGGEDIN
          <li class="nav-item dropdown ml-auto">
          <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">$k</a>
          <div class="dropdown-menu dropdown-menu-right">
              <a href="promeni_podatke_sportista.php" class="dropdown-item">Promeni podatke</a>
              <a href="pregled_takmicenja_trener.php" class="dropdown-item">Pregled takmicenja</a>
              <a href="posalji_zahtev_sportista.php" class="dropdown-item">Posalji zahtev</a>
             
              <div class="dropdown-divider"></div>
              <a href="odjavi_se.php"class="dropdown-item">Odjavi se</a>
          </div>
      </li>
      _LOGGEDIN;
         }
         else if(isset($_SESSION['tip_korisnika']) && $_SESSION['tip_korisnika'] == 'trener'){
          $k = $_SESSION['korisnik'];
         echo <<<_LOGGEDIN
         <li class="nav-item dropdown ml-auto">
         <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown">$k</a>
         <div class="dropdown-menu dropdown-menu-right">
             <a href="promeni_podatke_sportista.php" class="dropdown-item">Promeni podatke</a>
             <a href="pregled_zahteva_za_tim.php" class="dropdown-item">Zahtevi za tim</a>
             <a href="pregled_takmicenja_trener.php" class="dropdown-item">Pregled takmicenja</a>
             <a href="prijava_za_takmicenje.php" class="dropdown-item">Prijava za takmicenje</a>
             <a href="pregled_clanova_tima.php" class="dropdown-item">Pregled clanova</a>
             <a href="dodaj_vest.php" class="dropdown-item">Dodaj vest</a>
             <a href="dodaj_tim_trener.php" class="dropdown-item">Dodaj tim</a>
             <div class="dropdown-divider"></div>
             <a href="odjavi_se.php"class="dropdown-item">Odjavi se</a>
         </div>
     </li>
     _LOGGEDIN;
        }
        }
       
       else{
         echo  '<li class="nav-item"><a class="btn btn-primary " href="login.php">Prijavi se</a></li>';

       }
       
      
      ?>
    </ul>
    
  </div>
</nav>

<div style="flex:1;">
 

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    
  
  
  