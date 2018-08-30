$(document).ready(function() {
	/**
	 * 验证信息
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

    //人力决算部分特别设置
    var customPriceObj = $("#customPrice");
    if(customPriceObj.length > 0){
        if(customPriceObj.val() == "1"){
            $("#priceShow").attr('readonly',false).removeClass('readOnlyText').addClass('txt').val($("#price").val()).blur(function(){
                if(checkMoney($(this).val()) == true){
                    $("#price").val($(this).val());
                    countPersonE();
                }else{
                    alert('输入不正确');
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