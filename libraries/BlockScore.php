<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| BlockScore CodeIgniter Library
| -------------------------------------------------------------------
| Official URL: http://www.blockscore.com
| Author: Ronald A. Richardson
| Author URI: http://ronaldarichardson.com/
| Author Email: me@ronaldarichardson.com
| -------------------------------------------------------------------
| License
| -------------------------------------------------------------------
| The MIT License (MIT)
| Copyright (c) 2014 Ronald A. Richardson
| 
| Permission is hereby granted, free of charge, to any person obtaining a copy
| of this software and associated documentation files (the "Software"), to deal
| in the Software without restriction, including without limitation the rights
| to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
| copies of the Software, and to permit persons to whom the Software is
| furnished to do so, subject to the following conditions:
| 
| The above copyright notice and this permission notice shall be included in
| all copies or substantial portions of the Software.
| 
| THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
| IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
| FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
| AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
| LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
| OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
| THE SOFTWARE.
|
*/

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