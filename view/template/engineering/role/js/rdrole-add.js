Ext.onReady(function() {
	$.formValidator.initConfig({
				formid : "form1",
				// autotip: true,
				onerror : function(msg) {
					// alert(msg);
				},
				onsuccess : function() {
					if (confirm("������ɹ���ȷ���ύ��")) {
						return true;
					} else {
						return false;
					}
				}
			});
	$("#roleName").formValidator({
				onshow : "�������ɫ����",
				onfocus : "��ɫ��������2���ַ�,���50���ַ�",
				oncorrect : "������Ľ�ɫ���ƿ���"
			}).inputValidator({
				min : 2,
				max : 50,
				empty : {
					leftempty : false,
					rightempty : false,
					emptyerror : "��ɫ�������߲����пշ���"
				},
				onerror : "����������Ʋ��Ϸ�,��ȷ��"
			});
	$("#roleCode").formValidator({
				onshow : "�������ɫ����",
				onfocus : "��ɫ��������2���ַ�,���50���ַ�",
				oncorrect : "������Ľ�ɫ�������"
			}).inputValidator({
				min : 2,
				max : 50,
				empty : {
					leftempty : false,
					rightempty : false,
					emptyerror : "��ɫ�������߲����пշ���"
				},
				onerror : "������Ľ�ɫ���벻�Ϸ�,��ȷ��"
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
