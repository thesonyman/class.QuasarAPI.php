<?php
/*
#	class.QuasarAPI.php
#	Made By: William Gill
#	Date: 2/19/2016
#	Time: 5:00 AM
#	Source: https://github.com/57-Wolve/class.QuasarAPI.php
#
#
#	The MIT License (MIT)
#
#	Copyright (c) 2016 William Gill
#
#	Permission is hereby granted, free of charge, to any person obtaining a copy
#	of this software and associated documentation files (the "Software"), to deal
#	in the Software without restriction, including without limitation the rights
#	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
#	copies of the Software, and to permit persons to whom the Software is
#	furnished to do so, subject to the following conditions:
#
#	The above copyright notice and this permission notice shall be included in all
#	copies or substantial portions of the Software.
#
#	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
#	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
#	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
#	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
#	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
#	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
#	SOFTWARE.
*/

class Quasar_API {
	private $ClientKey = "";
	private $ClientAuth = "";
	
	private $API_URL = "https://quasar.alpha.neutronservers.com/";
	private $API_Version = "api/v3/index.php";
	
	public function Settings($key, $auth, $apiurl, $apiversion) { 
		$this->ClientKey = $key;
		$this->ClientAuth = $auth;

		$this->API_URL = $apiurl;
		$this->API_Version = $apiversion;
	}
	
	public function Upload() {
		$array = array();
		if (!empty($_FILES['file'])) {
			$header = array('Content-Type: multipart/form-data');
			$url = $this->API_URL.$this->API_Version;
			$file = new CURLFile($_FILES['file']['tmp_name'], $_FILES['file']['type'], $_FILES['file']['name']);
			$data = array('client' => $this->ClientKey, 'auth' => $this->ClientAuth, 'option' => 'upload', 'file' => $file);
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			//curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$result = curl_exec($ch);
			curl_close($ch);
		
			$json = json_decode($result, true);
			if($json['Quasar']['v3']['Upload']['SUCCESS']) {
				$array['Quasar_API']['v3']['Upload']['SUCCESS'] = $json['Quasar']['v3']['Upload']['SUCCESS'];
			} else {
				$array['Quasar_API']['v3']['Upload']['ERROR'] = "No File Given";
			}
		
		} else {
			$array['Quasar_API']['v3']['Upload']['ERROR'] = "No File Given";
		}
		
		return(json_encode($array, JSON_FORCE_OBJECT));
	}

	public function Files($hash) {
		$array = array();
		
		if(!empty($hash)) {
			$url = $this->API_URL.$this->API_Version.'?client='.$this->ClientKey.'&auth='.$this->ClientAuth.'&option=files&item_id='.$hash;
			$data = file_get_contents($url);
			$json = json_decode($data, true);

			$array['Quasar_API']['v3']['Files']['SUCCESS'] = $json['Quasar']['v3']['Files'][$hash];
		} else {
			$url = $this->API_URL.$this->API_Version.'?client='.$this->ClientKey.'&auth='.$this->ClientAuth.'&option=files';
			$data = file_get_contents($url);
			$json = json_decode($data,true);
			
			$array['Quasar_API']['v3']['Files']['SUCCESS'] = $json['Quasar']['v3']['Files'];
		}
		
		return(json_encode($array, JSON_FORCE_OBJECT));
	}
	
	public function View($hash) {
		$array = array();
		
		if(!empty($hash)) {
			$url = $this->API_URL.$this->API_Version.'?client='.$this->ClientKey.'&auth='.$this->ClientAuth.'&option=files&item_id='.$hash;
			$data = file_get_contents($url);
			$json = json_decode($data, true);
			
			$array['Quasar_API']['v3']['View']['SUCCESS'] = $this->API_URL."file:".$hash.".".$json['Quasar']['v3']['Files'][$hash]['filetype'];
		} else {
			$array['Quasar_API']['v3']['View']['ERROR'] = "No Hash Given";
		}
		
		return(json_encode($array, JSON_FORCE_OBJECT));
	}
	
