<?php
include_once 'dbconnect.php';

$name = $email = $phone_number = $address = '';
$error_message = ''; // Define $error_message initially to avoid undefined variable warning

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $phone_number = $_POST['phone_number'];
    $address = $_POST['address'];

    // Check if password length is at least 6 characters
    if (strlen($password) < 6) {
        $error_message = "Password must be at least 6 characters.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Initialize database connection
        $db = new dbconnect();
        $conn = $db->connect();

        if ($conn) {
            // Prepare the SQL statement
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, phone_number, address) VALUES (?, ?, ?, ?, ?)");
            if ($stmt === false) {
                die("Prepare statement failed: " . $conn->error);
            }

            // Bind parameters
            $stmt->bind_param("sssss", $name, $email, $hashed_password, $phone_number, $address);

            // Execute the statement
            if ($stmt->execute()) {
                // Redirect to login page with a success message
                header("Location: login.php?status=registered");
                exit();
            } else {
                echo "Error during execution: " . $stmt->error;
            }

            // Close the statement and connection
            $stmt->close();
            $db->close();
        } else {
            echo "Database connection failed.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <header>
        <h1>Assignment 2</h1>
    </header>
    <h2>Patient Registration</h2>
    <form id="registerForm" action="register.php" method="POST" autocomplete="off">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required><br>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required><br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br>
        <label for="phone_number">Phone Number:</label>
        <input type="text" id="phone_number" name="phone_number" value="<?php echo htmlspecialchars($phone_number); ?>" required><br>
        <label for="address">Address:</label>
        <textarea id="address" name="address" required><?php echo htmlspecialchars($address); ?></textarea><br>
        <button type="submit">Register</button><br>
        <span>Already have an account? <a href="login.php">Log in</a></span>
    </form>
    <?php
    if (!empty($error_message)) { // Check if $error_message is not empty before displaying
        echo '<p style="color:red;">' . $error_message . '</p>';
    }
    ?>
    <footer class="footer">
        <p>&copy; <?php echo date('Y'); ?> My Clinic. All rights reserved.</p>
    </footer>
    <script src="script.js"></script>
</body>
</html>
