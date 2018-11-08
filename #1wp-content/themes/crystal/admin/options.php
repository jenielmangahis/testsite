<?php
/**
 * Register the settings for the theme. This is required for using the
 * WordPress Settings API.
*/
function graphene_settings_init(){
    // register options set and store it in graphene_settings db entry
    register_setting( 'graphene_options', 'graphene_settings', 'graphene_settings_validator' );
	
	// Load the farbtastic colour picker
	if ( strstr( $_SERVER["REQUEST_URI"], 'page=graphene_options&tab=display' ) ) {
		wp_enqueue_style( 'farbtastic' );
		wp_enqueue_script( 'farbtastic' );
	}
}
add_action( 'admin_init', 'graphene_settings_init' );


/**
 * To support the Media Uploader/Gallery picker in the theme options
 */
function graphene_admin_scripts() {
    wp_enqueue_script( 'media-upload' );
    wp_enqueue_script( 'thickbox' );
}
function graphene_admin_styles() {
    wp_enqueue_style( 'thickbox' );
}
if ( strstr( $_SERVER["REQUEST_URI"], 'page=graphene_options' ) ) {
    add_action( 'admin_print_scripts', 'graphene_admin_scripts' );
    add_action( 'admin_print_styles', 'graphene_admin_styles' );
}



/* Include the settings validator */
include( 'validator.php' );


