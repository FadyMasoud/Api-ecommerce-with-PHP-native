<?php
include 'smartchipsdb.php';

$cat_id = $_POST['cat_id'] ?? '';
$cat_name = $_POST['cat_name'] ?? '';
$cat_pd_name = $_POST['cat_pd_name'] ?? '';
$cat_pd_description = $_POST['cat_pd_description'] ?? '';
$cat_pd_cost = $_POST['cat_pd_cost'] ?? '';
$cat_pd_review = $_POST['cat_pd_review'] ?? '';
$cat_pd_star = $_POST['cat_pd_star'] ?? '';
$cat_pd_img = '';


if (empty($cat_id) || empty($cat_name) || empty($cat_pd_name) || empty($cat_pd_description)
 || empty($cat_pd_cost) || empty($cat_pd_review) || empty($cat_pd_star)) {
    echo json_encode(array('message' => 'Please fill in all fields'));
    return;
}

if (isset($_FILES['cat_pd_img']) && $_FILES['cat_pd_img']['error'] === UPLOAD_ERR_OK) {
    $img_temp = $_FILES['cat_pd_img']['tmp_name'];
    $img_name = basename($_FILES['cat_pd_img']['name']);
    $img_dir = './assets/' . $img_name;

    if (!move_uploaded_file($img_temp, $img_dir)) {
        echo json_encode(array('message' => 'Failed to upload image'));
        return;
    }

    $cat_pd_img = $img_dir;
}

if (empty($cat_pd_img)) {
    echo json_encode(array('message' => 'Image not uploaded'));
    return;
}

$sql = "SELECT * FROM categories WHERE cat_id=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cat_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo json_encode(array('message' => 'Category not found'));
    $stmt->close();
    $conn->close();
    return;
}

$stmt->close();

// Additional validation check
$sql = "SELECT cat_id FROM categories WHERE cat_name = ?  AND cat_id <> ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $cat_name, $cat_id);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo json_encode(array('message' => 'Category already exists'));
    $stmt->close();
    $conn->close();
    return;
}

$stmt->close();

if (!empty($cat_pd_img)) {
    $sql = "UPDATE categories SET cat_name=?, cat_pd_name=?, cat_pd_description=?, cat_pd_cost=?, cat_pd_review=?, cat_pd_star=?, cat_pd_img=? WHERE cat_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssisssi", $cat_name, $cat_pd_name, $cat_pd_description, $cat_pd_cost, $cat_pd_review, $cat_pd_star, $cat_pd_img, $cat_id);
} else {
    $sql = "UPDATE categories SET cat_name=?, cat_pd_name=?, cat_pd_description=?, cat_pd_cost=?, cat_pd_review=?, cat_pd_star=? WHERE cat_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssissi", $cat_name, $cat_pd_name, $cat_pd_description, $cat_pd_cost, $cat_pd_review, $cat_pd_star, $cat_id);
}

if ($stmt->execute()) {
    echo json_encode(array('message' => 'Category updated successfully'));
} else {
    echo json_encode(array('message' => 'Failed to update category'));
}

$stmt->close();
$conn->close();
?>
