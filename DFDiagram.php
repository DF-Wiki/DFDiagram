<?php
/*
 * DFDiagram
 * MediaWiki extension for including Dwarf Fortress diagrams
 */
define('DFD_DEBUG', 1);

if(DFD_DEBUG){
	$wgDFDConfigFile = "diagram_config.txt";
}
elseif (!isset($wgDFDConfigFile)) {
	$wgDFDConfigFile = "$IP/extensions/DFDiagram/diagram_config.txt";
}

require_once 'Diagram.php';

$DFDFile = new DFDBlockFile($wgDFDConfigFile);


$wgExtensionCredits['DFDiagram'][] = array(
	'path' => __FILE__,
	'name' => 'DFDiagram',
	'author' =>'Lethosor',
	'url' => 'https://github.com/lethosor/DFDiagram',
	'description' => 'Adds support for DF-style diagrams',
	'version'  => 0.1,
);

//DEV
print($DFDFile->get_block('floor')->name);


print("\n");