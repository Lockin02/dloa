var MENU_ITEM_HEIGHT = 30;
var MIN_PNAEL_HEIGHT = 8 * MENU_ITEM_HEIGHT;
var MAX_PNAEL_HEIGHT = 20 * MENU_ITEM_HEIGHT;
var SCROLL_HEIGHT = 4 * MENU_ITEM_HEIGHT;
var nextTabId = 0;
var OA_TIME = new Date();

(function($) {
	$.fn.addTab = function(id, title, url, closable, selected, isLoad) {
		if (!id)
			return;
		closable = (typeof(closable) == 'undefined') ? true : closable;
		selected = (typeof(selected) == 'undefined') ? true : selected;
		var height = '100%';
		$('#tabs_container').tabs('add', {
			id : id,
			title : title,
			closable : closable,
			selected : selected,
			style : 'height:' + height + ';',
			isLoad : isLoad,
			content : '<iframe id="tabs_'
					+ id
					+ '_iframe" name="tabs_'
					+ id
					+ '_iframe" src="'
					+ url
					+ '" onload="IframeLoaded(\''
					+ id
					+ '\');" border="0" frameborder="0" framespacing="0" marginheight="0" marginwidth="0" style="scrolling:no;width:100%;height:'
					+ height + ';"></iframe>'
		});
	};
	$.fn.selectTab = function(id) {
		$('#tabs_container').tabs('select', id);
	};
	$.fn.closeTab = function(id) {
		$('#tabs_container').tabs('close', id);
	};
	$.fn.getSelected = function() {
		return $('#tabs_container').tabs('selected');
	};

	function checkActive(id) {
		if ($('#' + id + '_panel:hidden').length > 0)
			$('#' + id).removeClass('active');
		else
			window.setTimeout(checkActive, 300, id);
	};

	/**
	 * 获取二级及三级菜单
	 *
	 * @param id
	 */
	function getSecondMenuHTML(id) {
		var html = '';
		if (second_array[id] == undefined || second_array[id] == null
				|| second_array[id] == '') {
			return "";
		}
		for (var i = 0; i < second_array[id].length; i++) {
			var func_id = 'f' + second_array[id][i];
			var func_name = func_array[func_id][0];
			var func_code = func_array[func_id][1];
			var func_img = func_array[func_id][2]
					? func_array[func_id][2]
					: 'default';
			var open_window = func_array[func_id][3]
					? func_array[func_id][3]
					: '';
			var bExpand = func_code.substr(0, 1) == "@" && third_array[func_id];
			bExpand = false;// 先写死
			var onclick = "createTab(" + func_id.substr(1) + ",'"
					+ func_name.replace("'", "\'") + "','"
					+ func_code.replace("'", "\'") + "','" + open_window
					+ "');";
			html += '<li><a id="' + func_id + '" href="javascript:;" onclick="'
					+ onclick + '"' + (bExpand ? ' class="expand"' : '')
					+ ' hidefocus="hidefocus"><span style="background: url(\''
					+ CSS_PATH + func_img + '.png\') -2px 5px no-repeat;">'
					+ func_name + '</span></a>';
			// 判断生成三级菜单
			if (bExpand) {
				html += '<ul>';
				for (var j = 0; j < third_array[func_id].length; j++) {
					var func_id1 = 'f' + third_array[func_id][j];
					var fa = func_array[func_id1];
					var func_name1 = fa[0];
					var func_code1 = fa[1];
					var open_window1 = fa[3] ? fa[3] : '';
					var isSystem = fa[4] ? fa[4] : false;
					if (isSystem) {
						var onclick1 = "createTab(" + func_id1.substr(1) + ",'"
								+ func_name.replace("'", "\'") + "','"
								+ func_code1.replace("'", "\'") + "','"
								+ open_window1 + "',true,true,true,'"
								+ second_array[id][i] + "');";
						html += '<li><a id="' + func_id1
								+ '" href="javascript:;" onclick="' + onclick1
								+ '" hidefocus="hidefocus"><span>' + func_name1
								+ '</span></a></li>';
					}
				}
				html += '</ul>';
			}
			html += '</li>';
		}

		return '<ul id="second_menu">' + html + '</ul>';
	};

	function resizeLayout() {
		// 主操作区域高度
		var wWidth = (window.document.documentElement.clientWidth
				|| window.document.body.clientWidth || window.innerHeight);
		var wHeight = (window.document.documentElement.clientHeight
				|| window.document.body.clientHeight || window.innerHeight);
		var nHeight = $('#north').is(':visible')
				? $('#north').outerHeight()
				: 0;
		var cHeight = wHeight - nHeight - $('#south').outerHeight()
				- $('#taskbar').outerHeight();

		$('#center').height(cHeight);
		$("#center iframe").css({
					height : cHeight
				});

		// 一级标签宽度
		var width = wWidth - $('#taskbar_left').outerWidth()
				- $('#taskbar_right').outerWidth();
		// $('#tabs_container').width(width -
		// $('#tabs_left_scroll').outerWidth() -
		// $('#tabs_right_scroll').outerWidth());
		$('#tabs_container').width(width - $('#tabs_left_scroll').outerWidth()
				- $('#tabs_right_scroll').outerWidth() - 2);
		$('#taskbar_center').width(width - 1); // -1是为了兼容iPad

		$('#tabs_container').triggerHandler('_resize');
	}

	;

	// 菜单滚动箭头事件,id为first_menu
	function initMenuScroll(id) {
		// 菜单向上滚动箭头事件
		$('#' + id + ' > .scroll-up:first').hover(function() {
					$(this).addClass('scroll-up-hover');
					if (id == 'first_panel') {
						$("#first_menu > li > a.active").removeClass('active'); // 恢复一级active的菜单为正常
					}
				}, function() {
					$(this).removeClass('scroll-up-hover');
				});

		// 点击向上箭头
		$('#' + id + ' > .scroll-up:first').click(function() {
					var ul = $('#' + id + ' > ul:first');
					ul.animate({
								'scrollTop' : (ul.scrollTop() - SCROLL_HEIGHT)
							}, 600);
				});

		// 向下滚动箭头事件
		$('#' + id + ' > .scroll-down:first').hover(function() {
					$(this).addClass('scroll-down-hover');
					if (id == 'first_panel') {
						$("#first_menu > li > a.active").removeClass('active'); // 恢复一级级active的菜单为正常
					}
				}, function() {
					$(this).removeClass('scroll-down-hover');
				});

		// 点击向下箭头
		$('#' + id + ' > .scroll-down:first').click(function() {
					var ul = $('#' + id + ' > ul:first');
					ul.animate({
								'scrollTop' : (ul.scrollTop() + SCROLL_HEIGHT)
							}, 600);
				});
	};

	/**
	 * 后台获取菜单数据 add by chengl 20120504
	 */
	function getMenuData() {
		var data = $.ajax({
					type : 'POST',
					url : "index1.php?model=system_menu_menu&action=getMenuData",
					async : false
				}).responseText;
		// alert(data);
		data = eval("(" + data + ")");
		first_array = data.first_array;
		second_array = data.second_array;
		third_array = data.third_array;
		func_array = data.func_array;
	}

	/**
	 * 初始化菜单
	 */
	function initStartMenu() {

		// 点击页面，隐藏各级菜单面板，并清除二级和三级菜单的active状态
		$('#overlay_startmenu').click(function() {
					if ($('#start_menu_panel:visible').length) {
						$('#overlay_startmenu').hide();
						$('#start_menu_panel').slideUp(300);
						$('#start_menu').removeClass('active');
					}
				});

		// 鼠标点击导航图标按钮弹出菜单面板
		$('#start_menu').bind('click', function() {
			if ($('#start_menu_panel:visible').length) {
				$('#overlay_startmenu').hide();
				$('#start_menu_panel').slideUp(300);
				$(this).removeClass('active');
			}
			// 设置导航图标为active状态
			$(this).addClass('active');

			// 遮罩层位置和显示
			$('#overlay_startmenu').show();

			// 菜单面板位置
			var top = $('#start_menu').offset().top
					+ $('#start_menu').outerHeight() - 6;
			$('#start_menu_panel').css({
						top : top
					});
			$('#start_menu_panel').slideDown('fast');

			// //计算并设置菜单面板的高度,是否显示滚动箭头
			var scrollHeight = $("#first_menu").attr('scrollHeight');
			if ($("#first_menu").height() < scrollHeight) {
				var height = ($('#south').offset().top - $('#start_menu')
						.offset().top)
						* 0.7; // 可用高度为开始菜单和状态栏高差的70%
				height = height - height % MENU_ITEM_HEIGHT; // 可用高度为
				// MENU_ITEM_HEIGHT
				// 的整数倍
				// 如果可用高度大于允许的最高高度，则限制
				height = height <= MAX_PNAEL_HEIGHT ? height : MAX_PNAEL_HEIGHT;
				// 如果可用高度超过scrollHeight，则设置高度为scrollHeight
				height = height > scrollHeight ? scrollHeight : height;
				$('#first_menu').height(height);
			} else {
				var height = scrollHeight > MIN_PNAEL_HEIGHT
						? scrollHeight
						: MIN_PNAEL_HEIGHT;
				$('#first_menu').height(height);
			}

			if ($("#first_menu").height() >= $("#first_menu")
					.attr('scrollHeight')) {
				$('#first_panel > .scroll-up:first').hide();
				$('#first_panel > .scroll-down:first').hide();
			}

			// 计算并设置二级菜单面板的位置
			var top = $('#first_menu').offset().top
					- $("#start_menu_panel").offset().top;
			$('#second_panel').css('top', top - 5);
			$('#second_panel > .second-panel-menu').css('height',
					$('#first_menu').height());

			// 第一次打开时设置二级菜单滚动事件
			if ($('#second_panel > .second-panel-menu > .jscroll-c').length <= 0) {
				$('#second_panel > .second-panel-menu').jscroll();
			}

			// 恢复一级菜单为正常
			$("#first_menu > li").removeClass('active');

			// 恢复二级菜单为系统介绍
			$('#second_panel > .second-panel-menu').html(explain_array["0"]);
		});

		// 生成一级菜单
		var html = "";
		for (var i = 0; i < first_array.length; i++) {

			var menu_id = first_array[i];
			if (typeof(func_array['m' + menu_id]) != "object")
				continue;
			var isSystem = func_array['m' + menu_id][1];
			var image = !func_array['m' + menu_id][2]
					? 'icon_default'
					: func_array['m' + menu_id][2];
			html += '<li><img class="menu_img" src="' + CSS_PATH + image
					+ '.png"  /><a isSystem="' + isSystem + '" id="m' + menu_id
					+ '" href="javascript:;" hidefocus="hidefocus"> '
					+ func_array['m' + menu_id][0] + '</a></li>';
		}
		$("#first_menu").html(html);
		$("#first_menu").mousewheel(function() {
					$('#first_menu').stop().animate({
								'scrollTop' : ($('#first_menu').scrollTop() - this.D)
							}, 300);
				});

		// 一级菜单滚动箭头事件
		initMenuScroll('first_panel');

		// 一级菜单hover事件
		$("#first_menu > li > a").hover(function() {
			$("#second_menu > li > a.expand").removeClass('active'); // 恢复二级expand菜单为正常
			$("#first_menu > li.active").removeClass('active'); // 恢复一级active的菜单为正常

			// 获取当前一级菜单下属二级菜单的HTML代码，并更新二级菜单面板
			if ($(this).attr('isSystem') == 1
					|| getSecondMenuHTML(this.id) == "") { // 当前一级菜单下如果没有二级菜单，显示一级菜单的介绍并绑定click事件
				var obj = func_array[this.id];
				if (explain_array[this.id.substr(1)]) {
					$('#second_panel > .second-panel-menu')
							.html(explain_array[this.id.substr(1)]);
				} else {
					$('#second_panel > .second-panel-menu').html("子系统直接点击进入");
				}
				$(this).unbind('click').removeAttr('onclick').click(function() {
							createTab(this.id.substring(1), obj[0], "#", '');
						});
			} else {

				var html = getSecondMenuHTML(this.id);
				$('#second_panel > .second-panel-menu').html(html);

				// 二级级菜单滚动事件
				$('#second_panel > .second-panel-menu').jscroll();

				// 二级菜单点击展开三级菜单，先删除再绑定事件，避免事件累加
				$('#second_menu > li > a.expand').unbind('click')
						.removeAttr('onclick').click(function() {
									$(this).toggleClass('active');
									$(this).parent().children('ul').toggle();
									$('#second_panel > .second-panel-menu')
											.jscroll();
								});
				// $('#second_menu > li > a.expand').mouseover(function() {
				// var $top = $(this).parent().parent();
				// $top.find('a').removeClass('active');
				// // alert($(this).parent().parent().html())
				// var ul = $(this).parent().children('ul');
				// if (ul.is(":visible") == false) {
				// $(this).addClass('active');
				// $top.find('ul').hide("slow");
				// $(this).parent().children('ul').show("slow");
				// }
				// $('#second_panel > .second-panel-menu').jscroll();
				// });
			}
			// 将当前一级菜单设为active
			$("#" + this.id).parent().addClass('active');

		}, function() {

		});

		$('#second_panel > .second-panel-menu').html(explain_array["0"]);

		$('#second_panel, #second_menu').bind('selectstart', function() {
					return false;
				});

	}

	function initTabs() {

		// 设置标签栏属性
		$('#tabs_container').tabs({
					tabsLeftScroll : 'tabs_left_scroll',
					tabsRightScroll : 'tabs_right_scroll',
					panelsContainer : 'center'
				});

	}

	function initLogout() {
		// 注销
		$('#logout').bind('click', function() {
					logout();
					return false;
				});
	}

	function initHideTopbar() {
		// 隐藏topbar事件
		$('#hide_topbar').bind('click', function() {
					$('#north').slideToggle(300, function() {
								resizeLayout();
							});
					$(this).toggleClass('up');

					var hidden = $(this).attr('class').indexOf('up') >= 0;
					$.cookie('hideTopbar', (hidden ? '1' : null), {
								expires : 1000,
								path : '/'
							});
				});

		if ($.cookie('hideTopbar') == '1')
			$('#hide_topbar').triggerHandler('click');
	}

	// 设置日期和节气
	function solarTerms() {
		var day = OA_TIME.getDay();
		switch (day) {
			case 0 :
				day = "星期日";
				break;
			case 1 :
				day = "星期一";
				break;
			case 2 :
				day = "星期二";
				break;
			case 3 :
				day = "星期三";
				break;
			case 4 :
				day = "星期四";
				break;
			case 5 :
				day = "星期五";
				break;
			case 6 :
				day = "星期六";
				break;
		}
		$("#date").html((OA_TIME.getMonth() + 1) + "月" + OA_TIME.getDate()
				+ "日" + day);

		var solarTerm = sTerm(OA_TIME.getFullYear(), OA_TIME.getMonth(),
				OA_TIME.getDate());
		$('#mdate').html(CalConv() + solarTerm);
	}

	// 设置小时
	function timeview() {
		$('#time_area').html(OA_TIME.toTimeString().substr(0, 5));
		OA_TIME.setSeconds(OA_TIME.getSeconds() + 1);
		window.setTimeout(timeview, 1000);
	}

	// 窗口resize事件
	$(window).resize(function() {
				resizeLayout();
			});

	$(document).ready(function($) {
		$('#loading').remove();

		// 调整窗口大小
		resizeLayout();

		// getMenuData();

		// 开始菜单
		initStartMenu();

		// 标签栏
		initTabs();

		// 隐藏topbar事件
		initHideTopbar();

		// 日期时间
		solarTerms();
		timeview();

		createTab('10', '工作桌面',
				'index1.php?model=system_portal_portlet&action=portal', '',
				true, true);
		// 打开锁定tab
		if (lock_array) {
			for (var key in lock_array) {
				createTab(key, func_array["f" + key][0],
						func_array["f" + key][1], '', true, false, "lock",
						false);
			}
			// 打开锁定tab
			// for (var i in lock_array) {
			// var tab = lock_array[i];
			// createTab(tab.menuCode, tab.name, tab.url, '', true, true,
			// "lock");
			// }
		}
	});
})(jQuery);

