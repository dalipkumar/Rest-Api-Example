<?php
session_start();
require_once('common/curl_functions.php');

//set base url of your api
$base_url = "http://localhost/my_sites/test/";
//End


$type = ((isset($_GET['type']))?trim($_GET['type']):"");

$is_error = true;
$message = "Unexpected error occured.";
$data = array();
if($type == 'login')
{
    $post_array = $_POST;
    
    $return_data = get_web_page($base_url.'backend/api/login/',$post_array);
    
    
    if($return_data['errno'] > 0)
    {
        $message = $return_data['errmsg'] ;
    }else
    {
        $content = trim($return_data['content']);
        $json_obj = (array) json_decode($content);
        
        if((isset($json_obj['success'])) && (isset($json_obj['user_id'])))
        {
            $is_error = false;
            $_SESSION['rad_user_id'] = (int) $json_obj['user_id'];
            $_SESSION['rad_user_login'] = true;
            $message = $json_obj['success'];
        }else if(isset($json_obj['error']))
        {
            $message = $json_obj['error'];
        }
    }
}else if($type == 'addwork')
{
    $post_array = $_POST;
    
    $return_data = get_web_page($base_url.'backend/api/work/',$post_array);
    
    if($return_data['errno'] > 0)
    {
        $message = $return_data['errmsg'] ;
    }else
    {
        $content = trim($return_data['content']);
        $json_obj = (array) json_decode($content);
        
        if((isset($json_obj['success'])))
        {
            $is_error = false;
            $message = $json_obj['success'];
        }else if(isset($json_obj['error']))
        {
            $message = $json_obj['error'];
        }
    }
}else if($type == 'getwork')
{
    $return_data = get_web_page($base_url.'backend/api/work/?user_id='.$_SESSION['rad_user_id']);
    if($return_data['errno'] > 0)
    {
        $message = $return_data['errmsg'] ;
    }else
    {
        $content = trim($return_data['content']);
        $json_obj = (array) json_decode($content);
        if((isset($json_obj['data'])))
        {
            $is_error = false;
            $message = 'Data found!';
            $data = $json_obj['data'];
        }else if(isset($json_obj['error']))
        {
            $message = $json_obj['error'];
        }
    }
}

if($is_error)
{
    echo json_encode(array('status'=>'fail','message'=>$message));
}else
{
    echo json_encode(array('status'=>'success','message'=>$message,'data'=>$data));
}