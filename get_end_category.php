
<?php
include 'smartchipsdb.php';
$data=json_decode(file_get_contents("php://input"),true);

if(isset($_GET['cond']) && !empty($_GET['cond'])){
    $cond=$_GET['cond'];
    $stmt = $conn->prepare("SELECT * FROM categories LIMIT 3 OFFSET 3 WHERE $cond");
    // $stmt = $conn->prepare("SELECT categories.*,product.*  FROM categories JOIN product ON categories.cat_id=product.pd_category_ID where $cond");
}else{
    $stmt = $conn->prepare("SELECT * FROM categories LIMIT 3 OFFSET 3");
}
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $users = array();
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
    echo json_encode($users);
} else {
    echo json_encode(array());
}

$stmt->close();
?>
