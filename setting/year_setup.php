<?php 
include('../header.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_year_setup.php');
if(!isset($_SESSION['objLogin'])){
    header("Location: " . WEB_URL . "logout.php");
    die();
}
include '../config.php';
$xyear = '';
$button_text = $_data['save_button_text'];
$form_url = WEB_URL . "setting/year_setup.php";

$hval = 0;

if(isset($_POST['txtXYear'])){
    $xyear = $conn->real_escape_string($_POST['txtXYear']); // Bảo vệ SQL Injection
    if($_POST['hdnSpid'] == '0'){
        $sql = "INSERT INTO `tbl_add_year_setup`(`xyear`) VALUES ('$xyear')";
        $conn->query($sql);
        $url = WEB_URL . 'setting/year_setup.php?m=add';
        header("Location: $url");
    } else {
        $sql_update = "UPDATE `tbl_add_year_setup` SET xyear = '$xyear' WHERE y_id= '" . (int)$_POST['hdnSpid'] . "'";
        $conn->query($sql_update);
        $url = WEB_URL . 'setting/year_setup.php?m=up';
        header("Location: $url");
    }
    $success = "block";
}

if(isset($_GET['spid']) && $_GET['spid'] != ''){
    $result_location = $conn->query("SELECT * FROM tbl_add_year_setup WHERE y_id= '" . (int)$_GET['spid'] . "'");
    if($row = $result_location->fetch_assoc()){
        $xyear = $row['xyear'];
        $button_text = $_data['update_button_text'];
        $form_url = WEB_URL . "setting/year_setup.php?id=".$_GET['spid'];
        $hval = $row['y_id'];
    }
}
?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1><?php echo $_data['text_1'];?> </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL?>dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home_breadcam'];?></a></li>
    <li class="active"><a href="<?php echo WEB_URL?>setting/setting.php"><?php echo $_data['setting'];?></a></li>
    <li class="active"><?php echo $_data['text_1'];?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<div class="row">
  <div class="col-md-12">
    <div align="right" style="margin-bottom:1%;"> <a class="btn btn-primary" title="" data-toggle="tooltip" href="<?php echo WEB_URL; ?>setting/setting.php" data-original-title="<?php echo $_data['back_text'];?>"><i class="fa fa-reply"></i></a> </div>
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['text_2'];?></h3>
      </div>
      <form onSubmit="return validateMe();" action="<?php echo $form_url; ?>" method="post" enctype="multipart/form-data">
        <div class="box-body">
          <div class="form-group">
            <label for="txtXYear"><?php echo $_data['text_3'];?> :</label>
            <input type="text" name="txtXYear" value="<?php echo $xyear;?>" id="txtXYear" class="form-control" />
          </div>
          <div class="form-group pull-right">
            <input type="submit" name="submit" class="btn btn-primary" value="<?php echo $button_text; ?>"/>
            &nbsp;
            <input type="reset" onClick="javascript:window.location.href='<?php echo WEB_URL; ?>setting/year_setup.php';" name="btnReset" id="btnReset" value="<?php echo $_data['reset'];?>" class="btn btn-primary"/>
          </div>
        </div>
        <input type="hidden" name="hdnSpid" value="<?php echo $hval; ?>"/>
      </form>
      <h4 style="text-align:center; color:red;"><?php echo $_data['reset_text'];?></h4>
      
      <?php
      $delinfo = 'none';
      $addinfo = 'none';
      $msg = "";
      if(isset($_GET['delid']) && $_GET['delid'] != '' && $_GET['delid'] > 0){
          $sqlx = "DELETE FROM `tbl_add_year_setup` WHERE y_id = " . (int)$_GET['delid'];
          $conn->query($sqlx); 
          $delinfo = 'block';
      }
      if(isset($_GET['m']) && $_GET['m'] == 'add'){
          $addinfo = 'block';
          $msg = "Added Year Information Successfully";
      }
      if(isset($_GET['m']) && $_GET['m'] == 'up'){
          $addinfo = 'block';
          $msg = "Updated Year Information Successfully";
      }
      ?>      
      <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <div class="alert alert-danger alert-dismissable" style="display:<?php echo $delinfo; ?>">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
            <h4><i class="icon fa fa-ban"></i> <?php echo $_data['delete_text'];?> !</h4>
            <?php echo $_data['text_7'];?> </div>
          <div class="alert alert-success alert-dismissable" style="display:<?php echo $addinfo; ?>">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button"><i class="fa fa-close"></i></button>
            <h4><i class="icon fa fa-check"></i> <?php echo $_data['success'];?> !</h4>
            <?php echo $msg; ?> </div>
          <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title"><?php echo $_data['text_4'];?></h3>
            </div>
            <div class="box-body">
              <table class="table sakotable table-bordered table-striped dt-responsive">
                <thead>
                  <tr>
                    <th><?php echo $_data['text_3'];?></th>
                    <th><?php echo $_data['action_text'];?></th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $result = $conn->query("SELECT * FROM tbl_add_year_setup ORDER BY y_id ASC");
                  while($row = $result->fetch_assoc()){?>
                  <tr>
                    <td><?php echo $row['xyear']; ?></td>
                    <td>
                      <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" onclick="$('#employee_view_<?php echo $row['y_id']; ?>').modal('show');" data-original-title="<?php echo $_data['view_text'];?>"><i class="fa fa-eye"></i></a> 
                      <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL;?>setting/year_setup.php?spid=<?php echo $row['y_id']; ?>" data-original-title="<?php echo $_data['edit_text'];?>"><i class="fa fa-pencil"></i></a> 
                      <a class="btn btn-danger" data-toggle="tooltip" onclick="deleteYear(<?php echo $row['y_id']; ?>);" href="javascript:;" data-original-title="<?php echo $_data['delete_text'];?>"><i class="fa fa-trash-o"></i></a>
                      <div id="employee_view_<?php echo $row['y_id']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                          <div class="modal-content">
                            <div class="modal-header orange_header">
                              <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true"><i class="fa fa-close"></i></span></button>
                              <h3 class="modal-title"><?php echo $_data['text_8'];?></h3>
                            </div>
                            <div class="modal-body model_view" align="center">&nbsp;
                              <div><!--<img class="photo_img_round" style="width:100px;height:100px;" src="<?php //echo $station_logo;  ?>" />--></div>
                              <div class="model_title"><?php //echo $row['station_name']; ?></div>
                            </div>
                            <div class="modal-body">
                              <h3 style="text-decoration:underline;"><?php echo $_data['details_information'];?></h3>
                              <div class="row">
                                <div class="col-xs-12"> <b><?php echo $_data['text_3'];?> :</b> <?php echo $row['xyear']; ?><br/>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <?php } $conn->close(); ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      function deleteYear(Id){
        var iAnswer = confirm("Are you sure you want to delete this Year ?");
        if(iAnswer){
            window.location = '<?php echo WEB_URL;?>setting/year_setup.php?delid=' + Id;
        }
      }
    </script>
<?php include('../footer.php'); ?>
