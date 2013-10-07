/*
	Copyight: Deux Huit Huit 2013
	License: MIT, http://deuxhuithuit.mit-license.org
*/

/**
 * Client code for link_preview
 *
 * @author deuxhuithuit
 */
(function ($, undefined) {

	var FIELD = 'field-link_preview';
	var FIELD_CLASS = '.' + FIELD;
	var target = $();
	
	var hookOne = function (index, elem) {
		elem = $(elem);
		
		var url = elem.attr('data-url');
		var text = elem.attr('data-text');
		
		if (!!url) {
			var link = $('<a />')
				.text(text)
				.attr('class', 'link-preview')
				.attr('href', url)
				.attr('target', '_blank');
			
			target.after(link);
		}
	};
	
	var init = function () {
		target = $('#context #breadcrumbs h2');
		return $(FIELD_CLASS).each(hookOne);
	};

	$(init);

})(jQuery);