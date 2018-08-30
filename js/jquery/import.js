// $.woo.importJs("js/jquery/component.js");
// $.woo.importJs("js/jquery/dump.js");
// $.woo.importJs("js/jquery/menu/yxmenu.js");
// $.woo.importJs("js/jquery/grid/yxsgrid.js");
// $.woo.importJs("js/jquery/grid/yxgrid.js");
// $.woo.importJs("js/jquery/grid/yxegrid.js");
// $.woo.importJs("js/jquery/combo/yxcombo.js");
// $.woo.importJs("js/jquery/combo/yxcombogrid.js");
// $.woo.importJs("view/template/customer/customer/js/customer_edit_list.js");

var yxUtil = {
	// 进行分类，需求不同加载不同js，获得性能上的提升
	model : {
		base : {
			css : ["css/yxstyle.css"],
			js : ["js/jquery/woo.js", "js/jquery/component.js",
					"js/jquery/dump.js"]
		},
		all : {
			css : ["css/yxstyle.css", "js/jquery/style/yxgrid.css",
					"js/jquery/style/yxmenu.css",
					"js/jquery/style/yxmenu.theme.css"],
			js : ["js/jquery/jquery-1.4.2.js", "js/jquery/woo.js",
					"js/jquery/component.js", "js/jquery/dump.js",
					"js/jquery/menu/yxmenu.js", "js/jquery/grid/yxsgrid.js",
					"js/jquery/grid/yxgrid.js", "js/jquery/grid/yxegrid.js",
					"js/jquery/combo/yxcombo.js",
					"js/jquery/combo/yxcombogrid.js"]
		},
		sgrid : {
			depend : ["base"],
			css : ["js/jquery/style/yxgrid.css"],
			js : ["js/jquery/grid/yxsgrid.js"]
		},
		grid : {
			depend : ["sgrid"],
			css : ["js/jquery/style/yxmenu.css",
					"js/jquery/style/yxmenu.theme.css"],
			js : ["js/jquery/menu/yxmenu.js", "js/jquery/grid/yxgrid.js"]
		},
		egrid : {
			depend : ["grid"],
			css : ["js/jquery/style/yxgrid.css"],
			js : ["js/jquery/grid/yxsgrid.js", "js/jquery/grid/yxgrid.js",
					"js/jquery/grid/yxegrid.js"]
		},
		form : {
			depend : ["sgrid"],
			css : ["js/jquery/style/yxmenu.css",
					"js/jquery/style/yxmenu.theme.css"],
			js : ["js/jquery/combo/yxcombo.js",
					"js/jquery/combo/yxcombogrid.js"]
		}
	},

	// 判断浏览器类型
	Browser : {
		ie : /msie/.test(window.navigator.userAgent.toLowerCase()),
		moz : /gecko/.test(window.navigator.userAgent.toLowerCase()),
		opera : /opera/.test(window.navigator.userAgent.toLowerCase()),
		safari : /safari/.test(window.navigator.userAgent.toLowerCase())
	},
	/**
	 * 动态加入js
	 */
	importJs : function(jsArguments, jsIndex) {
		var jsLength = jsArguments.length;
		var _script = document.createElement('script');
		_script.setAttribute('charset', 'gbk');
		_script.setAttribute('type', 'text/javascript');
		_script.setAttribute('src', jsArguments[jsIndex]);
		document.getElementsByTagName('head')[0].appendChild(_script);
		if (yxUtil.Browser.ie) {
			_script.onreadystatechange = function() {
				if (this.readyState == 'loaded'
						|| this.readyState == 'complete') {
					var nextIndex = jsIndex + 1;
					if (nextIndex < jsLength) {
						yxUtil.importJs(jsArguments, nextIndex);
					}
				}
			};
		} else if (yxUtil.Browser.moz) {
			_script.onload = function() {
				var nextIndex = jsIndex + 1;
				if (nextIndex < jsLength) {
					yxUtil.importJs(jsArguments, nextIndex);
				}
			};
		} else {
		}
	},
	importCss : function(cssArr) {
		for (var i = 0, l = cssArr.length; i < l; i++) {
			var _script = document.createElement('link');
			_script.setAttribute('rel', 'stylesheet');
			_script.setAttribute('type', 'text/css');
			_script.setAttribute('href', cssArr[i]);
			document.getElementsByTagName('head')[0].appendChild(_script);
		}
	}
};
/**
 * 外部导入js入口
 */
function importJs() {
	importCss();
	var jsArr = ["js/jquery/jquery-1.4.2.js", "js/jquery/woo.js",
			"js/jquery/component.js", "js/jquery/dump.js",
			"js/jquery/menu/yxmenu.js", "js/jquery/grid/yxsgrid.js",
			"js/jquery/grid/yxgrid.js", "js/jquery/grid/yxegrid.js",
			"js/jquery/combo/yxcombo.js", "js/jquery/combo/yxcombogrid.js",
			"js/jquery/ztree/yxtree.js", "js/jquery/combo/yxcombotree.js",
			"js/jquery/combo/yxcombotext.js",
			"js/thickbox.js"];

	for (var i = 0, l = arguments.length; i < l; i++) {
		jsArr.push(arguments[i]);
	}
	yxUtil.importJs(jsArr, 0);
}
/**
 * 外部导入css入口
 */
function importCss() {
	var cssArr = ["css/yxstyle.css", "js/jquery/style/yxgrid.css",
			"js/jquery/style/yxmenu.css", "js/jquery/style/yxmenu.theme.css",
			"js/jquery/style/yxtree.css",
			"js/thickbox.css"];
	yxUtil.importCss(cssArr);
}
