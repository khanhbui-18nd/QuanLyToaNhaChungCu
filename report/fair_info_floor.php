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
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
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
                  <th><?php echo $_data['text_3'];?></th>
                  <th><?php echo $_data['text_4'];?></th>
                  <th><?php echo $_data['text_5'];?></th>
                  <th><?php echo $_data['text_6'];?></th>
                  <th><?php echo $_data['text_7'];?></th>
                  <th><?php echo $_data['text_8'];?></th>
                  <th><?php echo $_data['text_9'];?></th>
                  <th><?php echo $_data['text_10'];?></th>
                  <th><?php echo $_data['text_11'];?></th>
                  <th><?php echo $_data['text_12'];?></th>
                  <th><?php echo $_data['text_13'];?></th>
                  <th><?php echo $_data['text_14'];?></th>
                  <th><?php echo $_data['text_15'];?></th>
                </tr>
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

                $result = mysqli_query($conn, "SELECT *,r.r_name,o.o_name,fl.floor_no,u.unit_no,m.month_name FROM tbl_add_fair f LEFT JOIN tbl_add_rent r ON r.rid = f.rid LEFT JOIN tbl_add_owner o ON o.ownid = f.rid INNER JOIN tbl_add_floor fl ON fl.fid = f.floor_no INNER JOIN tbl_add_unit u ON u.uid = f.unit_no INNER JOIN tbl_add_month_setup m ON m.m_id = f.month_id WHERE f.floor_no='".$_GET['fid']."' AND f.branch_id = '" . (int)$_SESSION['objLogin']['branch_id'] . "'");
                while($row = mysqli_fetch_array($result)) {
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
                    <td><?php echo ($currency_position == 'left') ? $global_currency.$row['rent'] : $row['rent'].$global_currency; ?></td>
                    <td><?php echo ($currency_position == 'left') ? $global_currency.$row['gas_bill'] : $row['gas_bill'].$global_currency; ?></td>
                    <td><?php echo ($currency_position == 'left') ? $global_currency.$row['electric_bill'] : $row['electric_bill'].$global_currency; ?></td>
                    <td><?php echo ($currency_position == 'left') ? $global_currency.$row['water_bill'] : $row['water_bill'].$global_currency; ?></td>
                    <td><?php echo ($currency_position == 'left') ? $global_currency.$row['security_bill'] : $row['security_bill'].$global_currency; ?></td>
                    <td><?php echo ($currency_position == 'left') ? $global_currency.$row['utility_bill'] : $row['utility_bill'].$global_currency; ?></td>
                    <td><?php echo ($currency_position == 'left') ? $global_currency.$row['other_bill'] : $row['other_bill'].$global_currency; ?></td>
                    <td><?php echo ($currency_position == 'left') ? $global_currency.$row['total_rent'] : $row['total_rent'].$global_currency; ?></td>
                </tr>
                <?php } mysqli_close($conn); ?>
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="6">&nbsp;</th>
                  <th style="color:red;"><?php echo ($currency_position == 'left') ? $global_currency.number_format($rent_per_month_sub_total, 2, '.', '') : number_format($rent_per_month_sub_total, 2, '.', '').$global_currency; ?></th>
                  <th style="color:red;"><?php echo ($currency_position == 'left') ? $global_currency.number_format($gas_per_month_sub_total, 2, '.', '') : number_format($gas_per_month_sub_total, 2, '.', '').$global_currency; ?></th>
                  <th style="color:red;"><?php echo ($currency_position == 'left') ? $global_currency.number_format($electric_per_month_sub_total, 2, '.', '') : number_format($electric_per_month_sub_total, 2, '.', '').$global_currency; ?></th>
                  <th style="color:red;"><?php echo ($currency_position == 'left') ? $global_currency.number_format($water_per_month_sub_total, 2, '.', '') : number_format($water_per_month_sub_total, 2, '.', '').$global_currency; ?></th>
                  <th style="color:red;"><?php echo ($currency_position == 'left') ? $global_currency.number_format($security_per_month_sub_total, 2, '.', '') : number_format($security_per_month_sub_total, 2, '.', '').$global_currency; ?></th>
                  <th style="color:red;"><?php echo ($currency_position == 'left') ? $global_currency.number_format($utility_per_month_sub_total, 2, '.', '') : number_format($utility_per_month_sub_total, 2, '.', '').$global_currency; ?></th>
                  <th style="color:red;"><?php echo ($currency_position == 'left') ? $global_currency.number_format($other_per_month_sub_total, 2, '.', '') : number_format($other_per_month_sub_total, 2, '.', '').$global_currency; ?></th>
                  <th style="color:red;"><?php echo ($currency_position == 'left') ? $global_currency.number_format($total_per_month_sub_total, 2, '.', '') : number_format($total_per_month_sub_total, 2, '.', '').$global_currency; ?></th>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div align="center" style="position:fixed;width:100%;bottom:0;left:45%;">
  <input type="button" onclick="printContent('printable','Fair Collection Report')" value="<?php echo $_data['text_16'];?>" class="btn btn-primary btn_save"/>
</div>
</section>
</body>
</html>
