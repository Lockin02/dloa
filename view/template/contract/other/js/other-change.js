//变更表单验证方法 - 当为项目外包时,需要填写项目编号
function checkFormChange() {
    //验证数据是否发生改变
    var rs = checkWithoutIgnore('合同主要内容没有发生改变');
    if (rs == false) {
        return false;
    }

    if ($("#changeReason").val() == "") {
        alert('变更原因必须填写');
        return false;
    }

    var fundTypeObj = $("#fundType");
    if (fundTypeObj.val() == 'KXXZB' || fundTypeObj.val() == 'KXXZC') {
        // 费用分摊验证
        if ($("#shareGrid").costShareGrid('checkForm', $("#" + getMoneyKey('data')).val(), false) == false) {
            return false;
        }

        if($("#isNeedRelativeContract").val() == 1 && $("#hasRelativeContract option:selected").val() == 1 && ($("#relativeContractId").val() <= 0 || $("#relativeContractId").val() == '')){
            alert('费用明细含【投标服务费】的其他类合同,保证金关联其他类合同号必填');
            $("#relativeContract").focus();
            return false;
        }
    }

    if ($("#isNeed").val() == 1) {
        if ($("#fundCondition").val() == "") {
            alert('款项条件必须填写');
            return false;
        }
    }

    // 附件必填检验
    var mainTypeSlted = $("#payForBusinessMain").find("option:selected");
    var isNeedFileTip = mainTypeSlted.attr("e3");
    if(isNeedFileTip == 1){
        var dispass = true;
        if($("#uploadFileList").children("input").length > 0 || $("#uploadFileList div").children(".newFile").length > 0){
            dispass = false;
        }

        if(dispass){
            var businessTypeName = mainTypeSlted.text();
            alert("此付款业务类型【"+businessTypeName+"】的附件不得为空。");
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
    //款项条件是否必填
    if ($("#isNeed").val() == "1") {
        $("#myspan").show();
    }

    //签约单位
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

    //获取省份数组并赋值给provinceArr
    var provinceArr = getProvince();

    //把省份数组provinceArr赋值给proCode
    addDataToProvince(provinceArr, 'proCode');

    //单选负责人
    $("#principalName").yxselect_user({
        hiddenId: 'principalId',
        isGetDept: [true, "deptId", "deptName"]
    });

    //单选费用归属部门
    $("#feeDeptName").yxselect_dept({
        hiddenId: 'feeDeptId',
        unDeptFilter: ($('#unDeptFilter').val() != undefined)? $('#unDeptFilter').val() : ''
    });

    //如果是付款合同，初始化项目相关信息
    var fundTypeObj = $("#fundType");
    if (fundTypeObj.val() == 'KXXZB' || fundTypeObj.val() == 'KXXZC') {
		$("#shareGridTr").show();
		$('#st').tabs();

        // 加载分摊列表
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
            var isNeedFileTip = mainTypeSlted.attr("e3");// 是否为附件必填标识

            // 附件必填处理
            if(isNeedFileTip == 1){
                if($("#fileIsNeed").length <= 0){
                    setTimeout(function(){
                        $("#upload #file_upload_1-button").after("<span id='fileIsNeed' class='red'>[*]</span>");
                    },100);
                }
            }else{
                $('#fileIsNeed').remove();
            }

            // 初始化对应的表单信息
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

            // 带出原来的银行保函信息
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
        // 金额币别
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

    // 验证信息
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

    // 发票事件绑定
    $("#invoiceType").bind("change", invoiceTypeChange);

    //省份选择
    setSelect('proCode');

    if ($("#isStamp").val() == 0 && $("#isNeedStamp").val() == 1) {

    } else {
        if ($("#isStamp").val() == 1) {
            $(".canStamp").show();
        }
    }

    //是否有变更字段初始化
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