	public function Download($hash) {
		$array = array();
		
		if(!empty($hash)) {
			$array['Quasar_API']['v3']['Download']['SUCCESS'] = $this->API_URL."dl:".$hash;
		} else {
			$array['Quasar_API']['v3']['Download']['ERROR'] = "No Hash Given";
		}
		
		return(json_encode($array, JSON_FORCE_OBJECT));
	}
	
	public function Rename($hash, $name) {
		$array = array();
		if(!empty($hash)) {
			$header = array('Content-Type: multipart/form-data');
			$url = $this->API_URL.$this->API_Version;
			$data = array('client' => $this->ClientKey, 'auth' => $this->ClientAuth, 'option' => 'rename', 'item_id' => $hash, 'data' => $name);
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			//curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$result = curl_exec($ch);
			curl_close($ch);
		
			$json = json_decode($result, true);
			if($json['Quasar']['v3']['Rename']['SUCCESS']) {
				$array['Quasar_API']['v3']['Rename']['SUCCESS'] = $json['Quasar']['v3']['Rename']['SUCCESS'];
			} else {
				$array['Quasar_API']['v3']['Rename']['ERROR'] = "";
			}
		} else {
			$array['Quasar_API']['v3']['Rename']['ERROR'] = "No Hash Given";
		}
		
		return(json_encode($array, JSON_FORCE_OBJECT));
	}
	
	public function Delete($hash) {
		$array = array();
		if(!empty($hash)) {
			$header = array('Content-Type: multipart/form-data');
			$url = $this->API_URL.$this->API_Version;
			$data = array('client' => $this->ClientKey, 'auth' => $this->ClientAuth, 'option' => 'delete', 'item_id' => $hash);
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			//curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$result = curl_exec($ch);
			curl_close($ch);
		
			$json = json_decode($result, true);
			if($json['Quasar']['v3']['Delete']['SUCCESS']) {
				$array['Quasar_API']['v3']['Delete']['SUCCESS'] = $json['Quasar']['v3']['Delete']['SUCCESS'];
			} else {
				$array['Quasar_API']['v3']['Delete']['ERROR'] = $json['Quasar']['v3']['Delete']['ERROR'];
			}
		} else {
			$array['Quasar_API']['v3']['Delete']['ERROR'] = "No Hash Given";
		}
		
		return(json_encode($array, JSON_FORCE_OBJECT));
	}
	
	/*
	# !!!DO NOT USE!!!
	#
	# I'm still working on the LTO Storage part of Quasar.
	# All this would do is submit a request to the Vault queue to pull the file from the LTO drive and cache it so the user may download or view the file.
	# File cache time will be 2 days after successful pull from LTO tape.
	# This function is not finished so don't implement it just yet.
	#
	public function VaultRequest($hash) {
		$array = array();
		if(!empty($hash)) {
			$header = array('Content-Type: multipart/form-data');
			$url = $this->API_URL.$this->API_Version;
			$data = array('client' => $this->ClientKey, 'auth' => $this->ClientAuth, 'option' => 'vault', 'item_id' => $hash);
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			//curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			$result = curl_exec($ch);
			curl_close($ch);
		
			$json = json_decode($result, true);
			if($json['Quasar']['v3']['Vault']['SUCCESS']) {
				$array['Quasar_API']['v3']['Vault']['SUCCESS'] = $json['Quasar']['v3']['Vault']['SUCCESS'];
			} else {
				$array['Quasar_API']['v3']['Vault']['ERROR'] = $json['Quasar']['v3']['Vault']['ERROR'];
			}
		} else {
			$array['Quasar_API']['v3']['Vault']['ERROR'] = "";
		}
		
		return(json_encode($array, JSON_FORCE_OBJECT));
	}
	*/
}
?>
