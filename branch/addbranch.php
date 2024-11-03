<?php
include('../header.php');
include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_add_branch.php');

if (!isset($_SESSION['objLogin']) || $_SESSION['login_type'] == "5") {
    header("Location: " . WEB_URL . "logout.php");
    die();
}
include '../config.php';
$success = "none";
$branch_name = '';
$b_email = '';
$b_contact_no = '';
$b_address = '';
$b_status = '';
$title = $_data['text_1'];
$button_text = $_data['save_button_text'];
$successful_msg = $_data['text_11'];
$form_url = WEB_URL . "branch/addbranch.php";
$id = "";
$hdnid = "0";

if (isset($_POST['txtBrName'])) {
    if (isset($_POST['hdn']) && $_POST['hdn'] == '0') {
        $stmt = $conn->prepare("INSERT INTO `tblbranch`(`branch_name`, `b_email`, `b_contact_no`, `b_address`, `b_status`) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $_POST['txtBrName'], $_POST['txtBrEmail'], $_POST['txtBrConNo'], $_POST['txtareaAddress'], $_POST['radioStatus']);
        $stmt->execute();
        $stmt->close();
        $url = WEB_URL . 'branch/branchlist.php?m=add';
        header("Location: $url");
    } else {
        $stmt = $conn->prepare("UPDATE `tblbranch` SET `branch_name`=?, `b_email`=?, `b_contact_no`=?, `b_address`=?, `b_status`=? WHERE branch_id = ?");
        $stmt->bind_param("ssssii", $_POST['txtBrName'], $_POST['txtBrEmail'], $_POST['txtBrConNo'], $_POST['txtareaAddress'], $_POST['radioStatus'], $_GET['id']);
        $stmt->execute();
        $stmt->close();
        $url = WEB_URL . 'branch/branchlist.php?m=up';
        header("Location: $url");
    }
    $success = "block";
}

if (isset($_GET['id']) && $_GET['id'] != '') {
    $stmt = $conn->prepare("SELECT * FROM tblbranch WHERE branch_id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $branch_name = $row['branch_name'];
        $b_email = $row['b_email'];
        $b_contact_no = $row['b_contact_no'];
        $b_address = $row['b_address'];
        $b_status = $row['b_status'];
        $hdnid = $_GET['id'];
        $title = $_data['text_1_1'];
        $button_text = $_data['update_button_text'];
        $successful_msg = $_data['text_12'];
        $form_url = WEB_URL . "branch/addbranch.php?id=" . $_GET['id'];
    }
    $stmt->close();
}

if (isset($_GET['mode']) && $_GET['mode'] == 'view') {
    $title = 'View Branch Details';
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo $title; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo WEB_URL ?>dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home_breadcam']; ?></a></li>
        <li class="active"><a href="<?php echo WEB_URL ?>branch/branchlist.php"><?php echo $_data['text_2']; ?></a></li>
        <li class="active"><?php echo $_data['text_1']; ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div align="right" style="margin-bottom:1%;">
                <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>branch/branchlist.php" data-original-title="<?php echo $_data['back_text']; ?>">
                    <i class="fa fa-reply"></i>
                </a>
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?php echo $_data['text_3']; ?></h3>
                </div>
                <div class="box-body">
                    <form method="POST" action="<?php echo $form_url; ?>">
                        <input type="hidden" name="hdn" value="<?php echo $hdnid; ?>">
                        <div class="form-group">
                            <label><?php echo $_data['text_4']; ?></label>
                            <input type="text" class="form-control" name="txtBrName" value="<?php echo $branch_name; ?>" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['text_5']; ?></label>
                            <input type="email" class="form-control" name="txtBrEmail" value="<?php echo $b_email; ?>" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['text_6']; ?></label>
                            <input type="text" class="form-control" name="txtBrConNo" value="<?php echo $b_contact_no; ?>" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['text_7']; ?></label>
                            <textarea class="form-control" name="txtareaAddress" required><?php echo $b_address; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['text_8']; ?></label>
                            <div>
                                <label class="radio-inline"><input type="radio" name="radioStatus" value="1" <?php echo ($b_status == 1) ? 'checked' : ''; ?>><?php echo $_data['text_9']; ?></label>
                                <label class="radio-inline"><input type="radio" name="radioStatus" value="0" <?php echo ($b_status == 0) ? 'checked' : ''; ?>><?php echo $_data['text_10']; ?></label>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><?php echo $button_text; ?></button>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</section>
<?php include('../footer.php'); ?>
