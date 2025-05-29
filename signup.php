<?php
// signup.php
include 'config.php';

if(isset($_POST['signup'])){
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate fields
    if(empty($name) || empty($phone) || empty($password) || empty($confirm_password)){
        echo "<script>alert('Please fill all fields.');</script>";
    } elseif($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        // Hash the password for security
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Insert the new user
        $stmt = $conn->prepare("INSERT INTO users (name, phone, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $phone, $hashed_password);
        if($stmt->execute()){
            echo "<script>alert('Signup successful! You can now login.');window.location.href='login.php';</script>";
        } else {
            echo "<script>alert('Signup failed. Please try again.');</script>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <style>
        body {
            background-image: url('background.jpg'); /* Place your background image in the same folder */
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
    <h2>Signup</h2>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="password" name="password" placeholder="Create Password" required>
        <input type="password" name="confirm_password" placeholder="Re-enter Password" required>
        <input type="submit" name="signup" value="Signup">
    </form>
    <div class="link">
        <a href="login.php">Already have an account? Login</a>
    </div>
</div>
</body>
</html>
