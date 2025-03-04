<?php 
include('../header.php');
if (!isset($_SESSION['objLogin'])) {
	header("Location: " . WEB_URL . "logout.php");
	die();
}
?>
<?php
include '../config.php';
include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_maintenance_cost_list.php');
$delinfo = 'none';
$addinfo = 'none';
$msg = "";

if (isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] > 0) {
	include('../config.php'); // Đảm bảo kết nối MySQLi có sẵn qua $conn
	$sqlx = "DELETE FROM `tbl_add_maintenance_cost` WHERE mcid = ?";
	$stmt = $conn->prepare($sqlx);
	$stmt->bind_param("i", $_GET['id']);
	$stmt->execute();
	$stmt->close();
	$delinfo = 'block';
}

if (isset($_GET['m']) && $_GET['m'] == 'add') {
	$addinfo = 'block';
	$msg = $_data['add_msg'];
}
if (isset($_GET['m']) && $_GET['m'] == 'up') {
	$addinfo = 'block';
	$msg = $_data['update_msg'];
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><?php echo $_data['add_title_text']; ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL; ?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam']; ?></a></li>
    <li class="active"><?php echo $_data['m_cost']; ?></li>
	<li class="active"><?php echo $_data['add_title_text']; ?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<div class="row">
  <div class="col-xs-12">
    <div id="me" class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-ban"></i> <?php echo $_data['delete_text']; ?> !</h4>
      <?php echo $_data['delete_msg']; ?> </div>
    <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
      <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
      <h4><i class="icon fa fa-check"></i> <?php echo $_data['success']; ?>!</h4>
      <?php echo $msg; ?> </div>
    <div align="right" style="margin-bottom:1%;">
		<a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL; ?>maintenance/add_maintenance_cost.php" data-original-title="<?php echo $_data['add_m_cost']; ?>"><i class="fa fa-plus"></i></a>
		<a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="<?php echo $_data['home_breadcam']; ?>"><i class="fa fa-dashboard"></i></a> 
	</div>
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['add_title_text']; ?></h3>
      </div>
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th><?php echo $_data['text_1']; ?></th>
              <th><?php echo $_data['date']; ?></th>
              <th><?php echo $_data['text_2']; ?></th>
              <th><?php echo $_data['text_3']; ?></th>
              <th><?php echo $_data['action_text']; ?></th>
            </tr>
          </thead>
          <tbody>
        <?php
			$sql = "SELECT * FROM tbl_add_maintenance_cost WHERE branch_id = ? ORDER BY mcid DESC";
			$stmt = $conn->prepare($sql);
			$stmt->bind_param("i", $_SESSION['objLogin']['branch_id']);
			$stmt->execute();
			$result = $stmt->get_result();

			while ($row = $result->fetch_assoc()) { ?>
				<tr>
				<td><?php echo $row['m_title']; ?></td>
				<td><?php echo $row['m_date']; ?></td>
				<td><?php echo $currency_position == 'left' ? $global_currency . $row['m_amount'] : $row['m_amount'] . $global_currency; ?></td>
				<td><?php echo $row['m_details']; ?></td>
				<td>
					<a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onclick="$('#nurse_view_<?php echo $row['mcid']; ?>').modal('show');" data-original-title="<?php echo $_data['view_text']; ?>"><i class="fa fa-eye"></i></a>
					<a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL; ?>maintenance/add_maintenance_cost.php?id=<?php echo $row['mcid']; ?>" data-original-title="<?php echo $_data['edit_text']; ?>"><i class="fa fa-pencil"></i></a>
					<a class="btn btn-danger" data-toggle="tooltip" onclick="deleteMaintenanceCost(<?php echo $row['mcid']; ?>);" href="javascript:;" data-original-title="<?php echo $_data['delete_text']; ?>"><i class="fa fa-trash-o"></i></a>
					<div id="nurse_view_<?php echo $row['mcid']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					  <div class="modal-dialog">
						<div class="modal-content">
						  <div class="modal-header orange_header">
							<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
							<h3 class="modal-title"><?php echo $_data['m_cost_details']; ?></h3>
						  </div>
						  <div class="modal-body model_view" align="center">
							<div class="model_title"><?php echo $row['m_title']; ?></div>
						  </div>
						  <div class="modal-body">
							<h3 style="text-decoration:underline;"><?php echo $_data['details_information']; ?></h3>
							<div class="row">
							  <div class="col-xs-12"> 
								<b><?php echo $_data['text_1']; ?> :</b> <?php echo $row['m_title']; ?><br/>
								<b><?php echo $_data['date']; ?> :</b> <?php echo $row['m_date']; ?><br/>
								<b><?php echo $_data['text_2']; ?> :</b> <?php echo $currency_position == 'left' ? $global_currency . $row['m_amount'] : $row['m_amount'] . $global_currency; ?><br/>
								<b><?php echo $_data['text_3']; ?> :</b> <?php echo $row['m_details']; ?><br/>
							  </div>
							</div>
						  </div>
						</div>
					  </div>
					</div>
				</td>
				</tr>
            <?php }
            $stmt->close();
            $conn->close();
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function deleteMaintenanceCost(Id) {
	var iAnswer = confirm("Are you sure you want to delete this Maintenance Cost?");
	if (iAnswer) {
		window.location = '<?php echo WEB_URL; ?>maintenance/maintenance_cost_list.php?id=' + Id;
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
