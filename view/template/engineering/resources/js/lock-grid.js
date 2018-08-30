var show_page = function(page) {
	$("#lockGrid").yxgrid("reload");
};
$(function() {
	$("#lockGrid").yxgrid({
		model : 'engineering_resources_lock',
		title : '�豸������������',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		isOpButton : false,
		showcheckbox : false,
		param : {
			status : 1
		},
		// ����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'lockDate',
				display : '����ʱ��',
				sortable : true
			}, {
				name : 'userId',
				display : 'Ա��id',
				sortable : true,
				hide : true
			}, {
				name : 'userName',
				display : 'Ա������',
				sortable : true
			}],			
			menusEx : [{
				text : "����",
				icon : 'edit',
				action : function(row) {
					if (window.confirm(("ȷ��Ҫ����?"))) {
						$.ajax({
							type : "POST",
							url : "?model=engineering_resources_lock&action=ajaxUnlock",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert("�����ɹ�!");
									show_page(1);
								} else {
									alert("����ʧ��!");
								}
							}
						});
					}
				}
			}],
			searchitems : [{
				display : "Ա������",
				name : 'userNameLike'
			}]
	});
});