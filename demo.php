<?php
/**
 * Example of REST API usage
 *
 * @package NeoRest
 */

/**
 * Include the API PHP file
 */
require_once 'jakewins/Neo4j.php';

/**
 *	Create a graphDb connection 
 *	Note:	this does not actually perform any network access, 
 *			the server is only accessed when you use the database
 */
$graphDb = new GraphDatabaseService('http://localhost:9999/');

/**
 *	Lets create some nodes
 *	Note: Unlike the java API, these nodes are NOT saved until you call the save() method (see below)
 */
$firstNode = $graphDb->createNode();
$secondNode = $graphDb->createNode();
$thirdNode = $graphDb->createNode();

/**
 *	Assign some attributes to the nodes and save the,
 */
$firstNode->message = "Hello, ";

$firstNode->save();

$firstNode->save();


$secondNode->message = "world!";
$secondNode->save();
/**
 *	Create a relationship between some nodes. These can also have attributes.
 *	Note: Relationships also need to be saved before they exist in the DB.
 */
$relationship = $firstNode->createRelationshipTo($secondNode, 'KNOWS');
$relationship->message = "brave Neo4j";
$relationship->blah = "blah blah";
$relationship->save();

$relationship->blah = NULL; // Setting to NULL removed the property
$relationship->save();


/**
 *	A little utility function to display a node
 */
function dump_node($node)
{
	$rels = $node->getRelationships();
	
	echo 'Node '.$node->getId()."\t\t\t\t\t\t\t\t".json_encode($node->getProperties())."\n";
	
	foreach($rels as $rel)
	{
		$start = $rel->getStartNode();
		$end = $rel->getEndNode();
		
		echo 	"  Relationship ".$rel->getId()."  :  Node ".$start->getId()." ---".$rel->getType()."---> Node ".$end->getId(),
				"\t\t\t\t\t\t\t\t".json_encode($rel->getProperties())."\n";
	}
}

/**
 *	Dump each node we created
 */
dump_node($firstNode);
dump_node($secondNode);



