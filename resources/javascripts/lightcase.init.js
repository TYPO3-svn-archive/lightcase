jQuery.noConflict();

(function($) {
	$(function() {
		if (typeof language !== 'undefined') {
			$.getJSON('typo3temp/lightcase/' + language + '.locallang.json', function(data) {
				lightcase.labels = data;
			});
		}
		$('a[data-rel^=lightcase]').lightcase('init');
	});
})(jQuery);