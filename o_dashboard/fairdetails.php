<?php 
include('../config.php'); // Thêm vào đây
include('../header_owner.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_owner_fare_details.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_common.php');

if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    die();
}
?>

<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><?php echo $_data['text_1']; ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL ?>o_dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home_breadcam']; ?></a></li>
    <li class="active"><?php echo $_data['text_1']; ?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <div align="right" style="margin-bottom:1%;">
      <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL; ?>o_dashboard.php" data-original-title="<?php echo $_data['owner_dashboard']; ?>">
        <i class="fa fa-dashboard"></i>
      </a>
    </div>
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['text_1']; ?></h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th><?php echo $_data['image']; ?></th>
              <th><?php echo $_data['text_2']; ?></th>
              <th><?php echo $_data['text_3']; ?></th>
              <th><?php echo $_data['text_4']; ?></th>
              <th><?php echo $_data['text_5']; ?></th>
              <th><?php echo $_data['text_6']; ?></th>
              <th><?php echo $_data['text_7']; ?></th>
              <th><?php echo $_data['text_8']; ?></th>
              <th><?php echo $_data['action_text']; ?></th>
            </tr>
          </thead>
          <tbody>
        	<?php
            // Sử dụng MySQLi thay vì MySQL
            $result = $conn->query("SELECT *, r.r_name, r.image, fl.floor_no AS fl_floor, u.unit_no AS u_unit, m.month_name FROM tbl_add_fair f INNER JOIN tbl_add_owner_unit_relation our ON f.unit_no = our.unit_id INNER JOIN tbl_add_rent r ON r.r_unit_no = f.unit_no INNER JOIN tbl_add_floor fl ON fl.fid = f.floor_no INNER JOIN tbl_add_unit u ON u.uid = f.unit_no INNER JOIN tbl_add_month_setup m ON m.m_id = f.month_id WHERE our.owner_id = '". (int)$_SESSION['objLogin']['ownid'] . "' ORDER BY f.f_id DESC");

            while ($row = $result->fetch_assoc()) {
                $image = WEB_URL . 'img/no_image.jpg';    
                if (file_exists(ROOT_PATH . '/img/upload/' . $row['image']) && $row['image'] != '') {
                    $image = WEB_URL . 'img/upload/' . $row['image'];
                }
            ?>
            <tr>
            <td><img class="photo_img_round" style="width:50px;height:50px;" src="<?php echo $image; ?>" /></td>
            <td><?php echo htmlspecialchars($row['r_name'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['fl_floor'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['u_unit'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['month_name'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td><?php echo htmlspecialchars($row['rent'], ENT_QUOTES, 'UTF-8') . ' ' . CURRENCY; ?></td>
            <td><?php echo htmlspecialchars($row['total_rent'], ENT_QUOTES, 'UTF-8') . ' ' . CURRENCY; ?></td>
            <td><?php echo htmlspecialchars($row['issue_date'], ENT_QUOTES, 'UTF-8'); ?></td>
            <td>
            <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onClick="$('#nurse_view_<?php echo $row['f_id']; ?>').modal('show');" data-original-title="<?php echo $_data['view_text']; ?>">
                <i class="fa fa-eye"></i>
            </a>
            <div id="nurse_view_<?php echo $row['f_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header orange_header">
                    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                    <h3 class="modal-title"><?php echo $_data['text_1']; ?></h3>
                  </div>
                  <div class="modal-body model_view" align="center">&nbsp;
                    <div><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image; ?>" /></div>
                    <div class="model_title"><?php echo htmlspecialchars($row['r_name'], ENT_QUOTES, 'UTF-8'); ?></div>
                  </div>
				  <div class="modal-body">
                    <h3 style="text-decoration:underline;"><?php echo $_data['details_information']; ?></h3>
                    <div class="row">
                      <div class="col-xs-6"> 
					    <b><?php echo $_data['text_2'];?> :</b> <?php echo htmlspecialchars($row['r_name'], ENT_QUOTES, 'UTF-8'); ?><br/>
                        <b><?php echo $_data['text_3'];?> :</b> <?php echo htmlspecialchars($row['fl_floor'], ENT_QUOTES, 'UTF-8'); ?><br/>
                        <b><?php echo $_data['text_4'];?> :</b> <?php echo htmlspecialchars($row['u_unit'], ENT_QUOTES, 'UTF-8'); ?><br/>
                        <b><?php echo $_data['text_5'];?> :</b> <?php echo htmlspecialchars($row['month_name'], ENT_QUOTES, 'UTF-8'); ?><br/>
                        <b><?php echo $_data['text_6'];?> :</b> <?php echo htmlspecialchars($row['rent'], ENT_QUOTES, 'UTF-8') . ' ' . CURRENCY; ?><br/>
                        <b><?php echo $_data['text_9'];?> :</b> <?php echo htmlspecialchars($row['water_bill'], ENT_QUOTES, 'UTF-8') . ' ' . CURRENCY; ?><br/>
                        <b><?php echo $_data['text_10'];?> :</b> <?php echo htmlspecialchars($row['electric_bill'], ENT_QUOTES, 'UTF-8') . ' ' . CURRENCY; ?><br/>
                        </div>
                        <div class="col-xs-6">
                        <b><?php echo $_data['text_11'];?> :</b> <?php echo htmlspecialchars($row['gas_bill'], ENT_QUOTES, 'UTF-8') . ' ' . CURRENCY; ?><br/>
                        <b><?php echo $_data['text_12'];?> :</b> <?php echo htmlspecialchars($row['security_bill'], ENT_QUOTES, 'UTF-8') . ' ' . CURRENCY; ?><br/>
                        <b><?php echo $_data['text_13'];?> :</b> <?php echo htmlspecialchars($row['utility_bill'], ENT_QUOTES, 'UTF-8') . ' ' . CURRENCY; ?><br/>
                        <b><?php echo $_data['text_14'];?> :</b> <?php echo htmlspecialchars($row['other_bill'], ENT_QUOTES, 'UTF-8') . ' ' . CURRENCY; ?><br/>
                        <b><?php echo $_data['text_15'];?> :</b> <?php echo htmlspecialchars($row['total_rent'], ENT_QUOTES, 'UTF-8') . ' ' . CURRENCY; ?><br/>
                        <b><?php echo $_data['text_8'];?> :</b> <?php echo htmlspecialchars($row['issue_date'], ENT_QUOTES, 'UTF-8'); ?><br/>
                      </div>
                    </div>
                  </div>
				  
                </div>
                <!-- /.modal-content -->
              </div>
            </div>
            </td>
            </tr>
            <?php } $conn->close(); ?>
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
<script type="text/javascript">
function deleteFair(Id) {
  	var iAnswer = confirm("Are you sure, you want to delete this Fair?");
	if (iAnswer) {
		window.location = '<?php echo WEB_URL; ?>fair/fair_list.php?id=' + Id;
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
