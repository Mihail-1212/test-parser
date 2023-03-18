<?php namespace Mamok\TestParser;


class CurlController
{
	private $curlHander;


	/**
	 * @param $url: 						string URL path to file
	 * @param $urlMethod: string 			HTTP method as string (GET | POST)
	 * @param $additiveOptsCallback: func	Callback with curlHander as arg
	 * @param $disableDefOpts: bool			Variable to use derfault curl options from setCurlDefaultOpts method
	 */
	public function __construct($url, $urlMethod, $additiveOptsCallback=null, $disableDefOpts=False){
		$this->curlHander = curl_init();

		// $url
		if (filter_var($url, FILTER_VALIDATE_URL) === FALSE) {
			throw new InvalidArgumentException('$url artument is not valid URL');
		}
		curl_setopt($this->curlHander, CURLOPT_URL, $url);

		//  $ urlMethod
		if ($urlMethod == "GET") {
			// GET opst
		} 
		elseif ($urlMethod == "POST") {
			// POST opts
			curl_setopt($this->curlHander, CURLOPT_POST, 1);
		} 
		else {
			throw new InvalidArgumentException('$urlMethod argument is not avaible, must "GET" or "POST" values only');
		}

		if ($additiveOptsCallback != null) {
			call_user_func($additiveOptsCallback, $this->curlHander);
		} 

		if (!$disableDefOpts) {
			$this->setCurlDefaultOpts();
		}
	}


	public function exec() {
		return curl_exec($this->curlHander);
	}


	public function setPostFields($dataFields) {
		curl_setopt($this->curlHander, CURLOPT_POSTFIELDS, $dataFields);
	}
	

	private function throwException($exception) {
		$this->closeConnectionIfAvaible();
	}


	public function closeConnectionIfAvaible() {
		if ($this->curlHander != null) {
			$this->closeConnection();
		}
	} 


	public function closeConnection() {
		curl_close($this->curlHander);
	}


	protected function setCurlDefaultOpts() {
		// Receive server response ...
		curl_setopt($this->curlHander, CURLOPT_RETURNTRANSFER, true);
		// Disable console output
		curl_setopt($this->curlHander, CURLOPT_HEADER, 0);
	}
	
}