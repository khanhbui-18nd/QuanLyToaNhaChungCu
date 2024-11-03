<?php
// Include necessary files
include('../header.php');
include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_add_m_committee.php');

if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    die();
}
include '../utility/common.php';
include '../config.php';
$success = "none";
$mc_name = $mc_email = $mc_contact = $mc_pre_address = $mc_per_address = $mc_nid = $member_type = '';
$mc_joining_date = $mc_ending_date = '';
$mc_status = '0';
$mc_password = '';
$branch_id = '';
$title = $_data['add_new_m_committee'];
$button_text = $_data['save_button_text'];
$successful_msg = $_data['added_m_committee_successfully'];
$form_url = WEB_URL . "management/add_m_committee.php";
$id = "";
$hdnid = "0";
$image_mc = WEB_URL . 'img/no_image.jpg';
$img_track = '';
$rowx_unit = array();

if (isset($_POST['txtMCName'])) {
    $mc_password = generateStrongPassword();
    $image_url = uploadImage();

    if (isset($_POST['chkRStaus'])) {
        $mc_status = 1;
    }

    if (isset($_POST['hdn']) && $_POST['hdn'] == '0') {
        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO tbl_add_management_committee(mc_name, mc_email, mc_contact, mc_pre_address, mc_per_address, mc_nid, member_type, mc_joining_date, mc_ending_date, mc_password, mc_status, image, branch_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssssssssss", $_POST['txtMCName'], $_POST['txtMCEmail'], $_POST['txtMCContact'], $_POST['txtMCPreAddress'], $_POST['txtMCPerAddress'], $_POST['txtMCNID'], $_POST['ddlMemberType'], $_POST['txtMCJoiningDate'], $_POST['txtMCEndingDate'], $mc_password, $mc_status, $image_url, $_SESSION['objLogin']['branch_id']);
        
        $stmt->execute();
        $stmt->close();

        header("Location: " . WEB_URL . 'management/m_committee_list.php?m=add');
    } else {
        $image_url = $image_url == '' ? $_POST['img_exist'] : $image_url;

        if (isset($_POST['chkRStaus'])) {
            $mc_status = 1;
        }

        // Prepare and bind for update
		$stmt = $conn->prepare("UPDATE tbl_add_management_committee SET mc_name=?, mc_email=?, mc_contact=?, mc_pre_address=?, mc_per_address=?, mc_nid=?, member_type=?, mc_joining_date=?, mc_ending_date=?, mc_status=?, image=? WHERE mc_id=?");
		$stmt->bind_param("sssssssssssi", $_POST['txtMCName'], $_POST['txtMCEmail'], $_POST['txtMCContact'], $_POST['txtMCPreAddress'], $_POST['txtMCPerAddress'], $_POST['txtMCNID'], $_POST['ddlMemberType'], $_POST['txtMCJoiningDate'], $_POST['txtMCEndingDate'], $mc_status, $image_url, $_GET['id']);

        
        $stmt->execute();
        $stmt->close();

        header("Location: " . WEB_URL . 'management/m_committee_list.php?m=up');
    }

    $success = "block";
}

if (isset($_GET['id']) && $_GET['id'] != '') {
    $stmt = $conn->prepare("SELECT * FROM tbl_add_management_committee WHERE mc_id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $mc_name = $row['mc_name'];
        $mc_email = $row['mc_email'];
        $mc_contact = $row['mc_contact'];
        $mc_pre_address = $row['mc_pre_address'];
        $mc_per_address = $row['mc_per_address'];
        $mc_nid = $row['mc_nid'];
        $member_type = $row['member_type'];
        $mc_joining_date = $row['mc_joining_date'];
        $mc_ending_date = $row['mc_ending_date'];
        $mc_status = $row['mc_status'];
        if ($row['image'] != '') {
            $image_mc = WEB_URL . 'img/upload/' . $row['image'];
            $img_track = $row['image'];
        }
        $hdnid = $_GET['id'];
        $title = $_data['update_m_committee'];
        $button_text = $_data['update_button_text'];
        $successful_msg = "Update Management Committee Member Successfully";
        $form_url = WEB_URL . "management/add_m_committee.php?id=" . $_GET['id'];
    }
}

// Image upload function
function uploadImage() {
    if (!empty($_FILES["uploaded_file"]) && $_FILES['uploaded_file']['error'] == 0) {
        $filename = basename($_FILES['uploaded_file']['name']);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'png', 'gif']) && in_array($_FILES["uploaded_file"]["type"], ['image/jpeg', 'image/png', 'image/gif'])) {
            $newfilename = NewGuid() . '.' . $ext;
            move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], ROOT_PATH . '/img/upload/' . $newfilename);
            return $newfilename;
        }
    }
    return '';
}

