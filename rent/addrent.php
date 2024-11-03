<?php 
include('../header.php');
include('../utility/common.php');
include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_add_rented.php');
include '../language/English/lang_add_rented.php';
include '../config.php'; // Include config.php for $conn

if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    die();
}

$success = "none";
$r_name = '';
$r_email = '';
$r_contact = '';
$r_address = '';
$r_nid = '';
$r_floor_no = 0;
$r_unit_no = 0;
$r_advance = '';
$r_rent_pm = '';
$r_date = '';
$r_month = '';
$r_year = '';
$r_password = '';
$r_status = '0';
$branch_id = '';
$title = $_data['add_new_renter'];
$button_text = $_data['save_button_text'];
$successful_msg = $_data['added_renter_successfully'];
$form_url = WEB_URL . "rent/addrent.php";
$id = "";
$hdnid = "0";
$image_rnt = WEB_URL . 'img/no_image.jpg';
$img_track = '';

if (isset($_POST['txtRName'])) {
    if (isset($_POST['hdn']) && $_POST['hdn'] == '0') {
        $r_password = $_POST['txtPassword'];
        $image_url = uploadImage();
        $r_status = isset($_POST['chkRStaus']) ? 1 : 0;

        $stmt = $conn->prepare("INSERT INTO tbl_add_rent (r_name, r_email, r_contact, r_address, r_nid, r_floor_no, r_unit_no, r_advance, r_rent_pm, r_date, r_month, r_year, r_password, r_status, image, branch_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssiiissssissi', $_POST['txtRName'], $_POST['txtREmail'], $_POST['txtRContact'], $_POST['txtRAddress'], $_POST['txtRentedNID'], $_POST['ddlFloorNo'], $_POST['ddlUnitNo'], $_POST['txtRAdvance'], $_POST['txtRentPerMonth'], $_POST['txtRDate'], $_POST['ddlMonth'], $_POST['ddlYear'], $r_password, $r_status, $image_url, $_SESSION['objLogin']['branch_id']);
        
        if ($stmt->execute()) {
            // Update unit status
            $stmtx = $conn->prepare("UPDATE tbl_add_unit SET status = 1 WHERE floor_no = ? AND uid = ?");
            $stmtx->bind_param('ii', $_POST['ddlFloorNo'], $_POST['ddlUnitNo']);
            $stmtx->execute();

            $stmt->close();
            $stmtx->close();
            header("Location: " . WEB_URL . 'rent/rentlist.php?m=add');
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        $image_url = uploadImage();
        if ($image_url == '') {
            $image_url = $_POST['img_exist'];
        }
        $r_status = isset($_POST['chkRStaus']) ? 1 : 0;

        $stmt = $conn->prepare("UPDATE tbl_add_rent SET r_name = ?, r_email = ?, r_password = ?, r_contact = ?, r_address = ?, r_nid = ?, r_floor_no = ?, r_unit_no = ?, r_advance = ?, r_rent_pm = ?, r_date = ?, r_month = ?, r_year = ?, r_status = ?, image = ? WHERE rid = ?");
        $stmt->bind_param('sssssiiissssissi', $_POST['txtRName'], $_POST['txtREmail'], $_POST['txtPassword'], $_POST['txtRContact'], $_POST['txtRAddress'], $_POST['txtRentedNID'], $_POST['ddlFloorNo'], $_POST['ddlUnitNo'], $_POST['txtRAdvance'], $_POST['txtRentPerMonth'], $_POST['txtRDate'], $_POST['ddlMonth'], $_POST['ddlYear'], $r_status, $image_url, $_GET['id']);
        
        if ($stmt->execute()) {
            // Update unit status
            $stmtx = $conn->prepare("UPDATE tbl_add_unit SET status = 0 WHERE floor_no = ? AND uid = ?");
            $stmtx->bind_param('ii', $_POST['hdnFloor'], $_POST['hdnUnit']);
            $stmtx->execute();

            $stmtxx = $conn->prepare("UPDATE tbl_add_unit SET status = 1 WHERE floor_no = ? AND uid = ?");
            $stmtxx->bind_param('ii', $_POST['ddlFloorNo'], $_POST['ddlUnitNo']);
            $stmtxx->execute();

            $stmt->close();
            $stmtx->close();
            $stmtxx->close();
            header("Location: " . WEB_URL . 'rent/rentlist.php?m=up');
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    }
    $success = "block";
}

if (isset($_GET['id']) && $_GET['id'] != '') {
    $stmt = $conn->prepare("SELECT * FROM tbl_add_rent WHERE rid = ?");
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $r_name = $row['r_name'];
        $r_email = $row['r_email'];
        $r_contact = $row['r_contact'];
        $r_address = $row['r_address'];
        $r_nid = $row['r_nid'];
        $r_floor_no = $row['r_floor_no'];
        $r_unit_no = $row['r_unit_no'];
        $r_advance = $row['r_advance'];
        $r_rent_pm = $row['r_rent_pm'];
        $r_date = $row['r_date'];
        $r_month = $row['r_month'];
        $r_year = $row['r_year'];
        $r_status = $row['r_status'];
        $r_password = $row['r_password'];

        if ($row['image'] != '') {
            $image_rnt = WEB_URL . 'img/upload/' . $row['image'];
            $img_track = $row['image'];
        }
        $hdnid = $_GET['id'];
        $title = $_data['update_rent'];
        $button_text = $_data['update_button_text'];
        $successful_msg = $_data['update_renter_successfully'];
        $form_url = WEB_URL . "rent/addrent.php?id=" . $_GET['id'];
    }

    $stmt->close();
}

// for image upload
function uploadImage() {
    if ((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {
        $filename = basename($_FILES['uploaded_file']['name']);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $newfilename = NewGuid() . '.' . $ext;
            move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], ROOT_PATH . '/img/upload/' . $newfilename);
            return $newfilename;
        } else {
            return '';
        }
    }
    return '';
}

function NewGuid() {
    $s = strtoupper(md5(uniqid(rand(), true)));
    $guidText = 
        substr($s, 0, 8) . '-' . 
        substr($s, 8, 4) . '-' . 
        substr($s, 12, 4) . '-' . 
        substr($s, 16, 4) . '-' . 
        substr($s, 20);
    return $guidText;
}
?>


<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo $_data['add_new_renter']; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo WEB_URL ?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam']; ?></a></li>
        <li class="active"><?php echo $_data['add_new_renter_information_breadcam']; ?></li>
        <li class="active"><?php echo $_data['add_new_renter_breadcam']; ?></li>
    </ol>
</section>


<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div align="right" style="margin-bottom:1%;">
                <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>rent/rentlist.php" data-original-title="<?php echo $_data['view_all_renter_text'] ?? 0; ?>">
                    <i class="fa fa-list"></i> <?php echo $_data['view_all_renter_text'] ?? ""; ?>
                </a>
            </div>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $title; ?></h3>
                </div>
                <form method="post" enctype="multipart/form-data" id="addRenterForm">
                    <input type="hidden" name="hdn" id="hdn" value="<?php echo $hdnid; ?>" />
                    <input type="hidden" name="hdnFloor" id="hdnFloor" value="<?php echo $r_floor_no; ?>" />
                    <input type="hidden" name="hdnUnit" id="hdnUnit" value="<?php echo $r_unit_no; ?>" />
                    <div class="box-body">
                        <?php if ($success == "block") { ?>
                            <div class="alert alert-success" style="margin: 10px;">
                                <strong><?php echo $successful_msg; ?></strong>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label for="txtRName"><?php echo $_data['add_new_form_field_text_1']; ?> <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="txtRName" name="txtRName" value="<?php echo $r_name; ?>" required />
                        </div>
                        <div class="form-group">
                            <label for="txtREmail"><?php echo $_data['add_new_form_field_text_2']; ?> <span style="color:red;">*</span></label>
                            <input type="email" class="form-control" autocomplete="current-password" id="txtREmail" name="txtREmail" value="<?php echo $r_email; ?>" required />
                        </div>
                        <div class="form-group">
                            <label for="txtRContact"><?php echo $_data['add_new_form_field_text_4']; ?> <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="txtRContact" name="txtRContact" value="<?php echo $r_contact; ?>" required />
                        </div>
                        <div class="form-group">
                            <label for="txtRAddress"><?php echo $_data['add_new_form_field_text_5']; ?></label>
                            <input type="text" class="form-control" id="txtRAddress" name="txtRAddress" value="<?php echo $r_address; ?>" />
                        </div>
                        <div class="form-group">
                            <label for="txtRentedNID"><?php echo $_data['add_new_form_field_text_6']; ?> <span style="color:red;">*</span></label>
                            <input type="text" class="form-control" id="txtRentedNID" name="txtRentedNID" value="<?php echo $r_nid; ?>" required />
                        </div>
                        <div class="form-group">
                            <label for="ddlFloorNo"><?php echo $_data['select_floor']; ?> <span style="color:red;">*</span></label>
                            <select class="form-control" id="ddlFloorNo" name="ddlFloorNo" required>
                                <option value=""><?php echo $_data['select_floor']; ?></option>
                                <?php 
                                    $result = $conn->query("SELECT * FROM tbl_add_floor WHERE branch_id='" . $_SESSION['objLogin']['branch_id'] . "'");
                                    while ($row = $result->fetch_assoc()) {
                                        $selected = $row['floor_no'] == $r_floor_no ? 'selected' : '';
                                        ?><option value='<?= $row['fid'] ?>' <?= $selected ?>><?= $row['floor_no'] ?></option><?php //HTML
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ddlUnitNo"><?php echo $_data['select_unit']; ?> <span style="color:red;">*</span></label>
                            <select class="form-control" id="ddlUnitNo" name="ddlUnitNo" required>
                                <option value=""><?php echo $_data['select_unit']; ?></option>
                                <?php 
									if(!empty($r_unit_no)){
										$result = $conn->query("SELECT * FROM tbl_add_unit WHERE floor_no = '$r_floor_no' AND branch_id='" . $_SESSION['objLogin']['branch_id'] . "'");
										while ($row = $result->fetch_assoc()) {
											$selected = $row['uid'] == $r_unit_no ? 'selected' : '';
											echo "<option value='{$row['uid']}' $selected>{$row['uid']}</option>";
										}
                                    }else{
										$dataUnit = $conn->query("SELECT * FROM tbl_add_unit");
										foreach ($dataUnit as $unit){
											?><option value="<?= $unit['uid'] ?>"><?= $unit['unit_no'] ?></option><?php //HTML
										}
									}
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="txtRAdvance"><?php echo $_data['add_new_form_field_text_9']; ?> <span style="color:red;">*</span></label>
                            <input type="number" class="form-control" id="txtRAdvance" name="txtRAdvance" value="<?php echo $r_advance; ?>" required />
                        </div>
                        <div class="form-group">
                            <label for="txtRentPerMonth"><?php echo $_data['add_new_form_field_text_10']; ?> <span style="color:red;">*</span></label>
                            <input type="number" class="form-control" id="txtRentPerMonth" name="txtRentPerMonth" value="<?php echo $r_rent_pm; ?>" required />
                        </div>
                        <div class="form-group">
                            <label for="txtRDate"><?php echo $_data['add_new_form_field_text_11']; ?> <span style="color:red;">*</span></label>
                            <input type="date" class="form-control" id="txtRDate" name="txtRDate" value="<?php echo $r_date; ?>" required />
                        </div>
                        <div class="form-group">
                            <label for="ddlMonth"><?php echo $_data['select_month']; ?> <span style="color:red;">*</span></label>
                            <select class="form-control" id="ddlMonth" name="ddlMonth" required>
                                <option value=""><?php echo $_data['select_month']; ?></option>
                                <?php 
									$months = $conn->query("SELECT * FROM tbl_add_month_setup");
                                    foreach ($months as $month) {
                                        $selected = $month['m_id'] == $r_month ? 'selected' : '';
										?>
                                        <option value='<?= $month['m_id'] ?>' <?= $selected ?>><?= $month['month_name']?></option>;
										<?php // HMTL
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="ddlYear"><?php echo $_data['select_year']; ?> <span style="color:red;">*</span></label>
                            <select class="form-control" id="ddlYear" name="ddlYear" required>
                                <option value=""><?php echo $_data['select_year']; ?></option>
                                <?php 
                                    $currentYear = date('Y');
                                    for ($i = $currentYear; $i <= $currentYear + 5; $i++) {
                                        $selected = $i == $r_year ? 'selected' : '';
                                        echo "<option value='$i' $selected>$i</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="txtPassword"><?php echo $_data['add_new_form_field_text_3']; ?> <span style="color:red;">*</span></label>
                            <input type="password" autocomplete="current-password" class="form-control" id="txtPassword" name="txtPassword" value="<?php echo $r_password; ?>" required />
                        </div>
                        <div class="form-group">
                            <label for="uploaded_file"><?php echo $_data['add_new_form_field_text_15']; ?></label>
                            <input type="file" class="form-control" id="uploaded_file" name="uploaded_file" />
							<style>
								#uploaded_file{
									font-size: 15px !important;
									width: 100% !important;
									height: 100% !important;
									position: relative;
									opacity: 1	;
								}
							</style>
                            <?php if ($img_track != '') { ?>
                                <img src="<?php echo $image_rnt; ?>" alt="Renter Image" style="height: 50px; width: 50px;" />
                                <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>" />
                            <?php } ?>
                        </div>
                        <div class="form-group">
                            <label for="chkRStaus"><?php echo $_data['add_new_form_field_text_14']; ?></label>
                            <input type="checkbox" id="chkRStaus" name="chkRStaus" <?php echo $r_status == '1' ? 'checked' : ''; ?> />
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary"><?php echo $_data['add_new_renter_breadcam']; ?></button>
                        <a href="<?php echo WEB_URL; ?>rent/rentlist.php" class="btn btn-danger">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>


<!-- /.row -->
<script type="text/javascript">
function validateMe(){
	if($("#txtRName").val() == ''){
		alert("Rented Name Required !!!");
		$("#txtRName").focus();
		return false;
	}
	else if($("#txtREmail").val() == ''){
		alert("Email Required !!!");
		$("#txtREmail").focus();
		return false;
	}
	else if($("#txtPassword").val() == ''){
		alert("Password Required !!!");
		$("#txtPassword").focus();
		return false;
	}
	else if($("#txtRContact").val() == ''){
		alert("Contact Number Required !!!");
		$("#txtRContact").focus();
		return false;
	}
	else if($("#txtRAddress").val() == ''){
		alert("Address Required !!!");
		$("#txtRAddress").focus();
		return false;
	}
	else if($("#txtRentedNID").val() == ''){
		alert("NID Required !!!");
		$("#txtRentedNID").focus();
		return false;
	}
	else if($("#ddlFloorNo").val() == ''){
		alert("Floor Required !!!");
		$("#ddlFloorNo").focus();
		return false;
	}
	else if($("#ddlUnitNo").val() == ''){
		alert("Unit Required !!!");
		$("#ddlUnitNo").focus();
		return false;
	}
	else if($("#txtRAdvance").val() == ''){
		alert("Advance Rent Required !!!");
		$("#txtRAdvance").focus();
		return false;
	}
	else if($("#txtRentPerMonth").val() == ''){
		alert("Rent Per Month Required !!!");
		$("#txtRentPerMonth").focus();
		return false;
	}
	else if($("#txtRDate").val() == ''){
		alert("Rent Date Required !!!");
		$("#txtRDate").focus();
		return false;
	}
	else if($("#ddlMonth").val() == ''){
		alert("Rented Month Required !!!");
		$("#ddlMonth").focus();
		return false;
	}
	else if($("#ddlYear").val() == ''){
		alert("Rented Year Required !!!");
		$("#ddlYear").focus();
		return false;
	}
	else{
		return true;
	}
}
</script>
<?php include('../footer.php'); ?>
