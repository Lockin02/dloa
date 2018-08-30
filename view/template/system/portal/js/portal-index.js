/*
 * ! Ext JS Library 3.4.0 Copyright(c) 2006-2011 Sencha Inc.
 * licensing@sencha.com http://www.sencha.com/license
 */

Ext.onReady(function() {
	var showPortletIdArr = [];// 已经显示在界面上的portlet
	var lastPortletColNum = 0;// 用于记录最后一个portlet的列数
	var lastPortletId;
	// 添加
	$("#addPortlet").click(function() {
		var url = "?model=system_portal_portlet&action=selectPortlet";
		var returnValue = showModalDialog(url, '',
				"dialogWidth:800px;dialogHeight:500px;");
		if (returnValue) {
			var ids = returnValue.val;
			var portletNames = returnValue.text;
			var idArr = ids.split(",");
			var portletNamesArr = portletNames.split(",");

			for (var i = 0; i < idArr.length; i++) {
				if (showPortletIdArr.indexOf(idArr[i]) != -1) {
					idArr.splice(i, 1);
					portletNamesArr = portletNamesArr.splice(i, 1);
					i--;
				}
			}
			if (idArr.length > 0) {
				// 保存关联
				var rIds = $.ajax({
							url : '?model=system_portal_portletuser&action=add',
							async : false,
							type : "POST",
							data : {
								portletIds : idArr.toString(),
								portletNames : portletNamesArr.toString()
							}
						}).responseText;

				var rIdArr = rIds.split(",");

				// 获取选择的portlet到门户上
				$.ajax({
					url : '?model=system_portal_portlet&action=listJson',
					type : "POST",
					data : {
						ids : idArr.toString()
					},
					success : function(data) {
						data = eval("(" + data + ")");
						for (var i = 0, l = data.length; i < l; i++) {
							var pd = data[i];
							pd.height = pd.height != 0 ? pd.height : 200;
							showPortletIdArr.push(pd.id);
							var pan1 = new Ext.Panel({
								id : "portlet_" + rIdArr[i],
								title : pd.portletName,
								layout : 'fit',
								tools : tools,
								collapsible : true,
								puId : rIdArr[i],
								portletId : pd.id,
								html : "<iframe scrolling='no' width='99%' height="
										+ pd.height
										+ " src='"
										+ pd.url
										+ "'</iframe>"
							});
							var lastPortletCmp = Ext.getCmp("colCmp"
									+ lastPortletColNum);
							lastPortletCmp.add(pan1);
							lastPortletCmp.doLayout();
							lastPortletColNum++;
							if (lastPortletColNum == colNum) {
								lastPortletColNum = 0;
							}
							lastPortletId = pd.id;
						}
					}
				});
			}
		}
	});
	// 设置
	$("#setPortlet").click(function() {
		var url = "?model=system_portal_usercustomize&action=setPortlet";
		// var returnValue = showModalDialog(url, '',
		// "dialogWidth:750px;dialogHeight:280px;");
		url += "&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=700";
		showThickboxWin(url, "设置");
	});

	// 保存
	$("#savePortlet").click(function() {
				var lastLeft;
				var savePanel = {};
				var saveColNum = 0;
				var baseNum = 0;
				var cols = Ext.getCmp("portalCmp").items.items;// 列
				for (var i = 0; i < cols.length; i++) {
					var col = cols[i];
					var portlets = col.items.items;
					for (var j = 0; j < portlets.length; j++) {
						savePanel[portlets[j].id] = i + j * colNum;
					}
				}
				$.ajax({
							url : '?model=system_portal_portletuser&action=saveOrder',
							type : "POST",
							data : {
								savePanel : savePanel
							},
							success : function() {
								alert("保存成功.");
							}
						});
			});

	Ext.state.Manager.setProvider(new Ext.state.CookieProvider());

	// create some portlet tools using built in Ext tool ids
	var tools = [{
		id : 'minimize',
		hidden : true,
		qtip : 'Minimize Panel',
		handler : function(e, target, panel) {

			var panelCollection = Ext.getCmp("portalCmp");
			var hiddenPanel = Ext.getCmp("center-panel-col0");
			var index = 0;

			hiddenPanel.remove(panel, false);
			hiddenPanel.hide();

			panel.tools['toggle'].setVisible(true);
			panel.tools['maximize'].setVisible(true);
			panel.tools['minimize'].setVisible(false);
			panel.tools['close'].setVisible(true);
			panel.setSize(panel.originalSize);
			var h = panel.originalSize.height - 30;// 不知道为什么要减去30，不然高度递增,好像是减去标题的高度
			panel.setHeight(h);
			$("#" + panel.el.id).find("iframe").height(h - 2);

			panel.originalOwnerCt.insert(panel.originalPosition, panel);
			panel.originalOwnerCt.doLayout();
			while (index < panelCollection.items.keys.length) {
				if (panelCollection.items.keys[index] != hiddenPanel.id) {
					panelCollection.items.items[index].el
							.setDisplayed("inline");
				}
				++index;
			}
			panelCollection.doLayout();
			panel.show();
		}
	}, {
		id : 'maximize',
		handler : function(e, target, panel) {

			panel.originalOwnerCt = panel.ownerCt;
			panel.originalPosition = panel.ownerCt.items.indexOf(panel);
			panel.originalSize = panel.getSize();

			var panelCollection = Ext.getCmp("portalCmp");
			var hiddenPanel = Ext.getCmp("center-panel-col0");
			var index = 0;

			while (index < panelCollection.items.keys.length) {
				if (panelCollection.items.keys[index] != hiddenPanel.id) {
					panelCollection.items.items[index].el.setDisplayed("none");
				}
				++index;
			}

			panel.ownerCt.remove(panel, false);

			hiddenPanel.items.add(panel);
			hiddenPanel.setSize(hiddenPanel.ownerCt.getSize());
			hiddenPanel.setHeight(hiddenPanel.ownerCt.getSize().height - 10);
			hiddenPanel.doLayout();
			hiddenPanel.show();

			panel.tools['toggle'].setVisible(false);
			panel.tools['maximize'].setVisible(false);
			panel.tools['close'].setVisible(false);
			panel.tools['minimize'].setVisible(true);

			panel.setWidth(hiddenPanel.ownerCt.getInnerWidth());
			panel.setHeight(hiddenPanel.ownerCt.getInnerHeight());
			// alert($(panel.el).attr("id"))
			var $iframe = $("#" + panel.el.id).find("iframe");
			$iframe.height(hiddenPanel.ownerCt.getInnerHeight());
			// alert($iframe.size())
			// $iframe.css("scrolling","yes");
			// $(panel.el).find("iframe").height(hiddenPanel.ownerCt.getInnerHeight());

		}
	}, {
		id : 'close',
		handler : function(e, target, panel) {
			panel.ownerCt.remove(panel, true);
			$.ajax({
						url : '?model=system_portal_portletuser&action=ajaxdeletes',
						type : "POST",
						data : {
							id : panel.puId
						}
					});
			var index = showPortletIdArr.indexOf(panel.portletId);
			if (index != -1) {
				showPortletIdArr.splice(index, 1);
			}
			// 如果关闭的是最后一个，需要去除
			if (lastPortletId == panel.portletId) {
				showPortletIdArr.pop();
				if (showPortletIdArr.length > 0) {
					lastPortletId = showPortletIdArr[showPortletIdArr.length
							- 1];
				} else {
					lastPortletId = null;
				}
				lastPortletColNum--;
			}
		}
	}];
	var data = $.ajax({
				url : '?model=system_portal_usercustomize&action=listJson',
				type : "POST",
				async : false
			}).responseText;
	data = eval(data);
	if (data) {
		data = data[0].customizeStr;
	}else{
		data="0.5,0.5";
	}
	var getcol = data.split(",");
	var colNum = getcol.length;

	var curNum = 0;
	var portletArr = [];
	var viewport;
	// 获取登陆用户的portlet
	$.ajax({
				url : '?model=system_portal_portletuser&action=getCurUserPortlets',
				type : "POST",
				data : {
					sort : 'portletOrder,id',
					dir : 'ASC'
				},
				success : function(data) {
					data = eval("(" + data + ")");
					curNum = data.length;

					for (var i = 0, l = data.length; i < l; i++) {
						var pd = data[i];
						var column = pd.portletOrder % colNum;
						pd.height = pd.height != 0 ? pd.height : 200;
						showPortletIdArr.push(pd.portletId);
						var param = {
							id : "portlet_" + pd.id,
							title : pd.portletName,
							layout : 'fit',
							tools : tools,
							puId : pd.id,
							portletId : pd.portletId,
							// oid : "portlet_" + pd.id,
							html : "<iframe scrolling='auto' width='99%' height="
									+ pd.height
									+ " src='"
									+ pd.url
									+ "'</iframe>"
							// autoLoad : {
							// url : pd.url
							// }
						}
						if (!portletArr[column]) {
							portletArr[column] = [];
						}
						portletArr[column].push(param);
						if (column == colNum - 1) {
							lastPortletColNum = 0
						} else {
							lastPortletColNum = column + 1;
						}
						lastPortletId = pd.portletId;
					}

					var firstItems = [];

					for (var i = 0; i < colNum; i++) {
						firstItems.push({
									columnWidth : getcol[i] - 0.01,
									style : 'padding:10px 0 10px 10px',
									id : "colCmp" + i,
									items : portletArr[i]
								});

					}

					firstItems.push({
								columnWidth : 0,
								id : 'center-panel-col0',
								layout : 'fit',
								hidden : true
							});

					viewport = new Ext.Viewport({
								layout : 'border',
								items : [{
											xtype : 'portal',
											id : 'portalCmp',
											bodyStyle : 'overflow-x:hidden',
											region : 'center',
											margins : '35 0 0 0',
											items : firstItems
										}]
							});
				}
			});

});
