<?php 
include('../header.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
?>
<?php
include '../config.php';
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_rented_list.php');
$delinfo = 'none';
$addinfo = 'none';
$msg = "";

// Check if an ID is provided for deletion
if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0){
	$stmt = $conn->prepare("DELETE FROM `tbl_add_rent` WHERE rid = ?");
	$stmt->bind_param("i", $_GET['id']);
	$stmt->execute();
  $stmt->close();
	$delinfo = 'block';
}

// Handle success messages
if(isset($_GET['m']) && $_GET['m'] == 'add'){
	$addinfo = 'block';
	$msg = $_data['added_renter_successfully'];
}
if(isset($_GET['m']) && $_GET['m'] == 'up'){
	$addinfo = 'block';
	$msg = $_data['update_renter_successfully'];
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?php echo $_data['renter_list'];?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><?php echo $_data['add_new_renter_information_breadcam'];?></li>
	<li class="active"><?php echo $_data['renter_list'];?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <div id="me" class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-ban"></i><?php echo $_data['delete_text'];?> !</h4>
      <?php echo $_data['delete_renter_information'];?> 
    </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> <?php echo $_data['success'];?> !</h4>
      <?php echo $msg; ?> 
    </div>
    <div align="right" style="margin-bottom:1%;"> 
      <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL; ?>rent/addrent.php" data-original-title="<?php echo $_data['add_new_rent_breadcam'];?>"><i class="fa fa-plus"></i></a> 
      <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="<?php echo $_data['home_breadcam'];?>"><i class="fa fa-dashboard"></i></a> 
    </div>
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['renter_list'];?></h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th><?php echo $_data['add_new_form_field_text_0'];?></th>
              <th><?php echo $_data['add_new_form_field_text_1'];?></th>
              <th><?php echo $_data['add_new_form_field_text_4'];?></th>
              <th><?php echo $_data['add_new_form_field_text_8'];?></th>
              <th><?php echo $_data['add_new_form_field_text_9'];?></th>
              <th><?php echo $_data['add_new_form_field_text_10'];?></th>
              <th><?php echo $_data['add_new_form_field_text_14'];?></th>
              <th><?php echo $_data['action_text'];?></th>
            </tr>
          </thead>
          <tbody>
            <?php
				$stmt = $conn->prepare("SELECT *, f.floor_no AS ffloor, u.unit_no FROM tbl_add_rent r 
                                        INNER JOIN tbl_add_floor f ON f.fid = r.r_floor_no 
                                        INNER JOIN tbl_add_unit u ON u.uid = r.r_unit_no 
                                        WHERE r.branch_id = ? 
                                        ORDER BY r.r_unit_no ASC");
				$stmt->bind_param("i", $_SESSION['objLogin']['branch_id']);
				$stmt->execute();
				$result = $stmt->get_result();

				while($row = $result->fetch_assoc()){
					$image = WEB_URL . 'img/no_image.jpg';	
					if(file_exists(ROOT_PATH . '/img/upload/' . $row['image']) && $row['image'] != ''){
						$image = WEB_URL . 'img/upload/' . $row['image'];
					}
				?>
            <tr>
              <td><img class="photo_img_round" style="width:50px;height:50px;" src="<?php echo $image;  ?>" /></td>
              <td><?php echo $row['r_name']; ?></td>
              <td><?php echo $row['r_contact']; ?></td>
              <td><?php echo $row['unit_no']; ?></td>
              <td><?php echo ($currency_position == 'left') ? $global_currency . $row['r_advance'] : $row['r_advance'] . $global_currency; ?></td>
              <td><?php echo ($currency_position == 'left') ? $global_currency . $row['r_rent_pm'] : $row['r_rent_pm'] . $global_currency; ?></td>
              <td><?php echo ($row['r_status'] == '1') ? $_data['add_new_form_field_text_16'] : $_data['add_new_form_field_text_17']; ?></td>
              <td>
                <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['rid']; ?>').modal('show');" data-original-title="<?php echo $_data['view_text'];?>"><i class="fa fa-eye"></i></a> 
                <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL;?>rent/addrent.php?id=<?php echo $row['rid']; ?>" data-original-title="<?php echo $_data['edit_text'];?>"><i class="fa fa-pencil"></i></a> 
                <a class="btn btn-danger" data-toggle="tooltip" onClick="deleteRent(<?php echo $row['rid']; ?>);" href="javascript:;" data-original-title="<?php echo $_data['delete_text'];?>"><i class="fa fa-trash-o"></i></a>
                
                <div id="nurse_view_<?php echo $row['rid']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header orange_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                        <h3 class="modal-title"><?php echo $_data['rented_details'];?></h3>
                      </div>
                      <div class="modal-body model_view" align="center">&nbsp;
                        <div><img style="width:200px;height:200px;border-radius:200px;" src="<?php echo $image;  ?>" /></div>
                        <div class="model_title"><?php echo $row['r_name']; ?></div>
                      </div>
                      <div class="modal-body">
                        <h3 style="text-decoration:underline;"><?php echo $_data['details_information'];?></h3>
                        <div class="row">
                          <div class="col-xs-6"> 
                            <b><?php echo $_data['add_new_form_field_text_1'];?> :</b> <?php echo $row['r_name']; ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_2'];?> :</b> <?php echo $row['r_email']; ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_3'];?> :</b> <?php echo $row['r_password']; ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_4'];?> :</b> <?php echo $row['r_contact']; ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_5'];?> :</b> <?php echo $row['r_address']; ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_6'];?> :</b> <?php echo $row['r_nid']; ?><br/>
                          </div>
                          <div class="col-xs-6"> 
                            <b><?php echo $_data['add_new_form_field_text_7'];?> :</b> <?php echo $row['ffloor']; ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_8'];?> :</b> <?php echo $row['unit_no']; ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_9'];?> :</b> <?php echo ($currency_position == 'left') ? $global_currency . $row['r_advance'] : $row['r_advance'] . $global_currency; ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_10'];?> :</b> <?php echo ($currency_position == 'left') ? $global_currency . $row['r_rent_pm'] : $row['r_rent_pm'] . $global_currency; ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_11'];?> :</b> <?php echo ($row['r_status'] == '1') ? $_data['add_new_form_field_text_16'] : $_data['add_new_form_field_text_17']; ?><br/>
                            <b><?php echo $_data['add_new_form_field_text_12']?> :</b> <?php echo $row['added_date'] ?? ""; ?><br/>
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button aria-hidden="true" data-dismiss="modal" class="btn btn-default" type="button"><?php echo $_data['close_text'] ?? "Đóng";?></button>
                      </div>
                    </div>
                  </div>
                </div>
              </td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->
</section>
<!-- /.content -->
<?php include('../footer.php');?>
<script type="text/javascript">
function deleteRent(id){
    if(confirm("Are you sure you want to delete this renter?")){
        window.location = '<?php echo WEB_URL;?>rent/rentlist.php?id=' + id;
    }
}
</script>
