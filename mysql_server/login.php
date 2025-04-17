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

    if (!isset($data['user'], $data['password'])) {
        echo json_encode(["status" => "error", "message" => "Incomplete input."]);
        exit();
    }

    $userInput = $conn->real_escape_string($data['user']); // email or username
    $passwordInput = $data['password'];

    $query = "SELECT * FROM users WHERE email='$userInput' OR username='$userInput'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($passwordInput, $user['password'])) {
            echo json_encode(["status" => "success"]);
        } else {
            echo json_encode(["status" => "wrong_password"]);
        }
    } else {
        echo json_encode(["status" => "not_found"]);
    }
} else {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Invalid request method."]);
}

$conn->close();
?>
