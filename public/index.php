<?php
header("Access-Control-Allow-Origin: *");
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../src/vendor/autoload.php';
$app = new \Slim\App;

//endpoint user registration
$app->post('/userreg', function (Request $request, Response $response, array $args)
{
    $data=json_decode($request->getBody());
    $name =$data->name ;
    $uname =$data->username ;
    $pword = $data->password ;
    //Database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dms";
    try {
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname",
    
    $username, $password);
    
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE,
    
    PDO::ERRMODE_EXCEPTION);
    
    $sql = "INSERT INTO user_account (uname, username, passkey)
    VALUES ('". $name ."','". $uname ."','". $pword ."')";
    // use exec() because no results are returned
    $conn->exec($sql);
    $response->getBody()->write(json_encode(array("status"=>"success","data"=>null)));
    
    } catch(PDOException $e){
    $response->getBody()->write(json_encode(array("status"=>"error",
    "message"=>$e->getMessage())));
    }
    $conn = null;
});

//endpoint create or upload file
$app->post('/fileupload', function (Request $request, Response $response, array $args)
{
    $data=json_decode($request->getBody());
    $document_TITLE =$data->document_TITLE ;
    $document_TYPE =$data->document_TYPE ;
    $document_ORIGIN = $data->document_ORIGIN ;
    $date_received = $data->date_received;
    $document_DESTINATION = $data->document_DESTINATION ;
    $tags = $data->tags ;

    //Database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dms";
    try {
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname",
    
    $username, $password);
    
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE,
    
    PDO::ERRMODE_EXCEPTION);
    
    $sql = "INSERT INTO documents (document_TITLE, document_TYPE, document_ORIGIN, date_received, document_DESTINATION, tags)
    VALUES ('". $document_TITLE ."','". $document_TYPE ."','". $document_ORIGIN ."','". $date_received ."','". $document_DESTINATION ."','". $tags ."')";
    // use exec() because no results are returned
    $conn->exec($sql);
    $response->getBody()->write(json_encode(array("status"=>"success","data"=>null)));
    
    } catch(PDOException $e){
    $response->getBody()->write(json_encode(array("status"=>"error",
    "message"=>$e->getMessage())));
    }
    $conn = null;
});

//endpoint delete file upload
$app->post('/deletefileupload', function (Request $request, Response $response, array $args)
{
    $data=json_decode($request->getBody());
    $id =$data->id ;

    //Database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dms";
    try {
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sql = "DELETE FROM documents where document_ID='". $id ."'";
    // use exec() because no results are returned
    $conn->exec($sql);
    $response->getBody()->write(json_encode(array("status"=>"success","data"=>null)));
    
    } catch(PDOException $e){
    $response->getBody()->write(json_encode(array("status"=>"error",
    "message"=>$e->getMessage())));
    }
    $conn = null;
});

//endpoint update file upload
$app->post('/updatefileupload', function (Request $request, Response $response, array $args)
{
    $data=json_decode($request->getBody());
    $id =$data->id ;
    $document_TITLE =$data->document_TITLE ;
    $document_TYPE =$data->document_TYPE ;
    $document_ORIGIN = $data->document_ORIGIN ;
    $date_received = date("m-d-y");
    $document_DESTINATION = $data->document_DESTINATION ;
    $tags = $data->tags ;

    //Database
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "dms";
    try {
    
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    

    $sql = "UPDATE documents set document_TITLE='". $document_TITLE ."', document_TYPE='". $document_TYPE ."', document_ORIGIN='". $document_ORIGIN ."', document_DESTINATION='". $document_DESTINATION ."', tags='". $tags ."' where document_id='". $id ."'";
    // use exec() because no results are returned
    $conn->exec($sql);
    $response->getBody()->write(json_encode(array("status"=>"success","data"=>null)));
    
    } catch(PDOException $e){
    $response->getBody()->write(json_encode(array("status"=>"error",
    "message"=>$e->getMessage())));
    }
    $conn = null;
});

//endpoint search file upload
$app->post('/searchfileupload', function (Request $request, Response $response, array
		$args) {

		$data=json_decode($request->getBody());
		$id =$data->document_ID;
		//Database
		$servername = "localhost";
		$username = "root";
		$password = "";
		$dbname = "dms";
		// Create connection
		$conn = new mysqli($servername, $username, $password, $dbname);
		// Check connection
		if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
		}
		$sql = "SELECT * FROM documents where document_ID='". $id ."'";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
		$data=array();
		while($row = $result->fetch_assoc()) {
			array_push($data,array("document_ID"=>$row["document_ID"],"document_TITLE"=>$row["document_TITLE"] ,"document_ORIGIN"=>$row["document_ORIGIN"],"date_received"=>$row["date_received"] ,"document_DESTINATION"=>$row["document_DESTINATION"],"tags"=>$row["tags"] ,));

		}
		$data_body=array("status"=>"success","data"=>$data);
		$response->getBody()->write(json_encode($data_body));
		} else {

		$response->getBody()->write(array("status"=>"success","data"=>null));
		}
		$conn->close();

		return $response;
		});

        
$app->run();
?>