<?php
include 'smartchipsdb.php';
$request=json_decode(file_get_contents("php://input"),true);

if (!isset($request['username']) || !isset($request['mail'])|| !isset($request['pass'])
|| !isset($request['phone'])) {
    http_response_code(400);
    echo json_encode(array("message" => "Missing parameters"));
    return;
}

$username = $request['username'];
$email = $request['mail'];
$password = $request['pass'];
$phone = $request['phone'];
$position=$request['position'];

if (empty($username) || empty($email) || empty($password) || empty($phone)) {
    $response['status'] = 'error';
    $response['message'] = 'All fields are required.';
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['status'] = 'error';
    $response['message'] = 'Invalid email format.';
} else {
    $stmt = $conn->prepare("SELECT us_id FROM users WHERE us_username=? OR us_email=? OR us_phone=? OR us_password=?");
    $stmt->bind_param("ssss", $username,$email,$phone,$password);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        $response['status'] = 'error';
        $response['message'] = 'This Data already exists before.';
    } 
    
    else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (us_username, us_email, us_password,us_phone,us_postion) VALUES (?,?,?,?,?)");
        $stmt->bind_param("sssss", $username, $email, $hashed_password,$phone,$position);

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'User registered successfully.';
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Error registering user.';
        }
        $stmt->close();
    }

}
echo json_encode($response);

$conn->close();
?>
