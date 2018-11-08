<?php
class categoriesWidget extends WP_Widget {
	
        function categoriesWidget(){
                $widget_ops = array( 'classname' => 'Selective categories', 'description' => 'Show a list of Categories, with the ability to exclude categories' );
                $control_ops = array( 'id_base' => 'some-cats-widget' );
                $this->WP_Widget( 'some-cats-widget', 'Crystal Selective Catagories', $widget_ops, $control_ops );
        }
 
        function form ( $instance){
                $defaults = array( 'title' => 'Catagories', 'cats' => '' );
                $instance = wp_parse_args( (array) $instance, $defaults );
                ?>
                <p>
                        <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
                        <input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" style="width:100%;" />
                </p>
                <p>
                        <label for="<?php echo $this->get_field_id( 'cats' ); ?>">Categories to exclude(comma separated list of Category IDs, with no space between comma and IDs): </label>
                        <input id="<?php echo $this->get_field_id( 'cats' ); ?>" name="<?php echo $this->get_field_name( 'cats' ); ?>" value="<?php echo $instance['cats']; ?>" style="width:100%;" />
                </p>
                <?php
        }
 
        function update($new_instance, $old_instance) {
                $instance = $old_instance;
				$instance['title'] = strip_tags($new_instance['title']);
				$categories = explode(',',strip_tags($new_instance['cats']));
				
				$newcats = array();
				foreach($categories as $val){
					$val = (int)$val;
					if($val < 0){
						$newcats[] =  abs($val);
					}elseif($val > 0){
						$newcats[] =  str_replace(' ','',$val);
					}
				}

				$instance['cats'] = implode(',',$newcats);
				
                return $instance;
        }
 
        function widget($args, $instance){
                extract( $args );
                $title = apply_filters('widget_title', $instance['title'] );
                $cats = $instance['cats'];
                echo $before_widget;
                if ( $title )
                        echo $before_title . $title . $after_title;
                echo "<ul>";
                wp_list_categories("exclude=$cats&title_li=");
                echo "</ul>";
                echo $after_widget;
        }
 
}
 
function register_elcadia_widget(){
	register_widget('categoriesWidget');
}
 
add_action('widgets_init', 'register_elcadia_widget');
 
?>