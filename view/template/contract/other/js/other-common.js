$(function () {
    var testVal = "hahaha";
    var fundType = $("#fundType").val();

    changeFundTypeClear(fundType);

    //��ʼ�����㷽ʽ
    changePayTypeFun();

    // �����ͬ���
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
 * �����ͬ���
 *
 * @param fromType // ʹ�ý����Դ:��data:��ʾ�������ݿ�����û��Զ���,Ĭ��Ϊ�ձ�ʾ����ʽ���㡿
 */
function calContractMoney(fromType) {
    var orderMoney = $("#orderMoney").val();
    var taxPoint = $("#taxPoint").val();
    if (orderMoney != "" && taxPoint != "") {
        var taxPointVal = accDiv(taxPoint, 100, 2);
        $("#taxPoint").attr('data-setVal',taxPoint);

        // ����ֶ������˲���˰���,�����ֶ�������Ϊ׼
        var moneyNotTax_re = new RegExp(",","g");
        var moneyNotTax_input = Number($('#moneyNoTax_v').val().replace(moneyNotTax_re, ""));

        var caled_moneyNoTax = accDiv(orderMoney, accAdd(1, taxPointVal, 2), 2);
        if(fromType == 'data'){
            caled_moneyNoTax = (moneyNotTax_input != caled_moneyNoTax)? moneyNotTax_input : caled_moneyNoTax;
        }
        setMoney('moneyNoTax', caled_moneyNoTax);
    }

    // �����̯���
    moneyChange();
}

//���㷽ʽ
function changePayTypeFun() {
    if ($("#payType").find("option:selected").attr("e1") == 1) {
        $("#bankNeed").show();
        $("#accountNeed").show();
    } else {
        $("#bankNeed").hide();
        $("#accountNeed").hide();
    }
}

//ѡ���ͬ��������ʱ��ֵǩԼ��˾
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

//ѡ���ӦǩԼ����
function changeFundTypeClear(fundType) {
    var fundTypeObj = $("#fundType");
    if (fundTypeObj.find("option:selected").length == 0){// ���ҳ��
        if(fundType == "KXXZB"){
            $(".payForBusinessMainTd").show();
        }
        return true;
    }

    //���ÿ�����������
    fundTypeObj = fundTypeObj.find("option:selected");
    if (fundTypeObj.find("option:selected").attr("e1") == 1) {
        $("#myspan").show();
    } else {
        $("#myspan").hide();
    }

    //��������
    $("#fundConditionShow").html(fundTypeObj.attr("e2"));
    $("#fundConditionRequired").remove();
    if(fundTypeObj.attr("e1") == 1){
        $("#fundConditionShow").parent("td").next("td").append('<span id="fundConditionRequired" class="red">[*]</span>');
    }

    //���ظ�������
    $("#forPay").hide();
    //�����ͬ������Ŀ����
    $("#projectInfo").hide();

    //��ѡ����
    $("#otherFeeDeptName").yxselect_dept('remove');

    // ���÷�̯
    if ($("#isShare").val() == "1") {
        //��ʾ���÷�̯��ϸ
        $("#shareGrid").costShareGrid('remove');
    }

    // ���ظ���ҵ������
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
            $("#fundTypeDesc").html('ǩԼ��˾��Ϣ�����ֶ���д�����Ǳ��������������ѡ��');
    }
}

//�տ����ͬ����
function incomeSet() {
    //������ʾ�ı�
    $("#fundTypeDesc").html('�տ����ͬ���Խ��� <span class="red">[ ��Ʊ ]</span> �� <span class="red">[ �տ� ]</span> ����');
    $("#orderMoneyNeed").show();
    $("#taxPointNeed").show();
    $("#moneyNoTaxNeed").show();
    $("#invoiceTypeNeed").show();
}

//�������ͬ����
function paySet() {
    //������ʾ�ı�
    $("#fundTypeDesc").html('�������ͬ���Խ��� <span class="red">[ ��Ʊ ]</span> �� <span class="red">[ ���� ]</span> ����');
    $("#orderMoneyNeed").show();
    $("#taxPointNeed").show();
    $("#moneyNoTaxNeed").show();
    $("#invoiceTypeNeed").show();

    //��������������Ϣ����
    $("#forPay").show();

    $(".payForBusinessMainTd").show();

    // ���÷�̯
    if ($("#isShare").val() == "1") {
        //��ʾ���÷�̯��ϸ
        loadShareGrid();
    }
}

var defaultChangeTime = 0;
// ��ȡ��Ʊ���Ͷ�Ӧ���ֶ�
function getMoneyKey(fromType) {
    // var invoiceTypeE3 = $("#invoiceType").find("option:selected").attr("e3");
    //
    // return invoiceTypeE3 && invoiceTypeE3 == "1" ? 'moneyNoTax' : 'orderMoney';
    // console.log($("#taxPoint").attr('title'));
    // ��ѡ��ķ�Ʊ���ʹ�����ͬ˰�ʲ���������˰�ʼ��㲻��˰��Ȼ������޸ĺ�ͬ˰�� PMS2460
    var invoiceTypeE1 = ($("#invoiceType").find("option:selected").attr("e1") == '')? 0 : parseInt($("#invoiceType").find("option:selected").attr("e1"));
    var invoiceTypeVal = $("#invoiceType").find("option:selected").val();
    invoiceTypeE1 = (invoiceTypeVal == 'ZZSPTFP' || invoiceTypeVal == '')? 0 : invoiceTypeE1;// ��ֵ˰��ͨ��Ʊ˰��ҲΪ0

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

// ��Ʊ���͸ı�ʱ�����¼� -- �˷���ͨ��jquery�󶨣�����ֱ����$(this)
var invoiceTypeChange = function () {
    $('#taxPoint').removeAttr('data-setVal');
    $("#shareGrid").costShareGrid('changeCountKey', getMoneyKey());
};

// ���ı�ʱ�����¼� -- �˷���ͨ��jquery�󶨣�����ֱ����$(this)
var moneyChange = function () {
    $("#shareGrid").costShareGrid('countShareMoney');
};

//�޿������ͬ����
function noneSet() {
    //������ʾ�ı�
    $("#fundTypeDesc").html('�����ͬ����ؿ������');
    $("#orderMoneyNeed").hide();
    $("#taxPointNeed").hide();
    $("#moneyNoTaxNeed").hide();
    $("#invoiceTypeNeed").hide();

    // ���÷�̯
    if ($("#isShare").val() == "1") {
        //��ʾ���÷�̯��ϸ
        loadShareGrid();
    }
}

//�������ͬѡ����Ŀ����ʱ��ʼ����Ŀ��Ӧѡ���
function changeProject() {
    //�����Ŀ��ʼ����Ϣ
    $("#projectName").yxcombogrid_esmproject("remove").yxcombogrid_esmproject("remove").val("");
    $("#projectCode").val("");
    $("#projectId").val("");
    $("#projectNeed").hide();

    //�������������
    changeProjectClear();
}

//��ʼ����Ŀѡ��� - ���������
function changeProjectClear() {
    //��ȡ��Ŀ����ֵ
    var $val = $("#projectType").find("option:selected").val();
    if ($val == "") return false;
    $("#projectNeed").show();
    if ($val == 'QTHTXMLX-01') {
        //�з���Ŀ��Ⱦ��
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
        //������Ŀ��Ⱦ
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

//����ѡ���ж�
function changeRadio() {
    //����������֤
    var uploadFileList = $("#uploadfileList").html();
    if (uploadFileList == "" || uploadFileList == "�����κθ���") {
        alert('�������ǰ��Ҫ�ϴ���ͬ����!');
        $("#isNeedStampNo").attr("checked", true);
        return false;
    }

    //��ʾ������
    if ($("#isNeedStampYes").attr("checked")) {
        $("#radioSpan").show();
        //��ֹ�ظ������������
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
                                alert('��ѡ�������͹�����˾����һ�¡�');
                                checkbox.removeAttr('checked');
                                row.removeClass('trSelected');
                                return false;
                            }else if($('#legalPersonUsername').val() != rowData.legalPersonUsername){
                                alert('��ӡ�¹�˾������Ϣ�в��죬����ϵ����Ա������������Ϣ��');
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

        //����������Ⱦ
        $("#stampType").yxcombogrid_stampconfig('remove').val('');

        $('#businessBelongId').val('');
        $('#legalPersonUsername').val('');
        $('#legalPersonName').val('');
    }
}

//����֤���� - ����ͬ�漰����ʱ,��Ҫ��д��������
function checkForm() {
    // ���ﱣ��һ�·�̯��ϸ
    if ($("#isShare").val() == "1" && !$('#shareGrid').costShareGrid('ajaxSave', true)) {
        return false;
    }

    var fundTypeObj = $("#fundType");

    // ����Ǹ������͵�,�ȸ��ݸ���ҵ�����ʹ���һ����Ӧ�ķ�����ϸ
    if (fundTypeObj.val() == 'KXXZB'){
        var mainTypeSlted = $("#payForBusinessMain").find("option:selected");
        var mainTypeCode = mainTypeSlted.val();

        if(mainTypeCode == "FKYWLX-0"){// ���ѡ���ˡ��ޡ��򽫷�����ϸ�г����˽�ֹѡ��ķ�������ID��ѡ�����
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

        }else if(mainTypeSlted.attr("e3") == 1){// �����Ƿ�Ϊ��������֤
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

    }

    if($("#isNeedRelativeContract").val() == 1 && $("#hasRelativeContract").val() == "1"){
        if(($("#relativeContractId").val() <= 0 || $("#relativeContractId").val() == '') || $("#relativeContract").val() == ''){
            var businessTypeName = $("#payForBusinessMain option[value='FKYWLX-06']").text();
            alert('����ҵ������Ϊ��'+businessTypeName+'�����������ͬ,��֤������������ͬ�ű���');
            $("#relativeContract").focus();
            return false;
        }
    }

    if (fundTypeObj.val() == "") {
        alert('��ѡ���ͬ�Ŀ�������');
        return false;
    }

    var contCode4Type = $("#contCode4Type").find("option:selected").val();
    if (fundTypeObj.val() == 'KXXZB') {
        var mainTypeSlted = $("#payForBusinessMain").find("option:selected");
        if(mainTypeSlted.val() == '' || $("#payForBusinessName").val() == ''){
            alert('��ѡ�񸶿�ҵ�����͡�');
            return false;
        }else if($("#EXT1").css("display") != 'none' && ($("#chanceId").val() == '' || $("#chanceCode").val() == '')){
            $("#chanceCode").focus();
            alert('�̻��Ų���Ϊ�ա�');
            return false;
        }else if(mainTypeSlted.val() != 'FKYWLX-07' && ($("#EXT1").css("display") != 'none' && $("#prefBidDate").val() == '')){
            alert('Ԥ��Ͷ�����ڲ���Ϊ�ա�');
            return false;
        }else if($("#EXT2").css("display") != 'none' && ($("#contractId").val() == '' || $("#contractCode").val() == '')){
            $("#contractId").val('');
            $("#contractCode").focus();
            alert('��Ŀ���ۺ�ͬ��Ų���Ϊ�ա�');
            return false;
        }else if($("#EXT4").css("display") != 'none' && contCode4Type == '���ۺ�ͬ' && ($("#contractId").val() == '' || $("#contractCode").val() == '')){
            $("#contractId").val('');
            $("#contractCode").val('');
            $("#contractId4").val('');
            $("#contractCode4").val('');
            $("#contractCode4").focus();
            alert('��Ŀ���ۺ�ͬ��Ų���Ϊ�ա�');
            return false;
        }else if($("#EXT4").css("display") != 'none' && contCode4Type == '�̻�' && ($("#chanceId").val() == '' || $("#chanceCode").val() == '')){
            $("#chanceId").val('');
            $("#chanceCode").val('');
            $("#chanceId4").val('');
            $("#chanceCode4").val('');
            $("#chanceCode4").focus();
            alert('�̻��Ų���Ϊ�ա�');
            return false;
        }
        // else if($("#EXT2").css("display") != 'none' && $("#projectPrefEndDate").val() == ''){
        //     alert('��ĿԤ�ƽ���ʱ�䲻��Ϊ�ա�');
        //     return false;
        // }
        else if($("#EXT3").css("display") != 'none' && $("#prefPayDate").val() == ''){
            alert('Ԥ�ƻؿ�ʱ�䲻��Ϊ�ա�');
            return false;
        }

        var isBankbackLetter = $("input[name='other[isBankbackLetter]']:checked").val();
        if($("#EXT1-2").css("display") != 'none' &&
            (isBankbackLetter == undefined || (isBankbackLetter != 1 && isBankbackLetter != 0))){
            $("#isBankbackLetterYes").focus();
            alert('��ѡ���Ƿ������б�����');
            return false;
        }
    }

    //���ݽ��
    var orderMoneyObj = $("#orderMoney");
    if (orderMoneyObj.val() == "" && fundTypeObj.val() != 'KXXZC') {
        alert('��ͬ����Ϊ��');
        return false;
    }

    if ($("#taxPoint").val() == "" && fundTypeObj.val() != 'KXXZC') {
        alert('��ͬ˰�ʲ���Ϊ��');
        return false;
    }

    var moneyNoTaxObj = $("#moneyNoTax");
    if (moneyNoTaxObj.val() == "" && fundTypeObj.val() != 'KXXZC') {
        alert('��ͬ���(����˰)����Ϊ��');
        return false;
    }

    var invoiceTypeObj = $("#invoiceType");
    if (invoiceTypeObj.val() == "" && fundTypeObj.val() != 'KXXZC') {
        alert('��ͬ��Ʊ���Ͳ���Ϊ��');
        return false;
    }

    //�������ʱ���
    if (fundTypeObj.find("option:selected").attr("e1") == 1) {
        if ($("#fundCondition").val() == "") {
            alert(fundTypeObj.find("option:selected").attr("e2") + '����������д');
            return false;
        }
    }

    //����
    if ($("#currency").val() == "") {
        alert('����д�������');
        return false;
    }

    var isNeedPayapplyObj = $("#isNeedPayapply");
    if (fundTypeObj.val() == 'KXXZB') {

        //���ù�������
        var feeDeptName = $("#otherFeeDeptName").val();
        if (feeDeptName == "") {
            alert('��ͬ�еķ��ù�������Ϊ��');
            return false;
        }

        //�����Ŀ���Ͳ�Ϊ�գ�����Ŀ��Ϣ�Ǳ���
        var projectType = $("#projectType").val();
        if (projectType != '') {
            //��Ŀ����
            if (projectType == "") {
                alert('��Ŀ���Ͳ���Ϊ��');
                return false;
            }

            //��Ŀ����
            var projectName = $("#projectName").val();
            if (projectName == "") {
                alert('��Ŀ���Ʋ���Ϊ��');
                return false;
            }
        }

        //��������
        if (isNeedPayapplyObj.length == 1 && isNeedPayapplyObj.attr("checked") == true) {
            // �Ƿ񿪾ݷ�Ʊ
            var isInvoice = $("input[name='other[payapply][isInvoice]']:checked").val();
            if(isInvoice == undefined || (isInvoice != 1 && isInvoice != 0)){
                alert('��ȷ���Ƿ񿪾ݷ�Ʊ');
                return false;
            }

            //������
            var applyMoney = $("#applyMoney").val();
            if (applyMoney == 0 || applyMoney == "") {
                alert('�����������Ϊ0���');
                return false;
            }

            //��������
            var formDate = $("#formDate").val();
            if (formDate == "") {
                alert('�����������ڲ���Ϊ��');
                return false;
            }

            //���ù�������
            if ($("#feeDeptName").val() == "") {
                alert('���ù������Ų���Ϊ��');
                return false;
            }

            var innerPayType = $("#payType").find("option:selected").attr("e1");
            if (innerPayType == 1) {

                //�տ�����
                var bank = $("#bank").val();
                if (bank == "") {
                    alert('�տ����в���Ϊ��');
                    return false;
                }

                //�տ��˺�
                var account = $("#account").val();
                if (account == "") {
                    alert('�տ��˺Ų���Ϊ��');
                    return false;
                }
            }

            //����ص�
            if ($("#place").val() == "") {
                alert('����д����ص�(ʡ/��)');
                return false;
            }

            // ����˵��
            var payDesc = $("#payDesc").val();
            if (payDesc == "") {
                alert('����д����˵��');
                return false;
            }
            payDesc = payDesc.replace(/\n/g, '').replace(/\r/g, '');
            if(payDesc.length > 60){
                alert('����˵����಻�ܳ���60�����֡�');
                $("#payDesc").focus();
                return false;
            }


            //�տλ
            if ($("#payee").val() == "") {
                alert('����д�տλ');
                return false;
            }

            //������;
            var remark = strTrim($("#remark").val());
            if (remark == "") {
                alert('������;����Ϊ��');
                return false;

            } else {
                if (remark.length > 10) {
                    alert('�뽫������;������Ϣ������10���ֻ�10��������,��ǰ����Ϊ' + remark.length + " ����");
                    return false;
                }
            }
            
            //���������ͬ��һ��ʱ��֤
            if(applyMoney * 1 != orderMoneyObj.val() * 1){
                if (orderMoneyObj.val() * 1 < applyMoney * 1) {
                    alert('��ͬ����С�ڸ���������');
                    $("#applyMoney").val("");
                    $("#applyMoney_v").val("");
                	return false;
                }
            	return confirm("���������ͬ��һ�£�ȷ���ύ��");
            }
        }
    } else {
        //������Ǹ������ͣ�ʧЧ
        if (isNeedPayapplyObj.length == 1) isNeedPayapplyObj.attr('checked', false);
    }

    if (fundTypeObj.val() == 'KXXZB' || fundTypeObj.val() == 'KXXZC') {
        // ���÷�̯
        if ($("#isShare").val() == "1") {
            //��ʾ���÷�̯��ϸ
            if ($("#shareGrid").costShareGrid('checkForm', $("#" + getMoneyKey('data')).val(), false) == false) {
                return false;
            }
        }
    }

    if ($("#isNeedStampYes").attr('checked') == true) {
        if ($("#stampType").val() == "") {
            alert('�������ͱ�����д');
            return false;
        }

        var upList = strTrim($("#uploadfileList").html());
        //����������֤
        if (upList == "" || upList == "�����κθ���") {
            alert('�������ǰ��Ҫ�ϴ���ͬ����!');
            return false;
        }
    }

    return true;
}

//����ʱ�ύ���� -- ��������
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
/********************** ������ͬ����� ********************/

//�ı��ͬ����,�����������ں�ͬ�����������
function checkApplyMoney() {
    var orderMoneyObj = $("#orderMoney");
    var applyMoneyObj = $("#applyMoney");

    if (orderMoneyObj.val() * 1 < applyMoneyObj.val() * 1) {
        alert('��ͬ����С�ڸ���������');
        applyMoneyObj.val("");
        $("#applyMoney_v").val("");
    }
}

// ��ȡ������ҵ������
function setPayFoeBusinessValue(isChange,isEdit){
    if(isChange != 'fundType'){
        var mainTypeSlted = $("#payForBusinessMain").find("option:selected");
        var mainTypeCode = mainTypeSlted.val();
        var mainTypeName = (mainTypeCode == '')? '' : mainTypeSlted.text();
        $("#payForBusinessName").val(mainTypeName);
        $("#payForBusinessShow").val(mainTypeName);
        $("#payForBusiness").val(mainTypeCode);

        if(mainTypeCode == "FKYWLX-0"){// ���ѡ���ˡ��ޡ��򽫷�����ϸ�г����˽�ֹѡ��ķ�������ID��ѡ�����
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

            // ��ҵ������������ʾ��Ӧ�ı��ֶ�
            var extCode = mainTypeSlted.attr("e1");
            var defaultCostTypeId = mainTypeSlted.attr("e2");
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

// ����Ĭ�ϵķ��÷�̯��Ϣ
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

// ����ID��ȡ������ϸ����Ϣ
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

// ����Ĭ�ϵķ��÷�̯��ϸ��Ϣ
function dealDefaultCostshareDEtailInfo(payBusinessType,rowNum){
    var defaultSelectedCostTypeId = $("#defaultSelectedCostTypeId").val();
    // console.log(payBusinessType);
    switch (payBusinessType){
        case 'FKYWLX-03': // Ͷ�걣֤��
            // ��̯������Ϣ����
            var chanceCode = $("#chanceCode").val();
            $("#shareGrid_cmp_detailType"+rowNum).find("option[text='��ǰ����']").attr("selected",true);
            $("#shareGrid_cmp_detailType"+rowNum).change();
            $("#shareGrid_cmp_shareObjType"+rowNum).find("option[text='�̻����/���óе���']").attr("selected",true);
            $("#shareGrid_cmp_shareObjType"+rowNum).change();
            $("#shareGrid_cmp_chanceCode"+rowNum).val(chanceCode);
            $("#shareGrid_cmp_chanceCode"+rowNum).blur();

            break;
        case 'FKYWLX-04': // ��Լ��֤��
            // ��̯������Ϣ����
            var contractCode = $("#contractCode").val();
            $("#shareGrid_cmp_detailType"+rowNum).find("option[text='��ͬ��Ŀ����']").attr("selected",true);
            $("#shareGrid_cmp_detailType"+rowNum).change();
            $("#shareGrid_cmp_shareObjType"+rowNum).find("option[text='��ͬ']").attr("selected",true);
            $("#shareGrid_cmp_shareObjType"+rowNum).change();
            $("#shareGrid_cmp_contractCode"+rowNum).val(contractCode);
            $("#shareGrid_cmp_contractCode"+rowNum).blur();

            break;
        case 'FKYWLX-06': // �б�����
            // ��̯������Ϣ����
            var contCode4Type = $("#contCode4Type").find("option:selected").val();
            switch (contCode4Type){
                case '�̻�':
                    var chanceCode = $("#chanceCode").val();
                    $("#shareGrid_cmp_detailType"+rowNum).find("option[text='��ǰ����']").attr("selected",true);
                    $("#shareGrid_cmp_detailType"+rowNum).change();
                    $("#shareGrid_cmp_shareObjType"+rowNum).find("option[text='�̻����/���óе���']").attr("selected",true);
                    $("#shareGrid_cmp_shareObjType"+rowNum).change();
                    $("#shareGrid_cmp_chanceCode"+rowNum).val(chanceCode);
                    $("#shareGrid_cmp_chanceCode"+rowNum).blur();
                    break;
                case '���ۺ�ͬ':
                    var contractCode = $("#contractCode").val();
                    $("#shareGrid_cmp_detailType"+rowNum).find("option[text='��ͬ��Ŀ����']").attr("selected",true);
                    $("#shareGrid_cmp_detailType"+rowNum).change();
                    $("#shareGrid_cmp_shareObjType"+rowNum).find("option[text='��ͬ']").attr("selected",true);
                    $("#shareGrid_cmp_shareObjType"+rowNum).change();
                    $("#shareGrid_cmp_contractCode"+rowNum).val(contractCode);
                    $("#shareGrid_cmp_contractCode"+rowNum).blur();
                    break;
            }

            break;
        case 'FKYWLX-07': // ����������
            // ��̯������Ϣ����
            var chanceCode = $("#chanceCode").val();
            $("#shareGrid_cmp_detailType"+rowNum).find("option[text='��ǰ����']").attr("selected",true);
            $("#shareGrid_cmp_detailType"+rowNum).change();
            $("#shareGrid_cmp_shareObjType"+rowNum).find("option[text='�̻����/���óе���']").attr("selected",true);
            $("#shareGrid_cmp_shareObjType"+rowNum).change();
            $("#shareGrid_cmp_chanceCode"+rowNum).val(chanceCode);
            $("#shareGrid_cmp_chanceCode"+rowNum).blur();

            break;
    }

    // ��̯������ϸ����
    chkDefaultSelectedCostTypeId(rowNum);
}

// ��鲢����Ĭ��ѡ�еķ�����ϸ
function chkDefaultSelectedCostTypeId(rowNum){
    var defaultId = $("#defaultSelectedCostTypeId").val();
    if(defaultId != '' && defaultId != undefined){
        var costTypeObj = getCostTypeInfoById(defaultId);// Ĭ��ѡ�еķ�����
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

// �Ƿ������б���ѡ�����
function changeisBankbackLetterRadio(){
    $("#backLetterEndDate").val('');
    var mainTypeSlted = $("#payForBusinessMain").find("option:selected");
    var mainTypeCode = mainTypeSlted.val();
    var backLetterEndDate = '';

    switch(mainTypeCode){
        case 'FKYWLX-03':
            // Ͷ�걣֤�� -> Ԥ��Ͷ������
            backLetterEndDate = ($("#prefBidDate").val() != undefined)? $("#prefBidDate").val() : '';
            break;
        case 'FKYWLX-04':
            // ��Լ��֤�� -> ��ĿԤ�ƽ���ʱ��
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

            // ����Ĭ��ҵ����Ϣ��������
            dealDefaultCostshareInfo();
        }else{
            if(isTypeFour == 1){
                $("#chanceCodeTips4").text("ϵͳ�ڲ����ڴ��̻���� ["+$("#chanceCode4").val()+"] ��");
                $("#chanceCode4").next(".clear-trigger").trigger("click");
                $("#chanceId4").val('');
                $("#chanceCode4").val('');
                $("#chanceCode4").focus();
            }else{
                $("#chanceCodeTips").text("ϵͳ�ڲ����ڴ��̻���� ["+$("#chanceCode").val()+"] ��");
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
    if(isNoProject == 1){// �Ѻ�ͬ�ŷŵ���ʽ�ĺ�ͬ��input����ȥ
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

                // ��ȡ��Ŀ����һ��Ԥ�ƽ�������
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
                        $(".contractCodeTips").text("�˺�ͬ ["+contractCode+"] �޹�������Ŀ��Ϣ��");
                        $("#contractCode").val('');
                        $("#contractCode").focus();
                    }
                    // else if(maxPlanEndDate == "" && isNoProject != 1){
                    //     $("#contractCode").next(".clear-trigger").trigger("click");
                    //     $("#contractId").val('');
                    //     $("#projectPrefEndDate").val('');
                    //     $(".contractCodeTips").text("�˺�ͬ ["+contractCode+"] �޹�������ĿԤ�ƽ������ڡ�");
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
                                $(".contractCodeTips").text("�ú�ͬ["+contractCode+"]��δͨ���������쳣�رգ�������Ч��ͬ��");
                                $("#contractCode").val('');
                                $("#contractCode").focus();
                            }
                        }
                    }
                }else{
                    if(isNoProject != 1){
                        var tipsMsg = (!chkContractOnly())? "�ú�ͬ["+contractCode+"]��δͨ���������쳣�رգ�������Ч��ͬ��" : "�ú�ͬ["+$("#contractCode").val()+"]��δ������ع�����Ŀ��";

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
                            $(".contractCodeTips").text("�ú�ͬ["+contractCode+"]��δͨ���������쳣�رգ�������Ч��ͬ��");
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
                                $(".contractCodeTips").text("�ú�ͬ["+contractCode+"]��δͨ���������쳣�رգ�������Ч��ͬ��");
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
                    $(".contractCodeTips").text("�޷�ƥ�䵽��Ӧ��¼��");
                    $("#contractCode").val('');
                    $("#contractCode").focus();
                }else{// Ͷ������ѡ��һ��û���κι�����Ŀ����Ч��ͬʱ,������������߼�
                    if(!chkContractOnly()){
                        $("#contractCode4").next(".clear-trigger").trigger("click");
                        $("#contractId4").val('');
                        $("#contractCode").val('');
                        $("#contractId").val('');
                        $("#projectPrefEndDate").val('');
                        $(".contractCodeTips").text("�ú�ͬ["+contractCode+"]��δͨ���������쳣�رգ�������Ч��ͬ��");
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
                            $(".contractCodeTips").text("�ú�ͬ["+contractCode+"]��δͨ���������쳣�رգ�������Ч��ͬ��");
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

    // ����Ĭ��ҵ����Ϣ��������
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
    if(resultArr != '' && resultArr.ExaStatus != 'δ����' && resultArr.state != 7){
        return true;
    }else{
        return false;
    }
}

//��ʾ����������Ϣ
function showPayapplyInfo(thisObj, isInit) {
    if (thisObj.checked == true) {
        thisObj.value = 1;

        $(".payapplyInfo").show();

        //���ù�������
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
        //���ù�������
        $("#feeDeptName").yxselect_dept('remove');
    }
}

//ѡ�����д��ۺ󴥷��¼�
function entrustFun(thisVal) {
    if (thisVal == '1') {
        if (confirm('ѡ���Ѹ���󣬲����ɳ��ɽ��п���֧����ȷ��ѡ����')) {
            $("#bank").val('�Ѹ���').attr('class', 'readOnlyTxtNormal').attr('readonly', true);
            $("#account").val('�Ѹ���').attr('class', 'readOnlyTxtNormal').attr('readonly', true);
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
 * ��ȡʡ������
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
 * ���ʡ��������ӵ�������
 */
function addDataToProvince(data, selectId) {
    var str = "";
    for (var i = 0, l = data.length; i < l; i++) {
        str += "<option title='" + data[i].text + "' value='" + data[i].value + "'>" + data[i].text + "</option>";
    }
    $("#" + selectId).append(str)
}

/**
 * ��ʡ�ݸı�ʱ�ԣ���esmproject[proCode]��title��ֵ��ֵ��esmproject[proName]
 */
function setProName() {
    $('#proName').val($("#proCode").find("option:selected").attr("title"));
}

//����ʱ�ύ����
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

//�༭ʱ�ύ����
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

// ����ˢ�� - ���÷�̯����ˢ���б� - ��Ҫɾ��
function costShareImportExcel(data) {
    $("#shareGrid").costShareGrid('importData', data);

    var pageAct = $("#pageAct").val();
    if(pageAct == 'edit' || pageAct == 'add'){
        // ���ٱ���һ������,����ɾ��
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

    //���� ��ͬ
    if($("#relativeContract").val() != undefined){
        $("#relativeContract").yxcombogrid_other({
            hiddenId : 'relativeContractId',
            searchName : 'docCode',
            isShowButton:false,
            isDown : false,
            gridOptions : {
                param : {'isSelf':'1','principalId' : $("#userId").val(),'ExaStatus' : '���'},
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

    // ��ʼ���̻�������
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

    //������ͬ
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

    $("#formDate").attr("title","����ͬ���ڡ������������ڡ�����12:00�����������ϵͳ���콫���������ύ�����ɣ�����12:00�����ڴ����ύ�����ɡ�\n���ע��ͬ�������ʱ�䡣");
    $("#payDesc").attr("title","��಻�ܳ���60�����֡�");
});

var setCodeSelectBox = function(act){
    var contCode4Type = $("#contCode4Type").find("option:selected").val();
    if($("#chanceCode4").val() != undefined){
        $("#chanceCode4").yxcombogrid_chance("remove").val("");
        $("#contractCode4").yxcombogrid_allcontract("remove").val("");
    }
    switch (contCode4Type){
        case '�̻�':
            $("#contractCode4").hide();
            $("#chanceCode4").show();
            // ��ʼ���̻�������
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
        case '���ۺ�ͬ':
            $("#contractCode4").show();
            $("#chanceCode4").hide();
            // ������ͬ (Ͷ��������)
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