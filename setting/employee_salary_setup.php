<?php 
include('../header.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_employee_salary_setup.php');
if(!isset($_SESSION['objLogin'])){
    header("Location: " . WEB_URL . "logout.php");
    die();
}
include '../config.php';
$emp_name = '';
$designation = '';
$month_id = '';
$amount = '0.00';
$issue_date = '';
$branch_id = '';
$button_text = $_data['save_button_text'];
$form_url = WEB_URL . "setting/employee_salary_setup.php";

$hval = 0;

$station_logo = WEB_URL . 'img/no_image.jpg';
$img_track = '';

if (isset($_POST['ddlEmpName'])) {
    $year = date('Y');

    if ($_POST['hdnSpid'] == '0') {
        $stmt = $conn->prepare("INSERT INTO `tbl_add_employee_salary_setup` (`emp_name`, `designation`, `month_id`, `xyear`, `amount`, `issue_date`, `branch_id`) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $_POST['ddlEmpName'], $_POST['hdnDesg'], $_POST['ddlEmpMonth'], $year, $_POST['txtEmpAmount'], $_POST['txtEmpIssueDate'], $_SESSION['objLogin']['branch_id']);
        $stmt->execute();
        $stmt->close();
        $url = WEB_URL . 'setting/employee_salary_setup.php?m=add';
        header("Location: $url");
    } else {
        $stmt_update = $conn->prepare("UPDATE `tbl_add_employee_salary_setup` SET emp_name = ?, designation = ?, month_id = ?, amount = ?, issue_date = ? WHERE emp_id = ?");
        $stmt_update->bind_param("sssssi", $_POST['ddlEmpName'], $_POST['hdnDesg'], $_POST['ddlEmpMonth'], $_POST['txtEmpAmount'], $_POST['txtEmpIssueDate'], $_POST['hdnSpid']);
        $stmt_update->execute();
        $stmt_update->close();
        $url = WEB_URL . 'setting/employee_salary_setup.php?m=up';
        header("Location: $url");
    }

    $success = "block";
}

if (isset($_GET['spid']) && $_GET['spid'] != '') {
    $stmt_location = $conn->prepare("SELECT * FROM tbl_add_employee_salary_setup WHERE emp_id = ? AND branch_id = ?");
    $stmt_location->bind_param("ii", $_GET['spid'], $_SESSION['objLogin']['branch_id']);
    $stmt_location->execute();
    $result_location = $stmt_location->get_result();
    
    if ($row = $result_location->fetch_assoc()) {
        $emp_name = $row['emp_name'];
        $designation = $row['designation'];
        $month_id = $row['month_id'];
        $amount = $row['amount'];
        $issue_date = $row['issue_date'];
        $button_text = $_data['update_button_text'];
        $form_url = WEB_URL . "setting/employee_salary_setup.php?id=" . $_GET['spid'];
        $hval = $row['emp_id'];
    }
    $stmt_location->close();
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
                            <label for="ddlEmpName"><?php echo $_data['text_3'];?> :</label>
                            <select onchange="getDesgInfo(this.value)" name="ddlEmpName" id="ddlEmpName" class="form-control">
                                <option value="">--<?php echo $_data['text_4'];?>--</option>
                                <?php 
                                $result_emp = $conn->query("SELECT * FROM tbl_add_employee ORDER BY eid ASC");
                                while ($row_emp = $result_emp->fetch_assoc()) { ?>
                                    <option <?php if($emp_name == $row_emp['eid']){echo 'selected';}?> value="<?php echo $row_emp['eid'];?>"><?php echo $row_emp['e_name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="txtEmpDesignation"><?php echo $_data['text_5'];?> :</label>
                            <input readonly="readonly" type="text" name="txtEmpDesignation" value="<?php echo $designation;?>" id="txtEmpDesignation" class="form-control" />
                            <input type="hidden" id="hdnDesg" name="hdnDesg" value="<?php echo $designation;?>" />
                        </div>
                        <div class="form-group">
                            <label for="ddlEmpMonth"><?php echo $_data['text_6'];?> :</label>
                            <select name="ddlEmpMonth" id="ddlEmpMonth" class="form-control">
                                <option value="">--<?php echo $_data['text_6'];?>--</option>
                                <?php 
                                $result_month = $conn->query("SELECT * FROM tbl_add_month_setup ORDER BY m_id ASC");
                                while ($row_month = $result_month->fetch_assoc()) { ?>
                                    <option <?php if($month_id == $row_month['m_id']){echo 'selected';}?> value="<?php echo $row_month['m_id'];?>"><?php echo $row_month['month_name'];?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="txtEmpAmount"><?php echo $_data['text_7'];?> :</label>
                            <div class="input-group">
                                <input type="text" name="txtEmpAmount" value="<?php echo $amount;?>" id="txtEmpAmount" class="form-control" />
                                <div class="input-group-addon"><?php echo CURRENCY;?></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="txtEmpIssueDate"><?php echo $_data['text_8'];?> :</label>
                            <input type="text" name="txtEmpIssueDate" value="<?php echo $issue_date;?>" id="txtEmpIssueDate" class="form-control datepicker" />
                        </div>
                        <div class="form-group pull-right">
                            <input type="submit" name="submit" class="btn btn-primary" value="<?php echo $button_text; ?>"/>
                            &nbsp;
                            <input type="reset" onClick="javascript:window.location.href='<?php echo WEB_URL; ?>setting/employee_salary_setup.php';" name="btnReset" id="btnReset" value="<?php echo $_data['reset'];?>" class="btn btn-primary"/>
                        </div>
                    </div>
                    <input type="hidden" name="hdnSpid" value="<?php echo $hval; ?>"/>
                </form>
                <h4 style="text-align:center; color:red;"><?php echo $_data['reset_text'];?></h4>

                <!-- Xử lý thông báo -->
                <?php
                $delinfo = 'none';
                $addinfo = 'none';
                $msg = "";

                if (isset($_GET['delid']) && $_GET['delid'] != '' && $_GET['delid'] > 0) {
                    $stmt_del = $conn->prepare("DELETE FROM `tbl_add_employee_salary_setup` WHERE emp_id = ?");
                    $stmt_del->bind_param("i", $_GET['delid']);
                    $stmt_del->execute();
                    $stmt_del->close();
                    $delinfo = 'block';
                }

                if (isset($_GET['m']) && $_GET['m'] == 'add') {
                    $addinfo = 'block';
                    $msg = $_data['text_9'];
                }
                if (isset($_GET['m']) && $_GET['m'] == 'up') {
                    $addinfo = 'block';
                    $msg = $_data['text_10'];
                }
                ?>

                <div class="alert alert-success alert-dismissible" style="display: <?php echo $addinfo; ?>;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-check"></i> Thành công!</h4>
                    <?php echo $msg; ?>
                </div>
                <div class="alert alert-danger alert-dismissible" style="display: <?php echo $delinfo; ?>;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    <h4><i class="icon fa fa-ban"></i> Thất bại!</h4>
                    <?php echo $_data['delete_text']; ?>
                </div>

                <div class="box-body">
                    <table id="example" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th><?php echo $_data['text_11'];?></th>
                                <th><?php echo $_data['text_12'];?></th>
                                <th><?php echo $_data['text_6'];?></th>
                                <th><?php echo $_data['text_7'];?></th>
                                <th><?php echo $_data['text_8'];?></th>
                                <th><?php echo $_data['text_13'];?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $result = $conn->query("SELECT * FROM tbl_add_employee_salary_setup WHERE branch_id = ".$_SESSION['objLogin']['branch_id']);
                            while ($row = $result->fetch_assoc()) { ?>
                                <tr>
                                    <td><?php echo $row['emp_name']; ?></td>
                                    <td><?php echo $row['designation']; ?></td>
                                    <td><?php echo $row['month_id']; ?></td>
                                    <td><?php echo $row['amount']; ?></td>
                                    <td><?php echo $row['issue_date']; ?></td>
                                    <td>
                                        <a href="<?php echo WEB_URL; ?>setting/employee_salary_setup.php?spid=<?php echo $row['emp_id']; ?>" class="btn btn-info" title="<?php echo $_data['edit_text'];?>"><i class="fa fa-pencil"></i></a>
                                        <a href="<?php echo WEB_URL; ?>setting/employee_salary_setup.php?delid=<?php echo $row['emp_id']; ?>" class="btn btn-danger" title="<?php echo $_data['delete_text'];?>" onclick="return confirm('<?php echo 'Delete?';?>');"><i class="fa fa-trash-o"></i></a>
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
<script type="text/javascript">
    function getDesgInfo(empId) {
        $.ajax({
            type: "POST",
            url: "<?php echo WEB_URL; ?>setting/get_designation_info.php",
            data: { empId: empId },
            success: function (response) {
                var data = JSON.parse(response);
                $('#txtEmpDesignation').val(data.designation);
                $('#hdnDesg').val(data.designation);
            }
        });
    }
</script>
