<?php
require_once 'Neo4jTestCase.php';

class RelationshipTest extends Neo4jTestCase
{
    public function testDeleteRelationship() {
        $nodeOne = $this->graphDb->createNode();
        $nodeOne->name = 'First Node';
        $nodeOne->save();
        
        $nodeTwo = $this->graphDb->createNode();
        $nodeTwo->name = 'Second Node';
        $nodeTwo->save();
        
        $rel = $nodeOne->createRelationshipTo($nodeTwo, 'owns');
        $rel->day = 'Monday';
        $rel->save();
        
        $id = $rel->getId();
        $rel = $this->graphDb->getRelationshipById($id);
        
        $rel->delete();
        
        $exception = null;
        try {
           $this->graphDb->getRelationshipById($id);
        } catch( NotFoundException $e ) {
            $exception = $e;
        }
        
        $this->assertTrue(
            $exception instanceof NotFoundException,
            "Exception was not thrown properly when retrieving deleted relationship."
        );
    }
}