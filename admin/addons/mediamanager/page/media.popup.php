<?php

$catId = type::super('catId', 'int', 0);
	

$table = table::factory();

$table->setSql('SELECT * FROM '.sql::table('media').' WHERE `category` = '.$catId);
	
$table->addRow()
->addCell()
->addCell(lang::get('title'))
->addCell(lang::get('file_type'))
->addCell(lang::get('action'));

$table->addCollsLayout('50,*,100,250');

$table->addSection('tbody');

if($table->numSql()) {

while($table->isNext()) {
		
	$media = new media($table->getSql());
	
	$edit = '<button data-id="'.$table->get('id').'" data-name="'.$table->get('filename').'" class="btn btn-sm btn-warning dyn-media-select">'.lang::get('select').'</button>';
	
	$table->addRow()
	->addCell('<img src="'.$media->getPath().'" style="max-width:50px; max-height:50px" />')
	->addCell($media->get('title'))
	->addCell($media->getExtension())
	->addCell('<span class="btn-group">'.$edit.'</span>');
	
	$table->next();

}

} else {
	
	$table->addRow()
	->addCell(lang::get('no_entries'), ['colspan'=>4]);
		
}

?>
<div class="row">
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><?php echo lang::get('media'); ?></h3>
				<div class="clearfix"></div>
			</div>
			<div class="panel-body">
				<select class="form-control" id="media-select-category-ajax" name="catId">
					<option value="0"><?php echo lang::get('no_category'); ?></option>
					<?php echo mediaUtils::getTreeStructure(0, 0,' &nbsp;', $catId); ?>
				</select>
			</div>
            <div id="load">
			<?php echo $table->show(); ?>
            </div>
		</div>
	</div>
</div>