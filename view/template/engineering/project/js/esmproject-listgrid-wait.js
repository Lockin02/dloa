$(function() {
	$("#esmprojectGrid").esmprojectgrid({
		action : 'pageJsonWait',
		title : '��������Ŀ',
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_project_esmproject&action=readTab"
							+ "&id=" + row.id);
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '��������') {
					return false;
				}
			},
			action : function(row) {
				if (row) {
					showOpenWin("?model=engineering_project_esmproject&action=editTab"
							+ "&id=" + row.id);
				} else {
					alert("��ѡ��һ������");
				}
			}
		}]

	});

});