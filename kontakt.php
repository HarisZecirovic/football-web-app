<?php require_once 'header.php' ?>

<?php

require_once 'db.php';

$result = izvrsi_upit("SELECT * FROM takmicenje");
$rows = $result->num_rows;


echo  "
<script src='tabela.js'> </script>
<input class='form-control mb-4 mt-5' id='tableSearch' type='text' placeholder='Pretrazite'>
 <table class='table mr-5 mt-5'>
<thead>
  <tr>
    <th scope='col'>#</th>
    <th scope='col'>Naziv takmicenja</th>
    <th scope='col'>Kategorija</th>
    <th scope='col'>Pol takmicenja</th>
    <th scope='col'>Datum prve utakmice</th>
    
    <th scope='col'>Status</th>
  </tr>
</thead>
<tbody id='myTable'>

";
for ($i = 0; $i < $rows; ++$i) {

  $row = $result->fetch_array(MYSQLI_ASSOC);
  $naziv_takmicenja = $row['naziv_takmicenja'];
  $kategorija_takmicenja = $row['kategorija_takmicenja'];
  $pol_takmicenja = $row['pol_takmicenja'];
  $datum_utakmice = $row['datum_utakmice'];
  $id_takmicenja = $row['id_takmicenja'];
  $status = $row['status'];


  echo " <tr>
    
    
    <th scope='row'>$i</th>
    <td>$naziv_takmicenja</td>
    <td>$kategorija_takmicenja</td>
    
    <td>$pol_takmicenja</td>
    <td>$datum_utakmice</td>
    
    <td>$status</td>
    <td><a class = 'btn btn-outline-info' href ='pregled_utakmica.php?id_takmicenja=$id_takmicenja'>Pregled</a>   </td>
    </tr>
    ";
}
echo "

</tbody>
        </table>";


?>
<?php require_once 'footer.php' ?>