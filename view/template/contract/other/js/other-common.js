$(function () {
    var testVal = "hahaha";
    var fundType = $("#fundType").val();

    changeFundTypeClear(fundType);

    //初始化结算方式
    changePayTypeFun();

    // 计算合同金额
    $("#orderMoney_v").bind('blur', calContractMoney);
    $("#taxPoint").bind('blur', calContractMoney);

    if($("#hasRelativeContract").val() != undefined){
        $("#hasRelativeContract").change(function(){
            var selectedVal = $("#hasRelativeContract option:selected").val();
            if(selectedVal == 2){
                $("#relativeContract").val("");
                $("#relativeContractId").val("");
                $(".sltRelativeContractWrap").hide();
            }

            if(selectedVal == 1){
                $(".sltRelativeContractWrap").show();
            }
        });
    }
});

/**
 * 计算合同金额
 *
 * @param fromType // 使用金额来源:【data:表示来自数据库或是用户自定义,默认为空表示按公式计算】
 */
function calContractMoney(fromType) {
    var orderMoney = $("#orderMoney").val();
    var taxPoint = $("#taxPoint").val();
    if (orderMoney != "" && taxPoint != "") {
        var taxPointVal = accDiv(taxPoint, 100, 2);
        $("#taxPoint").attr('data-setVal',taxPoint);

        // 如果手动输入了不含税金额,则以手动输入金额为准
        var moneyNotTax_re = new RegExp(",","g");
        var moneyNotTax_input = Number($('#moneyNoTax_v').val().replace(moneyNotTax_re, ""));

        var caled_moneyNoTax = accDiv(orderMoney, accAdd(1, taxPointVal, 2), 2);
        if(fromType == 'data'){
            caled_moneyNoTax = (moneyNotTax_input != caled_moneyNoTax)? moneyNotTax_input : caled_moneyNoTax;
        }
        setMoney('moneyNoTax', caled_moneyNoTax);
    }

    // 计算分摊金额
    moneyChange();
}

//结算方式
function changePayTypeFun() {
    if ($("#payType").find("option:selected").attr("e1") == 1) {
        $("#bankNeed").show();
        $("#accountNeed").show();
    } else {
        $("#bankNeed").hide();
        $("#accountNeed").hide();
    }
}

//选择合同款项性质时充值签约公司
function changeFundType(fundType) {
    $("#projectName").val("");
    $("#projectCode").val("");
    $("#projectId").val("");
    $("#projectType").val("");
    $("#relativeContract").val("");
    $("#relativeContractId").val("");
    $("#isNeedRelativeContract").val("");

    changeFundTypeClear(fundType);
    setPayFoeBusinessValue("fundType");
    resetIsBankbackLetterRadio();
}

//选择对应签约对象
function changeFundTypeClear(fundType) {
    var fundTypeObj = $("#fundType");
    if (fundTypeObj.find("option:selected").length == 0){// 变更页面
        if(fundType == "KXXZB"){
            $(".payForBusinessMainTd").show();
        }
        return true;
    }

    //设置款项条件必填
    fundTypeObj = fundTypeObj.find("option:selected");
    if (fundTypeObj.find("option:selected").attr("e1") == 1) {
        $("#myspan").show();
    } else {
        $("#myspan").hide();
    }

    //款项条件
    $("#fundConditionShow").html(fundTypeObj.attr("e2"));
    $("#fundConditionRequired").remove();
    if(fundTypeObj.attr("e1") == 1){
        $("#fundConditionShow").parent("td").next("td").append('<span id="fundConditionRequired" class="red">[*]</span>');
    }

    //隐藏付款条件
    $("#forPay").hide();
    //付款合同关联项目部分
    $("#projectInfo").hide();

    //单选部门
    $("#otherFeeDeptName").yxselect_dept('remove');

    // 费用分摊
    if ($("#isShare").val() == "1") {
        //显示费用分摊明细
        $("#shareGrid").costShareGrid('remove');
    }

    // 隐藏付款业务类型
    $(".payForBusinessMainTd").hide();

    switch (fundType) {
        case 'KXXZA':
            incomeSet();
            break;
        case 'KXXZB':
            paySet();
            break;
        case 'KXXZC':
            noneSet();
            break;
        default :
            $("#fundTypeDesc").html('签约公司信息可以手动填写，不是必须从下拉窗口中选择。');
    }
}

//收款类合同设置
function incomeSet() {
    //插入显示文本
    $("#fundTypeDesc").html('收款类合同可以进行 <span class="red">[ 开票 ]</span> 和 <span class="red">[ 收款 ]</span> 操作');
    $("#orderMoneyNeed").show();
    $("#taxPointNeed").show();
    $("#moneyNoTaxNeed").show();
    $("#invoiceTypeNeed").show();
}

//付款类合同设置
function paySet() {
    //插入显示文本
    $("#fundTypeDesc").html('付款类合同可以进行 <span class="red">[ 收票 ]</span> 和 <span class="red">[ 付款 ]</span> 操作');
    $("#orderMoneyNeed").show();
    $("#taxPointNeed").show();
    $("#moneyNoTaxNeed").show();
    $("#invoiceTypeNeed").show();

    //开启付款申请信息部分
    $("#forPay").show();

    $(".payForBusinessMainTd").show();

    // 费用分摊
    if ($("#isShare").val() == "1") {
        //显示费用分摊明细
        loadShareGrid();
    }
}

var defaultChangeTime = 0;
// 获取发票类型对应的字段
function getMoneyKey(fromType) {
    // var invoiceTypeE3 = $("#invoiceType").find("option:selected").attr("e3");
    //
    // return invoiceTypeE3 && invoiceTypeE3 == "1" ? 'moneyNoTax' : 'orderMoney';
    // console.log($("#taxPoint").attr('title'));
    // 按选择的发票类型带出合同税率并按带出的税率计算不含税金额，然后可以修改合同税率 PMS2460
    var invoiceTypeE1 = ($("#invoiceType").find("option:selected").attr("e1") == '')? 0 : parseInt($("#invoiceType").find("option:selected").attr("e1"));
    var invoiceTypeVal = $("#invoiceType").find("option:selected").val();
    invoiceTypeE1 = (invoiceTypeVal == 'ZZSPTFP' || invoiceTypeVal == '')? 0 : invoiceTypeE1;// 增值税普通发票税率也为0

    if(defaultChangeTime == 0 && $("#taxPoint").attr('title') != ''){
        defaultChangeTime +=1;
        $("#taxPoint").val($("#taxPoint").attr('title'));
    }else if($("#taxPoint").attr('data-setVal') != '' && $("#taxPoint").attr('data-setVal') != undefined){
        $("#taxPoint").val($("#taxPoint").attr('data-setVal'));
    }else{
        $("#taxPoint").val(invoiceTypeE1);
    }

    calContractMoney(fromType);
    return 'moneyNoTax';
}

