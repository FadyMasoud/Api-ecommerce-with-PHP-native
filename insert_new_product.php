<?php
include 'smartchipsdb.php';

$pd_name = $_POST['pd_name'] ?? '';
$pd_description = $_POST['pd_description'] ?? '';
$pd_cost = $_POST['pd_cost'] ?? '';
$pd_review = $_POST['pd_review'] ?? '';
$pd_star = $_POST['pd_star'] ?? '';
$pd_category_ID = $_POST['pd_category_ID'] ?? '';
$pd_img = '';

if (isset($_FILES['pd_img']) && $_FILES['pd_img']['error'] === UPLOAD_ERR_OK) {
    $img_temp = $_FILES['pd_img']['tmp_name'];
    $img_name = basename($_FILES['pd_img']['name']);
    $img_dir = './assets/' . $img_name;

    if (!move_uploaded_file($img_temp, $img_dir)) {
        echo json_encode(array('message' => 'Failed to upload image'));
        return;
    }

    $pd_img = $img_dir;
} else {
    echo json_encode(array('message' => 'Image not uploaded'));
    return;
}

if (empty($pd_name) || empty($pd_description) || empty($pd_cost) || 
empty($pd_review) || empty($pd_star) || empty($pd_category_ID) || empty($pd_img)) {
    echo json_encode(array('message' => 'Please fill in all fields'));
    return;
}

$sql = "SELECT pd_name FROM product WHERE pd_name=? OR pd_img=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $pd_name, $pd_img);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(array('message' => 'Product already exists'));
    $stmt->close();
    return;
}
$stmt->close();

$sql = "INSERT INTO product (pd_name, pd_description, pd_cost, pd_review, pd_img, pd_star, pd_category_ID) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssissi", $pd_name, $pd_description, $pd_cost, $pd_review, $pd_img, $pd_star, $pd_category_ID);

if ($stmt->execute()) {
    echo json_encode(array('message' => 'Product inserted successfully'));
} else {
    echo json_encode(array('message' => 'Failed to insert product'));
}

$stmt->close();
$conn->close();
?>
