<?php 
include('../header.php');
include('../utility/common.php');
include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_add_complain.php');
if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    die();
}
include '../config.php';
$success = "none";
$c_title = '';
$c_description = '';
$c_date = '';
$c_month = '';
$c_year = '';
$c_userid = '';
$branch_id = '';
$title = $_data['text_1'];
$button_text = $_data['save_button_text'];
$successful_msg = $_data['text_8'];
$form_url = WEB_URL . "complain/addcomplain.php";
$id = "";
$hdnid = "0";

if (isset($_POST['txtCTitle'])) {
    $xmonth = date('m');
    $xyear = date('Y');
    $c_title = $_POST['txtCTitle'];
    $c_description = $_POST['txtCDescription'];
    $c_date = $_POST['txtCDate'];
    
    if (isset($_POST['hdn']) && $_POST['hdn'] == '0') {
        $stmt = $conn->prepare("INSERT INTO tbl_add_complain(c_title, c_description, c_date, c_month, c_year, c_userid, branch_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiiss", $c_title, $c_description, $c_date, $xmonth, $xyear, $_SESSION['objLogin']['aid'], $_SESSION['objLogin']['branch_id']);
        $stmt->execute();
        $stmt->close();

        $url = WEB_URL . 'complain/complainlist.php?m=add';
        header("Location: $url");
    } else {
        $stmt = $conn->prepare("UPDATE tbl_add_complain SET c_title=?, c_description=?, c_date=? WHERE complain_id=?");
        $stmt->bind_param("sssi", $c_title, $c_description, $c_date, $_GET['id']);
        $stmt->execute();
        $stmt->close();

        $url = WEB_URL . 'complain/complainlist.php?m=up';
        header("Location: $url");
    }

    $success = "block";
}

if (isset($_GET['id']) && $_GET['id'] != '') {
    $stmt = $conn->prepare("SELECT * FROM tbl_add_complain WHERE complain_id = ?");
    $stmt->bind_param("i", $_GET['id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $c_title = $row['c_title'];
        $c_description = $row['c_description'];
        $c_date = $row['c_date'];
        $hdnid = $_GET['id'];
        $title = $_data['text_1_1'];
        $button_text = $_data['update_button_text'];
        $successful_msg = $_data['text_9'];
        $form_url = WEB_URL . "complain/addcomplain.php?id=" . $_GET['id'];
    }
    $stmt->close();
}
?>
<!-- Content Header (Page header) -->

<section class="content-header">
    <h1><?php echo $title; ?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo WEB_URL ?>dashboard.php"><i class="fa fa-dashboard"></i><?php echo $_data['home_breadcam']; ?></a></li>
        <li class="active"><?php echo $_data['text_2']; ?></li>
        <li class="active"><?php echo $_data['text_3']; ?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div align="right" style="margin-bottom:1%;">
                <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>complain/complainlist.php" data-original-title="<?php echo $_data['back_text']; ?>"><i class="fa fa-reply"></i></a>
            </div>
            <div class="box box-info">
                <div class="box-header">
                    <h3 class="box-title"><?php echo $_data['text_4']; ?></h3>
                </div>
                <div class="box-body">
                    <form method="post" action="<?php echo $form_url; ?>" onsubmit="return validateMe();">
                        <input type="hidden" name="hdn" value="<?php echo $hdnid; ?>">
                        <div class="form-group">
                            <label for="txtCTitle"><?php echo $_data['text_5']; ?></label>
                            <input type="text" class="form-control" id="txtCTitle" name="txtCTitle" value="<?php echo $c_title; ?>" />
                        </div>
                        <div class="form-group">
                            <label for="txtCDescription"><?php echo $_data['text_6']; ?></label>
                            <textarea class="form-control" id="txtCDescription" name="txtCDescription"><?php echo $c_description; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="txtCDate"><?php echo $_data['text_7']; ?></label>
                            <input type="date" class="form-control" id="txtCDate" name="txtCDate" value="<?php echo $c_date; ?>" />
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary"><?php echo $button_text; ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script type="text/javascript">
function validateMe() {
    if ($("#txtCTitle").val() == '') {
        alert("Title is Required !!!");
        $("#txtCTitle").focus();
        return false;
    } else if ($("#txtCDescription").val() == '') {
        alert("Description is Required !!!");
        $("#txtCDescription").focus();
        return false;
    } else if ($("#txtCDate").val() == '') {
        alert("Date is Required !!!");
        $("#txtCDate").focus();
        return false;
    } else {
        return true;
    }
}
</script>
<?php include('../footer.php'); ?>
