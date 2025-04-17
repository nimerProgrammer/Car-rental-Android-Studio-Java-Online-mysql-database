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


// Get input data
$data = json_decode(file_get_contents("php://input"));
$fullname = $data->fullname;
$email = $data->email;
$username = $data->username;
$password = password_hash($data->password, PASSWORD_BCRYPT); // Encrypt password
$user_type = 'customer'; // User type (Admin, Customer, etc.)

// Step 1: Check if email exists
$checkEmail = $conn->prepare("SELECT id FROM users WHERE email=?");
$checkEmail->bind_param("s", $email);
$checkEmail->execute();
$result = $checkEmail->get_result();

if ($result->num_rows > 0) {
    // Email already exists
    echo json_encode(["status" => "error", "message" => "Email already exists."]);
} else {
    // Step 2: Check if username exists
    $checkUsername = $conn->prepare("SELECT id FROM users WHERE username=?");
    $checkUsername->bind_param("s", $username);
    $checkUsername->execute();
    $usernameResult = $checkUsername->get_result();

    if ($usernameResult->num_rows > 0) {
        // Username already exists
        echo json_encode(["status" => "error", "message" => "Username already exists."]);
    } else {
        // Both email and username are available, proceed with registration
        $stmt = $conn->prepare("INSERT INTO users (fullname, email, username, password, user_type) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $fullname, $email, $username, $password, $user_type);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "User registered successfully."]);
        } else {
            echo json_encode(["status" => "error", "message" => "Registration failed."]);
        }
    }
}

// Close connection
$conn->close();
?>
