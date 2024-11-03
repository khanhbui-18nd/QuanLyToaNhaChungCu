<?php
include("./config.php");

if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    exit(); // Use exit() instead of die()
}

$msg = 'none';
$xMsg = "No information Found";

if (isset($_POST['username']) && $_POST['username'] != '') {
    $password = '';

    // Check login type and prepare SQL query accordingly
    switch ($_POST['ddlLoginType']) {
        case '1': // Admin
            $stmt = $conn->prepare("SELECT * FROM tbl_add_admin WHERE email = ?");
            $password = 'password';
            break;
        case '2': // Owner
            $stmt = $conn->prepare("SELECT * FROM tbl_add_owner WHERE o_email = ?");
            $password = 'o_password';
            break;
        case '3': // Employee
            $stmt = $conn->prepare("SELECT * FROM tbl_add_employee WHERE e_email = ?");
            $password = 'e_password';
            break;
        case '4': // Renter
            $stmt = $conn->prepare("SELECT * FROM tbl_add_rent WHERE r_email = ?");
            $password = 'r_password';
            break;
        default:
            // Invalid login type
            $msg = 'block';
            break;
    }

    // Execute the query if statement was prepared
    if (isset($stmt)) {
        $stmt->bind_param("s", make_safe($_POST['username']));

        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                // Success: user found
                $xMsg = 'Check your Email Address for login details';
                $msg = 'block';

                // Send email to user
                $adminResult = $conn->query("SELECT * FROM tbl_add_admin");
                if ($adminRow = $adminResult->fetch_assoc()) {
                    $to = trim($_POST['username']);
                    $subject = $adminRow['email'] . ' User Login Details';
                    $headers = "From: " . strip_tags($adminRow['email']) . "\r\n";
                    $headers .= "Reply-To: " . strip_tags($adminRow['email']) . "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    $message = '<html><body>';
                    $message .= '<h1>User Login Information</h1>';
                    $message .= '<div>Username: ' . $_POST['username'] . '</div>';
                    $message .= '<div>Password: ' . $row[$password] . '</div>';
                    $message .= '</body></html>';
                    
                    echo $message; // For testing purposes
                    // mail($to, $subject, $message, $headers); // Uncomment to enable email sending
                    exit(); // Stop execution after testing
                }
            } else {
                // User not found
                $msg = 'block';
            }
        }
        $stmt->close();
    }
}

// Function to sanitize user input
function make_safe($variable) {
    global $conn; // Ensure we use the MySQLi connection
    $variable = strip_tags($variable);
    return $conn->real_escape_string(trim($variable));
}
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SAKO School Management System</title>
<!-- BOOTSTRAP STYLES-->
<link href="assets/css/bootstrap.css" rel="stylesheet" />
<!-- FONTAWESOME STYLES-->
<link href="assets/css/font-awesome.css" rel="stylesheet" />
<!-- CUSTOM STYLES-->
<link href="assets/css/custom.css" rel="stylesheet" />
<!-- GOOGLE FONTS-->
<link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>
<body>
<div class="container">
  <br/><br/><br/><br/>
  <div class="row text-center ">
    <div class="col-md-12"><br/>
      <span style="font-size:35px;font-weight:bold;color:red;">SAKO</span> <span style="font-size:18px;">Apartment Management System</span></div>
  </div>
  <br/>
  <div class="row ">
    <div class="col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1">
      <div style="margin-bottom:8px;padding-top:2px;width:100%;height:25px;background:#E52740;color:#fff; display:<?php echo $msg; ?>" align="center"><?php echo $xMsg; ?></div>
      <div class="panel panel-default" id="loginBox">
        <div class="panel-heading"> <strong> Forget Your Password </strong> </div>
        <div class="panel-body">
          <form onSubmit="return validationForm();" role="form" id="form" method="post">
            <br />
            <div class="form-group input-group"> <span class="input-group-addon"><i class="fa fa-tag"  ></i></span>
              <input type="text" name="username" id="username" class="form-control" placeholder="Your Email Address " />
            </div>
            <div class="form-group input-group"> <span class="input-group-addon"><i class="fa fa-user"  ></i></span>
              <select name="ddlLoginType" id="ddlLoginType" class="form-control">
                <option value="-1">-- Select --</option>
                <option value="1">Admin</option>
                <option value="2">Owner</option>
                <option value="3">Employee</option>
                <option value="4">Renter</option>
              </select>
            </div>
            <div class="form-group">
              <button style="width:100%;" type="submit" id="login" class="btn btn-primary">Submit</button>
            </div>
            <div class="form-group"> <a style="width:100%;" type="submit" id="login" class="btn btn-success" href="<?php echo WEB_URL;?>index.php">Back To Login</a> </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
function validationForm(){
    if($("#username").val() == ''){
        alert("Valid Email Required !!!");
        $("#username").focus();
        return false;
    }
    else if($("#ddlLoginType").val() == '-1'){
        alert("Select Login Type !!!");
        return false;
    }
    else{
        return true;
    }
}
</script>
<!-- SCRIPTS -AT THE BOTOM TO REDUCE THE LOAD TIME-->
<!-- JQUERY SCRIPTS -->
<script src="assets/js/jquery-1.10.2.js"></script>
<!-- BOOTSTRAP SCRIPTS -->
<script src="assets/js/bootstrap.min.js"></script>
</body>
</html>
