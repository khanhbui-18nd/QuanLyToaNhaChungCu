<?php
ob_start();
session_start();
include("../config.php");

if(!isset($_SESSION['objLogin'])){
	header("Location: ".WEB_URL."logout.php");
	die();
}

$lang_code_global = "English";
$global_currency = "$";
$currency_position = "left";
$currency_sep = ".";

$query_ams_settings = mysqli_query($conn, "SELECT * FROM tbl_settings");
while($row_query_ams_core = mysqli_fetch_array($query_ams_settings)){
	$lang_code_global = $row_query_ams_core['lang_code'];
	$global_currency = $row_query_ams_core['currency'];
	$currency_position = $row_query_ams_core['currency_position'];
	$currency_sep = $row_query_ams_core['currency_seperator'];
}

include(ROOT_PATH.'language/'.$lang_code_global.'/lang_fair_info_all.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_common.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Sako Apartment Management System</title>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<link href="<?php echo WEB_URL; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>dist/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>dist/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>dist/css/AdminLTE.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>plugins/iCheck/all.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>dist/css/dataTables.responsive.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>dist/css/dataTables.tableTools.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
<script src="<?php echo WEB_URL; ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="<?php echo WEB_URL; ?>dist/js/printThis.js"></script>
<script src="<?php echo WEB_URL; ?>dist/js/common.js"></script>
</head>
<body>
<section class="content">
<div id="printable">
  <div align="center" style="margin:50px;">
    <input type="hidden" id="web_url" value="<?php echo WEB_URL; ?>" />
    <div class="row">
      <div class="col-xs-12">
        <div class="box box-info">
          <div class="box-header">
            <h3 style="text-decoration:underline;font-weight:bold;color:orange" class="box-title"><?php echo $_data['text_1'];?></h3>
          </div>
          <div class="box-body">
            <table style="font-size:13px;" class="table sakotable table-bordered table-striped dt-responsive">
              <thead>
                <tr>
                  <th><?php echo $_data['text_2'];?></th>
                  <!-- Các tiêu đề khác -->
                </tr>
              </thead>
              <tbody>
                <?php
				$rent_per_month_sub_total = $gas_per_month_sub_total = $electric_per_month_sub_total = $water_per_month_sub_total = 0;
				$security_per_month_sub_total = $utility_per_month_sub_total = $other_per_month_sub_total = $total_per_month_sub_total = 0;
				
				$query = "SELECT *, r.r_name, o.o_name, fl.floor_no, u.unit_no, m.month_name 
                          FROM tbl_add_fair f 
                          LEFT JOIN tbl_add_rent r ON r.rid = f.rid 
                          LEFT JOIN tbl_add_owner o ON o.ownid = f.rid 
                          INNER JOIN tbl_add_floor fl ON fl.fid = f.floor_no 
                          INNER JOIN tbl_add_unit u ON u.uid = f.unit_no 
                          INNER JOIN tbl_add_month_setup m ON m.m_id = f.month_id 
                          WHERE f.month_id ='".$_GET['mid']."' AND f.branch_id = '" . (int)$_SESSION['objLogin']['branch_id'] . "'";
				$result = mysqli_query($conn, $query);
				while($row = mysqli_fetch_array($result)){
				    // Tổng hợp các giá trị cần thiết
				?>
                <tr>
                    <td><?php echo $row['issue_date']; ?></td>
                    <!-- Các cột dữ liệu khác -->
                </tr>
                <?php } mysqli_close($conn); ?>
              </tbody>
              <tfoot>
                <tr>
                  <th>&nbsp;</th>
                  <!-- Tổng hợp các cột -->
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div align="center"><a class="btn btn-primary btn_save" onClick="javascript:printContent('printable','Fair Collection Report');" href="javascript:void(0);"><?php echo $_data['text_16'];?></a></div>
</body>
</html>
