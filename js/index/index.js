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
	 * ��ȡ�����������˵�
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
			bExpand = false;// ��д��
			var onclick = "createTab(" + func_id.substr(1) + ",'"
					+ func_name.replace("'", "\'") + "','"
					+ func_code.replace("'", "\'") + "','" + open_window
					+ "');";
			html += '<li><a id="' + func_id + '" href="javascript:;" onclick="'
					+ onclick + '"' + (bExpand ? ' class="expand"' : '')
					+ ' hidefocus="hidefocus"><span style="background: url(\''
					+ CSS_PATH + func_img + '.png\') -2px 5px no-repeat;">'
					+ func_name + '</span></a>';
			// �ж����������˵�
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
		// ����������߶�
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

		// һ����ǩ���
		var width = wWidth - $('#taskbar_left').outerWidth()
				- $('#taskbar_right').outerWidth();
		// $('#tabs_container').width(width -
		// $('#tabs_left_scroll').outerWidth() -
		// $('#tabs_right_scroll').outerWidth());
		$('#tabs_container').width(width - $('#tabs_left_scroll').outerWidth()
				- $('#tabs_right_scroll').outerWidth() - 2);
		$('#taskbar_center').width(width - 1); // -1��Ϊ�˼���iPad

		$('#tabs_container').triggerHandler('_resize');
	}

	;

	// �˵�������ͷ�¼�,idΪfirst_menu
	function initMenuScroll(id) {
		// �˵����Ϲ�����ͷ�¼�
		$('#' + id + ' > .scroll-up:first').hover(function() {
					$(this).addClass('scroll-up-hover');
					if (id == 'first_panel') {
						$("#first_menu > li > a.active").removeClass('active'); // �ָ�һ��active�Ĳ˵�Ϊ����
					}
				}, function() {
					$(this).removeClass('scroll-up-hover');
				});

		// ������ϼ�ͷ
		$('#' + id + ' > .scroll-up:first').click(function() {
					var ul = $('#' + id + ' > ul:first');
					ul.animate({
								'scrollTop' : (ul.scrollTop() - SCROLL_HEIGHT)
							}, 600);
				});

		// ���¹�����ͷ�¼�
		$('#' + id + ' > .scroll-down:first').hover(function() {
					$(this).addClass('scroll-down-hover');
					if (id == 'first_panel') {
						$("#first_menu > li > a.active").removeClass('active'); // �ָ�һ����active�Ĳ˵�Ϊ����
					}
				}, function() {
					$(this).removeClass('scroll-down-hover');
				});

		// ������¼�ͷ
		$('#' + id + ' > .scroll-down:first').click(function() {
					var ul = $('#' + id + ' > ul:first');
					ul.animate({
								'scrollTop' : (ul.scrollTop() + SCROLL_HEIGHT)
							}, 600);
				});
	};

	/**
	 * ��̨��ȡ�˵����� add by chengl 20120504
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
	 * ��ʼ���˵�
	 */
	function initStartMenu() {

		// ���ҳ�棬���ظ����˵���壬����������������˵���active״̬
		$('#overlay_startmenu').click(function() {
					if ($('#start_menu_panel:visible').length) {
						$('#overlay_startmenu').hide();
						$('#start_menu_panel').slideUp(300);
						$('#start_menu').removeClass('active');
					}
				});

		// ���������ͼ�갴ť�����˵����
		$('#start_menu').bind('click', function() {
			if ($('#start_menu_panel:visible').length) {
				$('#overlay_startmenu').hide();
				$('#start_menu_panel').slideUp(300);
				$(this).removeClass('active');
			}
			// ���õ���ͼ��Ϊactive״̬
			$(this).addClass('active');

			// ���ֲ�λ�ú���ʾ
			$('#overlay_startmenu').show();

			// �˵����λ��
			var top = $('#start_menu').offset().top
					+ $('#start_menu').outerHeight() - 6;
			$('#start_menu_panel').css({
						top : top
					});
			$('#start_menu_panel').slideDown('fast');

			// //���㲢���ò˵����ĸ߶�,�Ƿ���ʾ������ͷ
			var scrollHeight = $("#first_menu").attr('scrollHeight');
			if ($("#first_menu").height() < scrollHeight) {
				var height = ($('#south').offset().top - $('#start_menu')
						.offset().top)
						* 0.7; // ���ø߶�Ϊ��ʼ�˵���״̬���߲��70%
				height = height - height % MENU_ITEM_HEIGHT; // ���ø߶�Ϊ
				// MENU_ITEM_HEIGHT
				// ��������
				// ������ø߶ȴ����������߸߶ȣ�������
				height = height <= MAX_PNAEL_HEIGHT ? height : MAX_PNAEL_HEIGHT;
				// ������ø߶ȳ���scrollHeight�������ø߶�ΪscrollHeight
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

			// ���㲢���ö����˵�����λ��
			var top = $('#first_menu').offset().top
					- $("#start_menu_panel").offset().top;
			$('#second_panel').css('top', top - 5);
			$('#second_panel > .second-panel-menu').css('height',
					$('#first_menu').height());

			// ��һ�δ�ʱ���ö����˵������¼�
			if ($('#second_panel > .second-panel-menu > .jscroll-c').length <= 0) {
				$('#second_panel > .second-panel-menu').jscroll();
			}

			// �ָ�һ���˵�Ϊ����
			$("#first_menu > li").removeClass('active');

			// �ָ������˵�Ϊϵͳ����
			$('#second_panel > .second-panel-menu').html(explain_array["0"]);
		});

		// ����һ���˵�
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

		// һ���˵�������ͷ�¼�
		initMenuScroll('first_panel');

		// һ���˵�hover�¼�
		$("#first_menu > li > a").hover(function() {
			$("#second_menu > li > a.expand").removeClass('active'); // �ָ�����expand�˵�Ϊ����
			$("#first_menu > li.active").removeClass('active'); // �ָ�һ��active�Ĳ˵�Ϊ����

			// ��ȡ��ǰһ���˵����������˵���HTML���룬�����¶����˵����
			if ($(this).attr('isSystem') == 1
					|| getSecondMenuHTML(this.id) == "") { // ��ǰһ���˵������û�ж����˵�����ʾһ���˵��Ľ��ܲ���click�¼�
				var obj = func_array[this.id];
				if (explain_array[this.id.substr(1)]) {
					$('#second_panel > .second-panel-menu')
							.html(explain_array[this.id.substr(1)]);
				} else {
					$('#second_panel > .second-panel-menu').html("��ϵͳֱ�ӵ������");
				}
				$(this).unbind('click').removeAttr('onclick').click(function() {
							createTab(this.id.substring(1), obj[0], "#", '');
						});
			} else {

				var html = getSecondMenuHTML(this.id);
				$('#second_panel > .second-panel-menu').html(html);

				// �������˵������¼�
				$('#second_panel > .second-panel-menu').jscroll();

				// �����˵����չ�������˵�����ɾ���ٰ��¼��������¼��ۼ�
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
			// ����ǰһ���˵���Ϊactive
			$("#" + this.id).parent().addClass('active');

		}, function() {

		});

		$('#second_panel > .second-panel-menu').html(explain_array["0"]);

		$('#second_panel, #second_menu').bind('selectstart', function() {
					return false;
				});

	}

	function initTabs() {

		// ���ñ�ǩ������
		$('#tabs_container').tabs({
					tabsLeftScroll : 'tabs_left_scroll',
					tabsRightScroll : 'tabs_right_scroll',
					panelsContainer : 'center'
				});

	}

	function initLogout() {
		// ע��
		$('#logout').bind('click', function() {
					logout();
					return false;
				});
	}

	function initHideTopbar() {
		// ����topbar�¼�
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

	// �������ںͽ���
	function solarTerms() {
		var day = OA_TIME.getDay();
		switch (day) {
			case 0 :
				day = "������";
				break;
			case 1 :
				day = "����һ";
				break;
			case 2 :
				day = "���ڶ�";
				break;
			case 3 :
				day = "������";
				break;
			case 4 :
				day = "������";
				break;
			case 5 :
				day = "������";
				break;
			case 6 :
				day = "������";
				break;
		}
		$("#date").html((OA_TIME.getMonth() + 1) + "��" + OA_TIME.getDate()
				+ "��" + day);

		var solarTerm = sTerm(OA_TIME.getFullYear(), OA_TIME.getMonth(),
				OA_TIME.getDate());
		$('#mdate').html(CalConv() + solarTerm);
	}

	// ����Сʱ
	function timeview() {
		$('#time_area').html(OA_TIME.toTimeString().substr(0, 5));
		OA_TIME.setSeconds(OA_TIME.getSeconds() + 1);
		window.setTimeout(timeview, 1000);
	}

	// ����resize�¼�
	$(window).resize(function() {
				resizeLayout();
			});

	$(document).ready(function($) {
		$('#loading').remove();

		// �������ڴ�С
		resizeLayout();

		// getMenuData();

		// ��ʼ�˵�
		initStartMenu();

		// ��ǩ��
		initTabs();

		// ����topbar�¼�
		initHideTopbar();

		// ����ʱ��
		solarTerms();
		timeview();

		createTab('10', '��������',
				'index1.php?model=system_portal_portlet&action=portal', '',
				true, true);
		// ������tab
		if (lock_array) {
			for (var key in lock_array) {
				createTab(key, func_array["f" + key][0],
						func_array["f" + key][1], '', true, false, "lock",
						false);
			}
			// ������tab
			// for (var i in lock_array) {
			// var tab = lock_array[i];
			// createTab(tab.menuCode, tab.name, tab.url, '', true, true,
			// "lock");
			// }
		}
	});
})(jQuery);

/**
 * ע��
 */
function logout() {
	var msg = "���ã�" + loginUser.user_name + "\n\nȷ��Ҫע����";
	if (window.confirm(msg)) {
		relogin = 1;
		window.location = 'general/bannera.php?REG_LOGIN=1';
	}
}

/**
 * ���ı�ת�ɶ���
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
 * ���ݾɺ�ͬ����
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
 * ����Tab
 *
 * @param id
 * @param name
 *            tab����
 * @param code
 *            ҳ���URL
 * @param open_window
 *            ҳ��򿪷�ʽ
 * @param selected
 *            ��ʱ�Ƿ�ѡ�е�ǰtab
 */
function createTab(id, name, code, open_window, close, select, lock, isLoad) {
	if (code.substr(0, 1) == "@") {
		code = "menu-second.php?menuId=" + id;
	} else if (code.substr(0, 1) == "#") {// ������һ���˵�
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

	// �´��ڴ�
	if (open_window == "1") {
		openURL(id, name, url, "1");
		return;
	}

	/**
	 * ����ԭ����ʱ���� var url2 = 'http://www.yongxin.com' + url; var parse =
	 * url2.match(/^(([a-z]+):\/\/)?([^\/\?#]+)\/*([^\?#]*)\??([^#]*)#?(\w*)$/i);
	 * var path = parse[4]; var query = parse[5];
	 *
	 * //�˵���ֱַ�Ӷ���Ϊ�����ļ���·�����ݲ�����ģ�� var pos = path.lastIndexOf('/'); if (pos > 0 &&
	 * path.substr(pos + 1).indexOf('.') > 0 || query != "") { openURL(id, name,
	 * url, open_window); return; }
	 *
	 * //��̨��̬����,�����ļ��˵����ݣ�����ԭ����ʱ���� /** jQuery.ajax({ type: 'GET', url:
	 * '/inc/second_tabs.php', data: {'FUNC_CODE':escape(url)}, dataType:
	 * 'text', success: function(data) {
	 *
	 * var array = Text2Object(data); if (typeof(array) != "object" ||
	 * typeof(array.length) != "number" || array.length <= 0) {
	 *
	 * openURL(id, name, url, open_window); return; }
	 *
	 * var index = 0; var html = ''; for (var i = 0; i < array.length; i++) {
	 * index = (array[i].active == "1") ? i : index;//Ĭ�ϴ򿪵�һ����ǩҳ��ַ var className =
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

	// ����ԭ��ʱʹ��
	openURL(id, name, url, open_window, close, select, lock, isLoad);

}

function gotoURL(id, url) {
	$('tabs_' + id + "_iframe").src = url;
}

/**
 * �ر�Tab
 *
 * @param id
 */
function closeTab(id) {
	id = (typeof(id) != 'string') ? jQuery().getSelected() : id;
	jQuery().closeTab(id);
}

/**
 * ����iframe
 *
 * @param id
 */
function IframeLoaded(id) {
	var iframe = window.frames['tabs_' + id + '_iframe'];
	if (iframe && $('tabs_link_' + id) && $('tabs_link_' + id).innerText == '') {
		$('tabs_link_' + id).innerText = !iframe.document.title
				? "�ޱ���"
				: iframe.document.title;
	}
}

/**
 * ��ҳ��
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

	// ���õ�ǰtab��ǩ���Ҽ��˵�����ʱ�ö�ʱ���ȴ�ҳ��������
	// ҳ�����ʱ��������״̬
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
 * �˳�
 */
function exit() {
	var msg = "���ã�" + loginUser.user_name + "\n\nȷ��Ҫ�˳���";
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

function getEvent() { // ͬʱ����ie��ff��д��

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
 * ���õ�ǰҳ���ǩ���Ҽ��˵�
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
					// ����
					'lock' : function(t) {
						setLock(t.id, 'lock');
					},
					// �������
					'unlock' : function(t) {
						setLock(t.id, 'unlock');
					},
					// �ر��ұ߱�ǩ
					'closeRight' : function(t) {
						var flag = false;
						var tabs = new Array();
						// ѭ��div���е�����tab��ǩ��ƥ�䵽��ǰtabʱ���������tabҳ���ŵ�������
						$("#tabs_container div").each(function() {
									if (flag) {
										tabs.push(this.id);
									}
									// ƥ�䵽�˸�����־λ������ѭ����ʱ���tab������
									if (t.id == this.id) {
										flag = true;
									}
								});
						for (var i = 0; i < tabs.length; i++) {
							closeTab(tabs[i].split("_")[1]);
						}
					},
					// �ر���߱�ǩ
					'closeLeft' : function(t) {
						var flag = true;
						var tabs = new Array();
						$("#tabs_container div").each(function() {
									// ƥ�䵽�˸�����־λ������ѭ����ʱ���tab�Ͳ��ŵ���������
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
					// �ر�������ǩ
					'closeOther' : function(t) {
						var tabs = new Array();
						$("#tabs_container div").each(function() {
									// ƥ�䵽��tab�Ͳ��ŵ���������
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
					// ����Ѿ������Ͳ���ʾ������ť���������δ������ͨ���������������ж�
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
	// alert("�����ɹ�!");
	// // ���°��Ҽ��˵�
	// $('#' + id).unbind();
	// setRightMenu(id, type);
	//
	// } else if (msg == "fail") {
	// alert("����ʧ�ܣ����Ժ�����.");
	// } else {
	// alert("���Ĳ�����Ч.");
	// }
	// });

	alert("�����ɹ�!");
	// ���°��Ҽ��˵�
	$('#' + id).unbind();
	setRightMenu(id, type);

}
