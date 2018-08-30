$(function() {
	// 选择入库仓库
	$("#stockName").yxcombogrid_stockinfo({
				hiddenId : 'stockId',
				nameCol : 'stockName',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							// alert(data.Prov);
						}
					}
				}
			});
	$("#purchManName").yxselect_user({
				hiddenId : 'purchManId'
			});
	// $("#supplierName").yxcombogrid_supplier({
	// hiddenId : 'supplierId',
	// gridOptions : {
	// showcheckbox : false,
	// event : {
	// 'row_dblclick' : function(e, row, data) {
	// //alert(data.Prov);
	// }
	// }
	// }
	// });

	$("#purchaseCode").yxcombogrid_purchcontract({
		hiddenId : 'purchaseId',
		gridOptions : {
			showcheckbox : false,
			param : {
				"stateArr" : "5,6,7"
			},
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#supplierName").val(data.suppName);
					$("#supplierId").val(data.suppId);
					$
							.post(
									"?model=purchase_arrival_arrival&action=itemListByAdd",
									{
										contractId : data.id,
										storageType : "ARRIVALTYPE1"
									}, function(data) {
										$("#invbody").html("");
										$("#invbody").append(data);
									});
					$
							.post(
									"?model=purchase_arrival_arrival&action=getApplyUser",
									{
										contractId : data.id,
										storageType : "ARRIVALTYPE1"
									}, function(datas) {
										var o = eval("(" + datas + ")");
										$("#TO_ID").val(o[1]);
										$("#TO_NAME").val(o[0]);

									});
				}
			}
		}
	});
});
//收料数量录入检测
function checkSize(number,obj){
	var num = parseInt(number);
	var input = parseInt($(obj).val());
	if(input>num){
		alert("输入数量过多，请重新输入！");
		obj.value=num;
		$(obj).focus();
	}
}