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
						onshow : "�������Ʒ����",
						onfocus : "��Ʒ������1���ַ������300���ַ�",
						oncorrect : "������Ĳ�Ʒ������Ч"
					}).inputValidator({
						min : 1,
						max : 300,
						empty : {
							leftempty : false,
							rightempty : false,
							emptyerror : "��Ʒ�������߲���Ϊ��"
						},
						onerror : "������Ĳ�Ʒ���Ʋ��Ϸ�������������"
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

							alert("������û�з������ݣ����ܷ�����æ��������");
						},
						onerror : "�ò�Ʒ���Ʋ����ã������",
						onwait : "���ڶԲ�Ʒ���ƽ��кϷ���У�飬���Ժ�..."
					});

//   $("#productCode").formValidator({
//						onshow : "�������Ʒ���",
//						onfocus : "�������Ʒ���",
//						oncorrect : "������Ĳ�Ʒ�����Ч"
//					}).inputValidator({
//						min : 0,
//						max : 50,
//						empty : {
//							leftempty : true,
//							rightempty : true,
//							emptyerror : "��Ʒ������߲���Ϊ��"
//						},
//						onerror : "������Ĳ�Ʒ��Ų��Ϸ�������������"
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
//							alert("������û�з������ݣ����ܷ�����æ��������");
//						},
//						onerror : "�ò�Ʒ��Ų����ã������",
//						onwait : "���ڶԲ�Ʒ��Ž��кϷ���У�飬���Ժ�..."
//					});


   })