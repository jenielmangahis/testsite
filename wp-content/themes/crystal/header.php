<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content-main">
 *
 * @package WordPress
 * @subpackage Graphene
 * @since graphene 1.0
 */
global $graphene_settings;
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
    <meta http-equiv="Content-Type" content="<?php bloginfo('html_type'); ?>; charset=<?php bloginfo('charset'); ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=Edge" />
    <?php if(is_front_page()): ?>
    <meta name="description" content="<?php echo $graphene_settings['homepage_description']; ?>" />
    <meta name="keywords" content="<?php echo $graphene_settings['homepage_keywords']; ?>" />
    <?php endif; ?>
    <title><?php graphene_title(); ?></title>
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" /> 
    <?php
    /* We add some JavaScript to pages with the comment form
     * to support sites with threaded comments (when in use).
     */
    if (is_singular() && get_option('thread_comments'))
        wp_enqueue_script('comment-reply');
    
    /* Add a print style only for single pages/posts 
     * and if the theme option is enabled.
     */
    if (is_singular() && $graphene_settings['print_css'])
        add_action('wp_print_styles', 'graphene_print_style');

    /* Always have wp_head() just before the closing </head>
     * tag of your theme, or you will break many plugins, which
     * generally use this hook to add elements to <head> such
     * as styles, scripts, and meta tags.
     */
    wp_head();
    ?>
</head><?php flush(); ?>
<body <?php body_class(); ?>>

<?php if (!get_theme_mod('background_image', false) && !get_theme_mod('background_color', false)) : ?>
<div class="bg-gradient">
<?php endif; ?>

<?php do_action( 'graphene_container_before' ); ?>

