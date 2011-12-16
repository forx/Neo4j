<?
class myNeoBuddy{
	private $base_uri;
	
	/**
	 * JSON HTTP client
	 *
	 * @var JSONClient
	 */
	protected $jsonClient;



	function __construct($jsonClient = null){
		$vars = useLib::loadLib('vars');		
		$this->base_uri = $vars->get('neoHost') . '/';
		
		if ( ! is_null($jsonClient) ) {
		    $this->jsonClient = $jsonClient;
		} else {
		    $this->jsonClient = new JsonClient;
		}
	}

	public function getBaseUri()
	{
		return $this->base_uri;
	}

	public function getById($id){
		$return = array(); //результат в виде массива, котрый мы вытаскиваем из php.
		
		$uri = $this->base_uri . 'node/' . $id;
		list($response, $http_code) = $this->jsonClient->jsonGetRequest($uri);

		switch ($http_code){
			case 200:
				$result['id'] = end(explode("/", $response['self']));
				$result['name'] = $response['data']['name'];
				$result['desc'] = $response['data']['desc'];
				 
				list($data, $http_code) = $this->jsonClient->jsonGetRequest($response['incoming relationships']);
				if ($http_code == 200){
					$result['arrAttr'] = array();
					foreach($data as $rel){
						$relId  = end(explode('/', $rel['self']));
						$attrId = end(explode('/', $rel['end']));
						list($attrData) = $this->jsonClient->jsonGetRequest($rel['end']);
						$attrName = $attrData['data']['name'];
						$result['arrAttr'][] = array('id' => $attrId, 'name' => $attrName, 'type' => $rel['type']);
					}
				}
				
				list($data, $http_code) = $this->jsonClient->jsonGetRequest($response['outgoing relationships']);
				if ($http_code == 200){
					$result['arrAttr4'] = array();
					foreach($data as $rel){
						$relId  = end(explode('/', $rel['self']));
						$attrId = end(explode('/', $rel['end']));
						list($attrData) = $this->jsonClient->jsonGetRequest($rel['end']);
						$attrName = $attrData['data']['name'];
						$result['arrAttr4'][] = array('id' => $attrId, 'name' => $attrName, 'type' => $rel['type']);
					}
				}
				
				break;
			default: 
				$result = false;
		} 
		return($result);
	}
	
	public function getByName($name){
		
	}

	public function loadName($id){
		$return = '';
		$uri = $this->base_uri . 'node/' . $id;
		list($response, $http_code) = $this->jsonClient->jsonGetRequest($uri);
		switch ($http_code){
			case 200:
				$return = $response['data']['name'];
			break;
			default:
				$return = false;
		}
		return $return;
	}

	public function save($id, $name, $desc, $arrAttr){
		$uri = $this->base_uri . 'node/' . $id;
		list($response, $http_code) = $this->jsonClient->jsonGetRequest($uri);
		switch ($http_code){
			case 200:
				$uri = $this->base_uri . 'node/' . $id . '/properties/name';
				//$data = json_encode(array('name' => $name, 'description' => $desc));
				$data = $name;
				$this->jsonClient->jsonPutRequest($uri, $data);
				$uri = $this->base_uri . 'node/' . $id . '/properties/desc';
				$data = $desc;
				$this->jsonClient->jsonPutRequest($uri, $data);
				
				foreach($arrAttr as $attr){
					$uri = $this->base_uri . 'node/' . $id . '/relationships';
					$data = array(
						'to' => $this->base_uri . 'node/' . $attr['id'],
						'type' => $attr['relation'],
						'data' => null,
					);
					//$data = '{"to" : "http://localhost:9999/node/10", "type" : "LOVES", "data" : {"foo" : "bar"}}';
					list($response, $http_code) = $this->jsonClient->jsonPostRequest($uri, $data);
					print_r($response);
					print "\n" . $http_code . "\n" . $uri . "\n" . $data;
				}
			break;
			default:
				$return = false;
		}
	}
			
	public function deleteWithRelation($id){
		$uri = $this->base_uri . $id . '/relationships/in';
		list($data, $http_code) = $this->jsonClient->jsonGetRequest($uri);
		switch ($http_code){
			case 200:
				foreach($data as $rel){
						$relId  = end(explode('/', $rel['self']));
						$uri = $this->base_uri . 'relationship/' . $relId;
						$this->jsonClient->jsonDeleteRequest($uri);
					}
				break;
		}
		$uri = $this->base_uri . 'node/' . $id;
		list($data, $http_code) = $this->jsonClient->jsonDeleteRequest($uri);
	}
	
	public function reserv(){
		$uri = $this->base_uri . 'node';
		
		list($response, $http_code) = $this->jsonClient->jsonPostRequest($uri, null);
		switch ($http_code){
			case 201:
				$return = end(explode('/', $response['self']));
			break;
			default:
				$return = false;
		}
		print $uri;
		return $return;
	}
}