// 发票类型改变时触发事件 -- 此方法通过jquery绑定，可以直接用$(this)
var invoiceTypeChange = function () {
    $('#taxPoint').removeAttr('data-setVal');
    $("#shareGrid").costShareGrid('changeCountKey', getMoneyKey());
};

// 金额改变时触发事件 -- 此方法通过jquery绑定，可以直接用$(this)
var moneyChange = function () {
    $("#shareGrid").costShareGrid('countShareMoney');
};

//无款项类合同设置
function noneSet() {
    //插入显示文本
    $("#fundTypeDesc").html('该类合同无相关款项操作');
    $("#orderMoneyNeed").hide();
    $("#taxPointNeed").hide();
    $("#moneyNoTaxNeed").hide();
    $("#invoiceTypeNeed").hide();

    // 费用分摊
    if ($("#isShare").val() == "1") {
        //显示费用分摊明细
        loadShareGrid();
    }
}

//付款类合同选择项目类型时初始化项目对应选择框
function changeProject() {
    //清楚项目初始化信息
    $("#projectName").yxcombogrid_esmproject("remove").yxcombogrid_esmproject("remove").val("");
    $("#projectCode").val("");
    $("#projectId").val("");
    $("#projectNeed").hide();

    //调用无清除方法
    changeProjectClear();
}

//初始化项目选择框 - 无清除方法
function changeProjectClear() {
    //获取项目类型值
    var $val = $("#projectType").find("option:selected").val();
    if ($val == "") return false;
    $("#projectNeed").show();
    if ($val == 'QTHTXMLX-01') {
        //研发项目渲染啊
        $("#projectName").yxcombogrid_esmproject({
            hiddenId: 'projectId',
            nameCol: 'projectName',
            isShowButton: false,
            height: 250,
            isFocusoutCheck: false,
            gridOptions: {
                param: {'is_delete': 0},
                isTitle: true,
                showcheckbox: false,
                event: {
                    'row_dblclick': function (e, row, data) {
                        $("#projectCode").val(data.projectCode);
                    }
                }
            }
        });
    } else if ($val == 'QTHTXMLX-02') {
        //工程项目渲染
        $("#projectName").yxcombogrid_esmproject({
            hiddenId: 'projectId',
            nameCol: 'projectName',
            isShowButton: false,
            height: 250,
            isFocusoutCheck: false,
            gridOptions: {
                isTitle: true,
                showcheckbox: false,
                event: {
                    'row_dblclick': function (e, row, data) {
                        $("#projectCode").val(data.projectCode);
                    }
                }
            }
        });
    }
}

//盖章选择判断
function changeRadio() {
    //附件盖章验证
    var uploadFileList = $("#uploadfileList").html();
    if (uploadFileList == "" || uploadFileList == "暂无任何附件") {
        alert('申请盖章前需要上传合同附件!');
        $("#isNeedStampNo").attr("checked", true);
        return false;
    }

    //显示必填项
    if ($("#isNeedStampYes").attr("checked")) {
        $("#radioSpan").show();
        //防止重复数理化下拉表格
        $("#stampType").yxcombogrid_stampconfig('remove').yxcombogrid_stampconfig({
            hiddenId: 'stampIds',
            height: 250,
            gridOptions: {
                isTitle: true,
                showcheckbox: true,
                event: {
                    'row_check' : function(e, checkbox, row, rowData) {
                        // console.log(rowData);
                        if (checkbox.attr('checked')) {
                            if($('#businessBelongId').val() == ''){
                                $('#businessBelongId').val(rowData.businessBelongId);
                                $('#legalPersonUsername').val(rowData.legalPersonUsername);
                                $('#legalPersonName').val(rowData.legalPersonName);
                                return true;
                            }else if($('#businessBelongId').val() != rowData.businessBelongId){
                                alert('所选盖章类型归属公司必须一致。');
                                checkbox.removeAttr('checked');
                                row.removeClass('trSelected');
                                return false;
                            }else if($('#legalPersonUsername').val() != rowData.legalPersonUsername){
                                alert('此印章公司法人信息有差异，请联系管理员检查盖章配置信息。');
                                checkbox.removeAttr('checked');
                                row.removeClass('trSelected');
                                return false;
                            }
                        }else{
                            if($('#stampType').val().split(',').length == 1 && $('#stampType').val() == rowData.stampType){
                                $('#businessBelongId').val('');
                                $('#legalPersonUsername').val('');
                                $('#legalPersonName').val('');
                            }
                        }
                    }
                }
            },
            event : {
                'clear': function () {
                    $('#businessBelongId').val('');
                    $('#legalPersonUsername').val('');
                    $('#legalPersonName').val('');
                }
            }
        });
    } else {
        $("#radioSpan").hide();

        //盖章类型渲染
        $("#stampType").yxcombogrid_stampconfig('remove').val('');

        $('#businessBelongId').val('');
        $('#legalPersonUsername').val('');
        $('#legalPersonName').val('');
    }
}

