<?
	// HOME
	$app->get('/', function() use ($app) {
		$boxes = array();
		$boxes["box"] = array();
		$books = array();

		// DATA FOLDER
		$dirPath = '../data';
  	$booksList = array_diff(scandir($dirPath), array('..', '.'));

		// Iteration for books
		foreach ($booksList as $book) {
			$bookContent = array();
			$bookContent["author"] = "";

			// OPEN EACH BOOK FOUND IN FOLDER DATA
			$handle = fopen("../data/".$book,"r");
			
			if($handle) {
				$content = "";

				// ITERATE EACH LINE IN FILE
				while(!feof($handle)) {
					$line = fgets($handle, 1024);
					$content .= $line;

					// AUTHOR
					if(strstr($line,'field-author')) {
						preg_match_all('/<a href=\"(.*|field\-author)\">(.*)<\/a>/',$line,$author,PREG_SET_ORDER);
						$bookContent["author"] = $author[0][2];
					}
				}
			}
			
			// CLOSING FILE
			fclose($handle);
						
			// SEARCH POUNDS
			preg_match_all("/Weight:<\/b> ([0-9][.][0-9])/", $content, $pounds, PREG_SET_ORDER);
			$bookContent["shipping_weight"] = $pounds[0][1]." pounds";
			
			// PRICES
			preg_match_all("/([$][0-9]*[,]*[.][0-9]{2})/", $content, $prices, PREG_SET_ORDER);
			$bookContent["price"] = $prices[0][0]." USD";
			
			// BOOK TITLE
			preg_match_all('/<span id=\"btAsinTitle\"(.*?)span>/',$content,$title,PREG_SET_ORDER);		
			$bookContent["title"] = trim(preg_replace('/(<([^>]+)>|>|<\/)/','',$title[0][1]));
			
			// ISBN-10
			preg_match_all("/ISBN\-10:<\/b> (\d\w+)/", $content, $isbn10, PREG_SET_ORDER);
			$bookContent["isbn-10"] = trim($isbn10[0][1]);

			array_push($books,$bookContent);
		}

		$boxes = buildBoxes($boxes,$books);

  	$app->response()->header("Content-Type", "application/json");
		echo json_encode($boxes);
  });

	function saveInABox($boxes,$book) {
		$boxId = 1;

		$boxes = buildBoxes($boxes,$book);

		return $boxes;
  }

  function buildBox($boxId,$book) {
  	$box = array();
  	$box["id"] = $boxId;
		$box["content"] = array();
		$box["totalWeight"] = 0;

  	return $box;
  }

  function buildBoxes($boxes,$books) {
  	$totalWeight = 0;
  	$totalBoxesNeeded = 0;
  	$maxWeightPerBoxes = 10;

  	foreach ($books as $bookId => $book) {
  		$totalWeight += $book["shipping_weight"];
  	}

  	$totalBoxesNeeded = ceil($totalWeight/$maxWeightPerBoxes);

  	$i=0;
  	while ($i <= $totalBoxesNeeded) {
		  buildBox();
		  $i++;
		}

 		return $boxes;
  }
?>