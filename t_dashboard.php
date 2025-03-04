<?php include('header_ten.php'); ?>
<?php
include(ROOT_PATH.'language/'.'English'.'/lang_admin_dashboard.php');
include(ROOT_PATH.'language/'.'English'.'/lang_common.php');

if ($_SESSION['login_type'] != '4') {
    header("Location: " . WEB_URL . "logout.php");
    die();
}
include(__DIR__ . "/config.php");


$total_rent = 0;
$stmt = $conn->prepare("SELECT SUM(rent) as total FROM tbl_add_fair WHERE rid = ?");
$stmt->bind_param("i", $_SESSION['objLogin']['rid']); // Liên kết tham số
$stmt->execute();
$result_amount = $stmt->get_result();

if ($row_amount_total = $result_amount->fetch_assoc()) {
    $total_rent = $row_amount_total['total'];
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?php echo $_data['rented_dashboard']; ?><small><?php echo $_data['c_panel']; ?></small></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL; ?>t_dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home_breadcam']; ?></a></li>
    <li class="active"><?php echo $_data['menu_dashboard']; ?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <div class="row home_dash_box">
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3><?php echo $total_rent . CURRENCY; ?></h3>
          <p><?php echo $_data['rented_statement']; ?></p>
        </div>
        <div class="icon"><img height="80" width="80" src="img/rented.png"></div>
        <a href="<?php echo WEB_URL; ?>t_dashboard/tenant_details.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>OPTIMUM</h3>
          <p><?php echo $_data['rented_report']; ?></p>
        </div>
        <div class="icon"><img height="80" width="80" src="img/report.png"></div>
        <a href="<?php echo WEB_URL; ?>t_dashboard/r_report.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>OPTIMUM</h3>
          <p><?php echo $_data['unit_details']; ?></p>
        </div>
        <div class="icon"><img height="80" width="80" src="img/room.png"></div>
        <a href="<?php echo WEB_URL; ?>t_dashboard/unit_details.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col end -->
  </div>
  <!-- /.row end -->
</section>
<!-- /.content -->
<?php include('footer.php'); ?>
