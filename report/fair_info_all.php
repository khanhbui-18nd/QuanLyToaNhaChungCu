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
<!-- Bootstrap 3.3.4 -->
<link href="<?php echo WEB_URL; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<!-- Font Awesome Icons -->
<link href="<?php echo WEB_URL; ?>dist/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<!-- Ionicons -->
<link href="<?php echo WEB_URL; ?>dist/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
<!-- Theme style -->
<link href="<?php echo WEB_URL; ?>dist/css/AdminLTE.css" rel="stylesheet" type="text/css" />
<!-- AdminLTE Skins. Choose a skin from the css/skins 
 folder instead of downloading all of them to reduce the load. -->
<link href="<?php echo WEB_URL; ?>dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
<!-- iCheck for checkboxes and radio inputs -->
<link href="<?php echo WEB_URL; ?>plugins/iCheck/all.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>dist/css/dataTables.responsive.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>dist/css/dataTables.tableTools.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- jQuery 2.1.4 -->
<script src="<?php echo WEB_URL; ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="<?php echo WEB_URL; ?>dist/js/printThis.js"></script>
<script src="<?php echo WEB_URL; ?>dist/js/common.js"></script>
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<section class="content">
<!-- Main content -->
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
                  <th>Ngày phát hành</th>
                  <th>Tên chủ sở hữu</th>
                  <th>Loại</th>
                  <th>Tầng</th>
                  <th>Số căn hộ</th>
                  <th>Tháng</th>
                  <th>Tiền thuê</th>
                  <th>Hóa đơn gas</th>
                  <th>Hóa đơn điện</th>
                  <th>Hóa đơn nước</th>
                  <th>Hóa đơn bảo vệ</th>
                  <th>Hóa đơn tiện ích</th>
                  <th>Hóa đơn khác</th>
                  <th>Tổng tiền thuê</th>
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
            $result = mysqli_query($conn, "SELECT *,r.r_name,o.o_name,fl.floor_no,u.unit_no,m.month_name FROM tbl_add_fair f LEFT JOIN tbl_add_rent r ON r.rid = f.rid LEFT JOIN tbl_add_owner o ON o.ownid = f.rid INNER JOIN tbl_add_floor fl ON fl.fid = f.floor_no INNER JOIN tbl_add_unit u ON u.uid = f.unit_no INNER JOIN tbl_add_month_setup m ON m.m_id = f.month_id WHERE f.floor_no='".$_GET['fid']."' AND f.unit_no='".$_GET['uid']."' AND f.month_id='".$_GET['mid']."' AND f.branch_id = '" . (int)$_SESSION['objLogin']['branch_id'] . "'");
                while($row = mysqli_fetch_array($result)){
                $rent_per_month_sub_total +=(float)$row['rent'];
                $gas_per_month_sub_total +=(float)$row['gas_bill'];
                $electric_per_month_sub_total +=(float)$row['electric_bill'];
                $water_per_month_sub_total +=(float)$row['water_bill'];
                $security_per_month_sub_total +=(float)$row['security_bill'];
                $utility_per_month_sub_total +=(float)$row['utility_bill'];
                $other_per_month_sub_total +=(float)$row['other_bill'];
                $total_per_month_sub_total +=(float)$row['total_rent'];
                ?>
                <tr>
                    <td><?php echo $row['issue_date']; ?></td>
                    <td><?php if($row['type']=='Rented'){echo $row['r_name'];} else{echo $row['o_name'];} ?></td>
                    <td><?php echo $row['type']; ?></td>
                    <td><?php echo $row['floor_no']; ?></td>
                    <td><?php echo $row['unit_no']; ?></td>
                    <td><?php echo $row['month_name']; ?></td>
                    <?php if($currency_position == 'left') { ?>
                    <td><?php echo $global_currency.$row['rent']; ?></td>
                    <?php } else { ?>
                    <td><?php echo $row['rent'].$global_currency; ?></h3>
                    <?php } ?>
                    <?php if($currency_position == 'left') { ?>
                    <td><?php echo $global_currency.$row['gas_bill']; ?></td>
                    <?php } else { ?>
                    <td><?php echo $row['gas_bill'].$global_currency; ?></h3>
                    <?php } ?>
                    <?php if($currency_position == 'left') { ?>
                    <td><?php echo $global_currency.$row['electric_bill']; ?></td>
                    <?php } else { ?>
                    <td><?php echo $row['electric_bill'].$global_currency; ?></h3>
                    <?php } ?>
                    <?php if($currency_position == 'left') { ?>
                    <td><?php echo $global_currency.$row['water_bill']; ?></td>
                    <?php } else { ?>
                    <td><?php echo $row['water_bill'].$global_currency; ?></h3>
                    <?php } ?>
                    <?php if($currency_position == 'left') { ?>
                    <td><?php echo $global_currency.$row['security_bill']; ?></td>
                    <?php } else { ?>
                    <td><?php echo $row['security_bill'].$global_currency; ?></h3>
                    <?php } ?>
                    <?php if($currency_position == 'left') { ?>
                    <td><?php echo $global_currency.$row['utility_bill']; ?></td>
                    <?php } else { ?>
                    <td><?php echo $row['utility_bill'].$global_currency; ?></h3>
                    <?php } ?>
                    <?php if($currency_position == 'left') { ?>
                    <td><?php echo $global_currency.$row['other_bill']; ?></td>
                    <?php } else { ?>
                    <td><?php echo $row['other_bill'].$global_currency; ?></h3>
                    <?php } ?>
                    <?php if($currency_position == 'left') { ?>
                    <td><?php echo $global_currency.$row['total_rent']; ?></td>
                    <?php } else { ?>
                    <td><?php echo $row['total_rent'].$global_currency; ?></h3>
                    <?php } ?>
                </tr>
            <?php } ?>
                <tr>
                    <td colspan="6" style="font-weight:bold;">Tổng</td>
                    <?php if($currency_position == 'left') { ?>
                    <td><?php echo $global_currency.$rent_per_month_sub_total; ?></td>
                    <?php } else { ?>
                    <td><?php echo $rent_per_month_sub_total.$global_currency; ?></td>
                    <?php } ?>
                    <?php if($currency_position == 'left') { ?>
                    <td><?php echo $global_currency.$gas_per_month_sub_total; ?></td>
                    <?php } else { ?>
                    <td><?php echo $gas_per_month_sub_total.$global_currency; ?></td>
                    <?php } ?>
                    <?php if($currency_position == 'left') { ?>
                    <td><?php echo $global_currency.$electric_per_month_sub_total; ?></td>
                    <?php } else { ?>
                    <td><?php echo $electric_per_month_sub_total.$global_currency; ?></td>
                    <?php } ?>
                    <?php if($currency_position == 'left') { ?>
                    <td><?php echo $global_currency.$water_per_month_sub_total; ?></td>
                    <?php } else { ?>
                    <td><?php echo $water_per_month_sub_total.$global_currency; ?></td>
                    <?php } ?>
                    <?php if($currency_position == 'left') { ?>
                    <td><?php echo $global_currency.$security_per_month_sub_total; ?></td>
                    <?php } else { ?>
                    <td><?php echo $security_per_month_sub_total.$global_currency; ?></td>
                    <?php } ?>
                    <?php if($currency_position == 'left') { ?>
                    <td><?php echo $global_currency.$utility_per_month_sub_total; ?></td>
                    <?php } else { ?>
                    <td><?php echo $utility_per_month_sub_total.$global_currency; ?></td>
                    <?php } ?>
                    <?php if($currency_position == 'left') { ?>
                    <td><?php echo $global_currency.$other_per_month_sub_total; ?></td>
                    <?php } else { ?>
                    <td><?php echo $other_per_month_sub_total.$global_currency; ?></td>
                    <?php } ?>
                    <?php if($currency_position == 'left') { ?>
                    <td><?php echo $global_currency.$total_per_month_sub_total; ?></td>
                    <?php } else { ?>
                    <td><?php echo $total_per_month_sub_total.$global_currency; ?></td>
                    <?php } ?>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>
</body>
</html>
