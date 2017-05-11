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

		if (!!url && url != '') {
			var li = $('<li />'),
				link = $('<a />')
				.html('<svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="33.7px" height="19.3px" viewBox="0 0 33.7 19.3"><path fill="currentColor" d="M16.8,19.3c-9.1,0-16.3-8.7-16.6-9c-0.3-0.4-0.3-0.9,0-1.3c0.3-0.4,7.5-9,16.6-9s16.3,8.7,16.6,9c0.3,0.4,0.3,0.9,0,1.3C33.2,10.7,26,19.3,16.8,19.3z M2.3,9.7c1.8,1.9,7.7,7.7,14.5,7.7c6.8,0,12.7-5.7,14.5-7.7C29.6,7.7,23.7,2,16.8,2C10,2,4.1,7.7,2.3,9.7z"/><path fill="currentColor" d="M16.8,15.3c-3.1,0-5.6-2.5-5.6-5.6c0-3.1,2.5-5.6,5.6-5.6s5.6,2.5,5.6,5.6C22.5,12.8,20,15.3,16.8,15.3zM16.8,6c-2,0-3.6,1.6-3.6,3.6s1.6,3.6,3.6,3.6s3.6-1.6,3.6-3.6S18.9,6,16.8,6z"/></svg><span><span>'+text+'</span></span>')
				.attr('class', 'button drawer vertical-right link-preview')
				.attr('href', url)
				.attr('target', '_blank');

			li.append(link);

			target.prepend(li);
		}
	};

	var init = function () {
		target = Symphony.Elements.context.find('.actions');
		if (!target.length) {
			target = $('<ul>').attr('class', 'actions');
			Symphony.Elements.breadcrumbs.after(target);
		}
		return $(FIELD_CLASS).each(hookOne);
	};

	$(init);

})(jQuery);
