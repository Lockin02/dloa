$(function() {
	$('#homeBody').css('visibility', 'visible');

	var menuId = $("#menuId").val();
	var parentId = $("#parentId").val();
	var isFirstMenu = $("#isFirstMenu").val();
	var second_array = parent.second_array;
	var third_array = parent.third_array;
	var four_array = parent.four_array;
	var func_array = parent.func_array;
	var fMenuId = parentId ? "f" + parentId : "f" + menuId;

	var secondArr = [], thirdArr = [], fourArr = [], funcArr = [];
	var menuData = [];
	try {
		secondArr = second_array["m" + menuId];
		thirdArr = third_array[fMenuId];
		fourArr = four_array[fMenuId];
		funcArr = func_array[fMenuId];
	} catch (e) {
	}
	var sFirstMenus = [];
	var sSecondMenus = [];

	if (isFirstMenu == 1) {// ����������һ���˵�
		sFirstMenus = secondArr;
		sSecondMenus = third_array;
	} else {
		sFirstMenus = thirdArr;
		sSecondMenus = four_array;
	}
	if (sFirstMenus) {// ���������Ƕ����˵�

		for (var i = 0; i < sFirstMenus.length; i++) {
			var thirdId = sFirstMenus[i];
			var thirdMenu = func_array["f" + thirdId];
			// $("#leftMenu").append(thirdMenu[0]);
			var menu = {
				name : thirdMenu[0],
				item : []
			};
			if (thirdMenu[1] != "@") {
				menu.url = thirdMenu[1];
			}
			try {
				var fArr = sSecondMenus["f" + thirdId];
				if (fArr) {
					for (var j = 0; j < fArr.length; j++) {
						var fId = fArr[j];
						var fMenu = func_array["f" + fId];
						var m = {
							name : fMenu[0],
							url : fMenu[1]
						};
						menu.item.push(m);
					}
				} else {
//					menu.item.push({
//								name : thirdMenu[0],
//								url : thirdMenu[1]
//							});
				}
			} catch (e) {

			}
			menuData.push(menu);
		}
	} else if (fourArr) {// ����������˵�

	}
	// $.showDump(menuData);
	var menuObj = {
		"menu" : menuData,
		"target" : "mainFrame"
	};

	var expanded = 0;
	// if (parentId) {
	// // �������ie���룬��ie��thirdArrΪobject
	// var thirdArr = Array.prototype.slice.call(thirdArr);
	// expanded = thirdArr.indexOf(menuId);
	// }
	// $("body").yxlayout();
	$("#outlook").yxoutlook({
		data : menuObj,
		defaultexpanded : [expanded]
			// persiststate : true,
			// collapseprev : true
		});
	// $(".ui-layout-center").width("90%");

	// tabs����
	$('#bodyTab').tabs({
		// ������ʷtab
		onSelect : function(node) {
			var tb = $('#bodyTab').tabs('getTab', node);
			var hc = tb.panel('options').headerCls;

			if (hc && hc != null) {
				tb.panel('options').headerCls = '';
				$('#bodyTab').tabs('update', {
					fit : true,
					tab : tb,
					options : {
						fit : true,
						content : '<iframe fit="true"  name="' + hc
								+ '" scrolling="auto" frameborder="0"  src="'
								+ hc
								+ '" style="width:100%;height:98%;"></iframe>',
						closable : true,
						cls : hc
					}
				});
			}
			$('#infoDiv').html(tb.panel('options').title);
		},
		onAdd : function(node) {
			var tb = $('#bodyTab').tabs('getTab', node);
			var url = tb.panel('options').cls;
			$.post('settabs_new.php', {
						flag : 'add',
						tl : node,
						url : url,
						parentId : menuId
					}, function(data) {
					})
		},
		onClose : function(node) {
			var tb = $('#bodyTab').tabs('getTab', node);
			$.post('settabs_new.php', {
						flag : 'del',
						tl : node,
						parentId : menuId
					}, function(data) {
					})
		},
		tools : [{
			iconCls : 'icon-tabmu',
			handler : function(e) {
				// ��Ŀ����¼�
				$('#tabmu').html('');
				if (e == null || !e) {
					$('#tabmu').menu('show', {
								left : window.event.clientX,
								top : window.event.clientY
							});
					var tabs = $('#bodyTab').tabs('tabs');
					var tabssel = $('#bodyTab').tabs('getSelected');
					$.each(tabs, function(n, obj) {
								if (tabssel == obj) {
									$('#tabmu').menu('appendItem', {
												iconCls : "icon-ok",
												text : obj.panel('options').title
											});
								} else {
									$('#tabmu').menu('appendItem', {
												text : obj.panel('options').title
											});
								}
							});
				} else {
					$('#tabmu').menu('show', {
								left : e.pageX,
								top : e.pageY
							});
					var tabs = $('#bodyTab').tabs('tabs');
					var tabssel = $('#bodyTab').tabs('getSelected');
					$.each(tabs, function(n, obj) {
								if (tabssel == obj) {
									$('#tabmu').menu('appendItem', {
												iconCls : "icon-ok",
												text : obj.panel('options').title
											});
								} else {
									$('#tabmu').menu('appendItem', {
												text : obj.panel('options').title
											});
								}
							});
				}
			}
		}]
	});
	// �˵��¼����
	$('#tabmu').menu({
				onClick : function(item) {
					$('#bodyTab').tabs('select', item.text);
				}
			});
	$('.icon-headreload').click(function() {
				$('#navTree').tree('reload');
			});
	$('.icon-headreload').attr('title', 'ˢ����Ŀ');
	$('.icon-headreload').css('cursor', 'hand');

	// ����¼�
	$(".tabs-inner").bind('contextmenu', function(e) {
				$('#mu').menu('show', {
							left : e.pageX,
							top : e.pageY
						});
				var subtitle = $(this).children("span").text();
				$('#mu').data("currtab", subtitle);
				$('#bodyTab').tabs('select', subtitle);
				return false;
			});
	// �رյ�ǰ
	$('#mm-tabclose').click(function() {
				var currtab_title = $('#mu').data("currtab");
				if (currtab_title != '��ҳ')
					$('#bodyTab').tabs('close', currtab_title);
				else
					alert('��ҳ���ܹر�');
			})
	// ȫ���ر�
	$('#mm-tabcloseall').click(function() {
				$('.tabs-inner span').each(function(i, n) {
							var t = $(n).text();
							if (t != '��ҳ')
								$('#bodyTab').tabs('close', t);
						});
			});
	// �رճ���ǰ֮���TAB
	$('#mm-tabcloseother').click(function() {
				var currtab_title = $('#mu').data("currtab");
				$('.tabs-inner span').each(function(i, n) {
							var t = $(n).text();
							if (t != currtab_title && t != '��ҳ')
								$('#bodyTab').tabs('close', t);
						});
			});
	// �رյ�ǰ�Ҳ��TAB
	$('#mm-tabcloseright').click(function() {
				var nextall = $('.tabs-selected').nextAll();
				if (nextall.length == 0) {
					return false;
				}
				nextall.each(function(i, n) {
							var t = $('a:eq(0) span', $(n)).text();
							if (t != '��ҳ')
								$('#bodyTab').tabs('close', t);
						});
				return false;
			});
	// �رյ�ǰ����TAB
	$('#mm-tabcloseleft').click(function() {
				var prevall = $('.tabs-selected').prevAll();
				if (prevall.length == 0) {
					return false;
				}
				prevall.each(function(i, n) {
							var t = $('a:eq(0) span', $(n)).text();
							if (t != '��ҳ')
								$('#bodyTab').tabs('close', t);
						});
				return false;
			});
	// ˢ��TAB
	$('#mm-tabreload').click(function() {
				var tb = $('#bodyTab').tabs('getSelected');
				var ifr = $("iframe[name='" + tb.panel('options').cls + "']");
				if (ifr) {
					ifr.attr('src', tb.panel('options').cls);
				}
				return false;
			});
});
/**
 * ��tab
 *
 * @param {}
 *            url
 * @param {}
 *            title
 */
function openTab(url, title) {
	if ($('#bodyTab').tabs('exists', title)) {
		$('#bodyTab').tabs('select', title);
		if (title == '�Ż�') {
			// $('#headIframe').attr('src', './general/mytable.php');
		} else {
			var tb = $('#bodyTab').tabs('getSelected');
			var ifr = $("iframe[name='" + tb.panel('options').cls + "']");
			if (ifr) {
				ifr.attr('src', tb.panel('options').cls);
			}
		}
	} else {
		$('#bodyTab').tabs('add', {
			fit : true,
			title : title,
			content : '<iframe fit="true"  name="' + url
					+ '" scrolling="auto" frameborder="0"  src="' + url
					+ '" style="width:100%;height:98%;"></iframe>',
			closable : true,
			cls : url
		});
		$(".tabs-inner").bind('contextmenu', function(e) {
					$('#mu').menu('show', {
								left : e.pageX,
								top : e.pageY
							});
					var subtitle = $(this).children("span").text();
					$('#bodyTab').tabs('select', subtitle);
					$('#mu').data("currtab", subtitle);
					return false;
				});
	}
}