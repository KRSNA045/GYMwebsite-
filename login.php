<?php
// login.php
include 'config.php';

if(isset($_POST['login'])){
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    
    if(empty($name) || empty($phone) || empty($password)){
        echo "<script>alert('Please fill all fields.');</script>";
    } else {
        // Check if user exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE name = ? AND phone = ?");
        $stmt->bind_param("ss", $name, $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            $user = $result->fetch_assoc();
            // Verify password
            if(password_verify($password, $user['password'])){
                echo "<script>alert('Login successful!');</script>";
                // Redirect to a dashboard or home page, e.g. dashboard.php
                // header("Location: dashboard.php");
            } else {
                echo "<script>alert('Login failed: Incorrect password.');</script>";
            }
        } else {
            echo "<script>alert('Login failed: User not found.');</script>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <style>
        body {
            background-image: url('background.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
        }
        .container {
            width: 300px;
            padding: 20px;
            background: rgba(255,255,255,0.8);
            margin: 100px auto;
            border-radius: 10px;
        }
        input[type=text], input[type=password] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 15px 0;
            border: none;
            background: #f1f1f1;
        }
        input[type=submit] {
            width: 100%;
            padding: 10px;
            background: #4CAF50;
            border: none;
            color: white;
            cursor: pointer;
        }
        input[type=submit]:hover {
            background: #45a049;
        }
        .link {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Login</h2>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="password" name="password" placeholder="Password" required>
        <input type="submit" name="login" value="Login">
    </form>
    <div class="link">
        <a href="signup.php">Signup</a> | 
        <a href="forgot_password.php">Forgot Password?</a>
    </div>
</div>
</body>
</html>
