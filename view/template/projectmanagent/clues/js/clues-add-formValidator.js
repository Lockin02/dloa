$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        }

    });
/**
 * ��������
 */
	$("#cluesName").formValidator({
		onshow : "��������������",
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
		onerror : "����������Ʋ��Ϸ�������������"
	});

});

//��֤�绰
	function tel(Num){
	    var tel = $("#mobileTel"+Num).val();
	    var t = /(\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$/ ;
	    if(t.test(tel) == false){
	        alert ("����ȷ��д�绰��Ϣ��");
	        $("#mobileTel"+Num).val("");
	        $("#mobileTel"+Num).focus();
	    }
	}

//��֤����
	function Email(Num){
	     var email = $("#email"+Num).val();
	     var E =  /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
         if(E.test(email) == false){
             alert("����д��ȷ��������Ϣ");
             $("#email"+Num).val("");
             $("#email"+Num).focus();
         }
	}