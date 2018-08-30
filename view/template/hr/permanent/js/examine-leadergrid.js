var show_page = function (page) {
	$("#examineGrid").yxgrid("reload");
};
$(function () {
	$("#examineGrid").yxgrid({
		model : 'hr_permanent_examine',
		title : 'Ա��ת������������',
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		param : {
			leaderId : $("#leaderid").val(),
			statusArr : "3,4,5,6,7,8"
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
				display : '��ְʱ��',
				sortable : true,
				width : 80
			}, {
				name : 'finishtime',
				display : '�ƻ�ת��ʱ��',
				sortable : true,
				width : 80
			},{
				name : 'permanentDate',
				display : 'ʵ��ת������',
				width : 80
			},{
				name : 'selfScore',
				display : '�����ܷ�',
				sortable : true,
				width : 80
			}, {
				name : 'totalScore',
				display : '���˳ɼ�',
				sortable : true,
				width : 80
			}
		],
		toEditConfig : {
			text : '��д���',
			showMenuFn : function(row) {
				if (row.status == 3&&row.ExaStatus== "δ���") {
					return true;
				} else
					return false;
			},
			toEditFn : function(p, g){
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_permanent_examine&action=toLeaderEdit&id=" + rowData[p.keyField]);
				}
			}
		},
		toViewConfig : {
			toViewFn : function(p, g){
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_permanent_examine&action=toView&id=" + rowData[p.keyField]);
				}
			}
		},
		menusEx : [{
			text : '����쵼���',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 3&&row.ExaStatus== "δ���") {
					return true;
				} else
					return false;
			},
			action : function(row, rows, grid){
					showOpenWin("?model=hr_permanent_examine&action=toLeaderEdit&id=" + row.id);
			}
		},{
			text : '�ύ�ܼ����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == 3&&row.ExaStatus== "δ���") {
					return true;
				} else
					return false;
			},
			action : function(row, rows, grid) {
				location = "controller/hr/permanent/ewf_examine_index.php?actTo=ewfSelect&billId="+row.id+"&examCode=oa_hr_permanent_examine&formName=���ÿ�������";
			}
		}],
		comboEx : [{
			text : '״̬',
			key : 'status',
			data : [{
						text : '�쵼���',
						value : '3'
					}, {
						text : 'Ա��ȷ��',
						value : '6'
					}, {
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