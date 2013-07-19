<?php

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 1);

include_once('admin/lib/classes/autoload.php');
autoload::register();

lang::setDefault();
lang::setLang('de_de');

sql::connect('localhost', 'dynao_user', 'dasisteinpasswort', 'dynao');

$action = type::super('action', 'string');
$id = type::super('id', 'int', 0);

if($action == 'save' || $action == 'save-edit') {
	
	$sql = new sql();	
	$sql->setTable('news');
	$types = array('title'=>'string', 'text'=>'string');
					
	$sql->getPosts($types);
	$sql->addPost('date', time());
	
	
	if($action == 'save-edit') {
		$sql->setWhere('id='.$id);
		$sql->update();
	} else {
		$sql->save();	
	}
	
	$save_back = type::post('save-back', 'string', false);
	
	if($save_back !== false) {
		$action = 'edit';
	}
		
}

if($action == 'add' ||$action == 'edit') {

	$form = new form('news','id='.$id,'index.php');
	
	$field = $form->addTextField('title', $form->get('title'));
	$field->fieldName('News-Titel');
	
	$field = $form->addTextareaField('text', $form->get('text'));
	$field->fieldName('Infotext');
	
	if($action == 'edit') {
		$form->addHiddenField('id', $id);
	}
	
	echo $form->show();
	
} else {

	$table = new table();
	
	$table->addRow()
	->addCell('Titel')
	->addCell('Datum')
	->addCell('Aktion');
	
	$table->addSection('tbody');
	
	$table->setSql('SELECT * FROM news ORDER BY date DESC');
	while($table->isNext()) {
		
		$id = $table->get('id');
		
		$edit = '<a href="index.php?action=edit&amp;id='.$id.'">'.lang::get('edit').'</a>';
		$delete = '<a href="index.php?action=del&amp;id='.$id.'">'.lang::get('delete').'</a>';
		
		$table->addRow()
		->addCell($table->get('title'))
		->addCell(date('d.m.Y', $table->get('date')))	
		->addCell($edit.' | '.$delete);
		
		$table->next();	
	}
	
	echo $table->show();
	
}

?>