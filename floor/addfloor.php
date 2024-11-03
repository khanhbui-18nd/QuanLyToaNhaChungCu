<?php 
include('../header.php');
include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_add_floor.php');

// Kiểm tra xem người dùng đã đăng nhập chưa
if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    exit(); // Sử dụng exit thay vì die
}

$success = "none";
$floor_no = '';
$branch_id = '';
$title = 'Add New Floor';
$button_text = $_data['save_button_text'];
$successful_msg = "Add Floor Successfully";
$form_url = WEB_URL . "floor/addfloor.php";
$hdnid = "0";

// Kết nối đến cơ sở dữ liệu
include('../config.php'); // Bao gồm file config để sử dụng biến $conn

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['txtFloor'])) {
    $floor_no = make_safe($_POST['txtFloor']); // Gọi hàm sanitize

    if (isset($_POST['hdn']) && $_POST['hdn'] == '0') {
        // Thêm mới
        $stmt = $conn->prepare("INSERT INTO `tbl_add_floor` (floor_no, `branch_id`) VALUES (?, ?)");
        $stmt->bind_param("si", $floor_no, $_SESSION['objLogin']['branch_id']); // Liên kết tham số
    } else {
        // Cập nhật
        $stmt = $conn->prepare("UPDATE `tbl_add_floor` SET `floor_no` = ? WHERE fid = ?");
        $stmt->bind_param("si", $floor_no, $_GET['id']); // Liên kết tham số
    }
    
    if ($stmt->execute()) {
        // Chuyển hướng sau khi thực hiện thành công
        $url = WEB_URL . 'floor/floorlist.php?' . (isset($_POST['hdn']) && $_POST['hdn'] == '0' ? 'm=add' : 'm=up');
        header("Location: $url");
        exit();
    }
}

// Lấy thông tin nếu đang chỉnh sửa
if (isset($_GET['id']) && $_GET['id'] != '') {
    $stmt = $conn->prepare("SELECT * FROM tbl_add_floor WHERE fid = ?");
    $stmt->bind_param("i", $_GET['id']); // Liên kết tham số
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $floor_no = $row['floor_no'];
        $hdnid = $_GET['id'];
        $title = 'Update Floor';
        $button_text = $_data['update_button_text'];
        $successful_msg = "Update Floor Successfully";
        $form_url = WEB_URL . "floor/addfloor.php?id=" . $_GET['id'];
    }
}

if (isset($_GET['mode']) && $_GET['mode'] == 'view') {
    $title = 'View Floor Details';
}

// Hàm để sanitize đầu vào
function make_safe($variable) {
    global $conn; // Đảm bảo sử dụng kết nối MySQLi
    return $conn->real_escape_string(strip_tags(trim($variable)));
}
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo $_data['add_new_floor_top_title'];?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam'];?></a></li>
        <li class="active"><?php echo $_data['add_new_floor_information_breadcam'];?></li>
        <li class="active"><?php echo $_data['add_new_add_floor_breadcam'];?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div align="right" style="margin-bottom:1%;">
                <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>floor/floorlist.php" data-original-title="<?php echo $_data['back_text'];?>"><i class="fa fa-reply"></i></a>
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?php echo $_data['add_new_floor_entry_text'];?></h3>
                </div>
                <div class="box-body">
                    <form action="<?php echo $form_url; ?>" method="post" onsubmit="return validateMe();">
                        <input type="hidden" name="hdn" value="<?php echo $hdnid; ?>" />
                        <div class="form-group">
                            <label for="txtFloor">Floor Number:</label>
                            <input type="text" id="txtFloor" name="txtFloor" class="form-control" value="<?php echo $floor_no; ?>" required />
                        </div>
                        <button type="submit" class="btn btn-primary"><?php echo $button_text; ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        function validateMe() {
            if ($("#txtFloor").val() === '') {
                alert("Floor Required !!!");
                $("#txtFloor").focus();
                return false;
            }
            return true;
        }
    </script>
</section>

<?php include('../footer.php'); ?>
