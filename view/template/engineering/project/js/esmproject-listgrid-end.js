$(function() {
	$("#esmprojectGrid").esmprojectgrid({
		action : 'pageJsonEnd',
		title : '�ѹر���Ŀ',

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
		}]
	});

});