<?php
require_once 'Neo4jTestCase.php';

class TraversalDescriptionTest extends Neo4jTestCase
{
    public function testPathTraversal()
    {
        
        // Create two nodes with a single relationship
        
        $nodeOne = $this->graphDb->createNode();
        $nodeOne->name = 'First Node';
        $nodeOne->save();
        
        $nodeTwo = $this->graphDb->createNode();
        $nodeTwo->name = 'Second Node';
        $nodeTwo->save();
        
        $rel = $nodeOne->createRelationshipTo($nodeTwo, 'owns');
        $rel->day = 'Monday';
        $rel->save();
        
        // Do traversal
        
        $td = new TraversalDescription($this->graphDb);
        $td->depthFirst();
        $paths = $td->traverse($nodeOne, TraversalDescription::RETURN_PATH );
        $path = $paths[0];
        
        $this->assertEquals($path->length(), 1, "Length of path should be 1");
        $this->assertEquals($path->startNode()->getId(), $nodeOne->getId(),"First Node should be start node");
        $this->assertEquals($path->endNode()->getId(), $nodeTwo->getId(),"Second Node should be end node");
        
        $pathRels = $path->relationships();
        $pathRel = $pathRels[0];
        
        $this->assertEquals($pathRels[0]->getId(), $rel->getId(),"Traversed relationship should be the same a created relationship");
    }
    
    public function testNodeTraversal()
    {
        
        // Create two nodes with a single relationship
        
        $nodeOne = $this->graphDb->createNode();
        $nodeOne->name = 'First Node';
        $nodeOne->save();
        
        $nodeTwo = $this->graphDb->createNode();
        $nodeTwo->name = 'Second Node';
        $nodeTwo->save();
        
        $rel = $nodeOne->createRelationshipTo($nodeTwo, 'owns');
        $rel->day = 'Monday';
        $rel->save();
        
        // Do traversal
        
        $td = new TraversalDescription($this->graphDb);
        $td->depthFirst();
        $nodes = $td->traverse($nodeOne, TraversalDescription::RETURN_NODES );
        
        $this->assertEquals(sizeof($nodes), 1, "Size of returned nodes array should be 1");
        $this->assertEquals($nodes[0]->getId(), $nodeTwo->getId(), "only node should be second node");
        
    }
    
    public function testNodeTraversalWithAllFilter()
    {
        
        // Create two nodes with a single relationship
        
        $nodeOne = $this->graphDb->createNode();
        $nodeOne->name = 'First Node';
        $nodeOne->save();
        
        $nodeTwo = $this->graphDb->createNode();
        $nodeTwo->name = 'Second Node';
        $nodeTwo->save();
        
        $rel = $nodeOne->createRelationshipTo($nodeTwo, 'owns');
        $rel->day = 'Monday';
        $rel->save();
        
        // Do traversal
        
        $td = new TraversalDescription($this->graphDb);
        $td->depthFirst();
        $td->returnFilter('builtin', 'all');
        $nodes = $td->traverse($nodeOne, TraversalDescription::RETURN_NODES );
        
        $this->assertEquals(sizeof($nodes), 2, "Size of returned nodes array should be 2");
        $this->assertEquals($nodes[0]->getId(), $nodeOne->getId(), "nodes[0] should be equal to first node");
        $this->assertEquals($nodes[1]->getId(), $nodeTwo->getId(), "nodes[1] should be equal to second node");
    }
    
    public function testRelationshipTraversal()
    {
        $this->markTestIncomplete(
          'testRelationshipTraversal has not yet been implemented.'
        );
    }
    
    public function testAdvancedTraversal()
    {
        $this->markTestIncomplete(
          'testAdvancedTraversal has not yet been implemented.'
        );
    }
}