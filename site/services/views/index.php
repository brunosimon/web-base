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

			setlocale(LC_TIME, $lang . "_" . strtoupper($lang));
			$date = explode(' ', strftime("%d %b %Y", $post['created']));

			$post['date'] = (object) array('day' => $date[0], 'month' => $date[1], 'year' => $date[2], 'formatted' => strftime("%d %b %Y", $post['created'] ));


			array_push($posts, $post);
		}
	}

	echo json_encode($posts, true);

?>