//表单验证方法 - 当合同涉及款项时,需要填写款项条件
function checkForm() {
    // 这里保存一下分摊明细
    if ($("#isShare").val() == "1" && !$('#shareGrid').costShareGrid('ajaxSave', true)) {
        return false;
    }

    var fundTypeObj = $("#fundType");

    // 如果是付款类型的,先根据付款业务类型处理一下相应的费用明细
    if (fundTypeObj.val() == 'KXXZB'){
        var mainTypeSlted = $("#payForBusinessMain").find("option:selected");
        var mainTypeCode = mainTypeSlted.val();

        if(mainTypeCode == "FKYWLX-0"){// 如果选择了【无】则将费用明细中出现了禁止选择的费用类型ID的选项清空
            var unSelectableIdsConfig = $("#unSelectableIdsConfig").val();
            var unSelectableIdsConfigObj = unSelectableIdsConfig.split(",");
            $("[id^='shareGrid_cmp_costTypeName']").each(function(i,item){
                var rowNum = $(item).parents("tr").attr("rownum");
                if($.inArray($("#shareGrid_cmp_costTypeId"+rowNum).val(), unSelectableIdsConfigObj) >= 0){
                    $(item).val('');
                    $("#shareGrid_cmp_costTypeId"+rowNum).val('');
                    $("#shareGrid_cmp_parentTypeId"+rowNum).val('');
                    $("#shareGrid_cmp_parentTypeName"+rowNum).val('');
                }
                if($.inArray($("#costTypeSelectedHidden").val(), unSelectableIdsConfigObj) >= 0){
                    $("#costTypeSelectedHidden").val('');
                }
            });

        }else if(mainTypeSlted.attr("e3") == 1){// 附件是否为必填项验证
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

    }

    if($("#isNeedRelativeContract").val() == 1 && $("#hasRelativeContract").val() == "1"){
        if(($("#relativeContractId").val() <= 0 || $("#relativeContractId").val() == '') || $("#relativeContract").val() == ''){
            var businessTypeName = $("#payForBusinessMain option[value='FKYWLX-06']").text();
            alert('付款业务类型为【'+businessTypeName+'】的其他类合同,保证金关联其他类合同号必填');
            $("#relativeContract").focus();
            return false;
        }
    }

    if (fundTypeObj.val() == "") {
        alert('请选择合同的款项性质');
        return false;
    }

    var contCode4Type = $("#contCode4Type").find("option:selected").val();
    if (fundTypeObj.val() == 'KXXZB') {
        var mainTypeSlted = $("#payForBusinessMain").find("option:selected");
        if(mainTypeSlted.val() == '' || $("#payForBusinessName").val() == ''){
            alert('请选择付款业务类型。');
            return false;
        }else if($("#EXT1").css("display") != 'none' && ($("#chanceId").val() == '' || $("#chanceCode").val() == '')){
            $("#chanceCode").focus();
            alert('商机号不能为空。');
            return false;
        }else if(mainTypeSlted.val() != 'FKYWLX-07' && ($("#EXT1").css("display") != 'none' && $("#prefBidDate").val() == '')){
            alert('预计投标日期不能为空。');
            return false;
        }else if($("#EXT2").css("display") != 'none' && ($("#contractId").val() == '' || $("#contractCode").val() == '')){
            $("#contractId").val('');
            $("#contractCode").focus();
            alert('项目销售合同编号不能为空。');
            return false;
        }else if($("#EXT4").css("display") != 'none' && contCode4Type == '销售合同' && ($("#contractId").val() == '' || $("#contractCode").val() == '')){
            $("#contractId").val('');
            $("#contractCode").val('');
            $("#contractId4").val('');
            $("#contractCode4").val('');
            $("#contractCode4").focus();
            alert('项目销售合同编号不能为空。');
            return false;
        }else if($("#EXT4").css("display") != 'none' && contCode4Type == '商机' && ($("#chanceId").val() == '' || $("#chanceCode").val() == '')){
            $("#chanceId").val('');
            $("#chanceCode").val('');
            $("#chanceId4").val('');
            $("#chanceCode4").val('');
            $("#chanceCode4").focus();
            alert('商机号不能为空。');
            return false;
        }
        // else if($("#EXT2").css("display") != 'none' && $("#projectPrefEndDate").val() == ''){
        //     alert('项目预计结束时间不能为空。');
        //     return false;
        // }
        else if($("#EXT3").css("display") != 'none' && $("#prefPayDate").val() == ''){
            alert('预计回款时间不能为空。');
            return false;
        }

        var isBankbackLetter = $("input[name='other[isBankbackLetter]']:checked").val();
        if($("#EXT1-2").css("display") != 'none' &&
            (isBankbackLetter == undefined || (isBankbackLetter != 1 && isBankbackLetter != 0))){
            $("#isBankbackLetterYes").focus();
            alert('请选择是否是银行保函。');
            return false;
        }
    }

    //单据金额
    var orderMoneyObj = $("#orderMoney");
    if (orderMoneyObj.val() == "" && fundTypeObj.val() != 'KXXZC') {
        alert('合同金额不能为空');
        return false;
    }

    if ($("#taxPoint").val() == "" && fundTypeObj.val() != 'KXXZC') {
        alert('合同税率不能为空');
        return false;
    }

    var moneyNoTaxObj = $("#moneyNoTax");
    if (moneyNoTaxObj.val() == "" && fundTypeObj.val() != 'KXXZC') {
        alert('合同金额(不含税)不能为空');
        return false;
    }

    var invoiceTypeObj = $("#invoiceType");
    if (invoiceTypeObj.val() == "" && fundTypeObj.val() != 'KXXZC') {
        alert('合同发票类型不能为空');
        return false;
    }

    //款项性质必填
    if (fundTypeObj.find("option:selected").attr("e1") == 1) {
        if ($("#fundCondition").val() == "") {
            alert(fundTypeObj.find("option:selected").attr("e2") + '条件必须填写');
            return false;
        }
    }

    //币种
    if ($("#currency").val() == "") {
        alert('请填写付款币种');
        return false;
    }

    var isNeedPayapplyObj = $("#isNeedPayapply");
    if (fundTypeObj.val() == 'KXXZB') {

        //费用归属部门
        var feeDeptName = $("#otherFeeDeptName").val();
        if (feeDeptName == "") {
            alert('合同中的费用归属不能为空');
            return false;
        }

        //如果项目类型不为空，则项目信息非必填
        var projectType = $("#projectType").val();
        if (projectType != '') {
            //项目类型
            if (projectType == "") {
                alert('项目类型不能为空');
                return false;
            }

            //项目名称
            var projectName = $("#projectName").val();
            if (projectName == "") {
                alert('项目名称不能为空');
                return false;
            }
        }

        //付款申请
        if (isNeedPayapplyObj.length == 1 && isNeedPayapplyObj.attr("checked") == true) {
            // 是否开据发票
            var isInvoice = $("input[name='other[payapply][isInvoice]']:checked").val();
            if(isInvoice == undefined || (isInvoice != 1 && isInvoice != 0)){
                alert('请确定是否开据发票');
                return false;
            }

            //申请金额
            var applyMoney = $("#applyMoney").val();
            if (applyMoney == 0 || applyMoney == "") {
                alert('付款申请金额不能为0或空');
                return false;
            }

            //付款日期
            var formDate = $("#formDate").val();
            if (formDate == "") {
                alert('期望付款日期不能为空');
                return false;
            }

            //费用归属部门
            if ($("#feeDeptName").val() == "") {
                alert('费用归属部门不能为空');
                return false;
            }

            var innerPayType = $("#payType").find("option:selected").attr("e1");
            if (innerPayType == 1) {

                //收款银行
                var bank = $("#bank").val();
                if (bank == "") {
                    alert('收款银行不能为空');
                    return false;
                }

                //收款账号
                var account = $("#account").val();
                if (account == "") {
                    alert('收款账号不能为空');
                    return false;
                }
            }

            //汇入地点
            if ($("#place").val() == "") {
                alert('请填写汇入地点(省/市)');
                return false;
            }

            // 款项说明
            var payDesc = $("#payDesc").val();
            if (payDesc == "") {
                alert('请填写款项说明');
                return false;
            }
            payDesc = payDesc.replace(/\n/g, '').replace(/\r/g, '');
            if(payDesc.length > 60){
                alert('款项说明最多不能超过60个汉字。');
                $("#payDesc").focus();
                return false;
            }


            //收款单位
            if ($("#payee").val() == "") {
                alert('请填写收款单位');
                return false;
            }

            //款项用途
            var remark = strTrim($("#remark").val());
            if (remark == "") {
                alert('款项用途不能为空');
                return false;

            } else {
                if (remark.length > 10) {
                    alert('请将款项用途描述信息保持在10个字或10个字以内,当前长度为' + remark.length + " 个字");
                    return false;
                }
            }
            
            //付款金额与合同金额不一致时验证
            if(applyMoney * 1 != orderMoneyObj.val() * 1){
                if (orderMoneyObj.val() * 1 < applyMoney * 1) {
                    alert('合同金额不能小于付款申请金额');
                    $("#applyMoney").val("");
                    $("#applyMoney_v").val("");
                	return false;
                }
            	return confirm("付款金额与合同金额不一致，确认提交吗？");
            }
        }
    } else {
        //如果不是付款类型，失效
        if (isNeedPayapplyObj.length == 1) isNeedPayapplyObj.attr('checked', false);
    }

    if (fundTypeObj.val() == 'KXXZB' || fundTypeObj.val() == 'KXXZC') {
        // 费用分摊
        if ($("#isShare").val() == "1") {
            //显示费用分摊明细
            if ($("#shareGrid").costShareGrid('checkForm', $("#" + getMoneyKey('data')).val(), false) == false) {
                return false;
            }
        }
    }

    if ($("#isNeedStampYes").attr('checked') == true) {
        if ($("#stampType").val() == "") {
            alert('盖章类型必须填写');
            return false;
        }

        var upList = strTrim($("#uploadfileList").html());
        //附件盖章验证
        if (upList == "" || upList == "暂无任何附件") {
            alert('申请盖章前需要上传合同附件!');
            return false;
        }
    }

    return true;
}

//新增时提交审批 -- 独立新增
function auditDept(thisType) {
    if (thisType == 'audit') {
        document.getElementById('form1').action = "?model=contract_other_other&action=addDept&act=audit";
    } else {
        document.getElementById('form1').action = "?model=contract_other_other&action=addDept";
    }
    if(checkForm()){
        $('#form1').submit();
    }
}
/********************** 其他合同付款部分 ********************/

//改变合同金额后,如果申请金额大于合同金额，清除申请金额
function checkApplyMoney() {
    var orderMoneyObj = $("#orderMoney");
    var applyMoneyObj = $("#applyMoney");

    if (orderMoneyObj.val() * 1 < applyMoneyObj.val() * 1) {
        alert('合同金额不能小于付款申请金额');
        applyMoneyObj.val("");
        $("#applyMoney_v").val("");
    }
}

// 获取主表付款业务类型
function setPayFoeBusinessValue(isChange,isEdit){
    if(isChange != 'fundType'){
        var mainTypeSlted = $("#payForBusinessMain").find("option:selected");
        var mainTypeCode = mainTypeSlted.val();
        var mainTypeName = (mainTypeCode == '')? '' : mainTypeSlted.text();
        $("#payForBusinessName").val(mainTypeName);
        $("#payForBusinessShow").val(mainTypeName);
        $("#payForBusiness").val(mainTypeCode);

        if(mainTypeCode == "FKYWLX-0"){// 如果选择了【无】则将费用明细中出现了禁止选择的费用类型ID的选项清空
            var unSelectableIdsConfig = $("#unSelectableIdsConfig").val();
            var unSelectableIdsConfigObj = unSelectableIdsConfig.split(",");
            $("#unSelectableIds").val(unSelectableIdsConfig);
            $("[id^='shareGrid_cmp_costTypeName']").each(function(i,item){
                var rowNum = $(item).parents("tr").attr("rownum");
                if($.inArray($("#shareGrid_cmp_costTypeId"+rowNum).val(), unSelectableIdsConfigObj) >= 0){
                    $(item).val('');
                    $("#shareGrid_cmp_costTypeId"+rowNum).val('');
                    $("#shareGrid_cmp_parentTypeId"+rowNum).val('');
                    $("#shareGrid_cmp_parentTypeName"+rowNum).val('');
                }
                if($.inArray($("#costTypeSelectedHidden").val(), unSelectableIdsConfigObj) >= 0){
                    $("#costTypeSelectedHidden").val('');
                }
            });

        }else{
            $("#unSelectableIds").val("");
        }

        if(isChange == 'payType'){
            if(!isEdit){
                $("#delayPayDays").val('');
                $(".extInput").val('');
                $("#chanceCodeTips").text('');
                $(".contractCodeTips").text('');
            }
            $(".extTR").hide();
            $("#isNeedRelativeContract").val('0');
            resetIsBankbackLetterRadio();

            // 按业务类型组来显示相应的表单字段
            var extCode = mainTypeSlted.attr("e1");
            var defaultCostTypeId = mainTypeSlted.attr("e2");
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
                    $("#delayPayDays").val('');
                    $(".prefBidDateWrap").show();
                    $("#EXT1").show();
                    $("#EXT1-2").show();
                    break;
                case 'FKYWLX_EXT2':
                    $("#EXT2").show();
                    $("#EXT1-2").show();
                    if($("#delayPayDays").val() == ''){
                        $("#delayPayDays").val(60);
                    }
                    break;
                case 'FKYWLX_EXT3':
                    $("#EXT1-2").hide();
                    $("#EXT3").show();
                    break;
                case 'FKYWLX_EXT4':
                    $("#isNeedRelativeContract").val('1');
                    $("#EXT4").show();
                    break;
                case 'FKYWLX_EXT5':
                    $("#delayPayDays").val('');
                    $("#EXT1").show();
                    $(".prefBidDateWrap").hide();
                    break;
            }
            $("#defaultSelectedCostTypeId").val(defaultCostTypeId);
            dealDefaultCostshareInfo();
        }
    }else{
        $("#payForBusinessMain option:first").attr("selected", 'selected');
        $("#payForBusinessName").val("");
        $("#payForBusinessShow").val("");
        $("#payForBusiness").val("");
        $("#unSelectableIds").val("");

        $(".extInput").val('');
        $(".extTR").hide();
        $("#chanceCodeTips").text('');
        $(".contractCodeTips").text('');
    }

}

