$(function() {
	setSelect('beginYear');
	setSelect('endYear');
	setSelect('beginMonth');
	setSelect('endMonth');
	// 客户
	$("#productCode").yxcombogrid_product({
		hiddenId : 'productId',
		height : 300,
		isShowButton : false,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#productName").val(data.productName);
				}
			}
		}
	});

	// 物料名称
	$("#productName").yxcombogrid_product({
		hiddenId : 'productId',
		nameCol : 'productName',
		searchName : '',
		isFocusoutCheck : false,
		height : 300,
		width : 600,
		gridOptions : {
			showcheckbox : false,
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#productCode").val(data.productCode);
				}
			}
		}
	});
});

function confirm() {
	var beginYear = $("#beginYear").val();
	var beginMonth = $("#beginMonth").val();
	var endYear = $("#endYear").val();
	var endMonth = $("#endMonth").val();
	var productId = $("#productId").val();
	var productCode = $("#productCode").val();
	var productName = $("#productName").val();

	this.opener.location = "?model=stock_report_stockreport&action=toAllocationItem&beginMonth="
			+ beginMonth
			+ "&beginYear="
			+ beginYear
			+ "&endMonth="
			+ endMonth
			+ "&endYear="
			+ endYear
			+ "&productId="
			+ productId
			+ "&productCode=" + productCode + "&productName=" + productName;
	this.close();
}

function refresh() {
	$(".clear").val("");
}