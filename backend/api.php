<?php
error_reporting(0);
require_once("classes/RestAPIHandler.php");

$RestAPIHandler = new RestAPIHandler();

$requestMethod = trim($_SERVER['REQUEST_METHOD']);

$table_name = trim($_GET['table_name']);
$id = (int) ((isset($_GET['id']))?trim($_GET['id']):0);
$type = ((isset($_GET['type']))?trim($_GET['type']):"");

switch(strtolower($requestMethod))
{
    case 'get':
        $RestAPIHandler->get_data($table_name,$id);
    break;
    
    case 'post':
        if($type == 'loginuser'){
            $RestAPIHandler->get_login();
        }else
        {
            $RestAPIHandler->create_data($table_name);
        }        
    break;
    
    case 'put':
    
    break;
    
    case 'delete':
        $RestAPIHandler->delete_data($table_name,$id);
    break;
    
    default:
        $RestAPIHandler->invalid_request();
    break;
}

$RestAPIHandler->db_connection_close();