// 处理默认的费用分摊信息
function dealDefaultCostshareInfo(rowNum){
    var payBusinessType = $("#payForBusinessMain").find("option:selected");
    payBusinessType = payBusinessType.val();
    if(rowNum != undefined){
        dealDefaultCostshareDEtailInfo(payBusinessType,rowNum);
    }else{
        $("[id^='shareGrid_cmp_detailType']").each(function(i,item){
            var rowNum = $(item).parents("tr").attr("rownum");

            if($("#shareGrid_cmp_shareObjType"+rowNum).css("display") != 'none'){
                dealDefaultCostshareDEtailInfo(payBusinessType,rowNum);
            }
        });
    }
    if($('#costShareSelectDiv').length > 0){
        $('#costShareSelectDiv input[type="checkbox"]').each(function(i,item){
            $(item).attr('checked', false).next().attr('class', '');
        });
    }
}

// 根据ID获取费用明细的信息
function getCostTypeInfoById(id){
    var responseText = $.ajax({
        url: 'index1.php?model=finance_expense_costtype&action=getSingleCostTypeInfoForFee',
        data: {"costTypeId":id},
        type: "POST",
        async: false
    }).responseText;
    var result = {};
    if(responseText){
        result = eval("("+responseText+")");
    }
    return result;
}

