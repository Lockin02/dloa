$(document).ready(function() {
	/*validate({
				"productName" : {
					required : true
				},
				"productCode" : {
					required : true
				}
			});
   */
   $.formValidator.initConfig({
						formid : "form1",
						// autotip: true,
						onerror : function(msg) {
							// alert(msg);
						}

					});
   $("#productName").formValidator({
						onshow : "请输入产品名称",
						onfocus : "产品名至少1个字符，最多300个字符",
						oncorrect : "您输入的产品名称有效"
					}).inputValidator({
						min : 1,
						max : 300,
						empty : {
							leftempty : false,
							rightempty : false,
							emptyerror : "产品名称两边不能为空"
						},
						onerror : "您输入的产品名称不合法，请重新输入"
					}).ajaxValidator({
						type : "get",
						url : "index1.php",
						data : "model=stockup_basic_products&action=ajaxProductName",
						datatype : "json",

						success : function(data) {

							if (data == "1") {
								return true;
							} else {
								return false;
							}
						},

						// buttons: $("#submitSave"),
						error : function() {

							alert("服务器没有返回数据，可能服务器忙，请重试");
						},
						onerror : "该产品名称不可用，请更换",
						onwait : "正在对产品名称进行合法性校验，请稍候..."
					});

//   $("#productCode").formValidator({
//						onshow : "请输入产品编号",
//						onfocus : "请输入产品编号",
//						oncorrect : "您输入的产品编号有效"
//					}).inputValidator({
//						min : 0,
//						max : 50,
//						empty : {
//							leftempty : true,
//							rightempty : true,
//							emptyerror : "产品编号两边不能为空"
//						},
//						onerror : "您输入的产品编号不合法，请重新输入"
//					}).ajaxValidator({
//						type : "get",
//						url : "index1.php",
//						data : "model=stockup_basic_products&action=ajaxProductCode",
//						datatype : "json",
//
//						success : function(data) {
//
//							if (data == "1") {
//								return true;
//							} else {
//								return false;
//							}
//						},
//
//						// buttons: $("#submitSave"),
//						error : function() {
//
//							alert("服务器没有返回数据，可能服务器忙，请重试");
//						},
//						onerror : "该产品编号不可用，请更换",
//						onwait : "正在对产品编号进行合法性校验，请稍候..."
//					});


   })