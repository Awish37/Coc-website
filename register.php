<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "clash_of_clans";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if user already exists
    $check_user = "SELECT * FROM users WHERE username='$username' OR email='$email'";
    $result = $conn->query($check_user);

    if ($result->num_rows > 0) {
        $_SESSION['error'] = "Username or email already exists. Please choose a different one.";
    } else {
        $sql = "INSERT INTO users (username, password, email) VALUES ('$username', '$password', '$email')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['success'] = "Registration successful. <a href='login.php'>Click here</a> to login.";
        } else {
            $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
    header("Location: register.php");
    exit();
}

$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
$success = isset($_SESSION['success']) ? $_SESSION['success'] : '';

// Clear session messages
unset($_SESSION['error']);
unset($_SESSION['success']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Clash of Clans - Register</h1>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li class="dropdown">
                    <a class="dropbtn">More</a>
                    <div class="dropdown-content">
                        <a href="event.html">Events</a>
                        <a href="troops.html">Troops</a>
                    </div>
                </li>
                <li><a href="#contact">Contact</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <div class="form-container">
            <form action="register.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>

                <button type="submit">Register</button>
                <div class="message">
                    <?php 
                        if ($error) {
                            echo "<p class='error'>$error</p>";
                        }
                        if ($success) {
                            echo "<p class='success'>$success</p>";
                        }
                    ?>
                </div>
            </form>
            <p>Already registered? <a href="login.php">Click here</a> to login</p>
        </div>
    </main>
    <footer>
        <section id="contact">
            <p>Contact us at <a href="mailto:contact@clashofclans.com">contact@clashofclans.com</a></p>
        </section>
    </footer>
</body>
</html>
