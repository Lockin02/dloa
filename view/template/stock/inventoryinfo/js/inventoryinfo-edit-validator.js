$(document).ready(function() {
	// $.formValidator.initConfig({
	// formid : "form1",
	// onerror : function(msg) {
	// }
	// });
	//
	// /** 仓库代码 * */
	// $("#stockId").formValidator({
	// onshow : "请选择仓库代码",
	// onfocus : "仓库代码不能为空",
	// oncorrect : "仓库代码有效"
	// }).inputValidator({
	// min : 1,
	// max : 50,
	// onerror : "仓库代码不能为空，请选择"
	// });
	// /** 物料代码 * */
	// $("#productId").formValidator({
	// onshow : "请选择物料代码",
	// onfocus : "物料代码不能为空",
	// oncorrect : "物料代码有效"
	// }).inputValidator({
	// min : 1,
	// max : 50,
	// onerror : "物料代码不能为空，请选择"
	// }).ajaxValidator({
	// type : "get",
	// url : "index1.php",
	// data :
	// "model=stock_inventoryinfo_inventoryinfo&action=checkProduct&id="+$("#id").val()+"&stockId="+$("#stockId").val(),
	// datatype : "json",
	// success : function(data) {
	// if (data == "1") {
	// return true;
	// } else {
	// return false;
	// }
	// },
	// buttons : $("#submitSave"),
	// error : function() {
	// alert("服务器没有返回数据，可能服务器忙，请重试");
	// },
	// onerror : "你所选仓库的这种物料已经初始化，请重新选择",
	// onwait : "正在对物料编号进行合法性校验，请稍候..."
	// });

	// 设置期初库存时 成本价*期初数量=金额 ;可执行库存==即时库存==期初数量
	$("#initialNum").change(function() {
				if ($("#price").val() != "") {
					$("#sumAmount").val($(this).val() * $("#price").val());
				}
				$("#actNum").val($(this).val());
				$("#exeNum").val($(this).val());

			})
		// $("#price").change(function() {
		// if ($("#initialNum").val() != "") {
		// $("#sumAmount").val($(this).val() * $("#initialNum").val());
		// }
		//
		// })
	});

function checkForm() {
	if ($("#stockId").val() == "") {
		alert("仓库不能为空!");
		return false;
	}
	if ($("#productId").val() == "") {
		alert("物料不能为空!");
		return false;
	}

	var resultBack = true;

	$.ajax({
				type : "GET",
				async : false,
				url : "?model=stock_inventoryinfo_inventoryinfo&action=checkProduct",
				data : {
					stockId : parseInt($("#stockId").val()),
					productId : $("#productId").val(),
					id : $("#id").val()
				},
				success : function(result) {
					if (result == 0) {
						alert("你所选仓库的这种物料已经初始化，请重新选择！")
						resultBack = false;
					}
				}
			})

	return resultBack;
}
