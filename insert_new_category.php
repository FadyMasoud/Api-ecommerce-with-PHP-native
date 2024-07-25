<?php
include 'smartchipsdb.php';
// $folderPath = "./assets/";

$cat_name = $_POST['cat_name'] ?? '';
$cat_pd_name = $_POST['cat_pd_name'] ?? '';
$cat_pd_description = $_POST['cat_pd_description'] ?? '';
$cat_pd_cost = $_POST['cat_pd_cost'] ?? '';
$cat_pd_review = $_POST['cat_pd_review'] ?? '';
$cat_pd_star = $_POST['cat_pd_star'] ?? '';
$cat_pd_img = '';

if (isset($_FILES['cat_pd_img']) && $_FILES['cat_pd_img']['error'] === UPLOAD_ERR_OK) {
    $img_temp = $_FILES['cat_pd_img']['tmp_name'];
    $img_name = basename($_FILES['cat_pd_img']['name']);
    $img_dir = './assets/' . $img_name;

    if (!move_uploaded_file($img_temp, $img_dir)) {
        echo json_encode(array('message' => 'Failed to upload image'));
        return;
    }

    $cat_pd_img = $img_dir;
} else {
    echo json_encode(array('message' => 'Image not uploaded'));
    return;
}

if (empty($cat_name) || empty($cat_pd_name) || empty($cat_pd_description) || empty($cat_pd_cost) || empty($cat_pd_review) || empty($cat_pd_star) || empty($cat_pd_img)) {
    echo json_encode(array('message' => 'Please fill in all fields'));
    return;
}

$sql = "SELECT cat_name FROM categories WHERE cat_name=? OR cat_pd_img=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $cat_name, $cat_pd_img);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(array('message' => 'Category already exists'));
    $stmt->close();
    return;
}
$stmt->close();

$sql = "INSERT INTO categories (cat_name, cat_pd_name, cat_pd_description, cat_pd_cost, cat_pd_review, cat_pd_star, cat_pd_img) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssisss", $cat_name, $cat_pd_name, $cat_pd_description, $cat_pd_cost, $cat_pd_review, $cat_pd_star, $cat_pd_img);

if ($stmt->execute()) {
    echo json_encode(array('message' => 'Category inserted successfully'));
} else {
    echo json_encode(array('message' => 'Failed to insert category'));
}

$stmt->close();
$conn->close();
?>
