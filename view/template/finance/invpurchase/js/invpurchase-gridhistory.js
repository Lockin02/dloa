function getQueryStringPay(name) {
	var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)", "i");
	var r = window.location.search.substr(1).match(reg);
	if (r != null)
		return unescape(r[2]);
	return null;
}
var show_page = function(page) {
	$("#invpurchaseGrid").yxsubgrid("reload");
};
$(function() {
	var skey = "&skey=" + $("#skey").val();
	var gdbtable = getQueryStringPay('gdbtable');
	$("#invpurchaseGrid").yxsubgrid({
		model: 'finance_invpurchase_invpurchase',
		action : 'pageJsonHistory',
		title: '�ɹ���Ʊ -- �ɹ����� :' + $("#objCode").val(),
        param : {'dcontractId' : $("#objId").val(),"gdbtable" : gdbtable},
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		isAddAction : false,
		showcheckbox :false,
		//����Ϣ
		colModel: [{
				display: 'id',
				name: 'id',
				sortable: true,
				hide: true,
				process : function(v,row){
					return v + "<input type='hidden' id='isBreak"+ row.id+"' value='unde'>";
				}
			},
			{
				name: 'objCode',
				display: '���ݱ��',
				sortable: true,
				width : 130,
				process : function(v,row){
					if(row.formType == "blue"){
						return v;
					}else{
						return "<span class='red'>"+ v +"</span>";
					}
				}
			},
			{
				name: 'objNo',
				display: '��Ʊ����',
				sortable: true
			},
			{
				name: 'supplierName',
				display: '��Ӧ������',
				sortable: true,
				width : 150
			},
			{
				name: 'invType',
				display: '��Ʊ����',
				sortable: true,
				width : 80,
				datacode : 'FPLX'
			},
			{
				name: 'taxRate',
				display: '˰��(%)',
				sortable: true,
				width : 60
			},
			{
				name: 'formAssessment',
				display: '����˰��',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'amount',
				display: '�ܽ��',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'formCount',
				display: '��˰�ϼ�',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'formDate',
				display: '��������',
				sortable: true,
				width : 80
			},
			{
				name: 'payDate',
				display: '��������',
				sortable: true,
				width : 80
			},{
				name : 'purcontCode',
				display : '�ɹ��������',
				width : 130,
				hide : true
			},
			{
				name: 'departments',
				display: '����',
				sortable: true,
				width : 80
			},
			{
				name: 'salesman',
				display: 'ҵ��Ա',
				sortable: true,
				width : 80
			},
			{
				name: 'ExaStatus',
				display: '���״̬',
				sortable: true,
				width : 60,
				process : function(v){
					if(v == 1){
						return '�����';
					}else{
						return 'δ���';
					}
				}
			},
			{
				name: 'exaMan',
				display: '�����',
				sortable: true,
				width : 80
			},
			{
				name: 'ExaDT',
				display: '�������',
				sortable: true,
				width : 80
			},
			{
				name: 'status',
				display: '����״̬',
				sortable: true,
				width : 60,
				process : function(v){
					if(v == 1){
						return '�ѹ���';
					}else{
						return 'δ����';
					}
				}
			},{
				name : 'createName',
				display : '������',
				width : 90,
				hide : true
			},
			{
				name: 'belongId',
				display: '����ԭ��Ʊid',
				hide: true
			}
		],

		// ���ӱ������
		subGridOptions : {
			url : '?model=finance_invpurchase_invpurdetail&action=pageJson&gdbtable=' + gdbtable,// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [
				{
					paramId : 'invPurId',// ���ݸ���̨�Ĳ�������
					colId : 'id'// ��ȡ���������ݵ�������
				}
			],
			// ��ʾ����
			colModel : [{
					name : 'productNo',
					display : '���ϱ��',
					width : 80
				},{
					name : 'productName',
					display : '��������',
					width : 140
				},{
					name : 'productModel',
					display : '����ͺ�'
				},{
					name : 'unit',
					display : '��λ',
				    width : 60
				}, {
				    name : 'number',
				    display : '����',
				    width : 60
				},{
					name : 'price',
					display : '����',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
					name : 'taxPrice',
					display : '��˰����',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
				    name : 'assessment',
				    display : '˰��',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
				    name : 'amount',
				    display : '���',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
				    name : 'allCount',
				    display : '��˰�ϼ�',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
				    name : 'objCode',
				    display : '�������',
				    width : 120
				}
			]
		},
        buttonsEx : [
        	{
				text : '����������ʷ',
				icon : 'view',
				action : function(row) {
					location="?model=finance_payablesapply_payablesapply&action=toHistory"
						+ "&obj[objId]=" + $("#objId").val()
					    + "&obj[objCode]=" + $("#objCode").val()
					    + "&obj[objType]=" + $("#objType").val()
					    + "&obj[supplierId]=" + $("#supplierId").val()
					    + "&obj[supplierName]=" + $("#supplierName").val()
					    + "&gdbtable=" + gdbtable
					    + skey ;
				}
			},{
				text : '�����¼��ʷ',
				icon : 'view',
				action : function(row) {
					location="?model=finance_payables_payables&action=toHistory"
						+ "&obj[objId]=" + $("#objId").val()
					    + "&obj[objCode]=" + $("#objCode").val()
					    + "&obj[objType]=" + $("#objType").val()
					    + "&obj[supplierId]=" + $("#supplierId").val()
					    + "&obj[supplierName]=" + $("#supplierName").val()
					    + "&gdbtable=" + gdbtable
					    + skey ;
				}
			}
			,{
				text : '�ɹ���Ʊ��¼',
				icon : 'edit',
				action : function(row) {
					location="?model=finance_invpurchase_invpurchase&action=toHistory"
						+ "&obj[objId]=" + $("#objId").val()
					    + "&obj[objCode]=" + $("#objCode").val()
					    + "&obj[objType]=" + $("#objType").val()
					    + "&obj[supplierId]=" + $("#supplierId").val()
					    + "&obj[supplierName]=" + $("#supplierName").val()
					    + "&gdbtable=" + gdbtable
					    + skey ;
				}
			}
			,{
				text : '���ϼ�¼',
				icon : 'view',
				action : function(row) {
					location="?model=purchase_arrival_arrival&action=toListByOrder"
						+ "&obj[objId]=" + $("#objId").val()
					    + "&obj[objCode]=" + $("#objCode").val()
					    + "&obj[objType]=" + $("#objType").val()
					    + "&obj[supplierId]=" + $("#supplierId").val()
					    + "&obj[supplierName]=" + $("#supplierName").val()
					    + "&gdbtable=" + gdbtable
					    + skey ;
				}
			}
        ],
		menusEx : [
			{
				text: "�鿴",
				icon: 'view',
				action: function(row) {
					showThickboxWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id=' + row.id
						+ "&skey=" + row.skey_+ "&gdbtable=" + gdbtable
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 550 + "&width=" + 800);
				}
			}
		],
        sortname : 'updateTime'
	});
});