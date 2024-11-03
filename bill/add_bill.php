<?php
include('../header.php');
include('../utility/common.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_add_bill.php');
if(!isset($_SESSION['objLogin'])){
    header("Location: " . WEB_URL . "logout.php");
    die();
}
include '../config.php';
$success = "none";
$bill_type = '';
$bill_date = '';
$bill_month = '';
$bill_year = '';
$total_amount = '';
$deposit_bank_name = '';
$bill_details = '';
$branch_id = '';
$title = $_data['text_1'];
$button_text = $_data['save_button_text'];
$successful_msg = $_data['text_15'];
$form_url = WEB_URL . "bill/add_bill.php";
$id = "";
$hdnid = "0";

// Xử lý thêm hoặc sửa hóa đơn
if (isset($_POST['ddlBillType'])) {
    if (isset($_POST['hdn']) && $_POST['hdn'] == '0') {
        $stmt = $conn->prepare("INSERT INTO tbl_add_bill (bill_type, bill_date, bill_month, bill_year, total_amount, deposit_bank_name, bill_details, branch_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssi", $_POST['ddlBillType'], $_POST['txtBillDate'], $_POST['ddlBillMonth'], $_POST['ddlBillYear'], $_POST['txtTotalAmount'], $_POST['txtDepositBankName'], $_POST['txtBillDetails'], $_SESSION['objLogin']['branch_id']);
        $stmt->execute();
        $stmt->close();

        $url = WEB_URL . 'bill/bill_list.php?m=add';
        header("Location: $url");
    } else {
        $stmt = $conn->prepare("UPDATE tbl_add_bill SET bill_type = ?, bill_date = ?, bill_month = ?, bill_year = ?, total_amount = ?, deposit_bank_name = ?, bill_details = ? WHERE bill_id = ?");
        $stmt->bind_param("issssssi", $_POST['ddlBillType'], $_POST['txtBillDate'], $_POST['ddlBillMonth'], $_POST['ddlBillYear'], $_POST['txtTotalAmount'], $_POST['txtDepositBankName'], $_POST['txtBillDetails'], $_GET['id']);
        $stmt->execute();
        $stmt->close();

        $url = WEB_URL . 'bill/bill_list.php?m=up';
        header("Location: $url");
    }

    $success = "block";
}

// Lấy thông tin hóa đơn nếu có id
if (isset($_GET['id']) && $_GET['id'] != '') {
    $stmt = $conn->prepare("SELECT * FROM tbl_add_bill WHERE bill_id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $bill_type = $row['bill_type'];
        $bill_date = $row['bill_date'];
        $bill_month = $row['bill_month'];
        $bill_year = $row['bill_year'];
        $total_amount = $row['total_amount'];
        $deposit_bank_name = $row['deposit_bank_name'];
        $bill_details = $row['bill_details'];
        $hdnid = $_GET['id'];
        $title = $_data['text_1_1'];
        $button_text = $_data['update_button_text'];
        $successful_msg = $_data['text_16'];
        $form_url = WEB_URL . "bill/add_bill.php?id=" . $_GET['id'];
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
                <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>bill/bill_list.php" data-original-title="<?php echo $_data['back_text']; ?>"><i class="fa fa-reply"></i></a>
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?php echo $_data['text_4']; ?></h3>
                </div>
                <div class="box-body">
                    <form id="billForm" method="post" action="<?php echo $form_url; ?>" onsubmit="return validateMe();">
                        <input type="hidden" name="hdn" value="<?php echo $hdnid; ?>">
                        <div class="form-group">
                            <label><?php echo $_data['text_5']; ?></label>
                            <select id="ddlBillType" name="ddlBillType" class="form-control" required>
                                <option value="">Select Bill Type</option>
								<?php 
									$stmt = $conn->prepare("SELECT * FROM tbl_add_bill_type ORDER BY bt_id ASC");
									$stmt->execute();
									$result_bill_type = $stmt->get_result();
									while($row_bill_type = $result_bill_type->fetch_assoc()){
										?>
										<option <?= ($bill_type == $row_bill_type['bt_id']) ? 'selected' : "";?> value="<?= $row_bill_type['bt_id'] ?>"><?= $row_bill_type['bill_type']?></option>
										<?php //HTML
									}
								?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['text_7']; ?></label>
                            <input type="date" id="txtBillDate" name="txtBillDate" class="form-control" value="<?php echo $bill_date; ?>" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['text_8']; ?></label>
                            <select id="ddlBillMonth" name="ddlBillMonth" class="form-control" required>
                                <option value="">Select Month</option>
								<?php 
							$stmt = $conn->prepare("SELECT * FROM tbl_add_month_setup ORDER BY m_id ASC");
							$stmt->execute();
							$result_month = $stmt->get_result();
							while($row_month = $result_month->fetch_assoc()){ ?>
						<option <?php if($bill_month == $row_month['m_id']){echo 'selected';}?> value="<?php echo $row_month['m_id'];?>"><?php echo $row_month['month_name'];?></option>
						<?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['text_10']; ?></label>
                            <select id="ddlBillYear" name="ddlBillYear" class="form-control" required>
                                <option value="">Select Year</option>
								<?php 
                $stmt = $conn->prepare("SELECT * FROM tbl_add_year_setup ORDER BY y_id ASC");
                $stmt->execute();
                $result_year = $stmt->get_result();
                while($row_year = $result_year->fetch_assoc()){ ?>
              <option <?php if($bill_year == $row_year['y_id']){echo 'selected';}?> value="<?php echo $row_year['y_id'];?>"><?php echo $row_year['xyear'];?></option>
              <?php } ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['text_12']; ?></label>
                            <input type="text" id="txtTotalAmount" name="txtTotalAmount" class="form-control" value="<?php echo $total_amount; ?>" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['text_13']; ?></label>
                            <input type="text" id="txtDepositBankName" name="txtDepositBankName" class="form-control" value="<?php echo $deposit_bank_name; ?>" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['text_14']; ?></label>
                            <textarea id="txtBillDetails" name="txtBillDetails" class="form-control" required><?php echo $bill_details; ?></textarea>
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

    <script type="text/javascript">
        function validateMe() {
            if ($("#ddlBillType").val() == '') {
                alert("Bill Type Required !!!");
                $("#ddlBillType").focus();
                return false;
            }
            else if ($("#txtBillDate").val() == '') {
                alert("Date is Required !!!");
                $("#txtBillDate").focus();
                return false;
            }
            else if ($("#ddlBillMonth").val() == '') {
                alert("Bill Month is Required !!!");
                $("#ddlBillMonth").focus();
                return false;
            }
            else if ($("#ddlBillYear").val() == '') {
                alert("Bill Year is Required !!!");
                $("#ddlBillYear").focus();
                return false;
            }
            else if ($("#txtTotalAmount").val() == '') {
                alert("Total is Required !!!");
                $("#txtTotalAmount").focus();
                return false;
            }
            else if ($("#txtDepositBankName").val() == '') {
                alert("Bank Name is Required !!!");
                $("#txtDepositBankName").focus();
                return false;
            }
            else if ($("#txtBillDetails").val() == '') {
                alert("Bill Details Required !!!");
                $("#txtBillDetails").focus();
                return false;
            }
            else {
                return true;
            }
        }
    </script>

<?php include('../footer.php'); ?>
