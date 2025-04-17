<?php
header("Content-Type: application/json");

$host = "sql210.infinityfree.com";
$dbname = "if0_38762363_car_rental";
$username = "if0_38762363";
$password = "LnlOUgAqhz";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database connection failed."]);
    exit();
} else {
    echo "✅ Database connected successfully!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data['fullname'], $data['email'], $data['username'], $data['password'])) {
        echo json_encode(["status" => "error", "message" => "Incomplete input."]);
        exit();
    }

    $fullname = $conn->real_escape_string($data['fullname']);
    $email = $conn->real_escape_string($data['email']);
    $usernameInput = $conn->real_escape_string($data['username']);
    $passwordInput = $data['password'];

    $checkEmail = "SELECT * FROM users WHERE email = '$email'";
    $checkUsername = "SELECT * FROM users WHERE username = '$usernameInput'";
    $resultEmail = $conn->query($checkEmail);
    $resultUsername = $conn->query($checkUsername);

    if ($resultEmail->num_rows > 0) {
        echo json_encode(["status" => "email_exists"]);
    } elseif ($resultUsername->num_rows > 0) {
        echo json_encode(["status" => "username_exists"]);
    } else {
        $hashedPassword = password_hash($passwordInput, PASSWORD_DEFAULT);
        $insert = "INSERT INTO users (fullname, email, username, password) VALUES ('$fullname', '$email', '$usernameInput', '$hashedPassword')";
        if ($conn->query($insert)) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Failed to insert user."]);
        }
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
?>
