var show_page = function(page) {
	$("#formworkGrid").yxgrid("reload");
};
$(function() {
	$("#formworkGrid").yxgrid({
		model : 'hr_leave_formwork',
		title : '��ְ�嵥ģ��',
		showcheckbox : false,
		isDelAction : false,
		isOpButton : false,
		event : {
				'row_dblclick' : function(e, row, data) {
					showThickboxWin("?model=hr_leave_formwork&action=toView&id=" + data.id
									+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900"
					);
 				}
			},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '��������') {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=hr_leave_formwork&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							alert(msg);
							if (msg == 1) {
								alert('ɾ���ɹ���');
								$("#formworkGrid").yxgrid("reload");
							}
						}
					});
				}
			}

		}],
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'items',
			display : '������Ŀ',
			sortable : true,
			width : 200
		}, {
			name : 'recipientName',
			display : '������',
			sortable : true
		}, {
			name : 'createName',
			display : '������',
			sortable : true
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true,
			width : 200
		}],

		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "�����ֶ�",
			name : 'XXX'
		}]
	});
});