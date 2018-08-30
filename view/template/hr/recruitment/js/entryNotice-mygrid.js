var show_page = function(page) {
	$("#entryNoticeGrid").yxgrid("reload");
};
$(function() {
	$("#entryNoticeGrid").yxgrid({
		model : 'hr_recruitment_entryNotice',
		title : '�ҵ���ְ����',
		param:{
			createId: $("#userid").val()
		},
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
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
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_entryNotice&action=toView&id=" + row.id + "\")'>" + v + "</a>";
			}
		}, {
			name : 'formDate',
			display : '��������',
			sortable : true
		}, {
			name : 'interviewType',
			display : '��������',
			sortable : true,
			process : function(v){
				if(v==1)
					return "��Ա����";
				else if(v==2)
					return "�ڲ��Ƽ�";
			}
		}, {
			name : 'resumeCode',
			display : '�������',
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_resume&action=toRead&code=" + v +"\")'>" + v + "</a>";
				}
		}, {
			name : 'userName',
			display : '����',
			sortable : true
		}, {
			name : 'sex',
			display : '�Ա�',
			sortable : true
		}, {
			name : 'phone',
			display : '��ϵ�绰',
			sortable : true
		}, {
			name : 'applyCode',
			display : 'ְλ������',
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_employment&action=toView&id=" + row.applyId +"\")'>" + v + "</a>";
			}
		}, {
			name : 'email',
			display : '��������',
			sortable : true
		}, {
			name : 'positionsName',
			display : 'ӦƸ��λ',
			sortable : true
		}, {
			name : 'developPositionName',
			display : '�з�ְλ',
			sortable : true
		}, {
			name : 'deptName',
			display : '���˲���',
			sortable : true
		}, {
			name : 'useHireTypeName',
			display : '¼����ʽ',
			sortable : true

		}, {
			name : 'useJobName',
			display : 'ְλ����',
			sortable : true
		}, {
			name : 'useAreaName',
			display : '���������֧������',
			sortable : true
		}, {
			name : 'useDemandEqu',
			display : '�����豸',
			sortable : true
		}, {
			name : 'useSign',
			display : 'ǩ������ҵ����Э�顷',
			sortable : true
		}, {
			name : 'probation',
			display : '������',
			sortable : true
		}, {
			name : 'contractYear',
			display : '��ͬ����',
			sortable : true
		}, {
			name : 'addType',
			display : '��Ա����',
			sortable : true
		}, {
			name : 'hrSourceType1Name',
			display : '������Դ����',
			sortable : true
		}, {
			name : 'hrJobName',
			display : '¼��ְλ����',
			sortable : true
		}, {
			name : 'hrIsManageJob',
			display : '�Ƿ�����',
			sortable : true
		}, {
			name : 'entryDate',
			display : '��ְ����',
			sortable : true
		}, {
			name : 'stateC',
			display : '״̬'
		}],
		toViewConfig : {
			toViewFn : function (p, g) {
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					var rowData = rowObj.data('data');
					showOpenWin("?model=hr_recruitment_entryNotice&action=toView&id=" + rowData[p.keyField]);
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},

		toEditConfig : {
			action : 'toEdit'
		},
		searchitems : [{
			display : "���˲���",
			name : 'deptName'
		},
		{
			display : "ӦƸ��λ",
			name : 'positionsName'
		},
		{
			display : "����",
			name : 'userName'
		},
		{
			display : "��Ա����",
			name : 'addType'
		}]
	});
});