<?php 
include('../header.php');
include('../utility/common.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_add_owner.php');

if(!isset($_SESSION['objLogin'])){
    header("Location: " . WEB_URL . "logout.php");
    die();
}
include '../config.php';
$success = "none";
$o_name = '';
$o_email = '';
$o_contact = '';
$o_pre_address = '';
$o_per_address = '';
$o_nid = '';
$o_password = '';
$owner_unit = '';
$branch_id = '';
$title = $_data['add_new_owner'];
$button_text = $_data['save_button_text'];
$successful_msg = $_data['added_owner_successfully'];
$form_url = WEB_URL . "owner/addowner.php";
$id = "";
$hdnid = "0";
$image_own = WEB_URL . 'img/no_image.jpg';
$img_track = '';
$rowx_unit = array();

// Process form submission
if (isset($_POST['txtOwnerName'])) {
    $o_password = $_POST['txtPassword'];
    $image_url = uploadImage();
    
    if (isset($_POST['hdn']) && $_POST['hdn'] == '0') {
        // Insert new owner
        $stmt = $conn->prepare("INSERT INTO tbl_add_owner (o_name, o_email, o_contact, o_pre_address, o_per_address, o_nid, o_password, image, branch_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('sssssssss', $_POST['txtOwnerName'], $_POST['txtOwnerEmail'], $_POST['txtOwnerContact'], $_POST['txtOwnerPreAddress'], $_POST['txtOwnerPerAddress'], $_POST['txtOwnerNID'], $o_password, $image_url, $_SESSION['objLogin']['branch_id']);
        $stmt->execute();
        $last_id = $conn->insert_id;

        if (isset($_POST['ChkOwnerUnit'])) {
            foreach ($_POST['ChkOwnerUnit'] as $value) {
                $stmt_unit = $conn->prepare("INSERT INTO tbl_add_owner_unit_relation (owner_id, unit_id) VALUES (?, ?)");
                $stmt_unit->bind_param('ii', $last_id, $value);
                $stmt_unit->execute();
            }
        }
        
        $stmt->close();
        $stmt_unit->close();
        $url = WEB_URL . 'owner/ownerlist.php?m=add';
        header("Location: $url");
    } else {
        // Update existing owner
        if ($image_url == '') {
            $image_url = $_POST['img_exist'];
        }
        $stmt = $conn->prepare("UPDATE tbl_add_owner SET o_name=?, o_email=?, o_password=?, o_contact=?, o_pre_address=?, o_per_address=?, o_nid=?, image=? WHERE ownid=?");
        $stmt->bind_param('ssssssssi', $_POST['txtOwnerName'], $_POST['txtOwnerEmail'], $_POST['txtPassword'], $_POST['txtOwnerContact'], $_POST['txtOwnerPreAddress'], $_POST['txtOwnerPerAddress'], $_POST['txtOwnerNID'], $image_url, $_GET['id']);
        $stmt->execute();

        if (isset($_POST['ChkOwnerUnit'])) {
            $stmt_unit = $conn->prepare("DELETE FROM tbl_add_owner_unit_relation WHERE owner_id = ?");
            $stmt_unit->bind_param('i', $_GET['id']);
            $stmt_unit->execute();

            foreach ($_POST['ChkOwnerUnit'] as $value) {
                $stmt_unit_insert = $conn->prepare("INSERT INTO tbl_add_owner_unit_relation (owner_id, unit_id) VALUES (?, ?)");
                $stmt_unit_insert->bind_param('ii', $_GET['id'], $value);
                $stmt_unit_insert->execute();
            }
            $stmt_unit_insert->close();
        } else {
            $stmt_unit = $conn->prepare("DELETE FROM tbl_add_owner_unit_relation WHERE owner_id = ?");
            $stmt_unit->bind_param('i', $_GET['id']);
            $stmt_unit->execute();
        }
        $stmt->close();
        $url = WEB_URL . 'owner/ownerlist.php?m=up';
        header("Location: $url");
    }

    $success = "block";
}

// Load owner details if editing
if (isset($_GET['id']) && $_GET['id'] != '') {
    $stmt = $conn->prepare("SELECT * FROM tbl_add_owner WHERE ownid = ?");
    $stmt->bind_param('i', $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $o_name = $row['o_name'];
        $o_email = $row['o_email'];
        $o_contact = $row['o_contact'];
        $o_pre_address = $row['o_pre_address'];
        $o_per_address = $row['o_per_address'];
        $o_password = $row['o_password'];
        $o_nid = $row['o_nid'];

        if ($row['image'] != '') {
            $image_own = WEB_URL . 'img/upload/' . $row['image'];
            $img_track = $row['image'];
        }
        $hdnid = $_GET['id'];
        $title = 'Update Owner';
        $button_text = $_data['update_button_text'];
        $successful_msg = $_data['update_owner_successfully'];
        $form_url = WEB_URL . "owner/addowner.php?id=" . $_GET['id'];
    }

    $stmt->close();

    // Load owner unit relations
    $stmt_unit = $conn->prepare("SELECT unit_id FROM tbl_add_owner_unit_relation WHERE owner_id = ?");
    $stmt_unit->bind_param('i', $_GET['id']);
    $stmt_unit->execute();
    $result_unit = $stmt_unit->get_result();
    
    while ($row_unit = $result_unit->fetch_assoc()) {
        array_push($rowx_unit, $row_unit['unit_id']);
    }

    $stmt_unit->close();
}

// Function for image upload
function uploadImage() {
    if (!empty($_FILES["uploaded_file"]) && $_FILES['uploaded_file']['error'] == 0) {
        $filename = basename($_FILES['uploaded_file']['name']);
        $ext = substr($filename, strrpos($filename, '.') + 1);
        if (($ext == "jpg" && $_FILES["uploaded_file"]["type"] == 'image/jpeg') || ($ext == "png" && $_FILES["uploaded_file"]["type"] == 'image/png') || ($ext == "gif" && $_FILES["uploaded_file"]["type"] == 'image/gif')) {
            $temp = explode(".", $_FILES["uploaded_file"]["name"]);
            $newfilename = NewGuid() . '.' . end($temp);
            move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], ROOT_PATH . '/img/upload/' . $newfilename);
            return $newfilename;
        } else {
            return '';
        }
    }
    return '';
}

