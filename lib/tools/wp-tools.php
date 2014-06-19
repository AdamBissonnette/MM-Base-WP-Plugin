<?php
if (!function_exists('createMMFormField')) {
	function createMMFormField($label, $name, $value, $type, $options=null)
	{
		$output = '';
		$field = '';
		$useField = true;

		switch ($type)
		{
			case 'text':
				$field = createMMInput($label, $value, $type, $options);
			break;
			case 'textarea':
				$field = createMMTextArea($label, $value, $options);
			break;
			case 'select':
				$field = createMMSelect($label, $value, $options);
			break;
			case 'editor':
				$useField = false;
			break;
		}

		echo '<div class="control-group">' .
						'<label class="control-label" for="' . $label . '">' . $name . '</label>' .
						'<div class="controls">';

		if ($useField)
		{
			echo $field;
		}
		else
		{
			wp_editor( $value, $label, $settings = array() );
		}

		echo '</div></div>';
					
		//return $output;
	}

	function merge_MM_options($pairs, $atts) {
	    $atts = (array)$atts;
	    $out = array();
	    foreach($pairs as $name => $default) {
	            if ( array_key_exists($name, $atts) )
	                    $out[$name] = $atts[$name];
	            else
	                    $out[$name] = $default;
	    }
	    return $out;
	}

	function createMMInput($label, $value, $type="text", $options = null)
	{
		extract( merge_MM_options(
			array("class" => "", "placeholder" => "", "note" => ""), $options)
		);

		$output = sprintf('<input type="%s" id="%s" class="%s" name="%s" value="%s" placeholder="%s" />', $type,
			 $label, //id
			 $class,
			 $label, //name
			 stripslashes($value), //value
			 $placeholder
		);
		
		if (isset($note)) {
			$output .= sprintf('<p class="help-block">%s</p>', $note);
		}
		
		return $output;
	}

	function createMMTextArea($label, $value, $options = null)
	{
		extract( merge_MM_options(
			array("class" => "", "placeholder" => "", "rows" => 3, "note" => ""), $options)
		);

		$output = sprintf('<textarea id="%s" class="%s" rows="%s" name="%s" placeholder="%s">%s</textarea>', 
			 $label, //id
			 $class,
	 		 $rows,
			 $label, //name
			 $placeholder,
			 stripslashes($value) //value
		);
		
		if ($note) {
			$output .= sprintf('<p class="help-block">%s</p>', $note);
		}
		
		return $output;
	}

	function createMMSelect($label, $value, $options)
	{
		return createMMSelectOptionsFromArray($label, $value, $options);
	}

	function createMMSelectOptionsFromArray($label, $selectedKey, $options)
	{
		extract( merge_MM_options(
			array("class" => "", "placeholder" => "", "note" => "", "data" => array()), $options)
		);
		
		$output = sprintf('<select id="%s" class="%s" name="%s">', $label, $class, $label);
		$optionTemplate = '<option value="%s"%s>%s</option>\n';
		$output .= sprintf($optionTemplate, "", "", $placeholder);

		foreach ($data as $key => $value)
		{
			if ($selectedKey == $key)
			{
				$output .= sprintf($optionTemplate, $key, ' selected', $value);
			}
			else
			{
				$output .= sprintf($optionTemplate, $key, '', $value);
			}
		}
		
		$output .= '</select>';
		
		if ($note) {
			$output .= sprintf('<p class="help-block">%s</p>', $note);
		}
		
		return $output;
	}

	function getMMCategorySelectArray()
	{
		$categories = get_categories(array('hide_empty' => 0));
		
		$catArray = array();
		foreach ($categories as $category)
		{
			$catArray[$category->term_id] = $category->cat_name;
		}
		
		return $catArray;
	}

	function getMMPagesSelectArray()
	{
		return getMMTaxonomySelectArray('page');
	}

	function getMMPostsSelectArray()
	{
		return getMMTaxonomySelectArray('post');
	}

	function getMMTaxonomySelectArray($taxonomy)
	{
		global $wp;
		$args = array('post_type' => $taxonomy);
		$posts = get_posts($args);
		
		$postArray = array();
		foreach ($posts as $post)
		{
			$postArray[$post->ID] = $post->post_title;
		}

		return $postArray;
	}

	function getMMFontAwesomeSelectArray()
	{
		return array('glass' => 'glass (&#xf000;)', 'music' => 'music (&#xf001;)', 'search' => 'search (&#xf002;)', 'envelope-alt' => 'envelope-alt (&#xf003;)', 'heart' => 'heart (&#xf004;)', 'star' => 'star (&#xf005;)', 'star-empty' => 'star-empty (&#xf006;)', 'user' => 'user (&#xf007;)', 'film' => 'film (&#xf008;)', 'th-large' => 'th-large (&#xf009;)', 'th' => 'th (&#xf00a;)', 'th-list' => 'th-list (&#xf00b;)', 'ok' => 'ok (&#xf00c;)', 'remove' => 'remove (&#xf00d;)', 'zoom-in' => 'zoom-in (&#xf00e;)', 'zoom-out' => 'zoom-out (&#xf010;)', 'off' => 'off (&#xf011;)', 'signal' => 'signal (&#xf012;)', 'cog' => 'cog (&#xf013;)', 'trash' => 'trash (&#xf014;)', 'home' => 'home (&#xf015;)', 'file-alt' => 'file-alt (&#xf016;)', 'time' => 'time (&#xf017;)', 'road' => 'road (&#xf018;)', 'download-alt' => 'download-alt (&#xf019;)', 'download' => 'download (&#xf01a;)', 'upload' => 'upload (&#xf01b;)', 'inbox' => 'inbox (&#xf01c;)', 'play-circle' => 'play-circle (&#xf01d;)', 'repeat' => 'repeat (&#xf01e;)', 'refresh' => 'refresh (&#xf021;)', 'list-alt' => 'list-alt (&#xf022;)', 'lock' => 'lock (&#xf023;)', 'flag' => 'flag (&#xf024;)', 'headphones' => 'headphones (&#xf025;)', 'volume-off' => 'volume-off (&#xf026;)', 'volume-down' => 'volume-down (&#xf027;)', 'volume-up' => 'volume-up (&#xf028;)', 'qrcode' => 'qrcode (&#xf029;)', 'barcode' => 'barcode (&#xf02a;)', 'tag' => 'tag (&#xf02b;)', 'tags' => 'tags (&#xf02c;)', 'book' => 'book (&#xf02d;)', 'bookmark' => 'bookmark (&#xf02e;)', 'print' => 'print (&#xf02f;)', 'camera' => 'camera (&#xf030;)', 'font' => 'font (&#xf031;)', 'bold' => 'bold (&#xf032;)', 'italic' => 'italic (&#xf033;)', 'text-height' => 'text-height (&#xf034;)', 'text-width' => 'text-width (&#xf035;)', 'align-left' => 'align-left (&#xf036;)', 'align-center' => 'align-center (&#xf037;)', 'align-right' => 'align-right (&#xf038;)', 'align-justify' => 'align-justify (&#xf039;)', 'list' => 'list (&#xf03a;)', 'indent-left' => 'indent-left (&#xf03b;)', 'indent-right' => 'indent-right (&#xf03c;)', 'facetime-video' => 'facetime-video (&#xf03d;)', 'picture' => 'picture (&#xf03e;)', 'pencil' => 'pencil (&#xf040;)', 'map-marker' => 'map-marker (&#xf041;)', 'adjust' => 'adjust (&#xf042;)', 'tint' => 'tint (&#xf043;)', 'edit' => 'edit (&#xf044;)', 'share' => 'share (&#xf045;)', 'check' => 'check (&#xf046;)', 'move' => 'move (&#xf047;)', 'step-backward' => 'step-backward (&#xf048;)', 'fast-backward' => 'fast-backward (&#xf049;)', 'backward' => 'backward (&#xf04a;)', 'play' => 'play (&#xf04b;)', 'pause' => 'pause (&#xf04c;)', 'stop' => 'stop (&#xf04d;)', 'forward' => 'forward (&#xf04e;)', 'fast-forward' => 'fast-forward (&#xf050;)', 'step-forward' => 'step-forward (&#xf051;)', 'eject' => 'eject (&#xf052;)', 'chevron-left' => 'chevron-left (&#xf053;)', 'chevron-right' => 'chevron-right (&#xf054;)', 'plus-sign' => 'plus-sign (&#xf055;)', 'minus-sign' => 'minus-sign (&#xf056;)', 'remove-sign' => 'remove-sign (&#xf057;)', 'ok-sign' => 'ok-sign (&#xf058;)', 'question-sign' => 'question-sign (&#xf059;)', 'info-sign' => 'info-sign (&#xf05a;)', 'screenshot' => 'screenshot (&#xf05b;)', 'remove-circle' => 'remove-circle (&#xf05c;)', 'ok-circle' => 'ok-circle (&#xf05d;)', 'ban-circle' => 'ban-circle (&#xf05e;)', 'arrow-left' => 'arrow-left (&#xf060;)', 'arrow-right' => 'arrow-right (&#xf061;)', 'arrow-up' => 'arrow-up (&#xf062;)', 'arrow-down' => 'arrow-down (&#xf063;)', 'share-alt' => 'share-alt (&#xf064;)', 'resize-full' => 'resize-full (&#xf065;)', 'resize-small' => 'resize-small (&#xf066;)', 'plus' => 'plus (&#xf067;)', 'minus' => 'minus (&#xf068;)', 'asterisk' => 'asterisk (&#xf069;)', 'exclamation-sign' => 'exclamation-sign (&#xf06a;)', 'gift' => 'gift (&#xf06b;)', 'leaf' => 'leaf (&#xf06c;)', 'fire' => 'fire (&#xf06d;)', 'eye-open' => 'eye-open (&#xf06e;)', 'eye-close' => 'eye-close (&#xf070;)', 'warning-sign' => 'warning-sign (&#xf071;)', 'plane' => 'plane (&#xf072;)', 'calendar' => 'calendar (&#xf073;)', 'random' => 'random (&#xf074;)', 'comment' => 'comment (&#xf075;)', 'magnet' => 'magnet (&#xf076;)', 'chevron-up' => 'chevron-up (&#xf077;)', 'chevron-down' => 'chevron-down (&#xf078;)', 'retweet' => 'retweet (&#xf079;)', 'shopping-cart' => 'shopping-cart (&#xf07a;)', 'folder-close' => 'folder-close (&#xf07b;)', 'folder-open' => 'folder-open (&#xf07c;)', 'resize-vertical' => 'resize-vertical (&#xf07d;)', 'resize-horizontal' => 'resize-horizontal (&#xf07e;)', 'bar-chart' => 'bar-chart (&#xf080;)', 'twitter-sign' => 'twitter-sign (&#xf081;)', 'facebook-sign' => 'facebook-sign (&#xf082;)', 'camera-retro' => 'camera-retro (&#xf083;)', 'key' => 'key (&#xf084;)', 'cogs' => 'cogs (&#xf085;)', 'comments' => 'comments (&#xf086;)', 'thumbs-up-alt' => 'thumbs-up-alt (&#xf087;)', 'thumbs-down-alt' => 'thumbs-down-alt (&#xf088;)', 'star-half' => 'star-half (&#xf089;)', 'heart-empty' => 'heart-empty (&#xf08a;)', 'signout' => 'signout (&#xf08b;)', 'linkedin-sign' => 'linkedin-sign (&#xf08c;)', 'pushpin' => 'pushpin (&#xf08d;)', 'external-link' => 'external-link (&#xf08e;)', 'signin' => 'signin (&#xf090;)', 'trophy' => 'trophy (&#xf091;)', 'github-sign' => 'github-sign (&#xf092;)', 'upload-alt' => 'upload-alt (&#xf093;)', 'lemon' => 'lemon (&#xf094;)', 'phone' => 'phone (&#xf095;)', 'check-empty' => 'check-empty (&#xf096;)', 'bookmark-empty' => 'bookmark-empty (&#xf097;)', 'phone-sign' => 'phone-sign (&#xf098;)', 'twitter' => 'twitter (&#xf099;)', 'facebook' => 'facebook (&#xf09a;)', 'github' => 'github (&#xf09b;)', 'unlock' => 'unlock (&#xf09c;)', 'credit-card' => 'credit-card (&#xf09d;)', 'rss' => 'rss (&#xf09e;)', 'hdd' => 'hdd (&#xf0a0;)', 'bullhorn' => 'bullhorn (&#xf0a1;)', 'bell' => 'bell (&#xf0a2;)', 'certificate' => 'certificate (&#xf0a3;)', 'hand-right' => 'hand-right (&#xf0a4;)', 'hand-left' => 'hand-left (&#xf0a5;)', 'hand-up' => 'hand-up (&#xf0a6;)', 'hand-down' => 'hand-down (&#xf0a7;)', 'circle-arrow-left' => 'circle-arrow-left (&#xf0a8;)', 'circle-arrow-right' => 'circle-arrow-right (&#xf0a9;)', 'circle-arrow-up' => 'circle-arrow-up (&#xf0aa;)', 'circle-arrow-down' => 'circle-arrow-down (&#xf0ab;)', 'globe' => 'globe (&#xf0ac;)', 'wrench' => 'wrench (&#xf0ad;)', 'tasks' => 'tasks (&#xf0ae;)', 'filter' => 'filter (&#xf0b0;)', 'briefcase' => 'briefcase (&#xf0b1;)', 'fullscreen' => 'fullscreen (&#xf0b2;)', 'group' => 'group (&#xf0c0;)', 'link' => 'link (&#xf0c1;)', 'cloud' => 'cloud (&#xf0c2;)', 'beaker' => 'beaker (&#xf0c3;)', 'cut' => 'cut (&#xf0c4;)', 'copy' => 'copy (&#xf0c5;)', 'paper-clip' => 'paper-clip (&#xf0c6;)', 'save' => 'save (&#xf0c7;)', 'sign-blank' => 'sign-blank (&#xf0c8;)', 'reorder' => 'reorder (&#xf0c9;)', 'list-ul' => 'list-ul (&#xf0ca;)', 'list-ol' => 'list-ol (&#xf0cb;)', 'strikethrough' => 'strikethrough (&#xf0cc;)', 'underline' => 'underline (&#xf0cd;)', 'table' => 'table (&#xf0ce;)', 'magic' => 'magic (&#xf0d0;)', 'truck' => 'truck (&#xf0d1;)', 'pinterest' => 'pinterest (&#xf0d2;)', 'pinterest-sign' => 'pinterest-sign (&#xf0d3;)', 'google-plus-sign' => 'google-plus-sign (&#xf0d4;)', 'google-plus' => 'google-plus (&#xf0d5;)', 'money' => 'money (&#xf0d6;)', 'caret-down' => 'caret-down (&#xf0d7;)', 'caret-up' => 'caret-up (&#xf0d8;)', 'caret-left' => 'caret-left (&#xf0d9;)', 'caret-right' => 'caret-right (&#xf0da;)', 'columns' => 'columns (&#xf0db;)', 'sort' => 'sort (&#xf0dc;)', 'sort-down' => 'sort-down (&#xf0dd;)', 'sort-up' => 'sort-up (&#xf0de;)', 'envelope' => 'envelope (&#xf0e0;)', 'linkedin' => 'linkedin (&#xf0e1;)');
	}
}

?>