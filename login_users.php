<?php
include 'smartchipsdb.php';
$postdata = file_get_contents("php://input");
$request = json_decode($postdata, true);

if (!isset($request['email']) || !isset($request['password'])) {
    http_response_code(400);
    echo json_encode(array("message" => "Missing parameters"));
    return;
}

$email = $request['email'];
$password = $request['password'];


$stmt = $conn->prepare("SELECT us_id,us_postion, us_password,us_username,us_email FROM users WHERE us_email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id,$us_postion, $hashed_password,$us_username,$us_email);
    $stmt->fetch();
    if (password_verify($password, $hashed_password)) {
        http_response_code(200);
        echo json_encode(array("message" => "Login successful","user"=>array(
            "id"=>$id,
            "type"=>$us_postion,
            "username"=>$us_username,
            "email"=>$us_email)));
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Invalid password"));
    }
} else {
    http_response_code(400);
    echo json_encode(array("message" => "Email not found"));
}

$stmt->close();
$conn->close();

?>
