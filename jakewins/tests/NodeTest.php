<?php
require_once 'Neo4jTestCase.php';

class NodeTest extends Neo4jTestCase
{
    public function testDeleteNode() {
        $node = $this->graphDb->createNode();
        $node->save();
        
        // Make sure the node is there
        $id = $node->getId();
        $this->graphDb->getNodeById($id);
        
        $node->delete();
        
        $exception = null;
        try {
            $this->graphDb->getNodeById($id);
        } catch( NotFoundException $e ) {
            $exception = $e;
        }
        
        $this->assertTrue(
            $exception instanceof NotFoundException,
            "Exception was not thrown properly when retrieving deleted node."
        );
    }
    
    public function testCreateAndRetrieveRelationship() {
        $nodeOne = $this->graphDb->createNode();
        $nodeOne->name = 'First Node';
        $nodeOne->save();
        
        $nodeTwo = $this->graphDb->createNode();
        $nodeTwo->name = 'Second Node';
        $nodeTwo->save();
        
        $rel = $nodeOne->createRelationshipTo($nodeTwo, 'owns');
        $rel->day = 'Monday';
        $rel->save();
        
        $this->assertEquals(
            $this->graphDb->getRelationshipById($rel->getId())->day,
            $rel->day,
            "Properties were lost when saving and then retrieving a relationship."
        );
    }
    
}