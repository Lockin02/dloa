$(function() {
	$("#esmprojectGrid").esmprojectgrid({
		action : 'pageJsonNotRec',
		title : 'δ������Ŀ',

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=engineering_project_esmproject&action=readTab"
							+ "&id=" + row.id);
				} else {
					alert("��ѡ��һ������");
				}
			}
		},
		{
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status != '7' && row.status != '8' &&row.status != '2'&&row.status != '6' ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(row){
					showOpenWin("?model=engineering_project_esmproject&action=editTab"
						+ "&id="
						+ row.id);
				}else{
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'exam',
			text : 'ָ����Ŀ����',
			icon : 'edit',
			action : function(row) {
				showThickboxWin("?model=engineering_project_esmproject&action=designateManager"
						+ "&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 250 + "&width=" + 600);
			}
		}]
	});

});