$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        }

    });
/**
 * ����������
 */
	$("#trackName").formValidator({
		onshow : "���������������",
		onfocus : "��������2���ַ������50���ַ�",
		oncorrect : "�������������Ч"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "�������߲���Ϊ��"
		},
		onerror : "��������������Ϸ�������������"
	});

/**
 *��������
 */
$("#trackDate").formValidator({
         onshow:"�������������",
     	 onfocus:"�������ڲ���Ϊ��",
     	 oncorrect:"����������ںϷ�"
     }).inputValidator({
     	 min:"1900-01-01",
     	 max:"3000-01-01",
     	 type:"date",
     	 onerror:"���ڱ�����\"1900-01-01\"��\"3000-01-01\"֮��"});//.defaultPassed();

});