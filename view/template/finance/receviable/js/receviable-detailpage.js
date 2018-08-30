/** �����б�* */

var show_page = function(page) {
	$("#receviableGrid").yxgrid("reload");
};

$(function() {

	$("#receviableGrid").yxgrid({

		model : 'finance_receviable_receviable',
		action : 'detailPageJson',
		title : 'Ӧ���˿���ϸ -- �ͻ� : ' + $("#customerName").val() + " -- " + $("#year").val() + "��" + $("#beginMonth").val() + "����"  + $("#endMonth").val() + "��" ,
		param : { 'salesmanId' : $("#salesmanId").val(), 'deptIds' : $("#deptId").val() , "customerId" : $("#customerId").val() ,'beginMonth' : $("#beginMonth").val() ,'endMonth' : $("#endMonth").val() , 'year' : $("#year").val() },
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
					case 'invoice': return '���۷�Ʊ';break;
					case 'YFLX-DKD' : return '���';break;
					case 'YFLX-YFK' : return 'Ԥ�տ�';break;
					case 'YFLX-TKD' : return '�˿';break;
					default : return v;
				}
			},
			width : 80
		},
		{
			name: 'subjects',
			display: '������Ŀ',
			hide : true
		},
		{
			name: 'customerName',
			display: '�ͻ�����',
			width : 140
		},
		{
			name: 'deptName',
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
			name: 'trueReceive',
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
					location = "?model=finance_receviable_receviable&action=toDetailPage"
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
					case 'invoice': showOpenWin("?model=finance_invoice_invoice&action=init&perm=view&id=" + row.objId + "&skey=" + row['skey_']);break;
					case 'YFLX-TKD' : showOpenWin("?model=finance_income_income&action=toAllot&perm=view&id=" + row.objId + "&skey=" + row['skey_'] );break;
					case 'YFLX-YFK' : showOpenWin("?model=finance_income_income&action=toAllot&perm=view&id=" + row.objId + "&skey=" + row['skey_'] );break;
					case 'YFLX-DKD' : showOpenWin("?model=finance_income_income&action=toAllot&perm=view&id=" + row.objId + "&skey=" + row['skey_'] );break;
					default : return '';
				}
			}
		}],
		sortname : 'formDate',
		sortorder : 'ASC'
	});
});