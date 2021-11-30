<?php
require_once("conexion.php");
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <script type="text/javascript">
            setTimeout('document.location.reload()',5000)//especificamos los milisegundos en que la página se recarga
        </script>
        <title></title>

        <script type="text/javascript" src="../resources/jquery.js"></script>
        <style type="text/css">
        ${demo.css}
        </style>
        <script type="text/javascript">
    $(function () {
        $('#container').highcharts({
            title: {
                text: 'Reporte de ritmo cardiaco',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
                categories: [
                <?php
                    $sql = " select reading_time from SensorData order by id desc limit 10 ";
                    $result = mysqli_query($connection, $sql);
                    while($registros = mysqli_fetch_array($result)){
                        ?>
                            '<?php echo  $registros["reading_time"]?>',
                        <?php
                    }
                ?>
                ]
            },
            yAxis: {
                title: {
                    text: 'Valores de la ESP'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: ' ESP'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [
            {   name: 'Ritmo Cardiaco',
                data: [
                <?php
                    $query = " select ritmo_cardiaco from SensorData order by id desc limit 10 ";
                    $resultados = mysqli_query($connection, $query);
                    while($rows = mysqli_fetch_array($resultados)){
                        ?>
                            <?php echo $rows["ritmo_cardiaco"]?>,
                        <?php
                    }
                ?>
                ]
            }
            ]
        });
});
        </script>
    </head>
    <body>
<script src="../resources/highcharts.js"></script>
<script src="../resources/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

    </body>
</html>