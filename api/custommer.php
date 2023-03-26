<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/custommer/addPass', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json,true);
    $conn =$GLOBALS['connect'];
    // $body = $request->getParsedBody();
    // $username = $body['username'];
    // $password = $body['password'];
    $hashPass = password_hash($jsonData['password'],PASSWORD_DEFAULT);
    $sql = 'UPDATE customer SET password = ? where username = ? ';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss",$hashPass,$jsonData['username']);
    $stmt->execute();
    $result = $stmt->get_result();
    
   
    $response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
    ->withHeader('Content-Type', 'application/json; charset=utf-8')
    ->withStatus(200);
  
 
});

$app->post('/custommer/login', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json,true);
    $conn =$GLOBALS['connect'];

    // $body = $request->getParsedBody();
    // $username = $body['username'];
    // $password = $body['password'];

    $pwdInDB = getPasswordFromDB($conn,$jsonData['username']);

    if(password_verify($jsonData['password'],$pwdInDB))
    {
        $result = "login Success";
    }
    else
    {
        $result = "login fail!!";
    }
   
    $response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
    ->withHeader('Content-Type', 'application/json; charset=utf-8')
    ->withStatus(200);
  
 
});
$app->post('/updateMoney', function (Request $request, Response $response, $args) {
    $json = $request->getBody();
    $jsonData = json_decode($json,true);
    $conn =$GLOBALS['connect'];
   
    $sql = 'UPDATE customer SET money = money - ? WHERE cid = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $jsonData['money'],$jsonData['cid']);
    $stmt->execute();
    $result = $stmt->get_result();
  
    $response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
    ->withHeader('Content-Type', 'application/json; charset=utf-8')
    ->withStatus(200);
  
});

$app->get('/getCusid/{username}', function (Request $request, Response $response, $args) {
    $conn =$GLOBALS['connect'];
    $sql = 'select cid,name,money,address,phone from customer where username = ?';
    $stmt = $conn->prepare($sql);
    $cid = $args['username'];
    $stmt->bind_param('s', $cid);
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

function getPasswordFromDB($conn,$username)
    {
        $sql = "SELECT password from customer where username = ?";
        $stmt =$conn->prepare($sql);
        $stmt->bind_param("s",$username);
        $stmt->execute();
        $result =$stmt->get_result();
        if($result->num_rows == 1)
        {
            $row =$result->fetch_assoc();
           $dbPassword = $row["password"];
          
        }
        return $dbPassword;
    }

?>