/**
 * This function generates the theme's general options page in WordPress administration.
 *
 * @package WordPress
 * @subpackage Crystal
 * @since Crystal 1.0
*/
function graphene_options(){
	
	global $graphene_settings, $graphene_defaults;
	
	// Assign a null post ID for the inline media uploader
	$_REQUEST['post_id'] = $_GET['post_id'] = 0;
	
	/* Checks if the form has just been submitted */
	if ( ! isset( $_REQUEST['settings-updated'] ) ) {$_REQUEST['settings-updated'] = false;} 
        
	/* Apply options preset if submitted */ 
	if (isset( $_POST['graphene_preset'] ) ) {
		include( 'options-presets.php' );
	}
	
	/* Import the graphene theme options */
	if (isset( $_POST['graphene_import'] ) ) { 
		graphene_import_form();
		return;
	}
	
	if (isset( $_POST['graphene_import_confirmed'] ) ) {            
		graphene_import_file();
		return;                           
	}
        
        /* Uninstall the theme if confirmed */
	if (isset( $_POST['graphene_uninstall_confirmed'] ) ) { 
		include( 'uninstall.php' );
	}       
	
	/* Display a confirmation page to uninstall the theme */
	if (isset( $_POST['graphene_uninstall'] ) ) { 
	?>

		<div class="wrap">
        <div class="icon32" id="icon-themes"><br /></div>
        <h2><?php _e( 'Uninstall Crystal', 'graphene' ); ?></h2>
        <p><?php _e("Please confirm that you would like to uninstall the Crystal theme. All of the theme's options in the database will be deleted.", 'graphene' ); ?></p>
        <p><?php _e( 'This action is not reversible.', 'graphene' ); ?></p>
        <form action="" method="post">
        	<?php wp_nonce_field( 'graphene-uninstall', 'graphene-uninstall' ); ?>
        	<input type="hidden" name="graphene_uninstall_confirmed" value="true" />
            <input type="submit" class="button graphene_uninstall" value="<?php _e( 'Uninstall Theme', 'graphene' ); ?>" />
        </form>
        </div>
        
		<?php
		return;
	}
	
	/* Get the updated settings before outputting the options page */
	$graphene_settings = graphene_get_settings();
	
	/* This where we start outputting the options page */ ?>
	<div class="wrap meta-box-sortables">
		<div class="icon32" id="icon-themes"><br /></div>
        <h2><?php _e( 'Crystal Theme Options', 'graphene' ); ?></h2>
        
        <p><?php _e( 'These are the global settings for the theme. You may override some of the settings in individual posts and pages.', 'graphene' ); ?></p>
        
		<?php settings_errors(); ?>
        
        <?php /* Print the options tabs */ ?>
        <?php 
            if ( $_GET['page'] == 'graphene_options' ) :
                $tabs = array(
                    'general' => __( 'General', 'graphene' ),
                    'display' => __( 'Display', 'graphene' ),
                    'advanced' => __( 'Advanced', 'graphene' ),
                    );
                $current_tab = (isset( $_GET['tab'] ) ) ? $_GET['tab'] : 'general';
                graphene_options_tabs( $current_tab, $tabs); 
            endif;
        ?>
        
        <div class="left-wrap">
        
        <?php /* Begin the main form */ ?>
        <form method="post" action="options.php" class="mainform clearfix">
        
        	<?php /* The form submit button */ ?>
            <p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'graphene' ); ?>" /></p>
		
            <?php /* Output wordpress hidden form fields, e.g. nonce etc. */ ?>
            <?php settings_fields( 'graphene_options' ); ?>
        
        
            <?php 
            
                /* Display the current tab */
                switch ( $current_tab) {
                    case 'advanced': /* Display the Display settings */ 
                        graphene_options_advanced();
                        break;
                    
                    case 'display': /* Display the Display settings */ 
                        graphene_options_display();
                        break;

                    default: /* Display the General settings */ 
                        graphene_options_general();
                        break;
                }            
            ?>
            
        
            <?php /* The form submit button */ ?>
            <p class="submit"><input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'graphene' ); ?>" /></p>
        
        <?php /* Close the main form */ ?>
        </form>
        
        </div><!-- #left-wrap -->
        
        <div class="side-wrap">
        
        <?php /* PayPal's donation button */ ?>
        <!--<div class="postbox donation">
            <div>
        		<h3 class="hndle"><?php// _e( 'Support the developer', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <p><?php //_e( 'Developing this awesome theme took a lot of effort and time, months and months of continuous voluntary unpaid work. If you like this theme or if you are using it for commercial websites, please consider a donation to the developer to help support future updates and development.', 'graphene' ); ?></p>
                <form action="https://www.paypal.com/cgi-bin/webscr" method="post" style="text-align:center;">
                    <input type="hidden" name="cmd" value="_s-xclick" />
                    <input type="hidden" name="hosted_button_id" value="SJRVDSEJF6VPU" />
                    <input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" />
                    <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
                </form>
            </div>
        </div>-->
        
        
        <?php /* Survey notification */ ?>
        <!--
        <div class="postbox donation">
            <div>
        		<h3 class="hndle"><?php //_e( 'Crystal Usage Survey', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <p><?php //_e( 'Help us to get to know you better. Take the Crystal theme usage survey now! Every opinion counts towards making the theme better. The survey is very short, and is completely anonymous.', 'graphene' ); ?></p>
                <p><a href="http://www.surveymonkey.com/s/WKJ7SQD" class="button"><?php _e( 'Take the survey', 'graphene' ); ?> &raquo;</a></p>
            </div>
        </div>
        -->
        
        
        <?php /* Natively supported plugins */ ?>
        <div class="postbox">
        	<div>
                <h3 class="hndle"><?php _e( 'Add-ons and plugins', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">                
                <h4><?php _e( 'Plugins', 'graphene' ); ?></h4>
                <p><?php _e( 'The plugins listed here are natively supported by the theme. All you need to do is install the plugins and activate them.', 'graphene' ); ?></p>
                <ul class="add-ons native-plugins">
                	<?php 
						$plugins = array(
										array( 'name' => 'WP-PageNavi', 'function' => 'wp_pagenavi' ),
										array( 'name' => 'WP-CommentNavi', 'function' => 'wp_commentnavi' ),
										array( 'name' => 'WP-Email', 'function' => 'wp_email' ),
										array( 'name' => 'Breadcrumb NavXT', 'function' => 'bcn_display' ),
									);
						foreach ( $plugins as $plugin) :
					?>
                	<li>
                    	<span class="title"><?php echo $plugin['name']; ?>: </span>
                        <?php if (function_exists( $plugin['function'] ) ) : ?><span class="activated"><?php _e( 'Activated', 'graphene' ); ?></span>
                        <?php else : ?><span class="not-active"><?php _e( 'Not installed', 'graphene' ); ?></span><?php endif; ?>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
            
        
        <?php /* Options Presets. This uses separate form than the main form */ ?>
        <div class="postbox preset">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
                <h3 class="hndle"><?php _e( 'Options Presets', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <p><?php _e("The default settings for the theme is preconfigured for use in blogs. If you're using this theme primarily for a normal website, or if you want to reset the settings to their default values, you can apply one of the available options presets below.", 'graphene' ); ?></p>
                <p><?php _e("Note that you can still configure the individual settings after you apply any preset.", 'graphene' ); ?></p>
                <form action="" method="post">
                    <?php wp_nonce_field( 'graphene-preset', 'graphene-preset' ); ?>
                	<table class="form-table">
                        <tr>
                            <th scope="row" style="width: 140px;"><?php _e( 'Select Options Preset', 'graphene' ); ?></th>
                            <td class="options-cat-list">
                                <input type="radio" name="graphene_options_preset" value="website" id="graphene_options_preset-website" />
                                <label for="graphene_options_preset-website"><?php _e( 'Normal website', 'graphene' ); ?></label>
                                <br />                                
                                <input type="radio" name="graphene_options_preset" value="reset" id="graphene_options_preset-reset" />
                                <label for="graphene_options_preset-reset"><?php _e( 'Reset to default settings', 'graphene' ); ?></label>
                            </td>
                        </tr>
                    </table>
                    <input type="hidden" name="graphene_preset" value="true" />
                    <input type="submit" class="button graphene_preset" value="<?php _e( 'Apply Options Preset', 'graphene' ); ?>" />
                </form>
            </div>
        </div>
        
        
        <?php /* Theme import/export */ ?>    
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Import/export theme options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <?php if (graphene_can_import() ) : ?>
                <p><strong><?php _e( 'Import', 'graphene' ); ?></strong></p>    
                <form action="" method="post">
                    <input type="hidden" name="graphene_import" value="true" />
                    <input type="submit" class="button" value="<?php _e( 'Import Theme options', 'graphene' ); ?>" />
                </form> <br />
                <p><strong><?php _e( 'Export', 'graphene' ); ?></strong></p>                
                <form action="" method="post">
                	<?php wp_nonce_field( 'graphene-export', 'graphene-export' ); ?>
                    <input type="hidden" name="graphene_export" value="true" />
                    <input type="submit" class="button" value="<?php _e( 'Export Theme options', 'graphene' ); ?>" />
                </form>              
                <?php else : ?>
                    <p><?php printf( __( 'Import and export is only available for to users with PHP version 5.2.1 and higher. You are using PHP version %s.', 'graphene' ), PHP_VERSION);  ?></p>
                <?php endif; ?>                
            </div>
        </div>
            
        
        <?php /* Theme's uninstall */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Uninstall theme', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <p><?php _e("<strong>Be careful!</strong> Uninstalling the theme will remove all of the theme's options from the database. Do this only if you decide not to use the theme anymore.",'graphene' ); ?></p>
                <p><?php _e( 'If you just want to try another theme, there is no need to uninstall this theme. Simply activate the other theme in the Appearance > Themes admin page.','graphene' ); ?></p>
                <p><?php _e("Note that uninstalling this theme <strong>does not remove</strong> the theme's files. To delete the files after you have uninstalled this theme, go to Appearances > Themes and delete the theme from there.",'graphene' ); ?></p>
                <form action="" method="post">
                    <?php wp_nonce_field( 'graphene-options', 'graphene-options' ); ?>
                
                    <input type="hidden" name="graphene_uninstall" value="true" />
                    <input type="submit" class="button graphene_uninstall" value="<?php _e( 'Uninstall Theme', 'graphene' ); ?>" />
                </form>
            </div>
        </div>
            
            
         </div><!-- #side-wrap --> 
         <div class="clear"></div>
         <div class="icon32" id="icon-themes"><br /></div>
         <h2><?php _e( 'Subscribe Form', 'graphene' ); ?></h2>
         <p>Subscribe to receive lifetime updates and upgrade of Crystal Theme.</p>
         <!-- AWeber Web Form Generator 3.0 -->
<style type="text/css">
#af-form-504138212 .af-body 
.af-textWrap{width:70%;display:block;float:right;}
#af-form-504138212 .af-body 
.privacyPolicy{color:#000000;font-size:12px;font-family:, serif;}
#af-form-504138212 .af-body 
a{color:#000000;text-decoration:underline;font-style:normal;font-weight:normal;}
#af-form-504138212 .af-body input.text, #af-form-504138212 .af-body 
textarea{background-color:#FFFFFF;border-color:#CCCCCC;border-width:2px;border-style:inset;color:#000000;text-decoration:none;font-style:normal;font-weight:normal;font-size:inherit;font-family:inherit;}
#af-form-504138212 .af-body input.text:focus, #af-form-504138212 
.af-body 
textarea:focus{background-color:inherit;border-color:#CCCCCC;border-width:2px;border-style:inset;}
#af-form-504138212 .af-body 
label.previewLabel{display:block;float:left;width:25%;text-align:left;color:#000000;text-decoration:none;font-style:normal;font-weight:normal;font-size:inherit;font-family:inherit;}
#af-form-504138212 
.af-body{padding-bottom:15px;background-repeat:no-repeat;background-position:inherit;background-image:none;color:#000000;font-size:12px;font-family:,

serif;}
#af-form-504138212 .af-quirksMode{padding-right:15px;padding-left:15px;}
#af-form-504138212 .af-standards 
.af-element{padding-right:15px;padding-left:15px;}
#af-form-504138212 .buttonContainer 
input.submit{color:#000000;text-decoration:none;font-style:normal;font-weight:normal;font-size:inherit;font-family:inherit;border:solid 1px #999;}
#af-form-504138212 .buttonContainer input.submit{width:auto;}
#af-form-504138212 .buttonContainer{text-align:center;}
#af-form-504138212 button,#af-form-504138212 input,#af-form-504138212 
submit,#af-form-504138212 textarea,#af-form-504138212 
select,#af-form-504138212 label,#af-form-504138212 
optgroup,#af-form-504138212 option{float:none;position:static;margin:0;}
#af-form-504138212 div{margin:0;}
#af-form-504138212 form,#af-form-504138212 
textarea,.af-form-wrapper,.af-form-close-button,#af-form-504138212 
img{float:left;color:inherit;position:static;background-color:none;border:none;margin:0;padding:0;}
#af-form-504138212 input,#af-form-504138212 button,#af-form-504138212 
textarea,#af-form-504138212 select{font-size:100%;}
#af-form-504138212 select,#af-form-504138212 label,#af-form-504138212 
optgroup,#af-form-504138212 option{padding:0;}
#af-form-504138212,#af-form-504138212 .quirksMode{width:190px;}
#af-form-504138212.af-quirksMode{overflow-x:hidden;}
#af-form-504138212{background-color:transparent;border-color:inherit;border-width:none;border-style:none;}
#af-form-504138212{display:block;}
#af-form-504138212{overflow:hidden;}
.af-body .af-textWrap{text-align:left;}
.af-body input.image{border:none!important;}
.af-body input.submit,.af-body input.image,.af-form .af-element 
input.button{float:none!important;}
.af-body input.text{width:100%;float:none;padding:2px!important;}
.af-body.af-standards input.submit{padding:4px 12px;}
.af-clear{clear:both;}
.af-element label{text-align:left;display:block;float:left;}
.af-element{padding:5px 0;}
.af-form-wrapper{text-indent:0;}
.af-form{text-align:left;margin:auto;}
.af-quirksMode 
.af-element{padding-left:0!important;padding-right:0!important;}
.lbl-right .af-element label{text-align:right;}
body {
}
</style>
<form method="post" class="af-form-wrapper" 
action="http://www.aweber.com/scripts/addlead.pl" >
<div style="display: none;">
<input type="hidden" name="meta_web_form_id" value="504138212" />
<input type="hidden" name="meta_split_id" value="" />
<input type="hidden" name="listname" value="crystaltheme" />
<input type="hidden" name="redirect" 
value="http://www.aweber.com/thankyou-coi.htm?m=video" 
id="redirect_d0bd5861d9b509b205376c2710a01131" />

<input type="hidden" name="meta_adtracking" value="Crystaltheme" />
<input type="hidden" name="meta_message" value="1" />
<input type="hidden" name="meta_required" value="name,email" />

<input type="hidden" name="meta_tooltip" value="" />
</div>
<div id="af-form-504138212" class="af-form"><div id="af-body-504138212"  
class="af-body af-standards">
<div class="af-element">
<label class="previewLabel" for="awf_field-28500557">Name: </label>
<div class="af-textWrap">
<input id="awf_field-28500557" type="text" name="name" class="text" 
value=""  tabindex="500" />
</div>
<div class="af-clear"></div></div>
<div class="af-element">
<label class="previewLabel" for="awf_field-28500558">Email: </label>
<div class="af-textWrap"><input class="text" id="awf_field-28500558" 
type="text" name="email" value="" tabindex="501"  />
</div><div class="af-clear"></div>
</div>
<div class="af-element buttonContainer">
<input name="submit" class="submit" type="submit" value="Submit" 
tabindex="502" />
<div class="af-clear"></div>
</div>
<div class="af-element privacyPolicy" style="text-align: center"><p><a 
title="Privacy Policy" href="http://www.aweber.com/permission.htm" 
target="_blank">We respect your email privacy</a></p>
<div class="af-clear"></div>
</div>
</div>
</div>
<div style="display: none;"><img 
src="http://forms.aweber.com/form/displays.htm?id=rAwsjMwcTIxM" alt="" 
/></div>
</form>
<script type="text/javascript">
<!--
     (function() {
         var IE = /*@cc_on!@*/false;
         if (!IE) { return; }
         if (document.compatMode && document.compatMode == 'BackCompat') {
             if (document.getElementById("af-form-504138212")) {
                 document.getElementById("af-form-504138212").className = 'af-form af-quirksMode';
             }
             if (document.getElementById("af-body-504138212")) {
                 document.getElementById("af-body-504138212").className = "af-body inline af-quirksMode";
             }
             if (document.getElementById("af-header-504138212")) {
                 document.getElementById("af-header-504138212").className = "af-header af-quirksMode";
             }
             if (document.getElementById("af-footer-504138212")) {
                 document.getElementById("af-footer-504138212").className = "af-footer af-quirksMode";
             }
         }
     })();
     -->
</script>

<!-- /AWeber Web Form Generator 3.0 -->
           
         
         <?php if ( $graphene_settings['enable_preview'] == true ) : ?>
         <div class="clear"></div>
         <div class="icon32" id="icon-themes"><br /></div>
         <h2><?php _e( 'Preview', 'graphene' ); ?></h2>
         <p><?php _e( 'The preview will be updated when the "Save Options" button above is clicked.', 'graphene' ); ?></p>
         <iframe src="<?php echo home_url( '?preview=true' ); ?>" width="95%" height="600" ></iframe>
         <?php endif; ?>
    </div><!-- #wrap -->
    
    
<?php    
} // Closes the graphene_options() function definition 

function graphene_options_general() { 
    
    global $graphene_settings, $wpdb;
    ?>
        <input type="hidden" name="graphene_general" value="true" />        
        
        <?php /* Menu Exclude Page */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Menu (Exclude Pages) Option', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
            	<table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="menuexclude_pages"><?php _e( 'Exclude Pages', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="text" name="graphene_settings[menuexclude_pages]" value="<?php echo $graphene_settings['menuexclude_pages']?>" id="menuexclude_pages" /><br />
                        <span class="description">
						<?php _e( 'Enter IDs separated by comma.', 'graphene' ); ?>
                        </span> 
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <?php /* Google search */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Google Custom Search', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
            	<table class="form-table">
                	<tr>
                        <th scope="row">
                            <label for="google_search"><?php _e( 'Enable Google Custom search', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[google_search]" id="google_search" <?php checked( $graphene_settings['google_search']); ?> value="true" data-toggleOptions="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="google_search_form"><?php _e( 'Google Form Code', 'graphene' ); ?></label>
                        </th>
                        <td>
                        	<textarea name="graphene_settings[google_search_form]" id="google_search_form" cols="50" rows="5"><?php echo $graphene_settings['google_search_form']; ?></textarea>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <?php /* Create Pages */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Create Pages', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
            	<table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="about_page"><?php _e( 'About Page', 'graphene' ); ?></label>
                        </th>
                        <?php $name = $wpdb->get_row("SELECT ID FROM wp_posts WHERE post_title = 'About Us'", 'ARRAY_A');?>
                        <td><input type="checkbox" name="graphene_settings[about_page]" id="about_page" <?php if(!empty($name)) echo 'checked="checked"'; else if(!empty($name) && checked( $graphene_settings['about_page'])) checked( $graphene_settings['about_page']); ?> value="true" data-toggleOptions="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="privacypolicy_page"><?php _e( 'Privacy Policy Page', 'graphene' ); ?></label>
                        </th>
                        <?php $name = $wpdb->get_row("SELECT ID FROM wp_posts WHERE post_title = 'Privacy Policy'", 'ARRAY_A');?>
                        <td><input type="checkbox" name="graphene_settings[privacypolicy_page]" id="privacypolicy_page" <?php if(!empty($name)) echo 'checked="checked"'; else if(!empty($name) && checked( $graphene_settings['privacypolicy_page'])) checked( $graphene_settings['privacypolicy_page']); ?> value="true" data-toggleOptions="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="contact_page"><?php _e( 'Contact Page', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <?php $name = $wpdb->get_row("SELECT ID FROM wp_posts WHERE post_title = 'Contact Us'", 'ARRAY_A');?>
                        <input type="checkbox" name="graphene_settings[contact_page]" id="contact_page" <?php if(!empty($name)) echo 'checked="checked"'; else if(!empty($name) && checked( $graphene_settings['contact_page'])) checked( $graphene_settings['contact_page']); ?> value="true" data-toggleOptions="true" /><br />
                        <span class="description">
						<?php _e( 'To create this page with success you need to have "Contact Form 7" plugin installed.', 'graphene' ); ?>
                        </span>                        
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="sitemap_page"><?php _e( 'SiteMap Page', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <?php $name = $wpdb->get_row("SELECT ID FROM wp_posts WHERE post_title = 'Site Map'", 'ARRAY_A');?>
                        <input type="checkbox" name="graphene_settings[sitemap_page]" id="sitemap_page" <?php if(!empty($name)) echo 'checked="checked"'; else if(!empty($name) && checked( $graphene_settings['sitemap_page'])) checked( $graphene_settings['sitemap_page']); ?>  value="true" data-toggleOptions="true" /><br />
                        <span class="description">
						<?php _e( 'To create this page with success you need to have "WP Archive-Sitemap Generator" plugin installed.', 'graphene' ); ?>
                        </span>                        
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <?php /* Exclude Categories from Sidebar */ ?>
        <!--<div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php //_e( 'Categories Sidebar Widget', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
            	<table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="sidebar_exclude_categories"><?php //_e( 'Exclude Categories', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="text" name="graphene_settings[sidebar_exclude_categories]" value="<?php //echo $graphene_settings['sidebar_exclude_categories']?>" id="sidebar_exclude_categories" /><br />
                        <span class="description">
						<?php //_e( 'Enter IDs separated by comma.', 'graphene' ); ?>
                        </span> 
                        </td>
                    </tr>
                </table>
            </div>
        </div>-->
        
        
        <?php /* Slider Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Slider Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="slider_disable"><?php _e( 'Disable slider', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[slider_disable]" id="slider_disable" <?php checked( $graphene_settings['slider_disable'] ); ?> value="true" data-toggleOptions="true" /></td>
                    </tr>
                </table>
                <table class="form-table<?php if ( $graphene_settings['slider_disable'] == true ) echo ' hide'; ?>">
                    <tr>
                        <th scope="row">
                            <label><?php _e( 'What do you want to show in the slider', 'graphene' ); ?></label><br />                            
                        </th>
                        <td>
                            <input type="radio" name="graphene_settings[slider_type]" value="latest_posts" id="slider_type_latest_posts" <?php checked( $graphene_settings['slider_type'], 'latest_posts' ); ?>/>
                            <label for="slider_type_latest_posts"><?php _e( 'Show latest posts', 'graphene' ); ?></label>                            
                            <br />
                            <input type="radio" name="graphene_settings[slider_type]" value="random" id="slider_type_random" <?php checked( $graphene_settings['slider_type'], 'random' ); ?>/>
                            <label for="slider_type_random"><?php _e( 'Show random posts', 'graphene' ); ?></label>
                            <br />
                            <input type="radio" name="graphene_settings[slider_type]" value="posts_pages" id="slider_type_posts_pages" <?php checked( $graphene_settings['slider_type'], 'posts_pages' ); ?>/>
                            <label for="slider_type_posts_pages"><?php _e( 'Show specific posts/pages', 'graphene' ); ?></label>                            
                            <br />
                            <input type="radio" name="graphene_settings[slider_type]" value="categories" id="slider_type_categories" <?php checked( $graphene_settings['slider_type'], 'categories' ); ?>/>
                            <label for="slider_type_categories"><?php _e( 'Show posts from categories', 'graphene' ); ?></label>                            
                        </td>
                    </tr>
                    <tr id="row_slider_type_posts_pages"<?php if ( $graphene_settings['slider_type'] != 'posts_pages' ) echo ' class="hide"'; ?>>
                        <th scope="row">
                            <label for="slider_specific_posts"><?php _e( 'Posts and/or pages to display', 'graphene' ); ?></label>
                        </th>
                        <td>
                            <input type="text" name="graphene_settings[slider_specific_posts]" id="slider_specific_posts" value="<?php echo $graphene_settings['slider_specific_posts']; ?>" size="60" class="wide code" /><br />
                            <span class="description">
							<?php _e( 'Enter ID of posts and/or pages to be displayed, separated by comma. Example: <code>1,13,45,33</code>', 'graphene' ); ?><br />
							<?php _e( 'Applicable only if <strong>Show specific posts/pages</strong> is selected above.', 'graphene' ); ?>
                            </span>                        
                        </td>
                    </tr>
                    <tr id="row_slider_type_categories"<?php if ( $graphene_settings['slider_type'] != 'categories' ) echo ' class="hide"'; ?>>
                        <th scope="row">
                            <label for="slider_specific_categories"><?php _e( 'Categories to display', 'graphene' ); ?></label>
                            <small><?php _e( 'All posts within the categories selected here will be displayed on the slider. Usage example: create a new category "Featured" and assign all posts to be displayed on the slider to that category, and then select that category here.', 'graphene' ); ?></small>
                        </th>
                        <td>
                            <select name="graphene_settings[slider_specific_categories][]" id="slider_specific_categories" multiple="multiple" class="select-multiple">
                               <?php /* Get the list of categories */ 
                                    $selected_cats = $graphene_settings['slider_specific_categories'];
                                    $categories = get_categories();
                                    foreach ( $categories as $category) :
                                ?>
                                <option value="<?php echo $category->cat_ID; ?>" <?php if ( $selected_cats && in_array( $category->cat_ID, $selected_cats ) ) { echo 'selected="selected"'; }?>><?php echo $category->cat_name; ?></option>
                                <?php endforeach; ?> 
                            </select>                       
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="slider_postcount"><?php _e( 'Number of posts to display', 'graphene' ); ?></label>
                        </th>
                        <td>
                            <input type="text" name="graphene_settings[slider_postcount]" id="slider_postcount" value="<?php echo $graphene_settings['slider_postcount']; ?>" size="3" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="slider_img"><?php _e( 'Slider image', 'graphene' ); ?></label>
                        </th>
                        <td>
                            <select name="graphene_settings[slider_img]" id="slider_img">
                                <option value="disabled" <?php selected( $graphene_settings['slider_img'], 'disabled' ); ?>><?php _e("Don't show image", 'graphene' ); ?></option>
                                <option value="featured_image" <?php selected( $graphene_settings['slider_img'], 'featured_image' ); ?>><?php _e("Featured Image", 'graphene' ); ?></option>
                                <option value="post_image" <?php selected( $graphene_settings['slider_img'], 'post_image' ); ?>><?php _e("First image in post", 'graphene' ); ?></option>
                                <option value="custom_url" <?php selected( $graphene_settings['slider_img'], 'custom_url' ); ?>><?php _e("Custom URL", 'graphene' ); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="slider_imgurl"><?php _e( 'Custom slider image URL', 'graphene' ); ?></label>


                        </th>
                        <td>
                            <input type="text" name="graphene_settings[slider_imgurl]" id="slider_imgurl" value="<?php echo $graphene_settings['slider_imgurl']; ?>" size="60" class="widefat code" /><br />
                            <span class="description"><a href="#" class="upload_image_button"><?php _e( 'Upload or select image from gallery', 'graphene' );?></a> - <?php _e( 'Make sure you select Custom URL in the slider image option above to use this custom url.', 'graphene' ); ?></span>
                            
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="slider_display_style"><?php _e( 'Slider display style', 'graphene' ); ?></label><br />
                        </th>
                        <td>
                            <select name="graphene_settings[slider_display_style]" id="slider_display_style">
                                <option value="thumbnail-excerpt" <?php selected( $graphene_settings['slider_display_style'], 'thumbnail-excerpt' ); ?>><?php _e( 'Thumbnail and excerpt', 'graphene' ); ?></option>
                                <option value="bgimage-excerpt" <?php selected( $graphene_settings['slider_display_style'], 'bgimage-excerpt' ); ?>><?php _e( 'Background image and excerpt', 'graphene' ); ?></option>
                                <option value="full-post" <?php selected( $graphene_settings['slider_display_style'], 'full-post' ); ?>><?php _e( 'Full post content', 'graphene' ); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="slider_animation"><?php _e( 'Slider animation', 'graphene' ); ?></label>
                        </th>
                        <td>
                            
                            <select name="graphene_settings[slider_animation]" id="slider_animation">
                                <option value="horizontal-slide" <?php selected( $graphene_settings['slider_animation'], 'slide' ); ?>><?php _e( 'Horizontal slide', 'graphene' ); ?></option>
                                <option value="vertical-slide" <?php selected( $graphene_settings['slider_animation'], 'vertical-slide' ); ?>><?php _e( 'Vertical slide', 'graphene' ); ?></option>
                                <option value="fade" <?php selected( $graphene_settings['slider_animation'], 'fade' ); ?>><?php _e( 'Fade', 'graphene' ); ?></option>
                                <option value="none" <?php selected( $graphene_settings['slider_animation'], 'none' ); ?>><?php _e( 'No effect', 'graphene' ); ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="slider_height"><?php _e( 'Slider height', 'graphene' ); ?></label>
                        </th>
                        <td>
                            <input type="text" name="graphene_settings[slider_height]" id="slider_height" value="<?php echo $graphene_settings['slider_height']; ?>" size="3" /> px                        
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="slider_speed"><?php _e( 'Slider speed', 'graphene' ); ?></label>
                        </th>
                        <td>
                            <input type="text" name="graphene_settings[slider_speed]" id="slider_speed" value="<?php echo $graphene_settings['slider_speed']; ?>" size="4" /> <?php _e( 'milliseconds', 'graphene' ); ?><br />
                            <span class="description"><?php _e( 'This is the duration that each slider item will be shown', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="slider_trans_speed"><?php _e( 'Slider transition speed', 'graphene' ); ?></label>
                        </th>
                        <td>
                            <input type="text" name="graphene_settings[slider_trans_speed]" id="slider_trans_speed" value="<?php echo $graphene_settings['slider_trans_speed']; ?>" size="4" /> <?php _e( 'milliseconds', 'graphene' ); ?><br />
                            <span class="description"><?php _e( 'This is the speed of the slider transition. Lower values = higher speed.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="slider_position"><?php _e( 'Move slider to bottom of page', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[slider_position]" id="slider_position" <?php checked( $graphene_settings['slider_position'] ); ?> value="true" /></td>
                    </tr>                    
                </table>
            </div>
        </div>
        
        
        <?php /* Front Page Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Front Page Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">       	
                    <tr>
                        <th scope="row">
                            <label for="frontpage_posts_cats"><?php _e( 'Front page posts categories', 'graphene' ); ?></label>
                            <p>
                            	<small><?php _e( 'Only posts that belong to the categories selected here will be displayed on the front page. Does not affect Static Front Page.', 'graphene' ); ?></small>
                            </p>
                        </th>
                        <td>
                            <select name="graphene_settings[frontpage_posts_cats][]" id="frontpage_posts_cats" multiple="multiple" class="select-multiple">
                                <option value="" <?php if ( empty( $graphene_settings['frontpage_posts_cats'] ) ) { echo 'selected="selected"'; } ?>><?php _e( '--Disabled--', 'graphene' ); ?></option>
                                <?php /* Get the list of categories */ 
                                    $categories = get_categories();
                                    foreach ( $categories as $category) :
                                ?>
                                <option value="<?php echo $category->cat_ID; ?>" <?php if ( in_array( $category->cat_ID, $graphene_settings['frontpage_posts_cats'] ) ) {echo 'selected="selected"';}?>><?php echo $category->cat_name; ?></option>
                                <?php endforeach; ?>
                            </select><br />
                            <span class="description"><?php _e( 'You may select multiple categories by holding down the CTRL key.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <?php /* Homepage options */ ?>
        <div class="postbox">
            <div class="head-wrap">
            	<div title="Click to toggle" class="handlediv"><br /></div>
            	<h3 class="hndle"><?php _e( 'Homepage Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
            	<table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="homepage_description"><?php _e( 'HomePage Description', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <textarea name="graphene_settings[homepage_description]" id="homepage_description" cols="50" rows="5"><?php echo $graphene_settings['homepage_description']; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="homepage_keywords"><?php _e( 'HomePage Keywords', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="text" name="graphene_settings[homepage_keywords]" id="homepage_keywords" value="<?php echo $graphene_settings['homepage_keywords']; ?>" size="30"/><br />
                        <span class="description">
                        <?php _e( 'Enter keyowrds separated by comma.', 'graphene' ); ?><br />
                        </span>  
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        
        <?php /* Homepage panes options */ ?>
        <div class="postbox">
            <div class="head-wrap">
            	<div title="Click to toggle" class="handlediv"><br /></div>
            	<h3 class="hndle"><?php _e( 'Homepage Panes', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">            	
                <?php if ( 'page' == get_option( 'show_on_front' ) ) : ?>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="disable_homepage_panes"><?php _e( 'Disable homepage panes', 'graphene' ); ?></label>
                        </th>
                        <td>
                            <input type="checkbox" name="graphene_settings[disable_homepage_panes]" id="disable_homepage_panes" <?php checked( $graphene_settings['disable_homepage_panes'] ); ?> value="true" data-toggleOptions="true" />
                        </td>
                    </tr>
                </table>
                <table class="form-table site-summary<?php if ( $graphene_settings['disable_homepage_panes'] == true ) echo ' hide'; ?>">
                	<tr>
                    	<th scope="row">
                            <?php _e( 'Type of content to show', 'graphene' ); ?>
                        </th>
                        <td>
                            <input type="radio" name="graphene_settings[show_post_type]" value="latest-posts" id="show_post_type_latest-posts" <?php checked( $graphene_settings['show_post_type'], 'latest-posts' ); ?>/>
                            <label for="show_post_type_latest-posts"><?php _e( 'Latest posts', 'graphene' ); ?></label>
                            
                            <input type="radio" name="graphene_settings[show_post_type]" value="cat-latest-posts" id="show_post_type_cat-latest-posts" <?php checked( $graphene_settings['show_post_type'],  'cat-latest-posts' ); ?>/>
                            <label for="show_post_type_cat-latest-posts"><?php _e( 'Latest posts by category', 'graphene' ); ?></label>
                           
                            <input type="radio" name="graphene_settings[show_post_type]" value="posts" id="show_post_type_pages" <?php checked( $graphene_settings['show_post_type'], 'posts' ); ?>/>
                            <label for="show_post_type_pages"><?php _e( 'Posts and/or pages', 'graphene' ); ?></label>
                        </td>
                    </tr>
                    <tr id="row_show_post_type_latest-posts"<?php if ( ! in_array( $graphene_settings['show_post_type'], array( 'latest-posts', 'cat-latest-posts' ) )) echo ' class="hide"'; ?>>
                        <th scope="row">
                            <label for="homepage_panes_count"><?php _e( 'Number of latest posts to display', 'graphene' ); ?></label>
                        </th>
                        <td>
                            <input type="text" name="graphene_settings[homepage_panes_count]" id="homepage_panes_count" value="<?php echo $graphene_settings['homepage_panes_count']; ?>" size="1" /><br />
                            <span class="description"><?php _e( 'Applicable only if <strong>Latest posts</strong> or <strong>Latest posts by category</strong> is selected above.', 'graphene' ); ?></span>                        
                        </td>
                    </tr>
                    <tr id="row_show_post_type_cat-latest-posts"<?php if ( 'cat-latest-posts' != $graphene_settings['show_post_type'] ) echo ' class="hide"'; ?>>
                        <th scope="row">
                            <label for="homepage_panes_cat"><?php _e( 'Category to show latest posts from', 'graphene' ); ?></label>
                        </th>
                        <td>                            
                            <select name="graphene_settings[homepage_panes_cat][]" id="homepage_panes_cat" multiple="multiple" class="select-multiple">
                                <?php /* Get the list of categories */ 
                                    foreach ( $categories as $category) :
                                ?>
                                <option value="<?php echo $category->cat_ID; ?>" <?php if ( in_array( $category->cat_ID, (array) $graphene_settings['homepage_panes_cat'] ) ) echo 'selected="selected"'; ?>><?php echo $category->cat_name; ?></option>
                                <?php endforeach; ?>
                            </select><br />
                            <span class="description"><?php _e( 'Applicable only if <strong>Latest posts by category</strong> is selected above.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    <tr id="row_show_post_type_posts"<?php if ( 'posts' != $graphene_settings['show_post_type'] ) echo ' class="hide"'; ?>>
                        <th scope="row">
                            <label for="homepage_panes_posts"><?php _e( 'Posts and/or pages to display', 'graphene' ); ?></label>
                        </th>
                        <td>
                            <input type="text" name="graphene_settings[homepage_panes_posts]" id="homepage_panes_posts" value="<?php echo $graphene_settings['homepage_panes_posts']; ?>" size="10" class="code" /><br />
                            <span class="description">
							<?php _e( 'Enter ID of posts and/or pages to be displayed, separated by comma. Example: <code>1,13,45,33</code>', 'graphene' ); ?><br />
							<?php _e( 'Applicable only if <strong>Posts and/or pages</strong> is selected above.', 'graphene' ); ?>
                            </span>                        
                        </td>
                    </tr>                    
                </table>
                <?php else : ?>
                <p><?php _e( '<strong>Note:</strong> homepage panes are only displayed when using a <a href="http://codex.wordpress.org/Creating_a_Static_Front_Page">static front page</a>.', 'graphene' ); ?></p>
                <?php endif; ?>
            </div>
        </div>
        
        
        <?php /* Comments Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Comments Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">       	
                    <tr>
                        <th scope="row">
                            <label for="comments_setting"><?php _e( 'Commenting', 'graphene' ); ?></label>                            
                        </th>
                        <td>
                            <select name="graphene_settings[comments_setting]" id="comments_setting">
                                <option value="wordpress" <?php selected( $graphene_settings['comments_setting'], 'wordpress' ); ?>><?php _e( 'Use WordPress settings', 'graphene' ); ?></option>
                                <option value="disabled_pages" <?php selected( $graphene_settings['comments_setting'], 'disabled_pages' ); ?>><?php _e( 'Disabled for pages', 'graphene' ); ?></option>
                                <option value="disabled_completely" <?php selected( $graphene_settings['comments_setting'], 'disabled_completely' ); ?>><?php _e( 'Disabled completely', 'graphene' ); ?></option>                               
                            </select><br />
                            <span class="description"><?php _e( 'Note: this setting overrides the global WordPress Discussion Setting called "Allow people to post comments on new articles" and also the "Allow comments" option for individual posts/pages.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        
        <?php /* Child Page Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Child Page Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">       	
                    <tr>
                        <th scope="row">
                            <label for="hide_parent_content_if_empty"><?php _e( 'Hide parent box if content is empty', 'graphene' ); ?></label>                            
                        </th>
                        <td><input type="checkbox" name="graphene_settings[hide_parent_content_if_empty]" id="hide_parent_content_if_empty" <?php checked( $graphene_settings['hide_parent_content_if_empty'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="child_page_listing"><?php _e( 'Child page listings', 'graphene' ); ?></label>                            
                        </th>
                        <td>
                            <select name="graphene_settings[child_page_listing]" id="child_page_listing">
                                <option value="show_always" <?php selected( $graphene_settings['child_page_listing'], 'show_always' ); ?>><?php _e( 'Show listing', 'graphene' ); ?></option>
                                <option value="hide" <?php selected( $graphene_settings['child_page_listing'], 'hide' ); ?>><?php _e( 'Hide listing', 'graphene' ); ?></option>
                                <option value="show_if_parent_empty" <?php selected( $graphene_settings['child_page_listing'], 'show_if_parent_empty' ); ?>><?php _e( 'Only show listing if parent content is empty', 'graphene' ); ?></option>
                            </select>
                        </td>                            
                    </tr>
                </table>
            </div>
        </div>
        
        
        <?php /* Widget Area Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Widget Area Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
            	<h4><?php _e( 'Header widget area', 'graphene' ); ?></h4>
                <p><?php _e( '<strong>Important:</strong> This widget area is unstyled, as it is often used for advertisement banners, etc. If you enable it, make sure you style it to your needs using the Custom CSS option.', 'graphene' ); ?></p>
                <table class="form-table site-summary">
                    <tr>
                        <th scope="row">
                        	<label for="enable_header_widget"><?php _e( 'Enable header widget area', 'graphene' ); ?></label>
                        </th>
                        <td>
                        	<input type="checkbox" value="true" name="graphene_settings[enable_header_widget]" id="enable_header_widget" <?php checked( $graphene_settings['enable_header_widget'] ); ?> />
                        </td>
                    </tr>
                </table>
                
                
                <h4><?php _e( 'Alternate Widgets', 'graphene' ); ?></h4>
                <p><?php _e( 'You can enable the theme to show different widget areas in the front page than the rest of the website. If you enable this option, additional widget areas that will only be displayed on the front page will be added to the Widget settings page.', 'graphene' ); ?></p>
                <table class="form-table">       	
                    <tr>
                        <th scope="row" style="width:350px;"><label for="alt_home_sidebar"><?php _e( 'Enable alternate front page sidebar widget area', 'graphene' ); ?></label></th>
                        <td><input type="checkbox" name="graphene_settings[alt_home_sidebar]" id="alt_home_sidebar" <?php checked( $graphene_settings['alt_home_sidebar'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="alt_home_footerwidget"><?php _e( 'Enable alternate front page footer widget area', 'graphene' ); ?></label><br />
                        <small><?php _e( 'You can also specify different column counts for the front page footer widget and the rest-of-site footer widget if you enable this option.', 'graphene' ); ?></small>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[alt_home_footerwidget]" id="alt_home_footerwidget" <?php checked( $graphene_settings['alt_home_footerwidget'] ); ?> value="true" /></td>
                    </tr>
                </table>
            </div>
        </div>
        
        
        <?php /* Top Bar Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Top Bar Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="hide_top_bar"><?php _e( 'Hide the top bar', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[hide_top_bar]" id="hide_top_bar" <?php checked( $graphene_settings['hide_top_bar'] ); ?> value="true" data-toggleOptions="true" /></td>
                    </tr>
                </table>
                <table class="form-table social-media-table<?php if ( $graphene_settings['hide_top_bar'] == true ) echo ' hide'; ?>">
                    <tbody>
                    <tr>
                        <th scope="row"><label for="hide_feed_icon"><?php _e( 'Hide feed icon', 'graphene' ); ?></label></th>
                        <td><input type="checkbox" name="graphene_settings[hide_feed_icon]" id="hide_feed_icon" <?php checked( $graphene_settings['hide_feed_icon'] ); ?> value="true" /></td>                                    
                    </tr> 	
                    <tr>
                        <th scope="row"><label for="custom_feed_url"><?php _e( 'Use custom feed URL', 'graphene' ); ?></label></th>
                        <td>
                            <input type="text" name="graphene_settings[custom_feed_url]" id="custom_feed_url" value="<?php echo $graphene_settings['custom_feed_url']; ?>" size="60" class="widefat code" /><br />
                            <span class="description"><?php _e( 'This custom feed URL will replace the default WordPress RSS feed URL.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="social_media_new_window"><?php _e( 'Open social media links in new window', 'graphene' ); ?></label></th>
                        <td><input type="checkbox" name="graphene_settings[social_media_new_window]" id="social_media_new_window" <?php checked( $graphene_settings['social_media_new_window'] ); ?> value="true" /></td>                                    
                    </tr> 
                    <tr>
                        <th scope="row"><label for="twitter_url"><?php _e( 'Twitter URL', 'graphene' ); ?></label></th>
                        <td>
                            <input type="text" name="graphene_settings[twitter_url]" id="twitter_url" value="<?php echo $graphene_settings['twitter_url']; ?>" size="60" class="widefat code" /><br />
                            <span class="description"><?php _e( 'Enter the URL to your Twitter page.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="facebook_url"><?php _e( 'Facebook URL', 'graphene' ); ?></label></th>
                        <td>
                            <input type="text" name="graphene_settings[facebook_url]" id="facebook_url" value="<?php echo $graphene_settings['facebook_url']; ?>" size="60" class="widefat code" /><br />
                            <span class="description"><?php _e( 'Enter the URL to your Facebook profile page.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="youtube_url"><?php _e( 'Youtube URL', 'graphene' ); ?></label></th>
                        <td>
                            <input type="text" name="graphene_settings[youtube_url]" id="youtube_url" value="<?php echo $graphene_settings['youtube_url']; ?>" size="60" class="widefat code" /><br />
                            <span class="description"><?php _e( 'Enter the URL to your Youtube channel page.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="linkedin_url"><?php _e( 'LinkedIn URL', 'graphene' ); ?></label></th>
                        <td>
                            <input type="text" name="graphene_settings[linkedin_url]" id="linkedin_url" value="<?php echo $graphene_settings['linkedin_url']; ?>" size="60" class="widefat code" /><br />
                            <span class="description"><?php _e( 'Enter the URL to your LinkedIn profile page.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="yahoo_url"><?php _e( 'Yahoo URL', 'graphene' ); ?></label></th>
                        <td>
                            <input type="text" name="graphene_settings[yahoo_url]" id="yahoo_url" value="<?php echo $graphene_settings['yahoo_url']; ?>" size="60" class="widefat code" /><br />
                            <span class="description"><?php _e( 'Enter the URL to your Yahoo profile page.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="flickr_url"><?php _e( 'Flickr URL', 'graphene' ); ?></label></th>
                        <td>
                            <input type="text" name="graphene_settings[flickr_url]" id="flickr_url" value="<?php echo $graphene_settings['flickr_url']; ?>" size="60" class="widefat code" /><br />
                            <span class="description"><?php _e( 'Enter the URL to your Flickr profile page.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="vevo_url"><?php _e( 'Vevo URL', 'graphene' ); ?></label></th>
                        <td>
                            <input type="text" name="graphene_settings[vevo_url]" id="vevo_url" value="<?php echo $graphene_settings['vevo_url']; ?>" size="60" class="widefat code" /><br />
                            <span class="description"><?php _e( 'Enter the URL to your Vevo profile page.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="bing_url"><?php _e( 'Bing URL', 'graphene' ); ?></label></th>
                        <td>
                            <input type="text" name="graphene_settings[bing_url]" id="bing_url" value="<?php echo $graphene_settings['bing_url']; ?>" size="60" class="widefat code" /><br />
                            <span class="description"><?php _e( 'Enter the URL to your Bing profile page.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="su_url"><?php _e( 'Stumbleupon URL', 'graphene' ); ?></label></th>
                        <td>
                            <input type="text" name="graphene_settings[su_url]" id="su_url" value="<?php echo $graphene_settings['su_url']; ?>" size="60" class="widefat code" /><br />
                            <span class="description"><?php _e( 'Enter the URL to your Stumbleupon profile page.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    
                    <?php /* Loop through the registered social media */
					$social_media = $graphene_settings['social_media'];
					// disect_it( $social_media);
					if ( ! empty( $social_media) ) : 
						foreach ( $social_media as $slug => $social_medium) : ?>
                            <tr class="<?php echo $slug.'-opt '; echo $slug.'-title'; ?> social-media-custom social-media-custom-name">
                            	<?php /* translators: %s will be replaced by the social media service name. Example: LinkedIn Title */ ?>
                                <th scope="row"><label for="social_media_<?php echo $slug; ?>_title"><?php printf( __( '%s Title', 'graphene' ), $social_medium['name'] ); ?></label></th>
                                <td>
                                    <input type="text" name="graphene_settings[social_media][<?php echo $slug; ?>][title]" id="social_media_<?php echo $slug; ?>_title" value="<?php echo $social_medium['title']; ?>" size="60" class="widefat code" />
                                    <?php if ( !( isset( $social_medium['title'] ) && ! empty( $social_medium['title'] ) ) ) : ?>
                                    <br />
                                    <span class="description"><?php printf( __( 'The title is empty and the following title will be shown: <strong>%s</strong>', 'graphene' ), graphene_determine_social_medium_title( $social_medium, false ) ); ?></span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr class="<?php echo $slug.'-opt '; echo $slug.'-url'; ?> social-media-custom">
                            	<?php /* translators: %s will be replaced by the social media service name. Example: LinkedIn URL */ ?>
                                <th scope="row"><label for="social_media_<?php echo $slug; ?>_url"><?php printf( __( '%s URL', 'graphene' ), $social_medium['name'] ); ?></label></th>
                                <td><input type="text" name="graphene_settings[social_media][<?php echo $slug; ?>][url]" id="social_media_<?php echo $slug; ?>_url" value="<?php echo $social_medium['url']; ?>" size="60" class="widefat code" /></td>
                            </tr>                            
                            <tr class="<?php echo $slug.'-opt '; echo $slug.'-icon'; ?> social-media-custom">
                            	<?php /* translators: %s will be replaced by the social media service name. Example: LinkedIn icon URL */ ?>
                                <th scope="row"><label for="social_media_<?php echo $slug; ?>_icon"><?php printf( __( '%s icon URL', 'graphene' ), $social_medium['name'] ); ?></label></th>
                                <td><input type="text" name="graphene_settings[social_media][<?php echo $slug; ?>][icon]" id="social_media_<?php echo $slug; ?>_icon" value="<?php echo $social_medium['icon']; ?>" size="60" class="widefat code" />
                                    <input type="hidden" name="graphene_settings[social_media][<?php echo $slug; ?>][name]" value="<?php echo $social_medium['name']; ?>" />
                                    <span class="delete"><a href="#" id="<?php echo $slug.'-del'; ?>" class="social-media-del"><?php _e( 'Delete', 'graphene' ); ?></a></span>
                                </td>
                            </tr>
					<?php endforeach; endif; ?>
                            
                    </tbody>
                    <tfoot>
                        <tr>
                            <td><a href="#" id="social-media-new"><?php _e( 'Add new social media icon', 'graphene' ); ?></a></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        
        
        <?php /* Social Sharing Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Social Sharing Buttons', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="show_addthis"><?php _e( 'Show social sharing button', 'graphene' ); ?></label></th>
                        <td><input type="checkbox" name="graphene_settings[show_addthis]" id="show_addthis" <?php checked( $graphene_settings['show_addthis'] ); ?> value="true" data-toggleOptions="true" /></td>
                    </tr>

                </table>
                <table class="form-table<?php if ( $graphene_settings['show_addthis'] != true ) echo ' hide'; ?>">                    
                    <tr>
                        <th scope="row"><label for="show_addthis_page"><?php _e( 'Show in Pages as well?', 'graphene' ); ?></label></th>
                        <td><input type="checkbox" name="graphene_settings[show_addthis_page]" id="show_addthis_page" <?php checked( $graphene_settings['show_addthis_page'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="addthis_location"><?php _e( 'Social sharing buttons location', 'graphene' ); ?></label></th>
                        <td>
                        	<select name="graphene_settings[addthis_location]" id="addthis_location">
                        		<option value="post-bottom" <?php selected( $graphene_settings['addthis_location'], 'post-bottom' ); ?>><?php _e( 'Bottom of posts', 'graphene' ); ?></option>
                                <option value="post-top" <?php selected( $graphene_settings['addthis_location'], 'post-top' ); ?>><?php _e( 'Top of posts', 'graphene' ); ?></option>
                                <option value="top-bottom" <?php selected( $graphene_settings['addthis_location'], 'top-bottom' ); ?>><?php _e( 'Both top and bottom', 'graphene' ); ?></option>
                        	</select>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="addthis_code"><?php _e("Your social sharing button code", 'graphene' ); ?></label><br />
                            <small><?php _e( 'You can use codes from any popular social sharing sites, like Facebook, Digg, AddThis, etc.', 'graphene' ); ?></small>
                        </th>
                        <td><textarea name="graphene_settings[addthis_code]" id="addthis_code" cols="60" rows="7" class="widefat code"><?php echo htmlentities(stripslashes( $graphene_settings['addthis_code'] ) ); ?></textarea><br />
                        	<span class="description"><?php _e("You may use these tags to get the post's URL and title:", 'graphene' ); ?> <code>[#post-url]</code>, <code>[#post-title]</code></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div> 
        
        <?php /* Header Ads Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Header Ads Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
            	<table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="show_headerads"><?php _e( 'Show Ads', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[show_headerads]" id="show_headerads" <?php checked( $graphene_settings['show_headerads'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="headerads_code"><?php _e("Your Ads code", 'graphene' ); ?></label>
                        </th>
                        <td>
                        <textarea name="graphene_settings[headerads_code]" id="headerads_code" cols="60" rows="10" class="widefat code"><?php echo htmlentities(stripslashes( $graphene_settings['headerads_code'] ) ); ?></textarea><br />
                        <span class="description"><?php _e( 'Ads must have 468x60.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="position_headerads"><?php _e( 'Ads Position', 'graphene' ); ?></label>
                        </th>
                        <td>
                        	Right : <input type="text" name="graphene_settings[rightposition_headerads]" id="rightposition_headerads" value="<?php echo $graphene_settings['rightposition_headerads']; ?>" />px<br />
                            Top : <input type="text" name="graphene_settings[topposition_headerads]" id="topposition_headerads" value="<?php echo $graphene_settings['topposition_headerads']; ?>" />px
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <?php /* Ads after Menu/Header Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Ads after Menu/Header Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
            	<table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="show_headerafterads"><?php _e('Show Ads', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[show_headerafterads]" id="show_headerafterads" <?php checked( $graphene_settings['show_headerafterads'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="headerafterads_code"><?php _e("Your Ads code", 'graphene' ); ?></label>
                        </th>
                        <td>
                        <textarea name="graphene_settings[headerafterads_code]" id="headerafterads_code" cols="60" rows="10" class="widefat code"><?php echo htmlentities(stripslashes( $graphene_settings['headerafterads_code'] ) ); ?></textarea><br />
                        <span class="description"><?php _e( 'Ads must have 728x15 or 728x90.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <?php /* Google Ads Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Google Ads Single Post Page Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
            	<table class="form-table">
                	<tr>
                        <th scope="row">
                            <label for="show_singleadsense"><?php _e( 'Show Ads advertising', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[show_singleadsense]" id="show_singleadsense" <?php checked( $graphene_settings['show_singleadsense'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="width_adsense"><?php _e( 'Width', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" name="graphene_settings[width_adsense]" id="width_adsense" value="<?php echo htmlentities(stripslashes( $graphene_settings['width_adsense'] ) ); ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="height_adsense"><?php _e( 'Height', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" name="graphene_settings[height_adsense]" id="height_adsense" value="<?php echo htmlentities(stripslashes( $graphene_settings['height_adsense'] ) ); ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="singleadsense_code"><?php _e("Your Ads code", 'graphene' ); ?></label>
                        </th>
                        <td><textarea name="graphene_settings[singleadsense_code]" id="singleadsense_code" cols="60" rows="10" class="widefat code"><?php echo htmlentities(stripslashes( $graphene_settings['singleadsense_code'] ) ); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="position_adsense"><?php _e( 'Ads Position', 'graphene' ); ?></label>
                        </th>
                        <td>
                        Top
                        <input type="checkbox" name="graphene_settings[topposition_adsense]" id="position_adsense" <?php checked( $graphene_settings['topposition_adsense'] ); ?> value="true" />
                        Bottom
                        <input type="checkbox" name="graphene_settings[bottomposition_adsense]" id="position_adsense" <?php checked( $graphene_settings['bottomposition_adsense'] ); ?> value="true" />
                        </td>
                    </tr>
                </table>
            </div>
        </div> 
        
        
        <?php /* AdSense Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Ads Options For Home Posts', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="show_adsense"><?php _e( 'Show Ads advertising', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[show_adsense]" id="show_adsense" <?php checked( $graphene_settings['show_adsense'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="adsense_show_frontpage"><?php _e( 'Show ads on front page as well', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[adsense_show_frontpage]" id="adsense_show_frontpage" <?php checked( $graphene_settings['adsense_show_frontpage'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="adsense_code"><?php _e("Your Ads code", 'graphene' ); ?></label>
                        </th>
                        <td><textarea name="graphene_settings[adsense_code]" id="adsense_code" cols="60" rows="10" class="widefat code"><?php echo htmlentities(stripslashes( $graphene_settings['adsense_code'] ) ); ?></textarea></td>
                    </tr>
                </table>
            </div>
        </div>
        
        
        <?php /* Google Analytics Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Google Analytics Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="show_ga"><?php _e( 'Enable Google Analytics tracking', 'graphene' ); ?></label></th>
                        <td><input type="checkbox" name="graphene_settings[show_ga]" id="show_ga" <?php checked( $graphene_settings['show_ga'] ); ?> value="true" data-toggleOptions="true" /></td>
                    </tr>
                </table>                
                <table class="form-table<?php if ( $graphene_settings['show_ga'] == false ) echo ' hide'; ?>">      
                    <tr>
                        <td colspan="2">
                            <p><?php _e( '<strong>Note:</strong> the theme now places the Google Analytics script in the <code>&lt;head&gt;</code> element to better support the new asynchronous Google Analytics script. Please make sure you update your script to use the new asynchronous script from Google Analytics.', 'graphene' ); ?></p>
                        </td>
                    </tr>                    
                    <tr>
                        <th scope="row"><label for="ga_code"><?php _e("Google Analytics tracking code", 'graphene' ); ?></label><br />
                        <small><?php _e( 'Make sure you include the full tracking code (including the <code>&lt;script&gt;</code> and <code>&lt;/script&gt;</code> tags) and not just the <code>UA-#######-#</code> code.','graphene' ); ?></small>
                        </th>
                        <td><textarea name="graphene_settings[ga_code]" id="ga_code" cols="60" rows="7" class="widefat code"><?php echo htmlentities(stripslashes( $graphene_settings['ga_code'] ) ); ?></textarea></td>
                    </tr>
                </table>
            </div>
        </div>
        
        
        <?php /* Footer Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Footer Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">       	
                    <tr>
                        <th scope="row"><label for="show_cc"><?php _e( 'Show Creative Commons logo', 'graphene' ); ?></label><br />
                        <span class="cc-logo">&nbsp;</span>
                        <td><input type="checkbox" name="graphene_settings[show_cc]" id="show_cc" <?php checked( $graphene_settings['show_cc'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="copy_text"><?php _e("Copyright text (html allowed)", 'graphene' ); ?></label>
                        <br /><small><?php _e( 'If this field is empty, the following default copyright text will be displayed:', 'graphene' ); ?></small>
                        <p style="background-color:#fff;padding:5px;border:1px solid #ddd;"><small><?php _e( 'All right reserved ©2011 ','graphene' ); ?></small></p>
                        </th>
                        <td><textarea name="graphene_settings[copy_text]" id="copy_text" cols="60" rows="7"><?php echo stripslashes( $graphene_settings['copy_text'] ); ?></textarea></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="hide_copyright"><?php _e( 'Do not show copyright info', 'graphene' ); ?></label></th>
                        <td><input type="checkbox" name="graphene_settings[hide_copyright]" id="hide_copyright" <?php checked( $graphene_settings['hide_copyright'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="hide_return_top"><?php _e( 'Do not show the "Return to top" link', 'graphene' ); ?></label></th>
                        <td><input type="checkbox" name="graphene_settings[hide_return_top]" id="hide_return_top" <?php checked( $graphene_settings['hide_return_top'] ); ?> value="true" /></td>
                    </tr>
                </table>
            </div>
        </div> 
        
        <?php /* Forum and Helpdesk Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Forum and Helpdesk Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="forum_link"><?php _e( 'Forum Url', 'graphene' ); ?></label></th>
                        <td>
                        <input type="text" name="graphene_settings[forum_link]" id="forum_link" value="<?php echo $graphene_settings['forum_link']; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="helpdesk_link"><?php _e( 'HelpDesk Url', 'graphene' ); ?></label></th>
                        <td>
                        <input type="text" name="graphene_settings[helpdesk_link]" id="helpdesk_link" value="<?php echo $graphene_settings['helpdesk_link']; ?>" />
                        </td>
                    </tr>
                </table>
            </div>
        </div>  
        
        
        <?php /* Print Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Print Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="print_css"><?php _e( 'Enable print CSS for single posts and pages?', 'graphene' ); ?></label></th>
                        <td><input type="checkbox" name="graphene_settings[print_css]" id="print_css" <?php checked( $graphene_settings['print_css'] ); ?> value="true" data-toggleOptions="true" /></td>
                    </tr>
                </table>
                <table class="form-table<?php if ( $graphene_settings['print_css'] != true ) echo ' hide'; ?>">                    
                    <tr>
                        <th scope="row"><label for="print_button"><?php _e("Show print button as well?", 'graphene' ); ?></label></th>
                        <td><input type="checkbox" name="graphene_settings[print_button]" id="print_button" <?php checked( $graphene_settings['print_button'] ); ?> value="true" /></td>                        
                    </tr>
                </table>
            </div>
        </div>  

<?php } // Closes the graphene_options_general() function definition

function graphene_options_display() { 
    
    global $graphene_settings;
    ?>
        
    <input type="hidden" name="graphene_display" value="true" />
        
        <?php /* Header Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Header Display Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="light_header"><?php _e( 'Use light-coloured header bars', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[light_header]" id="light_header" <?php checked( $graphene_settings['light_header'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="link_header_img"><?php _e( 'Link header image to front page', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[link_header_img]" id="link_header_img" <?php checked( $graphene_settings['link_header_img'] ); ?> value="true" /><br />
                            <span class="description"><?php _e( 'Check this if you disable the header texts and want the header image to be linked to the front page.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="featured_img_header"><?php _e( 'Disable Featured Image replacing header image', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[featured_img_header]" id="featured_img_header" <?php checked( $graphene_settings['featured_img_header'] ); ?> value="true" /><br />
                            <span class="description"><?php _e( 'Check this to prevent the posts Featured Image replacing the header image regardless of the featured image dimension.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="use_random_header_img"><?php _e( 'Use random header image', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[use_random_header_img]" id="use_random_header_img" <?php checked( $graphene_settings['use_random_header_img'] ); ?> value="true" /><br />
                            <span class="description">
								<?php _e( 'Check this to show a random header image (random image taken from the available default header images).', 'graphene' ); ?><br />
                                <?php _e( '<strong>Note:</strong> only works on pages where a specific header image is not defined.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="search_box_location"><?php _e( 'Search box location', 'graphene' ); ?></label>
                        </th>
                        <td>
                            <select name="graphene_settings[search_box_location]" id="search_box_location">
                                <option value="top_bar" <?php selected( $graphene_settings['search_box_location'], 'top_bar' ); ?>><?php _e("Top bar", 'graphene' ); ?></option>
                                <option value="nav_bar" <?php selected( $graphene_settings['search_box_location'], 'nav_bar' ); ?>><?php _e("Navigation bar", 'graphene' ); ?></option>
                                <option value="disabled" <?php selected( $graphene_settings['search_box_location'], 'disabled' ); ?>><?php _e("Disable search box", 'graphene' ); ?></option>             
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="header_color"><?php _e( 'Header Color', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="text" class="code color-1" name="graphene_settings[header_color]" id="header_color" value="<?php echo $graphene_settings['header_color']; ?>" />
                        <div class="colorpicker" id="colorpicker-1"></div>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="disable_header"><?php _e( 'Disable Header', 'graphene' ); ?></label>
                        </th>
                        <td>
                        	<input type="checkbox" name="graphene_settings[disable_header]" id="disable_header" <?php checked( $graphene_settings['disable_header'] ); ?> value="true" />
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label><?php _e( 'Round Corners', 'graphene' ); ?></label>
                        </th>
                        <td>
                        	Top Left :<input type="text" name="graphene_settings[round_corners_topleft]" id="round_corners_topleft" value="<?php echo $graphene_settings['round_corners_topleft']; ?>" />px<br />
                            Top Right :<input type="text" name="graphene_settings[round_corners_topright]" id="round_corners_topright" value="<?php echo $graphene_settings['round_corners_topright']; ?>" />px<br />
                            Bottom Left :<input type="text" name="graphene_settings[round_corners_bottomleft]" id="round_corners_bottomleft" value="<?php echo $graphene_settings['round_corners_bottomleft']; ?>" />px<br />
                            Bottom Right :<input type="text" name="graphene_settings[round_corners_bottomright]" id="round_corners_bottomright" value="<?php echo $graphene_settings['round_corners_bottomright']; ?>" />px
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <?php /* Header Title and Logo options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Header Title and Logo options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
            	<table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="enable_header_widget"><?php _e( 'Enable header widget area', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="checkbox" id="enable_header_widget" name="graphene_settings[enable_header_widget]" <?php checked( $graphene_settings['enable_header_widget'] ); ?> value="true">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="logomargin_left"><?php _e( 'Margin Left', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="text" name="graphene_settings[logomargin_left]" id="logomargin_left" value="<?php echo $graphene_settings['logomargin_left']; ?>" />px
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="logomargin_top"><?php _e( 'Margin Top', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="text" name="graphene_settings[logomargin_top]" id="logomargin_top" value="<?php echo $graphene_settings['logomargin_top']; ?>" />px
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        
        <?php /* Menu Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Menu Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
            	<table class="form-table">
                	<tr>
                        <th scope="row">
                            <label><?php _e( 'Disable Menu', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="checkbox" id="menu_disable" name="graphene_settings[menu_disable]" <?php checked( $graphene_settings['menu_disable'] ); ?> value="true">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php _e( 'Menu Position', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="radio" name="graphene_settings[menu_position]" <?php if($graphene_settings['menu_position'] == 'top') echo 'checked="checked"'; ?> value="top" />Top
                        <input type="radio" name="graphene_settings[menu_position]" <?php if($graphene_settings['menu_position'] == 'bottom') echo 'checked="checked"'; ?> value="bottom" />Bottom
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php _e( 'Round Corners', 'graphene' ); ?></label>
                        </th>
                        <td>
                        	Top Left :<input type="text" name="graphene_settings[menuround_corners_topleft]" id="menuround_corners_topleft" value="<?php echo $graphene_settings['menuround_corners_topleft']; ?>" />px<br />
                            Top Right :<input type="text" name="graphene_settings[menuround_corners_topright]" id="menuround_corners_topright" value="<?php echo $graphene_settings['menuround_corners_topright']; ?>" />px<br />
                            Bottom Left :<input type="text" name="graphene_settings[menuround_corners_bottomleft]" id="menuround_corners_bottomleft" value="<?php echo $graphene_settings['menuround_corners_bottomleft']; ?>" />px<br />
                            Bottom Right :<input type="text" name="graphene_settings[menuround_corners_bottomright]" id="menuround_corners_bottomright" value="<?php echo $graphene_settings['menuround_corners_bottomright']; ?>" />px
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
        <?php /* Column Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Column Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                    <tr>
                        <th scope="col" style="width:150px;">
                            <label><?php _e( 'Column mode', 'graphene' ); ?></label>
                        </th>
                    </tr>
                    <tr>
                        <td>
                            <div class="column-options">
                            	<p>                           
                                <label>
                                    <input type="radio" name="graphene_settings[column_mode]" value="one-column" <?php checked( $graphene_settings['column_mode'], 'one-column' ); ?>/>
                                    <img src="<?php echo get_template_directory_uri(); ?>/admin/images/template-onecolumn.png" alt="<?php _e( 'One column', 'graphene' ); ?>" title="<?php _e( 'One column', 'graphene' ); ?>" />                                
                                </label>
                                </p>
                                
                                <p>
                                <label>
                                    <input type="radio" name="graphene_settings[column_mode]" value="two-col-left" <?php checked( $graphene_settings['column_mode'], 'two-col-left' ); ?>/>
                                    <img src="<?php echo get_template_directory_uri(); ?>/admin/images/template-twocolumnsleft.png" alt="<?php _e( 'Two columns (with sidebar right)', 'graphene' ); ?>" title="<?php _e( 'Two columns (with sidebar right)', 'graphene' ); ?>" />
                                </label>
                                <label>
                                    <input type="radio" name="graphene_settings[column_mode]" value="two-col-right" <?php checked( $graphene_settings['column_mode'], 'two-col-right' ); ?>/>
                                    <img src="<?php echo get_template_directory_uri(); ?>/admin/images/template-twocolumnsright.png" alt="<?php _e( 'Two columns (with sidebar left)', 'graphene' ); ?>" title="<?php _e( 'Two columns (with sidebar left)', 'graphene' ); ?>" />
                                </label>
                                </p>
                                
                                <p>
                                <label>
                                    <input type="radio" name="graphene_settings[column_mode]" value="three-col-left" <?php checked( $graphene_settings['column_mode'], 'three-col-left' ); ?>/>
                                    <img src="<?php echo get_template_directory_uri(); ?>/admin/images/template-threecolumnsleft.png" alt="<?php _e( 'Three columns (with two sidebars right)', 'graphene' ); ?>" title="<?php _e( 'Three columns (with two sidebars right)', 'graphene' ); ?>" />
                                </label>
                                <label>
                                    <input type="radio" name="graphene_settings[column_mode]" value="three-col-right" <?php checked( $graphene_settings['column_mode'], 'three-col-right' ); ?>/>
                                    <img src="<?php echo get_template_directory_uri(); ?>/admin/images/template-threecolumnsright.png" alt="<?php _e( 'Three columns (with two sidebars left)', 'graphene' ); ?>" title="<?php _e( 'Three columns (with two sidebars left)', 'graphene' ); ?>" />
                                </label>
                                <label>
                                    <input type="radio" name="graphene_settings[column_mode]" value="three-col-center" <?php checked( $graphene_settings['column_mode'], 'three-col-center' ); ?>/>
                                    <img src="<?php echo get_template_directory_uri(); ?>/admin/images/template-threecolumnscenter.png" alt="<?php _e( 'Three columns (with sidebars left and right)', 'graphene' ); ?>" title="<?php _e( 'Three columns (with sidebars left and right)', 'graphene' ); ?>" />
                                </label>      
                                </p>                            
                            </div>                                                                                                              
                        </td>
                    </tr>
                </table>
                <?php /* Resize Options */ ?>
            	<table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="container_size"><?php _e( 'Container Size', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="text" name="graphene_settings[container_size]" value="<?php echo $graphene_settings['container_size']?>" id="container_size" />px<br />
                        <span class="description">
						<?php _e( 'Enter container size before resize content and sidebar. Depending on the type and columns, this can have distance between them.', 'graphene' ); ?>
                        </span> 
                        </td>
                    </tr>
                    <?php if($graphene_settings['column_mode'] == 'two-col-left'): ?>
                    <tr>
                        <th scope="row">
                            <label for="content_size"><?php _e( 'Content Size', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="text" name="graphene_settings[content_size]" value="<?php echo $graphene_settings['content_size']?>" id="content_size" />px
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="leftsidebar_size"><?php _e( 'Sidebar Size', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="text" name="graphene_settings[leftsidebar_size]" value="<?php echo $graphene_settings['leftsidebar_size']?>" id="leftsidebar_size" />px<br />
                        <span class="description">
						<?php _e( 'Padding between content and sidebar is 15px, and padding between sidebar and the margin is 15px', 'graphene' ); ?>
                        </span> 
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if($graphene_settings['column_mode'] == 'two-col-right'): ?>
                    <tr>
                        <th scope="row">
                            <label for="content_size"><?php _e( 'Content Size', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="text" name="graphene_settings[content_size]" value="<?php echo $graphene_settings['content_size']?>" id="content_size" />px
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="rightsidebar_size"><?php _e( 'Sidebar Size', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="text" name="graphene_settings[rightsidebar_size]" value="<?php echo $graphene_settings['rightsidebar_size']?>" id="rightsidebar_size" />px<br />
                        <span class="description">
						<?php _e( 'Padding between content and sidebar is 15px and padding between sidebar and the margin is 15px', 'graphene' ); ?>
                        </span>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <?php if($graphene_settings['column_mode'] == 'three-col-left' || $graphene_settings['column_mode'] == 'three-col-right' || $graphene_settings['column_mode'] == 'three-col-center'): ?>
                    <tr>
                        <th scope="row">
                            <label for="content_size"><?php _e( 'Content Size', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="text" name="graphene_settings[content_size]" value="<?php echo $graphene_settings['content_size']?>" id="content_size" />px<br />
                        <span class="description">
						<?php _e( 'Padding between content and sidebars, between sidebars, and between sidebr and the margin is 15px.', 'graphene' ); ?>
                        </span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="leftsidebar_size"><?php _e( 'Sidebar1 Size', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="text" name="graphene_settings[leftsidebar_size]" value="<?php echo $graphene_settings['leftsidebar_size']?>" id="leftsidebar_size" />px<br>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="rightsidebar_size"><?php _e( 'Sidebar2 Size', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="text" name="graphene_settings[rightsidebar_size]" value="<?php echo $graphene_settings['rightsidebar_size']?>" id="rightsidebar_size" />px
                        </td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
        
        
        <?php /* Posts Display Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Posts Display Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="hide_post_author"><?php _e( 'Hide post author', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[hide_post_author]" id="hide_post_author" <?php checked( $graphene_settings['hide_post_author'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="post_date_display"><?php _e( 'Post date display', 'graphene' ); ?></label>
                        </th>
                        <td>
                            <select name="graphene_settings[post_date_display]" id="post_date_display">
                                <option value="hidden" <?php selected( $graphene_settings['post_date_display'], 'hidden' ); ?>><?php _e( 'Hidden', 'graphene' ); ?></option>
                                <option value="icon_no_year" <?php selected( $graphene_settings['post_date_display'], 'icon_no_year' ); ?>><?php _e( 'As an icon (without the year)', 'graphene' ); ?></option>
                                <option value="icon_plus_year" <?php selected( $graphene_settings['post_date_display'], 'icon_plus_year' ); ?>><?php _e( 'As an icon (including the year)', 'graphene' ); ?></option>
                                <option value="text" <?php selected( $graphene_settings['post_date_display'], 'text' ); ?>><?php _e( 'As inline text', 'graphene' ); ?></option>
                            </select><br />
                            <span class="description"><?php _e( 'Note: displaying date as inline text allows more space for the content area, especially useful for a three-column layout configuration.', 'graphene' ); ?></span>
                        </td>
                    </tr>                    
                    <tr>
                        <th scope="row">
                            <label for="hide_post_cat"><?php _e( 'Hide post categories', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[hide_post_cat]" id="hide_post_cat" <?php checked( $graphene_settings['hide_post_cat'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="hide_post_tags"><?php _e( 'Hide post tags', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[hide_post_tags]" id="hide_post_tags" <?php checked( $graphene_settings['hide_post_tags'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="hide_post_commentcount"><?php _e( 'Hide post comment count', 'graphene' ); ?></label><br />
                            <small><?php _e( 'Only affects posts listing (such as the front page ) and not single post view.', 'graphene' ); ?></small>                        
                        </th>
                        <td><input type="checkbox" name="graphene_settings[hide_post_commentcount]" id="hide_post_commentcount" <?php checked( $graphene_settings['hide_post_commentcount'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="show_post_avatar"><?php _e("Show post author's gravatar", 'graphene' ); ?></label></th>
                        <td><input type="checkbox" name="graphene_settings[show_post_avatar]" id="show_post_avatar" <?php checked( $graphene_settings['show_post_avatar'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="show_post_author"><?php _e("Show post author's info", 'graphene' ); ?></label></th>
                        <td><input type="checkbox" name="graphene_settings[show_post_author]" id="show_post_author" <?php checked( $graphene_settings['show_post_author'] ); ?> value="true" /></td>
                    </tr>
                </table>
            </div>
        </div>
        
        
        <?php /* Excerpts Display Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Excerpts Display Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
            	<table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="posts_show_excerpt"><?php _e( 'Show excerpts in front page', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[posts_show_excerpt]" id="posts_show_excerpt" <?php checked( $graphene_settings['posts_show_excerpt'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="archive_full_content"><?php _e( 'Show full content in archive pages', 'graphene' ); ?></label>
                        </th>
                        <td>
                        	<input type="checkbox" name="graphene_settings[archive_full_content]" id="archive_full_content" <?php checked( $graphene_settings['archive_full_content'] ); ?> value="true" /><br />
                            <span class="description"><?php _e( 'Note: Archive pages include the archive for category, tags, time, and search results pages. Enabling this option will cause the full content of posts and pages listed in those archives to displayed instead of the excerpt, and truncated by the Read More tag if used.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="show_excerpt_more"><?php _e("Show More link for manual excerpts", 'graphene' ); ?></label></th>
                        <td><input type="checkbox" name="graphene_settings[show_excerpt_more]" id="show_excerpt_more" <?php checked( $graphene_settings['show_excerpt_more'] ); ?> value="true" /></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="excerpt_html_tags"><?php _e("Retain these HTML tags in excerpts", 'graphene' ); ?></label></th>
                        <td>
                        	<input type="text" class="widefat code" name="graphene_settings[excerpt_html_tags]" id="excerpt_html_tags" value="<?php echo $graphene_settings['excerpt_html_tags']; ?>" /><br />
                        	<span class="description"><?php _e("Enter the HTML tags you'd like to retain in excerpts. For example, enter <code>&lt;p&gt;&lt;ul&gt;&lt;li&gt;</code> to retain <code>&lt;p&gt;</code>, <code>&lt;ul&gt;</code>, and <code>&lt;li&gt;</code> HTML tags.", 'graphene' ); ?></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
      
        
        
        <?php /* Comments Display Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Comments Display Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="hide_allowedtags"><?php _e( 'Hide allowed tags in comment form', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[hide_allowedtags]" id="hide_allowedtags" <?php checked( $graphene_settings['hide_allowedtags'] ); ?> value="true" /></td>
                    </tr>
                </table>
            </div>
        </div>
        
        
        <?php /* Colours Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Colours Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
            	<p><?php _e("Changing colours for your website involves a lot more than just trial and error. Simply mixing and matching colours without regard to their compatibility may do more damage than good to your site's aesthetics.", 'graphene' ); ?>
				<?php printf( __("It's generally a good idea to stick to colours from colour pallettes that are aesthetically pleasing. Try the %s website for a kickstart on some colour palettes you can use.", 'graphene' ), '<a href="http://www.colourlovers.com/palettes/">COLOURlovers</a>' ); ?></p>
                <p><?php _e("When you've got the perfect combination, you can even share it with fellow Crystal theme users through the <a href=\"http://forum.khairul-syahir.com/\">Support Forum</a>.", 'graphene' ); ?></p>
            	<p><?php _e( '<strong>Note:</strong> The previews work best on modern Gecko- and Webkit-based browsers, such as Mozilla Firefox and Google Chrome.', 'graphene' ); ?></p>
                <p><?php _e( '<strong>Note:</strong> To reset any of the colours to their default value, just click on "Clear" beside the colour field and save the settings. The theme will automatically revert the value to the default colour.', 'graphene' ); ?></p>
            	<h4><?php _e( 'Content area', 'graphene' ); ?></h4>
                <table class="form-table">
                	<?php 
						$colour_opts = array(
							'bg_content_wrapper' => array( 'title' => __( 'Main content wrapper background', 'graphene' ) ),
							'bg_content' => array( 'title' => __( 'Post and pages content background', 'graphene' ) ),
							'bg_meta_border' => array( 'title' => __( 'Post meta and footer border', 'graphene' ) ),
							'bg_post_top_border' => array( 'title' => __( 'Post and pages top border', 'graphene' ) ),
							'bg_post_bottom_border' => array( 'title' => __( 'Post and pages bottom border', 'graphene' ) ),
						);
						
						$counter = 2;
						foreach ( $colour_opts as $key => $colour_opt) :
					?>
                    <tr>
                        <th scope="row"><label for="<?php echo $key; ?>"><?php echo $colour_opt['title']; ?></label></th>
                        <td>
                        	<input type="text" class="code color-<?php echo $counter; ?>" name="graphene_settings[<?php echo $key; ?>]" id="<?php echo $key; ?>" value="<?php echo $graphene_settings[$key]; ?>" />
                            <div class="colorpicker" id="colorpicker-<?php echo $counter; ?>"></div>
                        </td>
                    </tr>
                    <?php $counter++; endforeach; ?>
                </table>
                
                <h4><?php _e( 'Widgets', 'graphene' ); ?></h4>
                <table class="form-table">
                	<tr>
                    	<th scope="row"><?php _e( 'Widget preview', 'graphene' ); ?></th>
                        <td><div class="sidebar graphene"><div class="sidebar-wrap"><h3><?php _e( 'Widget title', 'graphene' ); ?></h3><ul><li><?php _e( 'List item', 'graphene' ); ?> 1</li><li><?php _e( 'List item', 'graphene' ); ?> 2</li><li><a href="#"><?php _e( 'List item', 'graphene' ); ?> 3</a></li></ul></div></div></td>
                    </tr>
                	<?php 
						$colour_opts = array(
							'bg_widget_item' => array( 'title' => __( 'Widget item background', 'graphene' ) ),
							'bg_widget_list' => array( 'title' => __( 'Widget item list border', 'graphene' ) ),
							'bg_widget_header_border' => array( 'title' => __( 'Widget header border', 'graphene' ) ),
							'bg_widget_title' => array( 'title' => __( 'Widget title colour', 'graphene' ) ),
							'bg_widget_title_textshadow' => array( 'title' => __( 'Widget title text shadow colour', 'graphene' ) ),
							'bg_widget_header_bottom' => array( 'title' => __( 'Widget header gradient bottom colour', 'graphene' ) ),
							'bg_widget_header_top' => array( 'title' => __( 'Widget header gradient top colour', 'graphene' ) ),
						);

						foreach ( $colour_opts as $key => $colour_opt) :
					?>
                    <tr>
                        <th scope="row"><label for="<?php echo $key; ?>"><?php echo $colour_opt['title']; ?></label></th>
                        <td>
                        	<input type="text" class="code color-<?php echo $counter.' '.str_replace( '_', '-', $key); ?>" name="graphene_settings[<?php echo $key; ?>]" id="<?php echo $key; ?>" value="<?php echo $graphene_settings[$key]; ?>" />
                            <div class="colorpicker" id="colorpicker-<?php echo $counter; ?>"></div>
                        </td>
                    </tr>
                    <?php $counter++; endforeach; ?>
                    
                </table>
                
                <h4><?php _e( 'Slider', 'graphene' ); ?></h4>
                <table class="form-table">
                	<tr>
                    	<th scope="row"><?php _e( 'Slider background preview', 'graphene' ); ?></th>
                        <td><div id="grad-box"></div></td>
                    </tr>
                	<?php 
						$colour_opts = array(
							'bg_slider_top' => array( 'title' => __( 'Slider top left colour', 'graphene' ) ),
							'bg_slider_bottom' => array( 'title' => __( 'Slider bottom right colour', 'graphene' ) ),
						);

						foreach ( $colour_opts as $key => $colour_opt) :
					?>
                    <tr>
                        <th scope="row"><label for="<?php echo $key; ?>"><?php echo $colour_opt['title']; ?></label></th>
                        <td>
                        	<input type="text" class="code color-<?php echo $counter.' '.str_replace( '_', '-', $key); ?>" name="graphene_settings[<?php echo $key; ?>]" id="<?php echo $key; ?>" value="<?php echo $graphene_settings[$key]; ?>" />
                            <div class="colorpicker" id="colorpicker-<?php echo $counter; ?>"></div>
                        </td>
                    </tr>
                    <?php $counter++; endforeach; ?>
                    
                </table>
                
                <h4><?php _e( 'Block buttons', 'graphene' ); ?></h4>
                <table class="form-table">
                	<tr>
                    	<th scope="row"><?php _e( 'Block button preview', 'graphene' ); ?></th>
                        <td><a class="block-button" href="#"><?php _e( 'Button label', 'graphene' ); ?></a></td>
                    </tr>
                	<?php 
						$colour_opts = array(
							'bg_button' => array( 'title' => __( 'Button background colour', 'graphene' ) ),
							'bg_button_label' => array( 'title' => __( 'Button label colour', 'graphene' ) ),
							'bg_button_label_textshadow' => array( 'title' => __( 'Button label text shadow', 'graphene' ) ),
						);

						foreach ( $colour_opts as $key => $colour_opt) :
					?>
                    <tr>
                        <th scope="row"><label for="<?php echo $key; ?>"><?php echo $colour_opt['title']; ?></label></th>
                        <td>
                        	<input type="text" class="code color-<?php echo $counter.' '.str_replace( '_', '-', $key); ?>" name="graphene_settings[<?php echo $key; ?>]" id="<?php echo $key; ?>" value="<?php echo $graphene_settings[$key]; ?>" />
                            <div class="colorpicker" id="colorpicker-<?php echo $counter; ?>"></div>
                        </td>
                    </tr>
                    <?php $counter++; endforeach; ?>
                    
                </table>
                
                <h4><?php _e( 'Archive title', 'graphene' ); ?></h4>
                <table class="form-table">
                	<tr>
                    	<th scope="row"><?php _e( 'Archive title preview', 'graphene' ); ?></th>
                        <td><div class="archive-title-preview"><span class="page-title"><?php _e( 'Archive title:', 'graphene' ); ?> <span><?php _e( 'Sample title', 'graphene' ); ?></span></span></div></td>
                    </tr>
                	<?php 
						$colour_opts = array(
							'bg_archive_left' => array( 'title' => __( 'Archive background gradient left colour', 'graphene' ) ),
                            'bg_archive_right' => array( 'title' => __( 'Archive background gradient right colour', 'graphene' ) ),
							'bg_archive_label' => array( 'title' => __( 'Archive label colour', 'graphene' ) ),
							'bg_archive_text' => array( 'title' => __( 'Archive text colour', 'graphene' ) ),
                            'bg_archive_textshadow' => array( 'title' => __( 'Archive label and text shadow colour', 'graphene' ) ),
						);

						foreach ( $colour_opts as $key => $colour_opt) :
					?>
                    <tr>
                        <th scope="row"><label for="<?php echo $key; ?>"><?php echo $colour_opt['title']; ?></label></th>
                        <td>
                        	<input type="text" class="code color-<?php echo $counter.' '.str_replace( '_', '-', $key); ?>" name="graphene_settings[<?php echo $key; ?>]" id="<?php echo $key; ?>" value="<?php echo $graphene_settings[$key]; ?>" />
                            <div class="colorpicker" id="colorpicker-<?php echo $counter; ?>"></div>
                        </td>
                    </tr>
                    <?php $counter++; endforeach; ?>
                    
                </table>
            </div>
        </div>
        
         <?php /* Page Border Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Page Border Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <p><?php _e( 'Note that these are CSS properties, so any valid CSS values for each particular property can be used.', 'graphene' ); ?></p>
                <p><?php _e( 'Some example CSS properties values:', 'graphene' ); ?></p>
                <table class="graphene-code-example">
                    <tr>
                        <th scope="row"><?php _e( 'Text colour:', 'graphene' ); ?></th>
                        <td><code>blue</code>, <code>navy</code>, <code>red</code>, <code>#ff0000</code></td>
                    </tr>        
                </table>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="page_border_color"><?php _e( 'Border Color', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="text" class="code color-<?php echo $counter; ?>" name="graphene_settings[page_border_color]" id="page_border_color" value="<?php echo $graphene_settings['page_border_color']; ?>" />
                        <div class="colorpicker" id="colorpicker-<?php echo $counter; $counter++; ?>"></div>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="page_border_size"><?php _e( 'Border Size', 'graphene' ); ?></label>
                        </th>
                        <td>
                        <input type="text" name="graphene_settings[page_border_size]" id="page_border_size" value="<?php echo $graphene_settings['page_border_size']; ?>" />px<br />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label><?php _e( 'Round Corners', 'graphene' ); ?></label>
                        </th>
                        <td>
                        	Top Left :<input type="text" name="graphene_settings[page_round_corners_topleft]" id="page_round_corners_topleft" value="<?php echo $graphene_settings['page_round_corners_topleft']; ?>" />px<br />
                            Top Right :<input type="text" name="graphene_settings[page_round_corners_topright]" id="page_round_corners_topright" value="<?php echo $graphene_settings['page_round_corners_topright']; ?>" />px<br />
                            Bottom Left :<input type="text" name="graphene_settings[page_round_corners_bottomleft]" id="page_round_corners_bottomleft" value="<?php echo $graphene_settings['page_round_corners_bottomleft']; ?>" />px<br />
                            Bottom Right :<input type="text" name="graphene_settings[page_round_corners_bottomright]" id="page_round_corners_bottomright" value="<?php echo $graphene_settings['page_round_corners_bottomright']; ?>" />px
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
            
        <?php /* Text Style Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Text Style Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <p><?php _e( 'Note that these are CSS properties, so any valid CSS values for each particular property can be used.', 'graphene' ); ?></p>
                <p><?php _e( 'Some example CSS properties values:', 'graphene' ); ?></p>
                <table class="graphene-code-example">
                    <tr>
                        <th scope="row"><?php _e( 'Text font:', 'graphene' ); ?></th>
                        <td><code>arial</code>, <code>tahoma</code>, <code>georgia</code>, <code>'Trebuchet MS'</code></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e( 'Text size and line height:', 'graphene' ); ?></th>
                        <td><code>12px</code>, <code>12pt</code>, <code>12em</code></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e( 'Text weight:', 'graphene' ); ?></th>
                        <td><code>normal</code>, <code>bold</code>, <code>100</code>, <code>700</code></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e( 'Text style:', 'graphene' ); ?></th>
                        <td><code>normal</code>, <code>italic</code>, <code>oblique</code></td>
                    </tr>
                    <tr>
                        <th scope="row"><?php _e( 'Text colour:', 'graphene' ); ?></th>
                        <td><code>blue</code>, <code>navy</code>, <code>red</code>, <code>#ff0000</code></td>
                    </tr>        
                </table>
                
                <h4><?php _e( 'Header Text', 'graphene' ); ?></h4>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="header_title_font_type"><?php _e( 'Title text font', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[header_title_font_type]" id="header_title_font_type" value="<?php echo $graphene_settings['header_title_font_type']; ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="header_title_font_size"><?php _e( 'Title text size', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[header_title_font_size]" id="header_title_font_size" value="<?php echo $graphene_settings['header_title_font_size']; ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="header_title_font_weight"><?php _e( 'Title text weight', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[header_title_font_weight]" id="header_title_font_weight" value="<?php echo $graphene_settings['header_title_font_weight']; ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="header_title_font_lineheight"><?php _e( 'Title text line height', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[header_title_font_lineheight]" id="header_title_font_lineheight" value="<?php echo $graphene_settings['header_title_font_lineheight']; ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="header_title_font_style"><?php _e( 'Title text style', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[header_title_font_style]" id="header_title_font_style" value="<?php echo $graphene_settings['header_title_font_style']; ?>" /></td>
                    </tr>
                </table>
                
                <table class="form-table" style="margin-top:30px;">               
                    <tr>
                        <th scope="row">
                            <label for="header_desc_font_type"><?php _e( 'Description text font', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[header_desc_font_type]" id="header_desc_font_type" value="<?php echo $graphene_settings['header_desc_font_type']; ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="header_desc_font_size"><?php _e( 'Description text size', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[header_desc_font_size]" id="header_desc_font_size" value="<?php echo $graphene_settings['header_desc_font_size']; ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="header_desc_font_weight"><?php _e( 'Description text weight', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[header_desc_font_weight]" id="header_desc_font_weight" value="<?php echo $graphene_settings['header_desc_font_weight']; ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="header_desc_font_lineheight"><?php _e( 'Description text line height', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[header_desc_font_lineheight]" id="header_desc_font_lineheight" value="<?php echo $graphene_settings['header_desc_font_lineheight']; ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">

                            <label for="header_desc_font_style"><?php _e( 'Description text style', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[header_desc_font_style]" id="header_desc_font_style" value="<?php echo $graphene_settings['header_desc_font_style']; ?>" /></td>
                    </tr>
                </table>
                
                <h4><?php _e( 'Content Text', 'graphene' ); ?></h4>
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="content_font_type"><?php _e( 'Text font', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[content_font_type]" id="content_font_type" value="<?php echo $graphene_settings['content_font_type']; ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="content_font_size"><?php _e( 'Text size', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[content_font_size]" id="content_font_size" value="<?php echo $graphene_settings['content_font_size']; ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="content_font_lineheight"><?php _e( 'Text line height', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[content_font_lineheight]" id="content_font_lineheight" value="<?php echo $graphene_settings['content_font_lineheight']; ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="content_font_colour"><?php _e( 'Text colour', 'graphene' ); ?></label>
                        </th>
                        <td>
                            <input type="text" class="code color-<?php echo $counter; ?>" name="graphene_settings[content_font_colour]" id="content_font_colour" value="<?php echo $graphene_settings['content_font_colour']; ?>" />
                            <div class="colorpicker" id="colorpicker-<?php echo $counter; $counter++; ?>"></div>
                        </td>
                    </tr>
                </table>
                    
                <h4><?php _e( 'Link Text', 'graphene' ); ?></h4>
                <table class="form-table">
                	<?php 
						$colour_opts = array(
							'link_colour_normal' => array( 'title' => __( 'Link colour (normal state )', 'graphene' ) ),
							'link_colour_visited' => array( 'title' => __( 'Link colour (visited state )', 'graphene' ) ),
							'link_colour_hover' => array( 'title' => __( 'Link colour (hover state )', 'graphene' ) ),
						);

						foreach ( $colour_opts as $key => $colour_opt) :
					?>
                    <tr>
                        <th scope="row"><label for="<?php echo $key; ?>"><?php echo $colour_opt['title']; ?></label></th>
                        <td>
                        	<input type="text" class="code color-<?php echo $counter.' '.str_replace( '_', '-', $key); ?>" name="graphene_settings[<?php echo $key; ?>]" id="<?php echo $key; ?>" value="<?php echo $graphene_settings[$key]; ?>" />
                            <div class="colorpicker" id="colorpicker-<?php echo $counter; ?>"></div>
                        </td>
                    </tr>
                    <?php $counter++; endforeach; ?>

                    <tr>
                        <th scope="row">
                            <label for="link_decoration_normal"><?php _e( 'Text decoration (normal state )', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[link_decoration_normal]" id="link_decoration_normal" value="<?php echo $graphene_settings['link_decoration_normal']; ?>" /></td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="link_decoration_hover"><?php _e( 'Text decoration (hover state )', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[link_decoration_hover]" id="link_decoration_hover" value="<?php echo $graphene_settings['link_decoration_hover']; ?>" /></td>
                    </tr>
                </table>
            </div>
        </div>
		
        
        <?php /* Footer Widget Display Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Footer Widget Display Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">        
                <table class="form-table">
                    <tr>
                        <th scope="row" style="width:260px;">
                            <label for="footerwidget_column"><?php _e( 'Number of columns to display', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[footerwidget_column]" id="footerwidget_column" value="<?php echo $graphene_settings['footerwidget_column']; ?>" maxlength="2" size="3" /></td>
                    </tr>
                    <?php if ( $graphene_settings['alt_home_footerwidget'] ) : ?>
                    <tr>
                        <th scope="row">
                            <label for="alt_footerwidget_column"><?php _e( 'Number of columns to display for front page footer widget', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[alt_footerwidget_column]" id="alt_footerwidget_column" value="<?php echo $graphene_settings['alt_footerwidget_column']; ?>" maxlength="2" size="3" /></td>
                    </tr>
                    <?php endif; ?>
                </table>
            </div>
        </div>
            
        
        <?php /* Navigation Menu Display Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Navigation Menu Display Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="navmenu_child_width"><?php _e( 'Dropdown menu item width', 'graphene' ); ?></label>
                        </th>
                        <td><input type="text" class="code" name="graphene_settings[navmenu_child_width]" id="navmenu_child_width" value="<?php echo $graphene_settings['navmenu_child_width']; ?>" maxlength="3" size="3" /> px</td>
                    </tr>                    
                    <tr>
                        <th scope="row">
                            <label for="disable_menu_desc"><?php _e( 'Disable description in Header Menu', 'graphene' ); ?></label>
                        </th>
                        <td><input type="checkbox" name="graphene_settings[disable_menu_desc]" id="disable_menu_desc" <?php checked( $graphene_settings['disable_menu_desc'] ); ?> value="true" data-toggleOptions="true" /></td>
                    </tr>
                </table>
                <table class="form-table<?php if ( $graphene_settings['disable_menu_desc'] == true ) echo ' hide'; ?>">
                    <tr>
                        <th scope="row">
                            <label for="navmenu_home_desc"><?php _e( 'Description for default menu "Home" item', 'graphene' ); ?></label>
                        </th>
                        <td>
                        	<input type="text" size="60" name="graphene_settings[navmenu_home_desc]" id="navmenu_home_desc" value="<?php echo $graphene_settings['navmenu_home_desc']; ?>" /><br />
                            <span class="description"><?php _e( 'Only required if you need a description in the navigation menu and you are not using a custom menu.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        
            
        <?php /* Miscellaneous Display Options */ ?>
        <div class="postbox">
            <div class="head-wrap">
                <div title="Click to toggle" class="handlediv"><br /></div>
        		<h3 class="hndle"><?php _e( 'Miscellaneous Display Options', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <h4><?php _e( 'Site title options', 'graphene' ); ?></h4>
                <p><?php _e( 'Use these tags to customise your own site title structure: <code>#site-name</code>, <code>#site-desc</code>, <code>#post-title</code>', 'graphene' ); ?></p>
                <table class="form-table">
                	<tr>
                        <th scope="row" style="width:250px;">
                        	<label for="custom_site_title_frontpage"><?php _e("Custom front page site title", 'graphene' ); ?></label>
                        </th>
                        <td>
                        	<input type="text" name="graphene_settings[custom_site_title_frontpage]" id="custom_site_title_frontpage" class="widefat code" value="<?php echo stripslashes( $graphene_settings['custom_site_title_frontpage'] ); ?>" />
                            <span class="description"><?php _e( 'Defaults to <code>#site-name &raquo; #site-desc</code>. The <code>#post-title</code> tag cannot be used here.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row" style="width:250px;">
                        	<label for="custom_site_title_content"><?php _e("Custom content pages site title", 'graphene' ); ?></label>
                        </th>
                        <td>
                        	<input type="text" name="graphene_settings[custom_site_title_content]" id="custom_site_title_content" class="widefat code" value="<?php echo stripslashes( $graphene_settings['custom_site_title_content'] ); ?>" />
                            <span class="description"><?php _e( 'Defaults to <code>#post-title &raquo; #site-name</code>.', 'graphene' ); ?></span>
                        </td>
                    </tr>
                </table>
                
                <h4><?php _e( 'Favicon options', 'graphene' ); ?></h4>
                <table class="form-table">
                    <tr>
                        <th scope="row" style="width:250px;">
                        	<label for="favicon_url"><?php _e( 'Favicon URL', 'graphene' ); ?></label>
                        </th>
                        <td>
                        	<input type="text" class="widefat code" value="<?php echo $graphene_settings['favicon_url']; ?>" name="graphene_settings[favicon_url]" id="favicon_url" />
                                <span class="description"><a href="#" class="upload_image_button"><?php _e( 'Upload or select image from gallery', 'graphene' );?></a> - <?php _e( 'Simply enter the full URL to your favicon file here to enable favicon. Make sure you include the <code>http://</code> in front of the URL as well. Or use the WordPress media uploader to upload an image, or select one from the media library.', 'graphene' ); ?></span>                                
                        </td>
                    </tr>
                </table>
            </div>
        </div>
                    
                    
        <?php /* Custom CSS */ ?>
        <div class="postbox">
            <div class="head-wrap">
            	<div title="Click to toggle" class="handlediv"><br /></div>
            	<h3 class="hndle"><?php _e( 'Custom CSS', 'graphene' ); ?></h3>
            </div>
            <div class="panel-wrap inside">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="custom_css"><?php _e( 'Custom CSS styles', 'graphene' ); ?></label></th>
                        <td>
                        	<span class="description"><?php _e("You can enter your own CSS codes below to modify any other aspects of the theme's appearance that is not included in the options.", 'graphene' ); ?></span>
                        	<textarea name="graphene_settings[custom_css]" id="custom_css" cols="60" rows="20" class="widefat code"><?php echo stripslashes( $graphene_settings['custom_css'] ); ?></textarea>
                        </td>
                    </tr>
                </table>
            </div>
        </div>                  
        
<?php } // Closes the graphene_options_display() function definition 

function graphene_options_advanced() { 
    global $graphene_settings;
    ?>
        
    <input type="hidden" name="graphene_advanced" value="true" />    
    
    <?php /* Site Preview */ ?>
    <div class="postbox">
        <div class="head-wrap">
            <div title="Click to toggle" class="handlediv"><br /></div>
            <h3 class="hndle"><?php _e( 'Preview', 'graphene' ); ?></h3>
        </div>
        <div class="panel-wrap inside">
            <table class="form-table">
                <tr>
                    <td>
                        <input type="checkbox" name="graphene_settings[enable_preview]" id="enable_preview" <?php checked( $graphene_settings['enable_preview'] ); ?> value="true" />
                        <label for="enable_preview"><?php _e( 'Enable preview of your site on the Crystal Theme Options page', 'graphene' ); ?></label>
                    </td>
                </tr>
            </table>
        </div>
    </div>  
    
    
    <?php /* Action hooks widgets areas */ ?>
    <div class="postbox">
        <div class="head-wrap">
            <div title="Click to toggle" class="handlediv"><br /></div>
            <h3 class="hndle"><?php _e( 'Action Hooks Widget Areas', 'graphene' ); ?></h3>
        </div>
        <div class="panel-wrap inside">
        	<p><?php _e("This option enables you to place virtually any content to every nook and cranny in the theme, by attaching widget areas to the theme's action hooks.", 'graphene' ); ?></p>
            <p><?php _e("All action hooks available in the Crystal Theme are listed below. Click on the filename to display all the action hooks available in that file. Then, tick the checkbox next to an action hook to make a widget area available for that action hook.", 'graphene' ); ?></p>
            
            <ul class="graphene-action-hooks">    
                <?php                
                $actionhooks = graphene_get_action_hooks();
                foreach ( $actionhooks as $actionhook) : 
                    $file = $actionhook['file']; 
                ?>
                    <li>
                        <p class="hooks-file"><a href="#" class="toggle-widget-hooks" title="<?php _e( 'Click to show/hide the action hooks for this file', 'graphene' ); ?>"><?php echo $file; ?></a></p>
                        <ul class="hooks-list">
                            <li class="widget-hooks<?php if(count(array_intersect( $actionhook['hooks'], $graphene_settings['widget_hooks'] ) ) == 0) echo ' hide'; ?>">
                    <?php foreach ( $actionhook['hooks'] as $hook) : ?>
                                <input type="checkbox" name="graphene_settings[widget_hooks][]" value="<?php echo $hook; ?>" id="hook_<?php echo $hook; ?>" <?php if ( in_array( $hook, $graphene_settings['widget_hooks'] ) ) echo 'checked="checked"'; ?> /> <label for="hook_<?php echo $hook; ?>"><?php echo $hook; ?></label><br />
                    <?php endforeach; ?>
                            </li>
                        </ul>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    
<?php } // Closes the graphene_options_advanced() function definition


function graphene_import_form(){            
    
    $bytes = apply_filters( 'import_upload_size_limit', wp_max_upload_size() );
    $size = wp_convert_bytes_to_hr( $bytes );
    $upload_dir = wp_upload_dir();
    if ( ! empty( $upload_dir['error'] ) ) :
        ?><div class="error"><p><?php _e( 'Before you can upload your import file, you will need to fix the following error:', 'graphene' ); ?></p>
            <p><strong><?php echo $upload_dir['error']; ?></strong></p></div><?php
    else :
    ?>
    <div class="wrap">
        <div id="icon-tools" class="icon32"><br></div>
        <h2><?php echo __( 'Import Crystal Theme Options', 'graphene' );?></h2>    
        <form enctype="multipart/form-data" id="import-upload-form" method="post" action="">
        	<p><?php _e( '<strong>Note:</strong> This is an experimental feature. Please report any problem at the <a href="http://forum.khairul-syahir.com/forum/bug-report">Support Forum</a>.', 'graphene' ); ?></p>
            <p>
                <label for="upload"><?php _e( 'Choose a file from your computer:', 'graphene' ); ?></label> (<?php printf( __( 'Maximum size: %s', 'graphene' ), $size ); ?>)
                <input type="file" id="upload" name="import" size="25" />
                <input type="hidden" name="action" value="save" />
                <input type="hidden" name="max_file_size" value="<?php echo $bytes; ?>" />
                <?php wp_nonce_field( 'graphene-import', 'graphene-import' ); ?>
                <input type="hidden" name="graphene_import_confirmed" value="true" />
            </p>
            <input type="submit" class="button" value="<?php _e( 'Upload file and import', 'graphene' ); ?>" />            
        </form>
    </div> <!-- end wrap -->
    <?php
    endif;
} // Closes the graphene_import_form() function definition 

include( 'options-import.php' );
?>