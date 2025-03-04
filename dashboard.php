<?php
include('header.php');
include './config.php';
if ($_SESSION['login_type'] != '1' && $_SESSION['login_type'] != '5') {
	header("Location: " . WEB_URL . "logout.php");
	die();
}

include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_admin_dashboard.php');

$total_floor = $total_unit = $total_owner = $total_rented = $total_employee = $total_fair = 0;
$total_mc = $total_c = $total_owner_utility = $total_fund = 0;

// floor count
$result_floor = $conn->query("SELECT count(fid) as total_floor FROM tbl_add_floor WHERE branch_id = " . (int)$_SESSION['objLogin']['branch_id']);
if ($row_floor_total = $result_floor->fetch_assoc()) {
	$total_floor = $row_floor_total['total_floor'];
}

// unit count
$result_unit = $conn->query("SELECT count(uid) as total_unit FROM tbl_add_unit WHERE branch_id = " . (int)$_SESSION['objLogin']['branch_id']);
if ($row_unit_total = $result_unit->fetch_assoc()) {
	$total_unit = $row_unit_total['total_unit'];
}

// owner count
$result_owner = $conn->query("SELECT count(ownid) as total_owner FROM tbl_add_owner WHERE branch_id = " . (int)$_SESSION['objLogin']['branch_id']);
if ($row_owner_total = $result_owner->fetch_assoc()) {
	$total_owner = $row_owner_total['total_owner'];
}

// rented count
$result_rented = $conn->query("SELECT count(rid) as total_rent FROM tbl_add_rent WHERE branch_id = " . (int)$_SESSION['objLogin']['branch_id']);
if ($row_rented_total = $result_rented->fetch_assoc()) {
	$total_rented = $row_rented_total['total_rent'];
}

// employee count
$result_employee = $conn->query("SELECT count(eid) as total_employee FROM tbl_add_employee WHERE branch_id = " . (int)$_SESSION['objLogin']['branch_id']);
if ($row_employee_total = $result_employee->fetch_assoc()) {
	$total_employee = $row_employee_total['total_employee'];
}

// fair count
$result_fair = $conn->query("SELECT sum(rent) as total FROM tbl_add_fair WHERE branch_id = " . (int)$_SESSION['objLogin']['branch_id']);
if ($row_fair_total = $result_fair->fetch_assoc()) {
	if ($row_fair_total['total'] > 0) {
		$total_fair = number_format($row_fair_total['total'], 2, $currency_sep, '');
	}
}

// maintenance cost count
$result_mc = $conn->query("SELECT sum(m_amount) as total FROM tbl_add_maintenance_cost WHERE branch_id = " . (int)$_SESSION['objLogin']['branch_id']);
if ($row_mc_total = $result_mc->fetch_assoc()) {
	if ($row_mc_total['total'] > 0) {
		$total_mc = number_format($row_mc_total['total'], 2, $currency_sep, '');
	}
}

// fund count
$result_fund = $conn->query("SELECT sum(total_amount) as totals FROM tbl_add_fund WHERE branch_id = " . (int)$_SESSION['objLogin']['branch_id']);
if ($row_fund_total = $result_fund->fetch_assoc()) {
	if ($row_fund_total['totals'] > 0) {
		$total_fund = number_format($row_fund_total['totals'], 2, $currency_sep, '');
	}
}

// committee count
$result_c = $conn->query("SELECT count(mc_id) as total FROM tbl_add_management_committee WHERE branch_id = " . (int)$_SESSION['objLogin']['branch_id']);
if ($row_c_total = $result_c->fetch_assoc()) {
	$total_c = $row_c_total['total'];
}

// utility count
$result_ou = $conn->query("SELECT sum(water_bill) as w_bil, sum(electric_bill) as e_bil, sum(gas_bill) as g_bil, sum(security_bill) as s_bil, sum(utility_bill) as u_bil, sum(other_bill) as o_bil FROM tbl_add_fair WHERE type = 'Owner'");
if ($row_ou_total = $result_ou->fetch_assoc()) {
	$total_owner_utility = (float)$row_ou_total['w_bil'] + (float)$row_ou_total['e_bil'] + (float)$row_ou_total['g_bil'] + (float)$row_ou_total['u_bil'] + (float)$row_ou_total['s_bil'] + (float)$row_ou_total['o_bil'];
	$total_utility = number_format($total_owner_utility, 2, $currency_sep, '');
}
?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?php echo $_data['dashboard_title']; ?></h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL; ?>dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home_breadcam']; ?></a></li>
    <li class="active"><?php echo $_data['home_breadcam']; ?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
  <!-- /.row start -->
  <div class="row home_dash_box">
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3><?php echo $total_floor; ?></h3>
          <p><?php echo $_data['dashboard_total_floor']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/floor.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>floor/floorlist.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3><?php echo $total_unit; ?></h3>
          <p><?php echo $_data['dashboard_total_unit']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/room.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>unit/unitlist.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3><?php echo $total_owner; ?></h3>
          <p><?php echo $_data['dashboard_total_owner']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/owner.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>owner/ownerlist.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3><?php echo $total_rented; ?></h3>
          <p><?php echo $_data['dashboard_total_rented']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/rented.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>rent/rentlist.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3><?php echo $total_employee; ?></h3>
          <p><?php echo $_data['dashboard_total_employee']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/employee.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>employee/employeelist.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
	<!-- ./col end -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3><?php echo $total_c; ?></h3>
          <p><?php echo $_data['dashboard_total_committee']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/comittee.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>management/m_committee_list.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
          <?php if($currency_position == 'left') { ?>
          <h3><?php echo $global_currency.$total_fair; ?></h3>
          <?php } else { ?>
          <h3><?php echo $total_fair.$global_currency; ?></h3>
          <?php } ?>
          <p><?php echo $_data['dashboard_total_fare']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/fair.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>fair/fairlist.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
        <?php if($currency_position == 'left') { ?>
          <h3><?php echo $global_currency.$total_mc; ?></h3>
           <?php } else { ?>
           <h3><?php echo $total_mc.$global_currency; ?></h3>
           <?php } ?>
          <p><?php echo $_data['dashboard_total_maintenance']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/maintenance.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>maintenance/maintenance_cost_list.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
	<!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
         <?php if($currency_position == 'left') { ?>
          <h3><?php echo $global_currency.$total_fund; ?></h3>
           <?php } else { ?>
           <h3><?php echo $total_fund.$global_currency; ?></h3>
           <?php } ?>
          <p><?php echo $_data['dashboard_total_fund']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/fund.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>fund/fund_list.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
        <?php if($currency_position == 'left') { ?>
          <h3><?php echo $global_currency.$total_utility; ?></h3>
           <?php } else { ?>
           <h3><?php echo $total_utility.$global_currency; ?></h3>
           <?php } ?>
          <p><?php echo $_data['dashboard_total_owner_utility']; ?></p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/utility.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>owner_utility/owner_utility_list.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3>
            <?php echo $_data['dashboard_report']; ?></h3>
          <p>&nbsp;</p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/report.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>report/report.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
    <!-- col start -->
    <div class="col-lg-3 col-xs-6">
      <div class="small-box bg-aqua">
        <div class="inner">
          <h3><?php echo $_data['dashboard_settings']; ?></h3>
          <p>&nbsp;</p>
        </div>
        <div class="icon"> <img height="80" width="80" src="img/setting.png"></a> </div>
        <a href="<?php echo WEB_URL; ?>setting/setting.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a> </div>
    </div>
    <!-- ./col end -->
  </div>
  <!-- /.row end -->
</section>
<!-- /.content -->
<?php include('footer.php'); ?>
