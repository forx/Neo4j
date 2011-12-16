<?php
require_once 'Neo4jTestCase.php';
class GraphDatabaseServiceTest extends Neo4jTestCase
{
    public function testUrlSetting()
    {
        $this->assertEquals(
            $this->graphDbUri,
            $this->graphDb->getBaseUri(),
            'Hm, it seems like we can not get the URI back.'
            );
    }

    public function testCreateAndRetrieveNode() {
        $node = $this->graphDb->createNode();
        $node->name = 'Mr node';
        $node->save();
        
        $this->assertEquals(
            $this->graphDb->getNodeById($node->getId())->name,
            $node->name,
            "Properties were lost when saving and then retrieving a node."
        );
    }
}
