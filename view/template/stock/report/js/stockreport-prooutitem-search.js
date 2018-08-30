/**
 * 
 */
// 查询
function confirm() {
	// var beginYear = $("#beginYear").val();
	// var beginMonth = $("#beginMonth").val();
	// var endYear = $("#endYear").val();
	// var endMonth = $("#endMonth").val();
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();
	var productId = $("#productId").val();
	var customerId = $("#customerId").val();
	var contractId = $("#contractId").val();

	this.opener.location = "?model=stock_report_stockreport&action=toProOutItem"
			+ "&beginDate="
			+ document.getElementById("beginDate").value
			+ "&endDate="
			+ document.getElementById("endDate").value
			+ "&productId="
			+ document.getElementById("productId").value
			+ "&productCode="
			+ document.getElementById("productCode").value
			+ "&productName="
			+ document.getElementById("productName").value
			+ "&customerId="
			+ document.getElementById("customerId").value
			+ "&customerName="
			+ document.getElementById("customerName").value
			+ "&contractId="
			+ document.getElementById("contractId").value
			+ "&contractName="
			+ document.getElementById("contractName").value
			+ "&contractCode=" + document.getElementById("contractCode").value
	this.close();
}

// 清空
function toClear() {
	$("#beginDate").val("");
	$("#endDate").val("");
	$("#productId").val("");
	$("#productCode").val("");
	$("#productName").val("");
	$("#customerId").val("");
	$("#customerName").val("");
	$("#contractId").val("");
	$("#contractName").val("");
	$("#contractCode").val("");
	$(".toClear").val("");
}
$(document).ready(function() {
			$("#customerName").yxcombogrid_customer({
						hiddenId : 'customerId',
						isShowButton : false,
						height : 200,
						width : 600,
						gridOptions : {
							showcheckbox : false,
							event : {
								row_dblclick : function(e, row, data) {
									// $("#suppCode").val(data.busiCode);
								}
							}
						}
					});

			$("#productName").yxcombogrid_product({
						hiddenId : 'productId',
						nameCol : 'productName',
						isShowButton : false,
						height : 200,
						width : 600,
						gridOptions : {
							showcheckbox : false,
							event : {
								row_dblclick : function(e, row, data) {
									$("#productCode").val(data.productCode);
								}
							}
						}
					});
			$("#productCode").yxcombogrid_product({
						hiddenId : 'productId',
						nameCol : 'productCode',
						isShowButton : false,
						height : 200,
						width : 600,
						gridOptions : {
							showcheckbox : false,
							event : {
								row_dblclick : function(e, row, data) {
									$("#productName").val(data.productName);
								}
							}
						}
					});
			$("#contractCode").yxcombogrid_allorder({
						hiddenId : 'contractId',
						// nameCol : 'orderCode',
						valueCol : 'id',
						isDown : false,
						gridOptions : {
							param : {
								ExaStatus : '完成'
							},
							showcheckbox : false,
							event : {
								'row_dblclick' : function(e, row, data) {
									$("#contractName").val(data.orderName);
									if (data.orderCode == "") {
										$("#contractCode")
												.val(data.orderTempCode);
									} else {
										$("#contractCode").val(data.orderCode);

									}
								}
							}
						}
					});

		})