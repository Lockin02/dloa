$(document).ready(function() {
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"price" : {
			required : true
		},
		"numberOne" : {
			required : true,
			custom : ['onlyNumber']
		},
		"numberTwo" : {
			required : true,
			custom : ['onlyNumber']
		},
		"amount" : {
			required : true
		}
	});

    //�������㲿���ر�����
    var customPriceObj = $("#customPrice");
    if(customPriceObj.length > 0){
        if(customPriceObj.val() == "1"){
            $("#priceShow").attr('readonly',false).removeClass('readOnlyText').addClass('txt').val($("#price").val()).blur(function(){
                if(checkMoney($(this).val()) == true){
                    $("#price").val($(this).val());
                    countPersonE();
                }else{
                    alert('���벻��ȷ');
                    $(this).val('');
                }
            });
        }
        validate({
            "priceShow" : {
                required : true
            }
        });
    }
});