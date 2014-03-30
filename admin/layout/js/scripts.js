(function($) {
	
	var fixHelper = function(e, ui) {
        ui.children().each(function() {
			$(this).width($(this).width());
		});
		return ui;
	}
	
	$.fn.DynSorting = function(options) {
		
		var settings = $.extend({
			helper: true,
			children: 'tr',
			handle: '.fa-sort',
			htmlTo : '#ajax-content'
		}, options);
		
		if(settings.helper == false)
			settings.helper = 'original';
			
		if(settings.helper === true)
			settings.helper = fixHelper;
			
		return $(this).sortable({
			handle: settings.handle,
			helper: settings.helper,
			update : function() {
				
				var child = $(this).children(settings.children);
				var results = Array();
				
				for (var i=0;i<child.length;i++){
					results[i] = $(child[i]).data('id');
				}
				
				var getString = document.location.search.substr(1,document.location.search.length);
				$(settings.htmlTo).fadeOut(200);
				$.post('index.php?'+getString, {array: results }, function(data) {
					$(settings.htmlTo).html(data).fadeIn(200);
				});
			}
			
		});
		
	}
	
} (jQuery));

function getAjaxLoad() {
	
	$('<div>').attr('id', 'loading-body').attr('hidden', true).appendTo('body').fadeIn(200);
	$('<div>').attr('id', 'loading-spin').addClass('fa fa-spin fa-spinner').attr('hidden', true).appendTo('body').fadeIn(200);
	
}

function removeAjaxLoad() {
	$('#loading-body').remove();
	$('#loading-spin').remove();	
}

