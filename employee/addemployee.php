<?php 
include('../header.php');
include('../utility/common.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_add_employee.php');
include '../language/English/lang_add_employee.php';
include '../config.php';

if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    die();
}

// Initialize variables
$success = "none";
$e_name = $e_email = $e_contact = $e_pre_address = $e_per_address = $e_nid = '';
$e_designation = 0;
$e_date = $ending_date = '';
$e_status = 0;
$e_password = '';
$branch_id = '';
$title = $_data['add_new_employee'];
$button_text = $_data['save_button_text'];
$successful_msg = $_data['added_employee_successfully'];
$form_url = WEB_URL . "employee/addemployee.php";
$id = "";
$hdnid = "0";
$image_emp = WEB_URL . 'img/no_image.jpg';
$img_track = '';

if (isset($_POST['txtEmpName'])) {
    $e_password = $_POST['txtPassword'];
    $image_url = uploadImage();
    $e_status = isset($_POST['chkEmpStaus']) ? 1 : 0; // Set status based on checkbox

    if (isset($_POST['hdn']) && $_POST['hdn'] == '0') {
        // Insert new employee
        $stmt = $conn->prepare("INSERT INTO tbl_add_employee (e_name, e_email, e_contact, e_pre_address, e_per_address, e_nid, e_designation, e_date, ending_date, e_password, e_status, image, branch_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssississi", $_POST['txtEmpName'], $_POST['txtEmpEmail'], $_POST['txtEmpContact'], $_POST['txtEmpPreAddress'], $_POST['txtEmpPerAddress'], $_POST['txtEmpNID'], $_POST['ddlMemberType'], $_POST['txtEmpDate'], $_POST['txtEndingDate'], $e_password, $e_status, $image_url, $_SESSION['objLogin']['branch_id']);
        
        if ($stmt->execute()) {
            $url = WEB_URL . 'employee/employeelist.php?m=add';
            header("Location: $url");
            exit();
        }
        $stmt->close();
    } else {
        // Update existing employee
        $image_url = $image_url ?: $_POST['img_exist'];
        $stmt = $conn->prepare("UPDATE tbl_add_employee SET e_name=?, e_email=?, e_password=?, e_contact=?, e_pre_address=?, e_per_address=?, e_nid=?, e_designation=?, e_date=?, ending_date=?, e_status=?, image=? WHERE eid=?");
        $stmt->bind_param("ssssssississi", $_POST['txtEmpName'], $_POST['txtEmpEmail'], $e_password, $_POST['txtEmpContact'], $_POST['txtEmpPreAddress'], $_POST['txtEmpPerAddress'], $_POST['txtEmpNID'], $_POST['ddlMemberType'], $_POST['txtEmpDate'], $_POST['txtEndingDate'], $e_status, $image_url, $_GET['id']);
        
        if ($stmt->execute()) {
            $url = WEB_URL . 'employee/employeelist.php?m=up';
            header("Location: $url");
        }
    }
    $success = "block";
}

// Check if we are editing an existing employee
if (isset($_GET['id']) && $_GET['id'] != '') {
    $stmt = $conn->prepare("SELECT * FROM tbl_add_employee WHERE eid = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $e_name = $row['e_name'];
        $e_email = $row['e_email'];
        $e_contact = $row['e_contact'];
        $e_pre_address = $row['e_pre_address'];
        $e_per_address = $row['e_per_address'];
        $e_nid = $row['e_nid'];
        $e_designation = $row['e_designation'];
        $e_date = $row['e_date'];
        $ending_date = $row['ending_date'];
        $e_status = $row['e_status'];
        $e_password = $row['e_password'];
        if ($row['image'] != '') {
            $image_emp = WEB_URL . 'img/upload/' . $row['image'];
            $img_track = $row['image'];
        }
        $hdnid = $_GET['id'];
        $title = $_data['update_employee'];
        $button_text = $_data['update_button_text'];
        $successful_msg = "Update Employee Successfully";
        $form_url = WEB_URL . "employee/addemployee.php?id=" . $_GET['id'];
    }
    $stmt->close();
}

