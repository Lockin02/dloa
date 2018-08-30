//�������֤���� - ��Ϊ��Ŀ���ʱ,��Ҫ��д��Ŀ���
function checkFormChange() {
    //��֤�����Ƿ����ı�
    var rs = checkWithoutIgnore('��ͬ��Ҫ����û�з����ı�');
    if (rs == false) {
        return false;
    }

    if ($("#changeReason").val() == "") {
        alert('���ԭ�������д');
        return false;
    }

    var fundTypeObj = $("#fundType");
    if (fundTypeObj.val() == 'KXXZB' || fundTypeObj.val() == 'KXXZC') {
        // ���÷�̯��֤
        if ($("#shareGrid").costShareGrid('checkForm', $("#" + getMoneyKey('data')).val(), false) == false) {
            return false;
        }

        if($("#isNeedRelativeContract").val() == 1 && $("#hasRelativeContract option:selected").val() == 1 && ($("#relativeContractId").val() <= 0 || $("#relativeContractId").val() == '')){
            alert('������ϸ����Ͷ�����ѡ����������ͬ,��֤������������ͬ�ű���');
            $("#relativeContract").focus();
            return false;
        }
    }

    if ($("#isNeed").val() == 1) {
        if ($("#fundCondition").val() == "") {
            alert('��������������д');
            return false;
        }
    }

    // �����������
    var mainTypeSlted = $("#payForBusinessMain").find("option:selected");
    var isNeedFileTip = mainTypeSlted.attr("e3");
    if(isNeedFileTip == 1){
        var dispass = true;
        if($("#uploadFileList").children("input").length > 0 || $("#uploadFileList div").children(".newFile").length > 0){
            dispass = false;
        }

        if(dispass){
            var businessTypeName = mainTypeSlted.text();
            alert("�˸���ҵ�����͡�"+businessTypeName+"���ĸ�������Ϊ�ա�");
            $("#upload #file_upload_1-button").focus();
            return false;
        }
    }

    return true;
}
var subChange = function(){
    if(checkFormChange()){
        $("#form1").submit();
    }
}

