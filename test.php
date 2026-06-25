<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laptop Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }

        main {
            flex: 1;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 800px;
        }

        h2 {
            color: #007bff;
            text-align: center;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #007bff;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .logout-form {
            text-align: right;
            margin-bottom: 20px;
        }

        .logout-btn {
            background-color: #ff0000;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .logout-btn:hover {
            background-color: #cc0000;
        }

        .action-button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .action-button:hover {
            background-color: #0056b3;
        }

        footer {
            background-color: #007bff;
            color: white;
            text-align: center;
            padding: 10px 0;
            margin-top: auto;
        }
    </style>
</head>
<body>

<header>
    <h1>Laptop Management System</h1>
</header>

<main>
    <div class="container">
        <h2>Select Your Laptops</h2>

        <form action="process_selection.php" method="post">
            <table>
                <tr>
                    <th>Computer Name</th>
                    <th>Status</th>
                    <th>Screaming</th>
                    <th>Data Wipe</th>
                    <th>Lock</th>
                </tr>

                <?php foreach ($laptops as $laptop): ?>
                <tr>
                    <td>
                        <a href="test1.php?pc_name=<?php echo htmlspecialchars($laptop['pc_name']); ?>"
                           target="popup"
                           onclick="window.open('test1.php?pc_name=<?php echo htmlspecialchars($laptop['pc_name']); ?>','popup',' width=600,height=600'); return false;">
                            <?php echo htmlspecialchars($laptop['pc_name']); ?>
                        </a>
                    </td>
                    <td>
                        <?php echo $laptop['locate'] == 1 ? 'Status Pending' : 'Not yet tried'; ?>
                    </td>
                    <td>Screaming</td>
						<?php echo $laptop['scream'] == 1 ? 'Status Pending' : 'Not yet tried'; ?>
                    <td>Data Wipe</td>
                    <td>Lock</td>
                </tr>
                <?php endforeach; ?>
            </table>

            <div style="margin-top: 20px;">
                <button class="action-button" type="submit">Submit Selections</button>
            </div>
        </form>

        <form action="logout.php" method="post" class="logout-form">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>
</main>

<footer>
    <p>&copy; 2024 Laptop Management System</p>
</footer>

</body>
</html>
