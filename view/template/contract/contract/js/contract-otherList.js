var show_page = function(page) {
	$("#otherlistGrid").yxgrid("reload");
};
$(function() {
	 buttonsArr = [{
		 text : "�����ļ�",
		 icon : 'add',
		 action : function(row) {
			 showThickboxWin("?model=contract_contract_aidhandle&action=chooseContract"
				 +'&handleType=YSWJ'
				 + "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=800")
		 }
	 },{
		text : "�����ϴ�",
		icon : 'add',
		action : function(row) {
			showThickboxWin("?model=contract_contract_aidhandle&action=chooseContract"
			        +'&handleType=FJSC'
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=800")
		}
	}, {
		text : "��������",
		icon : 'add',
		action : function(row) {
			showThickboxWin("?model=contract_contract_aidhandle&action=chooseContract"
			        +'&handleType=GZSQ'
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=800")
		}
	}],
//	var param = {
//		'states' : '1,2,3,4,5,6,7',
//		'isTemp' : '0'
//	}
	$("#otherlistGrid").yxgrid({
		model : 'contract_contract_aidhandle',
		title : '���������б�',
		action : 'PageJson',
//		param : param,

		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		isOpButton:false,

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
					width : 100
				}, {
					name : 'contractName',
					display : '��ͬ����',
					sortable : true,
					width : 100
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true,
					width : 200
				}, {
					name : 'prinvipalName',
					display : '��ͬ������',
					sortable : true,
					width : 80
				}, {
					name : 'areaPrincipal',
					display : '��������',
					sortable : true,
					width : 80
				}, {
					name : 'handleType',
					display : '��������',
					sortable : true,
					process : function(v) {
						if (v == 'FJSC') {
							return "�����ϴ�";
						} else if (v == 'GZSQ') {
							return "��������";
						}
					}
				}, {
					name : 'createName',
					display : '������',
					sortable : true,
					width : 80
				}, {
					name : 'createTime',
					display : '����ʱ��',
					sortable : true,
					width : 150
				}],

		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ���',
			name : 'contractCode'
		}, {
			display : '��ͬ����',
			name : 'contractName'
		}, {
			display : '�ͻ�����',
			name : 'customerName'
		}],
		buttonsEx : buttonsArr,
		sortname : "createTime"
	});
});