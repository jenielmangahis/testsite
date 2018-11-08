<?php
/**
 * This function generates the theme's FAQ page in WordPress administration.
 *
 * @package WordPress
 * @subpackage Crystal
 * @since Crystal 1.1.3
*/
function graphene_faq(){ ?>
	<div class="wrap">
    	<div class="icon32" id="icon-themes"><br /></div>
        <h2><?php _e("Crystal's Frequently Asked Questions", 'graphene'); ?></h2>
        <ol>
        	<li>
            	<p><strong><?php _e("What license types do you have?", 'graphene'); ?></strong></p>
                <p><?php _e("We currently have single user and developer rights to Crystal Theme and each comes with unlimited installation and use with any monthly or hidden fees. There is no Master Resale Right (MRR) and Private Label Right (PLR) to Crystal Theme.", 'graphene'); ?></p>
            </li>
            <li>
            	<p><strong><?php _e("What rights of usage are available?", 'graphene'); ?></strong></p>
                <p><?php _e("There is no Master Resale Right (MRR) and Private Label Right (PLR) to Crystal Theme. Therefore, you can sell or distribute Crystal Theme in any form or modify Crystal Theme to satisfy your own use without right from the copyright owner.", 'graphene'); ?></p>
            </li>
        	<li>
            	<p><strong><?php _e("What are the requirements to run Crystal Theme?", 'graphene'); ?></strong></p>
                <p><?php _e("It is compatible with Safari, Firefox, Chrome or IE8+", 'graphene'); ?></p>
                <p><?php _e("PHP 5.2 or higher.", 'graphene'); ?></p>
                <p><?php _e("Wordpress 3.0 or higher.", 'graphene'); ?></p>
                <p><?php _e("MySQL 4.0 or higher.", 'graphene'); ?></p>
            </li>
            <li>
            	<p><strong><?php _e("Is Crystal Theme SEO friendly?", 'graphene'); ?></strong></p>
                <p><?php _e("Yes, Crystal Theme is very SEO friendly Wordpress theme, built with recent Google algorithm updates in mind.", 'graphene'); ?></p>
            </li>
            <li>
            	<p><strong><?php _e("What should I do next after downloading this theme?", 'graphene'); ?></strong></p>
                <p><?php _e("You need to install Crystal Theme on your Wordpress site. Detailed information on how to install and set it up has been downloaded with the theme.", 'graphene'); ?></p>
            </li>
            <li>
            	<p><strong><?php _e("Can I remove or edit the footer attribution link?", 'graphene'); ?></strong></p>
                <p><?php _e("Yes. You change or completely remove the attribution link.", 'graphene'); ?></p>
            </li>
            <li>
            	<p><strong><?php _e("Is the theme compatible with this plugin or that plugin?", 'graphene'); ?></strong></p>
                <p><?php _e("I don't know. With so many plugins available for Wordpress, there's no way that I (or anybody else for that matter) can test for compatibility for all of them. Having said that, the theme is built with all the necessary Wordpress components included with it, so chances are most plugins will be compatible with the theme.", 'graphene'); ?></p>
                <p><?php _e("My suggestion is to just install the plugin and try it. If you stumble into problem, ask for support from the plugin author first. If the plugin author says that it's a problem with the theme, you know where to find support.", 'graphene'); ?></p>
            </li>
            <li>
            	<p><strong><?php _e("The post's featured image is replacing my header image. Help!", 'graphene'); ?></strong></p>
                <p><?php _e("This is actually one of the theme's features, based on the feature in the default TwentyTen theme. Any featured image that has a size of greater than or equal to the theme's header image size (960 x 198 pixels) will replace the header image when the post/page that featured image is assigned to is being displayed. It enables you to have different header image for different posts and/or pages.", 'graphene'); ?></p>
                <p><?php _e("If you want to disable this feature, simply tick the Disable Featured Image replacing header image option in the <a href='".get_bloginfo('url')."/wp-admin/themes.php?page=graphene_options'>Crystal Options</a> page, under Display > Header Display Options.", 'graphene'); ?></p>
            </li>
            <li>
            	<p><strong><?php _e("Where should I go for the theme's support?", 'graphene'); ?></strong></p>
                <p><?php _e("Please direct all support requests for the theme to the theme's Support Email or Forum.", 'graphene'); ?></p>
                <p><strong><?php _e("More questions? Please check out our FAQ Section of our Support Forum at <a href=\"http://crystaltheme.com/forum\" target=\"_blank\">http://crystaltheme.com/forum</a>",'graphene'); ?></strong></p>
            </li>
        </ol>
    </div>
<?php
}
?>