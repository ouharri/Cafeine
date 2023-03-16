const init_admin_exclusion = function () {

	const execute = () => {
		const template_add_exclude_url = document.querySelector("#tpl-exclusion-url");
		const template_add_exclude_block = document.querySelector("#tpl-exclusion-block");
		const parent_exclude_block_append = document.querySelector("#container-exclude_blocks");

		function removeLine(e) {
			e.preventDefault()
			this.parentNode.remove()
		}

		if (document.querySelector("#js-add-exclude-block")) {

			const input_exclude_blocks = document.querySelectorAll('#container-exclude_blocks input')
			input_exclude_blocks.forEach((el) => {
				el.addEventListener('keypress', function (e) {
					if (e.keyCode === 13 || e.which === 13) {
						e.preventDefault();
						return false;
					}
				});
			})
			document.querySelectorAll('#container-exclude_blocks input')

			document
				.querySelector("#js-add-exclude-block")
				.addEventListener("click", (e) => {
					let available_input = true
					const input_exclude_blocks = document.querySelectorAll('#container-exclude_blocks input')
					input_exclude_blocks.forEach((el) => {
						if (el.value.length === 0) {
							available_input = false
						}
					})
					e.preventDefault()
					if (available_input === true) {
						document.querySelector("#js-add-exclude-block").classList.add("disable-btn");
						parent_exclude_block_append.insertAdjacentHTML("beforeend", template_add_exclude_block.innerHTML);
						document
							.querySelector(
								"#container-exclude_blocks .item-exclude:last-child .js-btn-remove-exclude"
							)
							.addEventListener("click", removeLine);

						document.querySelectorAll('#container-exclude_blocks input').forEach((el) => {
							el.addEventListener('keypress', function (e) {
								if (e.keyCode === 13 || e.which === 13) {
									e.preventDefault();
									return false;
								} else {
									document.querySelector("#js-add-exclude-block").classList.remove("disable-btn");
								}
							});
						})
					}

				});
		}

		const remove_urls = document
			.querySelectorAll(".js-btn-remove")

		remove_urls.forEach((el) => {
			el.addEventListener("click", removeLine);
		})


	}

	document.addEventListener('DOMContentLoaded', () => {
		execute();
	})
}

export default init_admin_exclusion;

