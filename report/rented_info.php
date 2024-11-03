<?php
ob_start();
session_start();
include("../config.php");

if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    die();
}

$lang_code_global = "English";
$global_currency = "$";
$currency_position = "left";
$currency_sep = ".";

// Thay thế mysql_* bằng MySQLi
$query_ams_settings = $conn->query("SELECT * FROM tbl_settings");
while ($row_query_ams_core = $query_ams_settings->fetch_assoc()) {
    $lang_code_global = $row_query_ams_core['lang_code'];
    $global_currency = $row_query_ams_core['currency'];
    $currency_position = $row_query_ams_core['currency_position'];
    $currency_sep = $row_query_ams_core['currency_seperator'];
}

include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_renter_info.php');
include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_common.php');
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
                                <h3 style="text-decoration:underline;font-weight:bold;color:orange" class="box-title"><?php echo $_data['text_1']; ?></h3>
                            </div>
                            <div class="box-body">
                                <table style="font-size:13px;" class="table sakotable table-bordered table-striped dt-responsive">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th><?php echo $_data['text_5']; ?></th>
                                            <th><?php echo $_data['text_6']; ?></th>
                                            <th><?php echo $_data['text_7']; ?></th>
                                            <th><?php echo $_data['text_8']; ?></th>
                                            <th><?php echo $_data['text_14']; ?></th>
                                            <th><?php echo $_data['text_9']; ?></th>
                                            <th><?php echo $_data['text_10']; ?></th>
                                            <th><?php echo $_data['text_11']; ?></th>
                                            <th><?php echo $_data['text_12']; ?></th>
                                            <th><?php echo $_data['text_13']; ?></th>
                                            <th><?php echo $_data['text_15']; ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Lấy thông tin thuê
                                        $result = $conn->query("SELECT *, f.floor_no AS ffloor, u.unit_no FROM tbl_add_rent r 
                                            INNER JOIN tbl_add_floor f ON f.fid = r.r_floor_no 
                                            INNER JOIN tbl_add_unit u ON u.uid = r.r_unit_no 
                                            WHERE r.r_status='" . $conn->real_escape_string($_GET['rsid']) . "' 
                                            AND r.branch_id = '" . (int)$_SESSION['objLogin']['branch_id'] . "'");

                                        while ($row = $result->fetch_assoc()) {
                                            $image = WEB_URL . 'img/no_image.jpg';
                                            if (file_exists(ROOT_PATH . '/img/upload/' . $row['image']) && $row['image'] != '') {
                                                $image = WEB_URL . 'img/upload/' . $row['image'];
                                            }
                                        ?>
                                            <tr>
                                                <td><img class="photo_img_round" style="width:50px;height:50px;" src="<?php echo $image; ?>" /></td>
                                                <td><?php echo $row['r_name']; ?></td>
                                                <td><?php echo $row['r_email']; ?></td>
                                                <td><?php echo $row['r_contact']; ?></td>
                                                <td><?php echo $row['r_address']; ?></td>
                                                <td><?php echo $row['r_nid']; ?></td>
                                                <td><?php echo $row['ffloor']; ?></td>
                                                <td><?php echo $row['unit_no']; ?></td>
                                                <td><?php echo $currency_position == 'left' ? $global_currency . $row['r_advance'] : $row['r_advance'] . $global_currency; ?></td>
                                                <td><?php echo $currency_position == 'left' ? $global_currency . $row['r_rent_pm'] : $row['r_rent_pm'] . $global_currency; ?></td>
                                                <td><?php echo $row['r_date']; ?></td>
                                                <td><?php echo $row['r_status'] == '1' ? $_data['text_17'] : $_data['text_18']; ?></td>
                                            </tr>
                                        <?php } ?>
                                        <?php $conn->close(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <div align="center"><a class="btn btn-primary btn_save" onClick="javascript:printContent('printable','Visitors Report');" href="javascript:void(0);"><?php echo $_data['text_16']; ?></a></div>
    </section>
</body>

</html>
