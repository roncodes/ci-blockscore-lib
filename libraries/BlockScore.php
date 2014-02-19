<?php defined('BASEPATH') OR exit('No direct script access allowed');

require_once FCPATH.'vendor/autoload.php';

use Guzzle\Http\Client;

class BlockScore {

	const BLOCKSCORE_URL = 'https://api.blockscore.com/';

	public $ci;
	public $client; 
	public $version = 2;

	public function __construct() {
	    $this->ci =& get_instance();
        $config = $this->ci->load->config('blockscore');
        $this->client = new Client(self::BLOCKSCORE_URL, array(
            'request.options' => array(
                'auth'    => array($config['blockscore_api_key'], '', 'Basic'),
            ), 
        ));
	}

	public function verification($resource) {
		return $this->request('verifications', $resource);
	}

	public function questions($resource) {
		if(is_array($resource)) {
			return $this->request('questions', $resource);
		}
		return $this->request('questions', array('verification_id' => $resource));
	}

	public function score($resource) {
		return $this->request('questions/score', $resource);
	}

	private function request($path, $params=array(), $method="post") {
        $this->client->setDefaultOption('headers', array(
            'Accept' => 'application/vnd.blockscore+json;version='.$this->version,
            'Content-Type' => (is_array($params)) ? 'application/x-www-form-urlencoded' : 'application/json'
        ));
        $request = $this->client->$method($path, array(), $params);
        $response = $request->send();

        if(strlen(trim($response->getBody())) == 0) {
            return True;
        }

        if(strpos($response->getHeader("Content-Type"), "json") != False) {
        	$result = json_decode($response->getBody(true), true);
        	if($result === null) {
        		// most likely a strange character is showing up appeneded to json result
        		preg_match('/{(.*)}/', trim($response->getBody(true)), $matches);
		  		return (isset($matches[0])) ? json_decode($matches[0], true) : $response->getBody(true);
        	}
        	return $result;
    	} else {
    		return $response->getBody();
    	}
    }

}