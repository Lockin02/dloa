$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        }
    });

/** �ͻ����� * */
if(!($('#docType').val()=='oa_borrow_borrow' && $('#customerName').val()=='')){
	$("#customerName").formValidator({
		onshow : "������ͻ�����",
		onfocus : "��������2���ַ������50���ַ�",
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
}

/** ��ϵ�� * */
$("#linkman").formValidator({
	onshow : "��������ϵ��",
	onfocus : "��ϵ������2���ַ������50���ַ�",
	oncorrect : "���������ϵ����Ч"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : "��ϵ�����߲����пշ���"
	},
	onerror : "���������ϵ�˲��Ϸ�������������"
});

///** ������˾ * */
//$("#companyName").formValidator({
//	onshow : "������������˾",
//	onfocus : "������˾����2���ַ������50���ַ�",
//	oncorrect : "�������������˾��Ч"
//}).inputValidator({
//	min : 2,
//	max : 50,
//	empty : {
//		leftempty : false,
//		rightempty : false,
//		emptyerror : "������˾���߲����пշ���"
//	},
//	onerror : "�������������˾���Ϸ�������������"
//});


/** ������ * */
$("#shipman").formValidator({
	onshow : "��ѡ�񷢻���",
	onfocus : "��ѡ�񷢻���",
	oncorrect : "��ѡ��ķ�������Ч"
}).inputValidator({
	min : 2,
	max : 50,
	empty : {
		leftempty : false,
		rightempty : false,
		emptyerror : ""
	},
	onerror : "��ѡ��ķ����˲��Ϸ�������������"
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
/** ��֤�������� * */
 $("#shipDate").formValidator({
	    onshow: "��ѡ�񷢻�����",
	    onfocus: "��ѡ������",
	    oncorrect: "����������ںϷ�"
	}).inputValidator({
	    min: "1900-01-01",
	    max: "2100-01-01",
	    type: "date",
	    onerror: "���ڱ�����\"1900-01-01\"��\"2100-01-01\"֮��"
	});

});
