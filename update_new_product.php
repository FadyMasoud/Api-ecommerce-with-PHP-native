<?php
include 'smartchipsdb.php';

$pd_id = $_POST['pd_id'] ?? '';
$pd_name = $_POST['pd_name'] ?? '';
$pd_description = $_POST['pd_description'] ?? '';
$pd_cost = $_POST['pd_cost'] ?? '';
$pd_review = $_POST['pd_review'] ?? '';
$pd_star = $_POST['pd_star'] ?? '';
$pd_category_ID = $_POST['pd_category_ID'] ?? '';
$pd_img = '';

if (empty($pd_id) || empty($pd_name) || empty($pd_description) || empty($pd_cost) || empty($pd_review) || empty($pd_star) || empty($pd_category_ID)) {
    echo json_encode(array('message' => 'Please fill in all fields'));
    return;
}

if (isset($_FILES['pd_img']) && $_FILES['pd_img']['error'] === UPLOAD_ERR_OK) {
    $img_temp = $_FILES['pd_img']['tmp_name'];
    $img_name = basename($_FILES['pd_img']['name']);
    $img_dir = './assets/' . $img_name;

    if (!move_uploaded_file($img_temp, $img_dir)) {
        echo json_encode(array('message' => 'Failed to upload image'));
        return;
    }

    $pd_img = $img_dir;
}

$sql = "SELECT * FROM product WHERE pd_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $pd_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(array('message' => 'Product not found'));
    $stmt->close();
    $conn->close();
    return;
}

$stmt->close();

// Additional validation check
$sql = "SELECT pd_id FROM product WHERE pd_name = ? AND pd_id <> ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $pd_name, $pd_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(array('message' => 'Product already exists'));
    $stmt->close();
    $conn->close();
    return;
}

$stmt->close();

if (!empty($pd_img)) {
    $sql = "UPDATE product SET pd_name=?, pd_description=?, pd_cost=?, pd_review=?, pd_star=?, pd_img=?, pd_category_ID=? WHERE pd_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissii", $pd_name, $pd_description, $pd_cost, $pd_review, $pd_star, $pd_img, $pd_category_ID, $pd_id);
} else {
    $sql = "UPDATE product SET pd_name=?, pd_description=?, pd_cost=?, pd_review=?, pd_star=?, pd_category_ID=? WHERE pd_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissii", $pd_name, $pd_description, $pd_cost, $pd_review, $pd_star, $pd_category_ID, $pd_id);
}

if ($stmt->execute()) {
    echo json_encode(array('message' => 'Product updated successfully'));
} else {
    echo json_encode(array('message' => 'Failed to update product'));
}

$stmt->close();
$conn->close();
?>
