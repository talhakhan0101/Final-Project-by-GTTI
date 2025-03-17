<?php
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $email = $_POST['email'];
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $terms_accepted = isset($_POST['terms']) ? 1 : 0;

    // Check if username or email already exists
    $check_user = $conn->prepare("SELECT id FROM users WHERE username=? OR email=?");
    $check_user->bind_param("ss", $username, $email);
    $check_user->execute();
    $check_user->store_result();

    if ($check_user->num_rows > 0) {
        echo "Username or Email already exists.";
    } else {
        // Insert user data
        $stmt = $conn->prepare("INSERT INTO users (username, password_hash, email, first_name, last_name, terms_accepted) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $username, $password, $email, $first_name, $last_name, $terms_accepted);

        if ($stmt->execute()) {
            echo "Signup successful!";
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    
    $check_user->close();
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
    <link rel='stylesheet' href='style.css'>
</head>
<body>
    <form method="POST">
        <label>Username:</label>
        <input type="text" name="username" required><br>

        <label>Password:</label>
        <input type="password" name="password" required><br>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required><br>

        <label>Email:</label>
        <input type="email" name="email" required><br>

        <label>First Name:</label>
        <input type="text" name="first_name" required><br>

        <label>Last Name:</label>
        <input type="text" name="last_name" required><br>

        <input type="checkbox" name="terms" required> I accept the Terms of Service<br>

        <button type="submit">Signup</button>
    </form>
</body>
</html>