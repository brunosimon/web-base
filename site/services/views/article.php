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

			/*
			
			Utiliser http://php.net/manual/fr/function.strftime.php

			if ($prop == 'created'){
				$post['date'] = date($temp['created'])
			}*/
		}
	}

	echo json_encode($post, true);

?>