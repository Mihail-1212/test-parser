<?php

use PHPHtmlParser\Dom;


use Mamok\TestParser\DatabaseModel;
use Mamok\TestParser\Config;
use Mamok\TestParser\CurlController;
use Mamok\TestParser\ConsoleController;


define("URL_DOMAIN", "https://tender.rusal.ru");
define("URL_MAIN",	URL_DOMAIN . "/Tenders/Load");
define("URL_TENDER_DOMAIN", URL_DOMAIN);
define("URL_TENDER_FILE_DOMAIN", URL_TENDER_DOMAIN);


function createDataBaseInstance($dbConfig) {
	$host = $dbConfig['host'];
	$port = $dbConfig['port'];
	$database = $dbConfig['database'];
	$username = $dbConfig['username'];
	$password = $dbConfig['password'];
	$charset = $dbConfig['charset'];

	$dbInstance = new DatabaseModel($host, $port, $database, $username, $password, $charset);

    return $dbInstance;
}


function parseChildPage($contentPage) {
	$dom = new Dom;
	$dom->loadStr( htmlspecialchars_decode($contentPage) );

	// [data-field-name="Fields.RequestReceivingBeginDate"]
	$dateStartString = $dom->find('[data-field-name="Fields.RequestReceivingBeginDate"]')[0];

	$dateStartString = $dateStartString->text;
	$dateArray = preg_match("/(\d{2}.\d{2}.\d{4})/", $dateStartString, $dateStartMatches);

	$dateApplicationStart = $dateStartMatches[0];

	// *[@data-file-uid] a[@href]
	$fileMatches =  $dom->find('[data-file-uid] a.file-download-link');

	$files = array();

	foreach($fileMatches as $fileMatch) {
		// $fileMath = $fileMatch->link();

		$fileName = trim($fileMatch->text);
		$fileHref = URL_TENDER_FILE_DOMAIN . $fileMatch->getAttribute('href');

		$file = array(
			"name" => $fileName,
			"href" => $fileHref
		);

		array_push($files, $file);
	}

	return [
		'dateApplicationStart' => $dateApplicationStart,
		'files' => $files,
	];
}


function startApplication() {
	// Load config of application
	$config = Config::getConfig();

	// Init curl controller instance
	$curlController = new CurlController(URL_MAIN, "POST");

	// Get request POST data for page
	// $requestFieldsData = "limit=10000000&ClassifiersFieldData.SiteSectionType=bef4c544-ba45-49b9-8e91-85d9483ff2f6&";
	$requestFieldsData = "";
	foreach($config["requestFieldsData"] as $reqName => $reqVal) {
		$requestFieldsData .= $reqName . "=" . $reqVal . "&";
	}
	
	$curlController->setPostFields($requestFieldsData);

	$serverOutput = $curlController->exec();

	// close cURL resource, and free up system resources
	$curlController->closeConnection();

	// Decode response and get rows
	$serverOutput = json_decode($serverOutput);
	$dataRows = $serverOutput->{'Rows'};

	// Free up sys resources
	unset($serverOutput);

	// Init db  instance
	$dbInstance = createDataBaseInstance( $config["dbConfig"] );

	// Init pregress bar inst
	ConsoleController::getInstance()->initProgressBar(count($dataRows));
	// $progressBar = new ProgressBar();
	// $progressBar->setMaxProgress(  );

	// Define console headers and result
	$consoleHeaders = $config["consoleHeaders"];
	// Init list to output
	ConsoleController::getInstance()->initList();

	foreach ($dataRows as $slot) {
		// Parse main section
		// ==============================
		$tenderNumber = $slot->{'TenderNumber'};
		$tenderOrganizator = $slot->{'OrganizerName'};
		$tenderViewUrl = URL_TENDER_DOMAIN . $slot->{'TenderViewUrl'};


		// Request child page and get html-content as $serverOutput
		// ==============================
		$curlController = new CurlController($tenderViewUrl, "POST", function($curlH) {
			// Add X-Requested-With
			curl_setopt($curlH, CURLOPT_HTTPHEADER, array("X-Requested-With: XMLHttpRequest", "X-Content-Requested-For: Tab"));
		});

		$serverOutput = $curlController->exec();
		// Close curl connection
		$curlController->closeConnection();


		// Parse Child page
		// ==============================
		$childPageInfo = parseChildPage($serverOutput);

		$dateApplicationStart = $childPageInfo['dateApplicationStart'];
		$files = $childPageInfo['files'];


		// Generate files string from child page
		// ==============================
		$filesResult = null;
		$filesResultConsole = null;

		if (empty($files)) {
			$filesResult = null;
			$filesResultConsole = "Прикрепленных документов нет";
		} else {
			$filesResultConsole = "Документация:" . PHP_EOL;
			foreach($files as $fileElement) {
				$name = $fileElement["name"];
				$href = $fileElement["href"];

				$filesResult .= $name . "-" . PHP_EOL . $href . PHP_EOL;
				$filesResultConsole .= $name . "-" . PHP_EOL . $href . PHP_EOL;
			}
		}


		// Add data to console
		// ==============================
		$consoleData = array(
			$tenderNumber,
			$tenderOrganizator,
			$tenderViewUrl,
			$dateApplicationStart,
			$filesResultConsole,
		);

		for($i = 0; $i < count($consoleHeaders); $i++) {
			ConsoleController::getInstance()->listAddRow($consoleHeaders[$i] . " " .  $consoleData[$i]);
			// $consoleResult .=  $consoleHeaders[$i] . " " .  $consoleData[$i] . PHP_EOL;
		}

		ConsoleController::getInstance()->listAddRow("==============================");
		// $consoleResult .= "==============================" . PHP_EOL;


		// Save to db as row
		// ==============================
		$dbInstance->instertToTendersParse([
			'tenderNumber' => $tenderNumber,
			'tenderOrganizator' => $tenderOrganizator,
			'tenderViewUrl' => $tenderViewUrl,
			'dateApplicationStart' => $dateApplicationStart,
			'filesResult' => $filesResult,
			'requestSiteUrl' => URL_MAIN,
			'requestSiteFieldsData' => $requestFieldsData,
		]);


		// Update console Progress bar
		// ==============================
		ConsoleController::getInstance()->progressBarTick();

		// break; // remove
	}

	// ==============================
	// Display Console
	// Complete progress bar
	ConsoleController::getInstance()->progressComplete();
	// Render list
	ConsoleController::getInstance()->listRender();
}



startApplication();
