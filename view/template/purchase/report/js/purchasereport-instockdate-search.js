// ��Ա��Ⱦ
$(document).ready(function() {

			$("#productCode").yxcombogrid_product({
						hiddenId : 'productId',
						isShowButton : false,
						height : 200,
						width : 600,
						isShowButton : false,
						gridOptions : {
							showcheckbox : false,
							event : {
								row_dblclick : function(e, row, data) {
									$("#productName").val(data.productName);
								}
							}
						}
					});

		});

// ��ѯ
function confirm() {

	var productId = $("#productId").val();
	var productCode = $("#productCode").val();
	var productName = $("#productName").val();

	this.opener.location = "?model=purchase_report_purchasereport&action=toInstockDatePage"
			+ "&productId="
			+ productId
			+ "&productCode="
			+ productCode
			+ "&productName=" + productName
	this.close();
}

// ���
function toClear() {
	$(".toClear").val("");
}