$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        }

    });

/** ��֤��Ӧ������ * */
$("#pickingCode").formValidator({
	onshow : "�������������뵥��",
	onfocus : "�������뵥������2���ַ������50���ַ�",
	oncorrect : "������ĺ�����Ч"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "�������߲����пշ���"
	},
	onerror : "������ĺ��벻�Ϸ�������������"
});

/** ��֤��Ʊ���� * */
$("#pickingType").formValidator({
	onshow : "��������������",
	onfocus : "������������2���ַ������50���ַ�",
	oncorrect : "�������������Ч"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "�������߲���Ϊ��"
	},
	onerror : "����������Ͳ��Ϸ�������������"
});


///** ��֤�������� * */
// $("#payDate").formValidator({
//	    onshow: "��ѡ�񸶿�����",
//	    onfocus: "��ѡ������",
//	    oncorrect: "����������ںϷ�"
//	}).inputValidator({
//	    min: "1900-01-01",
//	    max: "2100-01-01",
//	    type: "date",
//	    onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
//	});

});
