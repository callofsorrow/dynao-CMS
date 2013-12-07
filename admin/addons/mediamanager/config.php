<?php

layout::addJs('addons/mediamanager/layout/js/mediamanager.js');

userPerm::add('media[edit]', lang::get('media[edit]'));
userPerm::add('media[delete]', lang::get('media[delete]'));
userPerm::add('media[category][edit]', lang::get('media[category][edit]'));
userPerm::add('media[category][delete]', lang::get('media[category][delete]'));

if(
	dyn::get('user')->hasPerm('media[edit]') ||
	dyn::get('user')->hasPerm('media[delete]') ||
	dyn::get('user')->hasPerm('media[category][edit]') ||
	dyn::get('user')->hasPerm('media[category][delete]')
) {
	backend::addNavi(lang::get('media'), url::backend('media'), 'picture-o', 2);
}

form::addClassMethod('addMediaField', function($name, $value) {
	
	return $this->addField($name, $value, 'formMedia');
	
});

form::addClassMethod('addMediaListField', function($name, $value) {
	
	return $this->addField($name, $value, 'formMediaList');
	
});
$page = type::super('page', 'string');
$subpage = type::super('subpage', 'string');

if($page == "media" && $subpage == "popup") {

	dyn::add('ajaxContinue', true);

}

extension::add('PAGE_AREA_BEFORE_OUTPUTFILTER', function($return) {
	return mediaUtils::convertMediaVars($return[0], $return[1], $return[2]);
});

pageArea::addType('MEDIA', 10);
pageArea::addType('MEDIA_LIST', 10);

?>