// 处理默认的费用分摊明细信息
function dealDefaultCostshareDEtailInfo(payBusinessType,rowNum){
    var defaultSelectedCostTypeId = $("#defaultSelectedCostTypeId").val();
    // console.log(payBusinessType);
    switch (payBusinessType){
        case 'FKYWLX-03': // 投标保证金
            // 分摊基本信息处理
            var chanceCode = $("#chanceCode").val();
            $("#shareGrid_cmp_detailType"+rowNum).find("option[text='售前费用']").attr("selected",true);
            $("#shareGrid_cmp_detailType"+rowNum).change();
            $("#shareGrid_cmp_shareObjType"+rowNum).find("option[text='商机编号/费用承担人']").attr("selected",true);
            $("#shareGrid_cmp_shareObjType"+rowNum).change();
            $("#shareGrid_cmp_chanceCode"+rowNum).val(chanceCode);
            $("#shareGrid_cmp_chanceCode"+rowNum).blur();

            break;
        case 'FKYWLX-04': // 履约保证金
            // 分摊基本信息处理
            var contractCode = $("#contractCode").val();
            $("#shareGrid_cmp_detailType"+rowNum).find("option[text='合同项目费用']").attr("selected",true);
            $("#shareGrid_cmp_detailType"+rowNum).change();
            $("#shareGrid_cmp_shareObjType"+rowNum).find("option[text='合同']").attr("selected",true);
            $("#shareGrid_cmp_shareObjType"+rowNum).change();
            $("#shareGrid_cmp_contractCode"+rowNum).val(contractCode);
            $("#shareGrid_cmp_contractCode"+rowNum).blur();

            break;
        case 'FKYWLX-06': // 中标服务费
            // 分摊基本信息处理
            var contCode4Type = $("#contCode4Type").find("option:selected").val();
            switch (contCode4Type){
                case '商机':
                    var chanceCode = $("#chanceCode").val();
                    $("#shareGrid_cmp_detailType"+rowNum).find("option[text='售前费用']").attr("selected",true);
                    $("#shareGrid_cmp_detailType"+rowNum).change();
                    $("#shareGrid_cmp_shareObjType"+rowNum).find("option[text='商机编号/费用承担人']").attr("selected",true);
                    $("#shareGrid_cmp_shareObjType"+rowNum).change();
                    $("#shareGrid_cmp_chanceCode"+rowNum).val(chanceCode);
                    $("#shareGrid_cmp_chanceCode"+rowNum).blur();
                    break;
                case '销售合同':
                    var contractCode = $("#contractCode").val();
                    $("#shareGrid_cmp_detailType"+rowNum).find("option[text='合同项目费用']").attr("selected",true);
                    $("#shareGrid_cmp_detailType"+rowNum).change();
                    $("#shareGrid_cmp_shareObjType"+rowNum).find("option[text='合同']").attr("selected",true);
                    $("#shareGrid_cmp_shareObjType"+rowNum).change();
                    $("#shareGrid_cmp_contractCode"+rowNum).val(contractCode);
                    $("#shareGrid_cmp_contractCode"+rowNum).blur();
                    break;
            }

            break;
        case 'FKYWLX-07': // 标书制作费
            // 分摊基本信息处理
            var chanceCode = $("#chanceCode").val();
            $("#shareGrid_cmp_detailType"+rowNum).find("option[text='售前费用']").attr("selected",true);
            $("#shareGrid_cmp_detailType"+rowNum).change();
            $("#shareGrid_cmp_shareObjType"+rowNum).find("option[text='商机编号/费用承担人']").attr("selected",true);
            $("#shareGrid_cmp_shareObjType"+rowNum).change();
            $("#shareGrid_cmp_chanceCode"+rowNum).val(chanceCode);
            $("#shareGrid_cmp_chanceCode"+rowNum).blur();

            break;
    }

    // 分摊费用明细处理
    chkDefaultSelectedCostTypeId(rowNum);
}

// 检查并处理默认选中的费用明细
function chkDefaultSelectedCostTypeId(rowNum){
    var defaultId = $("#defaultSelectedCostTypeId").val();
    if(defaultId != '' && defaultId != undefined){
        var costTypeObj = getCostTypeInfoById(defaultId);// 默认选中的费用项
        $("#shareGrid_cmp_costTypeId"+rowNum).val(costTypeObj.CostTypeID);
        $("#shareGrid_cmp_costTypeName"+rowNum).val(costTypeObj.CostTypeName);
        $("#shareGrid_cmp_parentTypeId"+rowNum).val(costTypeObj.CostTypeParentID);
        $("#shareGrid_cmp_parentTypeName"+rowNum).val(costTypeObj.CostTypeParentName);
    }
    // else{
    //     $("#shareGrid_cmp_costTypeId"+rowNum).val('');
    //     $("#shareGrid_cmp_costTypeName"+rowNum).val('');
    //     $("#shareGrid_cmp_parentTypeId"+rowNum).val('');
    //     $("#shareGrid_cmp_parentTypeName"+rowNum).val('');
    // }
}

