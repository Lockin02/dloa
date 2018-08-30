var show_page = function(page) {
	$("#adjustGrid").yxgrid("reload");
};
$(function() {
	$("#adjustGrid").yxgrid({
		model: 'finance_adjust_adjust',
		title: '���',
		showcheckbox : false,
		isAddAction : false,
		isEditAction :false,
		isViewAction : false,
		isDelAction : false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'adjustCode',
			display: '������',
			sortable: true,
			width : 120
		}, {
			name: 'supplierName',
			display: '��Ӧ������',
			sortable: true,
			width : 130
		}, {
			name: 'formDate',
			display: '��������',
			sortable: true
		}, {
			name: 'relatedId',
			display: '�������',
			sortable: true
		}, {
			name: 'amount',
			display: '������',
			sortable: true,
			process : function (v){
				return moneyFormat2(v);
			}
		}, {
			name: 'createName',
			display: '������',
			sortable: true
		}, {
			name: 'createTime',
			display: '����ʱ��',
			sortable: true,
			width : 130
		}],
		buttonsEx : [{
			name : 'add',
			text : "���б�",
			icon : 'search',
			action : function() {
				showModalWin('?model=finance_adjust_adjust&action=listInfo',1);
			}
		}],
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showThickboxWin("?model=finance_adjust_adjust"
					+ "&action=init"
					+ "&id="
					+ row.id
					+ "&perm=view&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
			}
		}, {
			text: "ɾ��",
			icon: 'delete',
			action: function(row) {
				if (window.confirm(("ȷ��Ҫɾ��?"))) {
					$.ajax({
						type : "POST",
						url : "?model=finance_adjust_adjust&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == "1") {
								alert('ɾ���ɹ���');
								show_page(1);
							}else{
								alert("ɾ��ʧ��! ");
							}
						}
					});
				}
			}
		}],
		searchitems:[{
			display:'��Ӧ������',
			name:'supplierName'
		}, {
			display:'�������',
			name:'relatedId'
		}, {
			display:'��������',
			name:'formDate'
		}, {
			display:'������',
			name:'createName'
		}]
	});
});