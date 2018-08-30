var show_page = function(page) {
	$("#stockbalanceGrid").yxgrid("reload");
};

$(function() {
    $("#stockbalanceGrid").yxgrid({
        model : 'finance_stockbalance_stockbalance',
        action : 'detailPageJson',
		title : '余额明细',
		param : {"thisYear":$("#thisYear").val(),"thisMonth":$("#thisMonth").val(),"stockId":$("#stockId").val(),"productId":$("#productId").val()},
		isDelAction : false,
		isEditAction : false,
		isAddAction : false,
		isShowNum : false,
		usepager : false, // 是否分页
		//列信息
		colModel : [{
				name : 'thisMonth',
				display : '月',
				sortable : true,
				width : 80
			}, {
				name : 'thisDate',
				display : '日期',
				sortable : true,
				width : 80
			}, {
				name : 'stockName',
				display : '仓库名称',
				sortable : true
			}, {
				name : 'productNo',
				display : '物料编号',
				sortable : true
			}, {
				name : 'productName',
				display : '物料名称',
				sortable : true,
				width : 140
			}, {
				name : 'productModel',
				display : '规格型号',
				sortable : true
			}, {
				name : 'units',
				display : '单位',
				sortable : true,
				width : 80
			}
//			, {
//				name : 'pricing',
//				display : '计价方式',
//				sortable : true
//			}
			, {
				name : 'inNumber',
				display : '入库数量',
				sortable : true,
				width : 80
			}, {
				name : 'inAmount',
				display : '入库金额',
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
				display : '出库数量',
				sortable : true,
				width : 80
			}, {
				name : 'outAmount',
				display : '出库金额',
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
				text: "查看表单",
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