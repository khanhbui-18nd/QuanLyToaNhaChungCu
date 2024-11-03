<?php 
include('../header.php');
include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_add_unit.php');

if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    die();
}

$success = "none";
$floor_no = '';
$unit_no = '';
$title = isset($_data['add_new_unit']) ? $_data['add_new_unit'] : 'Add New Unit';
$button_text = isset($_data['save_button_text']) ? $_data['save_button_text'] : 'Save';
$successful_msg = isset($_data['add_unit_successfully']) ? $_data['add_unit_successfully'] : 'Unit added successfully';
$form_url = WEB_URL . "unit/addunit.php";
$id = "";
$hdnid = "0";

// Kết nối đến cơ sở dữ liệu
include('../config.php'); // Bao gồm file config để sử dụng biến $conn

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['ddlFloor'])) {
    if (isset($_POST['hdn']) && $_POST['hdn'] == '0') {
        $stmt = $conn->prepare("INSERT INTO `tbl_add_unit` (floor_no, unit_no, branch_id) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $_POST['ddlFloor'], $_POST['txtUnit'], $_SESSION['objLogin']['branch_id']); // Liên kết tham số
        $stmt->execute();

        $url = WEB_URL . 'unit/unitlist.php?m=add';
        header("Location: $url");
        exit();  // Ensure the script stops executing after redirect
    } else {
        $stmt = $conn->prepare("UPDATE `tbl_add_unit` SET `floor_no` = ?, `unit_no` = ? WHERE uid = ?");
        $stmt->bind_param("ssi", $_POST['ddlFloor'], $_POST['txtUnit'], $_GET['id']); // Liên kết tham số
        $stmt->execute();

        $url = WEB_URL . 'unit/unitlist.php?m=up';
        header("Location: $url");
        exit();  // Ensure the script stops executing after redirect
    }
    
    $success = "block";
}

if (isset($_GET['id']) && $_GET['id'] != '') {
    $stmt = $conn->prepare("SELECT * FROM tbl_add_unit WHERE uid = ?");
    $stmt->bind_param("i", $_GET['id']); // Liên kết tham số
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $floor_no = $row['floor_no'];
        $unit_no = $row['unit_no'];
        $hdnid = $_GET['id'];
        $title = 'Update Unit';  // Updated title for clarity
        $button_text = isset($_data['update_button_text']) ? $_data['update_button_text'] : 'Update';
        $successful_msg = isset($_data['update_unit_successfully']) ? $_data['update_unit_successfully'] : 'Unit updated successfully';
        $form_url = WEB_URL . "unit/addunit.php?id=" . $_GET['id'];
    }
}

if (isset($_GET['mode']) && $_GET['mode'] == 'view') {
    $title = 'View Unit Details';
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?php echo $title; ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo isset($_data['home_breadcam']) ? $_data['home_breadcam'] : 'Home';?></a></li>
    <li class="active"><?php echo isset($_data['add_new_unit_information_breadcam']) ? $_data['add_new_unit_information_breadcam'] : 'Unit Info';?></li>
    <li class="active"><?php echo isset($_data['add_new_unit_breadcam']) ? $_data['add_new_unit_breadcam'] : 'Add Unit';?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;">
      <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>unit/unitlist.php" data-original-title="<?php echo isset($_data['back_text']) ? $_data['back_text'] : 'Back';?>">
        <i class="fa fa-reply"></i>
      </a>
    </div>
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title"><?php echo isset($_data['add_new_unit_entry_form']) ? $_data['add_new_unit_entry_form'] : 'Add Unit Form';?></h3>
      </div>
      <div class="box-body">
        <!-- Form bắt đầu ở đây -->
        <form action="<?php echo $form_url; ?>" method="post" onsubmit="return validateMe();">
            <input type="hidden" name="hdn" value="<?php echo $hdnid; ?>">
            <div class="form-group">
                <label for="ddlFloor"><?php echo isset($_data['select_floor']) ? $_data['select_floor'] : 'Select Floor'; ?></label>
                <select name="ddlFloor" id="ddlFloor" class="form-control" required>
                    <option value="">-- Select Floor --</option>
                    <?php
                    // Lấy danh sách tầng
                    $floors = $conn->query("SELECT * FROM tbl_add_floor");
                    while ($floor = $floors->fetch_assoc()) {
                        $selected = ($floor['fid'] == $floor_no) ? 'selected' : '';
                        echo "<option value='{$floor['fid']}' $selected>{$floor['floor_no']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label for="txtUnit"><?php echo isset($_data['enter_unit']) ? $_data['enter_unit'] : 'Enter Unit'; ?></label>
                <input type="text" name="txtUnit" id="txtUnit" class="form-control" value="<?php echo $unit_no; ?>" required>
            </div>
            <button type="submit" class="btn btn-success"><?php echo $button_text; ?></button>
        </form>
        <!-- Form kết thúc ở đây -->
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function validateMe() {
    if ($("#ddlFloor").val() == '') {
        alert("Select Floor !!!");
        $("#ddlFloor").focus();
        return false;
    } else if ($("#txtUnit").val() == '') {
        alert("Unit Required !!!");
        $("#txtUnit").focus();
        return false;
    } else {
        return true;
    }
}

// Adding passive event listeners
document.addEventListener('touchmove', function(event) {
    // Handle the touchmove event
}, { passive: true });
</script>
<?php include('../footer.php'); ?>
