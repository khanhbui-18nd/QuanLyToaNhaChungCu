<?php
include('header_emp.php');
include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_admin_dashboard.php');

if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    exit(); // Use exit() instead of die()
}
include './config.php';
$mysalery = 0;
$total_amount = 0;

// Check if $conn is a valid MySQLi connection object
if ($conn) {
    // Prepare the query for unit count for employee
    $stmt = $conn->prepare("SELECT SUM(amount) AS total FROM tbl_add_employee_salary_setup WHERE emp_name = ?");
    $stmt->bind_param("i", $_SESSION['objLogin']['eid']); // Bind the employee ID parameter
    $stmt->execute();
    
    $result_amount = $stmt->get_result();
    if ($result_amount) {
        $row_amount_total = $result_amount->fetch_assoc();
        $total_amount = $row_amount_total['total'] ?? 0; // Default to 0 if no total found
    }

    $stmt->close(); // Close the statement
}

$total_unit = 0;
$total_rented = 0;
$total_employee = 0;
$total_fair = 0;
$total_mc = 0;
$total_fund = 0;
$total_owner_utility = 0;

// Database connection assumed to be in $conn variable

// Unit count for owner
$stmt = $conn->prepare("SELECT COUNT(owner_id) AS total_unit FROM tbl_add_owner_unit_relation WHERE owner_id = ?");
$stmt->bind_param("i", $_SESSION['objLogin']['ownid']);
$stmt->execute();
$stmt->bind_result($total_unit);
$stmt->fetch();
$stmt->close();

// My rented
$stmt = $conn->prepare("SELECT COUNT(r.rid) AS total_rent FROM tbl_add_owner_unit_relation ur INNER JOIN tbl_add_rent r ON r.r_unit_no = ur.unit_id WHERE ur.owner_id = ?");
$stmt->bind_param("i", $_SESSION['objLogin']['ownid']);
$stmt->execute();
$stmt->bind_result($total_rented);
$stmt->fetch();
$stmt->close();

// Employee count
$stmt = $conn->prepare("SELECT COUNT(eid) AS total_employee FROM tbl_add_employee");
$stmt->execute();
$stmt->bind_result($total_employee);
$stmt->fetch();
$stmt->close();

// Fair count
$stmt = $conn->prepare("SELECT SUM(f.rent) AS total FROM tbl_add_fair f INNER JOIN tbl_add_owner_unit_relation ur ON ur.unit_id = f.unit_no WHERE ur.owner_id = ?");
$stmt->bind_param("i", $_SESSION['objLogin']['ownid']);
$stmt->execute();
$stmt->bind_result($total_fair);
$stmt->fetch();
$stmt->close();

// Maintenance count
$stmt = $conn->prepare("SELECT SUM(m_amount) AS total FROM tbl_add_maintenance_cost");
$stmt->execute();
$stmt->bind_result($total_mc);
$stmt->fetch();
$stmt->close();

// Fund count
$stmt = $conn->prepare("SELECT SUM(total_amount) AS totals FROM tbl_add_fund");
$stmt->execute();
$stmt->bind_result($total_fund);
$stmt->fetch();
$stmt->close();

// Utility count
$stmt = $conn->prepare("SELECT SUM(water_bill) AS w_bil, SUM(electric_bill) AS e_bil, SUM(gas_bill) AS g_bil, SUM(security_bill) AS s_bil, SUM(utility_bill) AS u_bil, SUM(other_bill) AS o_bil FROM tbl_add_fair f INNER JOIN tbl_add_owner_unit_relation ur ON ur.unit_id = f.unit_no WHERE f.type = 'Owner' AND ur.owner_id = ?");
$stmt->bind_param("i", $_SESSION['objLogin']['ownid']);
$stmt->execute();
$stmt->bind_result($w_bil, $e_bil, $g_bil, $s_bil, $u_bil, $o_bil);
$stmt->fetch();
$stmt->close();
$total_owner_utility = (float)$w_bil + (float)$e_bil + (float)$g_bil + (float)$u_bil + (float)$s_bil + (float)$o_bil;
?>

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo $_data['dashboard_emp']; ?><small><?php echo $_data['control']; ?></small></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo WEB_URL; ?>e_dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home_breadcam']; ?></a></li>
        <li class="active"><?php echo $_data['dash']; ?></li>
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
                    <h3><?php echo $total_amount . CURRENCY; ?></h3>
                    <p><?php echo $_data['salary_statement']; ?></p>
                </div>
                <div class="icon"> <img height="80" width="80" src="img/fund.png"></a> </div>
                <a href="<?php echo WEB_URL; ?>e_dashboard/salary_details.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col end -->
                 <!-- col start -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?php echo $total_unit; ?></h3>
                    <p><?php echo $_data['text_1']; ?></p>
                </div>
                <div class="icon"> <img height="80" width="80" src="img/room.png"></div>
                <a href="<?php echo WEB_URL; ?>e_dashboard/e_report.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col end -->
        <!-- col start -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?php echo $total_rented; ?></h3>
                    <p><?php echo $_data['text_2']; ?></p>
                </div>
                <div class="icon"> <img height="80" width="80" src="img/owner.png"></div>
                <a href="<?php echo WEB_URL; ?>e_dashboard/member_details.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col end -->
        <!-- col start -->
        <div class="col-lg-3 col-xs-6">
            <div class="small-box bg-aqua">
                <div class="inner">
                    <h3><?php echo $total_employee; ?></h3>
                    <p><?php echo $_data['text_3']; ?></p>
                </div>
                <div class="icon"> <img height="80" width="80" src="img/employee.png"></div>
                <a href="<?php echo WEB_URL; ?>e_dashboard/rented_details.php" class="small-box-footer"><?php echo $_data['dashboard_more_info']; ?> <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        <!-- ./col end -->
    </div>
    <!-- /.row end -->
</section>
<!-- /.content -->
<?php include('footer.php'); ?>
