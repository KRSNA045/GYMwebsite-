<?php
// forgot_password.php
include 'config.php';

if(isset($_POST['reset'])){
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if(empty($name) || empty($phone) || empty($new_password) || empty($confirm_password)){
        echo "<script>alert('Please fill all fields.');</script>";
    } elseif($new_password !== $confirm_password) {
        echo "<script>alert('Passwords do not match.');</script>";
    } else {
        // Verify that the user exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE name = ? AND phone = ?");
        $stmt->bind_param("ss", $name, $phone);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows == 1){
            // Update the password
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE name = ? AND phone = ?");
            $update_stmt->bind_param("sss", $hashed_password, $name, $phone);
            if($update_stmt->execute()){
                echo "<script>alert('Password updated successfully!');window.location.href='login.php';</script>";
            } else {
                echo "<script>alert('Error updating password.');</script>";
            }
            $update_stmt->close();
        } else {
            echo "<script>alert('User not found.');</script>";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Reset Password</title>
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
    <h2>Reset Password</h2>
    <form method="POST" action="">
        <input type="text" name="name" placeholder="Name" required>
        <input type="text" name="phone" placeholder="Phone Number" required>
        <input type="password" name="new_password" placeholder="New Password" required>
        <input type="password" name="confirm_password" placeholder="Re-enter New Password" required>
        <input type="submit" name="reset" value="Reset Password">
    </form>
    <div class="link">
        <a href="login.php">Back to Login</a>
    </div>
</div>
</body>
</html>
