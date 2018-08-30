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
    //         }
    //     }
    // });

	//状态选中事件
	var status = $("#status").val();

	$.each($("input:radio[name='stampconfig[status]']"),function(){
		if(this.value == status){
			this.checked = true;
		}

	})

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
                stampName : stampName,
                stampId : $("#stampId").val()
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