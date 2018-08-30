var show_page = function(page) {
	$("#requireGrid").yxsubgrid("reload");
};

//������ڸ���
function requireNoRed(v){
	var strArr = v.split('');
	var newStr = '';
	for(var i = 0;i < strArr.length ; i++){
		if(i == 4){
			newStr += "<span class='blue'>" + strArr[i];
		}else if(i == 11){
			newStr += strArr[i] + "</span>";
		}else{
			newStr += strArr[i];
		}
	}
	return newStr;
}

$(function() {
	$("#requireGrid").yxsubgrid({
		model: 'flights_require_require',
		title: '��Ʊ����',
		showcheckbox : false,
		isAddAction: false,
		isEditAction: false,
		isDelAction: false,
		isOpButton : false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'requireNo',
			display: '��Ʊ�����',
			sortable: true,
			width: 120,
			process : function(v){
				return requireNoRed(v);
			}
		},
		{
			name: 'requireId',
			display: '������ID',
			hide: true,
			sortable: true
		},
		{
			name: 'requireName',
			display: '������',
//			hide: true,
			sortable: true
		},
		{
			name: 'requireTime',
			display: '��������',
			sortable: true,
			hide: true,
			width : 70
		},
		{
			name: 'companyName',
			display: '���ڹ�˾',
			sortable: true,
			hide: true,
			width : 80
		},
		{
			name: 'deptName',
			display: '���ڲ���',
			sortable: true,
			hide: true,
			width : 80
		},
		{
			name: 'airName',
			display: '�˻���',
			sortable: true,
			hide: true,
			width : 80
		},
		{
			name: 'airPhone',
			display: '�ֻ�����',
			sortable: true,
			hide: true,
			width : 80
		},
		{
			name: 'cardTypeName',
			display: '֤������',
			sortable: true,
			hide: true,
			width : 80
		},
		{
			name: 'birthDate',
			display: '��������',
			hide: true,
			sortable: true
		},
		{
			name: 'flyStartTime',
			display: '��ʼʱ��',
			hide: true,
			sortable: true
		},
		{
			name: 'flyEndTime',
			display: '����ʱ��',
			hide: true,
			sortable: true
		},
		{
			name: 'ticketType',
			display: '��Ʊ����',
			sortable: true,
			process: function(v) {
				if (v == "10") {
					return '����';
				} else if (v == "11") {
					return '����';
				} else if (v == "12") {
					return '����';
				}
			},
			width : 80
		},
		{
			name: 'startPlace',
			display: '��������',
			sortable: true,
			width : 80
		},
		{
			name: 'middlePlace',
			display: '��ת����',
			sortable: true,
			width : 80
		},
		{
			name: 'endPlace',
			display: '�������',
			sortable: true,
			width : 80
		},
		{
			name: 'startDate',
			display: '����ʱ��',
			sortable: true,
			width : 80
		},
		{
			name: 'twoDate',
			display: '�ڶ�����ת����',
			sortable: true,
			width : 85
		},
		{
			name: 'comeDate',
			display: '����ʱ��',
			sortable: true,
			width : 80
		},
		{
			display: '��Ʊ״̬',
			name: 'ticketMsg',
			sortable: true,
			process: function(v) {
				if (v == "0") {
					return 'δ����';
				} else if (v == "1") {
					return ' ������';
				}
			},
			width : 70
		},
		{
			name: 'ExaStatus',
			display: '����״̬',
			width : 70
		},
		{
			name: 'ExaDT',
			display: '����ʱ��',
			sortable: true,
			width : 70
		},
		{
			name: 'detailType',
			display: '���ù�������',
			sortable: true,
			process: function(v) {
				if (v == "1") {
					return '���ŷ���';
				} else if (v == "2") {
					return '������Ŀ����';
				} else if (v == "3") {
					return '�з���Ŀ����';
				} else if (v == "4") {
					return '��ǰ����';
				} else if (v == "5") {
					return '�ۺ����';
				}
			},
			hide: true,
			width : 80
		},
		{
			name: 'costBelongComId',
			display: '���ù�����˾Id',
			hide: true,
			sortable: true
		},
		{
			name: 'costBelongCom',
			display: '���ù�����˾',
			hide: true,
			sortable: true
		},
		{
			name: 'costBelongDeptId',
			display: '���ù�������Id',
			hide: true,
			sortable: true
		},
		{
			name: 'costBelongDeptName',
			display: '���ù�������',
			hide: true,
			sortable: true
		},
		{
			name: 'projectCode',
			display: '��ͬ���',
			hide: true,
			sortable: true
		},
		{
			name: 'projectId',
			display: '��ͬId',
			hide: true,
			sortable: true
		},
		{
			name: 'projectName',
			display: '��ͬ����',
			hide: true,
			sortable: true
		},
		{
			name: 'proManagerName',
			display: '��Ŀ����',
			hide: true,
			sortable: true
		},
		{
			name: 'contractCode',
			display: '��ͬ����',
			hide: true,
			sortable: true
		},
		{
			name: 'contractId',
			display: '��ͬId',
			hide: true,
			sortable: true
		},
		{
			name: 'contractName',
			display: '��ͬ����',
			hide: true,
			sortable: true
		},
		{
			name: 'customerId',
			display: '�ͻ�ID',
			hide: true,
			sortable: true
		},
		{
			name: 'customerName',
			display: '�ͻ�����',
			hide: true,
			sortable: true
		},
		{
			name: 'createName',
			display: '������',
			hide: true,
			sortable: true
		},
		{
			name: 'createTime',
			display: '����ʱ��',
			hide: true,
			sortable: true
		},
		{
			name: 'updateName',
			display: '������',
			hide: true,
			sortable: true
		},
		{
			name: 'updateTime',
			display: '����ʱ��',
			sortable: true,
			width : 130
		}],
		// ���ӱ������
		subGridOptions: {
			url: '?model=flights_require_requiresuite&action=pageJson&cardNoLimit=1',
			param: [{
				paramId: 'mainId',
				colId: 'id'
			}],
			colModel: [{
				name: 'employeeTypeName',
				display: 'Ա������',
				sortable: true
			},
			{
				name: 'airName',
				display: '����',
				sortable: true
			},
			{
				name: 'airPhone',
				display: '�ֻ�����',
				sortable: true
			},
			{
				name: 'cardTypeName',
				display: '֤������',
				sortable: true,
				width : 80
			},
			{
				name: 'cardNo',
				display: '֤������',
				sortable: true,
				width : 140
			},
			{
				name: 'validDate',
				display: '֤����Ч��',
				sortable: true,
				width : 80
			},
			{
				name: 'birthDate',
				display: '��������',
				sortable: true,
				width : 80
			},
			{
				name: 'nation',
				display: '����',
				sortable: true
			},
			{
				name: 'tourAgencyName',
				display: '���ÿͻ���',
				sortable: true
			},
			{
				name: 'tourCardNo',
				display: '���ÿͿ���',
				sortable: true
			}]
		},
		toViewConfig : {
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showModalWin("?model=flights_require_require&action=viewTab&id=" + rowData[p.keyField]+"&cardNoLimit=1");
			}
		},
		// ��չ�Ҽ��˵�
		menusEx: [{
			name: 'aduit',
			text: '�������',
			icon: 'view',
			showMenuFn: function(row) {
				if (row.ExaStatus != "���ύ") {
					return true;
				}
				return false;
			},
			action: function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_flights_require&pid=" + row.id + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600");
				}
			}
		},
		{
			name: 'ExaStatus',
			text: '¼�붩Ʊ��Ϣ',
			icon: 'add',
			showMenuFn: function(row) {
				if (row.ticketMsg == "0" && row.ExaStatus != '���') {
					return true;
				}
				return false;
			},
			action: function(row, rows, grid) {
				showModalWin("index1.php?model=flights_message_message&action=toAddPush&id="
						+ row.id
						+ "&requireNo="
						+ row.requireNo
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=1300");
			}
		}],
		//��������
		comboEx : [{
			text : '��Ʊ״̬',
			key : 'ticketMsg',
			data : [{
				text : '������',
				value : '1'
			}, {
				text : 'δ����',
				value : '0'
			}]
		},{
			text : '����״̬',
			key : 'ExaStatus',
			type : 'workFlow'
		}],
		searchitems: [{
			display: "��Ʊ�����",
			name: 'requireNoSearch'
		},{
			display: "�˻���",
			name: 'airNameSearch'
		},{
			display: "��������",
			name: 'startPlaceSearch'
		},{
			display: "��ת����",
			name: 'middlePlaceSearch'
		},{
			display: "�������",
			name: 'endPlaceSearch'
		}],
		sortname : "c.createTime"
	});
});