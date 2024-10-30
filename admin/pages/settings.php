<?php
  if (!current_user_can('manage_options')) {
    wp_die('You do not have permissions to access this page.');
  }
?>
<?php $clpl_settings = get_option( 'clpl_settings' ); ?>
<div class="wrap about-wrap">
	<h1><?php echo __( CLPL_PLUGIN_NAME, CLPL_TEXTDOMAIN ); ?></h1>
	<div class="about-text"><?php echo __( 'Manage login logo settings here.', CLPL_TEXTDOMAIN ); ?></div>

	<h2 class="nav-tab-wrapper">
		<a class="nav-tab" data-tab="clpl-settings" href="#clpl-settings" id="clpl-settings-tab"><?php echo __( 'Settings', CLPL_TEXTDOMAIN ); ?></a>
		<a class="nav-tab" data-tab="clpl-help" href="#clpl-help" id="clpl-help-tab"><?php echo __( 'Help', CLPL_TEXTDOMAIN ); ?></a>
	</h2>
	<div id="clpl-settings" class="clpl-tabs">
		<form id="clpl-settings-form" method="post">
			<input type="hidden" name="action" value="clpl_save_settings">
			<input type="hidden" name="security" value="<?php echo wp_create_nonce( "clpl-save-settings" ); ?>">
			<div>
				<!-- BEGIN ADDONS LISTING -->
				<div class="clpl-col-7">
          <div class="clpl-row">

            <div class="clpl-col-12">
              <span class="clpl-setting-label"><?php echo __( 'Logo Image', CLPL_TEXTDOMAIN ); ?></span>
              <span class="clpl-setting-item">
                <input type="text" name="clpl_settings[logo_image]" class="clpl-media-field-url" value="<?php if (isset($clpl_settings['logo_image'])) echo $clpl_settings['logo_image']; ?>">
                <button type="button" class="button hide-if-no-js clpl-media-field" aria-expanded="true">Upload Logo</button>
                <small>
                  <?php echo __( 'You can select the logo from media library or you can paste the external image link.', CLPL_TEXTDOMAIN ); ?>
                </small>
              </span>
            </div>

            <div class="clpl-col-12">
              <span class="clpl-setting-label"><?php echo __( 'Logo Image Width', CLPL_TEXTDOMAIN ); ?></span>
              <span class="clpl-setting-item">
                <input type="number" name="clpl_settings[logo_width]" id="logo-width" class="small-text" value="<?php if (isset($clpl_settings['logo_width'])) echo $clpl_settings['logo_width']; ?>">
                <span>px</span>
              </span>
            </div>

            <div class="clpl-col-12">
              <span class="clpl-setting-label"><?php echo __( 'Logo Image Height', CLPL_TEXTDOMAIN ); ?></span>
              <span class="clpl-setting-item">
                <input type="number" name="clpl_settings[logo_height]" id="logo-height" class="small-text" value="<?php if (isset($clpl_settings['logo_height'])) echo $clpl_settings['logo_height']; ?>">
                <span>px</span>
              </span>
            </div>

            <div class="clpl-col-12">
              <span class="clpl-setting-label"><?php echo __( 'Logo Bottom Margin', CLPL_TEXTDOMAIN ); ?></span>
              <span class="clpl-setting-item">
                <input type="number" name="clpl_settings[logo_bottom_margin]" id="logo-bottom-margin" class="small-text" value="<?php if (isset($clpl_settings['logo_bottom_margin'])) echo $clpl_settings['logo_bottom_margin']; ?>">
                <span>px</span>
              </span>
            </div>

            <div class="clpl-col-12">
              <span class="clpl-setting-label"><?php echo __( 'Logo Link', CLPL_TEXTDOMAIN ); ?></span>
              <span class="clpl-setting-item">
                <input type="text" name="clpl_settings[logo_link]" value="<?php if (isset($clpl_settings['logo_link'])) echo $clpl_settings['logo_link']; ?>">
                <small>
                  <?php echo __( 'This link will open on the click of logo.', CLPL_TEXTDOMAIN ); ?>
                </small>
              </span>
            </div>
          
          </div>
        </div>

				<!-- END ADDONS LISTING -->
			</div>
		</form>
		
		<div class="clpl-save-settings-container">
			<input type="submit" value="<?php echo __( 'Save Settings', CLPL_TEXTDOMAIN ); ?>" class="button button-large button-primary clpl-button" id="clpl-save-settings" name="save_settings">
			<div id="clpl-error-message"></div>
		</div>

	</div>
	<div id="clpl-help" class="clpl-tabs">
		<div class="changelog feature-list">
			<div class="feature-section col two-col">
        <div>
          <h4><?php echo __( 'Is this plugin translation ready?', CLPL_TEXTDOMAIN ); ?></h4>
          <p><?php echo __( "Yes, this plugin has full translation and localization support.", CLPL_TEXTDOMAIN ); ?></p>
        </div>
				<div class="last-feature">
					<h4><?php echo __( 'Do you offer support?', CLPL_TEXTDOMAIN ); ?></h4>
					<p><?php echo __( 'You can contact me using', CLPL_TEXTDOMAIN ); ?> <code><a href="https://forms.gle/TTdwYUsJpvu8No186" onclick="window.open('https://forms.gle/TTdwYUsJpvu8No186', '', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0'); return false;" title="Contact me">Contact Form</a></code></p>
				</div>
			</div>

			<hr>
		</div>
	</div>
