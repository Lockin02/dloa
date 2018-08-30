var show_page = function(page) {
	$("#rentcarGrid").yxgrid("reload");
};

$(function() {
	$("#rentcarGrid").yxgrid({
		model : 'outsourcing_contract_rentcar',
		param : { "signedStatus" : '0','statusArr' : '2,3,4' },
		title : 'δǩ�յ��⳵��ͬ',
		bodyAlign : 'center',
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isOpButton : false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},{
			name: 'createDate',
			display: '¼������',
			sortable: true,
			width : 70
		},{
			name: 'orderCode',
			display: '������ͬ���',
			sortable: true,
			width : 130,
			process : function(v,row){
				if (row.status == 4) {
					return "<a href='#' style='color:red' onclick='showModalWin(\"?model=outsourcing_contract_rentcar&action=viewTab&id=" + row.id + "\",1)'>" + v + "</a>";
				} else{
					return "<a href='#' onclick='showModalWin(\"?model=outsourcing_contract_rentcar&action=viewTab&id=" + row.id + "\",1)'>" + v + "</a>";
				}
			}
		},{
			name: 'contractNature',
			display: '��ͬ����',
			sortable: true,
			width : 75
		},{
			name: 'contractType',
			display: '��ͬ����',
			sortable: true,
			width : 75
		},{
			name: 'projectCode',
			display: '��Ŀ���',
			sortable: true,
			width : 130,
			align : 'left'
		},{
			name: 'projectName',
			display: '��Ŀ����',
			sortable: true,
			width : 130,
			align : 'left'
		},{
			name: 'orderName',
			display: '��ͬ����',
			sortable: true,
			width : 130,
			align : 'left'
		},{
			name: 'signCompany',
			display: 'ǩԼ��˾',
			sortable: true,
			width : 150,
			align : 'left'
		},{
			name: 'companyProvince',
			display: '��˾ʡ��',
			sortable: true,
			width : 70
		},{
			name: 'ownCompany',
			display: '������˾',
			sortable: true,
			width : 80
		},{
			name: 'linkman',
			display: '��ϵ��',
			sortable: true,
			hide : true,
			width : 60
		},{
			name: 'phone',
			display: '��ϵ�绰',
			sortable: true,
			hide : true,
			width : 85
		},{
			name: 'address',
			display: '��ϵ��ַ',
			sortable: true,
			hide : true,
			width : 150,
			align : 'left'
		},{
			name: 'signDate',
			display: 'ǩԼ����',
			sortable: true,
			width : 70
		},{
//			name: 'payedMoney',
//			display: '�Ѹ����',
//			sortable: true,
//			process : function(v) {
//				return moneyFormat2(v);
//			},
//			align : 'left'
//		},{
			name: 'orderMoney',
			display: '��ͬ���',
			sortable: true,
			process : function(v) {
				return moneyFormat2(v);
			},
			align : 'left'
		},{
            name: 'contractStartDate',
            display: '��ͬ��ʼ����',
            sortable: true,
            width : 75
        },{
            name: 'contractEndDate',
            display: '��ͬ��������',
            sortable: true,
            width : 75
        },{
            name: 'rentUnitPrice',
            display: '���޷���(Ԫ/��/��)',
            sortable: true,
            width : 100,
            process : function(v) {
                return moneyFormat2(v);
            },
            align : 'left'
        },{
            name: 'fuelCharge',
            display: 'ȼ�ͷ�(Ԫ/����)',
            sortable: true,
            width : 85,
            process : function(v) {
                return moneyFormat2(v);
            },
            align : 'left'
        },{
			name: 'returnMoney',
			display: '������',
			sortable: true,
			process : function(v) {
				return moneyFormat2(v);
			},
			align : 'left'
		},{
			name: 'status',
			display: '״̬',
			sortable: true,
			width : 70,
			process : function(v,row){
				if(v == 0){
					return "δ�ύ";
				}else if(v == 1){
					return "������";
				}else if(v == 2){
					return "ִ����";
				}else if(v == 3){
					return "�ѹر�";
				}else if(v == 4){
					return "�����";
				}
			}
		},{
			name: 'ExaStatus',
			display: '����״̬',
			sortable: true,
			width : 70
		},{
			name: 'signedStatus',
			display: '��ͬǩ��',
			sortable: true,
			width : 70,
			process : function(v) {
				if (v == 0) {
					return 'δǩ��';
				}else {
					return '��ǩ��';
				}
			}
		},{
			name: 'objCode',
			display: 'ҵ����',
			sortable: true
		},{
			name: 'isNeedStamp',
			display: '�Ƿ���Ҫ����',
			sortable: true,
			width : 70,
			process : function(v) {
				if (v == 0) {
					return '��';
				}else {
					return '��';
				}
			}
		},{
			name: 'isStamp',
			display: '�Ƿ��Ѹ���',
			sortable: true,
			width : 70,
			process : function(v) {
				if (v == 0) {
					return '��';
				}else {
					return '��';
				}
			}
		},{
			name: 'stampType',
			display: '��������',
			sortable: true,
			width : 150,
			align : 'left'
		},{
//			name: 'rentalcarCode',
//			display: '�⳵����Code',
//			sortable: true
//		},{
//			name: 'rentUnitPrice',
//			display: '���޵��ۣ�Ԫ/��/����',
//			sortable: true
//		},{
//			name: 'oilPrice',
//			display: '�ͼ�',
//			sortable: true
//		},{
//			name: 'fuelCharge',
//			display: 'ȼ�ͷѵ���',
//			sortable: true
//		},{
			name: 'createName',
			display: '������',
			sortable: true,
			width : 80
		},{
			name: 'updateTime',
			display: '����ʱ��',
			sortable: true,
			width : 120
		}],

		menusEx : [{
			text : 'ǩ�պ�ͬ',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status == '4') {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				showOpenWin("?model=outsourcing_contract_rentcar&action=toSign&id="
					+ row.id
					+ "&skey=" + row.skey_
				);
			}
		}],

		comboEx : [{
			text: "״̬",
			key: 'status',
			data : [{
				text : 'ִ����',
				value : '2'
			},{
				text : '�ѹر�',
				value : '3'
			},{
				text : '�����',
				value : '4'
			}]
		},{
			text: "��ͬ����",
			key: 'contractNatureCode',
			datacode : 'ZCHTXZ'
		},{
			text: "��ͬ����",
			key: 'contractTypeCode',
			datacode : 'ZCHTLX'
		}],

		// ���ӱ������
		subGridOptions: {
			url: '?model=outsourcing_contract_NULL&action=pageItemJson',
			param: [{
				paramId: 'mainId',
				colId: 'id'
			}],
			colModel: [{
				name: 'XXX',
				display: '�ӱ��ֶ�'
			}]
		},

		toViewConfig: {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_contract_rentcar&action=viewTab&id=" + get[p.keyField],'1');
				}
			}
		},

		searchitems: [{
			display: "¼������",
			name: 'createTimeSea'
		},{
			display: "������ͬ���",
			name: 'orderCode'
		},{
			display: "��Ŀ����",
			name: 'projectName'
		},{
			display: "��Ŀ���",
			name: 'projectCode'
		},{
			display: "��ͬ����",
			name: 'orderName'
		},{
			display: "ǩԼ��˾",
			name: 'signCompany'
		},{
			display: "ǩԼ����",
			name: 'signDateSea'
		}]
	});
});