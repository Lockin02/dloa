Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();
	var tree = new Ext.ux.tree.MyTree({
		url : 'index1.php?model=system_procity_procitytype&action=listProTypeByParentId&parentId=-1',
		rootId : -1,
		rootText : '省份信息',
		rootVisible : true,
		listeners : {
			click : function(node) {
				Ext.Ajax.request({
					url : 'index1.php?model=system_procity_procitytype&action=getProTypeCodeById',
					success : function(result, request) {
						var json = result.responseText;
						var o = eval("(" + json + ")");
						//document.getElementById("typecode").value = o["typecode"];
					},
					params : {// 参数列表
						id : node.id
					}
				})

			}

		}
	});

	new Ext.ux.combox.ComboBoxTree({
				applyTo : 'parentName',
				hiddenField : 'parentId',
				tree : tree

			})
});