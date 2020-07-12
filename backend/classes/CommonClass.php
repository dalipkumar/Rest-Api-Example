<?php 
/*
A simple RESTful webservices base class
Use this as a template and build upon it
*/
class CommonClass {
	
	private $httpVersion = "HTTP/1.1";
    public $con = false;
    private $db_host = 'localhost';
    private $db_user = 'root';
    private $db_pass = '6634';
    private $db_name = 'ak_restapi_test';
    
    function __construct() {
        $this->db_connection();
    }
    
    public function db_connection()
    {
        $this->con = mysqli_connect($this->db_host,$this->db_user,$this->db_pass,$this->db_name);
        
        if (mysqli_connect_errno()) {
            $statusCode = 404;
            $rawData = array('error' => 'Unable to connect with db. Please connect with system sdministrator');
            $requestContentType = $_SERVER['HTTP_ACCEPT'];
            $this->setHttpHeaders($requestContentType, $statusCode);
            echo json_encode($rawData);
            die;
        }
    }
    
    public function db_connection_close()
    {
        mysqli_close($this->con);
    }
    
    public function query($query)
    {
        $result = mysqli_query($this->con,$query);
        return $result;
    }
    
    public function num_rows($result)
    {
        if($result)
        {
            return (int) mysqli_num_rows($result);
        }
        
        return 0;
    }
    
    public function fetch_row($result)
    {
        if($result)
        {
            $row = mysqli_fetch_assoc($result);
            
            return $row;
        }
        
        return array();
    }
    
    public function fetch_array($result)
    {
        $return_array = array();
        if($result)
        {
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
            {
                $return_array[] = $row;
            }
        }
        
        return $return_array;
    }
    
	public function setHttpHeaders($contentType, $statusCode){
		
		$statusMessage = $this -> getHttpStatusMessage($statusCode);
		
		header($this->httpVersion. " ". $statusCode ." ". $statusMessage);		
		header("Content-Type:". $contentType);
	}
	
	public function getHttpStatusMessage($statusCode){
		$httpStatus = array(
			100 => 'Continue',  
			101 => 'Switching Protocols',  
			200 => 'OK',
			201 => 'Created',  
			202 => 'Accepted',  
			203 => 'Non-Authoritative Information',  
			204 => 'No Content',  
			205 => 'Reset Content',  
			206 => 'Partial Content',  
			300 => 'Multiple Choices',  
			301 => 'Moved Permanently',  
			302 => 'Found',  
			303 => 'See Other',  
			304 => 'Not Modified',  
			305 => 'Use Proxy',  
			306 => '(Unused)',  
			307 => 'Temporary Redirect',  
			400 => 'Bad Request',  
			401 => 'Unauthorized',  
			402 => 'Payment Required',  
			403 => 'Forbidden',  
			404 => 'Not Found',  
			405 => 'Method Not Allowed',  
			406 => 'Not Acceptable',  
			407 => 'Proxy Authentication Required',  
			408 => 'Request Timeout',  
			409 => 'Conflict',  
			410 => 'Gone',  
			411 => 'Length Required',  
			412 => 'Precondition Failed',  
			413 => 'Request Entity Too Large',  
			414 => 'Request-URI Too Long',  
			415 => 'Unsupported Media Type',  
			416 => 'Requested Range Not Satisfiable',  
			417 => 'Expectation Failed',  
			500 => 'Internal Server Error',  
			501 => 'Not Implemented',  
			502 => 'Bad Gateway',  
			503 => 'Service Unavailable',  
			504 => 'Gateway Timeout',  
			505 => 'HTTP Version Not Supported');
		return ($httpStatus[$statusCode]) ? $httpStatus[$statusCode] : $status[500];
	}
}
?>