// 是否是银行保函选项更新
function changeisBankbackLetterRadio(){
    $("#backLetterEndDate").val('');
    var mainTypeSlted = $("#payForBusinessMain").find("option:selected");
    var mainTypeCode = mainTypeSlted.val();
    var backLetterEndDate = '';

    switch(mainTypeCode){
        case 'FKYWLX-03':
            // 投标保证金 -> 预计投标日期
            backLetterEndDate = ($("#prefBidDate").val() != undefined)? $("#prefBidDate").val() : '';
            break;
        case 'FKYWLX-04':
            // 履约保证金 -> 项目预计结束时间
            backLetterEndDate = ($("#projectPrefEndDate").val() != undefined)? $("#projectPrefEndDate").val() : '';
            break;
    }

    if($("#isBankbackLetterYes").attr("checked")){
        /*$(".backLetterEndDateWrap").show();
        $("#backLetterEndDate").addClass("validate[required]");*/
        $("#backLetterEndDate").val(backLetterEndDate);
    }
    /*else{
        $(".backLetterEndDateWrap").hide();
        $("#backLetterEndDate").removeClass("validate[required]");
    }*/
}
var updateBackLetterEndDate = function () {
    changeisBankbackLetterRadio();
}

function resetIsBankbackLetterRadio() {
    $("#backLetterEndDate").val('');
    $(".backLetterEndDateWrap").hide();
    $("input[type=radio][name=other\[isBankbackLetter\]]").each(function() {
        // if($(this).attr("id") == "isBankbackLetterNo"){
        //     $(this).attr("checked", "checked");
        // }else{
        //     $(this).removeAttr("checked");
        // }
        $("#backLetterEndDate").removeClass("validate[required]");
        $(this).removeAttr("checked");
    });
}

function chkChanceCode(chanceCode,isTypeFour){
    if(chanceCode != ''){
        var responseText = $.ajax({
            url: 'index1.php?model=projectmanagent_chance_chance&action=ajaxChanceByCode',
            data: {"chanceCode":chanceCode},
            type: "POST",
            async: false
        }).responseText;
        if(responseText){
            if(isTypeFour == 1){
                $("#chanceCodeTips4").text("");
                var responseObj = eval("("+responseText+")");
                $("#chanceId4").val(responseObj.id);
                $("#chanceCode").val($("#chanceCode4").val());
                $("#chanceId").val(responseObj.id);
                $("#chanceCodeTips4").text("");
            }else{
                $("#chanceCodeTips").text("");
                var responseObj = eval("("+responseText+")");
                $("#chanceId").val(responseObj.id);
                $("#chanceCodeTips").text("");
            }

            // 处理默认业务信息联动带出
            dealDefaultCostshareInfo();
        }else{
            if(isTypeFour == 1){
                $("#chanceCodeTips4").text("系统内不存在此商机编号 ["+$("#chanceCode4").val()+"] 。");
                $("#chanceCode4").next(".clear-trigger").trigger("click");
                $("#chanceId4").val('');
                $("#chanceCode4").val('');
                $("#chanceCode4").focus();
            }else{
                $("#chanceCodeTips").text("系统内不存在此商机编号 ["+$("#chanceCode").val()+"] 。");
                $("#chanceCode").next(".clear-trigger").trigger("click");
                $("#chanceId").val('');
                $("#chanceCode").val('');
                $("#chanceCode").focus();
            }
        }
    }else{
        $("#chanceId").val('');
        $("#chanceCode").val('');
        $("#chanceId4").val('');
        $("#chanceCode4").val('');
    }
}

