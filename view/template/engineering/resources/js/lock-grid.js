var show_page = function(page) {
	$("#lockGrid").yxgrid("reload");
};
$(function() {
	$("#lockGrid").yxgrid({
		model : 'engineering_resources_lock',
		title : '设备申请锁定管理',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		isOpButton : false,
		showcheckbox : false,
		param : {
			status : 1
		},
		// 列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'lockDate',
				display : '锁定时间',
				sortable : true
			}, {
				name : 'userId',
				display : '员工id',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : '员工姓名',
				sortable : true
			}],			
			menusEx : [{
				text : "解锁",
				icon : 'edit',
				action : function(row) {
					if (window.confirm(("确定要解锁?"))) {
						$.ajax({
							type : "POST",
							url : "?model=engineering_resources_lock&action=ajaxUnlock",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert("解锁成功!");
									show_page(1);
								} else {
									alert("解锁失败!");
								}
							}
						});
					}
				}
			}],
			searchitems : [{
				display : "员工姓名",
				name : 'userNameLike'
			}]
	});
});