$(function() {
	$("#esmprojectGrid").esmprojectgrid({
		action : 'pageJsonExecute',
		title : '��ִ����Ŀ',
		// ��չ�Ҽ��˵�
		menusEx : [
		{
			text : '�鿴',
			icon : 'view',
			action :function(row,rows,grid) {
				if(row){
					showOpenWin("?model=engineering_project_esmproject&action=readTab"
						+ "&id="
						+ row.id);
				}else{
					alert("��ѡ��һ������");
				}
			}
		},{
			text : '����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '6') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=engineering_project_esmproject&action=showFeedback"
						+ "&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 300 + "&width=" + 600
					);
			}
		},{
			text : '�ر�',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '6') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showThickboxWin("?model=engineering_project_esmproject&action=closeProject"
						+ "&id="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 200 + "&width=" + 600
					);
			}
		}]

	});

});