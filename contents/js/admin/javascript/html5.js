(
	function () {
		var e = 'abbr article aside audio bb canvas datagrid datalist details dialog figure footer header hgroup mark menu meter nav output progress section time video'.split(' ');
		i = e.length;
		while (i--) {
			document.createElement(e[i]);
		}
	}
)();

jQuery(document).ready(
	function () {
		jQuery("a[@href^='http']:not(a[@href*='" + window.location.host.toLowerCase() + "'])").attr("target", "_blank");
	}
);