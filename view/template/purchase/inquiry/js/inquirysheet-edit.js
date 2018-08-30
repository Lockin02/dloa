$(document).ready(function() {
	// 修改询价单时，获取供应商信息
	var parentId = $("#parentId").val();
	$.post("?model=purchase_inquiry_inquirysupp&action=getSupp", {
		parentId : parentId
	}, function(data) {
//		alert(data)
        var o = eval("(" + data + ")");
		for (i = 1; i < 4; i++) {
			if (o[i - 1]) {
				$("#supplierName" + i).val(o[i - 1].suppName);
				$("#supplierId" + i).val(o[i - 1].suppId);
				$("#products"+i).val(o[i-1].suppTel);
				var quotes=moneyFormat2(o[i-1].quote);
				$("#quote"+i).val(quotes);
				$("#suppId"+i).val(o[i-1].id);
			}
		}
	});

});


//在修改页面提交审批
function submitAudit(){
	document.getElementById('form1').action="?model=purchase_inquiry_inquirysheet&action=edit&act=audit";
//	location="index1.php?model=purchase_inquiry_inquirysheet&action=edit&act=audit";
}