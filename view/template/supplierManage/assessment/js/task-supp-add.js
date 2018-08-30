$(document).ready(function () {
    $("#assesManName").yxselect_user({
        hiddenId: 'assesManId',
        mode: "single",
        formCode: 'assessTask'
    });
    if ($("#assessType").val() == "gysjd") {
        $(".gysjd").show();
    }
    $("#assesQuarter").val($("#thisQuarter").val());

    /**
     * 验证信息
     */
    validate({
        "assesManName": {
            required: true
        },
        "hopeDate": {
            required: true
        },
        "assessType": {
            required: true
        }
    });
});

//验证是否选择了评估类型
function checkSelectSupp() {
    var assessType = $("#assessType").val();
    if (assessType == "") {
        alert("请先选择评估类型");
    }
}

//验证是否选择了评估类型
function checkAssesType() {
    var assessType = $("#assessType").val();
    if (assessType == "") {
        alert("请先选择评估类型");
    }
}
//开始时间与结束时间差验证
function timeCheck($t) {
    var s = plusDateInfo('formDate', 'hopeDate');
    if (s < 0) {
        alert("下达时间不能比期望完成时间晚！");
        $t.value = "";
        return false;
    }
}

//提交表单验证数据
function checkData(){
    //验证该供应商该季度的是否已存在考核任务
    var flag=true;
    $.ajax({
        type : 'POST',
        url : '?model=supplierManage_assessment_task&action=checkData',
        data:{
            suppId : $("#suppId").val(),
            assesYear : $("#assesYear").val(),
            assesQuarter : $("#assesQuarter").val(),
            assessType: $("#assessType").val()
        },
        async: false,
        success : function(data){
            if(data==0){
                alert("该供应商该季度已下达考核任务");
                flag=false;
            }
        }
    });
    return flag;
}