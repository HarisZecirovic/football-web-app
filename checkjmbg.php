<?php

if (isset($_POST['jmbg'])) {
    $jmbg = $_POST['jmbg'];
    $broj = strlen($jmbg);
    if ($broj < 13) {
        echo "<span class='taken'>&nbsp;&#x2718; " .
            "Nije ispravan jmbg</span>";
    }
}
