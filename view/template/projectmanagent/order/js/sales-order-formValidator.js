$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        }

    });

 //��֤������ͬ��
$("#contNumber").formValidator({
		onshow : "�������ͬ��",
		onfocus : "�������2���ַ������50���ַ�",
		oncorrect : "������ĺ�ͬ����Ч"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "��ͬ�����߲���Ϊ��"
		},
		onerror : "������ĺ�ͬ�Ų��Ϸ�������������"
	}).ajaxValidator({
        type: "get",
        url: "index1.php",
        data: "model=contract_sales_sales&action=ajaxContNumber",
        datatype: "json",

        success: function(data) {

            if (data == "1") {
                return true;
            } else {
                return false;
            }
        },
//        buttons: $("#submitSave"),
        error: function() {

            alert("������û�з������ݣ����ܷ�����æ��������");
        },
        onerror: "�����Ʋ����ã������",
        onwait: "���ڶ���Ŀ���ƽ��кϷ���У�飬���Ժ�..."
    })


});