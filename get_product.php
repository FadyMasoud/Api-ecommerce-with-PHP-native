<?php
include 'smartchipsdb.php';
// echo $_GET['cond'];
if(isset($_GET['cond']) && !empty($_GET['cond'])){
    $cond = $_GET['cond'];
    $stmt=$conn->prepare("SELECT product.*, categories.cat_name ,categories.cat_id FROM product JOIN categories ON product.pd_category_ID=categories.cat_id where $cond and pd_status = 1");

} else {
    $stmt=$conn->prepare("SELECT product.*, categories.cat_name,categories.cat_id FROM product JOIN categories ON product.pd_category_ID=categories.cat_id where pd_status = 1");
}

$stmt->execute();

$result=$stmt->get_result();

if($result->num_rows>0){
    $products=array();
    while($row=$result->fetch_assoc()){
        $products[] = $row;
    }
    echo json_encode($products);
} else {
    echo json_encode(['status'=>false, 'message'=>'No products found']);
}

?>