/**
 * 注销
 */
function logout() {
	var msg = "您好，" + loginUser.user_name + "\n\n确认要注销吗？";
	if (window.confirm(msg)) {
		relogin = 1;
		window.location = 'general/bannera.php?REG_LOGIN=1';
	}
}

/**
 * 将文本转成对象
 *
 * @param data
 */
function Text2Object(data) {
	try {
		var func = new Function("return " + data);
		return func();
	} catch (ex) {
		return '<b>' + ex.description + '</b><br /><br />' + HTML2Text(data)
				+ '';
	}
}

/**
 * 兼容旧合同方法
 *
 * @param {}
 *            url
 * @param {}
 *            title
 */
function openTab(url, title) {
	createTab("", title, url, "", true, true, true);
}
/**
 * 创建Tab
 *
 * @param id
 * @param name
 *            tab名称
 * @param code
 *            页面的URL
 * @param open_window
 *            页面打开方式
 * @param selected
 *            打开时是否选中当前tab
 */
function createTab(id, name, code, open_window, close, select, lock, isLoad) {
	if (code.substr(0, 1) == "@") {
		code = "menu-second.php?menuId=" + id;
	} else if (code.substr(0, 1) == "#") {// 代表点击一级菜单
		code = "menu-second.php?isFirstMenu=1&menuId=" + id;
	}
	jQuery('#overlay_startmenu').triggerHandler('click');
	if (code.indexOf('http://') == 0 || code.indexOf('https://') == 0
			|| code.indexOf('ftp://') == 0) {
		openURL(id, name, code, open_window, close, select, lock, isLoad);
		return;
	} else if (code.indexOf('file://') == 0) {
		winexe(name, code.substr(7));
		return;
	}

	var url = code;

	// 新窗口打开
	if (open_window == "1") {
		openURL(id, name, url, "1");
		return;
	}

	/**
	 * 界面原型暂时不用 var url2 = 'http://www.yongxin.com' + url; var parse =
	 * url2.match(/^(([a-z]+):\/\/)?([^\/\?#]+)\/*([^\?#]*)\??([^#]*)#?(\w*)$/i);
	 * var path = parse[4]; var query = parse[5];
	 *
	 * //菜单地址直接定义为具体文件或路径传递参数的模块 var pos = path.lastIndexOf('/'); if (pos > 0 &&
	 * path.substr(pos + 1).indexOf('.') > 0 || query != "") { openURL(id, name,
	 * url, open_window); return; }
	 *
	 * //后台动态加载,包括四级菜单数据，界面原型暂时不用 /** jQuery.ajax({ type: 'GET', url:
	 * '/inc/second_tabs.php', data: {'FUNC_CODE':escape(url)}, dataType:
	 * 'text', success: function(data) {
	 *
	 * var array = Text2Object(data); if (typeof(array) != "object" ||
	 * typeof(array.length) != "number" || array.length <= 0) {
	 *
	 * openURL(id, name, url, open_window); return; }
	 *
	 * var index = 0; var html = ''; for (var i = 0; i < array.length; i++) {
	 * index = (array[i].active == "1") ? i : index;//默认打开第一个标签页地址 var className =
	 * (array[i].active == "1") ? ' class="active"' : ''; var href =
	 * (url.substr(url.length - 1) != "/" && array[i].href.substr(0, 1) != "/") ?
	 * (url + '/' + array[i].href) : (url + array[i].href); html += '<a
	 * title="' + array[i].title + '" href="javascript:gotoURL(\'' + id +
	 * '\',\'' + href + '\');"' + className + ' hidefocus="hidefocus"><span>' +
	 * array[i].text + '</span></a>'; }
	 *
	 * html = '<div id="second_tabs_' + id + '" class="second-tabs-container">' +
	 * html + '</div>'; jQuery(html).appendTo('#funcbar_left');
	 *
	 * var secondTabs = jQuery('#second_tabs_' + id); jQuery('a',
	 * secondTabs).click(function() { jQuery('a.active',
	 * secondTabs).removeClass('active'); jQuery(this).addClass('active'); });
	 *
	 * if (jQuery('a.active', secondTabs).length <= 0) jQuery('a:first',
	 * secondTabs).addClass('active'); jQuery('a:last',
	 * secondTabs).addClass('last');
	 *
	 * jQuery().addTab(id, name, url + "/" + array[index].href, true); }, error:
	 * function (request, textStatus, errorThrown) { openURL(id, name, url,
	 * open_window); } });
	 */

	// 界面原型时使用
	openURL(id, name, url, open_window, close, select, lock, isLoad);

}

