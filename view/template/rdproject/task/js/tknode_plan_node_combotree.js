Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();
	var tree = new Ext.ux.tree.MyTree({
		url : 'index1.php?model=rdproject_task_tknode&action=getTkNodeTreeByParentId&planId='+$("#planId").val()+'&parentId=',
		rootId : -1,
		rootText : '½Úµã',
		rootVisible : false,
		listeners : {
			click : function(node) {

			}

		}
	});

	new Ext.ux.combox.ComboBoxTree({
				applyTo : 'parentName',
				hiddenField : 'parentId',
				tree : tree

			})
});