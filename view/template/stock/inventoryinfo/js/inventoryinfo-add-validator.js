$(document).ready(function() {
			// $.formValidator.initConfig({
			// formid : "form1",
			// onerror : function(msg) {
			// }
			// });

			// /** �ֿ���� * */
			// $("#stockId").formValidator({
			// onshow : "��ѡ��ֿ����",
			// onfocus : "�ֿ���벻��Ϊ��",
			// oncorrect : "�ֿ������Ч"
			// }).inputValidator({
			// min : 1,
			// max : 50,
			// onerror : "�ֿ���벻��Ϊ�գ���ѡ��"
			// });
			//
			// // ��֤�����ظ���
			// // checkProduct();
			//
			// /** ���ϴ��� * */
			// $("#productId").formValidator({
			// onshow : "��ѡ�����ϴ���",
			// onfocus : "���ϴ��벻��Ϊ��",
			// oncorrect : "���ϴ�����Ч"
			// }).ajaxValidator({
			// type : "get",
			// url : "index1.php",
			// data :
			// "model=stock_inventoryinfo_inventoryinfo&action=checkProduct&stockId="
			// + $("#stockId").val(),
			// datatype : "json",
			// success : function(data) {
			// if (data == "1") {
			// return true;
			// } else {
			// return false;
			// }
			// },
			// buttons : $(".txt_btn_a"),
			// error : function() {
			// alert("������û�з������ݣ����ܷ�����æ��������");
			// },
			// onerror : "����ѡ�ֿ�����������Ѿ����ڣ�������ѡ��",
			// onwait : "���ڶ����ϱ�Ž��кϷ���У�飬���Ժ�..."
			// });

			// �����ڳ����ʱ �ɱ���*�ڳ�����=��� ;��ִ�п��==��ʱ���==�ڳ�����
			$("#initialNum").change(function() {
						if ($("#price").val() != "") {
							$("#sumAmount").val($(this).val()
									* $("#price").val());
						}
						$("#actNum").val($(this).val());
						$("#exeNum").val($(this).val());

					})
		});

function checkProduct() {/* У�������ظ��� */
	// if ($("#stockId").val() != "") {
	// /** ���ϴ��� * */
	// $("#productId").trigger('blur');
	// $("#productId").formValidator({
	// onshow : "��ѡ������",
	// onfocus : "���ϱ����Ч",
	// oncorrect : "���ϱ����Ч"
	// }).ajaxValidator({
	// type : "get",
	// url : "index1.php",
	// data :
	// "model=stock_inventoryinfo_inventoryinfo&action=checkProduct&stockId="
	// + $("#stockId").val(),
	// datatype : "json",
	// success : function(data) {
	// if (data == "1") {
	// return true;
	// } else {
	// return false;
	// }
	// },
	// buttons : $(".txt_btn_a"),
	// error : function() {
	// alert("������û�з������ݣ����ܷ�����æ��������");
	// },
	// onerror : "����ѡ�ֿ�����������Ѿ���ʼ����������ѡ��",
	// onwait : "���ڶ����ϱ�Ž��кϷ���У�飬���Ժ�..."
	// });
	// }
}

function checkForm() {
	if ($("#stockId").val() == "") {
		alert("�ֿⲻ��Ϊ��!");
		return false;
	}
	if ($("#productId").val() == "") {
		alert("���ϲ���Ϊ��!");
		return false;
	}

	var resultBack = true;

	$.ajax({
				type : "GET",
				async : false,
				url : "?model=stock_inventoryinfo_inventoryinfo&action=checkProduct",
				data : {
					stockId : parseInt($("#stockId").val()),
					productId : $("#productId").val()
				},
				success : function(result) {
					if (result == 0) {
						alert("����ѡ�ֿ�����������Ѿ���ʼ����������ѡ��")
						resultBack = false;
					}
				}
			})

	return resultBack;
}
