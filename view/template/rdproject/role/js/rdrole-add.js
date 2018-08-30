Ext.onReady(function() {
	$.formValidator.initConfig({
		formid : "form1",
		// autotip: true,
		onerror : function(msg) {
			// alert(msg);
		},
		onsuccess : function() {
			if (confirm("������ɹ�,ȷ��������")) {
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

	// ��������ж�
	$("#projectType").formValidator({
		onshow : "��ѡ����Ŀ����",
		onfocus : "��Ŀ�����Ǳ�ѡ��",
		oncorrect : "OK"
	}).inputValidator({
		min : 1,
		onerror : "��ѡ����Ŀ����"
	}).defaultPassed();

	Ext.BLANK_IMAGE_URL = 'js/ext/resources/images/default/s.gif';
	Ext.QuickTips.init();

});

function selectType() {
	var url = "";
	var projectType = $("#projectType").val();
	if (projectType) {
		url = 'index1.php?model=rdproject_role_rdrole&action=ajaxRoleByParent&projectType='
				+ projectType + '&parentId=';
	}
	var projectId = $("#projectId").val();
	if (projectId) {
		url = 'index1.php?model=rdproject_role_rdrole&action=ajaxRoleByParent&projectId='
				+ projectId + '&parentId=';
	}
	if (Ext.getCmp('projectTypeCombox')) {
//		$("#parentName").val("");
//		$("#parentId").val("");
		var tree = Ext.getCmp('projectTypeCombox').tree;
		tree.loader = new Ext.tree.TreeLoader({
			url : 'index1.php?model=rdproject_role_rdrole&action=ajaxRoleByParent&projectType='
					+ projectType + '&parentId=-1'
		});
		tree.expandTreeLoader = tree.loader;
		tree.root.loader = tree.loader;
		tree.loader.on("beforeload", function(loader, node) {
			if (tree.loadAttributes == '' || node.id == -1) {
				var parent = node.attributes[tree.nodeValue];
				loader.url = url + parent;
			} else {
				loader.url = node.attributes[tree.loadAttributes];
			}
		});
		if (tree.rendered) {
			tree.root.reload();
		}
	} else {
		var tree = new Ext.ux.tree.MyTree({
			url : url,
			rootVisible : false
		});

		new Ext.ux.combox.ComboBoxTree({
			id : 'projectTypeCombox',
			applyTo : 'parentName',
			hiddenField : 'parentId',
			tree : tree
		// keyUrl : c.keyUrl
		});
	}

}

$(document).ready(function(){
	var projectId = $("#projectId").val();
	if(projectId){
		$("#projectshow").hide();
	}
});
