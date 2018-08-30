/** �����б�* */

var show_page = function(page) {
	$("#payableGrid").yxgrid("reload");
};

$(function() {

	$("#payableGrid").yxgrid({

		model : 'finance_payable_payable',
		title : '���е�Ӧ���˿�',
		isToolBar : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,

		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '�ɹ���ͬ/���뵥��',
					name : 'applyNumb',
					sortable : true,
					width : 200
				}, {
					display : '��Ӧ��',
					name : 'suppName',
					width : 200
				}, {
					display : '��ͬ������',
					name : 'createName'
				}, {
					display : '��ͬ���',
					name : 'applyMoney',
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					display : '�踶����',
					name : 'invoiceMoney',
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					display : '�Ѹ�����',
					name : 'payMoney',
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					display : 'δ������',
					name : 'remainMoney',
					process : function(v) {
						return moneyFormat2(v);
					}
				}, {
					display : '�Ѹ����ռ��ͬ�ٷֱ�',
					name : 'perMoney',
					width : 120 ,
					process : function(v) {
						return v + ' %' ;
					}
				}],

		searchitems : [{
					display : '�ɹ���ͬ/���뵥��',
					name : 'applyNumb'
				}],
		sortname : 'id',

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴��ϸ',
			icon : 'view',
			action : function(row, rows, grid) {
				if( row ){
					location = "?model=finance_payapply_payapply&action=payableDetail&applyNumb=" + row.applyNumb;
				}
			}

		}]
	});
});