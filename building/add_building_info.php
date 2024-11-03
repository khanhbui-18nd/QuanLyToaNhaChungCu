<?php 
include('../header.php');
include('../utility/common.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_add_building_info.php');

if(!isset($_SESSION['objLogin'])){
    header("Location: " . WEB_URL . "logout.php");
    die();
}
include '../config.php';
$success = "none";
$name = '';
$address = '';
$security_guard_mobile = '';
$secrataty_mobile = '';
$moderator_mobile = '';
$building_make_year = '';
$building_image = '';
$b_name = '';
$b_address = '';
$b_phone = '';
$branch_id = '';
$title = $_data['text_1'];
$button_text = $_data['save_button_text'];
$form_url = WEB_URL . "building/add_building_info.php";
$id = "";
$hdnid = "0";
$image_building = WEB_URL . 'img/no_image.jpg';
$img_track = '';
$rowx_unit = array();

if(isset($_POST['txtBName'])){
    // Xóa dữ liệu cũ
    $sqlx = "DELETE FROM `tbl_add_building_info`";
    $conn->query($sqlx);

    // Upload hình ảnh
    $image_url = uploadImage();

    // Chuẩn bị câu lệnh insert
    $stmt = $conn->prepare("INSERT INTO tbl_add_building_info(name, address, security_guard_mobile, secrataty_mobile, moderator_mobile, building_make_year, b_name, b_address, b_phone, building_image, branch_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssissssss", $_POST['txtBName'], $_POST['txtBAddress'], $_POST['txtBSecurityGuardMobile'], $_POST['txtBSecrataryMobile'], $_POST['txtBModeratorMobile'], $_POST['txtBMakeYear'], $_POST['txtBlName'], $_POST['txtBlAddress'], $_POST['txtBlPhone'], $image_url, $_SESSION['objLogin']['branch_id']);
    $stmt->execute();
    $stmt->close();
}

// Lấy thông tin tòa nhà
$stmt = $conn->prepare("SELECT *, y.y_id, y.xyear FROM tbl_add_building_info bi INNER JOIN tbl_add_year_setup y ON y.y_id = bi.building_make_year WHERE bi.branch_id = ? ORDER BY bi.name");
$stmt->bind_param("i", $_SESSION['objLogin']['branch_id']);
$stmt->execute();
$result = $stmt->get_result();

$buildings = [];
while($row = $result->fetch_assoc()){
    $buildings[] = $row; // Lưu thông tin vào mảng để hiển thị
}
$stmt->close();

// Hàm tải lên hình ảnh
function uploadImage(){
    if((!empty($_FILES["uploaded_file"])) && ($_FILES['uploaded_file']['error'] == 0)) {
        $filename = basename($_FILES['uploaded_file']['name']);
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if(in_array($ext, ['jpg', 'png', 'gif']) && in_array($_FILES["uploaded_file"]["type"], ['image/jpeg', 'image/png', 'image/gif'])){   
            $newfilename = NewGuid() . '.' . $ext;
            move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], ROOT_PATH . '/img/upload/' . $newfilename);
            return $newfilename;
        }
    }
    return '';
}

function NewGuid() { 
    $s = strtoupper(md5(uniqid(rand(), true))); 
    $guidText = 
        substr($s, 0, 8) . '-' . 
        substr($s, 8, 4) . '-' . 
        substr($s, 12, 4). '-' . 
        substr($s, 16, 4). '-' . 
        substr($s, 20); 
    return $guidText;
}	
?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?php echo $title;?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><?php echo $_data['text_2'];?></li>
    <li class="active"><?php echo $_data['text_3'];?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;">
        <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>dashboard.php" data-original-title="<?php echo $_data['back_text'];?>">
            <i class="fa fa-reply"></i>
        </a>
    </div>
    <div class="box box-info">
      <div class="box-header">
        <h3 style="color:red;font-weight:bold;" class="box-title"><?php echo $_data['text_4'];?></h3>
      </div>
      <div class="box-body">
        <!-- Bảng hiển thị thông tin tòa nhà -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Tên Tòa Nhà</th>
                    <th>Địa Chỉ</th>
                    <th>Số Điện Thoại Bảo Vệ</th>
                    <th>Số Điện Thoại Thư Ký</th>
                    <th>Số Điện Thoại Quản Trị</th>
                    <th>Năm Xây Dựng</th>
                    <th>Hình Ảnh</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($buildings as $building): ?>
                <tr>
                    <td><?php echo htmlspecialchars($building['name']); ?></td>
                    <td><?php echo htmlspecialchars($building['address']); ?></td>
                    <td><?php echo htmlspecialchars($building['security_guard_mobile']); ?></td>
                    <td><?php echo htmlspecialchars($building['secrataty_mobile']); ?></td>
                    <td><?php echo htmlspecialchars($building['moderator_mobile']); ?></td>
                    <td><?php echo htmlspecialchars($building['xyear']); ?></td>
                    <td>
                        <?php if($building['building_image']): ?>
                        <img src="<?php echo WEB_URL . 'img/upload/' . $building['building_image']; ?>" alt="Building Image" style="width:100px;height:auto;">
                        <?php else: ?>
                        <img src="<?php echo $image_building; ?>" alt="No Image" style="width:100px;height:auto;">
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
      </div>
    </div>
    <!-- /.box -->
  </div>
</div>
<!-- /.row -->
<script type="text/javascript">
function validateMe(){
    if($("#txtBName").val() == ''){
        alert("Name is Required !!!");
        $("#txtBName").focus();
        return false;
    }
    else if($("#txtBAddress").val() == ''){
        alert("Address is Required !!!");
        $("#txtBAddress").focus();
        return false;
    }
    else if($("#txtBSecurityGuardMobile").val() == ''){
        alert("Security Guard Number is Required !!!");
        $("#txtBSecurityGuardMobile").focus();
        return false;
    }
    else if($("#txtBSecrataryMobile").val() == ''){
        alert("Secratary Number is Required !!!");
        $("#txtBSecrataryMobile").focus();
        return false;
    }
    else if($("#txtBModeratorMobile").val() == ''){
        alert("Moderator Number is Required !!!");
        $("#txtBModeratorMobile").focus();
        return false;
    }
    else if($("#txtBMakeYear").val() == ''){
        alert("Year is Required !!!");
        $("#txtBMakeYear").focus();
        return false;
    }
    else if($("#txtBlName").val() == ''){
        alert("Builder Name is Required !!!");
        $("#txtBlName").focus();
        return false;
    }
    else if($("#txtBlAddress").val() == ''){
        alert("Builder Address is Required !!!");
        $("#txtBlAddress").focus();
        return false;
    }
    else if($("#txtBlPhone").val() == ''){
        alert("Builder Phone is Required !!!");
        $("#txtBlPhone").focus();
        return false;
    }
    else{
        return true;
    }
}
</script>
<?php include('../footer.php'); ?>
