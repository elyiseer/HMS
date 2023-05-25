<?php
//-------------------------------------------------------------------------------------------
require 'config.php';

$db;
$sql = "SELECT * FROM tbl_temperature ORDER BY id DESC LIMIT 30";
$result = $db->query($sql);
if (!$result) { {
        echo "Error: " . $sql . "<br>" . $db->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Monitor System</title>
    <link rel="icon" type="image/png" href="img/black-logo.png">
    <link id="styleLink" rel="stylesheet" href="style-3.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://www.gstatic.com/charts/loader.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container">
        <div class="navbar">
            <img src="img/black-logo.png" class="logo">
            <nav>
                <ul id="menuList">
                    <li><a href="/hms">Home</a></li>
                    <li><a href="/hms/History.php">History</a></li>
                    <li><a href="">About</a></li>
                </ul>
            </nav>
            <img src="img/menu-b.png" class="menu-icon" onclick="togglemenu()">
            <!--<button onclick="toggleTheme()">Toggle Theme</button>-->
        </div>

        <div class="row">
            <!--<div class="col-1">
                <h2>Home Monitor<br>System</h2>
                <h3>Make Our Home Safe With IOT</h3>
                <p>Computer Network and Security</p>
            </div>-->
            <div class="col-2">
                <div class="sensor-box">
                    <div id="chart_temperature" class="chart"></div>
                </div>
                <div class="sensor-box">
                    <div id="chart_humidity" class="chart"></div>
                </div>
                <div class="sensor-box">
                    <div id="chart_gas" class="chart"></div>
                </div>
            </div>
        </div>
        <div id="chartContainer" class="chart-container">
            <canvas id="lineGraph"></canvas>
        </div>
        <div class="social-links">
            <a href="https://www.facebook.com/elyiseeeeer"><img src="img/fb.png"></a>
            <a href="https://twitter.com/hfkwvsskdv"><img src="img/tw.png"></a>
            <a href="https://www.instagram.com/elyiseeeeer/"><img src="img/ig.png"></a>
        </div>
    </div>
    <script>
        var menuList = document.getElementById("menuList");
        menuList.style.maxHeight = "0px";

        function togglemenu() {
            if (menuList.style.maxHeight == "0px") {
                menuList.style.maxHeight = "130px";
            } else {
                menuList.style.maxHeight = "0px";
            }
        }
    </script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        //$(document).ready(function(){
        //-------------------------------------------------------------------------------------------------
        google.charts.load('current', {
            'packages': ['gauge']
        });
        google.charts.setOnLoadCallback(drawTemperatureChart);
        //-------------------------------------------------------------------------------------------------
        function drawTemperatureChart() {
            //guage starting values
            var data = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                ['Temperature', 0],
            ]);
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            var options = {
                width: 180,
                height: 480,
                redFrom: 70,
                redTo: 100,
                yellowFrom: 40,
                yellowTo: 70,
                greenFrom: 00,
                greenTo: 40,
                minorTicks: 5,
                fontSize: 12,
            };
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            var chart = new google.visualization.Gauge(document.getElementById('chart_temperature'));
            chart.draw(data, options);
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN


            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            function refreshData() {
                $.ajax({
                    url: 'getdata.php',
                    // use value from select element
                    data: 'q=' + $("#users").val(),
                    dataType: 'json',
                    success: function(responseText) {
                        //______________________________________________________________
                        //console.log(responseText);
                        var var_temperature = parseFloat(responseText.temperature).toFixed(2)
                        //console.log(var_temperature);
                        // use response from php for data table
                        //______________________________________________________________
                        //guage starting values
                        var data = google.visualization.arrayToDataTable([
                            ['Label', 'Value'],
                            ['Temperature', eval(var_temperature)],
                        ]);
                        //______________________________________________________________
                        //var chart = new google.visualization.Gauge(document.getElementById('chart_temperature'));
                        chart.draw(data, options);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown + ': ' + textStatus);
                    }
                });
            }
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            //refreshData();

            setInterval(refreshData, 1000);
        }
        //-------------------------------------------------------------------------------------------------



        //-------------------------------------------------------------------------------------------------
        google.charts.load('current', {
            'packages': ['gauge']
        });
        google.charts.setOnLoadCallback(drawHumidityChart);
        //-------------------------------------------------------------------------------------------------
        function drawHumidityChart() {
            //guage starting values
            var data = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                ['Humidity', 0],
            ]);
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            var options = {
                width: 180,
                height: 480,
                redFrom: 00,
                redTo: 30,
                yellowFrom: 60,
                yellowTo: 100,
                greenFrom: 30,
                greenTo: 60,
                minorTicks: 5,
                fontSize: 12,
            };
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            var chart = new google.visualization.Gauge(document.getElementById('chart_humidity'));
            chart.draw(data, options);
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN



            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            function refreshData() {
                $.ajax({
                    url: 'getdata.php',
                    // use value from select element
                    data: 'q=' + $("#users").val(),
                    dataType: 'json',
                    success: function(responseText) {
                        //______________________________________________________________
                        //console.log(responseText);
                        var var_humidity = parseFloat(responseText.humidity).toFixed(2)
                        //console.log(var_humidity);
                        // use response from php for data table
                        //______________________________________________________________
                        //guage starting values
                        var data = google.visualization.arrayToDataTable([
                            ['Label', 'Value'],
                            ['Humidity', eval(var_humidity)],
                        ]);
                        //______________________________________________________________
                        //var chart = new google.visualization.Gauge(document.getElementById('chart_temperature'));
                        chart.draw(data, options);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown + ': ' + textStatus);
                    }
                });
            }
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            //refreshData();

            setInterval(refreshData, 1000);
        }
        //-------------------------------------------------------------------------------------------------


        //-------------------------------------------------------------------------------------------------
        google.charts.load('current', {
            'packages': ['gauge']
        });
        google.charts.setOnLoadCallback(drawGasChart);
        //-------------------------------------------------------------------------------------------------
        function drawGasChart() {
            //guage starting values
            var data = google.visualization.arrayToDataTable([
                ['Label', 'Value'],
                ['Gas', 0],
            ]);
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            var options = {
                width: 180,
                height: 480,
                redFrom: 70,
                redTo: 100,
                yellowFrom: 40,
                yellowTo: 70,
                greenFrom: 00,
                greenTo: 40,
                minorTicks: 5,
                fontSize: 12,
            };
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            var chart = new google.visualization.Gauge(document.getElementById('chart_gas'));
            chart.draw(data, options);
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN



            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            function refreshData() {
                $.ajax({
                    url: 'getdata.php',
                    // use value from select element
                    data: 'q=' + $("#users").val(),
                    dataType: 'json',
                    success: function(responseText) {
                        //______________________________________________________________
                        //console.log(responseText);
                        var var_gas = parseFloat(responseText.gas).toFixed(2)
                        //console.log(var_gas);
                        // use response from php for data table
                        //______________________________________________________________
                        //guage starting values
                        var data = google.visualization.arrayToDataTable([
                            ['Label', 'Value'],
                            ['Gas', eval(var_gas)],
                        ]);
                        //______________________________________________________________
                        //var chart = new google.visualization.Gauge(document.getElementById('chart_temperature'));
                        chart.draw(data, options);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.log(errorThrown + ': ' + textStatus);
                    }
                });
            }
            //NNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNNN
            //refreshData();

            setInterval(refreshData, 1000);
        }
        //});

        $(window).resize(function() {
            drawTemperatureChart();
            drawHumidityChart();
            drawGasChart();
        });
    </script>
    <script>
        const ctx = document.getElementById('lineGraph').getContext('2d');
        const chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                        label: 'Temperature',
                        borderColor: 'red',
                        fill: true,
                        data: []
                    },
                    {
                        label: 'Humidity',
                        borderColor: 'blue',
                        fill: true,
                        data: []
                    },
                    {
                        label: 'Gas',
                        borderColor: 'green',
                        fill: true,
                        data: []
                    }
                ]
            },
            options: {
                fontSize: 12,
                animation: {
                    duration: 1 // Disable animation for real-time updates
                },
                elements: {
                    line: {
                        tension: 0.4 // Adjust the tension for the curves (0.0 to 1.0)
                    }
                },
                scales: {
                    x: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Time'
                        }
                    },
                    y: {
                        display: true,
                        title: {
                            display: true,
                            text: 'Value'
                        }
                    }
                }
            }
        });

        function refreshData() {
            var container = document.getElementById('chartContainer');
            var isMobile = window.matchMedia('(max-width: 700px)').matches;
            var height = isMobile ? chart.data.labels.length * 40 : 500;
            container.style.height = height + 'px';

            var maxDataPoints = 10;

            if (window.matchMedia('(max-width: 700px)').matches) {
                maxDataPoints = 5; // Adjust the maximum data points for mobile devices
            }

            // Limit the number of data points to display
            if (chart.data.labels.length > maxDataPoints) {
                var numToRemove = chart.data.labels.length - maxDataPoints;

                for (var i = 0; i < numToRemove; i++) {
                    chart.data.labels.shift();
                    chart.data.datasets.forEach((dataset) => {
                        dataset.data.shift();
                    });
                }
            }

            $.ajax({
                url: 'getdata.php',
                data: 'q=' + $("#users").val(),
                dataType: 'json',
                success: function(responseText) {
                    var created_date = responseText.created_date;
                    var formattedDate = moment(created_date).format('h:mm A');
                    var temperature = parseFloat(responseText.temperature).toFixed(2);
                    var humidity = parseFloat(responseText.humidity).toFixed(2);
                    var gas = parseFloat(responseText.gas).toFixed(2);

                    // Add new data to the chart
                    chart.data.labels.push(formattedDate);
                    chart.data.datasets[0].data.push(temperature);
                    chart.data.datasets[1].data.push(humidity);
                    chart.data.datasets[2].data.push(gas);

                    // Limit the number of data points to display
                    const maxDataPoints = 10;
                    if (chart.data.labels.length > maxDataPoints) {
                        chart.data.labels.shift();
                        chart.data.datasets.forEach((dataset) => {
                            dataset.data.shift();
                        });
                    }

                    // Update the chart
                    chart.update();
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown + ': ' + textStatus);
                }
            });
        }

        // Call refreshData function periodically to update the chart
        setInterval(refreshData, 1000);
    </script>
    <script>
        function toggleTheme() {
            const styleLink = document.getElementById('styleLink');
            if (styleLink.href.includes('style-2.css')) {
                styleLink.href = 'style-3.css';
            } else {
                styleLink.href = 'style-2.css';
            }
        }
    </script>
</body>

</html>