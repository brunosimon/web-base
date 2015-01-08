<?php
	
	$lang = $data['lang'];
	$posts = $data['posts'];
	$collection = $data['collection'];
		
	if ($lang){
		$temp = $posts;
		$posts = [];

		foreach ($temp as $item){

			$post = array();

			foreach ($item as $prop=>$value){
				//echo $prop . '_' . $lang .'   /   ';			

				$propNoLang = explode('_', $prop)[0];

				if (isset($item[$prop . '_' . $lang]) && $item[$prop . '_' . $lang] != ''){
					$post[$prop] = $item[$prop . '_' . $lang];
				}
				else if (!isset($post[$propNoLang])) {
					$post[$prop] = $value;
				}

				if (isset($post[$prop]) && preg_match('/site:|src="/', $post[$prop])){
					$post[$prop] = replaceSrcAttributes($post[$prop], UPLOADS . '/');
				}

			}

			$post['path'] = $lang . '/' . strtolower($collection) . '/' . stringToNiceUrl($post['title']);

			array_push($posts, $post);
		}
	}

	echo json_encode($posts, true);

?>