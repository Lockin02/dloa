var show_page = function(page) {
	$("#invpurchaseGrid").yxsubgrid("reload");
};
$(function() {
	$("#invpurchaseGrid").yxsubgrid({
		model: 'finance_invpurchase_invpurchase',
		title: '�ɹ���Ʊ',
        param : {'supplierId' : $("#supplierId").val()},
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
				hide: true
			},
			{
				name: 'objCode',
				display: '��Ʊ���',
				sortable: true,
				width : 160,
				process : function(v,row){
					if(row.formType == "blue"){
						return v;
					}else{
						return "<span class='red'>"+ v +"</span>";
					}
				}
			},
			{
				name: 'supplierName',
				display: '��Ӧ������',
				sortable: true,
				width : 170
			},
			{
				name: 'departments',
				display: '����',
				sortable: true
			},
			{
				name: 'salesman',
				display: 'ҵ��Ա',
				sortable: true
			},
			{
				name: 'objNo',
				display: '��Ʊ����',
				sortable: true,
				width : 130
			},
			{
				name: 'amount',
				display: '�ܽ��',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				}
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
			},
			{
				name: 'status',
				display: '״̬',
				sortable: true,
				datacode : 'CGFPZT',
				width : 60
			},{
				name : 'purcontCode',
				display : '�ɹ��������',
				width : 130
			},{
				name : 'createName',
				display : '������',
				width : 90,
				hide : true
			},
			{
				name: 'belongId',
				display: '����ԭ��Ʊid',
				process : function(v){
					if(v != ""){
						return "<span id='sBelongId"+ v + "'>" + v + "</span>";
					}
				},
				hide: true
			}
		],

		// ���ӱ������
		subGridOptions : {
			url : '?model=finance_invpurchase_invpurdetail&action=pageJson',// ��ȡ�ӱ�����url
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
					display : '���ϱ��'
				},{
					name : 'productName',
					display : '��������',
					width : 160
				},{
					name : 'productModel',
					display : '����ͺ�'
				},{
					name : 'unit',
					display : '��λ'
				}, {
				    name : 'number',
				    display : '����'
				},{
					name : 'price',
					display : '����',
					process : function(v){
						return moneyFormat2(v);
					}
				},{
				    name : 'amount',
				    display : '���',
					process : function(v){
						return moneyFormat2(v);
					}
				}
			]
		},
		menusEx : [
			{
				text: "�鿴",
				icon: 'view',
				action: function(row) {
					showThickboxWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id=' + row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 550 + "&width=" + 800);
				}
			}
		],
        sortname : 'updateTime'
	});
});