<?
//print 'test';
include_once('/var/www/neo4j/lib/useLib.php');
useLib::inclLib('JsonClient');
$cl = new jsonClient();
for($id = 1; $id < 1000; $id++){
	$uri = 'http://localhost:9999/node/' . $id . '/relationships/in';
	
	list($data, $http_code) = $cl->jsonGetRequest($uri);
	switch ($http_code){
		case 200:
			foreach($data as $rel){
				$relId  = end(explode('/', $rel['self']));
				$uri = 'http://localhost:9999/relationship/' . $relId;
				
				$cl->jsonDeleteRequest($uri, '');
				
			}
			break;
	}
	$uri = 'http://localhost:9999/node/' . $id;
	list($dataT, $http_code) = $cl->jsonDeleteRequest($uri);
	print $uri . '<br/>';print_r($http_code);
}
print 'done';
