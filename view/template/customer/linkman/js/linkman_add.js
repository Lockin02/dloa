$(function() {
	if($("#isFromCustomer").val()!=1){
		// 单选客户
		$("#customerName").yxcombogrid_customer({
					hiddenId : 'customerId',
					gridOptions : {
						showcheckbox : false
					}
				});
	}

	if($("#isFromCustomer").val()==1){
		$("#customerName").attr('readonly',true);
	}


});

//验证邮箱
	function Ema(){
	     var Email = $("#email").val();
	     var E =  /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
         if(E.test(Email) == false){
             alert("请填写正确的邮箱信息");
             $("#email").val("");
             $("#email").focus();
         }
	}
	//验证手机
function Mob(){
	    var tel = $("#mobile").val();
	    var t = /(\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$/ ;
	    if(t.test(tel) == false){
	        alert ("请正确填写电话信息！");
	        $("#mobile").val("");
	       $("#mobile").focus();
	    }

	}

	//验证固定电话
	function tell(){
	   var tel= $('#phone').val().replace(/(^[0-9]{3,4}\-[0-9]{7,8}$)|(^[0-9]{7,8}$)|(^\([0-9]{3,4}\)[0-9]{3,8}$)|(^0{0,1}13[0-9]{9}$)/)*1;
	   if(!tel){
	        alert ("请正确填写电话信息！");
	        $("#phone").val("");
	       $("#phone").focus();
	    }

	}
	//验证年龄
	function Agecheck(){
	var Ag=$('#age').val();
	a=/^([+]?)(\d+)$/;
	if(a.test(Ag)==false){

	alert("请输入正确的年龄");
	 $("#age").val("");
	 $("#age").focus();
	}
	if(Ag>100){
	alert("请输入正确的年龄");
	}
	}
	function QQcheck(){
	var Qq=$('#QQ').val();
	Q=/^\d+$/;
	if(Q.test(Qq)==false){
	alert("请输入正确的QQ信息");
	 $("#QQ").val("");
	 $("#QQ").focus();
	}
	}
	//验证MSN
	function msn(){
	     var ms = $("#MSN").val();
	     var E =  /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
         if(E.test(ms) == false){
             alert("请填写正确的MSN信息");
             $("#MSN").val("");
             $("#MSN").focus();
         }
	}
