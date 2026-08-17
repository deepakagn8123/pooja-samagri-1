<?php

require_once __DIR__ . '/../config/app.php';

if (isAdminLoggedIn()) {
    header('Location: index.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /*
    |--------------------------------------------------------------------------
    | CSRF Protection
    |--------------------------------------------------------------------------
    */

    verify_csrf();


    /*
    |--------------------------------------------------------------------------
    | Get Input
    |--------------------------------------------------------------------------
    */

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';


    /*
    |--------------------------------------------------------------------------
    | Generic Error Message
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    | Never tell the user whether the email exists.
    |
    */

    $error = 'Invalid email or password.';


    /*
    |--------------------------------------------------------------------------
    | Basic Validation
    |--------------------------------------------------------------------------
    */

    if ($email === '' || $password === '') {

        $error = 'Invalid email or password.';

    }


    /*
    |--------------------------------------------------------------------------
    | Validate Email Format
    |--------------------------------------------------------------------------
    */

    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = 'Invalid email or password.';

    }


    /*
    |--------------------------------------------------------------------------
    | Brute-force Protection
    |--------------------------------------------------------------------------
    */

    elseif (
        isLoginRateLimited(
            $pdo,
            $email,
            $ipAddress
        )
    ) {

        $error = 'Too many login attempts. Please try again later.';

    }


    /*
    |--------------------------------------------------------------------------
    | Authenticate
    |--------------------------------------------------------------------------
    */

    else {

        $stmt = $pdo->prepare("
            SELECT
                id,
                name,
                email,
                password
            FROM admins
            WHERE email = :email
            LIMIT 1
        ");

        $stmt->execute([
            'email' => $email
        ]);

        $admin = $stmt->fetch();


        /*
        |--------------------------------------------------------------------------
        | Successful Authentication
        |--------------------------------------------------------------------------
        */

        if (
            $admin &&
            password_verify(
                $password,
                $admin['password']
            )
        ) {

            /*
            |--------------------------------------------------------------------------
            | Record Successful Login
            |--------------------------------------------------------------------------
            */

            recordLoginAttempt(
                $pdo,
                $email,
                $ipAddress,
                true
            );


            /*
            |--------------------------------------------------------------------------
            | Prevent Session Fixation
            |--------------------------------------------------------------------------
            */

            session_regenerate_id(true);


            /*
            |--------------------------------------------------------------------------
            | Initialize Secure Admin Session
            |--------------------------------------------------------------------------
            */

            initializeAdminSession(
                (int)$admin['id'],
                $admin['name']
            );


            /*
            |--------------------------------------------------------------------------
            | Rotate CSRF Token
            |--------------------------------------------------------------------------
            */

            rotate_csrf_token();


            /*
            |--------------------------------------------------------------------------
            | Redirect
            |--------------------------------------------------------------------------
            */

            header('Location: index.php');

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | Failed Authentication
        |--------------------------------------------------------------------------
        */

        recordLoginAttempt(
            $pdo,
            $email,
            $ipAddress,
            false
        );

        $error = 'Invalid email or password.';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login</title>

<style>
body {
    font-family: Arial, sans-serif;
    background: #f5f5f5;
    margin: 0;
}

.login-box {
    width: 350px;
    margin: 120px auto;
    padding: 30px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,.08);
}

input {
    width: 100%;
    box-sizing: border-box;
    padding: 12px;
    margin-bottom: 15px;
}

button {
    width: 100%;
    padding: 12px;
    cursor: pointer;
}

.error {
    color: #b00020;
    margin-bottom: 15px;
}
</style>
</head>

<body>

<div class="login-box">

    <h2>Admin Login</h2>

    <?php if ($error): ?>
        <div class="error">
            <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <form method="POST">

     <?= csrf_field() ?>

        <input
            type="email"
            name="email"
            placeholder="Email"
            required
        >

        <input
            type="password"
            name="password"
            placeholder="Password"
            required
        >

        <button type="submit">
            Login
        </button>

    </form>

</div>

</body>
</html>