var show_page = function(page) {
	$("#stockbalanceGrid").yxgrid("reload");
};

$(function() {
    $("#stockbalanceGrid").yxgrid({
        model : 'finance_stockbalance_stockbalance',
        action : 'detailPageJson',
		title : '�����ϸ',
		param : {"thisYear":$("#thisYear").val(),"thisMonth":$("#thisMonth").val(),"stockId":$("#stockId").val(),"productId":$("#productId").val()},
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		isShowNum : false,
		usepager : false, // �Ƿ��ҳ
		//����Ϣ
		colModel : [{
				name : 'thisMonth',
				display : '��',
				sortable : true,
				width : 80
			}, {
				name : 'thisDate',
				display : '����',
				sortable : true,
				width : 80
			}, {
				name : 'stockName',
				display : '�ֿ�����',
				sortable : true
			}, {
				name : 'productNo',
				display : '���ϱ��',
				sortable : true
			}, {
				name : 'productName',
				display : '��������',
				sortable : true,
				width : 140
			}, {
				name : 'productModel',
				display : '����ͺ�',
				sortable : true
			}, {
				name : 'units',
				display : '��λ',
				sortable : true,
				width : 80
			}
//			, {
//				name : 'pricing',
//				display : '�Ƽ۷�ʽ',
//				sortable : true
//			}
			, {
				name : 'inNumber',
				display : '�������',
				sortable : true,
				width : 80
			}, {
				name : 'inAmount',
				display : '�����',
				sortable : true,
				process : function(v){
					if(v >= 0){
						return moneyFormat2(v);
					}else{
						return '<span class="red">' +moneyFormat2(v)+ '</span>';
					}
				}
			}, {
				name : 'outNumber',
				display : '��������',
				sortable : true,
				width : 80
			}, {
				name : 'outAmount',
				display : '������',
				sortable : true,
				process : function(v){
					if(v >= 0){
						return moneyFormat2(v);
					}else{
						return '<span class="red">' +moneyFormat2(v)+ '</span>';
					}
				}
			}
		],
		menusEx :[{
				text: "�鿴��",
				icon: 'view',
				showMenuFn : function(row){
					if(row.isDeal == 1){
						return true;
					}
					return false;
				},
				action: function(row) {
					showThickboxWin("?model=finance_costajust_costajust"
						+ "&action=initForStockBal&perm=view"
						+ "&stockbalId="
						+ row.id
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500"
						+ "&width=900");
				}
			}
		]
	});
});