$(function() {
	if($("#isFromCustomer").val()!=1){
		// ��ѡ�ͻ�
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

//��֤����
	function Ema(){
	     var Email = $("#email").val();
	     var E =  /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
         if(E.test(Email) == false){
             alert("����д��ȷ��������Ϣ");
             $("#email").val("");
             $("#email").focus();
         }
	}
	//��֤�ֻ�
function Mob(){
	    var tel = $("#mobile").val();
	    var t = /(\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$/ ;
	    if(t.test(tel) == false){
	        alert ("����ȷ��д�绰��Ϣ��");
	        $("#mobile").val("");
	       $("#mobile").focus();
	    }

	}

	//��֤�̶��绰
	function tell(){
	   var tel= $('#phone').val().replace(/(^[0-9]{3,4}\-[0-9]{7,8}$)|(^[0-9]{7,8}$)|(^\([0-9]{3,4}\)[0-9]{3,8}$)|(^0{0,1}13[0-9]{9}$)/)*1;
	   if(!tel){
	        alert ("����ȷ��д�绰��Ϣ��");
	        $("#phone").val("");
	       $("#phone").focus();
	    }

	}
	//��֤����
	function Agecheck(){
	var Ag=$('#age').val();
	a=/^([+]?)(\d+)$/;
	if(a.test(Ag)==false){

	alert("��������ȷ������");
	 $("#age").val("");
	 $("#age").focus();
	}
	if(Ag>100){
	alert("��������ȷ������");
	}
	}
	function QQcheck(){
	var Qq=$('#QQ').val();
	Q=/^\d+$/;
	if(Q.test(Qq)==false){
	alert("��������ȷ��QQ��Ϣ");
	 $("#QQ").val("");
	 $("#QQ").focus();
	}
	}
	//��֤MSN
	function msn(){
	     var ms = $("#MSN").val();
	     var E =  /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
         if(E.test(ms) == false){
             alert("����д��ȷ��MSN��Ϣ");
             $("#MSN").val("");
             $("#MSN").focus();
         }
	}
