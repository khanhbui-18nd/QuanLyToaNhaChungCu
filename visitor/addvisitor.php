<?php 
include('../header.php');
include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_add_visitor.php');

if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    die();
}

include '../config.php';
$success = "none";
$floor_no = '';
$title = $_data['text_1'];
$button_text = $_data['save_button_text'];
$successful_msg = $_data['text_15'];
$form_url = WEB_URL . "visitor/addvisitor.php";
$id = "";
$hdnid = "0";
$floor_id = 0;
$unit_id = 0;
$name = '';
$mobile = '';
$address = '';
$intime = '';
$outtime = '';
$xdate = '';
$branch_id = '';
$issue_date = '';

if (isset($_POST['txtName'])) {
    if (isset($_POST['hdn']) && $_POST['hdn'] == '0') {
        $month = date('m');
        $year = date('Y');
        
        // Thêm khách tham quan
        $stmt = $conn->prepare("INSERT INTO `tbl_visitor` (issue_date, name, mobile, address, floor_id, unit_id, intime, outtime, xmonth, xyear, branch_id) 
                                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssiiisssi", $_POST['txtIssueDate'], $_POST['txtName'], $_POST['txtMobile'], $_POST['txtAddress'], $_POST['ddlFloorNo'], $_POST['ddlUnitNo'], $_POST['txtInTime'], $_POST['txtOutTime'], $month, $year, $_SESSION['objLogin']['branch_id']);
        $stmt->execute();
        $stmt->close();
        
        $url = WEB_URL . 'visitor/visitorlist.php?m=add';
        header("Location: $url");
    } else {
        // Cập nhật thông tin khách tham quan
        $stmt = $conn->prepare("UPDATE `tbl_visitor` SET issue_date=?, name=?, mobile=?, address=?, floor_id=?, unit_id=?, intime=?, outtime=? WHERE vid=?");
        $stmt->bind_param("ssssiiisi", $_POST['txtIssueDate'], $_POST['txtName'], $_POST['txtMobile'], $_POST['txtAddress'], $_POST['ddlFloorNo'], $_POST['ddlUnitNo'], $_POST['txtInTime'], $_POST['txtOutTime'], $_GET['id']);
        $stmt->execute();
        $stmt->close();
        
        $url = WEB_URL . 'visitor/visitorlist.php?m=up';
        header("Location: $url");
    }
    $success = "block";
}

if (isset($_GET['id']) && $_GET['id'] != '') {
    $stmt = $conn->prepare("SELECT * FROM tbl_visitor WHERE vid = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $issue_date = $row['issue_date'];
        $name = $row['name'];
        $mobile = $row['mobile'];
        $floor_id = $row['floor_id'];
        $unit_id = $row['unit_id'];
        $intime = $row['intime'];
        $outtime = $row['outtime'];
        $address = $row['address'];
        $hdnid = $_GET['id'];
        $title = $_data['text_16'];
        $button_text = $_data['update_button_text'];
        $successful_msg = $_data['text_17'];
        $form_url = WEB_URL . "visitor/addvisitor.php?id=" . $_GET['id'];
    }
    $stmt->close();
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
    <h1><?php echo $title; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo WEB_URL ?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam']; ?></a></li>
        <li class="active"><?php echo $_data['text_2']; ?></li>
        <li class="active"><?php echo $_data['text_3']; ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div align="right" style="margin-bottom:1%;">
                <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>visitor/visitorlist.php" data-original-title="<?php echo $_data['back_text']; ?>"><i class="fa fa-reply"></i></a>
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?php echo $_data['text_4']; ?></h3>
                </div>
                <div class="box-body">
                    <form method="post" action="<?php echo $form_url; ?>">
                        <input type="hidden" name="hdn" value="<?php echo $hdnid; ?>">
                        <div class="form-group">
                            <label for="txtIssueDate"><?php echo $_data['text_5']; ?></label>
                            <input type="date" class="form-control" id="txtIssueDate" name="txtIssueDate" value="<?php echo $issue_date; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="txtName"><?php echo $_data['text_6']; ?></label>
                            <input type="text" class="form-control" id="txtName" name="txtName" value="<?php echo $name; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="txtMobile"><?php echo $_data['text_7']; ?></label>
                            <input type="text" class="form-control" id="txtMobile" name="txtMobile" value="<?php echo $mobile; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="txtAddress"><?php echo $_data['text_8']; ?></label>
                            <input type="text" class="form-control" id="txtAddress" name="txtAddress" value="<?php echo $address; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="ddlFloorNo"><?php echo $_data['text_9']; ?></label>
                            <select class="form-control" id="ddlFloorNo" name="ddlFloorNo" required>
                                <option value=""><?php echo $_data['text_10']; ?></option>
                                <!-- Populate floors dynamically -->
                                <?php
                                $floors = $conn->query("SELECT * FROM tbl_add_floor WHERE branch_id = '" . $_SESSION['objLogin']['branch_id'] . "'");
                                while ($floor = $floors->fetch_assoc()) {
                                    $selected = $floor['fid'] == $floor_id ? 'selected' : '';
                                    echo "<option value='{$floor['fid']}' $selected>{$floor['floor_no']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ddlUnitNo"><?php echo $_data['text_11']; ?></label>
                            <select class="form-control" id="ddlUnitNo" name="ddlUnitNo" required>
                                <option value=""><?php echo $_data['text_12']; ?></option>
                                <!-- Populate units dynamically -->
                                <?php
                                $units = $conn->query("SELECT * FROM tbl_add_unit WHERE branch_id = '" . $_SESSION['objLogin']['branch_id'] . "'");
                                while ($unit = $units->fetch_assoc()) {
                                    $selected = $unit['uid'] == $unit_id ? 'selected' : '';
                                    echo "<option value='{$unit['uid']}' $selected>{$unit['unit_no']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="txtInTime"><?php echo $_data['text_13']; ?></label>
                            <input type="time" class="form-control" id="txtInTime" name="txtInTime" value="<?php echo $intime; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="txtOutTime"><?php echo $_data['text_14']; ?></label>
                            <input type="time" class="form-control" id="txtOutTime" name="txtOutTime" value="<?php echo $outtime; ?>" required>
                        </div>
                        <button type="submit" class="btn btn-primary"><?php echo $button_text; ?></button>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    <!-- /.row -->
</section>
<?php include('../footer.php'); ?>
