<?php
/**
 * GraphDatabaseService abstracts a Neo4J database server.
 *
 * @package NeoRest
 */

/**
 * GraphDatabaseService abstracts a Neo4J database server.
 *
 * @example ../examples/demo.php Using the GraphDatabaseService
 *
 * @package NeoRest
 */
class GraphDatabaseService
{
	public $base_uri;
	
	/**
	 * JSON HTTP client
	 *
	 * @var JSONClient
	 */
	protected $jsonClient;
	
	public function __construct($base_uri, $jsonClient=null)
	{
		$this->base_uri = $base_uri;
		
		if (!is_null($jsonClient)) {
		    $this->jsonClient = $jsonClient;
		} else {
		    $this->jsonClient = new JsonClient;
		}
	}
	
	public function getNodeById($id) {
	     return $this->getNodeByUri($this->base_uri.'node/'.$id);   
	}
	
	public function getNodeByUri($uri)
	{
		list($response, $http_code) = $this->jsonClient->jsonGetRequest($uri);

		switch ($http_code)
		{
			case 200:
				return Node::inflateFromResponse($this, $response);
			case 404:
				throw new NotFoundException();
			default:
				throw new NeoRestHttpException($http_code);
		}
	}
	
    public function getRelationshipById($id) {
	     return $this->getRelationshipByUri($this->base_uri.'relationship/'.$id);   
	}
	
	public function getRelationshipByUri($uri)
	{
		list($response, $http_code) = $this->jsonClient->jsonGetRequest($uri);

		switch ($http_code)
		{
			case 200:
				return Relationship::inflateFromResponse($this, $response);
			case 404:
				throw new NotFoundException();
			default:
				throw new NeoRestHttpException($http_code);
		}
	}
	
	public function createNode()
	{
		return new Node($this);
	}
	
	public function getBaseUri()
	{
		return $this->base_uri;
	}
}
