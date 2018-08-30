Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();
	var tree = new Ext.ux.tree.MyTree({
		url : 'index1.php?model=system_procity_procitytype&action=listProTypeByParentId&parentId=',
		rootId : -1,
		rootText : '省份信息',
		rootVisible : false
	});
	new Ext.ux.combox.ComboBoxTree({
				applyTo : 'provinceName',
				hiddenField : 'provinceId',
				height :150,
				handleHeight :100,
				tree : tree
			});
});