function gotoURL(id, url) {
	$('tabs_' + id + "_iframe").src = url;
}

/**
 * 关闭Tab
 *
 * @param id
 */
function closeTab(id) {
	id = (typeof(id) != 'string') ? jQuery().getSelected() : id;
	jQuery().closeTab(id);
}

/**
 * 加载iframe
 *
 * @param id
 */
function IframeLoaded(id) {
	var iframe = window.frames['tabs_' + id + '_iframe'];
	if (iframe && $('tabs_link_' + id) && $('tabs_link_' + id).innerText == '') {
		$('tabs_link_' + id).innerText = !iframe.document.title
				? "无标题"
				: iframe.document.title;
	}
}

/**
 * 打开页面
 *
 * @param id
 * @param name
 * @param url
 * @param open_window
 * @param select
 */
function openURL(id, name, url, open_window, close, select, lock, isLoad) {

	id = !id ? ('w' + (nextTabId++)) : id;
	if (open_window != "1") {
		window.setTimeout(function() {
					jQuery().addTab(id, name, url, close, select, isLoad)
				}, 1);
	}

	else {
		var width = typeof(width) == "undefined" ? 780 : width;
		var height = typeof(height) == "undefined" ? 550 : height;
		var left = typeof(left) == "undefined" ? (screen.availWidth - width)
				/ 2 : left;
		var top = typeof(top) == "undefined" ? (screen.availHeight - height)
				/ 2 - 30 : top;
		window
				.open(
						url,
						id,
						"height="
								+ height
								+ ",width="
								+ width
								+ ",status=0,toolbar=no,menubar=yes,location=no,scrollbars=yes,top="
								+ top + ",left=" + left + ",resizable=yes");
	}

	jQuery(document).trigger('click');

	// 设置当前tab标签的右键菜单，暂时用定时器等待页面加载完成
	// 页面加载时设置锁定状态
	if (lock != undefined && lock != null && lock != '') {
		window.setTimeout("setRightMenu('tabs_" + id + "','" + lock + "')", 50);
	} else {
		window.setTimeout("setRightMenu()", 50);
	}
}

