/* Menu header */
jQuery(document).ready(function(){
	jQuery('.main-navbar').scrollToFixed();
});

/* Map Features */
var googleMapsUrl 		 = '//maps.googleapis.com/maps/api/js?language=en-CA&sensor=false&v=3.13&libraries=places',
	MapSearchXMLRpcUri   = '/assets/template/js/map/api.php',
	//MarkersImgPath       = '/template/assets/template/js/map/core/pins';
	MarkersImgPath       = '/assets/template/js/map/core/pins';
var userAttributeCookies = {
	flags_name: 'flags',
	roles_name: 'roles',
	flags: {},
	roles: {}
};

	
jQuery(document).ready(function($) {
	jQuery(".fullscreen").click(function() {
		jQuery("body").toggleClass("body-fullscreen");
		jQuery("#map-container").height(jQuery(window).height);
		var map = jQuery("#map-container");
		google.maps.event.trigger(map, "resize");
		jQuery(window).load();
	});
 });
 
 /* Sumo select */
 jQuery(document).ready(function($) {
	window.asd = jQuery('select.form-select').SumoSelect();
});
 
 /* Range slider */
 jQuery(document).ready(function($) {
	jQuery('div.cs-drag-slider').each(function() {
		 var _this = jQuery(this);
		 tooltip = jQuery('span.ui-slider-handle');
		_this.slider({
			range:'min',
			step: _this.data('slider-step'),
			min: _this.data('slider-min'),
			max: _this.data('slider-max'),
			value: _this.data('slider-value'),
			slide: function (event, ui) {
				//jQuery(this).parents('li.to-field').find('.cs-range-input').val(ui.value)
				jQuery( "#radiusSelector" ).val(ui.value);
					tooltip = jQuery(this).parents('li.to-field').find('span.ui-slider-handle');
					tooltip.html("<strong>"+ui.value+" Miles</strong>");
			}
		});
	});
	
	jQuery("div.cs-drag-slider span").first().html("<strong>"+ jQuery( "#radiusSelector" ).val() +" Miles</strong>");
});
 
 jQuery(document).ready(function($) {
	require('map_search/MapSearchPage').attachTo('.map-search');
	$('.search-button').trigger('click');
	
	$('.multi-select').multiselect({
		buttonClass: 'btn btn-default',
		includeSelectAllOption: true,
		enableCaseInsensitiveFiltering: true,
		filterBehavior: 'both',
		templates: {
			 filter: '<li class="multiselect-item filter"><input class="form-control multiselect-search" type="text" placeholder="Type to filter..."/></li>'
		},
		onChange:function(element, checked){
			$.uniform.update();
		}
	});
});

/* Category selection collapse */
$(document).ready(function(e) {
	$('a.cs-link-more').on('click', function (event) {
		event.preventDefault();
		
		var target   = $($(this).data('target')),
			openTxt  = $(this).attr('data-openTxt'),
			closeTxt = $(this).attr('data-collapseTxt');
			
		if (target.hasClass('collapse')) {
			$(this).html(openTxt);	
			target.css({display: 'none'}).removeClass('collapse');
			target.slideDown(600);
			
			$('html, body').animate({
				scrollTop: target.parents('li.categoryOptions').offset().top - 200
			}, 1000);
		} else {
			$(this).html(closeTxt);	
			target.addClass('collapse');
		}
	});
   /* new Masonry($('.categorySelection'), {
	  itemSelector: 'li.categoryOptions',
	  //columnWidth: 300
	});*/
	
	
	/*$('.categorySelection').isotope({
		itemSelector : 'li.categoryOptions',
		layoutMode : 'masonry'
	});*/
});