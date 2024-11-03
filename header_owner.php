<?php 
ob_start();
session_start();
include(__DIR__ . "/config.php");

$page_name = '';
$lang_code_global = "English";
$page_name = pathinfo(curPageURL(), PATHINFO_FILENAME);

function curPageURL() {
    $pageURL = 'http';
    if (!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on") {
        $pageURL .= "s";
    }
    $pageURL .= "://";
    $pageURL .= $_SERVER["SERVER_NAME"];
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= ":" . $_SERVER["SERVER_PORT"];
    }
    $pageURL .= $_SERVER["REQUEST_URI"];
    return $pageURL;
}
?>

<?php
if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    exit(); // Use exit() instead of die() for better practice
}

$image = WEB_URL . 'img/no_image.jpg';
if (isset($_SESSION['objLogin']['image']) && 
    file_exists(ROOT_PATH . '/img/upload/' . $_SESSION['objLogin']['image']) && 
    $_SESSION['objLogin']['image'] != '') {
    $image = WEB_URL . 'img/upload/' . $_SESSION['objLogin']['image'];
}

// Fetch settings using MySQLi
$query_ams_settings = $conn->query("SELECT * FROM tbl_settings");
if ($row_query_ams_core = $query_ams_settings->fetch_assoc()) {
    $lang_code_global = $row_query_ams_core['lang_code'];
}

include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_left_menu.php');
include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_common.php');
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>SAKO AMS</title>
<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
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
<!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
<header class="main-header">
  <a href="#" class="logo">
  <a href="dashboard.php" class="logo"><span class="logo-mini">OPT</span>
  <span class="logo-lg"><b>OPT</b> Quản Lý Toàn Nhà</span> </a>
  <nav class="navbar navbar-static-top" role="navigation">
  <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button"> <span class="sr-only">Toggle navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span> </a>
  <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-user fa-lg"></i> <span class="hidden-xs">
          <?php echo $_SESSION['objLogin']['o_name']; ?>
          </span> </a>
          <ul class="dropdown-menu">
            <li class="user-header"> <img src="<?php echo $image; ?>" class="img-circle" alt="User Image" />
              <p>
                <?php echo $_SESSION['objLogin']['o_name']; ?>
                <small>
                <?php echo ($_SESSION['login_type'] == '1') ? 'Admin' : 'Owner'; ?>
                <br/><?php echo $_SESSION['objLogin']['branch_name']; ?></small> </p>
            </li>
            <li class="user-footer">
              <div class="pull-left"><a data-target="#user_profile" data-toggle="modal" class="btn btn-success btn-flat">Profile</a></div>
              <div class="pull-right"> <a href="<?php echo WEB_URL; ?>logout.php" class="btn btn-danger btn-flat">Sign out</a> </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
</header>

<!-- Sidebar and content sections remain unchanged -->

<!-- Update Profile Modal -->
<form id="updateprofile" action="<?php echo WEB_URL; ?>ajax/updateProfile.php" method="post">
  <div class="modal fade" role="dialog" id="user_profile" aria-labelledby="gridSystemModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="gridSystemModalLabel">Update Your Profile</h4>
        </div>
        <div class="modal-body">
          <?php
            if (isset($_SESSION['objLogin']['image']) && file_exists(ROOT_PATH . '/img/upload/' . $_SESSION['objLogin']['image']) && $_SESSION['objLogin']['image'] != '') {
                $image = WEB_URL . 'img/upload/' . $_SESSION['objLogin']['image'];
            }
          ?>
          <div align="center"><img class="photo_img_round" style="width:100px;height:100px;" src="<?php echo $image; ?>" /></div>
          <h4 align="center"><?php echo $_SESSION['objLogin']['o_name']; ?></h4>
          <h4 align="center"><?php echo ($_SESSION['login_type'] == '1') ? 'Admin' : 'Owner'; ?></h4>
          <div class="form-group">
            <label class="control-label">Name:&nbsp;&nbsp;</label>
            <input type="text" class="form-control" id="txtProfileName" name="txtProfileName" value="<?php echo $_SESSION['objLogin']['o_name']; ?>">
          </div>
          <div class="form-group">
            <label class="control-label">Email:&nbsp;&nbsp;</label>
            <input type="text" class="form-control" id="txtProfileEmail" name="txtProfileEmail" value="<?php echo $_SESSION['objLogin']['o_email']; ?>">
          </div>
          <div class="form-group">
            <label class="control-label">Password:&nbsp;&nbsp;</label>
            <input type="text" class="form-control" id="txtProfilePassword" name="txtProfilePassword" value="<?php echo $_SESSION['objLogin']['o_password']; ?>">
          </div>
          <div style="color:orange;font-weight:bold;text-align:left;font-size:15px;">After update application will be logout automatically.</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" onClick="javascript:$('#updateprofile').submit();">Update</button>
        </div>
      </div>
    </div>
  </div>
  <input type="hidden" name="user_id" value="<?php echo $_SESSION['objLogin']['ownid']; ?>" >
</form>
