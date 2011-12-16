<?php 
// TODO Lazy evaluation?  There are a lot of requests to the REST
//      server and it's quite likely that not all the nodes 
//      or relationships in the path will be used.
class Path
{
	
	var $_neo_db;
	var $_endNode;
	var $_startNode;
	var $_length;
	var $_nodes;
	var $_relationships;
	
	public function __construct($neo_db)
	{
		$this->_neo_db = $neo_db;
	}
		
	public function length()
	{
		return $this->_length;	
	}
	
	public function setLength($length)
	{
		$this->_length = $length;	
	}
	
	public function endNode()
	{
		return $this->_endNode;	
	}

	public function setEndNode($node)
	{
		$this->_endNode = $node;	
	}
	
	public function startNode()
	{
		return $this->_startNode;	
	}

	public function setStartNode($node)
	{
		$this->_startNode = $node;	
	}
	
	public function nodes()
	{
		return $this->_nodes;	
	}

	public function setNodes($arrayOfNodes)
	{
		$this->_nodes = $arrayOfNodes;
	}

	public function relationships()
	{
		return $this->_relationships;	
	}

	public function setRelationships($arrayOfRelationships)
	{
		$this->_relationships = $arrayOfRelationships;
	}
	
	public static function inflateFromResponse($neo_db, $response)
	{
		$path = new Path($neo_db);
		$path->setLength($response['length']);
		$path->setStartNode($neo_db->getNodebyUri($response['start']));
		$path->setEndNode($neo_db->getNodebyUri($response['end']));
		
		$nodes = array();
		
		foreach ($response['nodes'] as $nodeUri ) {
			$nodes[] = $neo_db->getNodeByUri($nodeUri);
		}
		$path->setNodes($nodes);

		$rels = array();
		
		foreach ($response['relationships'] as $relUri ) {
			$rels[] = $neo_db->getRelationshipByUri($relUri);
		}
		$path->setRelationships($rels);
		
		return $path;
	}
	
}