// Function to generate a new GUID
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
  <h1><?php echo $_data['add_new_owner']; ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL ?>/dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam']; ?></a></li>
    <li class="active"><?php echo $_data['add_new_owner_information_breadcam']; ?></li>
    <li class="active"><?php echo $_data['add_new_owner_breadcam']; ?></li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;">
      <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>owner/ownerlist.php" data-original-title="<?php echo $_data['back_text']; ?>">
        <i class="fa fa-reply"></i>
      </a>
    </div>
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['add_new_owner_entry_form']; ?></h3>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->

<script type="text/javascript">
function validateMe(){
    if ($("#txtOwnerName").val() == '') {
        alert("Owner Name Required !!!");
        $("#txtOwnerName").focus();
        return false;
    }
    else if ($("#txtOwnerEmail").val() == '') {
        alert("Email Required !!!");
        $("#txtOwnerEmail").focus();
        return false;
    }
    else if ($("#txtPassword").val() == '') {
        alert("Password Required !!!");
        $("#txtPassword").focus();
        return false;
    }
    else if ($("#txtOwnerContact").val() == '') {
        alert("Contact Number Required !!!");
        $("#txtOwnerContact").focus();
        return false;
    }
    else if ($("#txtOwnerPreAddress").val() == '') {
        alert("Present Address Required !!!");
        $("#txtOwnerPreAddress").focus();
        return false;
    }
    else if ($("#txtOwnerPerAddress").val() == '') {
        alert("Permanent Address Required !!!");
        $("#txtOwnerPerAddress").focus();
        return false;
    }
    else if ($("#txtOwnerNID").val() == '') {
        alert("NID Required !!!");
        $("#txtOwnerNID").focus();
        return false;
    }
    else {
        return true;
    }
}
</script>

<?php include('../footer.php'); ?>
