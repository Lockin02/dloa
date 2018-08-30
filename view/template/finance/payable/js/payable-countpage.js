/** �����б�* */

var show_page = function(page) {
	$("#payableGrid").yxgrid("reload");
};

$(function() {

	$("#payableGrid").yxgrid({

		model : 'finance_payable_payable',
		action : 'countPageJson',
		title : 'Ӧ������ܱ� -- ��Ӧ�� : ' + $("#supplierName").val() + " -- " + $("#year").val() + "��" + $("#beginMonth").val() + "����"  + $("#endMonth").val() + "��" ,
		param : {  "supplierId" : $("#supplierId").val() ,'beginMonth' : $("#beginMonth").val() ,'endMonth' : $("#endMonth").val() , 'year' : $("#year").val() },
		isToolBar : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isShowNum : false,
		usepager : false, // �Ƿ��ҳ

		colModel : [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		},
		{
			name: 'supplierName',
			display: '��Ӧ������',
			sortable: true,
			width : 160
		},
		{
			name: 'period',
			display: '�ڼ�',
			sortable: true
		},
		{
			name: 'periodBeginBalance',
			display: '�ڳ����',
			sortable: true,
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width : 110
		},
		{
			name: 'amount',
			display: '����Ӧ��',
			sortable: true,
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width : 110
		},
		{
			name: 'needPay',
			display: '����ʵ��',
			sortable: true,
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width : 110
		},
		{
			name: 'yearNeedPay',
			display: '�����ۼ�Ӧ��',
			sortable: true,
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width : 110
		},
		{
			name: 'yearPayed',
			display: '�����ۼ�ʵ��',
			sortable: true,
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width : 110
		},
		{
			name: 'periodEndBalance',
			display: '��ĩ���',
			sortable: true,
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			},
			width : 110
		}],
		buttonsEx : [
			{
				text : '����',
				icon : 'view',
				action : function(row, rows, grid) {
					location = "?model=finance_payable_payable&action=toCountPage"
				}
			}

		],

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴��ϸ',
			icon : 'view',showMenuFn:function(row){
		         if(row.objId != undefined ){
		            return true;
		         }
		         return false;
		    },
			action : function(row, rows, grid) {
				switch(row.formType){
					case 'blue': showOpenWin("?model=finance_invpurchase_invpurchase&action=init&perm=view&id=" + row.objId );break;
					case 'red' : showOpenWin("?model=finance_invpurchase_invpurchase&action=init&perm=view&id=" + row.objId );break;
					case 'CWYF-01' : showOpenWin("?model=finance_payables_payables&action=initWin&perm=view&id=" + row.objId );break;
					case 'CWYF-02' : showOpenWin("?model=finance_payables_payables&action=initWin&perm=view&id=" + row.objId );break;
					case 'CWYF-03' : showOpenWin("?model=finance_payables_payables&action=initWin&perm=view&id=" + row.objId );break;
					default : return '';
				}
			}
		}],
		sortname : 'formDate',
		sortorder : 'ASC'
	});
});