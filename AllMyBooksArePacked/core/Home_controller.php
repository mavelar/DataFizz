<?
	// HOME
	$app->get('/', function() use ($app) {
		$variables = array();
		$dirPath = '../data';
  	$booksList = array_diff(scandir($dirPath), array('..', '.'));
		$output = array();
		$output["box"] = array();
		$counter = 1;
		
		header('Content-Type: application/json');
		
		foreach ($booksList as $book) {
			$bookContent = array();
			$bookContent["id"] = $counter;
			$bookContent["content"] = array();
			
			$fp = fopen("../data/".$book,"r");
		
			$content = "";
		
			while(!feof($fp)) {
				$content .= fgets($fp, 1024);
			}
			
			fclose($fp);
			
			// POUNDS
			preg_match_all("/Weight:<\/b> ([0-9][.][0-9] \w+)/", $content, $pounds, PREG_SET_ORDER);
			$bookContent["totalWeight"] = $pounds[0][1];
			
			// PRICES
			preg_match_all("/([$][0-9]*[,]*[.][0-9]{2})/", $content, $prices, PREG_SET_ORDER);
			$bookContent["content"]["price"] = $prices[0][0]." USD";
			
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