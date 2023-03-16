const init_admin_select = function(){

    const $ = jQuery
    const generate_destination_language = () => {
        return weglot_languages.available.filter(itm => {
            return itm.internal_code !== $("#original_language").val()
        });
    }

    let destination_selectize

    const load_destination_selectize = () => {
        destination_selectize = $(".weglot-select-destination")
			.selectize({
				delimiter: "|",
				persist: false,
				valueField: "internal_code",
				labelField: "local",
				searchField: ["internal_code", "english", "local"],
				sortField: [{ field: "english", direction: "asc" }],
				maxItems: weglot_languages.limit,
				plugins: ["remove_button"],
				options: generate_destination_language(),
				render: {
					option: function(item, escape) {
						var english = escape(item.english);
						var local = escape(item.local);
						var external = escape(item.external_code);
						return `<div class="weglot__choice__language"><span class="weglot__choice__language--english">${english}</span><span class="weglot__choice__language--local">${local}[${external}]</span></div>`;
					}
				}
			})
			.on("change", (value) => {
				const code_languages = destination_selectize[0].selectize.getValue()

				const template = $("#li-button-tpl");

				if (template.length  === 0){
					return;
				}

				const is_fullname = $("#is_fullname").is(":checked")
				const with_name = $("#with_name").is(":checked")
				const with_flags = $("#with_flags").is(":checked")

				let classes = ''
				if (with_flags) {
					classes = "weglot-flags";
				}

				let new_dest_language = ''
				var currentFlagClasses = $("label.weglot-flags").attr("class")
				var classArr = currentFlagClasses.split(/\s+/);
				$.each(classArr, function(index, value){
					if(value.includes('flag-') == true){
						classes += ' '+value;
						return false;
					}
				});
				code_languages.forEach(element => {
					const language = weglot_languages.available.find(itm => itm.internal_code === element);
					let label = ''
					if(with_name){
						if (is_fullname){
							label = language.local
						}
						else{
							label = element.toUpperCase()
						}
					}

					new_dest_language += template
						.html()
						.replace("{LABEL_LANGUAGE}", label)
						.replace(new RegExp("{CODE_LANGUAGE}", "g"), element)
						.replace("{CLASSES}", classes)
				});
				$(".country-selector ul").html(new_dest_language) //phpcs:ignore

			});
    }

    const execute = () => {
		let work_original_language = $("#original_language").val()

		$("#original_language").on("change", function (e) {
			const old_original_language = work_original_language;
			const new_destination_option = work_original_language;
			work_original_language = e.target.value;
			destination_selectize[0].selectize.removeOption(work_original_language);

			const new_option = weglot_languages.available.find(itm => {
				return itm.internal_code === new_destination_option
			});

			const new_original_option = weglot_languages.available.find(itm => {
				return itm.internal_code === work_original_language;
			});

			destination_selectize[0].selectize.addOption(new_option);


			const is_fullname = $("#is_fullname").is(":checked")
			const with_name = $("#with_name").is(":checked")
			let label = ''
			if(with_name){
				label = is_fullname ? new_original_option.local : new_original_option.internal_code.toUpperCase();
			}

			$(".wgcurrent.wg-li")
				.removeClass(old_original_language)
				.addClass(work_original_language)
				.attr("data-code-language", work_original_language)
				.find('span').text(label)
		});


        load_destination_selectize();

        window.addEventListener("weglotCheckApi", (data) => {
            destination_selectize[0].selectize.settings.maxItems = weglot_languages.limit;
        });

    }

    document.addEventListener('DOMContentLoaded', () => {
        execute();
    })
}

export default init_admin_select;
