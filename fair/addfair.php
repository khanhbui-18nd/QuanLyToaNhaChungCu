<?php 
include('../header.php');
include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_add_fare.php');

if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    die();
}
include '../config.php';
$success = "none";
$type = 'Rented';
$floor_no = '';
$unit_no = '';
$month_id = '';
$xyear = date('Y');
$rent = '0.00';
$water_bill = '0.00';
$electric_bill = '0.00';
$gas_bill = '0.00';
$security_bill = '0.00';
$utility_bill = '0.00';
$other_bill = '0.00';
$total_rent = '0.00';
$issue_date = '';
$branch_id = '';
$title = $_data['add_new_rent'];
$button_text = $_data['save_button_text'];
$successful_msg = $_data['added_rent_successfully'];
$form_url = WEB_URL . "fair/addfair.php";
$id = "";
$hdnid = "0";

// New
$reneted_name = '';
$rid = 0;

if (isset($_POST['txtRent'])) {
    if (isset($_POST['hdn']) && $_POST['hdn'] == '0') {
        // Insert operation
        $stmt = $conn->prepare("INSERT INTO tbl_add_fair (type, floor_no, unit_no, rid, month_id, xyear, rent, water_bill, electric_bill, gas_bill, security_bill, utility_bill, other_bill, total_rent, issue_date, branch_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssssssssss", $type, $_POST['ddlFloorNo'], $_POST['ddlUnitNo'], $_POST['hdnRentedId'], $_POST['ddlMonth'], $xyear, $_POST['txtRent'], $_POST['txtWaterBill'], $_POST['txtElectricBill'], $_POST['txtGasBill'], $_POST['txtSecurityBill'], $_POST['txtUtilityBill'], $_POST['txtOtherBill'], $_POST['txtTotalRent'], $_POST['txtIssueDate'], $_SESSION['objLogin']['branch_id']);
        $stmt->execute();
        $stmt->close();

        $url = WEB_URL . 'fair/fairlist.php?m=add';
        header("Location: $url");
    } else {
        // Update operation
        $stmt = $conn->prepare("UPDATE tbl_add_fair SET floor_no = ?, unit_no = ?, rid = ?, month_id = ?, xyear = ?, rent = ?, water_bill = ?, electric_bill = ?, gas_bill = ?, security_bill = ?, utility_bill = ?, other_bill = ?, total_rent = ?, issue_date = ? WHERE f_id = ?");
        $stmt->bind_param("ssssssssssssssi", $_POST['ddlFloorNo'], $_POST['ddlUnitNo'], $_POST['hdnRentedId'], $_POST['ddlMonth'], $xyear, $_POST['txtRent'], $_POST['txtWaterBill'], $_POST['txtElectricBill'], $_POST['txtGasBill'], $_POST['txtSecurityBill'], $_POST['txtUtilityBill'], $_POST['txtOtherBill'], $_POST['txtTotalRent'], $_POST['txtIssueDate'], $_GET['id']);
        $stmt->execute();
        $stmt->close();

        $url = WEB_URL . 'fair/fairlist.php?m=up';
        header("Location: $url");
    }
    $success = "block";
}

