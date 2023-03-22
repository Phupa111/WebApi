<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->post('/custommer/addPass', function (Request $request, Response $response, $args) {
    $conn =$GLOBALS['connect'];
    $body = $request->getParsedBody();
    $username = $body['username'];
    $password = $body['password'];
    $hashPass = password_hash($password,PASSWORD_DEFAULT);
    $sql = 'UPDATE customer SET password = ? where username = ? ';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss",$hashPass,$username);
    $stmt->execute();
    $result = $stmt->get_result();
    
   
    $response->getBody()->write(json_encode($result, JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    return $response
    ->withHeader('Content-Type', 'application/json; charset=utf-8')
    ->withStatus(200);
  
 
});

$app->post('/custommer/login', function (Request $request, Response $response, $args) {
    $conn =$GLOBALS['connect'];
    $body = $request->getParsedBody();
    $username = $body['username'];
    $password = $body['password'];

    $pwdInDB = getPasswordFromDB($conn,$username);

    if(password_verify($password,$pwdInDB))
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