$(document).ready(function() {
    $.formValidator.initConfig({
    	formid: "form1",
        //autotip: true,
        onerror: function(msg) {
            //alert(msg);
        }

    });
/**
 * 线索名称
 */
	$("#cluesName").formValidator({
		onshow : "请输入线索名称",
		onfocus : "名称至少2个字符，最多50个字符",
		oncorrect : "您输入的名称有效"
	}).inputValidator({
		min : 2,
		max : 50,
		empty : {
			leftempty : false,
			rightempty : false,
			emptyerror : "名称两边不能为空"
		},
		onerror : "您输入的名称不合法，请重新输入"
	});

});

//验证电话
	function tel(Num){
	    var tel = $("#mobileTel"+Num).val();
	    var t = /(\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$/ ;
	    if(t.test(tel) == false){
	        alert ("请正确填写电话信息！");
	        $("#mobileTel"+Num).val("");
	        $("#mobileTel"+Num).focus();
	    }
	}

//验证邮箱
	function Email(Num){
	     var email = $("#email"+Num).val();
	     var E =  /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
         if(E.test(email) == false){
             alert("请填写正确的邮箱信息");
             $("#email"+Num).val("");
             $("#email"+Num).focus();
         }
	}