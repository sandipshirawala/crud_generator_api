<?php 
/*print("<pre>");
print_r($_POST["cmb_control_type"]);
print_r($_POST["table_selected"]);
print_r($_POST["cmb_ref_title"]);
print_r($_POST["cmb_ref_value"]);
print("</pre>");*/

?>
<h1><a href="table.php">Back</a></h1>

<br>
<br>
<!--<script type="text/javascript">
function copyToClipboard(element) {
  var $temp = $("<input>");
  $("body").append($temp);
  $temp.val($(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
  alert("Content is Copied")
}

</script>
<link href='https://fonts.googleapis.com/css?family=Oswald' rel='stylesheet' type='text/css'> 

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>

<button onclick="copyToClipboard('#copydiv')">Copy TEXT 1</button>-->
<?php 
 //include_once('head_file.php');
 ?>
<!--<div class="container well">-->
<?php 
/*print("<pre>");
print_r($_POST);
print("</pre>");
*/
$create = "";
$table_name="";

$function_name=$_POST["txt_func_name"];
$table_name=$_POST["txt_table_name"];
$primary_key=$_POST["primary_field"];
$search_field=$_POST["search_field"];

$save_func_name=$_POST["txt_save_func_name"];
$edit_func_name=$_POST["txt_edit_func_name"];
$edit_save_func_name=$_POST["txt_edit_save_func_name"];
$search_func_name=$_POST["txt_search_func_name"];
$delete_func_name=$_POST["txt_delete_func_name"];
$display_func_name=$_POST["txt_display_func_name"];
$html_page_name=$_POST["txt_html_page_name"];

$title=$_POST["txt_title"];
$controller_name=$_POST["txt_controller_name"];


$name='$name';
$like_query="\"select * from ".$table_name." where ".$search_field." like '%\".".$name.".\"%' \" ";


$create = "";
$add_list = "";
$edit_list="";
for($i=0;$i<count($_POST["field_name"]);$i++)
{
	
	//$create = $create.'$data["'.$_POST["field_name"][$i].'"]='.'$this->input->post("'.$_POST["control_name"][$i].'");';	
	$create = $create.'$'.$_POST["control_name"][$i].'='.'mysql_real_escape_string($data->'.$_POST["control_name"][$i].');';

	$last_field =count($_POST["field_name"])-1;
	if($i==$last_field)
	{
		//$add_list=$add_list.'$'.$_POST["control_name"][$i];
		//$add_list=;
		$add_list=$add_list.'\'".$'.$_POST["control_name"][$i].'."\'';
		$edit_list=$edit_list.$_POST["field_name"][$i].'='.'\'".$'.$_POST["control_name"][$i].'."\'';
	}
	else
	{
		//$add_list=$add_list.'$'.$_POST["control_name"][$i].",";
		$add_list=$add_list.'\'".$'.$_POST["control_name"][$i].'."\''.',';
		$edit_list=$edit_list.$_POST["field_name"][$i].'='.'\'".$'.$_POST["control_name"][$i].'."\''.",";
	}

	
}


/*
for($i=0;$i<count($_POST["field_name"]);$i++)
{
	echo "<br>".$_POST["field_name"][$i];
	echo "--".$_POST["control_name"][$i];
}
*/


$code = '<?php 
public function '.$function_name.'($param1="",$param2="")
{
	if($param1=="view")
	{
		  $query="select * from '.$table_name.'";
          $resultset=mysql_query($query);
          $output_data=array();
          while($row=mysql_fetch_assoc($resultset))
          {
          	$output_data[]=$row;
          }
          print json_encode($output_data);
	}
	else if($param1=="add")
	{
		$data=json_decode($param2);

		'.$create.'

		$query="insert into '.$table_name.' values(NULL,'.$add_list.')";
		mysql_query($query);
		
	}
	else if($param1=="delete")
	{
		$data=json_decode($param2);
        $pid = mysql_real_escape_string($data->id);
        $query="delete from '.$table_name.' where '.$primary_key.'=".$pid;
        mysql_query($query);
	}
	else if($param1=="edit")
	{
		$data=json_decode($param2);
        $pid = mysql_real_escape_string($data->id);
        $query="select * from '.$table_name.' where '.$primary_key.'=".$pid;
        $resultset=mysql_query($query);
        while($row=mysql_fetch_assoc($resultset))
        {
        	$output_data[]=$row;
        }
        print json_encode($output_data);
	}
	else if($param1=="do_update")
	{
		$data=json_decode($param2);
        $id=mysql_real_escape_string($data->id);
        '.$create.'
        $query="update '.$table_name.' set  
        '.$edit_list.' where '.$primary_key.'=".$id;
        mysql_query($query);

	}
	else if($param1=="search")
	{
		$data=json_decode($param2);
        $name = trim(mysql_real_escape_string($data->name));
        $query='.$like_query.';
        $resultset=mysql_query($query);
        $output_data=array();
        while($row=mysql_fetch_assoc($resultset))
        {
          $output_data[]=$row;
        }
        print json_encode($output_data);
	}
	
}
?>';

$add_code='
<div class="container">
  <center><h2 style="margin-top:0px">'.$title.' Entry</h2></center>
  <form class="form-horizontal" method="post"><input ng-model="'.$primary_key.'" type="hidden">';
for($i=0;$i<count($_POST["field_name"]);$i++)
{
	//echo "<br>".$_POST["field_name"][$i]."-".$_POST["cmb_control_type"][$i];
	$add_code=$add_code.'
	<div class="col-md-6">
      <div class="form-group">
				<label class="control-label col-sm-3" >'.$_POST["control_label"][$i].'</label>';
	if($_POST["cmb_control_type"][$i]=="Text")
	{
		//$add_code=$add_code.'<input class="form-control" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'" ng-model="'.$_POST["control_name"][$i].'">';
		$add_code=$add_code.'<div class="col-sm-9">
				<input class="form-control"  ng-model="'.$_POST["control_name"][$i].'"></div>';
	}

	if($_POST["cmb_control_type"][$i]=="Textarea")
	{
		$add_code=$add_code.'
				<textarea class="form-control" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'" rows="3"></textarea>';
	}

	if($_POST["cmb_control_type"][$i]=="Password")
	{
		$add_code=$add_code.'
				<input type="password" class="form-control" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'">';
	}

	if($_POST["cmb_control_type"][$i]=="File")
	{
		if($_POST["cmb_file"][$i]=="image")
		{
			$add_code=$add_code.'
				<input type="file" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'">';
		}
		else
		{
			$add_code=$add_code.'
				<input type="file" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'">Allowed files-('.$_POST["txt_file_extension"][$i].')';
		}
	}

	if($_POST["cmb_control_type"][$i]=="Radio")
	{
		/*$add_code=$add_code.'
				<input type="radio" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'">Yes';*/
		$add_code=$add_code.'
				<?php 
				$radio_array=array('.$_POST['radio_array'][$i].');
				for($i=0;$i<count($radio_array);$i++)
				{
					?>
					<input type="radio" id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'" value="<?php echo $radio_array[$i]; ?>"><?php echo $radio_array[$i]; ?>
					<?php
				}
				?>';
	}


	if($_POST["cmb_control_type"][$i]=="Select")
	{

		$add_code=$add_code.'
				<select class="form-control"  id="'.$_POST["control_name"][$i].'" name="'.$_POST["control_name"][$i].'">
				<?php 
				$select_res	= $this->db->get("'.$_POST["table_selected"][$i].'");
				foreach($select_res->result() as $select_row)
				{
					echo "<option value=".$select_row->'.$_POST["cmb_ref_value"][$i].'.">".$select_row->'.$_POST["cmb_ref_title"][$i].'."</option>";
				}
				?>';
		$add_code =$add_code.'
				</select>';
    }
    if($_POST["cmb_control_type"][$i]=="Checkbox")
	{
		$add_code=$add_code.'
				<?php 
				$select_res	= $this->db->get("'.$_POST["table_selected"][$i].'");
				foreach($select_res->result() as $select_row)
				{
					?>
						<div class="checkbox">
	                    	<label>
	                    		<input value="<?php echo $select_row->'.$_POST["cmb_ref_value"][$i].';?>" type="checkbox"><?php echo $select_row->'.$_POST["cmb_ref_title"][$i].';?>
	                    	</label>
                  	  </div>
                  	<?php
				}
				?>';
		
    }
	$add_code=$add_code.'
	  </div>
	</div>';	
}
$add_code=$add_code.'
		<div class="form-group">
	      <div class="col-sm-offset-2 col-sm-5">
	        <input type="button" id="btnSubmit" tabindex="2" class="btn btn-success" ng-click="'.$save_func_name.'()" value="Add '.$title.' Data" style="display:{{addbutton}};">
	        <input type="button" id="btnSubmit" tabindex="2"  class="btn btn-success" ng-click="'.$edit_save_func_name.'()" value="Update '.$title.' Data" style="display:{{updatebutton}};">

	      </div>
	      <div class="col-sm-5">
	        <input type="text" class="form-control" ng-model="search" placeholder="Search Any '.$search_field.' Name" ng-keyup="'.$search_func_name.'();">
	      </div>
	    </div>
  </form>';
  $add_code =$add_code.'
  <div class="scrollable">
    <table cellpadding="0" cellspacing="0" border="0" class="table table-bordered  table-fixed" id="example">
        <thead>';
          for($i=0;$i<count($_POST["field_name"]);$i++)
			{
				$add_code=$add_code.'
				<th>'.$_POST["control_label"][$i].'</th>';
			}
			$add_code=$add_code.'
		    	<th>Action</th>
		</thead>';
		$add_code=$add_code.'
		<tbody>
			<tr class="odd gradeX" ng-repeat="'.$title.' in '.$title.'_data">';
		for($i=0;$i<count($_POST["field_name"]);$i++)
        {
        	
	        //$add_code=$add_code.'<td ng-click="edit'.$title.'('.$title.'.'.$primary_key.');">{{'.$title.'.'.$_POST["field_name"][$i].'}}</td>';
    		$add_code=$add_code.'
    		<td ng-click="'.$edit_func_name.'('.$title.'.'.$primary_key.');">{{'.$title.'.'.$_POST["field_name"][$i].'}}</td>';
    	   
        }
        //$add_code=$add_code.'<td><a href ng-click="delete'.$title.'('.$title.'.'.$primary_key.')">Delete</a></td>';
        $add_code=$add_code.'
        	<td><a href ng-click="'.$delete_func_name.'('.$title.'.'.$primary_key.')">Delete</a></td>';
        $add_code=$add_code.'
        	</tr>
        </tbody>
    </table>
  </div>';
$add_code=$add_code.'
</div>';

$js_code='app.controller("'.$controller_name.'",function ($scope, $http, $rootScope , DBService) 
{

     tabindexfunction();
	 console.log("'.$title.' Controller loaded");
     $scope.addbutton="block";
     $scope.updatebutton="none";


      $scope.'.$search_func_name.'=function()
      {
        var form_data=new FormData();
        var data = {"name":$scope.search};
        form_data.append("data",JSON.stringify(data));
        $http.post(api_url+"'.$function_name.'/search",form_data,{ headers: {"Content-Type": undefined,"Process-Data": false} })
        .success(function(response)
        {
              $scope.'.$title.'_data=response;
        })
      }

      $scope.'.$edit_func_name.'=function('.$primary_key.')
      {
        var form_data=new FormData();
        var data = {"id":'.$primary_key.'};
        form_data.append("data",JSON.stringify(data));
        $http.post(api_url+"'.$function_name.'/edit",form_data,{ headers: {"Content-Type": undefined,"Process-Data": false} })
        .success(function(response)
        {
              $scope.'.$title.'_data_edit=response;
              angular.forEach($scope.'.$title.'_data_edit,function(value,key){';
                /*$scope.dose_id=value.dose_id;
                $scope.dose_name=value.dose_name;
                $scope.dose_description=value.dose_description;
                $scope.dose_description2=value.dose_description2;
                $scope.dose_med_qty=value.dose_med_qty;*/
                $js_code=$js_code.'
                	$scope.'.$primary_key.'=value.'.$primary_key.";";
                for($i=0;$i<count($_POST["field_name"]);$i++)
				{
					$js_code=$js_code.'
					$scope.'.$_POST["control_name"][$i].'=value.'.$_POST["control_name"][$i].';';
				}

				$js_code=$js_code.'
				});
              $scope.addbutton="none";
              $scope.updatebutton="block";
        })
      }

      $scope.'.$delete_func_name.'=function('.$primary_key.')
      {
      		var form_data=new FormData();
      		var data = {"id":'.$primary_key.'};
      		form_data.append("data",JSON.stringify(data));
          $http.post(api_url+"'.$function_name.'/delete",form_data,{ headers: {"Content-Type": undefined,"Process-Data": false} })
      		.success(function(response)
      		{
                $scope.'.$display_func_name.'();
      		})
      }

      $scope.clear_data=function()
      {';
      	for($i=0;$i<count($_POST["field_name"]);$i++)
		{
			$js_code=$js_code.'
			$scope.'.$_POST["control_name"][$i].'=null;';
		}
      $js_code=$js_code.'
  	}

  	$scope.'.$edit_save_func_name.'=function()
      {
    		var form_data =new FormData();
    		var data = {
          	  "id":$scope.'.$primary_key.',';
          /*'dose_name':$scope.dose_name,
          'dose_med_qty':$scope.dose_med_qty,
          'dose_description':$scope.dose_description,
          'dose_description2':$scope.dose_description2,*/

          for($i=0;$i<count($_POST["field_name"]);$i++)
			{
				if($i==(count($_POST["field_name"])-1))
				{
					$js_code=$js_code.'
				"'.$_POST["control_name"][$i].'":$scope.'.$_POST["control_name"][$i];
				}
				else
				{
					$js_code=$js_code.'
				"'.$_POST["control_name"][$i].'":$scope.'.$_POST["control_name"][$i].',';
				}
				
			}
        $js_code=$js_code.'
    		};
    		form_data.append("data",JSON.stringify(data));
        	$http.post(api_url+"'.$function_name.'/do_update",form_data,{ headers: {"Content-Type": undefined,"Process-Data": false} })
        	.success(function(response)
    		{
    			$scope.addbutton="block";
    			$scope.updatebutton="none";
    			$scope.clear_data();
    			$scope.'.$display_func_name.'();
    		})
   	}
   	$scope.'.$display_func_name.'=function()
   	{
          var form_data=new FormData();
            var data={
              "uid":"1"
            };
            form_data.append("data",JSON.stringify(data));
            $http.post(api_url+"'.$function_name.'/view",form_data,{ headers: {"Content-Type": undefined,"Process-Data": false} })
            .success(function(response)
            {
              $scope.'.$title.'_data=response;
            })
      }
	  $scope.'.$save_func_name.'=function()
	  {
	      var form_data=new FormData();
	      var data={';
	      for($i=0;$i<count($_POST["field_name"]);$i++)
		  {
				if($i==(count($_POST["field_name"])-1))
				{
					$js_code=$js_code.'
					"'.$_POST["control_name"][$i].'":$scope.'.$_POST["control_name"][$i];
				}
				else
				{
					$js_code=$js_code.'
					"'.$_POST["control_name"][$i].'":$scope.'.$_POST["control_name"][$i].',';
				}
		  }
	      $js_code=$js_code.'
	    	};
	        form_data.append("data",JSON.stringify(data));
	        $http.post(api_url+"'.$function_name.'/add",form_data,{ headers: {"Content-Type": undefined,"Process-Data": false} })
	        .success(function(response)
	        {
	          $scope.message="'.$title.' Data Saved Successfully";
	          $scope.clear_data();
	          $scope.'.$display_func_name.'();
	        })
	  }
	  $scope.'.$display_func_name.'();
});
';

$app_router='.state("'.$html_page_name.'", {
        url: "/'.$html_page_name.'",
        templateUrl: "views/'.$html_page_name.'.html",
        controller: "'.$controller_name.'",
        controllerAs: "ctrl"
      })';
