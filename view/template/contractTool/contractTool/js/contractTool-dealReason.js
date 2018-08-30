var show_page = function(page) {
	$("#dealReasonGrid").yxgrid("reload");
};
$(function() {
	//��ʼ���Ҽ���ť����
	menusArr = [{
		text : '¼�볬��ԭ��',
		icon : 'add',
		showMenuFn : function(row) {
			if (row) {
				return true;
			}
			return false;
		},
		action : function(row) {
			showThickboxWin('?model=contract_contract_receiptplan&action=toUpdateDealReason&id='
					+ row.id
					+ "&skey="
					+ row['skey_']
					+ '&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=800');
		}
	}];
	$("#dealReasonGrid").yxgrid({
		model : 'contract_contract_receiptplan',
		action : 'dealPageJson',
		param : {
			'prinvipalId' : $("#userId").val()
		},

		title : '��ͬ��Ϣ',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		// ��չ�Ҽ��˵�
		menusEx : menusArr,
//		lockCol : ['flag', 'exeStatus', 'status2'],// ����������
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'contractCode',
			display : '��ͬ���',
			sortable : true,
			width : 180,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
						+ row.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</fo  nt>" + '</a>';
			}
		}, {
			name : 'paymentterm',
			display : '��������',
			sortable : true,
			width : 60
		}, {
			name : 'paymentPer',
			display : '����ٷֱ�',
			sortable : true,
			width : 80,
			hide : true
		}, {
			name : 'money',
			display : '�ƻ�������',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'Tday',
			display : '����T-��',
			sortable : true,
			width : 80
		}, {
			name : 'invoiceMoney',
			display : '��Ʊ���',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'incomMoney',
			display : '������',
			sortable : true,
			width : 100,
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			name : 'dealDay',
			display : '�տ������',
			sortable : true,
			width : 100
		}, {
			name : 'dealD',
			display : '��������',
			sortable : true,
			width : 100,
			process : function(v,row){
				var Nowdate = formatDate(new Date());
                var days = DateDiff(row.dealDay,Nowdate);
                if(row.dealDay){
                	return days;
                }

			}

		}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ���',
			name : 'contractCode'
		}],
		sortname : "id"
	});
});
