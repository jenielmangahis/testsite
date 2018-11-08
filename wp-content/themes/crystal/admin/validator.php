<?php
/**
 * Settings Validator
 * 
 * This file defines the function that validates the theme's options
 * upon submission.
*/
function graphene_settings_validator( $input ){
	
	if (!isset($_POST['graphene_uninstall'])) {
		global $graphene_defaults, $allowedposttags, $wpdb,$graphene_settings;
		
		// Add <script> tag to the allowed tags in code
		$allowedposttags = array_merge( $allowedposttags, array( 'script' => array( 'type' => array(), 'src' => array() ) ) );
		
		if (isset($_POST['graphene_general'])) {
			
			/* Menu Exclude Pages */
			if ( empty($input['menuexclude_pages']) ) $input['menuexclude_pages'] = $graphene_defaults['menuexclude_pages'];
			
			/* Categories Exclude */
			if ( empty($input['sidebar_exclude_categories']) ) $input['sidebar_exclude_categories'] = $graphene_defaults['sidebar_exclude_categories'];
			
			/* Create Page Options */
			
			$domain = $_SERVER['SERVER_NAME'];
			$domain = str_replace('www','',$domain);
			
			/* Contact Page Options */
			if(isset($input['contact_page']) && !is_plugin_active('contact-form-7/wp-contact-form-7.php')) {
				unset($input['contact_page']);
				add_settings_error('graphene_options', 2, __('ERROR: You need to install and activate "Contact Form 7" plugin.', 'graphene'));
			}elseif(isset($input['contact_page'])){
				$input['contact_page'] = true;
				
				$the_page_title = 'Contact Us';
    			$the_page_name = 'contact-us';
				
				$the_page = get_page_by_title( $the_page_title );
				
				if ( ! $the_page ) {
						
					$posttitle = 'Contact form 1';
					$postid = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_title = '" . $posttitle . "'" );
					
					if(empty($postid)){
						$_p = array();
						$_p['post_title'] = 'Contact form 1';
						$_p['post_content'] = '';
						$_p['post_status'] = 'publish';
						$_p['post_type'] = 'wpcf7_contact_form';
						$_p['comment_status'] = 'closed';
						$_p['ping_status'] = 'closed';
						
						$postid = wp_insert_post( $_p );
						
						add_post_meta($postid, 'form', '<p>Your Name (required)<br />
    [text* your-name] </p>

<p>Your Email (required)<br />
    [email* your-email] </p>

<p>Subject<br />
    [text your-subject] </p>

<p>Your Message<br />
    [textarea your-message] </p>

<p>[submit "Send"]</p>');

						$mail = array(

							'subject' => trim('[your-subject]'),
				
							'sender' => trim('[your-name] <[your-email]>'),
				
							'body' => trim('From: [your-name] <[your-email]>
Subject: [your-subject]

Message Body:
[your-message]

--
This mail is sent via contact form on '.get_bloginfo('name').' '.get_bloginfo('url').''),
				
							'recipient' => trim('info@'.$domain.''),
				
							'additional_headers' => trim(''),
				
							'attachments' => trim(''),
				
							'use_html' => 0
							);

						add_post_meta($postid, 'mail', $mail);
						
						$mail_2 = array(

						'active' =>0,
			
						'subject' => trim('[your-subject]'),
			
						'sender' => trim('[your-name] <[your-email]>'),
			
						'body' => trim('essage body:
[your-message]

--
This mail is sent via contact form on '.get_bloginfo('name').' '.get_bloginfo('url').''),
			
						'recipient' => trim('info@'.$domain.''),
			
						'additional_headers' => trim(''),
			
						'attachments' => trim(''),
			
						'use_html' =>0);

						add_post_meta($postid, 'mail_2', $mail_2);
						
						$messages = array(
						'mail_sent_ok'=>'Your message was sent successfully. Thanks.',
						'mail_sent_ng'=>'Failed to send your message. Please try later or contact the administrator by another method.',
						'validation_error'=>'Validation errors occurred. Please confirm the fields and submit it again.',
						'accept_terms'=>'Please accept the terms to proceed.',
						'invalid_email'=>'Email address seems invalid.',
						'invalid_required'=>'Please fill the required field.');

						add_post_meta($postid, 'messages', $messages);
						add_post_meta($postid, 'additional_settings', '');
					}
						
					// Create post object
					$_p = array();
					$_p['post_title'] = $the_page_title;
					$_p['post_content'] = "<p>If you have any questions or comments, please feel free to contact me
at the following email address:</p>
<p><i>info@".$domain."</i></p>
<p>I'll respond back typically within 24 hours.</p>
<p>Thanks!</p>
<p>Or use the contact form below</p>
<p>[contact-form-7 id='".$postid."' title='Contact form 1']</p>";
					$_p['post_status'] = 'publish';
					$_p['post_type'] = 'page';
					$_p['comment_status'] = 'closed';
					$_p['ping_status'] = 'closed';
					$_p['post_category'] = array(1); // the default 'Uncatrgorised'
			
					// Insert the post into the database
					$the_page_id = wp_insert_post( $_p );
			
				}
				else {			
					$the_page_id = $the_page->ID;
			
					//make sure the page is not trashed...
					$the_page->post_status = 'publish';
					$the_page_id = wp_update_post( $the_page );
				}
			}else{
				$input['contact_page'] = false;
			}
			
			
			/* SiteMap Page Options */
			if(isset($input['sitemap_page']) && !is_plugin_active('wp-archive-sitemap-generator/wp-simple-archive-sitemap.php')) {
				unset($input['sitemap_page']);
				add_settings_error('graphene_options', 2, __('ERROR: You need to install and activate "WP Archive-Sitemap Generator" plugin.', 'graphene'));
			}elseif(isset($input['sitemap_page'])){
				$input['sitemap_page'] = true;
				
				$the_page_title = 'Site Map';
    			$the_page_name = 'sitemap';
				
				$the_page = get_page_by_title( $the_page_title );
				
				if ( ! $the_page ) {
						
					// Create post object
					$_p = array();
					$_p['post_title'] = $the_page_title;
					$_p['post_content'] = "<!--wp-archive-sitemap-generator-->";
					$_p['post_status'] = 'publish';
					$_p['post_type'] = 'page';
					$_p['comment_status'] = 'closed';
					$_p['ping_status'] = 'closed';
					$_p['post_category'] = array(1); // the default 'Uncatrgorised'
			
					// Insert the post into the database
					$the_page_id = wp_insert_post( $_p );
			
				}
				else {			
					$the_page_id = $the_page->ID;
			
					//make sure the page is not trashed...
					$the_page->post_status = 'publish';
					$the_page_id = wp_update_post( $the_page );
				}
			}else{
				$input['sitemap_page'] = false;
			}
			
			
			/* About Page options */
			if(isset($input['about_page'])){
				$input['about_page'] = true;
				
				$the_page_title = 'About Us';
    			$the_page_name = 'about-us';
				
				$the_page = get_page_by_title( $the_page_title );
				
				if ( ! $the_page ) {
					
					$name = get_bloginfo('name');
					// Create post object
					$_p = array();
					$_p['post_title'] = $the_page_title;
					$_p['post_content'] = "<p><h3>Welcome to my blog site for ".$name.".</h3></p>
<p>This website is created to provide updated information on ".$name." and
enlighten people about what they need to know about ".$name.".</p>
<p>I've cut through all the hype and revealed the facts that will help you
decide whether ".$name." is right for you or not.</p>
<p>Feel free to go through my website and get to know more about
".$name.". If you have any questions, please go to the contact
page.</p>
<p>Thanks for visiting my website!</p>
";
					$_p['post_status'] = 'publish';
					$_p['post_type'] = 'page';
					$_p['comment_status'] = 'closed';
					$_p['ping_status'] = 'closed';
					$_p['post_category'] = array(1); // the default 'Uncatrgorised'
			
					// Insert the post into the database
					$the_page_id = wp_insert_post( $_p );
			
				}
				else {			
					$the_page_id = $the_page->ID;
			
					//make sure the page is not trashed...
					$the_page->post_status = 'publish';
					$the_page_id = wp_update_post( $the_page );
				}
				
			}else{
				$input['about_page'] = false;
			}
			
			/* Pricacy Policy options */
			if(isset($input['privacypolicy_page'])){
				$input['privacypolicy_page'] = true;
				$the_page_title = 'Privacy Policy';
    			$the_page_name = 'privacy-policy';
				
				$the_page = get_page_by_title( $the_page_title );
				
				if ( ! $the_page ) {

					// Create post object
					$_p = array();
					$_p['post_title'] = $the_page_title;
					$_p['post_content'] = "<p><strong>Your Privacy</strong><br/>Your privacy is important to us. To better protect your privacy we provide this notice explaining our online information practices and the choices you can make about the way your information is collected and used. To make this notice easy to find, we make it available on our homepage and at every point where personally identifiable information may be requested.
<p/><strong>Google Adsense and the DoubleClick DART Cookie</strong><br/>Google, as a third party advertisement vendor, uses cookies to serve ads on this site. The use of DART cookies by Google enables them to serve adverts to visitors that are based on their visits to this website as well as other sites on the internet.</p>
<p>To opt out of the DART cookies you may visit the Google ad and content network privacy policy at the following url<a href=\"http://www.google.com/privacy_ads.html\">http://www.google.com/privacy_ads.html</a> Tracking of users through the DART cookie mechanisms are subject to Google&#8217;s own privacy policies.</p>
<p>Other Third Party ad servers or ad networks may also use cookies to track users activities on this website to measure advertisement effectiveness and other reasons that will be provided in their own privacy policies, ".$domain." has no access or control over these cookies that may be used by third party advertisers.
<p/><strong>Collection of Personal Information</strong><br/>When visiting ".$domain.", the IP address used to access the site will be logged along with the dates and times of access. This information is purely used to analyze trends, administer the site, track users movement and gather broad demographic information for internal use. Most importantly, any recorded IP addresses are not linked to personally identifiable information.</p>
<p/><strong>Links to third party Websites</strong><br/>We have included links on this site for your use and reference. We are not responsible for the privacy policies on these websites. You should be aware that the privacy policies of these sites may differ from our own. </p>
<p/><strong>Changes to this Privacy Statement</strong><br/>The contents of this statement may be altered at any time, at our discretion. </p>
<p>If you have any questions regarding our privacy policy then you may contact us at info@".$domain."
<p/>";
					$_p['post_status'] = 'publish';
					$_p['post_type'] = 'page';
					$_p['comment_status'] = 'closed';
					$_p['ping_status'] = 'closed';
					$_p['post_category'] = array(1); // the default 'Uncatrgorised'
			
					// Insert the post into the database
					$the_page_id = wp_insert_post( $_p );
			
				}
				else {			
					$the_page_id = $the_page->ID;
			
					//make sure the page is not trashed...
					$the_page->post_status = 'publish';
					$the_page_id = wp_update_post( $the_page );
				}
			}else{
				$input['privacypolicy_page'] = false;
			}
		
			/* =Slider Options 
			--------------------------------------------------------------------------------------*/
			
			// Slider category
			if ( isset($input['slider_type']) && !in_array($input['slider_type'], array('latest_posts', 'random', 'posts_pages', 'categories' ) ) ){
				unset($input['slider_type']);
				add_settings_error('graphene_options', 2, __('ERROR: Invalid category to show in slider.', 'graphene'));
			} elseif ( $input['slider_type'] == 'posts_pages' && empty ( $input['slider_specific_posts'] ) ) {
				unset($input['slider_type']);
				add_settings_error('graphene_options', 2, __('ERROR: You must specify the posts/pages to be displayed when you have "Show specific posts/pages" selected for the slider.', 'graphene'));
                        } elseif ( $input['slider_type'] == 'categories' && empty ( $input['slider_specific_categories'] ) ) {
				unset($input['slider_type']);
				add_settings_error('graphene_options', 2, __('ERROR: You must have selected at least one category when you have "Show posts from categories" selected for the slider.', 'graphene'));
			}                        
			// Posts and/or pages to display
			if (isset($input['slider_type']) && $input['slider_type'] == 'posts_pages' && isset($input['slider_specific_posts'])) {
				$input['slider_specific_posts'] = str_replace(' ', '', $input['slider_specific_posts']);
			}
                        // Categories to display posts from
                        if (isset($input['slider_type']) && $input['slider_type'] == 'categories' && isset($input['slider_specific_categories']) && is_array($input['slider_specific_categories'])){
                            if ( in_array ( false, array_map( 'ctype_digit', (array) $input['slider_specific_categories'] ) ) ) {
                                unset($input['slider_specific_categories']);
                                add_settings_error('graphene_options', 2, __('ERROR: Invalid category selected for the slider categories.', 'graphene'));
                            }
                        }
			// Number of posts to display
			if (!empty($input['slider_postcount']) && !ctype_digit($input['slider_postcount'])){
				unset($input['slider_postcount']);
				add_settings_error('graphene_options', 2, __('ERROR: The number of posts to displayed in the slider must be a an integer value.', 'graphene'));
			}
			// Slider image
			$input = graphene_validate_dropdown( $input, 'slider_img', array('disabled', 'featured_image', 'post_image', 'custom_url'), __('ERROR: Invalid option for the slider image is specified.', 'graphene') );
			// Custom slider image URL
			$input = graphene_validate_url( $input, 'slider_imgurl', __('ERROR: Bad URL entered for the custom slider image URL.', 'graphene') );
			// Slider display style
			$input = graphene_validate_dropdown( $input, 'slider_display_style', array('thumbnail-excerpt', 'bgimage-excerpt', 'full-post'), __('ERROR: Invalid option for the slider display style is specified.', 'graphene') );
			// Slider height
			$input = graphene_validate_digits( $input, 'slider_height', __('ERROR: The value for slider height must be an integer.', 'graphene'));
			// Slider speed
			$input = graphene_validate_digits( $input, 'slider_speed', __('ERROR: The value for slider speed must be an integer.', 'graphene'));
			// Slider transition speed
			$input = graphene_validate_digits( $input, 'slider_trans_speed', __('ERROR: The value for slider transition speed must be an integer.', 'graphene'));
			// Slider animation
			$input = graphene_validate_dropdown( $input, 'slider_animation', array( 'horizontal-slide', 'vertical-slide', 'fade', 'none' ), __( 'ERROR: Invalid slider animation.', 'graphene' ) );
                        // Slider position
			$input['slider_position'] = (isset($input['slider_position'])) ? true : false;
			// Slider disable switch
			$input['slider_disable'] = (isset($input['slider_disable'])) ? true : false;
			
			
			/* =Front Page Options 
			--------------------------------------------------------------------------------------*/
			
			// Front page posts categories
			if ( ! in_array ( '', (array) $input['frontpage_posts_cats'] ) ) {
				if ( in_array ( false, array_map( 'ctype_digit', (array) $input['frontpage_posts_cats'] ) ) ) {
					unset($input['frontpage_posts_cats']);
					add_settings_error('graphene_options', 2, __('ERROR: Invalid category selected for the front page posts categories.', 'graphene'));
				}
			} else {
				$input['frontpage_posts_cats'] = $graphene_defaults['frontpage_posts_cats'];
			}
			
			/* =HomePage Options */
			if ( empty($input['homepage_description']) ) $input['homepage_description'] = $graphene_defaults['homepage_description'];
			
			
			/* =Homepage Panes
			--------------------------------------------------------------------------------------*/
			
			// Type of content to show
			$input = graphene_validate_dropdown( $input, 'show_post_type', array('latest-posts', 'cat-latest-posts', 'posts'), __('ERROR: Invalid option for the type of content to show in homepage panes.', 'graphene') );
			// Number of latest posts to display
			$input = graphene_validate_digits( $input, 'homepage_panes_count', __('ERROR: The value for the number of latest posts to display in homepage panes must be an integer.', 'graphene') );
			// Categories to show latest posts from
                        if ($input['show_post_type'] == 'cat-latest-posts' && isset($input['homepage_panes_cat']) && is_array($input['homepage_panes_cat'])) {
                            if ( in_array ( false, array_map( 'ctype_digit', (array) $input['homepage_panes_cat'] ) ) ) {
                                unset($input['slider_specific_categories']);
                                add_settings_error('graphene_options', 2, __('ERROR: Invalid category selected for the latest posts to show from in the homepage panes.', 'graphene'));
                            }
                        }			
			// Posts and/or pages to display
			if ($input['show_post_type'] == 'posts' && isset($input['homepage_panes_posts'])) {
				$input['homepage_panes_posts'] = str_replace(' ', '', $input['homepage_panes_posts']);
			}
			// Disable switch
			$input['disable_homepage_panes'] = (isset($input['disable_homepage_panes'])) ? true : false;
			
            
			/* =Comments Options
			--------------------------------------------------------------------------------------*/
			
			$input = graphene_validate_dropdown( $input, 'comments_setting', array('wordpress', 'disabled_pages', 'disabled_completely'), __('ERROR: Invalid option for the comments option.', 'graphene') );
			
			            
			/* =Child Page Options
			--------------------------------------------------------------------------------------*/
			
			// Hide parent box if content is empty
			$input['hide_parent_content_if_empty'] = (isset($input['hide_parent_content_if_empty'])) ? true : false;                        
			// Child page listing
			$input = graphene_validate_dropdown( $input, 'child_page_listing', array('hide', 'show_always', 'show_if_parent_empty'), __('ERROR: Invalid option for the child page listings.', 'graphene') );
			
                        
			/* =Widget Area Options
			--------------------------------------------------------------------------------------*/
			
			$input['enable_header_widget'] = (isset($input['enable_header_widget'])) ? true : false;
			$input['alt_home_sidebar'] = (isset($input['alt_home_sidebar'])) ? true : false;
			$input['alt_home_footerwidget'] = (isset($input['alt_home_footerwidget'])) ? true : false;
                        
                        
			/* =Top Bar Options
			--------------------------------------------------------------------------------------*/
			// Hide top bar
                        $input['hide_top_bar'] = (isset($input['hide_top_bar'])) ? true : false;
			// Hide feed icon switch
			$input['hide_feed_icon'] = (isset($input['hide_feed_icon'])) ? true : false;
			// Custom feed URL
			$input = graphene_validate_url( $input, 'custom_feed_url', __('ERROR: Bad URL entered for the custom feed URL.', 'graphene') );
			// Open in new window
			$input['social_media_new_window'] = (isset($input['social_media_new_window'])) ? true : false;
			// Twitter URL
			$input = graphene_validate_url( $input, 'twitter_url', __('ERROR: Bad URL entered for the Twitter URL.', 'graphene') );
			// Facebook URL
			$input = graphene_validate_url( $input, 'facebook_url', __('ERROR: Bad URL entered for the Facebook URL.', 'graphene') );
			// Youtube URL
			$input = graphene_validate_url( $input, 'youtube_url', __('ERROR: Bad URL entered for the Youtube URL.', 'graphene') );
			// LinkedIn URL
			$input = graphene_validate_url( $input, 'linkedin_url', __('ERROR: Bad URL entered for the LinkedIn URL.', 'graphene') );
			// Yahoo URL
			$input = graphene_validate_url( $input, 'yahoo_url', __('ERROR: Bad URL entered for the Yahoo URL.', 'graphene') );
			// Flickr URL
			$input = graphene_validate_url( $input, 'flickr_url', __('ERROR: Bad URL entered for the Flickr URL.', 'graphene') );
			// Vevo URL
			$input = graphene_validate_url( $input, 'vevo_url', __('ERROR: Bad URL entered for the Vevo URL.', 'graphene') );
			// Bing URL
			$input = graphene_validate_url( $input, 'bing_url', __('ERROR: Bad URL entered for the Bing URL.', 'graphene') );
			// Stumbleupon Url
			$input = graphene_validate_url( $input, 'su_url', __('ERROR: Bad URL entered for the Bing URL.', 'graphene') );
			
            /* Social media */
			$social_media_new = (!empty($input['social_media_new'])) ? $input['social_media_new'] : array();
			if (!empty($social_media_new)){
				$i = 0;
				foreach ($social_media_new as $social_medium){
					if (!empty($social_medium['name'])){
						$slug = sanitize_title($social_medium['name'], $i);
						$input['social_media'][$slug]['name'] = $social_medium['name'];
						$input['social_media'][$slug]['icon'] = $social_medium['icon'];
						$input['social_media'][$slug]['url'] = $social_medium['url'];
                                                $input['social_media'][$slug]['title'] = $social_medium['title'];
						$input['social_media'][$slug] = graphene_validate_url( $input['social_media'][$slug], 'icon', __('ERROR: Bad URL entered for the social media icon URL.', 'graphene') );
						$input['social_media'][$slug] = graphene_validate_url( $input['social_media'][$slug], 'url', __('ERROR: Bad URL entered for the social media URL.', 'graphene') );
						$i++;
					}
				}
			}else{
				$input['social_media'] = $graphene_defaults['social_media'];
			}
			            
                        
			/* =Social Sharing Options
			--------------------------------------------------------------------------------------*/
			
			// Show social sharing button switch
			$input['show_addthis'] = (isset($input['show_addthis'])) ? true : false;
			// Show buttons in pages switch
			$input['show_addthis_page'] = (isset($input['show_addthis_page'])) ? true : false;
			// Social sharing buttons location
			$input = graphene_validate_dropdown( $input, 'addthis_location', array('post-bottom', 'post-top', 'top-bottom'), __('ERROR: Invalid option for the social sharing buttons location.', 'graphene') );
			// Social sharing buttons code
			$input['addthis_code'] = trim( stripslashes( $input['addthis_code'] ) );
			
			/* =Header Ads Options */
			$input['show_headerads'] = (isset($input['show_headerads'])) ? true : false;
			if ( empty($input['headerads_code']) ) $input['headerads_code'] = $graphene_defaults['headerads_code'];
			if ( empty($input['rightposition_headerads']) ) $input['leftposition_headerads'] = $graphene_defaults['leftposition_headerads'];
			if ( empty($input['topposition_headerads']) ) $input['topposition_headerads'] = $graphene_defaults['topposition_headerads'];
			
			/* =Ads after Menu/Header Options */
			$input['show_headerafterads'] = (isset($input['show_headerafterads'])) ? true : false;
			if ( empty($input['headerafterads_code']) ) $input['headerafterads_code'] = $graphene_defaults['headerafterads_code'];
			
			/* =Google Custom Search */
			$input['google_search'] = (isset($input['google_search'])) ? true : false;
			if ( empty($input['google_search_form']) ) $input['google_search_form'] = $graphene_defaults['google_search_form'];
             
			/* =Google Adsense Single Post Page */
			$input['show_singleadsense'] = (isset($input['show_singleadsense'])) ? true : false;
			$input['topposition_adsense'] = (isset($input['topposition_adsense'])) ? true : false;
			$input['bottomposition_adsense'] = (isset($input['bottomposition_adsense'])) ? true : false;
			$input['width_adsense'] = wp_kses_post( $input['width_adsense'] );
			$input['height_adsense'] = wp_kses_post( $input['height_adsense'] );
			$input['singleadsense_code'] = wp_kses_post( $input['singleadsense_code'] );
			
			 
			/* =Adsense Options
			--------------------------------------------------------------------------------------*/
			
			// Show Adsense ads switch
			$input['show_adsense'] = (isset($input['show_adsense'])) ? true : false;
			// Show ads on front page switch
			$input['adsense_show_frontpage'] = (isset($input['adsense_show_frontpage'])) ? true : false;
			// Adsense code
			$input['adsense_code'] = wp_kses_post( $input['adsense_code'] );
			
						
			/* =Google Analytics Options
			--------------------------------------------------------------------------------------*/
			
			// Enable tracking switch
			$input['show_ga'] = (isset($input['show_ga'])) ? true : false;
			// Tracking code
			$input['ga_code'] = wp_kses_post($input['ga_code']);
			
                        
			/* =Footer Options
			--------------------------------------------------------------------------------------*/
			
			// Show creative common logo switch
			$input['show_cc'] = (isset($input['show_cc'])) ? true : false;
			// Copyright HTML
			$input['copy_text'] = wp_kses_post($input['copy_text']);
			// Hide copyright switch
			$input['hide_copyright'] = (isset($input['hide_copyright'])) ? true : false;
			// Hide "Return to top" link switch
			$input['hide_return_top'] = (isset($input['hide_return_top'])) ? true : false;
                        
                        
			/* =Print Options
			--------------------------------------------------------------------------------------*/  
			
			// Enable print CSS switch
			$input['print_css'] = (isset($input['print_css'])) ? true : false;
			// Show print button switch
			$input['print_button'] = (isset($input['print_button'])) ? true : false;
	
			
			
		} // Ends the General options
		
		
		if (isset($_POST['graphene_display'])) {
			
			/* =Header Display Options
			--------------------------------------------------------------------------------------*/  
			
			/* =Resize Options */
			if(isset($input['content_size']) && isset($input['leftsidebar_size']) && isset($input['container_size']) && isset($input['rightsidebar_size'])):
				if($input['column_mode'] == 'three-col-left' || $input['column_mode'] == 'three-col-right' || $input['column_mode'] == 'three-col-center'):
					$rightsidebar_size = (int)$input['rightsidebar_size'];					
					$leftsidebar_size = (int)$input['leftsidebar_size'];
					$content_size = (int)$input['content_size'];
					$container_size = (int)$input['container_size'];
					if($content_size < 0 || $rightsidebar_size < 0 || $leftsidebar_size < 0 || $container_size < 0){
						$input['rightsidebar_size'] = $graphene_defaults['rightsidebar_size'];
						$input['leftsidebar_size'] = $graphene_defaults['leftsidebar_size'];
						$input['content_size'] = $graphene_defaults['content_size'];
						$input['container_size'] = $graphene_defaults['container_size'];
						add_settings_error('graphene_options', 2, __('ERROR: You need to insert positive values.', 'graphene'));
					}else
					if($input['content_size'] === '0' || $input['rightsidebar_size'] === '0' || $input['leftsidebar_size'] === '0' || $input['container_size'] === '0'){
						$input['rightsidebar_size'] = $graphene_defaults['rightsidebar_size'];
						$input['leftsidebar_size'] = $graphene_defaults['leftsidebar_size'];
						$input['content_size'] = $graphene_defaults['content_size'];
						$input['container_size'] = $graphene_defaults['container_size'];
						add_settings_error('graphene_options', 2, __('ERROR: You insert value 0 to one of the field.', 'graphene'));
					}elseif($content_size+$leftsidebar_size+$rightsidebar_size > $container_size){
						$input['rightsidebar_size'] = $graphene_defaults['rightsidebar_size'];
						$input['leftsidebar_size'] = $graphene_defaults['leftsidebar_size'];
						$input['content_size'] = $graphene_defaults['content_size'];
						add_settings_error('graphene_options', 2, __('ERROR: Sum between sidebars and content exceeds the container size.', 'graphene'));
					}
				endif;
			elseif(isset($input['content_size']) && isset($input['leftsidebar_size']) && isset($input['container_size'])):
				if($input['column_mode'] == 'two-col-left'):
					$leftsidebar_size = (int)$input['leftsidebar_size'];					
					$content_size = (int)$input['content_size'];
					$container_size = (int)$input['container_size'];
					if($content_size < 0 || $leftsidebar_size < 0 || $container_size < 0){
						$input['leftsidebar_size'] = $graphene_defaults['leftsidebar_size'];
						$input['content_size'] = $graphene_defaults['content_size'];
						$input['container_size'] = $graphene_defaults['container_size'];
						add_settings_error('graphene_options', 2, __('ERROR: You need to insert positive values.', 'graphene'));
					}elseif($input['content_size'] === '0' || $input['leftsidebar_size'] === '0' || $input['container_size'] === '0'){
						$input['leftsidebar_size'] = $graphene_defaults['leftsidebar_size'];
						$input['content_size'] = $graphene_defaults['content_size'];
						$input['container_size'] = $graphene_defaults['container_size'];
						add_settings_error('graphene_options', 2, __('ERROR: You insert value 0 to one of the field.', 'graphene'));
					}elseif($content_size+$leftsidebar_size > $container_size){
						$input['leftsidebar_size'] = $graphene_defaults['leftsidebar_size'];
						$input['content_size'] = $graphene_defaults['content_size'];
						add_settings_error('graphene_options', 2, __('ERROR: Sum between sidebar and content exceeds the container size.', 'graphene'));
					}
				endif;
			elseif(isset($input['content_size']) && isset($input['rightsidebar_size']) && isset($input['container_size'])):
				if($input['column_mode'] == 'two-col-right'):
					$rightsidebar_size = (int)$input['rightsidebar_size'];
					$content_size = (int)$input['content_size'];
					$container_size = (int)$input['container_size'];
					if($content_size < 0 || $rightsidebar_size < 0 || $container_size < 0){
						$input['rightsidebar_size'] = $graphene_defaults['rightsidebar_size'];
						$input['content_size'] = $graphene_defaults['content_size'];
						$input['container_size'] = $graphene_defaults['container_size'];
						add_settings_error('graphene_options', 2, __('ERROR: You need to insert positive values.', 'graphene'));
					}elseif($input['content_size'] === '0' || $input['rightsidebar_size'] === '0' || $input['container_size'] === '0'){
						$input['rightsidebar_size'] = $graphene_defaults['rightsidebar_size'];
						$input['content_size'] = $graphene_defaults['content_size'];
						$input['container_size'] = $graphene_defaults['container_size'];
						add_settings_error('graphene_options', 2, __('ERROR: You insert value 0 to one of the field.', 'graphene'));
					}elseif($content_size+$rightsidebar_size > $container_size){
						$input['rightsidebar_size'] = $graphene_defaults['rightsidebar_size'];
						$input['content_size'] = $graphene_defaults['content_size'];
						add_settings_error('graphene_options', 2, __('ERROR: Sum between sidebar and content exceeds the container size.', 'graphene'));
					}
				endif;			
			elseif(isset($input['container_size'])):
				if($input['column_mode'] == 'one-column'):
					$container_size = (int)$input['container_size'];
					if($container_size < 0){
						$input['container_size'] = $graphene_defaults['container_size'];
						add_settings_error('graphene_options', 2, __('ERROR: You need to insert positive values.', 'graphene'));
					}elseif(!empty($input['container_size']) && $container_size == 0){
						$input['container_size'] = $graphene_defaults['container_size'];
						add_settings_error('graphene_options', 2, __('ERROR: You insert value 0 to one of the field.', 'graphene'));
					}
				endif;
			endif;
			
			$input['light_header'] = (isset($input['light_header'])) ? true : false;
			$input['link_header_img'] = (isset($input['link_header_img'])) ? true : false;
			$input['featured_img_header'] = (isset($input['featured_img_header'])) ? true : false;
			$input['use_random_header_img'] = (isset($input['use_random_header_img'])) ? true : false;				
			$input = graphene_validate_dropdown( $input, 'search_box_location', array('top_bar', 'nav_bar', 'disabled'), __('ERROR: Invalid option for the Search box location.', 'graphene') );
			if ( empty($input['header_color']) ) $input['header_color'] = $graphene_defaults['header_color'];
			$input['disable_header'] = (isset($input['disable_header'])) ? true : false;
			if ( empty($input['round_corners_topleft']) ) $input['round_corners_topleft'] = $graphene_defaults['round_corners_topleft'];
			if ( empty($input['round_corners_topright']) ) $input['round_corners_topright'] = $graphene_defaults['round_corners_topright'];
			if ( empty($input['round_corners_bottomleft']) ) $input['round_corners_bottomleft'] = $graphene_defaults['round_corners_bottomleft'];
			if ( empty($input['round_corners_bottomright']) ) $input['round_corners_bottomright'] = $graphene_defaults['round_corners_bottomright'];
			
			
			/*Header Title and Logo options */
			$input['enable_header_widget'] = (isset($input['enable_header_widget'])) ? true : false;
			$input['logomargin_left'] = strip_tags( $input['logomargin_left'] );
			$input['logomargin_top'] = strip_tags( $input['logomargin_top'] );
			
			/* Menu options */
			$input['menu_disable'] = (isset($input['menu_disable'])) ? true : false;
			$input['menu_position'] = strip_tags( $input['menu_position'] );
			if ( empty($input['menuround_corners_topleft']) ) $input['menuround_corners_topleft'] = $graphene_defaults['menuround_corners_topleft'];
			if ( empty($input['menuround_corners_topright']) ) $input['menuround_corners_topright'] = $graphene_defaults['menuround_corners_topright'];
			if ( empty($input['menuround_corners_bottomleft']) ) $input['menuround_corners_bottomleft'] = $graphene_defaults['menuround_corners_bottomleft'];
			if ( empty($input['menuround_corners_bottomright']) ) $input['menuround_corners_bottomright'] = $graphene_defaults['menuround_corners_bottomright'];
			
			
			/* =Column Options
			--------------------------------------------------------------------------------------*/
			$input = graphene_validate_dropdown( $input, 'column_mode', array('one-column', 'two-col-left', 'two-col-right', 'three-col-left', 'three-col-right', 'three-col-center'), __('ERROR: Invalid option for the column mode.', 'graphene') ); 
                        
                        
			/* =Post Display Options
			--------------------------------------------------------------------------------------*/                        
			$input['hide_post_author'] = (isset($input['hide_post_author'])) ? true : false;
			$input = graphene_validate_dropdown( $input, 'post_date_display', array('hidden', 'icon_no_year', 'icon_plus_year', 'text'), __('ERROR: Invalid option for the post date display.', 'graphene') ); 
			$input['hide_post_cat'] = (isset($input['hide_post_cat'])) ? true : false;
			$input['hide_post_tags'] = (isset($input['hide_post_tags'])) ? true : false;
			$input['hide_post_commentcount'] = (isset($input['hide_post_commentcount'])) ? true : false;
			$input['show_post_avatar'] = (isset($input['show_post_avatar'])) ? true : false;
			$input['show_post_author'] = (isset($input['show_post_author'])) ? true : false;
                        
                        
			/* =Excerpts Display Options
			--------------------------------------------------------------------------------------*/     
			$input['posts_show_excerpt'] = (isset($input['posts_show_excerpt'])) ? true : false;                        
			$input['archive_full_content'] = (isset($input['archive_full_content'])) ? true : false;					
			$input['show_excerpt_more'] = (isset($input['show_excerpt_more'])) ? true : false;
                        
                        
			/* =Comments Display Options
			--------------------------------------------------------------------------------------*/
			$input['hide_allowedtags'] = (isset($input['hide_allowedtags'])) ? true : false;
                        

			/* =Background Colour Options
			--------------------------------------------------------------------------------------*/
			// Content area
			if ( empty($input['bg_content_wrapper']) ) $input['bg_content_wrapper'] = $graphene_defaults['bg_content_wrapper'];
			if ( empty($input['bg_content']) ) $input['bg_content'] = $graphene_defaults['bg_content'];
			if ( empty($input['bg_meta_border']) ) $input['bg_meta_border'] = $graphene_defaults['bg_meta_border'];
			if ( empty($input['bg_post_top_border']) ) $input['bg_post_top_border'] = $graphene_defaults['bg_post_top_border'];
			if ( empty($input['bg_post_bottom_border']) ) $input['bg_post_bottom_border'] = $graphene_defaults['bg_post_bottom_border'];
			
			// Widgets
			if ( empty($input['bg_widget_item']) ) $input['bg_widget_item'] = $graphene_defaults['bg_widget_item'];
			if ( empty($input['bg_widget_list']) ) $input['bg_widget_list'] = $graphene_defaults['bg_widget_list'];
			if ( empty($input['bg_widget_header_border']) ) $input['bg_widget_header_border'] = $graphene_defaults['bg_widget_header_border'];
			if ( empty($input['bg_widget_title']) ) $input['bg_widget_title'] = $graphene_defaults['bg_widget_title'];
			if ( empty($input['bg_widget_title_textshadow']) ) $input['bg_widget_title_textshadow'] = $graphene_defaults['bg_widget_title_textshadow'];
			if ( empty($input['bg_widget_header_bottom']) ) $input['bg_widget_header_bottom'] = $graphene_defaults['bg_widget_header_bottom'];
			if ( empty($input['bg_widget_header_top']) ) $input['bg_widget_header_top'] = $graphene_defaults['bg_widget_header_top'];
			
			// Slider
			if ( empty($input['bg_slider_top']) ) $input['bg_slider_top'] = $graphene_defaults['bg_slider_top'];
			if ( empty($input['bg_slider_bottom']) ) $input['bg_slider_bottom'] = $graphene_defaults['bg_slider_bottom'];
			
			// Block button
			if ( empty($input['bg_button']) ) $input['bg_button'] = $graphene_defaults['bg_button'];
			if ( empty($input['bg_button_label']) ) $input['bg_button_label'] = $graphene_defaults['bg_button_label'];
			if ( empty($input['bg_button_label_textshadow']) ) $input['bg_button_label_textshadow'] = $graphene_defaults['bg_button_label_textshadow'];
                        
            // Archive
			if ( empty($input['bg_archive_left']) ) $input['bg_archive_left'] = $graphene_defaults['bg_archive_left'];
            if ( empty($input['bg_archive_right']) ) $input['bg_archive_right'] = $graphene_defaults['bg_archive_right'];
			if ( empty($input['bg_archive_label']) ) $input['bg_archive_label'] = $graphene_defaults['bg_archive_label'];
			if ( empty($input['bg_archive_text']) ) $input['bg_archive_text'] = $graphene_defaults['bg_archive_text'];
            if ( empty($input['bg_archive_textshadow']) ) $input['bg_archive_textshadow'] = $graphene_defaults['bg_archive_textshadow'];

			/* =Page Border Options*/
			if ( empty($input['page_border_color']) ) $input['page_border_color'] = $graphene_defaults['page_border_color'];
			if ( empty($input['page_border_size']) ) $input['page_border_size'] = $graphene_defaults['page_border_size'];
			if ( empty($input['page_round_corners_topleft']) ) $input['page_round_corners_topleft'] = $graphene_defaults['page_round_corners_topleft'];
			if ( empty($input['page_round_corners_topright']) ) $input['page_round_corners_topright'] = $graphene_defaults['page_round_corners_topright'];
			if ( empty($input['page_round_corners_bottomleft']) ) $input['page_round_corners_bottomleft'] = $graphene_defaults['page_round_corners_bottomleft'];
			if ( empty($input['page_round_corners_bottomright']) ) $input['page_round_corners_bottomright'] = $graphene_defaults['page_round_corners_bottomright'];
			
			
			/* =Text Style Options
			--------------------------------------------------------------------------------------*/
			if ( empty($input['content_font_colour']) ) $input['content_font_colour'] = $graphene_defaults['content_font_colour'];
			if ( empty($input['link_colour_normal']) ) $input['link_colour_normal'] = $graphene_defaults['link_colour_normal'];
			if ( empty($input['link_colour_visited']) ) $input['link_colour_visited'] = $graphene_defaults['link_colour_visited'];
			if ( empty($input['link_colour_hover']) ) $input['link_colour_hover'] = $graphene_defaults['link_colour_hover'];
			
			/* Forum and HelpDesk Options */
			$input = graphene_validate_url( $input, 'forum_link', __('ERROR: Bad URL entered for the Forum URL.', 'graphene') );
			$input = graphene_validate_url( $input, 'helpdesk_link', __('ERROR: Bad URL entered for the HelpDesk URL.', 'graphene') );
			
                        
			/* =Footer Widget Display Options
			--------------------------------------------------------------------------------------*/
			// Number of columns to display
			$input = graphene_validate_digits( $input, 'footerwidget_column', __('ERROR: The number of columns to be displayed in the footer widget must be a an integer value.', 'graphene' ) );
			
			
			/* =Navigation Menu Display Options
			--------------------------------------------------------------------------------------*/
			$input = graphene_validate_digits( $input, 'navmenu_child_width', __('ERROR: The width of the submenu must be a an integer value.', 'graphene' ) );
			$input['navmenu_home_desc'] = wp_kses_post( $input['navmenu_home_desc'] );
			$input['disable_menu_desc'] = (isset($input['disable_menu_desc'])) ? true : false;
			
			/* =Miscellaneous Display Options
			--------------------------------------------------------------------------------------*/
			$input['custom_site_title_frontpage'] = strip_tags( $input['custom_site_title_frontpage'] );
			$input['custom_site_title_content'] = strip_tags( $input['custom_site_title_content'] );
			$input = graphene_validate_url( $input, 'favicon_url', __( 'ERROR: Bad URL entered for the favicon URL.', 'graphene' ) );
			
			/* =Custom CSS Options 
			--------------------------------------------------------------------------------------*/
			$input['custom_css'] = strip_tags( $input['custom_css'] );
		
		} // Ends the Display options
                
		if ( isset($_POST['graphene_advanced'] ) ) {
			$input['enable_preview'] = ( isset( $input['enable_preview'] ) ) ? true : false; 
			
			if ( isset( $input['widget_hooks'] ) && is_array( $input['widget_hooks'] ) ) {
				if ( ! ( array_intersect( $input['widget_hooks'], graphene_get_action_hooks( true ) ) === $input['widget_hooks'] ) ) {
					unset( $input['widget_hooks'] );
					add_settings_error( 'graphene_options', 2, __( 'ERROR: Invalid action hook selected widget action hooks.', 'graphene' ) );
				}
			} else {
				$input['widget_hooks'] = $graphene_defaults['widget_hooks'];
			}
		} // Ends the Advanced options
		
		
		// Merge the new settings with the previous one (if exists) before saving
		$input = array_merge( get_option('graphene_settings', array() ), $input );
		
		/* Only save options that have different values than the default values */
		foreach ( $input as $key => $value ){
			if ( $graphene_defaults[$key] === $value || $value === '' ) {
				unset( $input[$key] );
			}
		}
		
		/* Delete the settings from database if all settings have their default values */
		if (empty($input)){
			delete_option('graphene_settings');
			return false;
		}
		
	} // Closes the uninstall conditional
	
	return $input;
}


/**
 * Define the data validation functions
*/
function graphene_validate_digits( $input, $option_name, $error_message ){
	global $graphene_defaults;
	if ( '0' === $input[$option_name] || ! empty($input[$option_name] ) ){
		if (!ctype_digit($input[$option_name])) {
			$input[$option_name] = $graphene_defaults[$option_name];
			add_settings_error('graphene_options', 2, $error_message);
		}
	} else {
		$input[$option_name] = $graphene_defaults[$option_name];
	}
	
	return $input;
}

function graphene_validate_dropdown( $input, $option_name, $possible_values, $error_message ){
	
	if (isset($input[$option_name]) && !in_array($input[$option_name], $possible_values)){
		unset($input[$option_name]);
		add_settings_error('graphene_options', 2, $error_message);
	}
	return $input;
	
}

function graphene_validate_url( $input, $option_name, $error_message ) {
	global $graphene_defaults;
	if (!empty($input[$option_name])){
		$input[$option_name] = esc_url_raw($input[$option_name]);
		if ($input[$option_name] == '') {
			$input[$option_name] = $graphene_defaults[$option_name];
			add_settings_error('graphene_options', 2, $error_message);
		}	
	}	
	return $input;
	
}
?>