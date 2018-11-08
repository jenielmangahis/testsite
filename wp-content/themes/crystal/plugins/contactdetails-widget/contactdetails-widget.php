<?php

class ContactDetailsWidget extends WP_Widget
{
  function ContactDetailsWidget()
  {
    $widget_ops = array('classname' => 'ContactDetailsWidget', 'description' => 'Widget for display contact details' );
    $this->WP_Widget('ContactDetailsWidget', 'Crystal Contact Details', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '','telephone'=>'','openinghoursmonfri'=>'','openinghourssat'=>'','openinghourssun'=>'','address'=>'','telephonecolor'=>'#FF0000','boldlabel'=>'',
														 'openinghourscolor'=>'#FF0000','addresscolor'=>'#FF0000') );
    $title = $instance['title'];
	$telephone = $instance['telephone'];
	$openinghoursmonfri = $instance['openinghoursmonfri'];
	$openinghourssat = $instance['openinghourssat'];
	$openinghourssun = $instance['openinghourssun'];
	$address = $instance['address'];
	$telephonecolor = $instance['telephonecolor'];
	$boldlabel = $instance['boldlabel'];
	$openinghourscolor = $instance['openinghourscolor'];
	$addresscolor = $instance['addresscolor'];

?>
  <p>
  <label for="<?php echo $this->get_field_id('title'); ?>">Title: 
  	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
  </label>
  </p>
  <p>
  <label for="<?php echo $this->get_field_id('telephone'); ?>">Telephone: 
  	<input class="widefat" id="<?php echo $this->get_field_id('telephone'); ?>" name="<?php echo $this->get_field_name('telephone'); ?>" type="text" value="<?php echo attribute_escape($telephone); ?>" />
  </label>
  </p>
  <p>
  <label for="<?php echo $this->get_field_id('telephonecolor'); ?>">Telephone Color: 
  	<input class="widefat" id="<?php echo $this->get_field_id('telephonecolor'); ?>" name="<?php echo $this->get_field_name('telephonecolor'); ?>" type="text" value="<?php echo attribute_escape($telephonecolor); ?>" />
  </label>
  </p>
  <p>
  <label>Opening Hours:
  	<br />
  </label>
  <p>
    <label for="<?php echo $this->get_field_id('openinghoursmonfri'); ?>">Monday - Friday :
  	<input class="widefat" id="<?php echo $this->get_field_id('openinghoursmonfri'); ?>" name="<?php echo $this->get_field_name('openinghoursmonfri'); ?>" type="text" value="<?php echo attribute_escape($openinghoursmonfri); ?>" />
  	</label>
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('openinghourssat'); ?>">Saturday :
    <input class="widefat" id="<?php echo $this->get_field_id('openinghourssat'); ?>" name="<?php echo $this->get_field_name('openinghourssat'); ?>" type="text" value="<?php echo attribute_escape($openinghourssat); ?>" />
    </label>
  </p>
  <p>
    <label for="<?php echo $this->get_field_id('openinghourssun'); ?>">Sunday :
    <input class="widefat" id="<?php echo $this->get_field_id('openinghourssun'); ?>" name="<?php echo $this->get_field_name('openinghourssun'); ?>" type="text" value="<?php echo attribute_escape($openinghourssun); ?>" />
    </label>
  </p>
   <p>
  <label for="<?php echo $this->get_field_id('openinghourscolor'); ?>">Opening Hours Color: 
  	<input class="widefat" id="<?php echo $this->get_field_id('openinghourscolor'); ?>" name="<?php echo $this->get_field_name('openinghourscolor'); ?>" type="text" value="<?php echo attribute_escape($openinghourscolor); ?>" />
  </label>
  </p>
  <p>
  <label for="<?php echo $this->get_field_id('address'); ?>">Address:<br />
  	<textarea style="resize:none" id="<?php echo $this->get_field_id('address'); ?>" cols="27" rows="3" name="<?php echo $this->get_field_name('address'); ?>"><?php echo attribute_escape($address); ?></textarea>
  </label>
  </p>
  <p>
  <label for="<?php echo $this->get_field_id('addresscolor'); ?>">Address Color: 
  	<input class="widefat" id="<?php echo $this->get_field_id('addresscolor'); ?>" name="<?php echo $this->get_field_name('addresscolor'); ?>" type="text" value="<?php echo attribute_escape($addresscolor); ?>" />
  </label>
  </p>
  <p>
  <label>Bold Label:
    <input type="checkbox" name="<?php echo $this->get_field_name('boldlabel'); ?>" value="yes" <?php if($boldlabel == 'yes') echo 'checked="checked"'; ?>/>Yes
  </label>
  </p>

<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
	$instance['telephone'] = $new_instance['telephone'];
	$instance['openinghoursmonfri'] = $new_instance['openinghoursmonfri'];
	$instance['openinghourssat'] = $new_instance['openinghourssat'];
	$instance['openinghourssun'] = $new_instance['openinghourssun'];
	$instance['address'] = $new_instance['address'];
	$instance['telephonecolor'] = $new_instance['telephonecolor'];
	$instance['openinghourscolor'] = $new_instance['openinghourscolor'];
	$instance['addresscolor'] = $new_instance['addresscolor'];
	$instance['boldlabel'] = $new_instance['boldlabel'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;
	  
	  echo '<p>';
	  if($instance['boldlabel'] == 'yes')
	  echo '<strong>';
	  echo 'Telephone : ';
	  if($instance['boldlabel'] == 'yes')
	  echo '</strong>';
	  echo '<span style="color:'.$instance['telephonecolor'].';"><i>'.$instance['telephone'].'</i></span></p><br>';
	  if(!empty($instance['openinghoursmonfri']) || !empty($instance['openinghourssat']) || !empty($instance['openinghourssun'])){
	  echo '<p>';
	  if($instance['boldlabel'] == 'yes')
	  echo '<strong>';
	  echo 'Opening Hours';
	  if($instance['boldlabel'] == 'yes')
	  echo '</strong>';
	  echo '<br>';
	  	if(!empty($instance['openinghoursmonfri']))
	  		echo '<span style="color:'.$instance['openinghourscolor'].';"><i>Monday - Friday : '.$instance['openinghoursmonfri'].'</i></span><br>';
		if(!empty($instance['openinghourssat']))
			echo '<span style="color:'.$instance['openinghourscolor'].';"><i>Saturday : '.$instance['openinghourssat'].'</i></span><br>';
		if(!empty($instance['openinghourssun']))
			echo '<span style="color:'.$instance['openinghourscolor'].';"><i>Sunday : '.$instance['openinghourssun'].'</i></span>';
		echo '</p><br>';
	  }
	  echo '<p>';
	  if($instance['boldlabel'] == 'yes')
	  echo '<strong>';
	  echo 'Address : ';
	  if($instance['boldlabel'] == 'yes')
	  echo '</strong>';
	  echo '<span style="color:'.$instance['addresscolor'].'"><i>'.$instance['address'].'</i></span></p><br>';
 
    echo $after_widget;
  }
  
  private function getVariableFromUrl($url) {
	$variablesStart = strpos($url, "?") + 1;
	if (!$variablesStart) {
 		return(false);
 	}

	$variablesEnd = strpos($url,"#",$variablesStart);

	if ($variablesEnd) {

 		$getVariables = substr($url, $variablesStart, $variablesEnd - $variablesStart);

 	} else {

 		$getVariables = substr($url, $variablesStart);

 	}

 	$variableArray = explode("&", $getVariables);

 	foreach ($variableArray as $arraySet) {

	$nameAndValue = explode("=", $arraySet);

	$output[$nameAndValue[0]] = $nameAndValue[1];

	}

	return($output);

  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("ContactDetailsWidget");') );

?>