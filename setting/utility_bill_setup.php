<?php 
include('../header.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_utility_bill_setup.php');

if(!isset($_SESSION['objLogin'])){
    header("Location: " . WEB_URL . "logout.php");
    die();
}
include '../config.php';
$gas_bill = '';
$security_bill = '';
$station_logo = '';
$button_text = $_data['save_button_text'];
$form_url = WEB_URL . "setting/utility_bill_setup.php";
$hval = 0;
$station_logo = WEB_URL . 'img/no_image.jpg';
$img_track = '';



if (isset($_POST['txtGasBill'])) {
    if ($_POST['hdnSpid'] == '0') {
        $stmt = $conn->prepare("INSERT INTO `tbl_add_utility_bill`(`gas_bill`, `security_bill`) VALUES (?, ?)");
        $stmt->bind_param("ss", $_POST['txtGasBill'], $_POST['txtSecurityBill']);
        $stmt->execute();
        $stmt->close();
        $url = WEB_URL . 'setting/utility_bill_setup.php?m=add';
        header("Location: $url");
    } else {
        $stmt = $conn->prepare("UPDATE `tbl_add_utility_bill` SET gas_bill = ?, security_bill = ? WHERE utility_id = ?");
        $stmt->bind_param("ssi", $_POST['txtGasBill'], $_POST['txtSecurityBill'], $_POST['hdnSpid']);
        $stmt->execute();
        $stmt->close();
        $url = WEB_URL . 'setting/utility_bill_setup.php?m=up';
        header("Location: $url");
    }
    $success = "block";
}

if (isset($_GET['spid']) && $_GET['spid'] != '') {
    $stmt = $conn->prepare("SELECT * FROM tbl_add_utility_bill WHERE utility_id = ?");
    $stmt->bind_param("i", $_GET['spid']);
    $stmt->execute();
    $result_location = $stmt->get_result();
    if ($row = $result_location->fetch_assoc()) {
        $gas_bill = $row['gas_bill'];
        $security_bill = $row['security_bill'];
        $button_text = $_data['update_button_text'];
        $form_url = WEB_URL . "setting/utility_bill_setup.php?id=" . $_GET['spid'];
        $hval = $row['utility_id'];
    }
    $stmt->close();
}

// Xử lý xóa
$delinfo = 'none';
$addinfo = 'none';
$msg = "";
if (isset($_GET['delid']) && $_GET['delid'] != '' && $_GET['delid'] > 0) {
    $stmt = $conn->prepare("DELETE FROM `tbl_add_utility_bill` WHERE utility_id = ?");
    $stmt->bind_param("i", $_GET['delid']);
    $stmt->execute();
    $stmt->close();
    $delinfo = 'block';
}

if (isset($_GET['m']) && $_GET['m'] == 'add') {
    $addinfo = 'block';
    $msg = $_data['text_5'];
}
if (isset($_GET['m']) && $_GET['m'] == 'up') {
    $addinfo = 'block';
    $msg = $_data['text_6'];
}
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo $_data['text_1'];?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam'];?></a></li>
        <li class="active"><a href="<?php echo WEB_URL?>setting/setting.php"><?php echo $_data['setting'];?></a></li>
        <li class="active"><?php echo $_data['text_1'];?></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div align="right" style="margin-bottom:1%;">
                <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>setting/setting.php" data-original-title="<?php echo $_data['back_text'];?>">
                    <i class="fa fa-reply"></i>
                </a>
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?php echo $_data['text_2'];?></h3>
                </div>
                <form onSubmit="return validateMe();" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="txtGasBill"><?php echo $_data['text_3'];?> :</label>
                            <div class="input-group">
                                <input type="text" name="txtGasBill" value="<?php echo $gas_bill;?>" id="txtGasBill" class="form-control" />
                                <div class="input-group-addon"><?php echo CURRENCY;?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="txtSecurityBill"><?php echo $_data['text_4'];?> :</label>
                            <div class="input-group">
                                <input type="text" name="txtSecurityBill" value="<?php echo $security_bill;?>" id="txtSecurityBill" class="form-control" />
                                <div class="input-group-addon"><?php echo CURRENCY;?></div>
                            </div>
                        </div>
                        <div class="form-group pull-right">
                            <input type="submit" name="submit" class="btn btn-primary" value="<?php echo $button_text; ?>"/>
                            &nbsp;
                            <input type="reset" onClick="javascript:window.location.href='<?php echo WEB_URL; ?>setting/utility_bill_setup.php';" name="btnReset" id="btnReset" value="<?php echo $_data['reset'];?>" class="btn btn-primary"/>
                        </div>
                    </div>
                    <input type="hidden" name="hdnSpid" value="<?php echo $hval; ?>"/>
                </form>
                <h4 style="text-align:center; color:red;"><?php echo $_data['reset_text'];?></h4>
                
                <!-- Thông báo -->
                <section class="content">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
                                <h4><i class="icon fa fa-ban"></i> <?php echo $_data['delete_text'];?> !</h4>
                                <?php echo $_data['text_7'];?> 
                            </div>
                            <div class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
                                <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
                                <h4><i class="icon fa fa-check"></i> <?php echo $_data['success'];?> !</h4>
                                <?php echo $msg; ?> 
                            </div>
                            <div class="box box-info">
                                <div class="box-header">
                                    <h3 class="box-title"><?php echo $_data['text_8'];?></h3>
                                </div>
                                <div class="box-body">
                                    <table class="table sakotable table-bordered table-striped dt-responsive">
                                        <thead>
                                            <tr>
                                                <th><?php echo $_data['text_3'];?></th>
                                                <th><?php echo $_data['text_4'];?></th>
                                                <th><?php echo $_data['action_text'];?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $result = $conn->query("SELECT * FROM tbl_add_utility_bill ORDER BY utility_id ASC");
                                            while ($row = $result->fetch_assoc()) {
                                            ?>
                                            <tr>
                                                <?php if($currency_position == 'left') { ?>
                                                    <td><?php echo $global_currency.$row['gas_bill']; ?></td>
                                                <?php } else { ?>
                                                    <td><?php echo $row['gas_bill'].$global_currency; ?></td>
                                                <?php } ?>
                                                <?php if($currency_position == 'left') { ?>
                                                    <td><?php echo $global_currency.$row['security_bill']; ?></td>
                                                <?php } else { ?>
                                                    <td><?php echo $row['security_bill'].$global_currency; ?></td>
                                                <?php } ?>
                                                <td>
                                                    <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onclick="$('#employee_view_<?php echo $row['utility_id']; ?>').modal('show');" data-original-title="<?php echo $_data['view_text'];?>"><i class="fa fa-eye"></i></a> 
                                                    <a class="btn btn-warning" data-toggle="tooltip" href="<?php echo WEB_URL; ?>setting/utility_bill_setup.php?spid=<?php echo $row['utility_id']; ?>" data-original-title="<?php echo $_data['edit_text'];?>"><i class="fa fa-edit"></i></a>
                                                    <a class="btn btn-danger" data-toggle="tooltip" href="<?php echo WEB_URL; ?>setting/utility_bill_setup.php?delid=<?php echo $row['utility_id']; ?>" data-original-title="<?php echo $_data['delete_text'];?>" onclick="return confirm('<?php echo 'Delete?';?>')"><i class="fa fa-trash-o"></i></a>
                                                </td>
                                            </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</section>
