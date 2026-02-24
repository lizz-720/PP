<?php
    session_start();
    $host = 'db';
    $user = 'root';
    $password = 'root_password';
    $db = 'studyguide_db';

    $conn = new mysqli($host, $user, $password, $db);

    if ($conn->connect_error) 
        {
            die("Connection failed: " . $conn->connect_error);
        }

    // HANDLE THE LOGIN SUBMISSION
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $conn->real_escape_string($_POST['Username']);
        $pass = $_POST['password'];

        // Check if user exists in the 'users' table created by setup.sql
        $result = $conn->query("SELECT * FROM users WHERE username = '$username' AND password = '$pass'");

        if ($result && $result->num_rows > 0) 
            {
                $user_data = $result->fetch_assoc();
                $_SESSION['user_id'] = $user_data['id']; // Store their ID
                $_SESSION['name'] = $user_data['name'];
                header("Location: index.php"); // Send them to the generator
                exit();
        } else {
                $error = "Invalid Username or Password!";
        }
    }
?>

<!DOCTYPE html>
<html lang = "en">
    <head>
        Study Buddies Login
    </head>
    <body>
        <h1>Login</h1>
            <form method = "POST" style = "margin: 20px 0;">
                <input type = "text" name = "Username" placeholder = "Username" required><br>
                <input type = "text" name = "password" placeholder = "Password" required><br>
                    <form action = "index.php">
                        <button type = "Login"> Login </button><br>
                    </form>

            </form>

    <hr>

        <p>Dont have an accoutn?</p>
            <form action = "http://127.0.0.1:8080/registration.php">
                    <button type = "New User"> New User</button>
            </form>
        </body>
</html>