$(document).ready(function () {
    //���������Ƿ����
    if ($("#isNeed").val() == "1") {
        $("#myspan").show();
    }

    //ǩԼ��λ
    $("#signCompanyName").yxcombogrid_signcompany({
        hiddenId: 'signCompanyId',
        isFocusoutCheck: false,
        gridOptions: {
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#proCode").val(data.proCode);
                    $("#proName").val(data.proName);
                    $("#linkman").val(data.linkman);
                    $("#phone").val(data.phone);
                    $("#address").val(data.address);
                }
            }
        }
    });

    //��ȡʡ�����鲢��ֵ��provinceArr
    var provinceArr = getProvince();

    //��ʡ������provinceArr��ֵ��proCode
    addDataToProvince(provinceArr, 'proCode');

    //��ѡ������
    $("#principalName").yxselect_user({
        hiddenId: 'principalId',
        isGetDept: [true, "deptId", "deptName"]
    });

    //��ѡ���ù�������
    $("#feeDeptName").yxselect_dept({
        hiddenId: 'feeDeptId',
        unDeptFilter: ($('#unDeptFilter').val() != undefined)? $('#unDeptFilter').val() : ''
    });

    //����Ǹ����ͬ����ʼ����Ŀ�����Ϣ
    var fundTypeObj = $("#fundType");
    if (fundTypeObj.val() == 'KXXZB' || fundTypeObj.val() == 'KXXZC') {
		$("#shareGridTr").show();
		$('#st').tabs();

        // ���ط�̯�б�
        $("#shareGrid").costShareGrid({
            objName: 'other[costshare]',
            type: 'change',
            url: "?model=finance_cost_costshare&action=listjson",
            param: {objType: 2, objId: $("#id").val()},
            isShowCountRow: true,
            countKey: getMoneyKey()
        });

        if(fundTypeObj.val() == 'KXXZB'){
            var mainTypeSlted = $("#payForBusinessMain").find("option:selected");
            var mainTypeCode = mainTypeSlted.val();

            var extCode = mainTypeSlted.attr("e1");
            var isNeedFileTip = mainTypeSlted.attr("e3");// �Ƿ�Ϊ���������ʶ

            // ���������
            if(isNeedFileTip == 1){
                if($("#fileIsNeed").length <= 0){
                    setTimeout(function(){
                        $("#upload #file_upload_1-button").after("<span id='fileIsNeed' class='red'>[*]</span>");
                    },100);
                }
            }else{
                $('#fileIsNeed').remove();
            }

            // ��ʼ����Ӧ�ı���Ϣ
            switch (extCode){
                case 'FKYWLX_EXT1':
                    $(".prefBidDateWrap").show();
                    $("#EXT1").show();
                    $("#EXT1-2").show();
                    break;
                case 'FKYWLX_EXT2':
                    $("#EXT2").show();
                    $("#EXT1-2").show();
                    break;
                case 'FKYWLX_EXT3':
                    $("#EXT1-2").hide();
                    $("#EXT3").show();
                    break;
                case 'FKYWLX_EXT4':
                    $(".relativeContract").show();
                    $("#EXT4").show();
                    break;
                case 'FKYWLX_EXT5':
                    $("#EXT1").show();
                    $(".prefBidDateWrap").hide();
                    break;
            }

            // ����ԭ�������б�����Ϣ
            var payForBusinessVal = $("#payForBusinessVal").val();
            if(payForBusinessVal == 'FKYWLX-03' || payForBusinessVal == 'FKYWLX-04'){
                var isBankbackLetterVal = $("#isBankbackLetterVal").val();
                var defalutBackLetterEndDate = $("#isBankbackLetterVal").attr("defalut-date");
                if(isBankbackLetterVal == 1){
                    $("#isBankbackLetterYes").attr("checked","checked");
                    $("#backLetterEndDate").val(defalutBackLetterEndDate);
                    // $(".backLetterEndDateWrap").show();
                    // $("#backLetterEndDate").addClass("validate[required]");
                }else if(isBankbackLetterVal == 0){
                    $("#isBankbackLetterNo").attr("checked","checked");
                    $(".backLetterEndDateWrap").hide();
                    $("#backLetterEndDate").val('');
                }else{
                    $(".backLetterEndDateWrap").hide();
                }
                $("#backLetterEndDate").removeClass("validate[required]");
            }else if(payForBusinessVal == 'FKYWLX-0'){
                var unSelectableIdsConfig = $("#unSelectableIdsConfig").val();
                var unSelectableIdsConfigObj = unSelectableIdsConfig.split(",");
                $("#unSelectableIds").val(unSelectableIdsConfig);
            }
        }
    }

    if ($("#canChangeCurrency").val() == "1") {
        // ���ұ�
        $("#currency").yxcombogrid_currency({
            valueCol: 'currency',
            isFocusoutCheck: false,
            gridOptions: {
                showcheckbox: false
            }
        });
    } else {
        $("#currency").removeClass('txt').addClass('readOnlyText');
    }

    // ��֤��Ϣ
    validate({
        orderName: {
            required: true,
            length: [0, 100]
        },
        signCompanyName: {
            required: true,
            length: [0, 100]
        },
        principalName: {
            required: true,
            length: [0, 20]
        },
        orderMoney_v: {
            required: true
        },
        linkman: {
            required: true,
            length: [0, 100]
        },
        signDate: {
            custom: ['date']
        },
        phone: {
            required: true
        },
        description: {
            required: false,
            length: [0, 300]
        },
        proCode: {
            required: true
        },
        currency: {
            required: true
        }
    });

    // ��Ʊ�¼���
    $("#invoiceType").bind("change", invoiceTypeChange);

    //ʡ��ѡ��
    setSelect('proCode');

    if ($("#isStamp").val() == 0 && $("#isNeedStamp").val() == 1) {

    } else {
        if ($("#isStamp").val() == 1) {
            $(".canStamp").show();
        }
    }

    //�Ƿ��б���ֶγ�ʼ��
    initWithoutIgnore();

    var isNeedRelativeContract = $("#isNeedRelativeContract").val();
    var hasRelativeContract = $("#hasRelativeContract").val();
    if(hasRelativeContract == 2){
        $("#relativeContract").val("");
        $("#relativeContractId").val("");
        $(".sltRelativeContractWrap").hide();
    }
    if(isNeedRelativeContract == 1){
        $(".relativeContract").show();
    }
});