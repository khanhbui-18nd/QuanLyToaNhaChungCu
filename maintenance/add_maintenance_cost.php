<?php 
include('../header.php');
include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_add_maintenance_cost.php');
if(!isset($_SESSION['objLogin'])){
	header("Location: " . WEB_URL . "logout.php");
	die();
}
$success = "none";
$m_title = '';
$m_date = '';
$m_amount = '';
$m_details = '';
$m_month = 0;
$m_year = 0;
$branch_id = '';
$title = $_data['add_title_text'];
$button_text = $_data['save_button_text'];
$successful_msg = $_data['add_msg'];
$form_url = WEB_URL . "maintenance/add_maintenance_cost.php";
$id = "";
$hdnid = "0";

include('../config.php'); // Use the updated connection variable $conn

if(isset($_POST['txtMTitle'])){
	if(isset($_POST['hdn']) && $_POST['hdn'] == '0'){
		$sql = "INSERT INTO tbl_add_maintenance_cost(m_title, m_date, xmonth, xyear, m_amount, m_details, branch_id) 
		        VALUES (?, ?, ?, ?, ?, ?, ?)";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ssiiisi", $_POST['txtMTitle'], $_POST['txtMDate'], $_POST['ddlMonth'], $_POST['ddlYear'], $_POST['txtMAmount'], $_POST['txtMDetails'], $_SESSION['objLogin']['branch_id']);
		$stmt->execute();
		$stmt->close();

		$url = WEB_URL . 'maintenance/maintenance_cost_list.php?m=add';
		header("Location: $url");
	}
	else {
		$sql = "UPDATE tbl_add_maintenance_cost 
		        SET m_title = ?, m_date = ?, xmonth = ?, xyear = ?, m_amount = ?, m_details = ? 
		        WHERE mcid = ?";
		$stmt = $conn->prepare($sql);
		$stmt->bind_param("ssiiisi", $_POST['txtMTitle'], $_POST['txtMDate'], $_POST['ddlMonth'], $_POST['ddlYear'], $_POST['txtMAmount'], $_POST['txtMDetails'], $_GET['id']);
		$stmt->execute();
		$stmt->close();

		$url = WEB_URL . 'maintenance/maintenance_cost_list.php?m=up';
		header("Location: $url");
	}

	$success = "block";
}

if(isset($_GET['id']) && $_GET['id'] != ''){
	$sql = "SELECT * FROM tbl_add_maintenance_cost WHERE mcid = ?";
	$stmt = $conn->prepare($sql);
	$stmt->bind_param("i", $_GET['id']);
	$stmt->execute();
	$result = $stmt->get_result();

	while($row = $result->fetch_assoc()){
		$m_title = $row['m_title'];
		$m_date = $row['m_date'];
		$m_amount = $row['m_amount'];
		$m_details = $row['m_details'];
		$m_month = $row['xmonth'];
		$m_year = $row['xyear'];
		$hdnid = $_GET['id'];
		$title = $_data['update_title_text'];
		$button_text = $_data['update_button_text'];
		$successful_msg = $_data['update_msg'];
		$form_url = WEB_URL . "maintenance/add_maintenance_cost.php?id=" . $_GET['id'];
	}
	$stmt->close();
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
  <h1><?php echo $title;?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL ?>/dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><?php echo $_data['maintenance_cost'];?></li>
    <li class="active"><?php echo $_data['add_m_cost'];?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"> 
      <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>maintenance/maintenance_cost_list.php" data-original-title="<?php echo $_data['back_text'];?>"><i class="fa fa-reply"></i></a> 
    </div>
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['m_cost_entry_form'];?></h3>
      </div>
      <div class="box-body">
        <form action="<?php echo $form_url; ?>" method="post">
          <input type="hidden" name="hdn" value="<?php echo $hdnid; ?>" />
          <div class="form-group">
            <label><?php echo $_data['text_1']; ?></label>
            <input type="text" class="form-control" name="txtMTitle" value="<?php echo $m_title; ?>" required>
          </div>
          <div class="form-group">
            <label><?php echo $_data['date']; ?></label>
            <input type="date" class="form-control" name="txtMDate" value="<?php echo $m_date; ?>" required>
          </div>
          <div class="form-group">
            <label><?php echo $_data['month']; ?></label>
            <select class="form-control" name="ddlMonth" required>
              <option value=""><?php echo $_data['select_month']; ?></option>
              <?php for($i=1; $i<=12; $i++): ?>
                <option value="<?php echo $i; ?>" <?php echo ($m_month == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <div class="form-group">
            <label><?php echo $_data['year']; ?></label>
            <select class="form-control" name="ddlYear" required>
              <option value=""><?php echo $_data['select_year']; ?></option>
              <?php for($i=date("Y"); $i>=2000; $i--): ?>
                <option value="<?php echo $i; ?>" <?php echo ($m_year == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
              <?php endfor; ?>
            </select>
          </div>
          <div class="form-group">
            <label><?php echo $_data['text_2']; ?></label>
            <input type="number" class="form-control" name="txtMAmount" value="<?php echo $m_amount; ?>" required>
          </div>
          <div class="form-group">
            <label><?php echo $_data['text_3']; ?></label>
            <textarea class="form-control" name="txtMDetails" required><?php echo $m_details; ?></textarea>
          </div>
          <button type="submit" class="btn btn-success"><?php echo $button_text; ?></button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- /.row -->
<?php include('../footer.php'); ?>
