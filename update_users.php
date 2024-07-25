<?php
include 'smartchipsdb.php';

$postdata = file_get_contents("php://input");
$request = json_decode($postdata, true);

if (!isset($request['id']) || !isset($request['username']) || !isset($request['phone']) || !isset($request['mail']) || !isset($request['pass'])) {
    http_response_code(400);
    echo json_encode(array("message" => "Missing parameters"));
    return;
}

$id = $request['id'];
$name = $request['username'];
$phone = $request['phone'];
$email = $request['mail'];
$password = password_hash($request['pass'], PASSWORD_BCRYPT);
$position=$request['position'];


$stmt = $conn->prepare("SELECT us_id FROM users WHERE us_email = ? AND us_id <> ?");
$stmt->bind_param("si",$email,$id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    http_response_code(400);
    echo json_encode(array("message" => "Email already exists"));
    $stmt->close();
    $conn->close();
    return;
}
$stmt->close();

$stmt = $conn->prepare("UPDATE users SET us_username = ?, us_phone = ?, us_email = ?, us_password = ?,us_postion= ? WHERE us_id = ?");
$stmt->bind_param("sssssi", $name, $phone, $email, $password,$position, $id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        http_response_code(200);
        echo json_encode(array("message" => "User data updated successfully"));
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "User not found"));
    }
} else {
    http_response_code(500); 
    echo json_encode(array("message" => "Failed to update record: " . $conn->error));
}

$stmt->close();
$conn->close();
?>
