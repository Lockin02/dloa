//初始化单据
$(function(){
	//打印信息赋值
	$("#headNum").html($("#allNum").html());
	var idStr = $("#ids").val();
	var idArr = idStr.split(",");
	for(var i=0;i<idArr.length;i++){
		var id = idArr[i];
		var detailObj = $("#detail"+id);
		detailObj.yxeditgrid({
			url : "?model=finance_compensate_compensatedetail&action=listJson",
			param : {"mainId":id},
			tableClass : 'form_in_table',
			type : 'view',
			async : false,
			event : {
				'reloadData': function(e,g,data) {
					if(data.length > 0){
						detailObj.find('tbody').after("<tr class='tr_count'>" +
	                        "<td></td><td>合计</td><td colspan='4'></td>" +
	                        "<td style='text-align:right;'>" +
	                            moneyFormat2($("#formMoney"+id).val()) +
	                        "</td>" +
	                        "<td></td>" +
	                        "<td style='text-align:right;'>" +
	                            moneyFormat2($("#compensateMoney"+id).val()) +
	                        "</td><td colspan='2'></td>" +
	                        "</tr>");
					}
				}
			},
			colModel : [{
				display : 'id',
				name : 'id',
				type : 'hidden',
				isSubmit : true
			}, {
	            display : '物料Id',
	            name : 'productId',
	            type : 'hidden'
	        }, {
				display : '物料编号',
				name : 'productNo',
				width : 80
			}, {
				display : '物料名称',
				name : 'productName'
			}, {
				display : '规格型号',
				name : 'productModel'
			}, {
				display : '单位',
				name : 'unitName',
				width : 50
			}, {
				display : '数量',
				name : 'number',
				width : 70
			}, {
				display : '金额',
				name : 'money',
				width : 70,
				align : 'right',
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
	            display : '净值',
	            name : 'price',
	            width : 70,
	            align : 'right',
	            process : function(v){
	                return moneyFormat2(v);
	            }
	        }, {
				display : '赔偿金额',
				name : 'compensateMoney',
				width : 70,
				align : 'right',
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				display : '备注',
				name : 'remark',
	            width : 70,
				align : 'left'
			}, {
				display : '序列号',
				name : 'serialNos',
	            width : 70,
				align : 'left'
			}]
		});;
	}
});