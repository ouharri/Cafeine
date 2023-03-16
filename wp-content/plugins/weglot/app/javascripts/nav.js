jQuery(document).ready(function ($) {
	$('#update-nav-menu').bind('click', function (e) {

		if (e.target && e.target.className && -1 != e.target.className.indexOf('item-edit')) {

			$("input[value='#weglot_switcher'][type=text]").parents('.menu-item-settings').each(function () {
				const id = $(this).attr('id').substring(19);
				$(this).children('p:not( .field-move )').remove(); // remove default fields we don't need

				$(this).append($('<input>').attr({ // phpcs:ignore
					type: 'hidden',
					id: 'edit-menu-item-title-' + id,
					name: 'menu-item-title[' + id + ']',
					value: weglot_data.title
				}));

				$(this).append($("<input>").attr({ // phpcs:ignore
					type: "hidden",
					id: "edit-menu-item-url-" + id,
					name: "menu-item-url[" + id + "]",
					value: "#weglot_switcher"
				}));

				$(this).append($('<input>').attr({ // phpcs:ignore
					type: 'hidden',
					id: 'edit-menu-item-weglot-detect-' + id,
					name: 'menu-item-weglot-detect[' + id + ']',
					value: 1
				}));


				$.each(weglot_data.list_options, (key, option) => {
					const paragraph = $("<p>").attr("class", "description");
					const label = $("<label>")
						.attr("for", `edit-menu-item-${option.key}-${id}`)
						.text(` ${option.title}`);

					$(this).prepend(paragraph); // phpcs:ignore
					paragraph.append(label); // phpcs:ignore

					const checkbox = $("<input>").attr({
						type: "checkbox",
						id: `edit-menu-item-${
							option.key
						}-${id}`,
						name: `menu-item-weglot-${
							option.key
						}[${id}]`,
						value: 1
					});


					if (weglot_data.options && weglot_data.options[`menu-item-${id}`] && weglot_data.options[`menu-item-${id}`][ option.key ] === 1 ){
						checkbox.prop("checked", true);
					}

					label.prepend(checkbox); // phpcs:ignore
				})
			});

		}
	});
});
