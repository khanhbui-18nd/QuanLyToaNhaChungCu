<?php 
ob_start();
session_start();
include(__DIR__ . "/config.php");

$page_name = '';
$page_name = pathinfo(curPageURL(), PATHINFO_FILENAME);

function curPageURL() {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}

if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    exit();
}

$image = WEB_URL . 'img/no_image.jpg';    
if (isset($_SESSION['objLogin']['image'])) {
    $imagePath = ROOT_PATH . '/img/upload/' . $_SESSION['objLogin']['image'];
    if (file_exists($imagePath) && $_SESSION['objLogin']['image'] != '') {
        $image = WEB_URL . 'img/upload/' . $_SESSION['objLogin']['image'];
    }
}

// Use MySQLi to fetch settings
$query_ams_settings = $conn->query("SELECT * FROM tbl_settings");
if ($query_ams_settings && $row_query_ams_core = $query_ams_settings->fetch_assoc()) {
    $lang_code_global = $row_query_ams_core['lang_code'];
}

include(ROOT_PATH . 'language/' . 'English' . '/lang_left_menu.php');
include(ROOT_PATH . 'language/' . 'English' . '/lang_common.php');
?>


<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SAKO AMS</title>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
<!-- Stylesheets and JS libraries -->
<link href="<?php echo WEB_URL; ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>dist/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>dist/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>dist/css/AdminLTE.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>dist/css/skins/_all-skins.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>plugins/iCheck/all.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>plugins/datatables/dataTables.bootstrap.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>dist/css/dataTables.responsive.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>dist/css/dataTables.tableTools.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo WEB_URL; ?>plugins/datepicker/datepicker3.css" rel="stylesheet" type="text/css" />
<script src="<?php echo WEB_URL; ?>plugins/jQuery/jQuery-2.1.4.min.js"></script>
<script src="<?php echo WEB_URL; ?>dist/js/printThis.js"></script>
</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
<span class="main-header">
    <a href="dashboard.php" class="logo">
        <span class="logo-mini">OPT</span>
        <span class="logo-lg"><b>Optimum</b> Apartment Management System</span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
    </nav>
</span>

<header class="main-header">
    <nav class="navbar navbar-static-top" role="navigation">
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user fa-lg"></i> 
                        <span class="hidden-xs"><?php echo $_SESSION['objLogin']['r_name']; ?></span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="<?php echo $image; ?>" class="img-circle" alt="User Image" />
                            <p>
                                <?php echo $_SESSION['objLogin']['r_name']; ?>
                                <small>Renter<br/><?php echo $_SESSION['objLogin']['branch_name']; ?></small>
                            </p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a data-target="#user_profile" data-toggle="modal" class="btn btn-success btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="<?php echo WEB_URL; ?>logout.php" class="btn btn-danger btn-flat">Sign out</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>

