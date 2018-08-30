Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();
	var tree = new Ext.ux.tree.MyTree({
				url : 'index1.php?model=system_datadict_datadict&action=listByParentCode&parentCode=pgzb&parentId=',
				rootVisible : false
			});

	tree.on('click', function(node, event) {// 声明菜单类型
		document.getElementById('normCode').value=node.attributes['dataCode'];
	});

	new Ext.ux.combox.ComboBoxTree({
		applyTo : 'normName',
		hiddenField : 'normId',
		tree : tree,
		editable:true
			// keyUrl : c.keyUrl
		});
});