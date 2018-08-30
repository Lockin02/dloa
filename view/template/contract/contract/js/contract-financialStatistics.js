var show_page = function(page) {
	$("#DetailGrid").yxsubgrid("reload");
};
$(function() {
	$("#DetailGrid").yxgrid({
		model : 'contract_contract_contract',
		action : 'financialStatisticspageJson',
		title : '��������ͳ�Ʊ�',
		isDelAction : false,
		isToolBar : false, // �Ƿ���ʾ������
		showcheckbox : false,
		customCode : 'financialdetail',
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				},{
					name : 'contractCode',
					display : '��ͬ���',
					sortable : true,
					width : 100
				},{
					name : 'moneyType',
					display : '������',
					sortable : true,
					width : 80,
					process :��function(v,row){
					   if(v=="deductMoney"){
					       return "�ۿ���";
					   }else if(v=="financeconfirmMoney"){
					       return "����ȷ���ܳɱ�";
					   }else if(v=="serviceconfirmMoney"){
					       return "����ȷ������";
					   }
					}
				},{
					name : 'year',
					display : '���',
					sortable : true,
					width : 60
				}, {
					name : 'initMoney',
					display : '��ʼ�����',
					sortable : true,
					width : 60
				}, {
					name : 'January',
					display : 'һ�·�',
					sortable : true,
					width : 60
				}, {
					name : 'February',
					display : '���·�',
					sortable : true,
					width : 60
				}, {
					name : 'March',
					display : '���·�',
					sortable : true,
					width : 60
				}, {
					name : 'April',
					display : '���·�',
					sortable : true,
					width : 60
				}, {
					name : 'May',
					display : '���·�',
					sortable : true,
					width : 60
				}, {
					name : 'June',
					display : '���·�',
					sortable : true,
					width : 60
				}, {
					name : 'July',
					display : '���·�',
					width : 60
				}, {
					name : 'August',
					display : '���·�',
					width : 60
				}, {
					name : 'September',
					display : '���·�',
					sortable : true,
					width : 60
				}, {
					name : 'October',
					display : 'ʮ�·�',
					sortable : true,
					width : 60
				}, {
					name : 'November',
					display : 'ʮһ�·�',
					sortable : true,
					width : 60
				}, {
					name : 'December',
					display : 'ʮ���·�',
					sortable : true,
					width : 60
				}],
		comboEx : [{
			text : '�������',
			key : 'moneyType',
			data : [{
				text : '�ۿ���',
				value : 'deductMoney'
			}, {
				text : '����ȷ���ܳɱ�',
				value : 'financeconfirmMoney'
			}, {
				text : '����ȷ������',
				value : 'serviceconfirmMoney'
			}]
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ���',
			name : 'contractCode'
		}],
		sortname : "year",
		// ��������ҳ����
		toAddConfig : {
			formHeight : 500,
			formWidth : 900
		},
		// ���ñ༭ҳ����
		toEditConfig : {
			formHeight : 500,
			formWidth : 900
		},
		// ���ò鿴ҳ����
		toViewConfig : {
			formHeight : 500,
			formWidth : 900
		}

	});

});