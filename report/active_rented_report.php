<?php 
include('../header.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}

include '../config.php';
?>
<section class="content-header">
  <h1> Active Rented List </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
    <li class="active">Active Rented List</li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <div align="right" style="margin-bottom:1%;"><a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL; ?>report.php" data-original-title="Report"><i class="fa fa-dashboard"></i></a> </div>
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title">Active Rented List</h3> 
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th>Image</th>
              <th>Tenant's Name</th>
              <th>Contact</th>
              <th>Unit No</th>
              <th>Advance Rent</th>
              <th>Rent Per Month</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
        <?php
$result = $conn->query("SELECT *, f.floor_no AS ffloor, u.unit_no FROM tbl_add_rent r 
INNER JOIN tbl_add_floor f ON f.fid = r.r_floor_no 
INNER JOIN tbl_add_unit u ON u.uid = r.r_unit_no 
WHERE r.r_status = '1' AND r.branch_id = '" . (int)$_SESSION['objLogin']['branch_id'] . "' 
ORDER BY r.rid DESC");

            while($row = $result->fetch_assoc()){
                $image = WEB_URL . 'img/no_image.jpg';    
                if(file_exists(ROOT_PATH . '/img/upload/' . $row['image']) && $row['image'] != ''){
                    $image = WEB_URL . 'img/upload/' . $row['image'];
                }
        ?>
            <tr>
                <td><img class="photo_img_round" style="width:50px;height:50px;" src="<?php echo $image; ?>" /></td>
                <td><?php echo $row['r_name']; ?></td>
                <td><?php echo $row['r_contact']; ?></td>
                <td><?php echo $row['unit_no']; ?></td>
                <?php if($currency_position == 'left') { ?>
                <td><?php echo $global_currency . $row['r_advance']; ?></td>
                <?php } else { ?>
                <td><?php echo $row['r_advance'] . $global_currency; ?></td>
                <?php } ?>
                <?php if($currency_position == 'left') { ?>
                <td><?php echo $global_currency . $row['r_rent_pm']; ?></td>
                <?php } else { ?>
                <td><?php echo $row['r_rent_pm'] . $global_currency; ?></td>
                <?php } ?>
                <td><?php echo ($row['r_status'] == '1') ? 'Active' : 'Expired'; ?></td>
                <td>
                    <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onclick="$('#nurse_view_<?php echo $row['rid']; ?>').modal('show');" data-original-title="View"><i class="fa fa-eye"></i></a>
                    <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL;?>rent/addrent.php?id=<?php echo $row['rid']; ?>" data-original-title="Edit"><i class="fa fa-pencil"></i></a>
                    <a class="btn btn-danger" data-toggle="tooltip" onclick="deleteRent(<?php echo $row['rid']; ?>);" href="javascript:;" data-original-title="Delete"><i class="fa fa-trash-o"></i></a>
                    <div id="nurse_view_<?php echo $row['rid']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header orange_header">
                                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                                    <h3 class="modal-title">Rented Details</h3>
                                </div>
                                <div class="modal-body model_view" align="center">&nbsp;
                                    <div><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image; ?>" /></div>
                                    <div class="model_title"><?php echo $row['r_name']; ?></div>
                                </div>
                                <div class="modal-body">
                                    <h3 style="text-decoration:underline;">Details Information</h3>
                                    <div class="row">
                                        <div class="col-xs-12"> 
                                            <b>Tenant's Name :</b> <?php echo $row['r_name']; ?><br/>
                                            <b>Email :</b> <?php echo $row['r_email']; ?><br/>
                                            <b>Contact :</b> <?php echo $row['r_contact']; ?><br/>
                                            <b>Address :</b> <?php echo $row['r_address']; ?><br/>
                                            <b>NID(National ID) :</b> <?php echo $row['r_nid']; ?><br/>
                                            <b>Floor No :</b> <?php echo $row['ffloor']; ?><br/>
                                            <b>Unit No :</b> <?php echo $row['unit_no']; ?><br/>
                                            <b>Advance Rent :</b> <?php echo ($currency_position == 'left') ? $global_currency . $row['r_advance'] : $row['r_advance'] . $global_currency; ?><br/>
                                            <b>Rent Per Month :</b> <?php echo ($currency_position == 'left') ? $global_currency . $row['r_rent_pm'] : $row['r_rent_pm'] . $global_currency; ?><br/>
                                            <b>Rent Start Date :</b> <?php echo $row['r_date']; ?><br/>
                                            <b>Status :</b> <?php echo ($row['r_status'] == '1') ? 'Active' : 'Expired'; ?><br/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- /.modal-content -->
                        </div>
                    </div>
                </td>
            </tr>
        <?php 
            } 
            $conn->close(); 
        ?>
          </tbody>
          <tfoot>
            <tr>
              <th>Image</th>
              <th>Tenant's Name</th>
              <th>Contact</th>
              <th>Floor No</th>
              <th>Unit No</th>
              <th>Advance Rent</th>
              <th>Rent Per Month</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </tfoot>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->
<script type="text/javascript">
function deleteRent(Id){
  	var iAnswer = confirm("Are you sure you want to delete this Rent?");
	if(iAnswer){
		window.location = '<?php echo WEB_URL; ?>rent/rentlist.php?id=' + Id;
	}
}
  
$(document).ready(function() {
	setTimeout(function() {
		  $("#me").hide(300);
		  $("#you").hide(300);
	}, 3000);
});
</script>
<?php include('../footer.php'); ?>
