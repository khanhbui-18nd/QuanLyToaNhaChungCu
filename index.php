<?php
ob_start();
session_start();
define('DIR_APPLICATION', str_replace('\'', '/', realpath(dirname(__FILE__))) . '/');

if (!file_exists("config.php")) {
    header("Location: install/index.php");
    exit(); // Use exit instead of die
}

include(DIR_APPLICATION . "config.php");
$msg = 'none';
$sql = '';

if (isset($_POST['username']) && $_POST['username'] != '' && isset($_POST['password']) && $_POST['password'] != '') {
    $username = make_safe($_POST['username']);
    $password = make_safe($_POST['password']);
    
    // Prepare the SQL query based on the login type
    if ($_POST['ddlLoginType'] == '1') {
        $stmt = $conn->prepare("SELECT *, b.branch_name FROM tbl_add_admin aa LEFT JOIN tblbranch b ON b.branch_id = aa.branch_id WHERE aa.email = ? AND aa.password = ?");
    } elseif ($_POST['ddlLoginType'] == '2') {
        $stmt = $conn->prepare("SELECT *, b.branch_name FROM tbl_add_owner o LEFT JOIN tblbranch b ON b.branch_id = o.branch_id WHERE o.o_email = ? AND o.o_password = ?");
    } elseif ($_POST['ddlLoginType'] == '3') {
        $stmt = $conn->prepare("SELECT *, b.branch_name FROM tbl_add_employee e LEFT JOIN tblbranch b ON b.branch_id = e.branch_id WHERE e.e_email = ? AND e.e_password = ?");
    } elseif ($_POST['ddlLoginType'] == '4') {
        $stmt = $conn->prepare("SELECT *, b.branch_name FROM tbl_add_rent ad LEFT JOIN tblbranch b ON b.branch_id = ad.branch_id WHERE ad.r_email = ? AND ad.r_password = ?");
    } elseif ($_POST['ddlLoginType'] == '5') {
        $stmt = $conn->prepare("SELECT *, (SELECT branch_name FROM tblbranch WHERE branch_id = ?) AS branch_name FROM tblsuper_admin WHERE email = ? AND password = ?");
    }

    // Bind parameters
    if ($_POST['ddlLoginType'] == '5') {
        $branch_id = $_POST['ddlBranch'];
        $stmt->bind_param("iss", $branch_id, $username, $password);
    } else {
        $stmt->bind_param("ss", $username, $password);
    }

    // Execute the query
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            // Success
            $_SESSION['objLogin'] = $row;
            $_SESSION['login_type'] = $_POST['ddlLoginType'];

            // Redirect based on login type
            switch ($_POST['ddlLoginType']) {
                case '1':
                case '5':
                    header("Location: dashboard.php");
                    break;
                case '2':
                    header("Location: o_dashboard.php");
                    break;
                case '3':
                    header("Location: e_dashboard.php");
                    break;
                case '4':
                    header("Location: t_dashboard.php");
                    break;
            }
            exit();
        } else {
            // User not found
            $msg = 'block';
        }
    }
    $stmt->close();
}

// Function to sanitize user input
function make_safe($variable) {
    global $conn; // Ensure we use the MySQLi connection
    $variable = strip_tags(trim($variable));
    return $conn->real_escape_string($variable);
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Optimum Apartment Management System</title>
    <!-- BOOTSTRAP STYLES-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONTAWESOME STYLES-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLES-->
    <link href="assets/css/custom.css" rel="stylesheet" />
    <!-- GOOGLE FONTS-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
    <!-- SCRIPTS - AT THE BOTTOM TO REDUCE THE LOAD TIME -->
    <!-- JQUERY SCRIPTS -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>
<body>
<div class="container"> <br/>
    <br/>
    <br/>
    <div class="row text-center ">
        <div class="col-md-12"><br/>
            <span style="font-size:35px;font-weight:bold;color:red;">OPTIMUM</span> <span style="font-size:18px;">Apartment Management System</span>
        </div>
    </div>
    <br/>
    <div class="row ">
        <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
            <div style="margin-bottom:8px;padding-top:2px;width:100%;height:25px;background:#E52740;color:#fff; display:<?php echo $msg; ?>" align="center">Wrong login information</div>
            <div class="panel panel-default" id="loginBox">
                <div class="panel-heading"> <strong> Enter Login Details </strong> </div>
                <div class="panel-body">
                    <form onSubmit="return validationForm();" role="form" id="form" method="post">
                        <br />
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-tag"></i></span>
                            <input type="text" name="username" id="username" class="form-control" placeholder="Your Email" autocomplete="current-password" required />
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
                            <input type="password" name="password" id="password" class="form-control" placeholder="Your Password" autocomplete="current-password" required />
                        </div>
                        <div class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-user"></i></span>
                            <select name="ddlLoginType" id="ddlLoginType" class="form-control">
                                <option value="">--Select Type--</option>
                                <option value="1">Admin</option>
                                <option value="2">Owner</option>
                                <option value="3">Employee</option>
                                <option value="4">Renter</option>
                                <option value="5">Super Admin</option>
                            </select>
                        </div>
                        <div id="x_branch" style="" class="form-group input-group">
                            <span class="input-group-addon"><i class="fa fa-plus"></i></span>
                            <select class="form-control" name="ddlBranch" id="ddlBranch">
                                <option value="">--Select Branch--</option>
                                <?php 
                                    $result_branch = $conn->query("SELECT * FROM tblbranch ORDER BY branch_name ASC");
                                    while ($row_branch = $result_branch->fetch_assoc()) {
                                        echo '<option value="' . $row_branch['branch_id'] . '">' . $row_branch['branch_name'] . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="checkbox-inline"></label>
                            <span class="pull-right"> <a href="<?php echo WEB_URL; ?>forgetpassword.php">Forget password?</a> </span>
                        </div>
                        <hr />
                        <!-- Checkbox Check All -->
                        <div class="form-group">
                            <label><input type="checkbox" id="checkAll" /> Check All</label>
                        </div>
                        <input style="width:100%" type="submit" id="login" class="btn btn-primary" value="Login">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function validationForm() {
    if ($("#username").val() == '') {
        alert("Email Required !!!");
        $("#username").focus();
        return false;
    } else if (!validateEmail($("#username").val())) {
        alert("Valid Email Required !!!");
        $("#username").focus();
        return false;
    } else if ($("#password").val() == '') {
        alert("Password Required !!!");
        $("#password").focus();
        return false;
    } else if ($("#ddlLoginType").val() == '') {
        alert("Select Login Type!!!");
        $("#ddlLoginType").focus();
        return false;
    } else {
        return true;
    }
}

function validateEmail(email) {
    var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(String(email).toLowerCase());
}

$(document).ready(function() {
    $('#ddlLoginType').change(function() {
        mewhat($(this).val());
    });

    $('#checkAll').click(function() {
        $('input[type=checkbox]').not(this).prop('checked', this.checked);
    });
});
</script>
</body>
</html>
