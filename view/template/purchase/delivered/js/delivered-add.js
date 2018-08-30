$(function() {
	 //选择退料仓库
	$("#stockName").yxcombogrid_stockinfo({
		//		width:400,
				hiddenId : 'stockId',
				nameCol:'stockName',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							//alert(data.Prov);
						},
						'row_click' : function(e,row,data) {
							// alert(123)
						},
						'row_rclick' : function() {
							// alert(222)
						}
					}
				}
			});
	$("#supplierName").yxcombogrid_supplier({
				hiddenId : 'supplierId',
				gridOptions : {
					showcheckbox : false,
					event : {
						'row_dblclick' : function(e, row, data) {
							//alert(data.Prov);
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
	$("#sourceCode").yxcombogrid_arrival({
				hiddenId : 'sourceId',
				gridOptions : {
					showcheckbox : false,
					param:{"state":"2"},
					event : {
						'row_dblclick' : function(e, row, data) {
							$("#stockName").val(data.stockName);
							$("#stockId").val(data.stockId);
							$("#supplierName").val(data.supplierName);
							$("#supplierId").val(data.supplierId);
							$("#deliveryPlace").val(data.deliveryPlace);
							$("#purchMode").val(data.purchMode_name);
							$.post(
									"?model=purchase_delivered_delivered&action=addItemList",
									{
										arrivalId : data.id
									}, function(data) {
										$("#invbody").html("");
										$("#invbody").append(data);
							});
							//alert(data.Prov);
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
	$("#purchManName").yxselect_user({
				hiddenId : 'purchManId'
			});

			$("#TO_NAME").yxselect_user({
						hiddenId : 'TO_ID',
						mode:'check'
					});

});

//添加退料时提交审批
function submitAudit(){
	document.getElementById('form1').action = "index1.php?model=purchase_delivered_delivered&action=toSubmitAudit&type=external";
}

function confirmAudit() {
		document.getElementById('form1').action = "index1.php?model=purchase_delivered_delivered&action=toSubmitAudit";
}
//修改退料时时提交审批
function submitAuditByEdit(){
	document.getElementById('form1').action = "index1.php?model=purchase_delivered_delivered&action=edit&type=aduit";
}