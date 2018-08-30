Ext.onReady(function() {
	$.formValidator.initConfig({
				formid : "form1",
				// autotip: true,
				onerror : function(msg) {
					// alert(msg);
				},
				onsuccess : function() {
					if (confirm("您输入成功，确定提交吗？")) {
						return true;
					} else {
						return false;
					}
				}
			});
	$("#roleName").formValidator({
				onshow : "请输入角色名称",
				onfocus : "角色名称至少2个字符,最多50个字符",
				oncorrect : "您输入的角色名称可用"
			}).inputValidator({
				min : 2,
				max : 50,
				empty : {
					leftempty : false,
					rightempty : false,
					emptyerror : "角色名称两边不能有空符号"
				},
				onerror : "你输入的名称不合法,请确认"
			});
	$("#roleCode").formValidator({
				onshow : "请输入角色编码",
				onfocus : "角色编码至少2个字符,最多50个字符",
				oncorrect : "您输入的角色编码可用"
			}).inputValidator({
				min : 2,
				max : 50,
				empty : {
					leftempty : false,
					rightempty : false,
					emptyerror : "角色编码两边不能有空符号"
				},
				onerror : "你输入的角色编码不合法,请确认"
			});

	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();

	var url = "";
	var projectType = $("#projectType").val();
	if (projectType) {
		url = 'index1.php?model=engineering_role_rdrole&action=ajaxRoleByParent&projectType='
				+ projectType + '&parentId=';
	}
	var projectId = $("#projectId").val();
	if (projectId) {
		url = 'index1.php?model=engineering_role_rdrole&action=ajaxRoleByParent&projectId='
				+ projectId + '&parentId=';
	}
	var tree = new Ext.ux.tree.MyTree({
				url : url,
				rootVisible : false
			});

	new Ext.ux.combox.ComboBoxTree({
		applyTo : 'parentName',
		hiddenField : 'parentId',
		tree : tree
			// keyUrl : c.keyUrl
		});
});
