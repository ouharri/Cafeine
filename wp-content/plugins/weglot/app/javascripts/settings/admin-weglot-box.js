

const init_admin_weglot_box = function () {
	const $ = jQuery

	const execute = () => {
		$("#weglot-box-first-settings .weglot-btn-close").on("click", function(e) {
			e.preventDefault();
			$("#weglot-box-first-settings").hide();
		})
	}

	document.addEventListener('DOMContentLoaded', () => {
		execute();
	})
}

export default init_admin_weglot_box;

