const init_admin_button_preview = function () {
	const $ = jQuery

	const execute = () => {
		// Init old type flags
		let old_type_flags = $("#type_flags option:selected").data('value')

		let destination_languages = []
		destination_languages.push($(".country-selector label").data("code-language"));
		$(".country-selector li").each((key, itm) => {
			destination_languages.push($(itm).data("code-language"));
		})

		const weglot_desination_languages = weglot_languages.available.filter(itm => {
			return destination_languages.indexOf(itm.external_code) >= 0;
		})

		$("#weglot-css-inline").text(weglot_css.inline);

		// Change dropdown
		$("#is_dropdown").on("change", function(){
			$(".country-selector").toggleClass("weglot-inline");
            $(".country-selector").toggleClass("weglot-dropdown");
		})

		// Change with flags
		$("#with_flags").on("change", function() {
			$(".country-selector label, .country-selector li").toggleClass("weglot-flags");
		});

		// Change type flags
		$("#type_flags").on("change", function(e) {
			$(".country-selector label, .country-selector li").removeClass(`flag-${old_type_flags}`);
			const new_type_flags = $(':selected', this).data('value')
			$(".country-selector label, .country-selector li").addClass(`flag-${new_type_flags}`);
			old_type_flags = new_type_flags;
		});

		const set_languages = () => {
			const label_language = weglot_desination_languages.find(
				(itm) => itm.external_code === $(".country-selector label").data("code-language")
			);
			const is_fullname = $("#is_fullname").is(":checked");

			const label = is_fullname ? label_language.local : label_language.internal_code.toUpperCase();

			$(".country-selector label a, .country-selector label span").text(label);

			$(".country-selector li").each((key, itm) => {
				const li_language = weglot_desination_languages.find(
					(lang) => lang.internal_code === $(itm).data("code-language")
				);

				const label = is_fullname ? li_language.local : li_language.internal_code.toUpperCase();

				$(itm)
					.find("a")
					.text(label);
			})
		}

		// Change with name
		$("#with_name").on("change", function(e) {
			if (e.target.checked) {
				set_languages();
			} else {
				$(".country-selector label a, .country-selector label span").text("");
				$(".country-selector li a, .country-selector li span").each(
					(key, itm) => {
						$(itm).text("");
					}
				);
			}
		});



		$("#is_fullname").on("change", function(e){
			if ( !$("#with_name").is(":checked") ) {
				return
			}

			if (e.target.checked) {
				set_languages();
			}
			else {
				const label_language = weglot_desination_languages.find(itm => itm.internal_code === $(".country-selector label").data("code-language"));

				$(".country-selector label a, .country-selector label span").text(label_language.internal_code.toUpperCase());
				$(".country-selector li").each((key, itm) => {
					const language = weglot_desination_languages.find(lang => lang.internal_code === $(itm).data("code-language"));

					$(itm).find("a").text(language.internal_code.toUpperCase());
					$(itm).find("span").text(language.internal_code.toUpperCase());
				});
			}
		});

		$("#override_css").on("keyup", function(e) {
			$("#weglot-css-inline").text(e.target.value);
		})
	}

	document.addEventListener('DOMContentLoaded', () => {
		if ($(".weglot-preview").length === 0){
			return
		}

		execute();
	})
}

export default init_admin_button_preview;

