<?php
	
	$lang = $data['lang'];
	$post = $data['post'];
	$collection = $data['collection'];

	if ($lang){
		$temp = $post;
		$post = [];

		foreach ($temp as $prop=>$value){
			//echo $prop . '_' . $lang .'   /   ';			

			$propNoLang = explode('_', $prop)[0];

			if (isset($temp[$prop . '_' . $lang]) && $temp[$prop . '_' . $lang] != ''){
				$post[$prop] = $temp[$prop . '_' . $lang];
			}
			else if (!isset($post[$propNoLang])) {
				$post[$prop] = $value;
			}

			if (isset($post[$prop]) && preg_match('/site:|src="/', $post[$prop])){
				$post[$prop] = replaceSrcAttributes($post[$prop], UPLOADS . '/');
			}

			/*
			
			Utiliser http://php.net/manual/fr/function.strftime.php

			if ($prop == 'created'){
				$post['date'] = date($temp['created'])
			}*/
		}

		setlocale(LC_TIME, $lang . "_" . strtoupper($lang));
		$date = explode(' ', strftime("%d %b %Y", $post['created']));

		$post['date'] = (object) array('day' => $date[0], 'month' => $date[1], 'year' => $date[2], 'formatted' => strftime("%d %b %Y", $post['created'] ));
	}

	echo json_encode($post, true);

?>