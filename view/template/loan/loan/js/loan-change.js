$(function(){
    if($("#isBeyondBudget").val() == "是"){
        $("#isBeyondBudgetView").css("color","red");
    }else{
        $("#isBeyondBudgetView").css("color","black");
    };

    var loanType = $("#xmflagTag").val();
    if(loanType == 1){
        $(".proView").show();
        //工程项目渲染
        $("#ProjectNo").yxcombogrid_esmproject({
            isDown: true,
            hiddenId: 'projectId',
            nameCol: 'ProjectNo',
            height: 250,
            isFocusoutCheck: false,
            openPageOptions : false,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                autoload : false,
                param: {
                    statusArr: 'GCXMZT02',// 在建
                    attributes:'GCXMSS-02,GCXMSS-01,GCXMSS-05'//服务项目,试用项目,研发项目
                },
                event: {
                    row_dblclick: function(e, row, data) {
                        $("#ProjectNo").val(data.projectCode);
                        $("#ProjectId").val(data.id);
                        chkBeyondBudget(data.id,'');
                    }
                }
            },
            event: {
                clear: function() {
                    $("#ProjectNo").val('');
                    $("#ProjectId").val('');
                    cleanBeyondBudgetChk();
                }
            }
        });
    }else{
        $(".proView").hide();
    }

    var hasChangeRclId = $("#hasChangeRclId").val();
    if(hasChangeRclId != ''){
        if(confirm("存在未提交的变更记录,需要加载吗?")){
            var responseText = $.ajax({
                url:'index1.php?model=loan_loan_loan&action=getChangeRecordByAjax',
                data : {"id" : hasChangeRclId},
                type : "POST",
                async : false
            }).responseText;
            var responseObj = eval("("+responseText+")");
            $("#ProjectNo").val(responseObj.ProjectNo);
            $("#ProjectId").val(responseObj.projectId);
            $("#isBeyondBudget").val(responseObj.isBeyondBudget);
            $("#isBeyondBudgetView").text(responseObj.isBeyondBudget);
            $("#PrepaymentDate").val(responseObj.PrepaymentDate);
            $("#rendHouseStartDate").val(responseObj.rendHouseStartDate);
            $("#rendHouseEndDate").val(responseObj.rendHouseEndDate);
            $("#changeReason").val(responseObj.changeLog.changeReason);
            $("#uploadfileList2").html(responseObj.file);
            $("#hasFilesNum").val(responseObj.hasFilesNum);
        }
    }
});

// 申请金额是否超过项目预计金额（start）
function cleanBeyondBudgetChk() {
    $("#isBeyondBudgetView").text("");
    $("#isBeyondBudget").val("");
}
function chkBeyondBudget(id,amount) {
    var projId = (id == undefined || id == '')? $("#ProjectId").val() : id;
    var amountVal = (amount == undefined || amount == '')? $("#Amount").val() : amount;
    if(projId != '' && amountVal != ''){
        var responseText = $.ajax({
            url:'index1.php?model=loan_loan_loan&action=ajaxChkBeyondBudget',
            data : {"projId" : projId,"amountVal" : amountVal},
            type : "POST",
            async : false
        }).responseText;
        var responseObj = eval("("+responseText+")");
        if(responseObj.isBeyondBudget != 1){
            $("#isBeyondBudgetView").text("否");
            $("#isBeyondBudget").val("否");
            $("#isBeyondBudgetView").css("color","black");
        }else{
            $("#isBeyondBudgetView").text("是");
            $("#isBeyondBudget").val("是");
            $("#isBeyondBudgetView").css("color","red");
        }
    }else{
        cleanBeyondBudgetChk();
    }
}
// 申请金额是否超过项目预计金额（end）

function chkForm(){
    var loanType = $("#xmflagTag").val();
    if(loanType == 1){
        if($("#ProjectNo").val() == ""){
            alert("项目编号为必填项目。");
            $("#ProjectNo").focus();
            return false;
        }
    }
    if($("#loanNatureCode").val() == '1'){
        var rendHouseStartDate = $("#rendHouseStartDate").val();
        var rendHouseEndDate = $("#rendHouseEndDate").val();
        var projId = $("#ProjectId").val();
        if(rendHouseStartDate == ''){
            alert("租房时间必填。");
            return false;
        }else if(rendHouseEndDate == ''){
            alert("租房时间必填。");
            return false;
        }else if(loanType == 1){
            var responseText = $.ajax({
                url:'index1.php?model=loan_loan_loan&action=ajaxChkRendDateRange',
                data : {"projId" : projId,"rendHouseStartDate" : rendHouseStartDate,"rendHouseEndDate" : rendHouseEndDate},
                type : "POST",
                async : false
            }).responseText;
            var responseObj = eval("("+responseText+")");
            if(responseObj.result != 'ok'){
                if(responseObj.startDate != '' || responseObj.endDate != ''){
                    var confirmRslt = confirm("该项目预计工期范围 "+responseObj.startDate+" 至 "+responseObj.endDate+"，租房时间不符合项目工期范围，是否确认提交?");
                    if(!confirmRslt){
                        return false;
                    }
                    // $("input.rendDepositInfo").val('');
                }
            }
        }
    }

    if($("#xmflagTag").val() == 1 && $("#isBeyondBudget").val() != "否"){
        alert("借款超过项目预算，请通知项目经理及时修改项目预算。");
        return false;
    }

    if($("#changeReason").val() == ''){
        alert("请填写变更理由!");
        return false;
    }
    return true;
}

/**
 * 提交变更表单
 */
function toChange(){
    var chkResult = chkForm();
    if(chkResult){
        var loanNatureVal = $("#loanNatureCode").val();
        var uploadFileCount = $("#uploadFileList2")[0].children.length;
        if(uploadFileCount <=0 && loanNatureVal == 1){
            $("#hasFilesNum").val(0);
            if(confirm("请尽快补充详细的租赁合同/协议跟押金条，否则系统将在出纳付款后10天将此借款变更为可冲销借款！")){
                $('#form1').submit();
            }
        }else{
            $("#hasFilesNum").val(uploadFileCount);
            $('#form1').submit();
        }
    }
}