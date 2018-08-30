/** �����б�* */

var show_page = function(page) {
	$("#payableGrid").yxgrid("reload");
};

$(function() {

	$("#payableGrid").yxgrid({

		model : 'finance_payable_payable',
		action : 'detailPageJson',
		title : 'Ӧ������ϸ�� -- ��Ӧ�� : ' + $("#supplierName").val() + " -- " + $("#year").val() + "��" + $("#beginMonth").val() + "����"  + $("#endMonth").val() + "��" ,
		param : { 'salesmanId' : $("#salesmanId").val(), 'deptIds' : $("#deptId").val() , "supplierId" : $("#supplierId").val() ,'beginMonth' : $("#beginMonth").val() ,'endMonth' : $("#endMonth").val() , 'year' : $("#year").val() },
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
			hide: true
		},{
			display: '��id',
			name: 'objId',
			hide: true
		},
		{
			name: 'formDate',
			display: '��������'
		},
		{
			name: 'objCode',
			display: '���ݱ��',
			width : 150
		},
		{
			name: 'formType',
			display: '��������',
			process : function(v){
				switch(v){
					case 'blue': return '���ַ�Ʊ';break;
					case 'red' : return '<span class="red">���ַ�Ʊ</span>';break;
					case 'CWYF-01' : return '���';break;
					case 'CWYF-02' : return 'Ԥ���';break;
					case 'CWYF-03' : return '<span class="red">�˿</span>';break;
					default : return '';
				}
			}
		},
		{
			name: 'subjects',
			display: '������Ŀ'
		},
		{
			name: 'supplierName',
			display: '��Ӧ������',
			width : 140
		},
		{
			name: 'departments',
			display: '����'
		},
		{
			name: 'salesman',
			display: 'ҵ��Ա'
		},
		{
			name: 'amount',
			display: '����Ӧ��',
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			}
		},
		{
			name: 'needPay',
			display: '����ʵ��',
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			}
		},
		{
			name: 'balance',
			display: '��ĩ���',
			process : function(v){
				if(v >= 0 ){
					return moneyFormat2(v);
				}else{
					return "<span class='red'>" + moneyFormat2(v) + "</span>";
				}
			}
		}],
		buttonsEx : [
			{
				text : '����',
				icon : 'view',
				action : function(row, rows, grid) {
					location = "?model=finance_payable_payable&action=toDetailPage"
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
					case 'blue': showOpenWin("?model=finance_invpurchase_invpurchase&action=init&perm=view&id=" + row.objId + "&skey=" + row.skey_ );break;
					case 'red' : showOpenWin("?model=finance_invpurchase_invpurchase&action=init&perm=view&id=" + row.objId  + "&skey=" + row.skey_ );break;
					case 'CWYF-01' : showOpenWin("?model=finance_payables_payables&action=initWin&perm=view&id=" + row.objId  + "&skey=" + row.skey_ );break;
					case 'CWYF-02' : showOpenWin("?model=finance_payables_payables&action=initWin&perm=view&id=" + row.objId  + "&skey=" + row.skey_ );break;
					case 'CWYF-03' : showOpenWin("?model=finance_payables_payables&action=initWin&perm=view&id=" + row.objId  + "&skey=" + row.skey_ );break;
					default : return '';
				}
			}
		}],
		sortname : 'formDate',
		sortorder : 'ASC'
	});
});