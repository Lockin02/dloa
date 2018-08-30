Ext.onReady(function() {
	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();
	var projectId = $("#projectId").val();
	var filetree = new Ext.ux.tree.MyTree({
		url : '?model=rdproject_uploadfile_uploadfiletype&action=tree&projectId='
				+ projectId + "&parentId=",
		rootId : -1,
		rootVisible : false,
		listeners : {
			click : function(node) {
				uploadfile.addPostParam("typeId", node.id);
				uploadfile.addPostParam("typeName", node.text);
			}

		}
	});
	new Ext.ux.combox.ComboBoxTree({
		emptyText : '请选择附件类型...',
		applyTo : 'uploadfiletype',
		hiddenField : 'uploadfiletypeId',
		tree : filetree
	});

});
