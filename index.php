<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require('sql.php');

require('form/fields.php');
require('form/text.php');
require('form/textarea.php');

sql::connect('localhost', 'c1sysix', 'sysixpw', 'c1dynao');

$form = new form('news','id=1','index.php');

$field = $form->addTextField('title', $form->get('title'), array('class'=>'text'));
$field->fieldName('News-Titel');
$field->setSuffix('<small>max. 50 Buchstaben</small>');

$field = $form->addTextareaField('text', $form->get('text'));
$field->fieldName('Infotext');

$field = $form->addTextareaField('text-de', 'Vorgefertigter Text');
$field->fieldName('Infotext-de');

echo $form->show();

?>