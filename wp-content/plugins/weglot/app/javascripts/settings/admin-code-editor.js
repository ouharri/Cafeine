const init_admin_weglot_code_editor = function () {
	const $ = jQuery

	const execute = () => {
		jQuery(document).ready(function($) {
			wp.codeEditor.initialize($('#override_css'), cm_settings);
		  })
	}

	document.addEventListener('DOMContentLoaded', () => {
		execute();
	})
}

export default init_admin_weglot_code_editor;
