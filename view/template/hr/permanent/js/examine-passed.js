var show_page = function (page) {
	$("#examineGrid").yxgrid("reload");
};
$(function () {
	var info = $("#userid").val();//��ȡ�˻���Ϣ
	$("#examineGrid").yxgrid({
		model : 'hr_permanent_examine',
		title : '��ת���б�',
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		param : {
			LinkAccount : info,
			statusArr : "7,8"
		},
		//����Ϣ
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'formCode',
				display : '���ݱ��',
				sortable : true,
				process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_permanent_examine&action=toView&id="+row.id+"\")'>"+v+"</a>"
				},
				width : 120
			}, {
				name : 'formDate',
				display : '��������',
				sortable : true
			}, {
				name : 'statusC',
				display : '״̬'
			}, {
				name : 'ExaStatus',
				display : '���״̬',
				sortable : true
			}, {
				name : 'userNo',
				display : 'Ա�����',
				sortable : true
			}, {
				name : 'useName',
				display : '����',
				sortable : true
			}, {
				name : 'sex',
				display : '�Ա�',
				sortable : true,
				width:30
			}, {
				name : 'deptName',
				display : '����',
				sortable : true,
				width:40
			}, {
				name : 'positionName',
				display : 'ְλ',
				sortable : true
			}, {
				name : 'permanentType',
				display : 'ת������',
				sortable : true,
				width : 80
			}, {
				name : 'begintime',
				display : '���ÿ�ʼʱ��',
				sortable : true,
				width : 80
			}, {
				name : 'finishtime',
				display : '���ý���ʱ��',
				sortable : true,
				width : 80
			}, {
				name : 'permanentDate',
				display : 'ʵ��ת������',
				width : 80
			}
		],
		toViewConfig : {
			toViewFn : function(p, g){
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_permanent_examine&action=toView&id=" + rowData[p.keyField]);
				}
			}
		},
		comboEx : [{
			text : '״̬',
			key : 'status',
			data : [ {
						text : '���',
						value : '7'
					}, {
						text : '�ر�',
						value : '8'
					}]
		},{
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
						text : '��������',
						value : '��������'
					}, {
						text : 'δ���',
						value : 'δ���'
					}, {
						text : '���',
						value : '���'
					}, {
						text : '���',
						value : '���'
					}]
			}],
		searchitems : [{
				display : "Ա������",
				name : 'useName'
			},{
				display : "Ա���Ƿ�ͬ��",
				name : 'isAgree'
			},{
				display : "�������",
				name : 'reformDT'
			},{
				display : "����",
				name : 'deptName'
			},{
				display : "ְλ",
				name : 'positionName'
			}
		]
	});
});