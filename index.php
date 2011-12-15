<?
//print 'test';
include_once('/var/www/neo4j/lib/useLib.php');
useLib::inclLib('intence');

$testIntence = intence::construct('Sql', 'test', 'Test node');
print_r($testIntence);