function chkContractCode(contractCode,isNoProject){
    if(isNoProject == 1){// 把合同号放到正式的合同号input里面去
        $("#contractCode").val(contractCode);
    }

    if(contractCode != ''){
        var responseText = $.ajax({
            url: 'index1.php?model=engineering_project_esmproject&action=pageJson',
            data: {
                "contractCodeFullSearch":contractCode,
                "noLimit": '1'
            },
            type: "POST",
            async: false
        }).responseText;
        if(contractCode != ''){
            if(responseText){
                $(".contractCodeTips").text("");
                var responseObj = eval("("+responseText+")");

                // 获取项目最晚一次预计结束日期
                var maxPlanEndDate = '';
                var projectData = responseObj.collection;
                if(projectData != ''){
                    var isNoRelativeProject = false;
                    $("#contractId").val(projectData[0].contractId);

                    if(projectData.length > 0){
                        $.each(projectData,function(i,item){
                            var planEndDate = item.planEndDate;
                            if(planEndDate != ''){
                                if(maxPlanEndDate == ''){
                                    maxPlanEndDate = planEndDate;
                                }else{
                                    var nextDate = new Date(planEndDate.replace("-", "/").replace("-", "/"));
                                    var catchDate = new Date(maxPlanEndDate.replace("-", "/").replace("-", "/"));
                                    if(nextDate > catchDate){
                                        maxPlanEndDate = planEndDate;
                                    }
                                }
                            }
                        });
                    }else{
                        isNoRelativeProject = true;
                        maxPlanEndDate = projectData[0].planEndDate;
                    }

                    if(isNoRelativeProject){
                        $("#contractCode").next(".clear-trigger").trigger("click");
                        $("#contractId").val('');
                        $("#projectPrefEndDate").val('');
                        $(".contractCodeTips").text("此合同 ["+contractCode+"] 无关联的项目信息。");
                        $("#contractCode").val('');
                        $("#contractCode").focus();
                    }
                    // else if(maxPlanEndDate == "" && isNoProject != 1){
                    //     $("#contractCode").next(".clear-trigger").trigger("click");
                    //     $("#contractId").val('');
                    //     $("#projectPrefEndDate").val('');
                    //     $(".contractCodeTips").text("此合同 ["+contractCode+"] 无关联的项目预计结束日期。");
                    //     $("#contractCode").val('');
                    //     $("#contractCode").focus();
                    // }
                    else{
                        $("#projectPrefEndDate").val(maxPlanEndDate);
                        $(".contractCodeTips").text("");
                        if(isNoProject != 1){
                            updateBackLetterEndDate();
                        }else{
                            if(chkContractOnly()){
                                $("#contractCode").val(contractCode);
                            }else{
                                $("#contractCode4").next(".clear-trigger").trigger("click");
                                $("#contractId4").val('');
                                $("#contractCode").val('');
                                $("#contractId").val('');
                                $("#projectPrefEndDate").val('');
                                $(".contractCodeTips").text("该合同["+contractCode+"]还未通过审批或异常关闭，不是有效合同。");
                                $("#contractCode").val('');
                                $("#contractCode").focus();
                            }
                        }
                    }
                }else{
                    if(isNoProject != 1){
                        var tipsMsg = (!chkContractOnly())? "该合同["+contractCode+"]还未通过审批或异常关闭，不是有效合同。" : "该合同["+$("#contractCode").val()+"]还未建立相关工程项目。";

                        $("#contractCode").next(".clear-trigger").trigger("click");
                        $("#contractId").val('');
                        $("#projectPrefEndDate").val('');
                        $(".contractCodeTips").text(tipsMsg);
                        $("#contractCode").val('');
                        $("#contractCode").focus();
                    }else{
                        if(!chkContractOnly()){
                            $("#contractCode4").next(".clear-trigger").trigger("click");
                            $("#contractId4").val('');
                            $("#contractCode").val('');
                            $("#contractId").val('');
                            $("#projectPrefEndDate").val('');
                            $(".contractCodeTips").text("该合同["+contractCode+"]还未通过审批或异常关闭，不是有效合同。");
                            $("#contractCode").val('');
                            $("#contractCode").focus();
                        }else{
                            if(chkContractOnly()){
                                $(".contractCodeTips").text("");
                                $("#contractCode").val(contractCode);
                            }else{
                                $("#contractCode4").next(".clear-trigger").trigger("click");
                                $("#contractId4").val('');
                                $("#contractCode").val('');
                                $("#contractId").val('');
                                $("#projectPrefEndDate").val('');
                                $(".contractCodeTips").text("该合同["+contractCode+"]还未通过审批或异常关闭，不是有效合同。");
                                $("#contractCode").val('');
                                $("#contractCode").focus();
                            }
                        }
                    }
                }
            }else{
                if(isNoProject != 1){
                    $("#contractCode").next(".clear-trigger").trigger("click");
                    $("#contractId").val('');
                    $("#projectPrefEndDate").val('');
                    $(".contractCodeTips").text("无法匹配到相应记录。");
                    $("#contractCode").val('');
                    $("#contractCode").focus();
                }else{// 投标服务费选了一个没有任何关联项目的有效合同时,触发这下面的逻辑
                    if(!chkContractOnly()){
                        $("#contractCode4").next(".clear-trigger").trigger("click");
                        $("#contractId4").val('');
                        $("#contractCode").val('');
                        $("#contractId").val('');
                        $("#projectPrefEndDate").val('');
                        $(".contractCodeTips").text("该合同["+contractCode+"]还未通过审批或异常关闭，不是有效合同。");
                        $("#contractCode").val('');
                        $("#contractCode").focus();
                    }else{
                        if(chkContractOnly()){
                            $(".contractCodeTips").text("");
                            $("#contractCode").val(contractCode);
                        }else{
                            $("#contractCode4").next(".clear-trigger").trigger("click");
                            $("#contractId4").val('');
                            $("#contractCode").val('');
                            $("#contractId").val('');
                            $("#projectPrefEndDate").val('');
                            $(".contractCodeTips").text("该合同["+contractCode+"]还未通过审批或异常关闭，不是有效合同。");
                            $("#contractCode").val('');
                            $("#contractCode").focus();
                        }
                    }
                }
            }
        }
    }else{
        $("#contractCode4").val('');
        $("#contractId4").val('');
        $("#contractCode").val('');
        $("#contractId").val('');
        $("#projectPrefEndDate").val('');
        $("#contractCode").val('');
    }

    $("#contractId4").val($("#contractId").val());

    // 处理默认业务信息联动带出
    dealDefaultCostshareInfo();
}

function chkContractOnly(){
    var responseText = $.ajax({
        url: 'index1.php?model=contract_contract_contract&action=ajaxGetContract',
        data: {"contractCode":$("#contractCode").val()},
        type: "POST",
        async: false
    }).responseText;
    var resultArr = (responseText == '')? '' : eval("("+responseText+")");
    if(resultArr != '' && resultArr.ExaStatus != '未审批' && resultArr.state != 7){
        return true;
    }else{
        return false;
    }
}

//显示付款申请信息
function showPayapplyInfo(thisObj, isInit) {
    if (thisObj.checked == true) {
        thisObj.value = 1;

        $(".payapplyInfo").show();

        //费用归属部门
        $("#feeDeptName").yxselect_dept({
            hiddenId: 'feeDeptId',
            unDeptFilter: ($('#unDeptFilter').val() != undefined)? $('#unDeptFilter').val() : ''
        });

        if (!isInit) {
            $("#feeDeptName").val($("#deptName").val());
            $("#feeDeptId").val($("#deptId").val());
        }

        setPayFoeBusinessValue();

        if ($("#otherFeeDeptName").val() && $("#id").length == 0) {
            $("#feeDeptName").val($("#otherFeeDeptName").val());
            $("#feeDeptId").val($("#otherFeeDeptId").val());
        }
    } else {
        thisObj.value = 0;
        $(".payapplyInfo").hide();
        //费用归属部门
        $("#feeDeptName").yxselect_dept('remove');
    }
}

//选择银行代扣后触发事件
function entrustFun(thisVal) {
    if (thisVal == '1') {
        if (confirm('选择已付款后，不再由出纳进行款项支付，确认选择吗？')) {
            $("#bank").val('已付款').attr('class', 'readOnlyTxtNormal').attr('readonly', true);
            $("#account").val('已付款').attr('class', 'readOnlyTxtNormal').attr('readonly', true);
        } else {
            $("#isEntrustNo").attr('checked', true);
            $("#bank").val('').attr('class', 'txt').attr('readonly', false);
            $("#account").val('').attr('class', 'txt').attr('readonly', false);
        }
    } else {
        $("#bank").val('').attr('class', 'txt').attr('readonly', false);
        $("#account").val('').attr('class', 'txt').attr('readonly', false);
    }
}

/**
 * 获取省份数组
 */
function getProvince() {
    var responseText = $.ajax({
        url: 'index1.php?model=system_procity_province&action=getProvinceNameArr',
        type: "POST",
        async: false
    }).responseText;
    return eval("(" + responseText + ")");
}

