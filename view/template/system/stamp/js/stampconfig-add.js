$(document).ready(function() {
	$("#principalName").yxselect_user({
		hiddenId : 'principalId',
		formCode : 'stampConfig',
		mode : 'check'
	});

    // 选择法人
    $("#legalPersonName").yxselect_user({
        hiddenId : 'legalPersonUsername',
        formCode : 'stampConfig'
    });

	/**
	 * 验证信息
	 */
	validate({
		"stampName" : {
			required : true
		},
		"principalName" : {
			required : true
		},
        "businessBelong" : {
            required : true
        },
        "typeId" : {
            required : true
        }
	});
    // 印章类别
    var customerTypeArr = getData('YZLB');
    addDataToSelect(customerTypeArr, 'typeId');
    $("#typeId").change(function(){
        var selectVal = $(this).val();
        $.each(customerTypeArr,function(k,v){
            if(v.dataCode == selectVal){
                $("#typeName").val(v.dataName);
            }
        })
    });

    //公司
    var businessBelongArr = getData('QYZT');
    addDataToSelect(businessBelongArr, 'businessBelong');
    $("#businessBelong").change(function(){
        var selectVal = $(this).val();
        $.each(businessBelongArr,function(k,v){
            if(v.dataCode == selectVal){
                $("#businessBelongName").val(v.dataName);
            }
        })
    });
    // $("#businessBelongName").yxcombogrid_branch({
    //     hiddenId: 'businessBelong',
    //     height: 250,
    //     isFocusoutCheck: false,
    //     gridOptions: {
    //         showcheckbox: false,
    //         event: {
//                'row_dblclick': function (e, row, data) {
//                    $("#areaName").val("");
//                    $("#areaCode").val("");
//                    $("#areaPrincipal").val("");
//                    $("#areaPrincipalId").val("");
//
//                    $("#areaName").yxcombogrid_area("remove");
////							regionList();
//                }
//             }
//         }
//     });

    $("#stampName").blur(function(){
        chkStampName($(this).val());
    });
})

var formChk = function(){
    var pass = false;
    if(chkStampName($("#stampName").val())){
        pass = true;
    }

    if(pass){
        $("#form1").submit();
    }
}

var chkStampName = function(stampName){
    if(stampName != ''){
        var responseText = $.ajax({
            url : 'index1.php?model=system_stamp_stampconfig&action=ajaxChkStampName',
            type : "POST",
            data : {
                stampName : stampName
            },
            async : false
        }).responseText;
        var result = eval("("+responseText+")");
        if(result.result != 'ok'){
            $("#tips").html("<br>"+result.msg);
            setTimeout(function(){
                $("#stampName").val('');
            },1000);
            return false;
        }else{
            $("#tips").html('');
            return true;
        }
    }else{
        $("#tips").html('');
        return true;
    }
};