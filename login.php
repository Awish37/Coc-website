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
    $password = $_POST['password'];

    // Check if user exists
    $check_user = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($check_user);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['user'] = $username;
            header("Location: dashboard.php"); // Redirect to the dashboard page
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password.";
        }
    } else {
        $_SESSION['error'] = "Invalid username or password.";
    }

    $conn->close();
    header("Location: login.php");
    exit();
}

$error = isset($_SESSION['error']) ? $_SESSION['error'] : '';
unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Clash of Clans - Login</h1>
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
            <form action="login.php" method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Login</button>
                <div class="message">
                    <?php 
                        if ($error) {
                            echo "<p class='error'>$error</p>";
                        }
                    ?>
                </div>
            </form>
            <p>Still not registered? <a href="register.php">Click here</a> to register</p>
        </div>
    </main>
    <footer>
        <section id="contact">
            <p>Contact us at <a href="mailto:contact@clashofclans.com">contact@clashofclans.com</a></p>
        </section>
    </footer>
</body>
</html>
