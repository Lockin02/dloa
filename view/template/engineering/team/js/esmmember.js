/**
 * 点击变更角色促发事件
 * 
 * @param {}
 *            memberId
 */
var toChangeRoles = function(memberId) {
	var roleCmp = Ext.getCmp('roleCmp' + memberId);
	$("#roleNameDiv" + memberId).hide();
	$("#save" + memberId).show();
	$("#cancel" + memberId).show();
	if (!roleCmp) {
		$("#roleName" + memberId).show();
		var projectId = $("#projectId").val();
		var url = 'index1.php?model=engineering_role_rdrole&action=ajaxRoleByParent&ischeck=1&projectId='
				+ projectId + '&parentId=';
		var tree = new Ext.ux.tree.MyTree({
					url : url,
					checkModel : 'all',
					rootVisible : false
				});
		new Ext.ux.combox.ComboBoxCheckTree({
			id : 'roleCmp' + memberId,
			applyTo : 'roleName' + memberId,
			hiddenField : 'roleId' + memberId,
			selectValueModel : 'all',
			tree : tree
				// keyUrl : c.keyUrl
			});
	} else {
		roleCmp.show();
	}
}

/**
 * 点击角色保存促发事件
 * 
 * @param {}
 *            memberId
 */
var saveRole = function(memberId) {
	var rolesId = $("#roleId" + memberId).val();
	$.ajax({
				type : "POST",
				url : "?model=engineering_team_rdmember&action=saveRolesToMember",
				data : {
					memberId : memberId,
					rolesId : rolesId
				},
				success : function(msg) {
					if (msg == 1) {
						alert("变更成员角色成功！");
						var rolesName = Ext.getCmp('roleCmp' + memberId)
								.getRawValue();
						$("#roleNameDiv" + memberId).html(rolesName);
					}else{
						alert("变更成员角色失败！");
					}
				}
			});
	cancelRole(memberId);
}

/**
 * 点击取消促发事件
 * 
 * @param {}
 *            memberId
 */
var cancelRole = function(memberId) {
	$("#save" + memberId).hide();
	$("#cancel" + memberId).hide();
	Ext.getCmp('roleCmp' + memberId).hide();
	// $("#roleNameDiv" + memberId).html(Ext.getCmp('roleCmp' + memberId)
	// .getRawValue());
	$("#roleNameDiv" + memberId).show();
}