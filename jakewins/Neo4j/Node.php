<?php
/**
 * Node in the graph.
 *
 * @package NeoRest
 */

/**
 * Node in the graph.
 *
 * @package Neo4j
 */
class Node extends PropertyContainer
{
	public $_neo_db;
	public $_id;
	public $_is_new;
	
	/**
	 * JSON HTTP client
	 *
	 * @var JSONClient
	 */
	protected $jsonClient;
	
	public function __construct(GraphDatabaseService $neo_db, $jsonClient=null)
	{
		$this->_neo_db = $neo_db;
		$this->_is_new = TRUE;
		
		if (!is_null($jsonClient)) {
		    $this->jsonClient = $jsonClient;
		} else {
		    $this->jsonClient = new JsonClient;
		}
	}
	
	public function delete()
	{
		if (!$this->_is_new) 
		{
			list($response, $http_code) = $this->jsonClient->deleteRequest($this->getUri());
			
			if ($http_code!=204){
			    throw new NeoRestHttpException($http_code);
			}
			
			$this->_id = NULL;
			$this->_id_new = TRUE;
		}
	}
	
	public function save()
	{
	    $data = count($this->_data) > 0 ? $this->_data : NULL;
		if ($this->_is_new) { 
			list($response, $http_code) = $this->jsonClient->jsonPostRequest($this->getUri(), $data);
			if ($http_code!=201) throw new NeoRestHttpException($http_code);
		} else {
			list($response, $http_code) = $this->jsonClient->jsonPutRequest($this->getUri().'/properties', $data);
			if ($http_code!=204) throw new NeoRestHttpException($http_code);
		}

		if ($this->_is_new) 
		{
			$this->_id = end(explode("/", $response['self']));
			$this->_is_new=FALSE;
		}
	}
	
	public function getId()
	{
		return $this->_id;
	}
	
	public function isSaved()
	{
		return !$this->_is_new;
	}
	
	public function getRelationships($direction=Relationship::DIRECTION_BOTH, $types=NULL)
	{
		$uri = $this->getUri().'/relationships';
		
		switch($direction)
		{
			case Relationship::DIRECTION_IN:
				$uri .= '/in';
				break;
			case Relationship::DIRECTION_OUT:
				$uri .= '/out';
				break;
			default:
				$uri .= '/all';
		}
		
		if ($types)
		{
			if (is_array($types)) $types = implode("&", $types);
			
			$uri .= '/'.$types;
		}
		
		list($response, $http_code) = $this->jsonClient->jsonGetRequest($uri);
		
		$relationships = array();
		
		foreach($response as $result)
		{
			$relationships[] = Relationship::inflateFromResponse($this->_neo_db, $result);
		}
		
		return $relationships;
	}
	
	public function createRelationshipTo($node, $type)
	{
		$relationship = new Relationship($this->_neo_db, $this, $node, $type);
		return $relationship;
	}
	
	public function getUri()
	{
		$uri = $this->_neo_db->getBaseUri().'node';
	
		if (!$this->_is_new) $uri .= '/'.$this->getId();
	
		return $uri;
	}
	
    // TODO Add handling for relationships
    // TODO Add algorithm parameter
	public function findPaths(Node $toNode, $maxDepth=null, RelationshipDescription $relationships=null, $singlePath=null)
	{
		
		$pathDescription = array();
		$pathDescription['to'] = $toNode->getUri();
		if ($maxDepth) $pathDescription['max depth'] = $maxDepth;
		if ($singlePath) $pathDescription['single path'] = $singlePath;
		if ($relationships) $pathDescription['relationships'] = $relationships->get();
		
		list($response, $http_code) = $this->jsonClient->jsonPostRequest($this->getUri().'/pathfinder', $pathDescription);
		
		if ($http_code==404) throw new NotFoundException;
		if ($http_code!=200) throw new NeoRestHttpException("http code: " . $http_code . ", response: " . print_r($response, true));
		
		$paths = array();
		foreach($response as $result)
		{
				$paths[] = Path::inflateFromResponse($this->_neo_db, $result);	
		}
		
		if (empty($paths)) {
			throw new NotFoundException();
		}
		
		return $paths;
	}	

	// Convenience method just returns the first path
	public function findPath(Node $toNode, $maxDepth=null, RelationshipDescription $relationships=null)
	{
		$paths = $this->findPaths($toNode, $maxDepth, $relationships, 'true');
		return $paths[0];
	}
	
	public static function inflateFromResponse(GraphDatabaseService $neo_db, $response, $jsonClient=null)
	{
		$node = new Node($neo_db, $jsonClient=null);
		$node->_is_new = FALSE;
		$node->_id = end(explode("/", $response['self']));
		$node->setProperties($response['data']);

		return $node;
	}
}
