$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        }
    });

/** �ʼĵ��� * */
$("#mailNo").formValidator({
	onshow : "�������ʼĵ���",
	onfocus : "�ʼĵ�������2���ַ������200���ַ�",
	oncorrect : "��������ʼĵ�����Ч"
}).inputValidator({
	min : 2,
	max : 200,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "�ʼĵ������߲����пշ���"
	},
	onerror : "��������ʼĵ��Ų��Ϸ�������������"
});

/** ������˾ * */
$("#logisticsName").formValidator({
	onshow : "������������˾",
	onfocus : "������˾����2���ַ������50���ַ�",
	oncorrect : "�������������˾��Ч"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "������˾���߲����пշ���"
	},
	onerror : "�������������˾���Ϸ�������������"
});

/** ������˾ * */
$("#customerName").formValidator({
	onshow : "��ѡ��ͻ�",
	onfocus : "�ͻ���������2���ַ������50���ַ�",
	oncorrect : "������Ŀͻ�������Ч"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "�ͻ��������߲����пշ���"
	},
	onerror : "������Ŀͻ����Ʋ��Ϸ�������������"
});


/** �ռ��� * */
$("#receiver").formValidator({
	onshow : "��ѡ���ռ���",
	onfocus : "��ѡ���ռ���",
	oncorrect : "��ѡ����ռ�����Ч"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : ""
	},
	onerror : "��ѡ����ռ��˲��Ϸ�������������"
});
/** ����� * */
$("#auditman").formValidator({
	onshow : "��ѡ�������",
	onfocus : "��ѡ�������",
	oncorrect : "��ѡ����������Ч"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : ""
	},
	onerror : "��ѡ�������˲��Ϸ�������������"
});
/** �ʼ����� * */
 $("#mailTime").formValidator({
	    onshow: "��ѡ���ʼ�����",
	    onfocus: "��ѡ������",
	    oncorrect: "����������ںϷ�"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
	});

});
