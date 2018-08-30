Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();
	var tree = new Ext.ux.tree.MyTree({
		url : 'index1.php?model=engineering_group_rdgroup&action=ajaxGroupByParent&parentId=',
		rootVisible : false
	});

	tree.on('click', function(node, event) {// 声明菜单类型
		document.getElementById('parentCode').value=node.attributes['groupCode'];
	});

	new Ext.ux.combox.ComboBoxTree({
		applyTo : 'parentName',
		hiddenField : 'parentId',
		tree : tree
			// keyUrl : c.keyUrl
	});
});