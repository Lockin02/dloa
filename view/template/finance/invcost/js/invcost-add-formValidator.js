$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        }

    });

/** ��֤��Ӧ������ * */
$("#supplierName").formValidator({
	onshow : "�����빩Ӧ������",
	onfocus : "��Ӧ����������2���ַ������50���ַ�",
	oncorrect : "�������������Ч"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "�������߲����пշ���"
	},
	onerror : "����������Ʋ��Ϸ�������������"
});

/** ��֤��Ʊ���� * */
$("#objNo").formValidator({
	onshow : "�����뷢Ʊ����",
	onfocus : "��Ʊ��������2���ַ������50���ַ�",
	oncorrect : "������ĺ�����Ч"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "�������߲���Ϊ��"
	},
	onerror : "������ĺ��벻�Ϸ�������������"
});

});
