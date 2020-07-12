<?php
require_once("CommonClass.php");

class RestAPIHandler extends CommonClass {
    
    public function invalid_request()
    {
        $statusCode = 404;
        $rawData = array('error' => 'Invalid request!');
        $requestContentType = $_SERVER['HTTP_ACCEPT'];
        $this->setHttpHeaders($requestContentType, $statusCode);
        
       	echo $this->encodeJson($rawData);
    }
    
    public function get_login()
    {
        $rawData = array("error" => "Some required fields are missing!");
        if(isset($_POST['username']) && isset($_POST['password']))
        {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            if((strlen($username) > 0) && (strlen($password) > 0))
            {
                $check_user_query = "SELECT * FROM users WHERE user_name = '".$username."'";
                $result = $this->query($check_user_query);
                $cnt = $this->num_rows($result);
                if($cnt > 0)
                {
                    $row = $this->fetch_row($result);
                    if(isset($row['user_hash']))
                    {
                        //create encoded string for password
                        $encoded_pass = $this->genrate_encode_password($password,trim($row['user_hash']));
                        //End
                        
                        if(trim($row['user_pwd']) == $encoded_pass)
                        {
                            $rawData = array("success" => "User logined successfully!","user_id"=>$row['user_id']);
                        }else
                        {
                            $rawData = array("error" => "Username and password does not match!");
                        }
                    }else
                    {
                        $rawData = array("error" => "Invalid user!");
                    }
                }else
                {                    
                    $rawData = array("error" => "Invalid user!");
                }
                    
            }
        }
        
        $requestContentType = $_SERVER['HTTP_ACCEPT'];
        $this->setHttpHeaders($requestContentType, $statusCode);
            
       	echo $this->encodeJson($rawData);
    }
    
    public function get_data($table,$id=0)
    {
        $statusCode = 404;
        if($this->check_table_exit($table))
        {
            if(isset($_GET['user_id'])){ $user_id = (int) $_GET['user_id'];}else{ $user_id = 0;} 
            
            $query = "SELECT * FROM ".$table." WHERE (1=1) ".(($id > 0)?" AND (".$this->get_primary_key($table)." = ".$id.")":"").(($user_id > 0)?" AND (user_id = ".$user_id.")":"");
            $result = $this->query($query);
            $rows = $this->num_rows($result);
            if($rows > 0)
            {
                if($id > 0)
                {
                    $data = $this->fetch_row($result);
                }else
                {
                    $data = $this->fetch_array($result);
                }
                $statusCode = 200;
                $rawData = array('data' => $data);
            }else
            {
                $rawData = array('error' => 'No Record Found!');
            }
        }else
        {
            $rawData = array('error' => 'Invalid table!');
        }
        
        $requestContentType = $_SERVER['HTTP_ACCEPT'];
        $this->setHttpHeaders($requestContentType, $statusCode);
            
       	echo $this->encodeJson($rawData);
    }
    
    public function delete_data($table,$id=0)
    {
        $statusCode = 404;
        if($this->check_table_exit($table))
        {
            if($id > 0)
            {
                $key = $this->get_primary_key($table);
                $query = "DELETE FROM ".$table." WHERE (".$key." = ".$id.")";
                if($this->query($query))
                {
                    $statusCode = 200;
                    $rawData = array('success' => 'Data deleted successfully!');
                }else
                {
                    $rawData = array('error' => 'Error to delete data!');
                }
            }else
            {
                $rawData = array('error' => 'Invalid request!');
            }
        }else
        {
            $rawData = array('error' => 'Invalid table!');
        }
        
        $requestContentType = $_SERVER['HTTP_ACCEPT'];
        $this->setHttpHeaders($requestContentType, $statusCode);
            
       	echo $this->encodeJson($rawData);
    }
    
