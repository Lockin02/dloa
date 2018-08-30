Ext.onReady(function() {
	$.formValidator.initConfig({
		formid : "form1",
		// autotip: true,
		onerror : function(msg) {
			// alert(msg);
		},
		onsuccess : function() {
			if (confirm("您输入成功,确定保存吗？")) {
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

	// 下拉框的判断
	$("#projectType").formValidator({
		onshow : "请选择项目类型",
		onfocus : "项目类型是必选项",
		oncorrect : "OK"
	}).inputValidator({
		min : 1,
		onerror : "请选择项目类型"
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
