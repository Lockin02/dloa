var show_page = function(page) {
	$("#applyGrid").yxgrid("reload");
};

$(function() {
	$("#butt").hide();
	$("#applyGrid").yxgrid({
		model : 'hr_worktime_apply',
		title : '�����ڼ���ͳ�Ʊ�',
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : false,
		bodyAlign:'center',
		param:{
			ExaStatusArr:'��������,���,���'
		},

		buttonsEx : [{
			name : 'expport',
			text : "����",
			icon : 'excel',
			action : function(row ,rows,idArr) {
				var sql = $("#applyGrid").data('yxgrid').getListSql();
				$("#sql").val(sql);
				$("#butt").click();
			}
		},{
			name : 'expport',
			text : "�߼���ѯ",
			icon : 'view',
			action : function(row) {
				showThickboxWin("?model=hr_worktime_apply&action=toSearch"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=350&width=700");
			}
		}],

		menusEx : [{
			name : 'edit',
			text : '�޸�ʱ��',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=hr_worktime_apply&action=toChangeTime&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=800");
				}
			}
		},{
			name : 'aduit',
			text : '�������',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_hr_worktime_apply&pid="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'userAccount',
			display : 'Ա���˻�',
			sortable : true,
			hide : true
		},{
			name : 'applyCode',
			display : '���뵥��',
			width : 180,
			sortable : true,
			process : function(v,row){
				return '<a href="javascript:void(0)" title="����鿴" onclick="javascript:showThickboxWin(\'?model=hr_worktime_apply&action=toView&id='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800\')">'
					+ "<font color = 'blue'>"
					+ v + "</font>" + '</a>';
			}
		},{
			name : 'userNo',
			display : 'Ա�����',
			width : 60,
			sortable : true
		},{
			name : 'userName',
			display : 'Ա������',
			width : 60,
			sortable : true
		},{
			name : 'deptName',
			display : 'ֱ������',
			width : 80,
			sortable : true
		},{
			name : 'deptNameS',
			display : '��������',
			width : 80,
			sortable : true
		},{
			name : 'deptNameT',
			display : '��������',
			width : 80,
			sortable : true
		},{
			name : 'deptNameF',
			display : '�ļ�����',
			width : 80,
			sortable : true
		},{
			name : 'jobName',
			display : 'ְλ',
			width : 100,
			sortable : true
		},{
			name : 'applyDate',
			display : '��������',
			width : 70,
			sortable : true
		},{
			name : 'holiday',
			display : '�Ӱ�ʱ��',
			width : 100,
			sortable : true,
			process: function(v) {
				var str = v.split(',');
				var holiday = '';
				var holidayInfo = '';
				var rs = '';
				for(var i = 0 ;i < str.length ;i++) {
					holiday = str[i].substr(0 ,10);
					holidayInfo = str[i].substr(-1);
					if (holidayInfo == '1') {
						holidayInfo = '����';
					}else if (holidayInfo == '2') {
						holidayInfo = '����';
					}else if (holidayInfo == '3') {
						holidayInfo = 'ȫ��';
					} else {
						holidayInfo = '';
					}
					rs += holiday + '&nbsp&nbsp' + holidayInfo + '<br>';
				}
				return rs;
			}
		},{
			name : 'workBegin',
			display : '�ϰ࿪ʼʱ��',
			width : 70,
			sortable : true
		},{
			name : 'beginIdentify',
			display : '��ʼ��/����',
			width : 65,
			sortable : true,
			process : function(v) {
				if(v == 1){
					return '����';
				} else if(v == 2){
					return '����';
				}
				return '';
			}
		},{
			name : 'workEnd',
			display : '�ϰ����ʱ��',
			width : 70,
			sortable : true
		},{
			name : 'endIdentify',
			display : '������/����',
			width : 65,
			sortable : true,
			process : function(v) {
				if(v == 1){
					return '����';
				} else if(v == 2){
					return '����';
				}
				return '';
			}
		},{
			name : 'dayNo',
			display : '����',
			width: 40,
			sortable : true
		},{
			name : 'ExaStatus',
			display : '����״̬',
			width : 50,
			sortable : true
		},{
			name : 'workContent',
			display : '�ϰദ��������',
			width : 150,
			sortable : true,
			align : 'left'
		},{
			name : 'changeTimeReason',
			display : '��ע',
			width : 150,
			sortable : true,
			align : 'left',
			process :��function(v) {
				if (v) {
					v = '�޸�ʱ��ԭ��' + v;
				}
				return v;
			}
		},{
			display : '����',
			sortable : true,
			width : 50,
			process :��function(v,row) {
				return '<a href="javascript:void(0)" title="����鿴����" onclick="javascript:showThickboxWin(\'?model=hr_worktime_apply&action=toViewApproval&id='
					+ row.id
					+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=800\')">'
					+ "<font color = 'blue'>"
					+ '�鿴' + "</font>" + '</a><br>';
			}
		}],

		lockCol:['userName','userNo'], //����������

		toViewConfig : {
			formHeight : 600,
			action : 'toView'
		},

		//��������
		comboEx : [{
			text : '����״̬',
			key : 'ExaStatus',
			data : [{
				text : '��������',
				value : '��������'
			},{
				text : '���',
				value : '���'
			},{
				text : '���',
				value : '���'
			}]
		}],

		searchitems : [{
			display : "���뵥��",
			name : 'applyCodeS'
		},{
			display : "Ա������",
			name : 'userNameS'
		},{
			display : "Ա�����",
			name : 'userNoS'
		},{
			display : "ְλ",
			name : 'jobName'
		},{
			display : "ֱ������",
			name : 'deptName'
		},{
			display : "��������",
			name : 'deptNameS'
		},{
			display : "��������",
			name : 'deptNameT'
		},{
			display : "�ļ�����",
			name : 'deptNameF'
		},{
			display : "��������",
			name : 'applyDate'
		},{
			display : "�Ӱ�ʱ��",
			name : 'holiday'
		}]
	});
});