function NewGuid() {
    $s = strtoupper(md5(uniqid(rand(), true)));
    return substr($s, 0, 8) . '-' . substr($s, 8, 4) . '-' . substr($s, 12, 4) . '-' . substr($s, 16, 4) . '-' . substr($s, 20);
}
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo $title; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo WEB_URL ?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam']; ?></a></li>
        <li class="active"><?php echo $_data['m_committee']; ?></li>
        <li class="active"><?php echo $_data['add_new_m_committee_breadcam']; ?></li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <!-- Full Width boxes (Stat box) -->
    <div class="row">
        <div class="col-md-12">
            <div align="right" style="margin-bottom:1%;">
                <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>management/m_committee_list.php" data-original-title="<?php echo $_data['back_text']; ?>">
                    <i class="fa fa-reply"></i>
                </a>
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?php echo $_data['add_new_m_committee_entry_form']; ?></h3>
                </div>
                <div class="box-body">
                    <form action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="hdn" value="<?php echo $hdnid; ?>">
                        
                        <div class="form-group">
                            <label><?php echo $_data['add_new_form_field_text_1']; ?>:</label>
                            <input type="text" class="form-control" name="txtMCName" value="<?php echo $mc_name; ?>" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['add_new_form_field_text_2']; ?>:</label>
                            <input type="email" class="form-control" name="txtMCEmail" value="<?php echo $mc_email; ?>" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['add_new_form_field_text_3']; ?>:</label>
                            <input type="password" class="form-control" name="txtMCPassword" value="<?php echo $mc_password; ?>" <?php echo ($hdnid == "0" ? 'required' : ''); ?>>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['add_new_form_field_text_4']; ?>:</label>
                            <input type="text" class="form-control" name="txtMCContact" value="<?php echo $mc_contact; ?>" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['add_new_form_field_text_5']; ?>:</label>
                            <textarea class="form-control" name="txtMCPreAddress" required><?php echo $mc_pre_address; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['add_new_form_field_text_6']; ?>:</label>
                            <textarea class="form-control" name="txtMCPerAddress" required><?php echo $mc_per_address; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['add_new_form_field_text_7']; ?>:</label>
                            <input type="text" class="form-control" name="txtMCNID" value="<?php echo $mc_nid; ?>" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['add_new_form_field_text_8']; ?>:</label>
                            <select name="ddlMemberType" class="form-control" required>
                                <?php 
								$types = $conn->query("SELECT * FROM tbl_add_member_type");
								foreach ($types as $type){
									?><option value="<?= $type['member_id'] ?>"><?= $type['member_type'] ?></option><?php //HTML
								}
								?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['add_new_form_field_text_9']; ?>:</label>
                            <input type="date" class="form-control" name="txtMCJoiningDate" value="<?php echo $mc_joining_date; ?>" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo $_data['add_new_form_field_text_10']; ?>:</label>
                            <input type="date" class="form-control" name="txtMCEndingDate" value="<?php echo $mc_ending_date; ?>" required>
                        </div>
                        <div class="form-group">
                            <label><?php echo 'Image'; ?>:</label>
                            <input type="file" name="uploaded_file" id="uploaded_file" accept="image/*">
							<style>
								#uploaded_file{
									font-size: 15px !important;
									width: 100% !important;
									height: 100% !important;
									position: relative;
									opacity: 1	;
								}
							</style>
                        </div>
                        <div class="form-group">
                            <label><?php echo 'Status' ; ?>:</label>
                            <input type="checkbox" name="chkRStaus" <?php echo ($mc_status == '1' ? 'checked' : ''); ?>>
                        </div>
                        <div class="form-group">
                            <img src="<?php echo $image_mc; ?>" alt="Image" style="width: 100px; height: 100px;">
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-success"><?php echo $button_text; ?></button>
                        </div>
                    </form>
                </div>
                <!-- /.box -->
            </div>
        </div>
    </div>
</section>
<?php include('../footer.php'); ?>
