<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/orederItem/{blid}', function (Request $request, Response $response, $args) {
    $conn =$GLOBALS['connect'];
    $blid= $args['blid'];
    $sql = 'SELECT orderitems.blid, food.fid, food.name, orderitems.amount, SUM(food.price * orderitems.amount) 
    FROM orderitems 
    JOIN food ON orderitems.fid = food.fid 
    WHERE orderitems.blid = ?
    GROUP BY orderitems.blid, food.fid, food.name, orderitems.amount';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$blid);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
   
    foreach ($result as $row) {
        array_push($data, $row);
    }
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
    ->withHeader('Content-Type', 'application/json; charset=utf-8')
    ->withStatus(200);
  
 
});

$app->get('/orederItem', function (Request $request, Response $response, $args) {
    $conn =$GLOBALS['connect'];
    $sql = 'SELECT * FROM `orderitems`';
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = array();
   
    foreach ($result as $row) {
        array_push($data, $row);
    }
    $response->getBody()->write(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
    ->withHeader('Content-Type', 'application/json; charset=utf-8')
    ->withStatus(200);
  
 
});

$app->delete('/orderItem/delete/{bid}', function (Request $request, Response $response, $args) {
    $conn =$GLOBALS['connect'];
    
    $bid= $args['bid'];
    
    $sql ="DELETE FROM `orderitems` WHERE blid = ?";
    $stmt =$conn->prepare($sql);
    $stmt->bind_param("i",$bid);
    $stmt->execute();
    $result =$stmt->affected_rows;

    $json = json_encode($result);

    //return result
    $response->getBody()->write(json_encode($json, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response  ->withHeader('Content-Type', 'application/json; charset=utf-8')
    ->withStatus(200);


});
$app->post('/orderItem/insert', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json,true);
    $conn =$GLOBALS['connect'];
    $sql = 'INSERT INTO `orderitems`(`blid`, `fid`, `amount`) VALUES (?,?,?)';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iii",$jsonData['bid'],$jsonData['fid'],$jsonData['amount']);
    $stmt->execute();
 
    $response->getBody()->write(json_encode("Data inserted successfully", JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
    ->withHeader('Content-Type', 'application/json; charset=utf-8')
    ->withStatus(200);
}); 
?>