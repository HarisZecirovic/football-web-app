<?php require_once "header.php"; ?>
<?php
if (isset($_POST['posalji_zahtev']) &&  isset($_POST['ime_tima'])) {
  $ime_tima = $_POST['ime_tima'];
  $usernameSportiste = $_POST['usernameSportiste'];
  $kategorija = $_POST['kategorija'];
  $pol = $_POST['pol'];
  require_once 'db.php';
  $brojac = 0;
  #selektujem sve iz clanova gde je usernamesportiste jednak sportisti koji je trenutno prijavljen i salje zahtev
  $result = izvrsi_upit("SELECT * from clanovi where usernameSportiste = ?", "s", $usernameSportiste);
  #provera da li je korisnik vec poslao zahtev za neki tim, u slucaju da pokusa ponovo da posalje zahtev za tim kome je vec poslao zahtev
  if ($result->num_rows > 0) {
    for ($i = 0; $i < $result->num_rows; ++$i) {
      $row = $result->fetch_array(MYSQLI_ASSOC);
      #ime tima za koji je sportista poslao zahtev i ime tima za koji pokusava da posalje zahtev, ako brojac bude veci od 0 onda je sportista vec slao zahtev za taj tim i treba
      #da ceka odgovor od trenera toga tima
      if ($row['ime_tima'] == $ime_tima)
        ++$brojac;
    }
  }
  #selektujem sve iz clanovi gde je sportista clan i dozvola = odobreno, a on moze biti clan jednog tima
  $result = izvrsi_upit("SELECT * from clanovi where usernameSportiste  = ? and dozvola = 'odobreno'", "s", $usernameSportiste);
  $row = $result->fetch_array(MYSQLI_ASSOC);
  $tim = $row['ime_tima'];
  #ako je broj == 1 onda je on vec clan tima
  if ($result->num_rows == 1) {
    echo " <script> alert('Vec ste clan tima $tim') </script>";
  } elseif ($brojac != 0) { #provera da li korisnik ponova salje zahtev za tim za koji je vec slao zahtev
    echo " <script> alert('Vec ste poslali zahtev za ovaj tim') </script>";
  } else {
    #u suprotnom uzimamo podatke toga sportiste iz baze
    $result = izvrsi_upit("SELECT * from sportista where username = ?", "s", $usernameSportiste);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $godine_sportiste = $row['datum'];
    $age = floor((time() - strtotime($godine_sportiste)) / 31556926);
    $result = izvrsi_upit("SELECT * from user where username = ?", "s", $usernameSportiste);
    $row = $result->fetch_array(MYSQLI_ASSOC);
    $pol_sportiste = $row['pol'];
    #ako ispunjava neki od dole navedenih uslova sportista moze da posalje zahtev za tim
    if ($kategorija == 'decija' && $pol == $pol_sportiste && $age <= 15 && $age >= 10) {
      izvrsi_upit("INSERT INTO clanovi VALUES (?,?,?,?,?,?)", "ssssss", $ime_tima, $usernameSportiste, $kategorija, $godine_sportiste, $pol_sportiste, NULL);
      echo " <script> alert('Uspesno ste poslali zahtev $kategorija') </script>";
    } elseif ($kategorija == 'srednja' && $pol == $pol_sportiste && $age >= 15 && $age <= 20) {
      izvrsi_upit("INSERT INTO clanovi VALUES (?,?,?,?,?,?)", "ssssss", $ime_tima, $usernameSportiste, $kategorija, $godine_sportiste, $pol_sportiste, NULL);
      echo " <script> alert('Uspesno ste poslali zahtev $kategorija') </script>";
    } elseif ($kategorija == 'odrasli' && $pol == $pol_sportiste && $age >= 20) {
      izvrsi_upit("INSERT INTO clanovi VALUES (?,?,?,?,?,?)", "ssssss", $ime_tima, $usernameSportiste, $kategorija, $godine_sportiste, $pol_sportiste, NULL);
      echo " <script> alert('Uspesno ste poslali zahtev $kategorija') </script>";
    } else {
      echo " <script> alert('Ne mozete se prijaviti za ovaj tim') </script>";
    }
  }
} elseif (isset($_POST['napusti_tim'])) {
  $ime_tima = $_POST['ime_tima'];
  $usernameSportiste = $_POST['usernameSportiste'];
  $kategorija = $_POST['kategorija'];
  require_once "db.php";
  izvrsi_upit("DELETE from clanovi where usernameSportiste = ? and dozvola = 'odobreno'", "s", $korisnik);
  echo " <script> alert('Uspesno ste napustili tim $ime_tima') </script>";
}

?>
<script src="tabela.js"> </script>
<div class="container">
  <input class="form-control mb-4 mt-5" id="tableSearch" type="text" placeholder="Pretrazite">

  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th>Ime tima</th>
        <th>Kategorija</th>
        <th>Pol</th>

      </tr>
    </thead>
    <tbody id="myTable">
      <?php
      require_once "db.php";

      $result = izvrsi_upit("SELECT * from tim");

      $rows = $result->num_rows;

      for ($i = 0; $i < $rows; ++$i) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
        $ime_tima = $row['ime_tima'];
        $kategorija = $row['kategorija'];
        $pol = $row['pol'];
        echo " <form action = 'posalji_zahtev_sportista.php' method = 'post'>";
        echo <<<_LOGGEDIN
                <tr>
                <td>$ime_tima</td>
                <td>$kategorija</td>
                <td>$pol</td>
                
               _LOGGEDIN;
        #provera da li je vec clan tima, ako jeste ima button da napusti tim
        $rezultat = izvrsi_upit("SELECT * from clanovi where usernameSportiste = ? and dozvola = 'odobreno'", "s", $korisnik);
        $red = $rezultat->fetch_array(MYSQLI_ASSOC);
        $tim = $red['ime_tima'];
        #ako je ime tima koje je u ponudi za slanje zahteva jednako imenu tima u kojem je sportista clan, onda ima mogucnost da izadje iz toga tima
        if ($ime_tima == $tim) {
          echo "
                <td>  
                <input type = 'hidden' name = 'ime_tima' value = '$ime_tima'>
                <input type = 'hidden' name = 'usernameSportiste' value = '$korisnik'>
                <input type = 'hidden' name = 'kategorija' value = '$kategorija'>
                <button type='submit' name ='napusti_tim'  class='btn btn-danger'>Napusti tim</button> </form> </td>
                <td><a class = 'btn btn-outline-info' href ='pregled_clanova_tima.php?ime_tima=$ime_tima'> pregledaj clanove</a>   </td>
              </tr>
                ";
        } else {

          echo "
                <td>  
                <input type = 'hidden' name = 'ime_tima' value = '$ime_tima'>
                <input type = 'hidden' name = 'usernameSportiste' value = '$korisnik'>
                <input type = 'hidden' name = 'kategorija' value = '$kategorija'>
                <input type = 'hidden' name = 'pol' value = '$pol'>
                <button type='submit' name = 'posalji_zahtev' class='btn btn-success'>Posalji zahtev</button> </form> </td>
                <td><a class = 'btn btn-outline-info' href ='pregled_clanova_tima.php?ime_tima=$ime_tima'> pregledaj clanove</a>   </td>
              </tr>";
        }
      }

      ?>

    </tbody>
  </table>
</div>
<?php require_once 'footer.php' ?>