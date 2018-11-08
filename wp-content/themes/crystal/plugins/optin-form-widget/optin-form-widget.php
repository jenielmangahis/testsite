<?php

class OptinFormWidget extends WP_Widget
{
  function OptinFormWidget()
  {
    $widget_ops = array('classname' => 'OptinFormWidget', 'description' => 'Widget for optin forms' );
    $this->WP_Widget('OptinFormWidget', 'Crystal Optin Form Widget', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '','website'=>'aweber','webform_id'=>'','aweberlistname'=>'','redirect_url'=>'','campaign_name'=>'','client_id'=>'','listid'=>'',
														'formid'=>'','listid1'=>'','actionurl'=>'','submitbuttontxt'=>'Subscribe','subscribedesctxt'=>'','namefieldid'=>'','specialid'=>'','successurl'=>'','errorurl'=>'') );
    $title = $instance['title'];
    $website = $instance['website'];
	$webform_id = $instance['webform_id'];
	$aweberlistname = $instance['aweberlistname'];
	$redirect_url = $instance['redirect_url'];
	$campaign_name = $instance['campaign_name'];
	$client_id = $instance['client_id'];
	$listid = $instance['listid'];
	$specialid = $instance['specialid'];
	$formid = $instance['formid'];
	$listid1 = $instance['listid1'];
	$actionurl = $instance['actionurl'];
	$submitbuttontxt = $instance['submitbuttontxt'];
	$subscribedesctxt = $instance['subscribedesctxt'];
	$namefieldid = $instance['namefieldid'];
	$successurl = $instance['successurl'];
	$errorurl = $instance['errorurl'];

?>
  <p>
  <label for="<?php echo $this->get_field_id('title'); ?>">Title: 
  	<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" />
  </label>
  </p>
  <p>
  <label for="<?php echo $this->get_field_id('website'); ?>">Provider: 
    <select id="<?php echo $this->get_field_id('website'); ?>" name="<?php echo $this->get_field_name('website'); ?>">
    	<option value="aweber" <?php if(attribute_escape($website) == 'aweber')  echo 'selected="selected"'; ?>>Aweber</option>
        <option value="getresponse" <?php if(attribute_escape($website) == 'getresponse')  echo 'selected="selected"'; ?>>Getresponse</option>
        <!--<option value="infusionsoft" <?php //if(attribute_escape($website) == 'infusionsoft')  echo 'selected="selected"'; ?>>InfusionSoft</option>-->
        <option value="icontact" <?php if(attribute_escape($website) == 'icontact')  echo 'selected="selected"'; ?>>iContact</option>
        <option value="imnicamail" <?php if(attribute_escape($website) == 'imnicamail')  echo 'selected="selected"'; ?>>ImnicaMail</option>
        <option value="mailchimp" <?php if(attribute_escape($website) == 'mailchimp')  echo 'selected="selected"'; ?>>MAilChimp</option>
    </select>
  </label>
  </p>
  <p>
  <label for="<?php echo $this->get_field_id('submitbuttontxt'); ?>">Submit Button Text:
  	<input class="widefat" id="<?php echo $this->get_field_id('submitbuttontxt'); ?>" name="<?php echo $this->get_field_name('submitbuttontxt'); ?>" type="text" value="<?php echo attribute_escape($submitbuttontxt); ?>" />
  </label>
  </p>
  <p>
      <label for="<?php echo $this->get_field_id('subscribedesctxt'); ?>">Subscribe Form Text:
        <input class="widefat" id="<?php echo $this->get_field_id('subscribedesctxt'); ?>" name="<?php echo $this->get_field_name('subscribedesctxt'); ?>" type="text" value="<?php echo attribute_escape($subscribedesctxt); ?>" />
      </label>
      </p>
  <div>
  	  <h3>Aweber</h3>
      <p>
      <label for="<?php echo $this->get_field_id('webform_id'); ?>">Web Form ID: 
        <input class="widefat" id="<?php echo $this->get_field_id('webform_id'); ?>" name="<?php echo $this->get_field_name('webform_id'); ?>" type="text" value="<?php echo attribute_escape($webform_id); ?>" />
      </label>
      </p>
      <p>
      <label for="<?php echo $this->get_field_id('aweberlistname'); ?>">List Name: 
        <input class="widefat" id="<?php echo $this->get_field_id('aweberlistname'); ?>" name="<?php echo $this->get_field_name('aweberlistname'); ?>" type="text" value="<?php echo attribute_escape($aweberlistname); ?>" />
      </label>
      </p>
      <p>
      <label for="<?php echo $this->get_field_id('redirect_url'); ?>">Redirect Url: 
        <input class="widefat" id="<?php echo $this->get_field_id('redirect_url'); ?>" name="<?php echo $this->get_field_name('redirect_url'); ?>" type="text" value="<?php echo attribute_escape($redirect_url); ?>" />
      </label>
      </p>
  </div>
  <div>
  	<h3>Getresponse</h3>
  	 <p>
      <label for="<?php echo $this->get_field_id('campaign_name'); ?>">Your Getresponse Campaign Name : 
        <input class="widefat" id="<?php echo $this->get_field_id('campaign_name'); ?>" name="<?php echo $this->get_field_name('campaign_name'); ?>" type="text" value="<?php echo attribute_escape($campaign_name); ?>" />
      </label>
      </p>
  </div>
  <!--<div>
  	<h3>InfusionSoft</h3>
	<p>
      <label for="<?php //echo $this->get_field_id('hidden_code'); ?>">Hidden Code : 
        <input class="widefat" id="<?php //echo $this->get_field_id('hidden_code'); ?>" name="<?php echo $this->get_field_name('hidden_code'); ?>" type="text" value="<?php echo attribute_escape($hidden_code); ?>" />
      </label>
      </p>
  </div>-->
  <div>
  	  <h3>iContact</h3>
	  <p>
      <label for="<?php echo $this->get_field_id('client_id'); ?>">Client ID: 
        <input class="widefat" id="<?php echo $this->get_field_id('client_id'); ?>" name="<?php echo $this->get_field_name('client_id'); ?>" type="text" value="<?php echo attribute_escape($client_id); ?>" />
      </label>
      </p>
      <p>
      <label for="<?php echo $this->get_field_id('listid'); ?>">List ID: 
        <input class="widefat" id="<?php echo $this->get_field_id('listid'); ?>" name="<?php echo $this->get_field_name('listid'); ?>" type="text" value="<?php echo attribute_escape($listid); ?>" />
      </label>
      </p>
      <p>
      <label for="<?php echo $this->get_field_id('formid'); ?>">Form ID: 
        <input class="widefat" id="<?php echo $this->get_field_id('formid'); ?>" name="<?php echo $this->get_field_name('formid'); ?>" type="text" value="<?php echo attribute_escape($formid); ?>" />
      </label>
      </p>
      <p>
      <label for="<?php echo $this->get_field_id('specialid'); ?>">Special ID: 
        <input class="widefat" id="<?php echo $this->get_field_id('specialid'); ?>" name="<?php echo $this->get_field_name('specialid'); ?>" type="text" value="<?php echo attribute_escape($specialid); ?>" />
      </label>
      </p>
      <p>
      <label for="<?php echo $this->get_field_id('successurl'); ?>">Success URL (leave blank for default thank you page): 
        <input class="widefat" id="<?php echo $this->get_field_id('successurl'); ?>" name="<?php echo $this->get_field_name('successurl'); ?>" type="text" value="<?php echo attribute_escape($successurl); ?>" />
      </label>
      </p>
      <p>
      <label for="<?php echo $this->get_field_id('errorurl'); ?>">Error URL (leave blank for default error page): 
        <input class="widefat" id="<?php echo $this->get_field_id('errorurl'); ?>" name="<?php echo $this->get_field_name('errorurl'); ?>" type="text" value="<?php echo attribute_escape($errorurl); ?>" />
      </label>
      </p>
  </div>
  <div>
  	<h3>ImnicaMail</h3>
  	<p>
      <label for="<?php echo $this->get_field_id('listid1'); ?>">List ID: 
        <input class="widefat" id="<?php echo $this->get_field_id('listid1'); ?>" name="<?php echo $this->get_field_name('listid1'); ?>" type="text" value="<?php echo attribute_escape($listid1); ?>" />
      </label>
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('namefieldid'); ?>">Name Field ID: 
        <input class="widefat" id="<?php echo $this->get_field_id('namefieldid'); ?>" name="<?php echo $this->get_field_name('namefieldid'); ?>" type="text" value="<?php echo attribute_escape($namefieldid); ?>" />
      </label>
      </p>
  </div>
  <div>
  	<h3>MAilChimp</h3>
    <p>
      <label for="<?php echo $this->get_field_id('actionurl'); ?>">Form Action Url: 
        <input class="widefat" id="<?php echo $this->get_field_id('actionurl'); ?>" name="<?php echo $this->get_field_name('actionurl'); ?>" type="text" value="<?php echo attribute_escape($actionurl); ?>" />
      </label>
    </p>
  </div>

<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
	$instance['website'] = $new_instance['website'];
	$instance['webform_id'] = $new_instance['webform_id'];
	$instance['aweberlistname'] = $new_instance['aweberlistname'];
	$instance['redirect_url'] = $new_instance['redirect_url'];
	$instance['campaign_name'] = $new_instance['campaign_name'];
	$instance['specialid'] = $new_instance['specialid'];
	$instance['client_id'] = $new_instance['client_id'];
	$instance['listid'] = $new_instance['listid'];
	$instance['formid'] = $new_instance['formid'];
	$instance['listid1'] = $new_instance['listid1'];
	$instance['actionurl'] = $new_instance['actionurl'];
	$instance['submitbuttontxt'] = $new_instance['submitbuttontxt'];
	$instance['subscribedesctxt'] = $new_instance['subscribedesctxt'];
	$instance['namefieldid'] = $new_instance['namefieldid'];
	$instance['successurl'] = $new_instance['successurl'];
	$instance['errorurl'] = $new_instance['errorurl'];
    return $instance;
  }
 
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;
	  
	  switch($instance['website']){
	  	case 'aweber':
			$form = '';
			$form ='<form method="post" class="af-form-wrapper" action="http://www.aweber.com/scripts/addlead.pl" >';
			if(!empty($instance['subscribedesctxt']))
				$form .='<span>'.$instance['subscribedesctxt'].'</span><br><br>';
			$form .='<input type="hidden" name="meta_web_form_id" value="'.$instance['webform_id'].'" />
			<input type="hidden" name="meta_split_id" value="" />
			<input type="hidden" name="listname" value="'.$instance['aweberlistname'].'" />
			<input type="hidden" name="redirect" value="'.$instance['redirect_url'].'"/>
			<input type="hidden" name="meta_adtracking" value="homepage text bottom" />
			<input type="hidden" name="meta_message" value="1" />
			<input type="hidden" name="meta_required" value="name,email" />
			<input type="hidden" name="meta_tooltip" value="" />
			<p>Name :<br> <input type="text" name="name" value=""></p>
			<p>Email :<br> <input type="text" name="email" value=""></p>
			<input type="submit" name="submit" value="'.$instance['submitbuttontxt'].'">
			</form>';
			echo $form;
		break;
		case 'getresponse':
			$form = '';
			$form .= '<form action="http://www.getresponse.com/cgi-bin/add.cgi" method="post">';
			if(!empty($instance['subscribedesctxt']))
				$form .='<span>'.$instance['subscribedesctxt'].'</span><br><br>';
        	$form .= '<input type="hidden" name="custom_http_referer" id="custom_http_referer" value="'. $_SERVER['REQUEST_URI'] .'"/>';
			$form .= '<input type="hidden" name="campaign_name" id="campaign_name" value="'. $instance['campaign_name'] .'" />';
			$form .= '<label for="subscriber_name">Name :</label><br>';
            $form .= '<input id="subscriber_name" name="subscriber_name" type="text" value=""/><br>';
			$form .= '<label for="subscriber_email">Email :</label><br>';
        	$form .= '<input id="subscriber_email" name="subscriber_email" type="text" value=""/><br><br>';
			$form .= '<input type="submit" class="GRfh-In" value="'.$instance['submitbuttontxt'].'" />';
			$form .= '</form>';
			echo $form;
		break;
		case 'imnicamail':
			$form = '';
			$form = '<form action="http://www.imnicamail.com/v4/subscribe.php" method="post" accept-charset="UTF-8">';
			if(!empty($instance['subscribedesctxt']))
				$form .='<span>'.$instance['subscribedesctxt'].'</span><br><br>';
			if(!empty($instance['namefieldid'])) {
				$form .= '<div class="o-form-row">
			<label for="FormValue_CustomField'.$instance['namefieldid'].'">Name</label><br>
			<input name="FormValue_Fields[CustomField'.$instance['namefieldid'].']" value="" id="FormValue_CustomField'.$instance['namefieldid'].'" type="text">
			</div>';
			}
			$form .= '<div class="o-form-row">
			<label for="FormValue_EmailAddress">Email Address</label><br>
			<input name="FormValue_Fields[EmailAddress]" value="" id="FormValue_EmailAddress" type="text">
			</div>';
			$form .= '<input name="FormButton_Subscribe" value="'.$instance['submitbuttontxt'].'" id="FormButton_Subscribe" type="submit">
			<input name="FormValue_ListID" value="'.$instance['listid1'].'" type="hidden">
			<input name="FormValue_Command" value="Subscriber.Add" id="FormValue_Command" type="hidden">
			</form>';
			echo $form;
		break;
		case 'icontact':
			$form = '';
			$form .= '<form method="post" action="https://app.icontact.com/icp/signup.php">';
			if(!empty($instance['subscribedesctxt']))
				$form .='<span>'.$instance['subscribedesctxt'].'</span><br><br>';
			
			if(!empty($instance['successurl']))
			$form .='<input type="hidden" name="redirect" value="'.$instance['successurl'].'">';
			else
			$form .='<input type="hidden" name="redirect" value="http://www.icontact.com/www/signup/thanks.html">';
			
			if(!empty($instance['errorurl']))
			$form .='<input type="hidden" name="errorredirect" value="'.$instance['errorurl'].'">';
			else
			$form .='<input type="hidden" name="errorredirect" value="http://www.icontact.com/www/signup/error.html">';
			
			$form .='<label for="firstname">First Name:</label><br><input type="text" name="fields_fname">'. "<br>";
			$form .='<label for="lastname">Last Name:</label><br><input type="text" name="fields_lname">'. "<br><br>";
			$form .='<label for="email">* Email :</label><br><input type="text" name="fields_email">'. "<br>";
			$form .='<input type="hidden" name="listid" value="'.$instance['listid'].'">
					 <input type="hidden" name="specialid:'.$instance['listid'].'" value="'.$instance['specialid'].'">
					 <input type="hidden" name="clientid" value="'.$instance['client_id'].'">
					 <input type="hidden" name="formid" value="'.$instance['formid'].'">
					 <input type="hidden" name="reallistid" value="1">
					 <input type="hidden" name="doubleopt" value="0">';
			$form .='<input type="submit" name="Submit" value="'.$instance['submitbuttontxt'].'">';
			$form .='</form>';
			
			echo $form;
		break;
		case 'mailchimp':
			$form = '';
			$form .='<form action="'.$instance['actionurl'].'" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank">';
				if(!empty($instance['subscribedesctxt']))
					$form .='<span>'.$instance['subscribedesctxt'].'</span><br><br>';
			$form .='<label for="MERGE1">First Name</label><br>
					<input type="text" value="" size="25" id="MERGE1" name="MERGE1"><br>
					<label for="MERGE1">Last Name</label><br>
					<input type="text" value="" size="25" id="MERGE2" name="MERGE2"><br>
					<label for="mce-EMAIL">Email Address</label><br>
					<input type="email" value="" name="EMAIL" id="mce-EMAIL" required><br><br>
					<div class="clear"><input type="submit" value="'.$instance['submitbuttontxt'].'" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
					</form>';
			echo $form;
		break;
		/*case 'infusionsoft':
		    $form = '';
			$form .= '<form method="post" action="https://pcisecure.Infusionsoft.com/AddForms/processFormSecure.jsp">';
			if(!empty($instance['subscribedesctxt']))
				$form .='<span>'.$instance['subscribedesctxt'].'</span><br><br>';
			$form .='<input type="hidden" name="hidden_code" value="'.$instance['hidden_code'].'" />';
			$form .='<label for="Contact0FirstName">First Name:</label><br><input type="text" name="Contact0FirstName" />'. "<br>";
			$form .='<label for="Contact0LastName">Last Name:</label><br><input type="text" name="Contact0LastName" />'. "<br>";
			$form .='<label for="Contact0Email">* Email:</label><br><input type="text" name="Contact0Email" />'. "<br><br>";
			$form .='<input type="submit" value="'.$instance['submitbuttontxt'].'" />';
			$form .='</form>';
			
			echo $form;
		break;*/
	  }
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
add_action( 'widgets_init', create_function('', 'return register_widget("OptinFormWidget");') );

?>