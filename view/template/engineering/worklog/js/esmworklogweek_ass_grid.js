$(function() {
	$(".assworklogweekGrid").esmprojectgrid({
		action : 'pageJson',
		title : '������Ϣ',
		comboEx: [{
			text: "�ύ����",
			key: 'subStatus',
			datacode: 'ZBZT'
		}],
		// ��չ��ť
		buttonsEx : [{

			name : 'excel',
			text : '����EXCEL',
			icon : 'excel',
			action : function(row, rows, grid) {
				
				if(confirm('ȷ������Excel')==true){
//						alert();
						showThickboxWin("?model=engineering_worklog_esmworklogweek&action=exportExcel&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");

				}
			}
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			name : 'viewass',
			text : '�鿴��ϸ',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=engineering_worklog_esmworklogweek&action=myassTaskview&id="
							+ row.id
							+ "&subStatus="
							+ row.subStatus
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=900");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}]
	});

});