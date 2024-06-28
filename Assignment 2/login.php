<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <h1>Assignment 2</h1>
    </header>
    <h2>Login</h2>
    <?php
    session_start();
    if (isset($_SESSION['message'])) {
        echo '<p style="color:red;">' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']);
    }
    ?>
    <form id="loginForm" action="login.php" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo isset($_COOKIE['email']) ? $_COOKIE['email'] : ''; ?>" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" value="<?php echo isset($_COOKIE['password']) ? $_COOKIE['password'] : ''; ?>" required><br>
        <label>
            <input type="checkbox" name="remember" <?php echo isset($_COOKIE['email']) ? 'checked' : ''; ?>> Remember Me
        </label><br>
        <button type="submit">Login</button>
        <p>Don't have an account? <a href="register.php">Register here</a></p>
    </form>
    <footer class="footer">
        <p>&copy; <?php echo date('Y'); ?> My Clinic. All rights reserved.</p>
    </footer>

    <div id="message"></div>
    <script src="script.js"></script>
</body>
</html>

<?php

include_once 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    $db = new dbconnect();
    $conn = $db->connect();

    if ($conn) {
        $stmt = $conn->prepare("SELECT password FROM users WHERE email = ?");
        if ($stmt === false) {
            die("Prepare statement failed: " . $conn->error);
        }

        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($hashed_password);
        $stmt->fetch();
        $stmt->close();

        if ($hashed_password && password_verify($password, $hashed_password)) {
            $_SESSION['loggedin'] = true;
            $_SESSION['email'] = $email;
            $_SESSION['message'] = "Login successful.";
            
            // Set cookies if "Remember Me" is checked
            if ($remember) {
                setcookie('email', $email, time() + (86400 * 30), "/"); // 30 days
                setcookie('password', $password, time() + (86400 * 30), "/"); // 30 days
            } else {
                // Clear cookies if "Remember Me" is not checked
                setcookie('email', '', time() - 3600, "/");
                setcookie('password', '', time() - 3600, "/");
            }

            header("Location: dashboard.php");
        } else {
            $_SESSION['message'] = "Incorrect email or password.";
            header("Location: login.php");
        }

        $db->close();
    } else {
        $_SESSION['message'] = "Database connection failed.";
        header("Location: login.php");
    }
    exit();
}
?>