/*
//echo $create;
$table_name=$_POST["txt_table_name"];
$primary_field=$_POST["primary_field"];
$page_name=$_POST["txt_view_page_name"];
$page_title=$_POST["txt_view_page_title"];

$edit_js_func_name=$_POST["txt_edit_js_func_name"];

$title=$_POST["txt_title"];


$join_query='';
for($i=0;$i<count($_POST["cmb_control_type"]);$i++)
{
	if($_POST["cmb_control_type"][$i]=="Select")
	{
		$refer_table = $_POST["table_selected"][$i];
		$join_query=$join_query.'$this->db->join("'.$refer_table.'","'.$table_name.'.'.$_POST["field_name"][$i].'='.$refer_table.'.'.$_POST["cmb_ref_value"][$i].'");';
	}
}




	


$code = '<?php 
public function '.$function_name.'($param1="",$param2="",$param3="")
{
	if($param1=="create")
	{
		'.$create.'
		$this->db->insert("'.$table_name.'",$data);
		redirect(base_url()."admin/'.$function_name.'");
	}
	if($param1=="edit" && $param2=="do_update")
	{
		'.$create.'
		$this->db->where("'.$primary_field.'",$param3);
		$this->db->update("'.$table_name.'",$data);
		redirect(base_url()."admin/'.$function_name.'");
	}
	else if($param1=="edit")
	{
		$page_data["edit_profile"]=$this->db->get_where("'.$table_name.'",array("'.$primary_field.'"=>$param2));
	}
	if($param1=="delete")
	{
		$this->db->where("'.$primary_field.'",$param2);
		$this->db->delete("'.$table_name.'");
		redirect(base_url()."admin/'.$function_name.'");
	}

	$per_page=$_SESSION["per_page"];
	$this->db->limit($per_page,$param1);
	'.$join_query.'
	$page_data["resultset"]=$this->db->get("'.$table_name.'");

	$resultset=$this->db->get("'.$table_name.'");
	$total_rows=$resultset->num_rows();
	$page_data["paging_string"]=$this->paging_init("'.$function_name.'",$total_rows,$per_page);


	$page_data["start_position"]=intval($param1)+1;	
	$page_data["page_title"]="'.$page_title.'";
	$page_data["page_name"]="'.$page_name.'";

	$this->load->view("admin/index",$page_data);
}
?>';

*/

//highlight_string($code);


// CONTROLLER PART COMPLETED END


//echo "<center><h1>Add View Page Code</h1></center>";



?>
<!--</div>-->

<center><h1>API</h1></center>
<?php highlight_string($code); ?>
<center><h1>View HTML page Coding</h1></center>

<?php highlight_string($add_code); ?>
<center><h1>APP.JS CONTROLLER</h1></center>

<?php highlight_string($js_code); ?>

<center><h1>APP.JS ROUTER</h1></center>

<?php highlight_string($app_router); ?>

<!--<br><br><br>
<center><h1>Edit page Coding - For AJAX Page</h1></center>
<?php highlight_string($edit_code); ?>
<br><br><br>

<center><h1>Add & List page Coding(Select All the Part)</h1></center>
<?php 
//highlight_string($add_code); 
//highlight_string($list_code);
?>
<br><br><br>-->