$conn->close();

// Function for image upload
function uploadImage() {
    if ((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {
        $filename = basename($_FILES['uploaded_file']['name']);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
            $temp = explode(".", $_FILES["uploaded_file"]["name"]);
            $newfilename = NewGuid() . '.' . end($temp);
            move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], ROOT_PATH . '/img/upload/' . $newfilename);
            return $newfilename;
        }
    }
    return '';
}

function NewGuid() { 
    $s = strtoupper(md5(uniqid(rand(), true))); 
    return substr($s, 0, 8) . '-' . 
           substr($s, 8, 4) . '-' . 
           substr($s, 12, 4). '-' . 
           substr($s, 16, 4). '-' . 
           substr($s, 20); 
}
?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?php echo $title; ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>/dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam']; ?></a></li>
    <li class="active"><?php echo $_data['add_new_employee_information_breadcam']; ?></li>
    <li class="active"><?php echo $_data['add_new_employee_breadcam']; ?></li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div align="right" style="margin-bottom:1%;">
        <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>employee/employeelist.php" data-original-title="<?php echo $_data['back_text']; ?>"><i class="fa fa-reply"></i></a>
      </div>
      <div class="box box-info">
        <div class="box-header">
          <h3 class="box-title"><?php echo $_data['add_new_employee_entry_form']; ?></h3>
        </div>
        <div class="box-body">
          <form method="post" enctype="multipart/form-data" action="<?php echo $form_url; ?>" onsubmit="return validateMe();">
            <input type="hidden" name="hdn" value="<?php echo $hdnid; ?>">
            <div class="form-group">
              <label for="txtEmpName"><?php echo $_data['add_new_form_field_text_1']; ?></label>
              <input type="text" class="form-control" id="txtEmpName" name="txtEmpName" value="<?php echo $e_name; ?>" placeholder="<?php echo $_data['add_new_form_field_text_1']; ?>" required>
            </div>
            <div class="form-group">
              <label for="txtEmpEmail"><?php echo $_data['add_new_form_field_text_2']; ?></label>
              <input type="email" class="form-control" id="txtEmpEmail" name="txtEmpEmail" value="<?php echo $e_email; ?>" placeholder="<?php echo $_data['add_new_form_field_text_2']; ?>" required>
            </div>
            <div class="form-group">
              <label for="txtPassword"><?php echo $_data['add_new_form_field_text_3']; ?></label>
              <input type="password" class="form-control" id="txtPassword" name="txtPassword" value="<?php echo $e_password; ?>" placeholder="<?php echo $_data['add_new_form_field_text_3']; ?>" required>
            </div>
            <div class="form-group">
              <label for="txtEmpContact"><?php echo $_data['add_new_form_field_text_4']; ?></label>
              <input type="text" class="form-control" id="txtEmpContact" name="txtEmpContact" value="<?php echo $e_contact; ?>" placeholder="<?php echo $_data['add_new_form_field_text_4']; ?>" required>
            </div>
            <div class="form-group">
              <label for="txtEmpPreAddress"><?php echo $_data['add_new_form_field_text_5']; ?></label>
              <input type="text" class="form-control" id="txtEmpPreAddress" name="txtEmpPreAddress" value="<?php echo $e_pre_address; ?>" placeholder="<?php echo $_data['add_new_form_field_text_5']; ?>" required>
            </div>
            <div class="form-group">
              <label for="txtEmpPerAddress"><?php echo $_data['add_new_form_field_text_6']; ?></label>
              <input type="text" class="form-control" id="txtEmpPerAddress" name="txtEmpPerAddress" value="<?php echo $e_per_address; ?>" placeholder="<?php echo $_data['add_new_form_field_text_6']; ?>" required>
            </div>
            <div class="form-group">
              <label for="txtEmpNID"><?php echo $_data['add_new_form_field_text_7']; ?></label>
              <input type="text" class="form-control" id="txtEmpNID" name="txtEmpNID" value="<?php echo $e_nid; ?>" placeholder="<?php echo $_data['add_new_form_field_text_7']; ?>" required>
            </div>
            <div class="form-group">
              <label for="ddlMemberType"><?php echo $_data['add_new_form_field_text_8']; ?></label>
              <select class="form-control" id="ddlMemberType" name="ddlMemberType" required>
                <option value=""><?php echo $_data['add_new_form_field_text_8']; ?></option>
                <!-- Add your designation options here -->
                <option value="1" <?php echo ($e_designation == 1) ? 'selected' : ''; ?>>Designation 1</option>
                <option value="2" <?php echo ($e_designation == 2) ? 'selected' : ''; ?>>Designation 2</option>
                <!-- More options... -->
              </select>
            </div>
            <div class="form-group">
              <label for="txtEmpDate"><?php echo $_data['add_new_form_field_text_9']; ?></label>
              <input type="date" class="form-control" id="txtEmpDate" name="txtEmpDate" value="<?php echo $e_date; ?>" required>
            </div>
            <div class="form-group">
              <label for="txtEndingDate"><?php echo $_data['add_new_form_field_text_10']; ?></label>
              <input type="date" class="form-control" id="txtEndingDate" name="txtEndingDate" value="<?php echo $ending_date; ?>">
            </div>
            <div class="form-group">
              <label for="uploaded_file"><?php echo $_data['upload_image']; ?></label>
              <input type="file" id="uploaded_file" name="uploaded_file">
			  <style>
								#uploaded_file{
									font-size: 15px !important;
									width: 100% !important;
									height: 100% !important;
									position: relative;
									opacity: 1	;
								}
							</style>
              <?php if ($img_track != ''): ?>
                  <img src="<?php echo $image_emp; ?>" alt="Employee Image" style="width: 100px; height: auto;"/>
                  <input type="hidden" name="img_exist" value="<?php echo $img_track; ?>">
              <?php endif; ?>
            </div>
            <div class="form-group">
              <label>
                <input type="checkbox" name="chkEmpStaus" <?php echo ($e_status == 1) ? 'checked' : ''; ?>> <?php echo $_data['add_new_form_field_text_11']; ?>
              </label>
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
    if ($("#txtEmpName").val() == '') {
        alert("<?php echo $_data['employee_name_required']; ?>");
        $("#txtEmpName").focus();
        return false;
    } else if ($("#txtEmpEmail").val() == '') {
        alert("<?php echo $_data['email_required']; ?>");
        $("#txtEmpEmail").focus();
        return false;
    } else if ($("#txtPassword").val() == '') {
        alert("<?php echo $_data['password_required']; ?>");
        $("#txtPassword").focus();
        return false;
    } else if ($("#txtEmpContact").val() == '') {
        alert("<?php echo $_data['contact_number_required']; ?>");
        $("#txtEmpContact").focus();
        return false;
    } else if ($("#txtEmpPreAddress").val() == '') {
        alert("<?php echo $_data['present_address_required']; ?>");
        $("#txtEmpPreAddress").focus();
        return false;
    } else if ($("#txtEmpPerAddress").val() == '') {
        alert("<?php echo $_data['permanent_address_required']; ?>");
        $("#txtEmpPerAddress").focus();
        return false;
    } else if ($("#txtEmpNID").val() == '') {
        alert("<?php echo $_data['nid_required']; ?>");
        $("#txtEmpNID").focus();
        return false;
    } else if ($("#ddlMemberType").val() == '') {
        alert("<?php echo $_data['designation_required']; ?>");
        $("#ddlMemberType").focus();
        return false;
    } else if ($("#txtEmpDate").val() == '') {
        alert("<?php echo $_data['joining_date_required']; ?>");
        $("#txtEmpDate").focus();
        return false;
    } else {
        return true;
    }
}
</script>

<?php include('../footer.php'); ?>
