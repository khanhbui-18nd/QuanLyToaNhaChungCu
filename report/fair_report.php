<?php
include('../config.php'); // Thêm dòng này để bao gồm tệp config
include('../header.php');
include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_fair_report.php');

include '../config.php';
if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    die();
}

$floor_id = "";
$unit_id = "";
$month_id = "";
$button_text = $_data['text_7'];

if (isset($_GET['fid'])) {
    $floor_id = $_GET['fid'];
}
if (isset($_GET['uid'])) {
    $unit_id = $_GET['uid'];
}
if (isset($_GET['mid'])) {
    $month_id = $_GET['mid'];
}
?>

<section class="content-header">
    <h1><?php echo $_data['text_1']; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo WEB_URL ?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam']; ?></a></li>
        <li class="active"><a href="<?php echo WEB_URL ?>report/fair_report.php"><?php echo $_data['text_2']; ?></a></li>
        <li class="active"><?php echo $_data['text_1']; ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div align="right" style="margin-bottom:1%;">
                <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>report/report.php" data-original-title="<?php echo 1; ?>">
                    <i class="fa fa-reply"></i>
                </a>
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?php echo $_data['text_3']; ?></h3>
                </div>
                <form onSubmit="return validateMe();" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="form-group">
                            <label for="ddlFloorNo"><?php echo $_data['text_4']; ?> :</label>
                            <select onchange="getUnitReport(this.value)" name="ddlFloorNo" id="ddlFloorNo" class="form-control">
                                <option value="">--<?php echo $_data['text_4']; ?>--</option>
                                <?php 
                                $result_floor = $conn->query("SELECT * FROM tbl_add_floor ORDER BY fid ASC");
                                while ($row_floor = $result_floor->fetch_assoc()) { ?>
                                    <option <?php if ($floor_id == $row_floor['fid']) { echo 'selected'; } ?> value="<?php echo $row_floor['fid']; ?>"><?php echo $row_floor['floor_no']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ddlUnitNo"><?php echo $_data['text_5']; ?> :</label>
                            <select name="ddlUnitNo" id="" class="form-control">
                                <option value="">--<?php echo $_data['text_5']; ?>--</option>
                                <?php 
                                $result_unit = $conn->query("SELECT * FROM tbl_add_unit ORDER BY uid ASC");
                                while ($row_unit = $result_unit->fetch_assoc()) { ?>
                                    <option <?php if ($unit_id == $row_unit['uid']) { echo 'selected'; } ?> value="<?php echo $row_unit['uid']; ?>"><?php echo $row_unit['unit_no']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ddlMonth"><?php echo $_data['text_6']; ?> :</label>
                            <select name="ddlMonth" id="ddlMonth" class="form-control">
                                <option value="">--<?php echo $_data['text_6']; ?>--</option>
                                <?php 
                                $result_month = $conn->query("SELECT * FROM tbl_add_month_setup ORDER BY m_id ASC");
                                while ($row_month = $result_month->fetch_assoc()) { ?>
                                    <option <?php if ($month_id == $row_month['m_id']) { echo 'selected'; } ?> value="<?php echo $row_month['m_id']; ?>"><?php echo $row_month['month_name']; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group pull-right">
                            <input type="button" onclick="getFairInfo()" value="<?php echo $button_text; ?>" class="btn btn-success"/>
                        </div>
                    </div>
                    <input type="hidden" value="<?php echo $hdnid; ?>" name="hdn"/>
                </form>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>

<script type="text/javascript">
    function getFairInfo() {
        var floor_id = $("#ddlFloorNo").val();
        var unit_id = $("#ddlUnitNo").val();
        var month_id = $("#ddlMonth").val();
        
        if (floor_id != '' && unit_id != '' && month_id != '') {
            window.open('<?php echo WEB_URL; ?>report/fair_info_all.php?fid=' + floor_id + '&uid=' + unit_id + '&mid=' + month_id, '_blank');
        } else if (floor_id != '' && unit_id != '') {
            window.open('<?php echo WEB_URL; ?>report/fair_info_floor_unit.php?fid=' + floor_id + '&uid=' + unit_id, '_blank');
        } else if (floor_id != '') {
            window.open('<?php echo WEB_URL; ?>report/fair_info_floor.php?fid=' + floor_id, '_blank');
        } else if (month_id != '') {
            window.open('<?php echo WEB_URL; ?>report/fair_info_month.php?mid=' + month_id, '_blank');
        } else {
            alert('Please select at least one or more fields');
        }
    }
</script>

<?php include('../footer.php'); ?>
