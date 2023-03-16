const init_private_mode = function () {

	const $ = jQuery

	const execute = () => {
		document.querySelector("#private_mode").addEventListener('change', function(e) {

			document.querySelectorAll(".private-mode-lang--input").forEach((itm) => {
				itm.checked = e.target.checked;
			})
		})

		document.querySelectorAll(".private-mode-lang--input").forEach((itm) => {
			itm.addEventListener('change', function(e){
				if (document.querySelectorAll(".private-mode-lang--input:checked").length === 0){
					document.querySelector("#private_mode").checked = false
				}
			})
		});
	}

	document.addEventListener('DOMContentLoaded', () => {
		const private_mode = document.querySelector("#private_mode")
		if (private_mode && private_mode.length != 0){
			execute();
		}
	})
}

export default init_private_mode;