<div id="container">
    
    <?php if ($graphene_settings['hide_top_bar'] != true) : ?>
        <div id="top-bar">

                <div id="rss" class="clearfix">
                	<?php if ($graphene_settings['hide_feed_icon'] != true) : ?>
						<?php $custom_feed_url = ($graphene_settings['custom_feed_url']) ? $graphene_settings['custom_feed_url'] : get_bloginfo('rss2_url'); ?>
                        <a href="<?php echo $custom_feed_url; ?>" title="<?php printf(esc_attr__("Subscribe to %s's RSS feed", 'graphene'), get_bloginfo('name')); ?>" class="rss_link" <?php if ( $graphene_settings['social_media_new_window'] ) { echo 'target="_blank"'; } ?>><span><?php _e('Subscribe to RSS feed', 'graphene'); ?></span></a>
                    <?php endif; ?>
                    <?php do_action('graphene_feed_icon'); ?>
                </div>
            
            <?php
            /**
             * Retrieves our custom search form.
             */
            ?>
            <?php if (($search_box_location = $graphene_settings['search_box_location']) && $graphene_settings['google_search'] === false && $search_box_location == 'top_bar' || $search_box_location == '') : ?>
                <div id="top_search">
                    <?php get_search_form(); ?>
                    <?php do_action('graphene_top_search'); ?>
                </div>
            <?php endif; ?>
            <?php if ($graphene_settings['google_search'] === true && !empty($graphene_settings['google_search_form']) && ($search_box_location = $graphene_settings['search_box_location']) && $search_box_location == 'top_bar' || $search_box_location == ''): ?>
            	<div id="google_search">
                	<?php echo $graphene_settings['google_search_form']; ?>
                </div>
            <?php endif; ?>
            
            <?php do_action( 'graphene_top_bar' ); ?>
            
        </div>
    <?php endif; ?>
    
    <?php if($graphene_settings['menu_position'] == 'top' && $graphene_settings['menu_disable'] === false){ ?>
    <div id="nav">
        <?php /* The navigation menu */ ?>
        <div id="header-menu-wrap">
			<?php
            /* Header menu */
            $args = array(
                'container' => '',
                'menu_id' => 'header-menu',
                'menu_class' => 'menu clearfix',
                'fallback_cb' => 'graphene_default_menu',
                'depth' => 5,
                'theme_location' => 'Header Menu',
            );
			if ( ! $graphene_settings['disable_menu_desc'] )
				$args = array_merge( $args, array( 'walker' => new Graphene_Description_Walker() ) );
            wp_nav_menu(apply_filters('graphene_header_menu_args', $args)); ?>
        
			<?php if (($search_box_location = $graphene_settings['search_box_location']) && $search_box_location == 'nav_bar') : ?>
                <div id="top_search">
                    <?php get_search_form(); ?>
                    <?php do_action('graphene_nav_search'); ?>
                </div>
            <?php endif; ?>
         <?php if ($graphene_settings['google_search'] === true && !empty($graphene_settings['google_search_form']) && ($search_box_location = $graphene_settings['search_box_location']) && $search_box_location == 'nav_bar'): ?>
            	<div id="google_search">
                	<?php echo $graphene_settings['google_search_form']; ?>
                </div>
            <?php endif; ?>     
        </div>
		
        <?php
        /* Secondary menu */
        $args = array(
            'container' => 'div',
			'container_id' => 'secondary-menu-wrap',
            'menu_id' => 'secondary-menu',
            'menu_class' => 'menu clearfix',
            'fallback_cb' => 'none',
            'depth' => 5,
            'theme_location' => 'secondary-menu',
        );
        wp_nav_menu(apply_filters('graphene_secondary_menu_args', $args));
        ?>

        <?php do_action('graphene_top_menu'); ?>

    </div>
    <?php } ?>

    <?php
        if ($post)
            $post_id = $post->ID;
        else
            $post_id = false;
        $header_img = apply_filters('graphene_header_image', graphene_get_header_image($post_id));

        /*
         * Check if the page uses SSL and change HTTP to HTTPS if true 
         * 
         * Experimental. Let me know if there's any problem.
         */
        if (is_ssl() && stripos($header_img, 'https') === false) {
            $header_img = str_replace('http', 'https', $header_img);
        }

        // Gets the colour for header texts, or if we should display them at all
        if ('blank' == get_theme_mod('header_textcolor', HEADER_TEXTCOLOR) || '' == get_theme_mod('header_textcolor', HEADER_TEXTCOLOR))
            $style = ' style="display:none;"';
        else
            $style = ' style="color:#' . get_theme_mod('header_textcolor', HEADER_TEXTCOLOR) . ';"';
    ?>
    <?php if(!$graphene_settings['disable_header']){ ?>
    	
        <?php $border_radius = 'border-top-left-radius:'.$graphene_settings['round_corners_topleft'].'px;border-top-right-radius:'.$graphene_settings['round_corners_topright'].'px;border-bottom-left-radius:'.$graphene_settings['round_corners_bottomleft'].'px;border-bottom-right-radius:'.$graphene_settings['round_corners_bottomright'].'px;'; ?>
    
        <div id="header" style="
        <?php if(!empty($header_img) && @fopen($header_img,'r')){?>
            background-image:url(<?php echo $header_img; ?>);
        <?php }else{ ?>
        background:<?php echo $graphene_settings['header_color']; ?>;
        <?php } ?>
        <?php echo $border_radius; ?>;">
            <?php if ($graphene_settings['link_header_img']) : ?>
            <a href="<?php echo home_url(); ?>" id="header_img_link" title="<?php esc_attr_e('Go back to the front page', 'graphene'); ?>">&nbsp;</a>
            <?php endif; ?>
            
            <?php /* Header widget area */
            if ($graphene_settings['enable_header_widget'] && is_active_sidebar('header-widget-area')) {
                
                $style = 'style="';
                if(!empty($graphene_settings['logomargin_left']))
                    $style .='margin-left:'.$graphene_settings['logomargin_left'].'px;'; 
                if(!empty($graphene_settings['logomargin_top']))
                    $style .='margin-top:'.$graphene_settings['logomargin_top'].'px;'; 
                $style .='"';
                
                echo '<div class="header-widget" '.$style.'>';
                dynamic_sidebar('header-widget-area');
                echo '</div>';
            }
            ?>
            
            <?php /* The site title and description */ 
                if (is_singular() && !is_front_page()) { 
                    $title_tag = 'h2';
                    $desc_tag = 'h3';
                } else {
                    $title_tag = 'h1';
                    $desc_tag = 'h2';
                }
            ?>
                <?php echo '<'.$title_tag; ?> <?php echo $style; ?> class="header_title"><a <?php echo $style; ?> href="<?php echo home_url(); ?>" title="<?php esc_attr_e('Go back to the front page', 'graphene'); ?>"><?php bloginfo('name'); ?></a></<?php echo $title_tag; ?>>
                <?php echo '<'.$desc_tag; ?> <?php echo $style; ?> class="header_desc"><?php bloginfo('description'); ?></<?php echo $desc_tag; ?>>
            <?php do_action('graphene_header'); ?>
            
            <?php if ($graphene_settings['show_headerads']) { ?>
            	<div style="float:right;margin-right:<?php echo $graphene_settings['rightposition_headerads']; ?>px;margin-top:<?php echo $graphene_settings['topposition_headerads']; ?>px; clear:both;">
                	<?php echo $graphene_settings['headerads_code']; ?>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
    
    <?php if($graphene_settings['menu_position'] == 'bottom' && $graphene_settings['menu_disable'] === false){ ?>
    <div id="nav">
        <?php /* The navigation menu */ ?>
        <div id="header-menu-wrap">
			<?php
            /* Header menu */
            $args = array(
                'container' => '',
                'menu_id' => 'header-menu',
                'menu_class' => 'menu clearfix',
                'fallback_cb' => 'graphene_default_menu',
                'depth' => 5,
                'theme_location' => 'Header Menu',
            );
			if ( ! $graphene_settings['disable_menu_desc'] )
				$args = array_merge( $args, array( 'walker' => new Graphene_Description_Walker() ) );
            wp_nav_menu(apply_filters('graphene_header_menu_args', $args)); ?>
        
			<?php if ($graphene_settings['google_search'] === false && ($search_box_location = $graphene_settings['search_box_location']) && $search_box_location == 'nav_bar') : ?>
                <div id="top_search">
                    <?php get_search_form(); ?>
                    <?php do_action('graphene_nav_search'); ?>
                </div>
            <?php endif; ?>
            <?php if ($graphene_settings['google_search'] === true && !empty($graphene_settings['google_search_form']) && ($search_box_location = $graphene_settings['search_box_location']) && $search_box_location == 'nav_bar'): ?>
            	<div id="top_search">
                	<?php echo $graphene_settings['google_search_form']; ?>
                </div>
            <?php endif; ?>
        
        </div>
		
        <?php
        /* Secondary menu */
        $args = array(
            'container' => 'div',
			'container_id' => 'secondary-menu-wrap',
            'menu_id' => 'secondary-menu',
            'menu_class' => 'menu clearfix',
            'fallback_cb' => 'none',
            'depth' => 5,
            'theme_location' => 'secondary-menu',
        );
        wp_nav_menu(apply_filters('graphene_secondary_menu_args', $args));
        ?>
        
        <div class="menu-bottom-shadow">&nbsp;</div>


        <?php do_action('graphene_top_menu'); ?>

    </div>
    <?php } ?>

    <?php do_action('graphene_before_content'); ?>

    <div id="content" class="clearfix hfeed">
    	<?php if ($graphene_settings['show_headerafterads']) { ?>
            <div style="width:728px;height:auto; margin:0 auto;">
                <?php echo $graphene_settings['headerafterads_code']; ?>
            </div>
        <?php } ?>
    
        <?php do_action('graphene_before_content-main'); ?>
        
        <?php
        
            /* Sidebar1 on the left side? */            
            if ( in_array(graphene_column_mode(), array('two-col-right', 'three-col-right', 'three-col-center')) ){
                get_sidebar();                
            }
            /* Sidebar2 on the left side? */
            if ( graphene_column_mode() == 'three-col-right' ){
                get_sidebar('two');
            }            
        
        ?>
        
        <div id="content-main" class="clearfix">
        <?php do_action('graphene_top_content'); ?>