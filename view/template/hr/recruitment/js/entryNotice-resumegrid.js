var show_page = function(page) {
	$("#entryNoticeGrid").yxgrid("reload");
};
$(function() {
	$("#entryNoticeGrid").yxgrid({
		model : 'hr_recruitment_entryNotice',
		title : '¼��֪ͨ',
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		showcheckbox : false,
		isOpButton:false,
		param :{
			resumeId : $("#resumeId").val()
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
			width : 120,
			sortable : true,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_entryNotice&action=toView&id=" + row.id + "\")'>" + v + "</a>";
			}
		}, {
			name : 'formDate',
			display : '��������',
			sortable : true,
			width:80
		}, {
			name : 'interviewType',
			display : '��������',
			sortable : true,
			width:70,
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
			sortable : true,
			width:60
		}, {
			name : 'entryDate',
			display : '��ְ����',
			sortable : true,
			width:80
		}, {
			name : 'stateC',
			display : '״̬',
			width:80
		}, {
			name : 'sex',
			display : '�Ա�',
			sortable : true,
			width:60
		}, {
			name : 'phone',
			display : '��ϵ�绰',
			sortable : true
		}, {
			name : 'applyCode',
			display : 'ְλ������',
			sortable : true,
			width:110,
			process : function(v,row){
					return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitment_employment&action=toView&id=" + row.applyId +"\")'>" + v + "</a>";
			}
		}, {
			name : 'email',
			display : '��������',
			sortable : true,
			width:110
		}, {
			name : 'positionsName',
			display : 'ӦƸ��λ',
			sortable : true
		}, {
			name : 'developPositionName',
			display : '�з�ְλ',
			sortable : true,
			width:60
		}, {
			name : 'deptName',
			display : '���˲���',
			sortable : true
		}, {
			name : 'useHireTypeName',
			display : '¼����ʽ',
			sortable : true

		}, {
			name : 'useAreaName',
			display : '���������֧������',
			sortable : true
		}, {
			name : 'assistManName',
			display : '��ְЭ����',
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
			sortable : true,
			width:60
		}, {
			name : 'contractYear',
			display : '��ͬ����',
			sortable : true,
			width:60
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
			sortable : true,
			width:80
		}],
		lockCol:['formCode','formDate','userName'],//����������
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