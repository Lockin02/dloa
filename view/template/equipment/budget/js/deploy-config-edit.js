var show_page = function(page) {
	$("#deployEditGrid").yxgrid("reload");
};
$(function() {
	$("#deployEditGrid").yxgrid({
		model : 'equipment_budget_deploy',
		param : {
			equId : $("#equId").val()
		},
		showcheckbox : false,
		isDelAction : false,
		isViewAction : false,
		title : '���ù���',
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'name',
			display : '�豸����',
			sortable : true,
			width : 200
		}, {
			name : 'info',
			display : '��ϸ����',
			sortable : true,
			width : 300
		}, {
			name : 'price',
			display : '����',
			sortable : true,
			width : 200,
			process : function(v) {
				return moneyFormat2(v);
			}
		}
//		, {
//			name : 'remark',
//			display : '��ע',
//			sortable : true
//		}
		],
		menusEx : [ {
			text : 'ɾ��',
			icon : 'delete',
			action : function(row, rows, grid) {
				if (window.confirm("ȷ��Ҫɾ��?")) {
					$.ajax({
						type : "POST",
						url : "?model=equipment_budget_deploy&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('ɾ���ɹ���');
							} else {
								alert('ɾ��ʧ��!');
							}
						}
					});
				}
			}
		}],
		toAddConfig : {
			toAddFn : function(p) {
				showThickboxWin("?model=equipment_budget_deploy&action=toAdd&equId="
						+ $("#equId").val()
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=750");
			}
//			action : 'toAdd',
//			formHeight : 400,
//			formWidth : 750
		},
		toEditConfig : {
			action : 'toEdit',
			formHeight : 400,
			formWidth : 750
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 400,
			formWidth : 750
		},
		searchitems : [{
			display : "�豸��������",
			name : 'budgetType'
		}, {
			display : "�ϼ�����",
			name : 'parentName'
		}]
	});
});