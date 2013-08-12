<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);
session_start();

include_once('lib/classes/dir.php');
new dir();

include_once(dir::classes('autoload.php'));
autoload::register();
autoload::addDir('utils');

include_once(dir::functions('html_stuff.php'));
include_once(dir::functions('url_stuff.php'));

lang::setDefault();
lang::setLang('de_de');

mb_internal_encoding('UTF-8');

sql::connect('localhost', 'dynao_user', 'dasisteinpasswort', 'dynao');

cache::setCache(false);

$page = type::super('page', 'string', 'dashboard');
$subpage = type::super('subpage', 'string');

layout::addCSS('http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700');
layout::addCSS('http://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700');

layout::addCSS('http://netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css');
layout::addCSS('layout/css/bootstrap.css');
layout::addCSS('layout/css/bootstrap-responsive.css');
layout::addCSS('layout/css/style.css');
layout::addCSS('layout/css/mobile.css');

layout::addJS('http://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js');
layout::addJS('http://code.jquery.com/ui/1.10.3/jquery-ui.js');
layout::addJS('layout/js/bootstrap.js');
layout::addJS('layout/js/scripts.js');


function add_structure_footer($table) {
	
	$table->addRow(array('class'=>'success'))
	->addCell('Das ist ein Testfooter', array('colspan'=>3));
	
}

extension::add('structure_table', 'add_structure_footer');


ob_start();

$login = new userLogin();

if($login->isLogged()) {
	include(dir::page($page.'.php'));
}

$CONTENT = ob_get_contents();

ob_end_clean();

if($login->isLogged()) {
include(dir::backend('layout/index.php'));
} else {
	include(dir::backend('layout/login.php'));
}
?>