<?php require_once "header.php" ?>
<html>

<head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        google.charts.load("current", {
            packages: ["corechart"]
        });
        google.charts.setOnLoadCallback(drawChart);

        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Ime tima', 'Posed lopte'],
                <?php
                if (isset($_GET['id_utakmice'])) {
                    $id_utakmice = $_GET['id_utakmice'];
                }
                require_once "db.php";
                $result = izvrsi_upit("SELECT * from utakmica where id_utakmice = ?", "i", $id_utakmice);
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $posed_tim1 = $row['posed_tim1'];
                $posed_tim2 = $row['posed_tim2'];
                echo "['" . $row['ime_tima1'] . "', $posed_tim1],
                  ['" . $row['ime_tima2'] . "', $posed_tim2]";


                ?>

            ]);

            var options = {
                title: 'Posed lopte',
                is3D: true,
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
            chart.draw(data, options);
        }
    </script>
    <script type="text/javascript">
        google.charts.load('current', {
            'packages': ['bar']
        });
        google.charts.setOnLoadCallback(drawStuff);

        function drawStuff() {
            var data = new google.visualization.arrayToDataTable([

                ['Move', 'Percentage'],
                <?php
                if (isset($_GET['id_utakmice'])) {
                    $id_utakmice = $_GET['id_utakmice'];
                }
                require_once "db.php";
                $result = izvrsi_upit("SELECT * from utakmica where id_utakmice = ?", "i", $id_utakmice);
                $row = $result->fetch_array(MYSQLI_ASSOC);
                $ime_tima1 = $row['ime_tima1'];
                $ime_tima2 = $row['ime_tima2'];
                $tim1_golovi = $row['tim1_golovi'];
                $tim2_golovi = $row['tim2_golovi'];
                echo "['$ime_tima1', '$tim1_golovi'],
                ['$ime_tima2', '$tim2_golovi']
                ";

                ?>
                // ["King's pawn (e4)", 44],
                // ["Queen's pawn (d4)", 31],
                // ["Knight to King 3 (Nf3)", 12],
                // ["Queen's bishop pawn (c4)", 10],
                // ['Other', 3]
            ]);

            var options = {
                width: 800,
                legend: {
                    position: 'none'
                },
                chart: {
                    title: 'Rezultat utakmice',
                    subtitle: 'Rezultat po procentima'
                },
                axes: {
                    x: {
                        0: {
                            side: 'top',
                            label: ''
                        } // Top x-axis.
                    }
                },
                bar: {
                    groupWidth: "90%"
                }
            };

            var chart = new google.charts.Bar(document.getElementById('top_x_div'));
            // Convert the Classic options to Material options.
            chart.draw(data, google.charts.Bar.convertOptions(options));
        };
    </script>
</head>

<body>
    <div id="piechart_3d" style="width: 900px; height: 500px;"></div>
    <div id="top_x_div" style="width: 800px; height: 600px;"></div>
</body>

</html>
<?php require_once "footer.php" ?>