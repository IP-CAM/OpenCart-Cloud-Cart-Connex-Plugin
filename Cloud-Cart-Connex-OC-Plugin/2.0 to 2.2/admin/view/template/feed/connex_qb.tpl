<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-connex-qb" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="fa fa-pencil"></i><?php echo $text_edit; ?></h3>
    </div>
    <div class="panel-body">
      <div class="container-fluid">
        <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-connex-qb" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_connex_qb_status; ?></label>
            <div class="col-sm-10">
              <select name="connex_qb_status" id="input-status" class="form-control">
                <?php if ($connex_qb_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-connex-qb-username"><span data-toggle="tooltip" title="<?php echo $help_connex_qb_username; ?>"><?php echo $entry_connex_qb_username; ?></span></label>
            <div class="col-sm-10">
              <input type="text" id="input-connex-qb-username" name="connex_qb_username" placeholder="<?php echo $entry_connex_qb_username; ?>" class="form-control" value="<?php echo $connex_qb_username; ?>">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-connex-qb-password"><span data-toggle="tooltip" title="<?php echo $help_connex_qb_password; ?>"><?php echo $entry_connex_qb_password; ?></span></label>
            <div class="col-sm-10">
              <input type="text" id="input-connex-qb-password" name="connex_qb_password" placeholder="<?php echo $entry_connex_qb_password; ?>" class="form-control" value="<?php echo $connex_qb_password; ?>">
              <br><input type="button" class="btn btn-info btn-sm" onclick="document.getElementById('input-connex-qb-password').value = generateCode()" value="Generate New">
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-connex-qb-url"><span data-toggle="tooltip" title="<?php echo $help_connex_qb_url; ?>"><?php echo $entry_connex_qb_url; ?></span></label>
            <div class="col-sm-10">
              <textarea rows="5" readonly="readonly" id="input-connex-qb-url" class="form-control"><?php echo $connex_qb_url; ?></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
    function generateCode()
    {
        var text = "";
        var possible = "0123456789abcdefghijklmnopqrstuvwxyz";

        for( var i=0; i < 25; i++ )
            text += possible.charAt(Math.floor(Math.random() * possible.length));

        return text;
    }
</script>
<?php echo $footer; ?>
