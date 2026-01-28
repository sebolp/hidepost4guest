/* global jQuery */

(function($) {
	'use strict';

	// Listen for clicks on the master checkbox
	$('#all_forums').on('click', function() {

		// Get the current status of the master checkbox (checked or unchecked)
		var isChecked = $(this).prop('checked');

		// Iterate over all checkboxes with the name 'forum_id[]'
		$('input[name="forum_id[]"]').each(function() {
			// Only change the status if the checkbox is not disabled
			if (!$(this).prop('disabled'))
			{
				$(this).prop('checked', isChecked);
			}
		});
	});

})(jQuery);
