<?php
session_start();
if(isset($_GET['logout']))
{
    unset($_SESSION['rad_user_id']);
    $_SESSION['rad_user_login'] = false;
    header('Location: index.php');
    exit;
}

if(!isset($_SESSION['rad_user_id'])){ $_SESSION['rad_user_id'] = 0;}
if(!isset($_SESSION['rad_user_login'])){ $_SESSION['rad_user_login'] = false;}

$user_id = $_SESSION['rad_user_id'];
$is_user_login = (bool) $_SESSION['rad_user_login'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    
	<title>Test</title>
    
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous" />
    
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous" />
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-default">
          <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="#">Test</a>
            </div>
        
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              
              <?php
              if($is_user_login){
              ?>
                <ul class="nav navbar-nav">
                    <li class="active"><a href="index.php">Works</a></li>
                  </ul>
              
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="index.php?logout=1">Logout</a></li>
                  </ul>
              <?php
              }
              ?>
                  
            </div><!-- /.navbar-collapse -->
          </div><!-- /.container-fluid -->
        </nav>
    </div>
    
    
    <?php
    if($is_user_login)
    {
    ?>
        <div class="container">
            
            <div id="main_error_msg" class="text-danger text-center"></div>
            
                    <!-- Modal -->
                    <div class="modal fade" id="addWork" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="myModalLabel">Add New Work</h4>
                          </div>
                          
                          <form class="form-horizontal" method="post" onsubmit="return false;">
                          
                          <div class="modal-body">
                            <div id="success_msg" class="text-success text-center"></div>
                            <div id="error_msg" class="text-danger text-center"></div>
                            <input type="hidden" name="user_id" value="<?php echo $user_id;?>" />
                            <input type="hidden" name="id" value="0" />
                            
                              <div class="form-group">
                                <label for="workname" class="col-sm-2 control-label">Name</label>
                                <div class="col-sm-10">
                                  <input type="text" name="workname" class="form-control" id="workname" placeholder="Name" />
                                </div>
                              </div>
                              
                              <div class="form-group">
                                <label for="workscore" class="col-sm-2 control-label">Score</label>
                                <div class="col-sm-10">
                                    <select name="workscore" class="form-control">
                                        <?php
                                        for($i = 0; $i<=10; $i++)
                                        {
                                        ?>
                                            <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                              </div>
                            
                          </div>
                          <div class="modal-footer">
                            <button type="button" name="save_work" class="btn btn-primary">Submit</button>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>                            
                          </div>
                          </form>
                        </div>
                      </div>
                    </div>
                    
                    <div class="col-xs-12 col-md-12 text-right" style="margin-bottom: 10px;">
                        <button type="button" class="btn btn-primary btn-md" data-toggle="modal" data-target="#addWork">Add new work</button>
                    </div>
                        
                    
                    <table class="table table-striped table-bordered table-hover" id="work_data">
                        <thead>
                            <tr>
                                <td>Work Name</td>
                                <td>Work Score</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        
                        <tbody>
                            <tr></tr>
                        </tbody>
                    </table> 
        </div>
        
        <script type="text/javascript">
                    var ajax_obj;
                    var is_edit = false;
                    $(document).ready(function(){
                        $("table#work_data tbody").html('<tr><td colspan="3" align="center">Processing...!</td></tr>');
                        $.ajax({
                            type:'POST',
                                    data:{},
                                    url:'ajax.php?type=getwork',
                                    success:function(data){
                                        var obj = $.parseJSON(data);
                                        
                                        if(obj.status == 'fail')
                                        {
                                            $("#main_error_msg").html(obj.message);
                                        }else
                                        {
                                            var main_data = obj.data;
                                            
                                            if(main_data.length > 0)
                                            {
                                                var html = '';
                                                
                                                $.each(main_data,function(index,value){
                                                    html += '<tr>';
                                                        html += '<td id="name_'+value['work_id']+'">'+value['work_name']+'</td>';
                                                        html += '<td id="score_'+value['work_id']+'">'+value['work_score']+'</td>';
                                                        html += '<td><a href="#" onclick="return edit_func('+value['work_id']+',this);">Edit</a></td>';
                                                    html += '</tr>';
                                                });
                                                
                                                $("table#work_data tbody").html(html);
                                            }else
                                            {
                                                $("table#work_data tbody").html('<tr><td colspan="3" align="center">No record found!</td></tr>');
                                            }
                                        }
                                    },
                                    error:function(e){
                                        $("#main_error_msg").html('Unexpected error!');
                                    }
                        });
                        
                        $('#addWork').on('shown.bs.modal', function () {
                            $('[name=rad_name]').focus();
                        });
                        
                        $('#addWork').on('hidden.bs.modal', function () {
                            $("#addWork #myModalLabel").html("Add New Work");
                            $("#addWork [name=save_work]").html('Submit');
                            $("#addWork [name=save_work]").removeAttr('disabled');
                            $("#addWork #error_msg").html('');
                            $("#addWork #success_msg").html('');
                            $("#addWork [name=workname]").val('');
                            $("#addWork [name=workscore]").val(0);
                            $("#addWork [name=id]").val(0);
                            if(ajax_obj){ajax_obj.abort();}
                            is_edit = false;
                            window.location.reload();
                        });

                        $("#addWork [name=save_work]").click(function(){
                            $(this).html('Submitting....');
                            $(this).attr('disabled','disabled');
                            save_work();
                            return false;
                        });
                        
                        
                        $("#editVendor [name=update_vendor]").click(function(){
                            $(this).html('Updating....');
                            $(this).attr('disabled','disabled');
                            save_work();
                            return false;
                        });
                    });
                    
                    function save_work()
                    {
                        $("#addWork #error_msg").html('');
                        $("#addWork #success_msg").html('');
                        var frm = $('#addWork').find('form').serialize();
                        
                        ajax_obj = $.ajax({
                                    type:'POST',
                                    data:frm,
                                    url:'ajax.php?type=addwork',
                                    success:function(data){
                                        var obj = $.parseJSON(data);
                                        
                                        if(obj.status == 'fail')
                                        {
                                            $("#addWork #error_msg").html(obj.message);
                                            $("#addWork [name=save_work]").html('Submit');
                                            $("#addWork [name=save_work]").removeAttr('disabled');
                                        }else
                                        {
                                            if(!is_edit)
                                            {
                                                $("#addWork [name=workname]").val('');
                                                $("#addWork [name=workscore]").val(0);
                                                $("#addWork [name=id]").val(0);
                                            }
                                            
                                            $("#addWork #success_msg").html(obj.message);
                                            $("#addWork [name=save_work]").html('Submit');
                                            $("#addWork [name=save_work]").removeAttr('disabled');                                            
                                        }
                                    },
                                    error:function(e){
                                        $("#addWork #error_msg").html('Unexpected error!');
                                        $("#addWork [name=save_work]").html('Submit');
                                        $("#addWork [name=save_work]").removeAttr('disabled');
                                    }
                                });
                    }
                    
                    function edit_func(id,p_obj)
                    {
                        var name = $("#name_"+id).html().trim();
                        var score = $("#score_"+id).html().trim();
                        
                        $("#addWork [name=id]").val(id);
                        $("#addWork [name=workname]").val(name);
                        $("#addWork [name=workscore]").val(score);
                        is_edit = true;
                        
                        $("#addWork #myModalLabel").html("Update "+name);
                        
                        $('#addWork').modal('show');
                        
                        return false;
                    }
        </script>
    <?php  
    }else
    {
    ?>
        <div class="container">
            <div id="error_msg" class="text-danger text-center"></div>
            <form class="form-horizontal" method="post" id="login_form" onsubmit="return false;">
              <div class="form-group">
                <label for="inputUsername" class="col-sm-2 control-label">Username</label>
                <div class="col-sm-10">
                  <input type="text" name="username" class="form-control" id="inputUsername" placeholder="Username" />
                </div>
              </div>
              
              <div class="form-group">
                <label for="inputPassword3" class="col-sm-2 control-label">Password</label>
                <div class="col-sm-10">
                  <input type="password" name="password" class="form-control" id="inputPassword3" placeholder="Password" />
                </div>
              </div>
              
              <div class="form-group">
                <div class="col-sm-offset-2 col-sm-10">
                  <button id="login_btn" class="btn btn-default">Login</button>
                </div>
              </div>
            </form>
        </div>
        
        <script type="text/javascript">
            $(document).ready(function(){
                $("#login_btn").click(function(){
                    
                    $("#error_msg").html('');
                    $.ajax({
                        type:'POST',
                        url:'ajax.php?type=login',
                        data:$("form#login_form").serializeArray(),
                        success:function(data){
                            var obj = $.parseJSON(data);
                            
                            if(obj.status == 'fail')
                            {
                                $("#error_msg").html(obj.message);
                            }else
                            {
                                window.location.reload();
                            }
                        },
                        error:function(e){
                            $("#error_msg").html('Unexpected error occured!');
                        }
                    });
                    return false;
                });
            });
        </script>
    <?php
    }
    ?>

</body>
</html>