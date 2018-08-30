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
		showcheckbox : false,
		param : {
//			DeptArr : $("#userid").val(),
			statusArr : "4,5,6,7,8"
		},
		isOpButton:false,
		bodyAlign:'center',

		//����Ϣ
		colModel : [ {
			name : 'formCode',
			display : '���ݱ��',
			sortable : true,
			process : function(v,row){
				return "<a href='#' onclick='showOpenWin(\"?model=hr_permanent_examine&action=toView&id="+row.id+"\")'>"+v+"</a>"
			},
			width : 130
		},{
			name : 'formDate',
			display : '��������',
			sortable : true,
			width:80
		},{
			name : 'statusC',
			display : '״̬',
			width:60
		},{
			name : 'ExaStatus',
			display : '���״̬',
			sortable : true,
			width:60
		},{
			name : 'userNo',
			display : 'Ա�����',
			sortable : true,
			width:80
		},{
			name : 'useName',
			display : '����',
			sortable : true,
			width:60
		},{
			name : 'sex',
			display : '�Ա�',
			sortable : true,
			width:30
		},{
			name : 'deptName',
			display : '����',
			sortable : true
		},{
			name : 'positionName',
			display : 'ְλ',
			sortable : true
		},{
			name : 'permanentType',
			display : 'ת������',
			sortable : true,
			width : 80
		},{
			name : 'begintime',
			display : '��ְʱ��',
			sortable : true,
			width : 80
		},{
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
			display : '��������',
			sortable : true,
			width : 80
		},{
			name : 'totalScore',
			display : '��ʦ����',
			sortable : true,
			width : 70
		},{
			name : 'leaderScore',
			display : '�쵼����',
			sortable : true,
			width : 70
		}],

		lockCol:['userNo','useName','deptName'],//����������

		toEditConfig : {
			showMenuFn : function(row) {
				if (row.isAgree == 2 && row.status == 7) {
					return true;
				} else {
					return false;
				}
			},
			toEditFn : function(p, g){
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_permanent_examine&action=toDirectorSet&id=" + rowData[p.keyField]);
				}
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_permanent_examine&action=toView&id=" + rowData[p.keyField] + "&isDirector=1");
				}
			}
		},

		menusEx : [{
			text : '�޸�н��',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isAgree == 2 && row.status == 7) {
					return true;
				} else {
					return false;
				}
			},
			action : function(row, rows, grid) {
				showOpenWin("?model=hr_permanent_examine&action=toDirectorSet&id=" + row.id);
			}
		}],

		comboEx : [{
			text : '״̬',
			key : 'status',
			data : [{
				text : '�쵼���',
				value : '3'
			},{
				text : '�ܼ����',
				value : '4'
			},{
				text : '�������',
				value : '5'
			},{
				text : 'Ա��ȷ��',
				value : '6'
			},{
				text : '���',
				value : '7'
			},{
				text : '�ر�',
				value : '8'
			}]
		},{
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
				text : '��������',
				value : '��������'
			},{
				text : 'δ���',
				value : 'δ���'
			},{
				text : '���',
				value : '���'
			},{
				text : '���',
				value : '���'
			}]
		},{
			text : '�Ƿ�ͬ��',
			key : 'isAgree',
			data : [{
				text : '��',
				value : '1'
			},{
				text : '��',
				value : '2'
			},{
				text : 'δ��д',
				value : '0'
			}]
		}],

		searchitems : [{
			display : "Ա�����",
			name : 'userNo'
		},{
			display : "����",
			name : 'useName'
		},{
			display : "����",
			name : 'deptName'
		},{
			display : "���ݱ��",
			name : 'formCode'
		},{
			display : "��������",
			name : 'formCode'
		},{
			display : "�Ա�",
			name : 'sex'
		},{
			display : "ְλ",
			name : 'positionName'
		},{
			display : "ת������",
			name : 'permanentType'
		},{
			display : "��ְʱ��",
			name : 'begintime'
		},{
			display : "�ƻ�ת��ʱ��",
			name : 'finishtime'
		},{
			display : "ʵ��ת������",
			name : 'permanentDate'
		},{
			display : "��������",
			name : 'selfScore'
		},{
			display : "��ʦ����",
			name : 'totalScore'
		},{
			display : "�쵼����",
			name : 'leaderScore'
		}]
	});
});