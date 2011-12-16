# Neo4J PHP REST API client #

Very basic at the moment.

## Features ##

* Node creation
* Node loading by id
* Node delete
* Create relationship between two nodes
* List / filter relationships on a node
* Traversal access
* Indexing

## Todo ##

* Documentation!
* Prevent multiple copies of the same node or relationship object (implement cache in load node and load relationship)
* Lazy loading (URI constructors for node & relationship)
* URI discovery rather than hard coded URIs

## Getting started ##

* Download the latest version of the Neo4j REST component
* Run it
* php demo.php


## Requirements ##

A PHP that has:

* curl

Note: Only tested with PHP 5.3.

## Going further ##

Generate API documentation:

    phing docs

To generate documentation, you need 

* [Phing](http://phing.info/trac/wiki/Users/Download)
* [PhpDocumentor](http://www.phpdoc.org/)

Run unit tests:

    phpunit
