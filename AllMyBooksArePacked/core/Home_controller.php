<?
	// HOME
	$app->get('/', function() use ($app) {
		$variables = array();
		$dirPath = '../data';
  	$booksList = array_diff(scandir($dirPath), array('..', '.'));
		$output = array();
		$output["box"] = array();
		$counter = 1;
		
		foreach ($booksList as $book) {
			$bookContent = array();
			$bookContent["id"] = $counter;
			$bookContent["content"] = array();
			
			$fp = fopen("../data/".$book,"r");
		
			$content = "";
		
			while(!feof($fp)) {
				$line = fgets($fp, 1024);
				$content .= $line;
				
				// AUTHOR
				if(strstr($line,'field-author')) {
					preg_match_all('/<a href=\"(.*|field\-author)\">(.*)<\/a>/',$line,$author,PREG_SET_ORDER);
					$bookContent["content"]["author"] = $author[0][2];
				}
			}
			
			fclose($fp);
			
			preg_match_all("/Weight:<\/b> ([0-9][.][0-9] \w+)/", $content, $pounds, PREG_SET_ORDER);
			$bookContent["totalWeight"] = $pounds[0][1];
			$bookContent["content"]["shipping_weight"] = $pounds[0][1];
			
			// PRICES
			preg_match_all("/([$][0-9]*[,]*[.][0-9]{2})/", $content, $prices, PREG_SET_ORDER);
			$bookContent["content"]["price"] = $prices[0][0]." USD";
			
			// BOOK TITLE
			preg_match_all('/<span id=\"btAsinTitle\"(.*?)span>/',$content,$title,PREG_SET_ORDER);		
			$bookContent["content"]["title"] = trim(preg_replace('/(<([^>]+)>|>|<\/)/','',$title[0][1]));
			
			// ISBN-10
			preg_match_all("/ISBN\-10:<\/b> (\d\w+)/", $content, $isbn10, PREG_SET_ORDER);
			$bookContent["content"]["isbn-10"] = trim($isbn10[0][1]);
			
			array_push($output["box"],$bookContent);
			
			$counter++;
		}
		
		/*
		$variables = array(
			'currentpage' => 	'home',
			'properties' => array(
				'title' 	=>	'Servicios Especializados, Monitoreo Fuentes Fijas y Móviles, Auditoría Energética, Monitoreo Ocupacional'
			),
			'widgetsData' => 	$widgetsData,
			'sliderData'	=>	$sliderData,
			'projectsData'	=>	$projectsData
		);
		*/

  	$app->response()->header("Content-Type", "application/json");
		echo json_encode($output);
  });
?>