<?php
ob_start();
session_start();
include("../config.php");

if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    die();
}
include '../config.php';
$lang_code_global = "English";
$query_ams_settings = mysqli_query($conn, "SELECT * FROM tbl_settings");

if ($row_query_ams_core = mysqli_fetch_array($query_ams_settings)) {
    $lang_code_global = $row_query_ams_core['lang_code'];
}

include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_visitor_info.php');
include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_common.php');
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sako Apartment Management System</title>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <!-- Bootstrap 3.3.4 -->
    <link href="<?php echo WEB_URL; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <!-- Font Awesome Icons -->
    <link href="<?php echo WEB_URL; ?>dist/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <!-- Ionicons -->
    <link href="<?php echo WEB_URL; ?>dist/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css"/>
    <!-- Theme style -->
    <link href="<?php echo WEB_URL; ?>dist/css/AdminLTE.css" rel="stylesheet" type="text/css"/>
    <!-- AdminLTE Skins -->
    <link href="<?php echo WEB_URL; ?>dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css"/>
    <!-- iCheck for checkboxes and radio inputs -->
    <link href="<?php echo WEB_URL; ?>plugins/iCheck/all.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo WEB_URL; ?>plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo WEB_URL; ?>dist/css/dataTables.responsive.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo WEB_URL; ?>dist/css/dataTables.tableTools.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo WEB_URL; ?>plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css"/>
    <!-- jQuery 2.1.4 -->
    <script src="<?php echo WEB_URL; ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <script src="<?php echo WEB_URL; ?>dist/js/printThis.js"></script>
    <script src="<?php echo WEB_URL; ?>dist/js/common.js"></script>
</head>
<body>
<section class="content">
    <div id="printable">
        <div align="center" style="margin:50px;">
            <input type="hidden" id="web_url" value="<?php echo WEB_URL; ?>"/>
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-info">
                        <div class="box-header">
                            <h3 style="text-decoration:underline;font-weight:bold;color:orange" class="box-title"><?php echo $_data['text_1']; ?></h3>
                        </div>
                        <div class="box-body">
                            <table style="font-size:13px;" class="table sakotable table-bordered table-striped dt-responsive">
                                <thead>
                                <tr>
                                    <th><?php echo $_data['text_2']; ?></th>
                                    <th><?php echo $_data['text_3']; ?></th>
                                    <th><?php echo $_data['text_4']; ?></th>
                                    <th><?php echo $_data['text_5']; ?></th>
                                    <th><?php echo $_data['text_6']; ?></th>
                                    <th><?php echo $_data['text_7']; ?></th>
                                    <th><?php echo $_data['text_8']; ?></th>
                                    <th><?php echo $_data['text_9']; ?></th>
                                    <th><?php echo $_data['text_10']; ?></th>
                                    <th><?php echo $_data['text_11']; ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $result = mysqli_query($conn, "SELECT *, fl.floor_no as fl_floor, u.unit_no, m.month_name 
                                    FROM tbl_visitor v 
                                    INNER JOIN tbl_add_floor fl ON fl.fid = v.floor_id 
                                    INNER JOIN tbl_add_unit u ON u.uid = v.unit_id 
                                    INNER JOIN tbl_add_month_setup m ON m.m_id = v.xmonth 
                                    WHERE v.xmonth='" . mysqli_real_escape_string($conn, $_GET['mid']) . "' 
                                    AND v.branch_id = '" . $_SESSION['objLogin']['branch_id'] . "'");
                                
                                while ($row = mysqli_fetch_array($result)) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['issue_date']; ?></td>
                                        <td><?php echo $row['name']; ?></td>
                                        <td><?php echo $row['mobile']; ?></td>
                                        <td><?php echo $row['address']; ?></td>
                                        <td><?php echo $row['fl_floor']; ?></td>
                                        <td><?php echo $row['unit_no']; ?></td>
                                        <td><?php echo $row['intime']; ?></td>
                                        <td><?php echo $row['outtime']; ?></td>
                                        <td><?php echo $row['month_name']; ?></td>
                                        <td><?php echo $row['xyear']; ?></td>
                                    </tr>
                                    <?php
                                }
                                mysqli_close($conn);
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div align="center">
        <a class="btn btn-primary btn_save" onClick="javascript:printContent('printable','Visitors Report');" href="javascript:void(0);"><?php echo $_data['print']; ?></a>
    </div>
</section>
</body>
</html>
