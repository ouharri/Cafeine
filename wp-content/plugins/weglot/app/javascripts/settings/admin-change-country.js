const init_admin_change_country = function() {
	const $ = jQuery;

	if(typeof weglot_css !== "undefined"){
		$("#weglot-css-flag-css").text(weglot_css.flag_css);
	}

	function refresh_flag_css() {
		var en_flags = new Array();
		var es_flags = new Array();
		var fr_flags = new Array();
		var ar_flags = new Array();
		var tw_flags = new Array();
		var zh_flags = new Array();
		var pt_flags = new Array();

		en_flags[1] = [3570, 7841, 48, 2712];
		en_flags[2] = [3720, 449, 3048, 4440];
		en_flags[3] = [3840, 1281, 2712, 4224];
		en_flags[4] = [3240, 5217, 1224, 2112];
		en_flags[5] = [4050, 3585, 1944, 2496];
		en_flags[6] = [2340, 3457, 2016, 2016];

		es_flags[1] = [4320, 4641, 3144, 3552];
		es_flags[2] = [3750, 353, 2880, 4656];
		es_flags[3] = [4200, 1601, 2568, 3192];
		es_flags[4] = [3990, 5793, 1032, 2232];
		es_flags[5] = [5460, 897, 4104, 3120];
		es_flags[6] = [3810, 7905, 216, 3888];
		es_flags[7] = [3630, 8065, 192, 2376];
		es_flags[8] = [3780, 1473, 2496, 4104];
		es_flags[9] = [6120, 2145, 4680, 2568];
		es_flags[10] = [4440, 3009, 3240, 1176];
		es_flags[11] = [5280, 1825, 3936, 2976];
		es_flags[12] = [4770, 2081, 3624, 1008];
		es_flags[13] = [4080, 3201, 2160, 2544];
		es_flags[14] = [4590, 5761, 3432, 624];
		es_flags[15] = [4350, 2209, 3360, 2688];
		es_flags[16] = [5610, 5249, 3168, 528];
		es_flags[17] = [5070, 1729, 3792, 2952];
		es_flags[18] = [6870, 5953, 96, 3408];
		es_flags[19] = [4020, 5697, 1056, 1224];

		fr_flags[1] = [2760, 736, 2856, 4416];
		fr_flags[2] = [3840, 1280, 2712, 4224];
		fr_flags[3] = [5700, 7201, 5016, 2400];
		fr_flags[4] = [2220, 4160, 1632, 1944];

		ar_flags[1] = [1830, 129, 3096, 5664];
		ar_flags[2] = [5100, 2177, 3840, 2904];
		ar_flags[3] = [4890, 3425, 3648, 2136];
		ar_flags[4] = [1320, 3681, 1896, 4080];
		ar_flags[5] = [1260, 3841, 1824, 1200];
		ar_flags[6] = [1020, 3969, 1608, 312];
		ar_flags[7] = [4800, 4065, 3600, 72];
		ar_flags[8] = [4710, 4865, 3504, 480];
		ar_flags[9] = [6720, 5984, 5112, 3792];
		ar_flags[10] = [4500, 7233, 3288, 1800];
		ar_flags[11] = [720, 7522, 384, 3936];
		ar_flags[12] = [690, 7745, 336, 1104];
		ar_flags[13] = [600, 8225, 120, 1272];
		ar_flags[14] = [660, 5569, 840, 576];

		tw_flags[1] = [3690, 1505, 2592, 3240]; // China
		tw_flags[2] = [3600, 3233, 2112, 48]; // Hong Kong

		zh_flags[1] = [2970, 6369, 3408, 4008]; // Taiwan
		zh_flags[2] = [3600, 3233, 2112, 48]; // Hong Kong

		pt_flags[1] = [6630, 993, 2784, 4344];

		var enval = $("select.flag-en-type").val();
		var esval = $("select.flag-es-type").val();
		var frval = $("select.flag-fr-type").val();
		var arval = $("select.flag-ar-type").val();
		var twval = $("select.flag-tw-type").val();
		var zhval = $("select.flag-zh-type").val();
		var ptval = $("select.flag-pt-type").val();
		var en_style = enval <= 0 ? "" : ".weglot-flags.en > a:before, .weglot-flags.en > span:before { background-position: -" + en_flags[enval][0] + "px 0 !important; } .weglot-flags.flag-1.en > a:before, .weglot-flags.flag-1.en > span:before { background-position: -" + en_flags[enval][1] + "px 0 !important; } .weglot-flags.flag-2.en > a:before, .weglot-flags.flag-2.en > span:before { background-position: -" + en_flags[enval][2] + "px 0 !important; } .weglot-flags.flag-3.en > a:before, .weglot-flags.flag-3.en > span:before { background-position: -" + en_flags[enval][3] + "px 0 !important; } ";
		var es_style = esval <= 0 ? "" : ".weglot-flags.es > a:before, .weglot-flags.es > span:before { background-position: -" + es_flags[esval][0] + "px 0 !important; } .weglot-flags.flag-1.es > a:before, .weglot-flags.flag-1.es > span:before { background-position: -" + es_flags[esval][1] + "px 0 !important; } .weglot-flags.flag-2.es > a:before, .weglot-flags.flag-2.es > span:before { background-position: -" + es_flags[esval][2] + "px 0 !important; } .weglot-flags.flag-3.es > a:before, .weglot-flags.flag-3.es > span:before { background-position: -" + es_flags[esval][3] + "px 0 !important; } ";
		var fr_style = frval <= 0 ? "" : ".weglot-flags.fr > a:before, .weglot-flags.fr > span:before { background-position: -" + fr_flags[frval][0] + "px 0 !important; } .weglot-flags.flag-1.fr > a:before, .weglot-flags.flag-1.fr > span:before { background-position: -" + fr_flags[frval][1] + "px 0 !important; } .weglot-flags.flag-2.fr > a:before, .weglot-flags.flag-2.fr > span:before { background-position: -" + fr_flags[frval][2] + "px 0 !important; } .weglot-flags.flag-3.fr > a:before, .weglot-flags.flag-3.fr > span:before { background-position: -" + fr_flags[frval][3] + "px 0 !important; } ";
		var ar_style = arval <= 0 ? "" : ".weglot-flags.ar > a:before, .weglot-flags.ar > span:before { background-position: -" + ar_flags[arval][0] + "px 0 !important; } .weglot-flags.flag-1.ar > a:before, .weglot-flags.flag-1.ar > span:before { background-position: -" + ar_flags[arval][1] + "px 0 !important; } .weglot-flags.flag-2.ar > a:before, .weglot-flags.flag-2.ar > span:before { background-position: -" + ar_flags[arval][2] + "px 0 !important; } .weglot-flags.flag-3.ar > a:before, .weglot-flags.flag-3.ar > span:before { background-position: -" + ar_flags[arval][3] + "px 0 !important; } ";
		var tw_style = twval <= 0 ? "" : ".weglot-flags.tw > a:before, .weglot-flags.tw > span:before { background-position: -" + tw_flags[twval][0] + "px 0 !important; } .weglot-flags.flag-1.tw > a:before, .weglot-flags.flag-1.tw > span:before { background-position: -" + tw_flags[twval][1] + "px 0 !important; } .weglot-flags.flag-2.tw > a:before, .weglot-flags.flag-2.tw > span:before { background-position: -" + tw_flags[twval][2] + "px 0 !important; } .weglot-flags.flag-3.tw > a:before, .weglot-flags.flag-3.tw > span:before { background-position: -" + tw_flags[twval][3] + "px 0 !important; } ";
		var zh_style = zhval <= 0 ? "" : ".weglot-flags.zh > a:before, .weglot-flags.zh > span:before { background-position: -" + zh_flags[zhval][0] + "px 0 !important; } .weglot-flags.flag-1.zh > a:before, .weglot-flags.flag-1.zh > span:before { background-position: -" + zh_flags[zhval][1] + "px 0 !important; } .weglot-flags.flag-2.zh > a:before, .weglot-flags.flag-2.zh > span:before { background-position: -" + zh_flags[zhval][2] + "px 0 !important; } .weglot-flags.flag-3.zh > a:before, .weglot-flags.flag-3.zh > span:before { background-position: -" + zh_flags[zhval][3] + "px 0 !important; } ";
		var pt_style = ptval <= 0 ? "" : ".weglot-flags.pt > a:before, .weglot-flags.pt > span:before { background-position: -" + pt_flags[ptval][0] + "px 0 !important; } .weglot-flags.flag-1.pt > a:before, .weglot-flags.flag-1.pt > span:before { background-position: -" + pt_flags[ptval][1] + "px 0 !important; } .weglot-flags.flag-2.pt > a:before, .weglot-flags.flag-2.pt > span:before { background-position: -" + pt_flags[ptval][2] + "px 0 !important; } .weglot-flags.flag-3.pt > a:before, .weglot-flags.flag-3.pt > span:before { background-position: -" + pt_flags[ptval][3] + "px 0 !important; } ";

		$("#flag_css, #weglot-css-flag-css").text(en_style + es_style + fr_style + ar_style + tw_style + zh_style + pt_style);
	}

	const execute = () => {

		$('.flag-style-openclose').on('click',
			function () {
				$('.flag-style-wrapper').toggle();
			}
		);

		$('.old-flag-style').on('click',
			function () {
				$('.old-flag-wrapper').toggle();
			}
		);

		$("select.flag-en-type, select.flag-es-type, select.flag-pt-type, select.flag-fr-type, select.flag-ar-type, select.flag-tw-type, select.flag-zh-type").on('change',
			function () {
				refresh_flag_css()
			}
		);

		var flag_css = $("#flag_css").text();
		if (flag_css.trim()) {
			$("#weglot-css-flag-css").text(flag_css);
		}

	};

	document.addEventListener("DOMContentLoaded", () => {
		execute();
	});
};

export default init_admin_change_country;