    public function create_data($table="")
    {
        $statusCode = 404;
        if($this->check_table_exit($table))
        {
            switch($table)
            {
                case "users":
                    $rawData = $this->create_user();
                break;
                
                case "work":
                    $rawData = $this->create_work();
                break;
                
                default:
                    $rawData = array('error' => 'Invalid request!');
                break;
            }
            
            if(isset($rawData['success'])){$statusCode = 200;}
        }else
        {
            
            $rawData = array('error' => 'Invalid table!');
        }
        
        $requestContentType = $_SERVER['HTTP_ACCEPT'];
        $this->setHttpHeaders($requestContentType, $statusCode);
            
       	echo $this->encodeJson($rawData);
    }
    
    
    private function create_work()
    {
        $return = array("error" => "Some required fields are missing!");
        if(isset($_POST['workname']) && isset($_POST['user_id']))
        {
            $work_name = trim($_POST['workname']);
            $work_id = (int) $_POST['id'];
            $work_score = (int) $_POST['workscore'];
            $user_id = (int) $_POST['user_id'];
            if((strlen($work_name) > 0) && ($user_id > 0))
            {
                if($work_id > 0)
                {
                    $query = "UPDATE work SET work_name = '".$work_name."', work_score = '".$work_score."', user_id='".$user_id."' WHERE work_id = ".$work_id;
                }else
                {
                    $query = "INSERT INTO work SET work_name = '".$work_name."', work_score = '".$work_score."', user_id='".$user_id."'";
                }
                
                if($this->query($query))
                {
                    $return = array("success" => "Work ".(($work_id > 0)?"updated":"created")." successfully!");
                }else
                {
                    $return = array("error" => "Unable to ".(($work_id > 0)?"updated":"created")." work!");
                }
            }
        }
        
        return $return;
    }
    
    
    private function create_user()
    {
        $return = array("error" => "Some required fields are missing!");
        if(isset($_POST['username']) && isset($_POST['password']))
        {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);
            $hash = uniqid();
            $user_id = (int) $_POST['user_id'];
            if((strlen($username) > 0) && (strlen($password) > 0))
            {
                $check_user_query = "SELECT * FROM users WHERE user_name = '".$username."'";
                $result = $this->query($check_user_query);
                $rows = $this->num_rows($result);
                if($rows > 0)
                {
                    $return = array("error" => "User already exit!");
                }else
                {
                    //create encoded string for password
                    $encoded_pass = $this->genrate_encode_password($password,$hash);
                    //End
                    
                    $query = "INSERT INTO users SET user_name = '".$username."', user_pwd = '".$encoded_pass."', user_hash='".$hash."'";
                    if($this->query($query))
                    {
                        $return = array("success" => "User created successfully!");
                    }else
                    {
                        $return = array("error" => "Unable to create user!");
                    }
                }
                    
            }
        }
        
        return $return;
    }
    
    private function genrate_encode_password($password,$hash="")
    {
        $password = base64_encode($password);
        $hash = ((strlen(trim($hash)) > 0)?base64_encode(trim($hash)):"");
        
        return base64_encode($password."aks".$hash);
    }
    
    private function get_primary_key($table_name="")
    {
        switch($table_name)
        {
            case "users":
                $key = "user_id";
            break;
            
            case "work":
                $key = "work_id";
            break;
            
            default :
                $key = "id";
            break;
        }
        
        return $key;
    }
    
    private function check_table_exit($table_name='')
    {
        if($result = $this->query("SHOW TABLES LIKE '".$table_name."'"))
        {
            if($result->num_rows == 1)
            {
                return true;
            }else
            {
                return false;
            }
        }else
        {
            return false;
        }
    }
    
    private function encodeHtml($responseData) {
	
		$htmlResponse = "<table border='1'>";
		foreach($responseData as $key=>$value) {
    			$htmlResponse .= "<tr><td>". $key. "</td><td>". $value. "</td></tr>";
		}
		$htmlResponse .= "</table>";
		return $htmlResponse;		
	}
	
	private function encodeJson($responseData) {
		$jsonResponse = json_encode($responseData);
		return $jsonResponse;		
	}
	
	private function encodeXml($responseData) {
		// creating object of SimpleXMLElement
		$xml = new SimpleXMLElement('<?xml version="1.0"?><mobile></mobile>');
		foreach($responseData as $key=>$value) {
			$xml->addChild($key, $value);
		}
		return $xml->asXML();
	}
}
?>