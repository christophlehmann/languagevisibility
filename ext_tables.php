<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');



$tempColumns = Array (
	"tx_languagevisibility_fallbackorder" => Array (		
		"exclude" => 0,		
		"label" => "LLL:EXT:languagevisibility/locallang_db.xml:sys_language.tx_languagevisibility_fallbackorder",		
		'l10n_display'=>'hideDiff',
		"config" => Array (
			"type" => "select",	
			"foreign_table" => "sys_language",	
			"foreign_table_where" => " ORDER BY sys_language.title",	
			"items" => Array (				
				Array("default", "999"),				
			),
			"size" => 10,	
			"minitems" => 0,
			"maxitems" => 10,
		)
	),
	"tx_languagevisibility_defaultvisibility" => Array (		
		"exclude" => 0,		
		"label" => "LLL:EXT:languagevisibility/locallang_db.xml:sys_language.tx_languagevisibility_defaultvisibility",		
		"config" => Array (
			"type" => "select",
			"items" => Array (	
				Array('',''),														
				Array("LLL:EXT:languagevisibility/locallang_db.xml:tx_languagevisibility_visibility.I.yes", "yes"),
				Array("LLL:EXT:languagevisibility/locallang_db.xml:tx_languagevisibility_visibility.I.no", "no"),
				Array("LLL:EXT:languagevisibility/locallang_db.xml:tx_languagevisibility_visibility.I.t", "t"),
				Array("LLL:EXT:languagevisibility/locallang_db.xml:tx_languagevisibility_visibility.I.f", "f"),
			),
			'default'=>'f',
			"size" => 1,	
			"maxitems" => 1,
		)
	),
	"tx_languagevisibility_defaultvisibilityel" => Array (		
		"exclude" => 0,		
		"label" => "LLL:EXT:languagevisibility/locallang_db.xml:sys_language.tx_languagevisibility_defaultvisibilityel",		
		"config" => Array (
			"type" => "select",
			"items" => Array (	
				Array('',''),							
				Array("LLL:EXT:languagevisibility/locallang_db.xml:tx_languagevisibility_visibility.I.yes", "yes"),
				Array("LLL:EXT:languagevisibility/locallang_db.xml:tx_languagevisibility_visibility.I.no", "no"),
				Array("LLL:EXT:languagevisibility/locallang_db.xml:tx_languagevisibility_visibility.I.t", "t"),
				Array("LLL:EXT:languagevisibility/locallang_db.xml:tx_languagevisibility_visibility.I.f", "f"),
			),
			'default'=>'f',
			"size" => 1,	
			"maxitems" => 1,
		)
	),
	"tx_languagevisibility_defaultvisibilityttnewsel" => Array (		
		"exclude" => 0,		
		"label" => "LLL:EXT:languagevisibility/locallang_db.xml:sys_language.tx_languagevisibility_defaultvisibilityttnewsel",		
		"config" => Array (
			"type" => "select",
			"items" => Array (	
				Array('',''),							
				Array("LLL:EXT:languagevisibility/locallang_db.xml:tx_languagevisibility_visibility.I.yes", "yes"),
				Array("LLL:EXT:languagevisibility/locallang_db.xml:tx_languagevisibility_visibility.I.no", "no"),
				Array("LLL:EXT:languagevisibility/locallang_db.xml:tx_languagevisibility_visibility.I.t", "t"),
				Array("LLL:EXT:languagevisibility/locallang_db.xml:tx_languagevisibility_visibility.I.f", "f"),
			),
			'default'=>'f',
			"size" => 1,	
			"maxitems" => 1,
		)
	),
);


t3lib_div::loadTCA("sys_language");
t3lib_extMgm::addTCAcolumns("sys_language",$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes("sys_language","tx_languagevisibility_fallbackorder;;;;1-1-1, tx_languagevisibility_defaultvisibility, tx_languagevisibility_defaultvisibilityttnewsel, tx_languagevisibility_defaultvisibilityel");

$tempColumns = Array (
	"tx_languagevisibility_visibility" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:languagevisibility/locallang_db.xml:pages.tx_languagevisibility_visibility",		
		"config" => Array (
			"type" => "user",	
			"size" => "30",
			"userFunc" => 'user_tx_languagevisibility_fieldvisibility->user_fieldvisibility',
		)
	),
);


t3lib_div::loadTCA("pages");
t3lib_extMgm::addTCAcolumns("pages",$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes("pages","--div--;LLL:EXT:languagevisibility/locallang_db.xml:tabname,tx_languagevisibility_visibility;;;;1-1-1");

t3lib_div::loadTCA("tt_news");
t3lib_extMgm::addTCAcolumns("tt_news",$tempColumns,1);
t3lib_extMgm::addToAllTCAtypes("tt_news","--div--;LLL:EXT:languagevisibility/locallang_db.xml:tabname,tx_languagevisibility_visibility;;;;1-1-1");

/*
$tempColumns = Array (
	"tx_languagevisibility_visibility" => Array (		
		"exclude" => 1,		
		"label" => "LLL:EXT:languagevisibility/locallang_db.xml:tt_content.tx_languagevisibility_visibility",		
		"config" => Array (
			"type" => "user",				
			"size" => "30",
			"userFunc" => 'user_tx_languagevisibility_fieldvisibility->user_fieldvisibility',
		)
	),
);
*/

t3lib_div::loadTCA("tt_content");
t3lib_extMgm::addTCAcolumns("tt_content",$tempColumns,1);
//t3lib_extMgm::addToAllTCAtypes("tt_content","--div--;LLL:EXT:languagevisibility/locallang_db.xml:tabname,tx_languagevisibility_visibility;;;;1-1-1");
t3lib_extMgm::addToAllTCAtypes('tt_content', "--div--;LLL:EXT:languagevisibility/locallang_db.xml:tabname,tx_languagevisibility_visibility;;;;1-1-1,sys_language_uid,l18n_parent", '', 'before:sys_language_uid');


//remove language related fields from pallete (instead show them in language tab)
$GLOBALS['TCA']['tt_content']['palettes']['4']['showitem'] = str_replace('sys_language_uid,','',$GLOBALS['TCA']['tt_content']['palettes']['4']['showitem']);
$GLOBALS['TCA']['tt_content']['palettes']['4']['showitem'] = str_replace('l18n_parent,','',$GLOBALS['TCA']['tt_content']['palettes']['4']['showitem']);
$GLOBALS['TCA']['tt_content']['ctrl']['dividers2tabs']=TRUE;


if (TYPO3_MODE=="BE")    {
    t3lib_extMgm::insertModuleFunction(
        "web_info",        
        "tx_languagevisibility_modfunc1",
        t3lib_extMgm::extPath($_EXTKEY)."modfunc1/class.tx_languagevisibility_modfunc1.php",
        "LLL:EXT:languagevisibility/locallang_db.xml:moduleFunction.tx_languagevisibility_modfunc1"
    );
}
?>