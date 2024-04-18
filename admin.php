<?php
session_start();

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate login credentials (you might have your own validation logic)
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if the username and password are correct (example logic)
    if ($username === "admin" && $password === "admin123") {
        // Set admin session variable upon successful login
        $_SESSION['admin_logged_in'] = true;

        // Redirect to the dashboard or any other authorized page
        header("Location: dashboard.php");
        exit(); // Stop further execution
    } else {
        // Redirect back to the login page with an error message
        header("Location: admin.php?error=incorrect_credentials");
        exit(); // Stop further execution
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
        /* Basic CSS for styling */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        form {
            width: 50%;
            margin: 0 auto;
        }
        input[type="text"], input[type="password"], textarea, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4caf50;
            color: white;
            padding: 14px 20px;
            margin: 8px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Login</h1>
        <?php
            // Display error message if any
            if (isset($_GET['error'])) {
                echo '<p style="color: red; text-align: center;">';
                if ($_GET['error'] === 'incorrect_credentials') {
                    echo 'Incorrect username or password.';
                } elseif ($_GET['error'] === 'missing_credentials') {
                    echo 'Please provide username and password.';
                }
                echo '</p>';
            }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
    </div>
</body>
</html>
