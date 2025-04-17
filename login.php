<?php
$host = "sql210.infinityfree.com";
$dbname = "if0_38762363_car_rental";
$username = "if0_38762363";
$password = "LnlOUgAqhz";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Connection failed."]));
} else {
    echo "✅ Database connected successfully!";
}

$data = json_decode(file_get_contents("php://input"));

$user = $data->username;
$pass = $data->password;

$stmt = $conn->prepare("SELECT fullname, password FROM users WHERE username = ?");
$stmt->bind_param("s", $user);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    if (password_verify($pass, $row['password'])) {
        echo json_encode(["status" => "success", "fullname" => $row['fullname']]);
    } else {
        echo json_encode(["status" => "error", "message" => "Incorrect password."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Username not found."]);
}

$conn->close();
?>
