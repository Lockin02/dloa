$(document).ready(function() {
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

 })