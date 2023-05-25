<?php
//-------------------------------------------------------------------------------------------
require 'config.php';

$db;
$sql = "SELECT * FROM tbl_temperature ORDER BY id DESC LIMIT 100";
$result = $db->query($sql);
if (!$result) { {
        echo "Error: " . $sql . "<br>" . $db->error;
    }
}

//$rows = $result->fetch_assoc();
//$rows = $result -> fetch_all(MYSQLI_ASSOC);

//$row = get_temperature();
//print_r($row);

//header('Content-Type: application/json');
//echo json_encode($rows);
//-------------------------------------------------------------------------------------------
?>

<!DOCTYPE html>
<html>

<head>
    <title>History</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        * {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            background-color: #222;
            color: #fff;
        }

        body {
            background-color: #222;
            color: #fff;
        }

        .container {
            width: 100%;
            min-height: 100vh;
            padding-left: 0;
            padding-right: 0;
            box-sizing: border-box;
            overflow: hidden;
        }

        .navbar {
            width: 100%;
            display: flex;
            align-items: center;
            background: linear-gradient(#060c21, #222);
        }

        .logo {
            width: 70px;
            cursor: pointer;
            margin: 30px 0;
            background-color: transparent;
            margin-left: 8%;
        }

        .menu-icon {
            display: none;
            width: 25px;
            cursor: pointer;
            background-color: transparent;
            margin-right: 8%;
        }

        nav {
            flex: 1;
            text-align: right;
        }


        nav ul {
            background: transparent;
        }

        nav ul li {
            list-style: none;
            display: inline-block;
            margin-right: 50px;
            background-color: transparent;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
            font-size: 14px;
            background-color: transparent;
        }


        nav ul li a:hover {
            color: #55aaff;
        }

        .table-container {
            overflow: auto;
        }

        /* CSS styles for the table */
        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 8px;
            text-align: center;
            /* Center-align the values */
            border: 1px solid #55aaff;
        }

        .table th {
            background-color: #222;
        }

        /* Optional: Add styles for alternate row colors */
        .table tbody tr:nth-child(even) {
            background-color: #222;
        }

        @media only screen and (max-width: 760px) {
            nav ul {
                width: 100%;
                background: linear-gradient(#44bb88, #222);
                position: absolute;
                top: 120px;
                right: 0;
                z-index: 2;
                overflow: hidden;
                transition: max-height 0.5s ease-out;
                max-height: 0;
            }

            nav ul>li {
                display: block;
                margin-top: 10px;
                margin-bottom: 10px;
                background-color: transparent;
            }

            nav ul>li>a {
                display: block;
                background-color: transparent;
            }

            .menu-icon {
                display: block;
            }
        }
    </style>

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
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Temperature</th>
                            <th scope="col">Humidity</th>
                            <th scope="col">Gas</th>
                            <th scope="col">Date time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?PHP $i = 1;
                        while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <th scope="row">
                                    <?php echo $i++; ?>
                                </th>
                                <td>
                                    <?PHP echo $row['temperature']; ?>
                                </td>
                                <td>
                                    <?PHP echo $row['humidity']; ?>
                                </td>
                                <td>
                                    <?PHP echo $row['gas']; ?>
                                </td>
                                <td>
                                    <?PHP echo date("Y-m-d h:i: A", strtotime($row['created_date'])); ?>
                                </td>
                            </tr>
                        <?PHP } ?>
                    </tbody>
                </table>
            </div>
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
</body>

</html>