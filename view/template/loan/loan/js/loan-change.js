$(function(){
    if($("#isBeyondBudget").val() == "��"){
        $("#isBeyondBudgetView").css("color","red");
    }else{
        $("#isBeyondBudgetView").css("color","black");
    };

    var loanType = $("#xmflagTag").val();
    if(loanType == 1){
        $(".proView").show();
        //������Ŀ��Ⱦ
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
                    statusArr: 'GCXMZT02',// �ڽ�
                    attributes:'GCXMSS-02,GCXMSS-01,GCXMSS-05'//������Ŀ,������Ŀ,�з���Ŀ
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
        if(confirm("����δ�ύ�ı����¼,��Ҫ������?")){
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

// �������Ƿ񳬹���ĿԤ�ƽ�start��
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
            $("#isBeyondBudgetView").text("��");
            $("#isBeyondBudget").val("��");
            $("#isBeyondBudgetView").css("color","black");
        }else{
            $("#isBeyondBudgetView").text("��");
            $("#isBeyondBudget").val("��");
            $("#isBeyondBudgetView").css("color","red");
        }
    }else{
        cleanBeyondBudgetChk();
    }
}
// �������Ƿ񳬹���ĿԤ�ƽ�end��

function chkForm(){
    var loanType = $("#xmflagTag").val();
    if(loanType == 1){
        if($("#ProjectNo").val() == ""){
            alert("��Ŀ���Ϊ������Ŀ��");
            $("#ProjectNo").focus();
            return false;
        }
    }
    if($("#loanNatureCode").val() == '1'){
        var rendHouseStartDate = $("#rendHouseStartDate").val();
        var rendHouseEndDate = $("#rendHouseEndDate").val();
        var projId = $("#ProjectId").val();
        if(rendHouseStartDate == ''){
            alert("�ⷿʱ����");
            return false;
        }else if(rendHouseEndDate == ''){
            alert("�ⷿʱ����");
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
                    var confirmRslt = confirm("����ĿԤ�ƹ��ڷ�Χ "+responseObj.startDate+" �� "+responseObj.endDate+"���ⷿʱ�䲻������Ŀ���ڷ�Χ���Ƿ�ȷ���ύ?");
                    if(!confirmRslt){
                        return false;
                    }
                    // $("input.rendDepositInfo").val('');
                }
            }
        }
    }

    if($("#xmflagTag").val() == 1 && $("#isBeyondBudget").val() != "��"){
        alert("������ĿԤ�㣬��֪ͨ��Ŀ����ʱ�޸���ĿԤ�㡣");
        return false;
    }

    if($("#changeReason").val() == ''){
        alert("����д�������!");
        return false;
    }
    return true;
}

/**
 * �ύ�����
 */
function toChange(){
    var chkResult = chkForm();
    if(chkResult){
        var loanNatureVal = $("#loanNatureCode").val();
        var uploadFileCount = $("#uploadFileList2")[0].children.length;
        if(uploadFileCount <=0 && loanNatureVal == 1){
            $("#hasFilesNum").val(0);
            if(confirm("�뾡�첹����ϸ�����޺�ͬ/Э���Ѻ����������ϵͳ���ڳ��ɸ����10�콫�˽����Ϊ�ɳ�����")){
                $('#form1').submit();
            }
        }else{
            $("#hasFilesNum").val(uploadFileCount);
            $('#form1').submit();
        }
    }
}