function winexe(NAME, PROG) {
	var URL = "/general/winexe?PROG=" + PROG + "&NAME=" + NAME;
	window
			.open(
					URL,
					"winexe",
					"height=100,width=350,status=0,toolbar=no,menubar=no,location=no,scrollbars=yes,top=0,left=0,resizable=no");
}

/**
 * 退出
 */
function exit() {
	var msg = "您好，" + loginUser.user_name + "\n\n确认要退出吗？";
	if (window.confirm(msg)) {
		var event = getEvent();
		if (ispirit != "1"
				|| jQuery(document.body).width() - event.clientX < 50
				|| event.altKey || event.ctrlKey) {
			// if (jQuery.browser.msie)
			// jQuery.get('relogin.php');
			// else
			// window.location = 'general/bannera.php?REG_LOGIN=1';
		}
		window.close();
	}
}

function getEvent() { // 同时兼容ie和ff的写法

	if (document.all)
		return window.event;
	func = getEvent.caller;
	while (func != null) {
		var arg0 = func.arguments[0];
		if (arg0) {
			if ((arg0.constructor == Event || arg0.constructor == MouseEvent)
					|| (typeof(arg0) == "object" && arg0.preventDefault && arg0.stopPropagation)) {
				return arg0;
			}
		}
		func = func.caller;
	}
	return null;
}

