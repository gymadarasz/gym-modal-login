<form id="regform" action="register" method="post">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&#215;</button>
    <h3><?php _e('User Registration', 'gym'); ?></h3>
  </div> 
  <div class="modal-body">
  	<div class="status"></div>
    <div class="row-fluid control-group">
		<label><?php __('Username', 'gym'); ?>
        <input type="text" name="user_login" id="user_login" class="span12" value="" <?php __('Username', 'gym'); ?>/></label>
		<label><?php __('E-mail', 'gym'); ?>
        <input type="text" name="user_email" id="user_email" class="span12" value="" <?php __('E-mail', 'gym'); ?>/></label>
		<label><?php  __('Password', 'gym'); ?>
        <input type="password" name="user_password" id="user_password" class="span12" value="" <?php __('Password', 'gym'); ?>/></label>
		<span class="label label-info"><a href="#login_tab" data-toggle="tab"><?php _e('Already registered? Login', 'gym'); ?></a></span>
		<span class="label"><?php echo _e('A password will be emailed to you.', 'gym') ?></span>
    </div>
  </div>
  <div class="modal-footer">
        <button type="submit" name="pass-sumbit" id="pass-submit" class="btn <?php echo default_buttons(); ?> <?php echo default_sizes(); ?> btn-block" data-loading-text="<?php _e('loading...', 'gym'); ?>"><?php _e('Submit', 'gym'); ?></button>
		<input type="hidden" name="register" value="true" />
  </div>
   <?php wp_nonce_field('ajax-form-nonce', 'security2'); ?>
</form>