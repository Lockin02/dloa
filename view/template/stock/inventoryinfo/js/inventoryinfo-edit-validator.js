$(document).ready(function() {
	// $.formValidator.initConfig({
	// formid : "form1",
	// onerror : function(msg) {
	// }
	// });
	//
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
	// /** ���ϴ��� * */
	// $("#productId").formValidator({
	// onshow : "��ѡ�����ϴ���",
	// onfocus : "���ϴ��벻��Ϊ��",
	// oncorrect : "���ϴ�����Ч"
	// }).inputValidator({
	// min : 1,
	// max : 50,
	// onerror : "���ϴ��벻��Ϊ�գ���ѡ��"
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
	// alert("������û�з������ݣ����ܷ�����æ��������");
	// },
	// onerror : "����ѡ�ֿ�����������Ѿ���ʼ����������ѡ��",
	// onwait : "���ڶ����ϱ�Ž��кϷ���У�飬���Ժ�..."
	// });

	// �����ڳ����ʱ �ɱ���*�ڳ�����=��� ;��ִ�п��==��ʱ���==�ڳ�����
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
					productId : $("#productId").val(),
					id : $("#id").val()
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
