/**
 * 
 */
// ²éÑ¯
function confirm() {
	var beginDate = $("#beginDate").val();
	var endDate = $("#endDate").val();
	var productId = $("#productId").val();

	this.opener.location = "?model=stock_report_stockreport&action=toProOutSub"
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
	this.close();
}

// Çå¿Õ
function toClear() {
	$("#beginDate").val("");
	$("#endDate").val("");
	$("#productId").val("");
	$("#productCode").val("");
	$("#productName").val("");
	$(".toClear").val("");
}
$(document).ready(function() {

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

		})