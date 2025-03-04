<?php 
include('../header.php');
include(ROOT_PATH . 'language/' . $lang_code_global . '/lang_language.php');
if (!isset($_SESSION['objLogin'])) {
    header("Location: " . WEB_URL . "logout.php");
    die();
}
include '../config.php';
$lang_code = "English";
$currency = '';
$currency_separator = '';
$currency_position = '';
$button_text = $_data['save_button_text'];
$success = 'none';
$msg = '';



if (isset($_POST['ddlLanguage'])) {
    $sqlx = "DELETE FROM `tbl_settings`";
    $conn->query($sqlx);
    
    $sql = "INSERT INTO tbl_settings(`lang_code`,`currency`,`currency_seperator`,`currency_position`) 
            VALUES ('" . $conn->real_escape_string($_POST['ddlLanguage']) . "', 
                    '" . $conn->real_escape_string($_POST['ddlCurrency']) . "', 
                    '" . $conn->real_escape_string($_POST['ddlCurrencySeparator']) . "', 
                    '" . $conn->real_escape_string($_POST['ddlCurrencyPosition']) . "')";
    
    if ($conn->query($sql) === TRUE) {
        $success = 'block';
        $msg = "Settings saved successfully.";
    } else {
        $msg = "Error: " . $conn->error;
    }
}

$query = $conn->query("SELECT * FROM tbl_settings");
if ($row = $query->fetch_assoc()) {
    $lang_code = $row['lang_code'];
    $currency = $row['currency'];
    $currency_separator = $row['currency_seperator'];
    $currency_position = $row['currency_position'];
}

$conn->close();
?>
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1><?php echo $_data['text_1'];?></h1>
    <ol class="breadcrumb">
        <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home_breadcam'];?></a></li>
        <li class="active"><a href="<?php echo WEB_URL?>setting/setting.php"><?php echo $_data['setting'];?></a></li>
        <li class="active"><?php echo $_data['text_1'];?></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
    <div class="col-md-12">
        <div id="you" class="alert alert-success alert-dismissable" style="display:<?php echo $success; ?>">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
            <h4><i class="icon fa fa-check"></i> <?php echo $_data['success'];?> !</h4>
            <?php echo $msg; ?>
        </div>
        <div class="box box-info">
            <div class="box-header">
                <h3 class="box-title"><?php echo $_data['text_2'];?></h3>
            </div>
            <form method="post" enctype="multipart/form-data">
                <div class="box-body">
                    <div class="form-group">
                        <label for="ddlLanguage"><?php echo $_data['text_3'];?> :</label>
                        <select name="ddlLanguage" id="ddlLanguage" class="form-control">
                            <option value="-1">---<?php echo $_data['text_3'];?>---</option>
                            <?php
                            $dir = ROOT_PATH . 'language/';
                            $files1 = scandir($dir);
                            foreach ($files1 as $folder) {
                                if ($folder != '' && $folder != '.' && $folder != '..') {
                                    if (trim($folder) == $lang_code) {
                                        echo '<option selected value="' . $folder . '">' . $folder . '</option>';
                                    } else {
                                        echo '<option value="' . $folder . '">' . $folder . '</option>';
                                    }
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ddlCurrency">Select Currency :</label>
                        <select name="ddlCurrency" id="ddlCurrency" class="form-control">
                            <option value="">--Select--</option>
                            <option <?php if ($currency == "$") { echo 'selected'; } ?> value="$">Dollar</option>
                            <option <?php if ($currency == "Tk") { echo 'selected'; } ?> value="Tk">Taka</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ddlCurrencySeparator">Currency Separator :</label>
                        <select name="ddlCurrencySeparator" id="ddlCurrencySeparator" class="form-control">
                            <option value="">--Select--</option>
                            <option <?php if ($currency_separator == ".") { echo 'selected'; } ?> value=".">Dot</option>
                            <option <?php if ($currency_separator == ",") { echo 'selected'; } ?> value=",">Comma</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="ddlCurrencyPosition">Currency Position :</label>
                        <select name="ddlCurrencyPosition" id="ddlCurrencyPosition" class="form-control">
                            <option value="">--Select--</option>
                            <option <?php if ($currency_position == "left") { echo 'selected'; } ?> value="left">Left</option>
                            <option <?php if ($currency_position == "right") { echo 'selected'; } ?> value="right">Right</option>
                        </select>
                    </div>
                    <div class="form-group pull-right">
                        <input type="submit" name="submit" class="btn btn-primary" value="<?php echo $button_text; ?>"/>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.box -->
    </div>
</div>
<!-- /.row -->
<script type="text/javascript">
    function deleteYear(Id) {
        var iAnswer = confirm("Are you sure you want to delete this Year ?");
        if (iAnswer) {
            window.location = '<?php echo WEB_URL; ?>setting/year_setup.php?delid=' + Id;
        }
    }
</script>

<?php include('../footer.php'); ?>