/**
 * 添加省份数组添加到下拉框
 */
function addDataToProvince(data, selectId) {
    var str = "";
    for (var i = 0, l = data.length; i < l; i++) {
        str += "<option title='" + data[i].text + "' value='" + data[i].value + "'>" + data[i].text + "</option>";
    }
    $("#" + selectId).append(str)
}

/**
 * 当省份改变时对，对esmproject[proCode]的title的值赋值给esmproject[proName]
 */
function setProName() {
    $('#proName').val($("#proCode").find("option:selected").attr("title"));
}

//新增时提交审批
function audit(thisType) {
    if (thisType == 'audit') {
        document.getElementById('form1').action = "?model=contract_other_other&action=add&act=audit";
    } else {
        document.getElementById('form1').action = "?model=contract_other_other&action=add";
    }
    if(checkForm()){
        $('#form1').submit();
    }
}

//编辑时提交审批
function auditEdit(thisType) {
    if (thisType == 'audit') {
        document.getElementById('form1').action = "?model=contract_other_other&action=edit&act=audit";
    } else {
        document.getElementById('form1').action = "?model=contract_other_other&action=edit";
    }
    if(checkForm()){
        $('#form1').submit();
    }
}

// 导入刷新 - 费用分摊导入刷新列表 - 不要删除
function costShareImportExcel(data) {
    $("#shareGrid").costShareGrid('importData', data);

    var pageAct = $("#pageAct").val();
    if(pageAct == 'edit' || pageAct == 'add'){
        // 至少保留一条数据,不得删除
        $("[id^='shareGrid_cmp_removeBn']").each(function(i,item){
            if(i > 0){
                $(item).show();
            }else{
                if($("[id^='shareGrid_cmp_removeBn']").length > 1){
                    $(item).show();
                }else{
                    $(item).hide();
                }
            }
        });
    }
}

$(function(){

    //加载 合同
    if($("#relativeContract").val() != undefined){
        $("#relativeContract").yxcombogrid_other({
            hiddenId : 'relativeContractId',
            searchName : 'docCode',
            isShowButton:false,
            isDown : false,
            gridOptions : {
                param : {'isSelf':'1','principalId' : $("#userId").val(),'ExaStatus' : '完成'},
                showcheckbox :false,
                event : {
                    'row_dblclick' : function(e,row,data) {
                        $("#province").val(data.proName);
                        $("#incomeUnitName").val(data.signCompanyName);
                        $("#incomeUnitId").val(data.signCompanyId);
                        $("#contractUnitName").val(data.signCompanyName);
                        $("#contractUnitId").val(data.signCompanyId);
                        $("#rObjCode").val(data.objCode);
                        $("#objType").val('KPRK-09');
                    }
                }
            }
        });
    }

    // 初始化商机编号组件
    if($("#chanceCode").val() != undefined){
        $("#chanceCode").yxcombogrid_chance({
            nameCol : 'chanceCode',
            hiddenId : 'chanceId',
            isDown : true,
            height : 250,
            isFocusoutCheck : false,
            gridOptions : {
                isTitle : true,
                param : {'prinvipalId':$("#userId").val()},
                event: {
                    row_dblclick : function(e, row, data) {
                        chkChanceCode(data.chanceCode);
                    }
                }
            },
            event : {
                'clear' : function() {
                    chkChanceCode("");
                }
            }
        });
    }

    //关联合同
    if($("#contractCode").val() != undefined){
        $("#contractCode").yxcombogrid_allcontract({
            hiddenId : 'contractId',
            nameCol : 'contractCode',
            isFocusoutCheck : false,
            gridOptions : {
                isTitle : true,
                param : {'prinvipalId' : $("#userId").val()},
                showcheckbox : false,
                event : {
                    'row_dblclick' : function(e, row, data) {
                        chkContractCode(data.contractCode);
                    }
                }
            }
        });
    }

    setCodeSelectBox('init');

    $("#contCode4Type").change(function(){
        setCodeSelectBox();
    });

    $("#contractCode").removeAttr("readonly");

    // updateBackLetterEndDate();

    $("#formDate").attr("title","本合同如在“期望付款日期”当天12:00完成审批，则系统当天将付款申请提交至出纳；超过12:00，将于次日提交至出纳。\n请关注合同审批完成时间。");
    $("#payDesc").attr("title","最多不能超过60个汉字。");
});

var setCodeSelectBox = function(act){
    var contCode4Type = $("#contCode4Type").find("option:selected").val();
    if($("#chanceCode4").val() != undefined){
        $("#chanceCode4").yxcombogrid_chance("remove").val("");
        $("#contractCode4").yxcombogrid_allcontract("remove").val("");
    }
    switch (contCode4Type){
        case '商机':
            $("#contractCode4").hide();
            $("#chanceCode4").show();
            // 初始化商机编号组件
            $("#chanceCode4").yxcombogrid_chance({
                hiddenId : 'chanceId4',
                nameCol : 'chanceCode',
                isDown : true,
                height : 250,
                isFocusoutCheck : false,
                gridOptions : {
                    isTitle : true,
                    param : {'prinvipalId':$("#userId").val()},
                    event: {
                        row_dblclick : function(e, row, data) {
                            chkChanceCode(data.chanceCode,1);
                        }
                    }
                },
                event : {
                    'clear' : function() {
                        chkChanceCode("",1);
                    }
                }
            });
            break;
        case '销售合同':
            $("#contractCode4").show();
            $("#chanceCode4").hide();
            // 关联合同 (投标服务费用)
            $("#contractCode4").yxcombogrid_allcontract({
                hiddenId : 'contractId4',
                nameCol : 'contractCode',
                isFocusoutCheck : false,
                gridOptions : {
                    isTitle : true,
                    param : {'prinvipalId' : $("#userId").val()},
                    showcheckbox : false,
                    event : {
                        'row_dblclick' : function(e, row, data) {
                            chkContractCode(data.contractCode,1);
                        }
                    }
                }
            });
            $("#contractCode4").removeAttr("readonly");
            break;
    }
    if(act != 'init'){
        $("#contractId4").val('');$("#contractCode4").val('');$("#contractId").val('');$("#contractCode").val('');$("#projectPrefEndDate").val('');
        $("#chanceId").val('');$("#chanceCode").val('');$("#chanceId4").val('');$("#chanceCode4").val('');
    }else{
        $("#contractCode4").val($("#contractCode").val());
        $("#chanceCode4").val($("#chanceCode").val());
    }
}