</div>

<script>
jQuery(document).ready(function(e) {
	jQuery('.clpl-tabs').hide();
	if (typeof(localStorage) != 'undefined' ) {
    activetab = localStorage.getItem("clplAddonsActivetab");
  }
  if (activetab != '' && jQuery(activetab).length ) {
    jQuery(activetab).fadeIn();
  } else {
    jQuery('.clpl-tabs:first').fadeIn();
  }

  if (activetab != '' && jQuery(activetab + '-tab').length ) {
      jQuery(activetab + '-tab').addClass('nav-tab-active');
  } else {
      jQuery('.nav-tab-wrapper a:first').addClass('nav-tab-active');
  }
	jQuery('.nav-tab-wrapper a').click(function(e) {
	    jQuery('.nav-tab-wrapper a').removeClass('nav-tab-active');
	    jQuery(this).addClass('nav-tab-active').blur();
	    var clicked_group = jQuery(this).attr('href');
	    if (typeof(localStorage) != 'undefined' ) {
	        localStorage.setItem("clplAddonsActivetab", jQuery(this).attr('href'));
	    }
	    jQuery('.clpl-tabs').hide();
	    jQuery(clicked_group).fadeIn();
	    e.preventDefault();
	});

	jQuery("#clpl-save-settings").on('click', function(e) {
		e.preventDefault();
    jQuery("#clpl-error-message").html('');
    jQuery("#clpl-save-settings").val("Saving Settings ...");
    jQuery("#clpl-save-settings").addClass("is-busy"); 

		var data = jQuery("#clpl-settings-form").serialize();
		jQuery.ajax({
			url: ajaxurl,
			dataType: 'json',
			type: 'post',
			data: data,
			success: function(response) {
				if(response.status == "success"){
					jQuery("#clpl-error-message").html('<div class="updated"><p>'+response.message+'</p></div>');
				} else if(response.status == "error") {
					jQuery("#clpl-error-message").html('<div class="error"><p>'+response.message+'</p></div>');
				} else {
					jQuery("#clpl-error-message").html('<div class="error"><p><?php echo __( "No settings were saved.", CLPL_TEXTDOMAIN ); ?></p></div>');
				}
        jQuery("#clpl-save-settings").val("Save Settings");
        jQuery("#clpl-save-settings").removeClass("is-busy");
			},
      error: function(jqXHR, exception) {
        var msg = '';
        if (jqXHR.status === 0) {
          msg = 'Not connect.\n Verify Network.';
        } else if (jqXHR.status == 404) {
          msg = 'Requested page not found. [404]';
        } else if (jqXHR.status == 500) {
          msg = 'Internal Server Error [500].';
        } else if (exception === 'parsererror') {
          msg = 'Requested JSON parse failed.';
        } else if (exception === 'timeout') {
          msg = 'Time out error.';
        } else if (exception === 'abort') {
          msg = 'Request aborted.';
        } else {
          msg = 'Uncaught Error.\n' + jqXHR.responseText;
        }
        jQuery("#clpl-error-message").html('<div class="error"><p>'+msg+'</p></div>');
        jQuery("#clpl-save-settings").val("Saving Settings");
        jQuery("#clpl-save-settings").removeClass("is-busy");
      }
		});
	});

  /* Media Upload */
  jQuery('.clpl-media-field').click(function(e) {
    e.preventDefault();
    var clplMedia;
    // Extend the wp.media object
    clplMedia = wp.media.frames.file_frame = wp.media({
      title: 'Select media',
      button: {
        text: 'Select media'
      },
      multiple: false 
    });
 
    // When a file is selected, grab the URL and set it as the text field's value
    clplMedia.on('select', function() {
      var attachment = clplMedia.state().get('selection').first().toJSON();
      jQuery(e.target).prev(".clpl-media-field-url").val(attachment.url);
    });
    // Open the upload dialog
    clplMedia.open();
  });
});
</script>