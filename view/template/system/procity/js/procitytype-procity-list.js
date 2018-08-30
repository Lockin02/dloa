Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();
	var tree = new Ext.ux.tree.MyTree({
		id:'mytree',
		renderTo : 'tree',
		height:500,
		url : 'index1.php?model=system_procity_procitytype&action=listProTypeByParentId&parentId=',
		rootId : -1,
		rootVisible : true,
		rootText : "ʡ����Ϣ",
		listeners : {
			click : function(node) {
				frames["mainFrame"].location.href = "?model=system_procity_procity&action=getProductInfoByTypeId&provinceId="
						+ node.id+"&provinceId="+node.text;
			}
		},
		isRightMenu : true,
		myRightMenus : [{
					text : '����',
					handler : function() {
						var node = tree.selectedNode;
						altStr="?model=system_procity_procitytype&action=toAdd&parentName="+node.text+"&parentId="+node.id+"&placeValuesBefore&TB_iframe=true&modal=false&height=300&width=500";
						showThickboxWin(altStr);
						//frames["mainFrame"]["addprotypebutton"].alt = altStr;
						//frames["mainFrame"]["addprotypebutton"].click();
					}
				}, {
					text : '�޸�',
					handler : function() {

						var node = tree.selectedNode;
						var altStr = "?model=system_procity_procitytype&action=init&id=" + node.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=400";
					    showThickboxWin(altStr);
						// $("#mainFrame:editprotypebutton").attr("alt",altStr);
                        //	 frames["mainFrame"]["editprotypebutton"].alt = altStr;
                        //	 frames["mainFrame"]["editprotypebutton"].click();
					}
				}, {
					text : 'ɾ��',
					handler : function() {
						var node = tree.selectedNode;
						msg = "ȷ��Ҫɾ��!";
						if (window.confirm(msg)) {
							location.href = "?model=system_procity_procitytype&action=deletes&id=" +node.id;
						}
					}
				}]

	});
});


