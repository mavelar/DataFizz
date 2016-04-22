<?
	// HOME
	$app->get('/', function() use ($app) {
		$GLOBALS["boxes"] = array();
		$GLOBALS["boxes"]["box"] = array();
		$GLOBALS["boxes"]["globalWeight"] = 0;
		$GLOBALS["boxes"]["totalItems"] = 0;
		$GLOBALS["books"] = array();

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

			array_push($GLOBALS["books"],$bookContent);
		}

		buildBoxes();
		fillOutBoxes();

  	$app->response()->header("Content-Type", "application/json");
		echo json_encode($GLOBALS["boxes"]);
  });

	function fillOutBoxes() {
		$GLOBALS["booksBoxed"] = -1;

		foreach ($GLOBALS["books"] as $bookId => $book) {
			saveBookInBox($bookId,$book);
		}
  }

  function saveBookInBox($bookId,$book) {
  		setBox($bookId,$book);
  }

  function setBox($bookId,$book) {
  	$newTotalWeight = 0;

  	foreach($GLOBALS["boxes"]["box"] as $boxId => $box) {
  		if($bookId!=$GLOBALS["booksBoxed"]) {
	  		$newTotalWeight = $box["totalWeight"]+$book["shipping_weight"];

	  		if($newTotalWeight<=10) {
	  			array_push($GLOBALS["boxes"]["box"][$boxId]["content"],$book);
	  			$GLOBALS["boxes"]["box"][$boxId]["totalWeight"]+=$book["shipping_weight"];

	  			$GLOBALS["booksBoxed"]++;
	  			$GLOBALS["boxes"]["totalItems"]++;
	  			$GLOBALS["boxes"]["globalWeight"] += $book["shipping_weight"];
	  		}
	  	}
  	}
  }

  function buildBox($boxId) {
  	$box = array();
  	$box["id"] = $boxId;
		$box["content"] = array();
		$box["totalWeight"] = 0;

  	return $box;
  }

  function buildBoxes() {
  	$totalWeight = 0;
  	$totalBoxesNeeded = 0;
  	$maxWeightPerBoxes = 10;

  	foreach ($GLOBALS["books"] as $bookId => $book) {
  		$totalWeight += $book["shipping_weight"];
  	}

  	$totalBoxesNeeded = ceil($totalWeight/$maxWeightPerBoxes);

  	$i=1;
  	while ($i <= $totalBoxesNeeded) {
		  $box = buildBox($i);
		  array_push($GLOBALS["boxes"]["box"],$box);
		  $i++;
		}
  }
?>