var show_page = function(page) {
	$("#borrowequGrid").yxgrid("reload");
};
$(function (){
	 var customerId = $("#customerId").val();
	 var salesNameId = $("#salesNameId").val();
	 var borrowId = $("#borrowId").val();
	 if(borrowId == ''){
	    var paramArr = { 'salesNameId' : salesNameId }
	 }else{
	    var paramArr = { 'customerId' : customerId , 'borrowId' : borrowId }
	 }

   $("#borrowequGrid").yxgrid({
		model : 'projectmanagent_borrow_borrow',
		action : 'borrowequJson',
		param : paramArr,
		height : 500,
		isAllPageCheckedId : true,//缓存所有分页的选中数据
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isViewAction : false,
		isOpButton:false,
		// 列信息
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'productName',
					display : '物料名称',
					sortable : true,
					width : 200
				}, {
					name : 'productId',
					display : '物料ID',
					sortable : true,
					hide : true
				}, {
					name : 'productNo',
					display : '物料编码',
					sortable : true
				}, {
					name : 'productModel',
					display : '规格/型号',
					sortable : true,
					width : 80
				}, {
					name : 'warrantyPeriod',
					display : '保修期',
					sortable : true,
					width : 50
				}, {
					name : 'number',
					display : '数量',
					sortable : true,
					width : 50
				}, {
					name : 'executedNum',
					display : '已执行数量',
					sortable : true,
					width : 50
				}, {
					name : 'backNum',
					display : '归还数量',
					sortable : true,
					width : 50
				}
//				, {
//					name : 'toContractNum',
//					display : '转销售数量',
//					sortable : true,
//					width : 50,
//					process : function(v){
//					   if(v == ''){
//					      return 0;
//					   }else{
//					      return v;
//					   }
//					}
//				}
				, {
					name : 'price',
					display : '单价',
					sortable : true
				}, {
					name : 'money',
					display : '金额',
					sortable : true
				}, {
					name : 'customerName',
					display : '客户名称',
					sortable : true
				}, {
					name : 'customerId',
					display : '客户ID',
					sortable : true,
					hide : true
				}, {
					name : 'salesName',
					display : '销售负责人',
					sortable : true
				}, {
					name : 'salesNameId',
					display : '销售负责人ID',
					sortable : true,
					hide : true
				}, {
					name : 'borrowId',
					display : '借试用源单ID',
					sortable : true,
					hide : true
				}],
		buttonsEx : [{
			name : 'Add',
			text : "确认",
			icon : 'add',
			action : function(rowData, rows, rowIds, g) {
					if (rows) {
						showThickboxWin('?model=projectmanagent_borrow_borrow&action=tochooseCon&ids='
						    +rowIds
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=200&width=400");
//						parent.window.returnValue = rows;
						// $.showDump(outJson);
//						parent.window.close();
					} else {
						alert('请先选择记录');
					}
			}
		},{
			name : 'Add',
			text : "全选",
			icon : 'add',
			action : function(rowData, rows, rowIds, g) {
				var ids = $("#borrowequIds").val();
				if(ids != ''){
					var idArr = ids.split(",");
					g.options.checkedIdArr = idArr;
					g.reload();
				}else{
					alert("没有数据被选中，请刷新页面重试");
				}
			}
		}],
//		event : {
//			'row_dblclick' : function(e, row, rowData) {
//				if (rowData) {
//					// 输出json
//					outJson = {
//						"id" : rowData.id,
//						"prodcutId" : rowData.productId,
//						"productCode" : rowData.prodcutNo,
//						"productName" : rowData.productName,
//						"productModel" : rowData.productModel,
//						"number" : rowData.number,
//						"price" : rowData.price,
//						"money" : rowData.money,
//						"warrantyPeriod" : rowData.warrantyPeriod,
//						"isBorrowToorder" : 1,
//						"toBorrowId" : rowData.borrowId,
//						"toBorrowequId" : rowData.id
//					};
//					parent.window.returnValue = outJson;
//
//					// $.showDump(outJson);
//					parent.window.close();
//				} else {
//					alert('请先选择记录');
//				}
//			}
//		},
		toViewConfig : {
			action : 'toView'
		}
	});
   //渲染借试用转销售数据id串
   getBorrowequIds(paramArr);
});

//借试用转销售获取数据id串
function getBorrowequIds(paramArr){
	$.ajax({
        type: 'POST',
        url: "?model=projectmanagent_borrow_borrow&action=borrowequIds",
        data: paramArr,
        async: false,
        success: function (data) {
        	$("#borrowequIds").val(data);
        }
    });
}