if (isset($_GET['id']) && $_GET['id'] != '') {
    $stmt = $conn->prepare("SELECT *, r.r_name, r.rid FROM tbl_add_fair af INNER JOIN tbl_add_rent r ON r.rid = af.rid WHERE af.f_id = ? AND af.type = 'Rented'");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $floor_no = $row['floor_no'];
        $unit_no = $row['unit_no'];
        $month_id = $row['month_id'];
        $rent = $row['rent'];
        $water_bill = $row['water_bill'];
        $electric_bill = $row['electric_bill'];
        $gas_bill = $row['gas_bill'];
        $security_bill = $row['security_bill'];
        $utility_bill = $row['utility_bill'];
        $other_bill = $row['other_bill'];
        $total_rent = $row['total_rent'];
        $issue_date = $row['issue_date'];
        $hdnid = $_GET['id'];
        $reneted_name = $row['r_name'];
        $rid = $row['rid'];
        $title = $_data['update_rent'];
        $button_text = $_data['update_button_text'];
        $successful_msg = $_data['update_rent_successfully'];
        $form_url = WEB_URL . "fair/addfair.php?id=" . $_GET['id'];
    }

    $stmt->close();
} else {
    // Default total rent calculation
    $total_rent = (float)$gas_bill + (float)$security_bill;
    $total_rent = number_format($total_rent, 2, '.', ' ');
}
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo $title; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo WEB_URL ?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam']; ?></a></li>
        <li class="active"><?php echo $_data['add_new_rent_information_breadcam']; ?></li>
        <li class="active"><?php echo $_data['add_new_rent_breadcam']; ?></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div align="right" style="margin-bottom:1%;">
                <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>fair/fairlist.php" data-original-title="<?php echo $_data['back_text']; ?>"><i class="fa fa-reply"></i></a>
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?php echo $_data['add_new_rent_entry_form']; ?></h3>
                </div>
                <div class="box-body">
                    <form action="<?php echo $form_url; ?>" method="post" onsubmit="return validateMe();">
                        <input type="hidden" name="hdn" value="<?php echo $hdnid; ?>">
						<div class="form-group">
                            <label for="hdnRentedId">Rent</label>
                            <select id="hdnRentedId" name="hdnRentedId" class="form-control" required>
                                <option value="" disabled selected>Select Rent</option>
                                <?php 
								$dataRent = $conn->query("SELECT * FROM tbl_add_rent");
								foreach ($dataRent as $rent){
									?><option value="<?= $rent['rid'] ?>"><?= $rent['r_name'] ?></option><?php //HTML
								}
								?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ddlFloorNo">Floor No</label>
                            <select id="ddlFloorNo" name="ddlFloorNo" class="form-control" required>
                                <option value="" disabled selected>Select Floor</option>
                                <?php 
								$dataFloor = $conn->query("SELECT * FROM tbl_add_floor");
								foreach ($dataFloor as $floor){
									?><option value="<?= $floor['fid'] ?>"><?= $floor['floor_no'] ?></option><?php //HTML
								}
								?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="ddlUnitNo">Unit No</label>
                            <select id="ddlUnitNo" name="ddlUnitNo" class="form-control" required>
                                <option value="" disabled selected>Select Unit</option>
                                <?php 
								$dataUnit = $conn->query("SELECT * FROM tbl_add_unit");
								foreach ($dataUnit as $unit){
									?><option value="<?= $unit['uid'] ?>"><?= $unit['unit_no'] ?></option><?php //HTML
								}
								?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="ddlMonth">Month</label>
                            <select id="ddlMonth" name="ddlMonth" class="form-control" required>
                                <option value="" disabled selected>Select Month</option>
                                <?php 
								$dataMonth = $conn->query("SELECT * FROM tbl_add_month_setup");
								foreach ($dataMonth as $month){
									?><option value="<?= $month['m_id'] ?>"><?= $month['month_name'] ?></option><?php //HTML
								}
								?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="txtRent">Rent</label>
                            <input type="text" id="txtRent" name="txtRent" class="form-control" value="<?php echo 0; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="txtWaterBill">Water Bill</label>
                            <input type="text" id="txtWaterBill" name="txtWaterBill" class="form-control" value="<?php echo $water_bill; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="txtElectricBill">Electric Bill</label>
                            <input type="text" id="txtElectricBill" name="txtElectricBill" class="form-control" value="<?php echo $electric_bill; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="txtGasBill">Gas Bill</label>
                            <input type="text" id="txtGasBill" name="txtGasBill" class="form-control" value="<?php echo $gas_bill; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="txtSecurityBill">Security Bill</label>
                            <input type="text" id="txtSecurityBill" name="txtSecurityBill" class="form-control" value="<?php echo $security_bill; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="txtUtilityBill">Utility Bill</label>
                            <input type="text" id="txtUtilityBill" name="txtUtilityBill" class="form-control" value="<?php echo $utility_bill; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="txtOtherBill">Other Bill</label>
                            <input type="text" id="txtOtherBill" name="txtOtherBill" class="form-control" value="<?php echo $other_bill; ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="txtTotalRent">Total Rent</label>
                            <input type="text" id="txtTotalRent" name="txtTotalRent" class="form-control" value="<?php echo $total_rent; ?>" >
                        </div>

                        <div class="form-group">
                            <label for="txtIssueDate">Issue Date</label>
                            <input type="date" id="txtIssueDate" name="txtIssueDate" class="form-control" value="<?php echo $issue_date; ?>" required>
                        </div>

                        <button type="submit" class="btn btn-success"><?php echo $button_text; ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
function validateMe() {
    if ($("#ddlFloorNo").val() == '') {
        alert("Floor Required !!!");
        $("#ddlFloorNo").focus();
        return false;
    } else if ($("#ddlUnitNo").val() == '') {
        alert("Unit Required !!!");
        $("#ddlUnitNo").focus();
        return false;
    } else if ($("#ddlMonth").val() == '') {
        alert("Month Required !!!");
        $("#ddlMonth").focus();
        return false;
    } else if ($("#txtWaterBill").val() == '') {
        alert("Water Bill Required !!!");
        $("#txtWaterBill").focus();
        return false;
    } else if ($("#txtElectricBill").val() == '') {
        alert("Electric Bill Required !!!");
        $("#txtElectricBill").focus();
        return false;
    } else if ($("#txtGasBill").val() == '') {
        alert("Gas Bill Required !!!");
        $("#txtGasBill").focus();
        return false;
    } else if ($("#txtSecurityBill").val() == '') {
        alert("Security Bill Required !!!");
        $("#txtSecurityBill").focus();
        return false;
    } else if ($("#txtUtilityBill").val() == '') {
        alert("Utility Bill Required !!!");
        $("#txtUtilityBill").focus();
        return false;
    } else if ($("#txtIssueDate").val() == '') {
        alert("Issue Date Required !!!");
        $("#txtIssueDate").focus();
        return false;
    } else {
        return true;
    }
}
</script>

<?php include('../footer.php'); ?>
