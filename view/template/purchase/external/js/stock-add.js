$(function() {
	// 单选合同
	$("#stockName").yxcombogrid_fillup({
				hiddenId : 'stockId',
				gridOptions : {
					showcheckbox : false,
					param:{"contStatus":"1"},
					event : {
						'row_dblclick' : function(e, row, data) {
							$.post("?model=purchase_external_stock&action=addList",{
								id:data.id,
								stockName:data.stockName
							},function(data){
								alert(data)
								$("#equList").html("");
								$("#equList").append(data);
							});
						},
						'row_click' : function() {
							// alert(123)
						},
						'row_rclick' : function() {
							// alert(222)
						}
					}
				}
			});
});