<?php
ob_start();
session_start();
include("../config.php");

if(!isset($_SESSION['objLogin'])){
    header("Location: ".WEB_URL."logout.php");
    die();
}
include '../config.php';
$lang_code_global = "English";
$global_currency = "$";
$currency_position = "left";
$currency_sep = ".";

// Sử dụng MySQLi để lấy dữ liệu từ tbl_settings
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
<!-- Các link CSS và script ở đây -->
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
                <!-- Các cột tiêu đề -->
              </thead>
              <tbody>
                <?php
                $rent_per_month_sub_total = 0;
                $gas_per_month_sub_total = 0;
                $electric_per_month_sub_total = 0;
                $water_per_month_sub_total = 0;
                $security_per_month_sub_total = 0;
                $utility_per_month_sub_total = 0;
                $other_per_month_sub_total = 0;
                $total_per_month_sub_total = 0;

                // Truy vấn dữ liệu
                $result = mysqli_query($conn, "SELECT *, r.r_name, o.o_name, fl.floor_no, u.unit_no, m.month_name 
                                               FROM tbl_add_fair f 
                                               LEFT JOIN tbl_add_rent r ON r.rid = f.rid 
                                               LEFT JOIN tbl_add_owner o ON o.ownid = f.rid 
                                               INNER JOIN tbl_add_floor fl ON fl.fid = f.floor_no 
                                               INNER JOIN tbl_add_unit u ON u.uid = f.unit_no 
                                               INNER JOIN tbl_add_month_setup m ON m.m_id = f.month_id 
                                               WHERE f.floor_no='{$_GET['fid']}' 
                                               AND f.unit_no='{$_GET['uid']}' 
                                               AND f.branch_id = '{$_SESSION['objLogin']['branch_id']}'");
                
                while($row = mysqli_fetch_array($result)){
                    $rent_per_month_sub_total += (float)$row['rent'];
                    $gas_per_month_sub_total += (float)$row['gas_bill'];
                    $electric_per_month_sub_total += (float)$row['electric_bill'];
                    $water_per_month_sub_total += (float)$row['water_bill'];
                    $security_per_month_sub_total += (float)$row['security_bill'];
                    $utility_per_month_sub_total += (float)$row['utility_bill'];
                    $other_per_month_sub_total += (float)$row['other_bill'];
                    $total_per_month_sub_total += (float)$row['total_rent'];
                ?>
                <tr>
                    <td><?php echo $row['issue_date']; ?></td>
                    <td><?php echo ($row['type'] == 'Rented') ? $row['r_name'] : $row['o_name']; ?></td>
                    <td><?php echo $row['type']; ?></td>
                    <td><?php echo $row['floor_no']; ?></td>
                    <td><?php echo $row['unit_no']; ?></td>
                    <td><?php echo $row['month_name']; ?></td>
                    <td><?php echo ($currency_position == 'left' ? $global_currency : '') . $row['rent'] . ($currency_position == 'right' ? $global_currency : ''); ?></td>
                    <!-- Các cột khác -->
                </tr>
                <?php } mysqli_close($conn); ?>
              </tbody>
              <tfoot>
                <!-- Dòng tổng kết -->
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div align="center">
  <a class="btn btn-primary btn_save" onClick="javascript:printContent('printable','Fair Collection Report');" href="javascript:void(0);"><?php echo $_data['text_16'];?></a>
</div>
</body>
</html>
