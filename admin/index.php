<?php

require_once __DIR__ . '/../config/app.php';

requireAdmin();

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Admin Dashboard</title>

<style>
body {
    font-family: Arial, sans-serif;
    margin: 40px;
}

.topbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

a {
    color: #800020;
}
</style>

</head>

<body>

<div class="topbar">

    <h1>Admin Dashboard</h1>

    <a href="logout.php">Logout</a>

</div>

<p>
    Welcome,
    <strong>
        <?= htmlspecialchars($_SESSION['admin_name']) ?>
    </strong>
</p>

<hr>

<h2>Product Management</h2>

<p>Coming next.</p>

</body>
</html>