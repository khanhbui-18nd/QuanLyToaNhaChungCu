<?php 
include('../header_owner.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_owner_unit_details.php');
include(ROOT_PATH.'language/'.$lang_code_global.'/lang_common.php');
include('../config.php'); // Thêm bao gồm config.php

// Content Header (Page header)
?>
<section class="content-header">
  <h1> <?php echo $_data['text_1']; ?> </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo WEB_URL ?>o_dashboard.php"><i class="fa fa-dashboard"></i> <?php echo $_data['home_breadcam']; ?></a></li>
    <li class="active"><?php echo $_data['text_1']; ?></li>
  </ol>
</section>
<!-- Main content -->
<section class="content">
<!-- Full Width boxes (Stat box) -->
<div class="row">
  <div class="col-xs-12">
    <div align="right" style="margin-bottom:1%;">
        <a class="btn btn-primary" data-toggle="tooltip" href="<?php echo WEB_URL; ?>o_dashboard.php" data-original-title="<?php echo $_data['text_9']; ?>"><i class="fa fa-dashboard"></i></a> 
    </div>
    <div class="box box-info">
      <div class="box-header">
        <h3 class="box-title"><?php echo $_data['text_1']; ?></h3>
      </div>
      <!-- /.box-header -->
      <div class="box-body">
        <table class="table sakotable table-bordered table-striped dt-responsive">
          <thead>
            <tr>
              <th><?php echo $_data['text_2']; ?></th>
              <th><?php echo $_data['text_2']; ?></th>
              <th><?php echo $_data['action_text']; ?></th>
            </tr>
          </thead>
          <tbody>
          <?php
          // Câu truy vấn
          $query = "SELECT u.unit_no, fl.floor_no AS fl_floor_no 
                    FROM tbl_add_owner_unit_relation owr 
                    INNER JOIN tbl_add_unit u ON owr.unit_id = u.uid 
                    INNER JOIN tbl_add_floor fl ON fl.fid = u.floor_no 
                    WHERE owr.owner_id = '" . (int)$_SESSION['objLogin']['ownid'] . "' 
                    ORDER BY u.unit_no ASC";
          
          $result = mysqli_query($conn, $query); // Sử dụng mysqli_query

          while ($row = mysqli_fetch_array($result)) { ?>
            <tr>
              <td><?php echo $row['fl_floor_no']; ?></td>
              <td><?php echo $row['unit_no']; ?></td>
              <td>
                <a class="btn btn-success" data-toggle="tooltip" href="javascript:;" 
                   onclick="$('#nurse_view_<?php echo (int)$_SESSION['objLogin']['ownid']; ?>').modal('show');" 
                   data-original-title="<?php echo $_data['view_text']; ?>">
                   <i class="fa fa-eye"></i>
                </a>
                <div id="nurse_view_<?php echo (int)$_SESSION['objLogin']['ownid']; ?>" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header orange_header">
                        <button aria-label="Close" data-dismiss="modal" class="close" type="button">
                          <span aria-hidden="true"><i class="fa fa-close"></i></span>
                        </button>
                        <h3 class="modal-title"><?php echo $_data['text_4']; ?></h3>
                      </div>
                      <div class="modal-body model_view" align="center">&nbsp;
                        <div>&nbsp;</div>
                        <div class="model_title"><?php echo $_SESSION['objLogin']['o_name']; ?></div>
                      </div>
                      <div class="modal-body">
                        <h3 style="text-decoration:underline;"><?php echo $_data['details_information']; ?></h3>
                        <div class="row">
                          <div class="col-xs-12"> 
                            <b><?php echo $_data['text_5']; ?> :</b> <?php echo $_SESSION['objLogin']['o_name']; ?><br/>
                            <b><?php echo $_data['text_6']; ?> :</b> <?php echo $_SESSION['objLogin']['o_email']; ?><br/>
                            <b><?php echo $_data['text_7']; ?> :</b> <?php echo $_SESSION['objLogin']['o_contact']; ?><br/>
                            <b><?php echo $_data['text_8']; ?> :</b> <?php echo $_SESSION['objLogin']['o_pre_address']; ?><br/>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                </div>
              </td>
            </tr>
          <?php } mysqli_close($conn); // Đóng kết nối ?>
          </tbody>
        </table>
      </div>
      <!-- /.box-body -->
    </div>
    <!-- /.box -->
  </div>
  <!-- /.col -->
</div>
<!-- /.row -->
<?php include('../footer.php'); ?>
