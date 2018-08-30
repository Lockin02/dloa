/**
 * 
 * @author chengl
 * @version 1.0
 * 
 * jquery调试输出插件 功能特色：
 * 
 * 1 提供浏览友好的变量和对象输出 2 输出不影响页面布局和进程 3 点击了一次输出的内容后，鼠标移离插件会自动关闭移除
 * 
 * 使用方法： 1 可直接输出简单字符串：$.showDump('我为什么要弄这个'); 2 可以输出对象 $.showDump(
 * {第一行:'这插件非常好玩',第二行:'为什么会这么好玩'} ); 3 可以输出jquery对象 $('div').showDump();
 */
(function($) {
	/**
	 * 格式化显示信息
	 */
	$.show = function(msg, isDebug) {
		isDebug = isDebug == false ? false : true;
		if (typeof msg === "string") {
			msg = msg.indexOf('|') != -1 && '<p>'
					+ msg.split('|').join('</p><p>') || '<p>' + msg;

		}
		if (typeof msg === 'object') {

			var msgs = new Array();
			for (var p in msg) {
				msgs.push(p + ':&nbsp;' + msg[p]);
			}
			msg = '<p>' + msgs.join('</p><p>');

		}
		if (isDebug) {
			alert(msg);
		} else {
			if (!$('debug_dump')[0]) {
				$('body').prepend('<div id="debug_dump"></div>');
			}

			$('div#debug_dump').css({
						position : 'absolute',
						backgroundColor : '#000',
						opacity : '0.65',
						top : '0',
						left : '0',
						width : $('body').width(),
						lineHeight : '28px',
						zIndex : '99999',
						textIndent : '20px',
						color : '#fff'
					}).html('输出结果是： <pre>' + msg + '</pre></p>').click(
					function() {

						$(this).mouseout(function() {
							setTimeout(function() {
										$('div#debug_dump').fadeOut('slow')
												.empty().remove();
									}, 1000);
						});

					});
		}
	};
	/**
	 * 格式化显示对象信息
	 */
	$.showDump = function(object) {
		$.show($.dump(object));
	};
	/**
	 * 格式化显示jquery对象信息
	 */
	$.fn.showDump = function() {
		$.show($.dump(this));
	}

	$.fn.dump = function() {
		return $.dump(this);
	}

	$.dump = function(object) {
		var recursion = function(obj, level) {
			if (!level)
				level = 0;
			var dump = '', p = '';
			for (i = 0; i < level; i++)
				p += "\t";

			t = type(obj);
			switch (t) {
				case "string" :
					return '"' + obj + '"';
					break;
				case "number" :
					return obj.toString();
					break;
				case "boolean" :
					return obj ? 'true' : 'false';
				case "date" :
					return "Date: " + obj.toLocaleString();
				case "array" :
					dump += 'Array ( \n';
					$.each(obj, function(k, v) {
								dump += p + '\t' + k + ' => '
										+ recursion(v, level + 1) + '\n';
							});
					dump += p + ')';
					break;
				case "object" :
					dump += 'Object { \n';
					$.each(obj, function(k, v) {
								dump += p + '\t' + k + ': '
										+ recursion(v, level + 1) + '\n';
							});
					dump += p + '}';
					break;
				case "jquery" :
					dump += 'jQuery Object { \n';
					$.each(obj, function(k, v) {
								dump += p + '\t' + k + ' = '
										+ recursion(v, level + 1) + '\n';
							});
					dump += p + '}';
					break;
				case "regexp" :
					return "RegExp: " + obj.toString();
				case "error" :
					return obj.toString();
				case "document" :
				case "domelement" :
					dump += 'DOMElement [ \n' + p + '\tnodeName: '
							+ obj.nodeName + '\n' + p + '\tnodeValue: '
							+ obj.nodeValue + '\n' + p + '\tinnerHTML: [ \n';
					$.each(obj.childNodes, function(k, v) {
								if (k < 1)
									var r = 0;
								if (type(v) == "string") {
									if (v.textContent.match(/[^\s]/)) {
										dump += p + '\t\t' + (k - (r || 0))
												+ ' = String: '
												+ trim(v.textContent) + '\n';
									} else {
										r--;
									}
								} else {
									dump += p + '\t\t' + (k - (r || 0)) + ' = '
											+ recursion(v, level + 2) + '\n';
								}
							});
					dump += p + '\t]\n' + p + ']';
					break;
				case "function" :
					var match = obj.toString().match(/^(.*)\(([^\)]*)\)/im);
					match[1] = trim(match[1].replace(new RegExp("[\\s]+", "g"),
							" "));
					match[2] = trim(match[2].replace(new RegExp("[\\s]+", "g"),
							" "));
					return match[1] + "(" + match[2] + ")";
				case "window" :
				default :
					dump += 'N/A: ' + t;
					break;
			}

			return dump;
		}

		var type = function(obj) {
			var type = typeof(obj);

			if (type != "object") {
				return type;
			}

			switch (obj) {
				case null :
					return 'null';
				case window :
					return 'window';
				case document :
					return 'document';
				case window.event :
					return 'event';
				default :
					break;
			}

			if (obj.jquery) {
				return 'jquery';
			}

			switch (obj.constructor) {
				case Array :
					return 'array';
				case Boolean :
					return 'boolean';
				case Date :
					return 'date';
				case Object :
					return 'object';
				case RegExp :
					return 'regexp';
				case ReferenceError :
				case Error :
					return 'error';
				case null :
				default :
					break;
			}

			switch (obj.nodeType) {
				case 1 :
					return 'domelement';
				case 3 :
					return 'string';
				case null :
				default :
					break;
			}

			return 'Unknown';
		}

		return recursion(object);
	}

	function trim(str) {
		return ltrim(rtrim(str));
	}

	function ltrim(str) {
		return str.replace(new RegExp("^[\\s]+", "g"), "");
	}

	function rtrim(str) {
		return str.replace(new RegExp("[\\s]+$", "g"), "");
	}

})(jQuery);