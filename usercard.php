<?php
include 'smartchipsdb.php';
$action = $_GET['action'];

if ($action == 'insert') {
    $data = json_decode(file_get_contents("php://input"), true);
    
    $user_id = $data['id_user'];
    $product_id = $data['id_product'];
    $quantity = $data['quantity'];

    if (!$user_id || !$product_id || !$quantity) {
        http_response_code(400);
        echo json_encode(["message" => "All fields are required"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO usercard (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $stmt->bind_param("iii", $user_id, $product_id, $quantity);

    if ($stmt->execute()) {
        http_response_code(201);
        echo json_encode(["message" => "User card created successfully"]);
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Server error"]);
    }

    $stmt->close();
} elseif ($action == 'get') {
    $user_id = $_GET['user_id'];

    $stmt = $conn->prepare("SELECT uc.*, p.pd_name, p.pd_description, p.pd_cost, p.pd_review, p.pd_img, p.pd_star, p.pd_category_ID
                            FROM cards uc
                            JOIN product p ON uc.id_product = p.pd_id
                            WHERE uc.id_user = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        $usercards = $result->fetch_all(MYSQLI_ASSOC);

        if (count($usercards) > 0) {
            echo json_encode($usercards);
        } else {
            http_response_code(404);
            echo json_encode(["message" => "No user cards found"]);
        }
    } else {
        http_response_code(500);
        echo json_encode(["message" => "Server error"]);
    }

    $stmt->close();
} else {
    http_response_code(400);
    echo json_encode(["message" => "Invalid action"]);
}

$conn->close();

?>
