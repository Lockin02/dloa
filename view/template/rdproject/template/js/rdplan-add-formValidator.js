$(document).ready(function(){
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        }
    });
	$("#templateName").formValidator({
		onshow:"������ģ������",
		onfocus:"ģ�������벻Ҫ�������25������",
		oncorrect:"�������ģ�����Ʋ�����"
	}).inputValidator({
		min:1,
		max:50,
		empty:{
			leftempty:false,
			rightempty:false,
			emptyerror:"ģ���������߲����пշ���"
		},
		onerror:"�������ģ�����Ʋ�����Ҫ������"
	});
});