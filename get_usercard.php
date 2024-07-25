<?php
include 'smartchipsdb.php';
// echo $_GET['cond'];
if(isset($_GET['cond']) && !empty($_GET['cond'])){
    $cond = $_GET['cond'];
    $stmt=$conn->prepare("SELECT cards.*,product.* FROM cards JOIN product ON cards.id_product=product.pd_id where $cond and status = 1");

} else {
    $stmt=$conn->prepare("SELECT cards.*,product.* FROM cards JOIN product ON cards.id_product=product.pd_id where status = 1");
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
    echo json_encode(['status'=>false, 'message'=>'No card of products found']);
}

?>