/**
 * 设置当前页面标签的右键菜单
 *
 * @param id
 */
function setRightMenu(id, status) {

	if (id == null || id == undefined || id == '') {
		id = ".selected";
	} else {
		id = '#' + id;
	}

	$(id).contextMenu('tab_menu', {
				bindings : {
					// 锁定
					'lock' : function(t) {
						setLock(t.id, 'lock');
					},
					// 解除锁定
					'unlock' : function(t) {
						setLock(t.id, 'unlock');
					},
					// 关闭右边标签
					'closeRight' : function(t) {
						var flag = false;
						var tabs = new Array();
						// 循环div层中的所有tab标签，匹配到当前tab时，将后面的tab页都放到数组中
						$("#tabs_container div").each(function() {
									if (flag) {
										tabs.push(this.id);
									}
									// 匹配到了给个标志位，后面循环的时候把tab放数组
									if (t.id == this.id) {
										flag = true;
									}
								});
						for (var i = 0; i < tabs.length; i++) {
							closeTab(tabs[i].split("_")[1]);
						}
					},
					// 关闭左边标签
					'closeLeft' : function(t) {
						var flag = true;
						var tabs = new Array();
						$("#tabs_container div").each(function() {
									// 匹配到了给个标志位，后面循环的时候把tab就不放到数组中了
									if (t.id == this.id) {
										flag = false;
									}
									if (flag) {
										tabs.push(this.id);
									}
								});
						for (var i = 0; i < tabs.length; i++) {
							closeTab(tabs[i].split("_")[1]);
						}
					},
					// 关闭其他标签
					'closeOther' : function(t) {
						var tabs = new Array();
						$("#tabs_container div").each(function() {
									// 匹配到的tab就不放到数组中了
									if (t.id != this.id) {
										tabs.push(this.id);
									}
								});
						for (var i = 0; i < tabs.length; i++) {
							closeTab(tabs[i].split("_")[1]);
						}
					}
				},
				onShowMenu : function(e, menu) {
					// 如果已经锁定就不显示锁定按钮，如果参数未定义则通过锁定数组中做判断
					if (status == 'lock') {
						$("#lock", menu).remove();
					} else if (status == 'unlock') {
						$("#unlock", menu).remove();
					} else {
						var flag = false;
						var tab_id = e.currentTarget.id;
						tab_id = tab_id.split("_")[1];

						for (var i in lock_array) {
							if (lock_array[i] == tab_id) {
								flag = true;
								break;
							}
						}

						if (flag) {
							$("#lock", menu).remove();
						} else {
							$("#unlock", menu).remove();
						}
					}
					return menu;
				}
			});
}

function setLock(id, type) {

	// $.get("menu/setLock.do", {
	// 'code' : id.split("_")[1],
	// 'type' : type
	// }, function(msg) {
	// if (msg == 'success') {
	// alert("操作成功!");
	// // 重新绑定右键菜单
	// $('#' + id).unbind();
	// setRightMenu(id, type);
	//
	// } else if (msg == "fail") {
	// alert("操作失败，请稍后再试.");
	// } else {
	// alert("您的操作无效.");
	// }
	// });

	alert("操作成功!");
	// 重新绑定右键菜单
	$('#' + id).unbind();
	setRightMenu(id, type);

}
