<?php
/*
 * DFDiagram
 * MediaWiki extension for including Dwarf Fortress diagrams
 */
$wgShowExceptionDetails = true;
// Use on command line
define('DFD_DEBUG', 0);

if(defined(DFD_DEBUG) && DFD_DEBUG){
	$wgDFDConfigFile = "diagram_config.txt";
	$wgDFDDefaultDiagramPath = "default_diagram.txt";
}
elseif (!isset($wgDFDConfigFile)) {
	$wgDFDConfigFile = "$IP/extensions/DFDiagram/diagram_config.txt";
	$wgDFDDefaultDiagramPath = "$IP/extensions/DFDiagram/default_diagram.txt";
}

require_once 'Diagram.php';

$DFDFile = new DFDBlockFile($wgDFDConfigFile);

/*
 * Add hooks
 */

$wgHooks['ParserFirstCallInit'][] = 'DFDMWHook::init';
$wgResourceModules['ext.DFDiagram'] = array(
	'styles' => "extensions/DFDiagram/dfdiagram.css"
);

$wgHooks['BeforePageDisplay'][] = 'DFDMWHook::includeModules';

/*
 * Credits for Special:Version
 */

$wgExtensionCredits['DFDiagram'][] = array(
	'path' => __FILE__,
	'name' => 'DFDiagram',
	'author' =>'Lethosor',
	'url' => 'https://github.com/lethosor/DFDiagram',
	'description' => 'Adds support for DF-style diagrams',
	'version'  => 0.1,
);

//DEV
//print($DFDFile->get_block('floor')->name);

//$wgShowExceptionDetails = true;

print("\n");