$(document).ready(function () {
    var body_width = window.innerHeight;
    var _window = $(window);

    _window.resize(function() {
        body_width = window.innerHeight;
    })
	
	$('.form-back').click(function() {
		 window.history.go(-1);
	});
	
	$('.news h5 a').tooltip();
	
	$('nav').on('navClick', function(object,action) {
        var _nav = $(this);
        action = action || auto;
        console.log(action);
        if(_nav.hasClass('active') && (action == 'auto' || action == 'close')) {
            _nav.removeClass('active');
            console.log('remove')
        } else if(action == 'auto' || action == 'open') {
            _nav.addClass('active');
            console.log('add')
        }

		$("header").addClass('stay');

	});
	
	$(window).swipe({
	    swipeRight:function(event, direction, distance, duration, fingerCount) {
            if(distance > 100 && body_width < 768) {
                $('nav').trigger('navClick', ['open']);
            }
	    },
        swipeLeft:function(event, direction, distance, duration, fingerCount) {
            if(distance > 100 && body_width < 768) {
              $('nav').trigger('navClick', ['close']);
            }
        }
	});


	$('#nav-expand').click(function() {
        $('nav').trigger('navClick', ['auto']);
	});
	
	$('nav .expand').click(function () {
		$(this).next('ul').slideToggle();	
	});
	
	$('#user').click(function() {
		$(this).children('ul').fadeToggle();	
	});
	
	$("header").headroom({
		"tolerance": 100	
	});
	
$('.js-sort tbody').DynSorting();
	$('#structure-content').DynSorting({children: 'li', handle: '.panel-heading'});
	
	$('.structure-addmodul-box select').change(function() {
		
		var form = $(this).closest('form');
		var li = form.closest('li');
		
		var pos = $('<input>').attr({
			type: 'hidden',
			name: 'sort',
			value: li.index()+1
		});
		
		if(!form.find('input[name=sort]').length)
			pos.appendTo(form);
		
		form.submit();
		
	});
	
	$('.delete').on('click', function(e) {
		e.preventDefault();
		var _this = $(this),
			title = _this.data('title');
			message = _this.data('message'),
			url = _this.attr('href');
			
		$.get('index.php', {'deleteAction': true, 'title': title, 'message': message}, function(data) {
			$('body').append(data);
			$('#delete_modal').modal('show');
		});		
		
		$(document.body).on('click', '#delete_modal .confirm', function() {
			window.location.href = url;
		});
		
		$(document.body).on('hidden.bs.modal', '#delete_modal', function () {
			$(this).remove();	
		})
		
		return false;
		
	});
	
	//
	// formLink, formLinkList
	//
	
	
	$('.dyn-link-add, .dyn-linklist-add').on('click', function() {
	var _this = $(this);
		_this.closest('.dyn-link, .dyn-linklist').attr('id', 'dyn-link-active');
	
	
	
	$("body").append('<div class="modal fade" id="selectLink" tabindex="-1" role="dialog" aria-labelledby="selectLinkLabel" aria-hidden="true"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title" id="selectLinkLabel">Struktur</h4></div><div class="modal-body"></div></div></div></div>');
	
	$(".modal-body").load('index.php?page=structure&subpage=popup');
	
	$('#selectLink').modal('show');
		
});

$('.dyn-link-del').click(function() {	
	$(this).closest('.dyn-link').find('input').removeAttr('value');
});

$('.dyn-linklist-del').on('click', function() {
	
	var selectForm = $(this).closest('.dyn-linklist').children('select'),
		index = selectForm[0].selectedIndex;
		
	selectForm.children('option').eq(index).remove().end().eq(index-1).attr('selected', 'selected');
	
});
	

$('.dyn-linklist-up').on('click', function() {
	
	var selectForm = $(this).closest('.dyn-linklist').children('select'),
		index = selectForm[0].selectedIndex,
		options = selectForm.children('option');
		
	if(index  < 1) {
		return $(this);	
	}	
	
	var option = options.eq(index);
	
	options.eq(index - 1).before('<option value="'+option.val()+'" selected="selected">'+option.text()+'</option>');
	option.remove();
});

$('.dyn-linklist-down').on('click', function() {
	
	var selectForm = $(this).closest('.dyn-linklist').children('select'),
		index = selectForm[0].selectedIndex,
		options = selectForm.children('option');
		
	if(index  == options.size()) {
		return $(this);	
	}		
		
	var option = options.eq(index);
	
	options.eq(index + 1).after('<option value="'+option.val()+'" selected="selected">'+option.text()+'</option>');
	option.remove();
});

$('.dyn-linklist-bottom').on('click', function() {
	
	var selectForm = $(this).closest('.dyn-linklist').children('select'),
		index = selectForm[0].selectedIndex,
		options = selectForm.children('option');
		
	if(index  == options.size()) {
		return $(this);	
	}		
		
	var option = options.eq(index);
	
	selectForm.append('<option value="'+option.val()+'" selected="selected">'+option.text()+'</option>');
	option.remove();
});

$('.dyn-linklist-top').on('click', function() {
	
	var selectForm = $(this).closest('.dyn-linklist').children('select'),
		index = selectForm[0].selectedIndex,
		options = selectForm.children('option');
		
	if(index  == 0) {
		return $(this);	
	}		
		
	var option = options.eq(index);
	
	selectForm.prepend('<option value="'+option.val()+'" selected="selected">'+option.text()+'</option>');
	option.remove();
});

$('form').on('submit', function() {
	var selectForm = $('.dyn-linklist').find('select');
	if(selectForm.length) {
		selectForm.attr('multiple', 'multiple');
		selectForm.children('option').prop('selected', true);
	}
});

$(document.body).on("click", '.dyn-link-select', function() {
	
	var _this = $(this),
		name = _this.data('name'),
		id = _this.data('id')
		input_wrap = $('#dyn-link-active'),
		tr = _this.closest('tr');
	
	_this.button('loading');
	var interval = setInterval(function() {
		_this.button('reset');
		// Sich selbst auflösen, da Button mehrmals geklickt werden kann
		clearInterval(interval);
	}, 300);
	
	if(input_wrap.children('select').length == 1) {
		
		input_wrap.find('select').append('<option value="'+id+'">"'+name+'"</option>');		
		
	} else {
		
		input_wrap.find('input[type=hidden]:first').val(id);
		input_wrap.find('input[type=text]:first').val(name);
		
		$('#selectLink').modal('hide');
	}
	
});

$(document).on('hidden.bs.modal', '#selectLink', function () {
	$('#dyn-link-active').removeAttr('id');
	$(this).remove();	
})


	
});
