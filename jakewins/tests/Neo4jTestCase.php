<?php
require_once 'PHPUnit/Framework.php';
require_once 'Neo4j.php';

class Neo4jTestCase extends PHPUnit_Framework_TestCase
{
    public $graphDb;
    public $graphDbUri;
    
    public function setUp() {
        $this->graphDbUri = 'http://localhost:9999/';
        $this->graphDb = new GraphDatabaseService($this->graphDbUri);
        if (!$this->graphDb instanceof GraphDatabaseService) {
            $this->markTestIncomplete();
        }
    }
}
