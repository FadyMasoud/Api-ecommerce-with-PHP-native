<?php
include 'smartchipsdb.php';
$data=json_decode(file_get_contents("php://input"),true);


$id=$data['id'];
$category=$data['categoryname'];
$name=$data['name'];
$description=$data['description'];
$cost=$data['cost'];
$review=$data['review'];
$img=$data['img'];
$star=$data['star'];




$sql = "SELECT cat_id FROM categories WHERE cat_name = ? AND cat_id <> ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("si", $category, $id);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    http_response_code(400);
    $response = array("message" => "category Name already exists");
    echo json_encode($response);
    $stmt->close();
    $conn->close();
    return;
}



$sql="UPDATE categories SET cat_name=?,cat_pd_name=?,cat_pd_description=?,cat_pd_cost=?,cat_pd_review=?,cat_pd_img=?,cat_pd_star=? WHERE cat_id=?";

$stmt=$conn->prepare($sql);

$stmt->bind_param("sssisssi",$category,$name,$description,$cost,$review,$img,$star,$id);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        http_response_code(200);
        echo json_encode(array("message" => "category data updated successfully"));
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "category not found"));
    }
} else {
    http_response_code(500); 
    echo json_encode(array("message" => "Failed to update category record: " . $conn->error));
}

$stmt->close();

$conn->close();


?>
