//2012-12-27����
/**
 * �ж��ַ�������
 * @return {Boolean}
 */
function strlength(obj) {
    if (obj.length > 300) {
        alert("����������ݹ���!")
    }
}
//��ʼʱ�������ʱ�����֤
function timeCheck($t) {
    var s = plusDateInfo('beginDate', 'endDate');
    if (s < 0) {
        alert("��ʼʱ�䲻�ܱȽ���ʱ����");
        $t.value = "";
        return false;
    }
}
/**
 * ��Ʊ���Ϳ���
 * @return {Boolean}
 */
function Kcontrol(obj) {
    var KPLX = $("input[name='contract[" + obj + "]']:checked").val();
    var objHide = obj + "Hide";
    var objMoney = obj + "Money";
    if (KPLX == "on") {
        document.getElementById(objHide).style.display = "";
    } else {
        document.getElementById(objHide).style.display = "none";
        $("#" + objMoney + "").val("");
        $("#" + objMoney + "_v").val("");
    }
}

/**
 *�¿�Ʊ���Ϳ���
 * @param {} obj
 */
function isCheckType(obj) {
    var isChecked = $("#" + obj).is(':checked');
    if (isChecked) {
        $("#" + obj + "Hide").show();
    } else {
        $("#" + obj + "Hide").hide();
        $("#" + obj + "Money").val("");
        $("#" + obj + "Money_v").val("");
    }
}

/**
 * �ж��¿�Ʊ����ѡ��������
 * @param {} obj
 */
function checkConMoney() {
    var dataCode = $("#dataCode").val();
    var itemArr = dataCode.split(',');
    var itemLength = itemArr.length;
    var rate = $("#rate").val();
    var currency = $("#currency").val();
    var allCost = 0;
    var contractMoney = $("#contractMoney_v").val();

    for (i = 0; i < itemLength; i++) {
        if ($("#" + itemArr[i]).is(":checked")) {
            if ($("#" + itemArr[i] + "Money_v").val() == "") {
                $("#" + itemArr[i] + "Money_v").addClass("validate[required]");
            } else {
                allCost = accAdd(allCost, $("#" + itemArr[i] + "Money_v").val(), 2);
                $("#" + itemArr[i] + "Money_v").removeClass("validate[required]");
            }
        }
    }
    //    if (currency == '�����') {
    setMoney("contractMoney", allCost);
    //    } else {
    //        setMoney("contractMoneyCur", allCost);
    //    }
    conversion();
}


/**
 * ��ͬ������������
 * @return {Boolean}
 */
function paymentControl(obj) {
    var flag = $("#" + obj).attr('checked');
    if (obj == 'otherpaymentterm') {
        otherpaymenttermList(flag);
    } else if (obj == 'progresspayment') {
        paymentList(flag);
    } else {
        var objHide = obj + "Hide";
        var objA = obj + "A";
        if (flag == true) {
            $("#" + objHide).show();
        } else {
            $("#" + objHide).hide();
            if (objHide == 'otherpaymenttermHide') {
                $("#otherpaymenttermA").val("");
                $("#otherpaymentA").val("");
            } else {
                $("#" + objA + "").val("");
            }
        }
    }
}
//�����ȸ����б�
function paymentList(flag) {
    var listObj = $("#progresspaymentList");
    var str = '<tr id="paymentTR1">' +
        '<td ><input class="rimless_text_normal" id="protem1"  name="contract[progresspaymentterm][1]"/></td>' +
        '<td><input class="rimless_text_short realNum" id="progresspaymentPro1" name="contract[progresspayment][1]" />%</td>' +
        '<td><img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_paymentlist();"/></td>' +
        '</tr>';
    if (flag == true) {
        listObj.html(str);
    } else {
        listObj.html("");
    }

}
function add_paymentlist() {
    var listObj = $("#progresspaymentList");
    var listLength = $("#progresspaymentList tr").length;
    var i = listLength + 1;
    var str = '<tr id="paymentTR' + i + '">' +
        '<td><input class="rimless_text_normal" id="protem' + i + '"  name="contract[progresspaymentterm][' + i + ']"/></td>' +
        '<td><input class="rimless_text_short realNum" id="progresspaymentPro' + i + '" name="contract[progresspayment][' + i + ']"/>%</td>' +
        '<td><img style="cursor:pointer;" src="images/removeline.png" title="ɾ����" onclick="delete_paymentlist(this);"/></td>' +
        '</tr>';
    listObj.append(str);

}
//������������
function otherpaymenttermList(flag) {
    var listObj = $("#otherpaymenttermList");
    var str = '<tr id="otherTR1">' +
        '<td><input class="rimless_text_normal" id="ohterprotem1"  name="contract[otherpaymentterm][1]"/></td>' +
        '<td><input class="rimless_text_short realNum" id="otherpaymentPro1" name="contract[otherpayment][1]"/>%</td>' +
        '<td><img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_otherpaymenttermList();"/></td>' +
        '</tr>';
    if (flag == true) {
        listObj.html(str);
    } else {
        listObj.html("");
    }
}
function add_otherpaymenttermList() {
    var listObj = $("#otherpaymenttermList");
    var listLength = $("#otherpaymenttermList tr").length;
    var i = listLength + 1;
    var str = '<tr id="otherTR' + i + '">' +
        '<td><input class="rimless_text_normal" id="ohterprotem' + i + '"  name="contract[otherpaymentterm][' + i + ']"/></td>' +
        '<td><input class="rimless_text_short realNum" id="otherpaymentPro' + i + '" name="contract[otherpayment][' + i + ']"/>%</td>' +
        '<td><img style="cursor:pointer;" src="images/removeline.png" title="ɾ����" onclick="delete_paymentlist(this);"/></td>' +
        '</tr>';
    listObj.append(str);

}
//ɾ����
function delete_paymentlist(obj) {
    if (confirm('ȷ��Ҫɾ�����У�')) {
        $(obj).parent().parent().remove();
    }
}

//��ͬ����

/**
 * �Ƿ���±����ж�
 */
function changeRadio() {
    //����������֤
    if ($("#uploadfileList2").html() == "" || $("#uploadfileList2").html() == "�����κθ���") {
        alert('�������ǰ��Ҫ�ϴ���ͬ�ı�!');
        $("#isNeedStampNo").attr("checked", true);
        //����������Ⱦ
        $("#radioSpan").attr('style', "color:black");
        var stampTypeObj = $("#stampType");
        stampTypeObj.yxcombogrid_stampconfig('remove');
        stampTypeObj.val('');
        return false;
    }
    //��ʾ������
    if ($("#isNeedStampYes").attr("checked")) {
        $("#radioSpan").attr('style', "color:blue");
        //��ֹ�ظ������������
        if ($("#stampType").yxcombogrid_stampconfig('getIsRender') == true) return false;

        //����������Ⱦ
        $("#stampType").yxcombogrid_stampconfig({
            hiddenId: 'stampType',
            height: 250,
            gridOptions: {
                isTitle: true,
                showcheckbox: true
            }
        });
    } else {
        $("#radioSpan").attr('style', "color:black");

        //����������Ⱦ
        var stampTypeObj = $("#stampType");
        stampTypeObj.yxcombogrid_stampconfig('remove');
        stampTypeObj.val('');
    }
}

/**
 * ���¸���ѡ��
 */
function restampRadio(thisVal) {
    if (thisVal == 1) {
        $(".restamp").show();
        $(".restampIn").attr('disabled', false);
    } else {
        $(".restamp").hide();
        $(".restampIn").attr('disabled', true);
    }
}

// ���������ֵ���
$(function () {
    // ��ͬ����
    contractTypeArr = getData('HTLX');
    addDataToSelect(contractTypeArr, 'contractType');
    // ��������
    fileTypeArr = getData('FJLX');
    addDataToSelect(fileTypeArr, 'fileType');
    // �ͻ�����
    customerTypeArr = getData('KHLX');
    addDataToSelect(customerTypeArr, 'customerType');
    // ǩԼ����
    signSubjectTypeArr = getData('QYZT');
    addDataToSelect(signSubjectTypeArr, 'signSubject');
    // �������
    moduleArr = getData('HTBK');
    addDataToSelect(moduleArr, 'module');

});

//������ת��������
$(function () {
    var ids = $("#ids").val();
    if (ids != '') {
        var returnValue = $.ajax({
            type: 'POST',
            url: "?model=projectmanagent_borrow_borrow&action=getBorrowequInfo",
            data: {
                ids: ids
            },
            async: false,
            success: function (data) {
            }
        }).responseText;
        returnValue = eval("(" + returnValue + ")");
        if (returnValue) {
            var g = $("#borrowConEquInfo").data("yxeditgrid");
            //ѭ���������
            for (var i = 0; i < returnValue.length; i++) {
                var canExeNum = returnValue[i].executedNum - returnValue[i].backNum;
                outJson = {
                    "productId": returnValue[i].productId,
                    "productCode": returnValue[i].productNo,
                    "productName": returnValue[i].productName,
                    "productModel": returnValue[i].productModel,
                    "number": canExeNum,
                    "price": returnValue[i].price,
                    "money": returnValue[i].price * canExeNum,
                    "warrantyPeriod": returnValue[i].warrantyPeriod,
                    "toBorrowId": returnValue[i].borrowId,
                    "toBorrowequId": returnValue[i].id,
                    "conProductNameOriginal": returnValue[i].conProductName
                };
                //��������
                $("#customerIdtest").val(returnValue[i].customerId);
                drawCustomerInfo();
                g.addRow(i, outJson);
                //����������Ʒ��Ϣ��Ĭ��ѡ�С�onlyProductIdΪ�գ�ѡ���Ʒ��Ϣ������Ⱦ��
                if (typeof (returnValue[i].conProductId) != 'undefined') {
                    var obj = document.getElementById("borrowConEquInfo_cmp_onlyProductId" + i);
                    obj.add(new Option("" + returnValue[i].conProductName + "", "" + '' + "", true, true));
                }

                // ������ִ������,����Ӵ���������ı䶯����
                if (typeof (returnValue[i].executedNum) != 'undefined') {
                    var executedNum = returnValue[i].executedNum;
                    var exeNum = returnValue[i].executedNum - returnValue[i].backNum;
                    var obj = $("#borrowConEquInfo_cmp_number" + i);
                    obj.after("<input type='hidden' id='borrowConEquInfo_cmp_executedNum" + i + "' value='" + returnValue[i].executedNum + "'/>");
                    obj.change(function () {
                        var inputVal = $(this).val();
                        if (isNaN(inputVal) || parseInt(inputVal) <= 0) {
                            alert("���������0��������");
                            $(this).val(exeNum);
                        } else if (parseInt(inputVal) > parseInt(exeNum)) {
                            alert("ת��������������ڿ�����������Χ�ڡ�");
                            $(this).val(exeNum);
                        }
                    });
                }
            }
        }
    }

});
//����ID ��ȡ��Ⱦҳ��ͻ���Ϣ
function drawCustomerInfo() {
    var customerIdtest = $("#customerIdtest").val();
    var cusInfo = $.ajax({
        type: 'POST',
        url: "?model=customer_customer_customer&action=getCusInfo",
        data: {
            id: customerIdtest
        },
        async: false,
        success: function (data) {
        }
    }).responseText;
    cusInfo = eval("(" + cusInfo + ")");

    if (cusInfo != false) {
        $("#customerName").val(cusInfo[0].Name);
        $("#customerId").val(cusInfo[0].id);
        $("#customerType").val(cusInfo[0].TypeOne);
        $("#customerTypeName").val(cusInfo[0].TypeOneName);

        // �ͻ���ַ
        var linkmanCmp = $("#linkmanListInfo").yxeditgrid(
            "getCmpByCol", "linkmanName");
        linkmanCmp.yxcombogrid_linkman("remove");
        $("#linkmanListInfo").yxeditgrid('remove');
        linkmanList(cusInfo[0].id);
    }

    // ���ؿͻ�����(����ͻ�����û���س��������� ID2232 2016-11-18)
    if (cusInfo != false && cusInfo[0].TypeOne != '') {
        customerTypeArr = getData('KHLX');
        addDataToSelect(customerTypeArr, 'customerType', cusInfo[0].TypeOne);
    } else {
        customerTypeArr = getData('KHLX');
        addDataToSelect(customerTypeArr, 'customerType');
    }

    if (cusInfo != false) {
        $("#province_Id").val(cusInfo[0].ProvId);// ����ʡ��Id
        $("#province").val(cusInfo[0].ProvId);// ����ʡ��Id
        $("#province").trigger("change");
        $("#provinceName").val(cusInfo[0].Prov);// ����ʡ��
        $("#city_Id").val(cusInfo[0].CityId);// ����ID
        $("#city").val(cusInfo[0].CityId);// ����ID
        $("#cityName").val(cusInfo[0].City);// ��������
        $("#customerId").val(cusInfo[0].id);
        //$("#areaPrincipal").val(cusInfo[0].AreaLeader);// ��������
        //$("#areaPrincipalId").val(cusInfo[0].AreaLeaderId);// ��������Id
        // $("#areaPrincipal").val(cusInfo[0].AreaLeaderNow);// ������������ (ID2232 2016-11-18)
        // $("#areaPrincipalId").val(cusInfo[0].AreaLeaderIdNow);// ������������Id  (ID2232 2016-11-18)
        // $("#areaName").val(cusInfo[0].AreaName);// ��ͬ��������
        // $("#areaCode").val(cusInfo[0].AreaId);// ��ͬ��������

        $("#address").val(cusInfo[0].Address);// �ͻ���ַ
    }

    // ���º�ͬ�������� (ID2232 2016-11-18)
    if (cusInfo != false && cusInfo[0].AreaIdNow != "") {
        $("#areaCode").val(cusInfo[0].AreaIdNow);
    }

    setAreaInfo();
    setProExeDept();
}
// ֱ���ύ����
function toApp() {
    if(browserChk()){
        document.getElementById('form1').action = "index1.php?model=contract_contract_contract&action=add&act=app";
        if(checkExeDept()){
            $('#form1').submit();
        }
    }else{
        return false;
    }
}
//���棬����������֤
function toSave() {
    var form = $('#form1');
    if(browserChk() && invoiceChk()){
        form.action = "index1.php?model=contract_contract_contract&action=add";
        if (checkExeDept()) {
            form.submit();
        }
    }
}

function checkExeDept() {
    var productInfoObj = $("#productInfo");
    // ��ֹ����ҳ����������js�ļ���û�м��ص�productInfoObj����ļ���������ֱ���
    if (productInfoObj.productInfoGrid != undefined) {
        var exeDeptObj = productInfoObj.productInfoGrid("getCmpByCol", "exeDeptId");
        var hasEmpty = 0;
        exeDeptObj.each(function () {
            if ($(this).val() == "") {
                hasEmpty += 1;
            }
        });
        // console.log(hasEmpty);
        if (hasEmpty > 0) {
            alert("��ѡ���Ʒ��ִ������");
            return false;
        } else {
            return true;
        }
    }
}

// ������
function hideList(listId) {
    var temp = document.getElementById(listId);
    var tempH = document.getElementById(listId + "H");
    if (temp.style.display == '') {
        temp.style.display = "none";
        if (tempH != null) {
            tempH.style.display = "";
        }
    } else if (temp.style.display == "none") {
        temp.style.display = '';
        if (tempH != null) {
            tempH.style.display = 'none';
        }
    }
}
// �ж��Ƿ�ǩԼ
function isSign(obj) {
    if (obj.value == '100%') {
        document.getElementById("signDateNone").style.display = "";
        $('#signDate').addClass("validate[required]");
        $("#moneyName").html("ǩԼ���(��)��");
        $("#signDate").val("");
    } else {
        $("#signDate").val("");
        $("#moneyName").html("Ԥ�ƽ�");
        document.getElementById("signDateNone").style.display = "none";
        $('#signDate').removeClass("validate[required]");
    }
}

// ���ݺ�ͬ���͸ı��ͬ���Եļ�����
function changeNature(obj) {
    $('#contractNature').empty();
    var objV = document.getElementById('contractNature');
    if (obj.value != "") {
        contractNatureCodeArr = getData(obj.value);
        objV.options.add(new Option("...��ѡ��...", "")); // �������IE��firefox
        addDataToSelect(contractNatureCodeArr, 'contractNature');
    } else {
        objV.options.add(new Option("...��ѡ��...", "")); // �������IE��firefox
    }
    //���ۺ�ͬ
    if (obj.value == 'HTLX-XSHT') {
        //ȥ����ͬ��ʼ��ֹ����
        $('#beginDate').removeClass("validate[required]");
        $('#endDate').removeClass("validate[required]");
        $("#beginSpan").attr('style', "color:black");
        $("#endSpan").attr('style', "color:black");
        //ά��ʱ�䣨�£���Ϊ����
        $("#Maintenance").addClass("validate[required]");
        $("#maintenanceSpan").attr('style', "color:blue");
        // ֽ�ʺ�ͬ���
        if($('#paperContract').val() == '��'){
            $("#paperContractSpan").attr('style', "color:blue");
            $('#paperContract').addClass("validate[required]");
            $("#paperContractRemark").addClass("validate[required]");
            $("#paperContractRemarkSpan").attr('style', "color:blue");
            $("#paperReason").show();
        }else{
            $("#paperReason").hide();
            $("#paperContractRemark").val('').removeClass("validate[required]");	//����ǰ��������
        }
        // �����ļ�
        $("#checkFileSpan").attr('style', "color:blue");
        $('#checkFile').addClass("validate[required]");
    } else if (obj.value == 'HTLX-PJGH') {
        //ά��ʱ�䣨�£���Ϊ�Ǳ���
        $("#Maintenance").removeClass("validate[required]");
        $("#maintenanceSpan").attr('style', "color:black");
        //ȥ����ͬ��ʼ��ֹ����
        $('#beginDate').removeClass("validate[required]");
        $('#endDate').removeClass("validate[required]");
        $("#beginSpan").attr('style', "color:black");
        $("#endSpan").attr('style', "color:black");
        // ֽ�ʺ�ͬ���
        $("#paperContractSpan").attr('style', "color:black");
        $('#paperContract').removeClass("validate[required]").val('��');
        $("#paperContractRemark").removeClass("validate[required]");
        $("#paperContractRemarkSpan").attr('style', "color:black");
        $("#paperReason").show();
        // �����ļ�
        $("#checkFileSpan").attr('style', "color:black");
        $('#checkFile').removeClass("validate[required]").val('��');
        // �Ƿ���ǩ
        $("#isRenewed").val("0");
    } else {
        $('#beginDate').addClass("validate[required]");
        $('#endDate').addClass("validate[required]");
        $("#beginSpan").attr('style', "color:blue");
        $("#endSpan").attr('style', "color:blue");
        //ά��ʱ�䣨�£���Ϊ�Ǳ���
        $("#Maintenance").removeClass("validate[required]");
        $("#maintenanceSpan").attr('style', "color:black");
        // ֽ�ʺ�ͬ���
        $("#paperContractSpan").attr('style', "color:blue");
        $('#paperContract').addClass("validate[required]");
        $("#paperContractRemarkSpan").attr('style', "color:blue");

        // ֽ�ʺ�ͬ���
        if($('#paperContract').val() == '��'){
            $("#paperContractRemark").addClass("validate[required]");
            $("#paperReason").show();
        }else{
            $("#paperReason").hide();
            $("#paperContractRemark").val('').removeClass("validate[required]");	//����ǰ��������
        }

        // �����ļ�
        $("#checkFileSpan").attr('style', "color:blue");
        $('#checkFile').addClass("validate[required]");
    }
    // �ı�ϣ���������� ������֤
    if (obj.value == 'HTLX-FWHT' || obj.value == 'HTLX-YFHT') {
        if (obj.value == 'HTLX-FWHT') {
            $("#trialprojectNone").show();
        }
        //        $("#shipConditionSpan").attr('style', "color:black");
        $("#deliveryDateSpan").attr('style', "color:black");
        $('#deliveryDate').removeClass("validate[required]");
        $('#shipCondition').removeClass("validate[required]");
    } else {
        $("#trialprojectNone").hide();
        $('#deliveryDate').addClass("validate[required]");
        $('#shipCondition').addClass("validate[required]");
        //        $("#shipConditionSpan").attr('style', "color:blue");
        $("#deliveryDateSpan").attr('style', "color:blue");
    }
    //��ͬ����ѡ���Ƿ����ͬʱ����Ʒ��������Ǳ���
    if (obj.value == 'HTLX-FWHT') {
        $('#warrantyClause').removeClass("validate[required]");
        $("#warrantyClauseSpan").attr('style', "color:black");
    } else {
        $('#warrantyClause').addClass("validate[required]");
        $("#warrantyClauseSpan").attr('style', "color:blue");
    }
}
// ��֯����ѡ��
$(function () {
    $("#prinvipalName").yxselect_user({
        hiddenId: 'prinvipalId',
        isGetDept: [true, "depId", "depName"]
    });
    $("#contractSigner").yxselect_user({
        hiddenId: 'contractSignerId'
    });
});
//��������
function regionList() {
    $("#areaName").yxcombogrid_area({
        hiddenId: 'areaCode',
        gridOptions: {
            showcheckbox: false,
            //			param : { 'businessBelong' : $("#businessBelong").val()},
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#areaPrincipal").val(data.areaPrincipal);
                    $("#areaCode").val(data.id);
                    $("#areaPrincipalId").val(data.areaPrincipalId);
                }
            }
        }
    });
}
// �ͻ���Ϣ��ʼ��
function initCustomerInfo() {
    $("#customerType").val('');
    $("#customerTypeName").val('');
    $("#country").find("option[value='1']").attr("selected", "selected").trigger("change");
    $("#province").find("option[value='']").attr("selected", "selected").trigger("change");
    $("#chooseAreaName").find("option[value='']").attr("selected", "selected").trigger("change");
    $("#chooseAreaName").hide();
    $("#areaName").val('');
    $("#areaCode").val('');
    $("#areaName").show();
    $("#areaPrincipal").val("");
    $("#areaPrincipalId").val("");
}

// ���������б�
$(function () {
    // �ͻ�
    $("#customerName").yxcombogrid_customer({
        hiddenId: 'customerId',
        height: 250,
        gridOptions: {
            isTitle: true,
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {
                    initCustomerInfo();
                    // ���������Ŀͻ�����
                    $("#customerType").val(data.TypeOne);
                    $("#customerTypeName").val(data.TypeOneName);

                    // ���������ͻ��Ĺ���/ʡ��/������Ϣ
                    $("#country").find("option[value='" + data.CountryId + "']").attr("selected", "selected").trigger("change");
                    $("#province").find("option[value='" + data.ProvId + "']").attr("selected", "selected").trigger("change");

                    if ($("#city").find("option[value='" + data.CityId + "']").length > 0) {
                        $("#city").find("option[value='" + data.CityId + "']").attr("selected", "selected").trigger("change");
                    } else {
                        $("#city").find("option[value='']").attr("selected", "selected").trigger("change");
                    }

                    $("#country_Id").val(data.CountryId);
                    $("#province_Id").val(data.ProvId);
                    $("#city_Id").val(data.CityId);
                    $("#customerId").val(data.id);
                    $("#address").val(data.Address);
                    // �ͻ���ַ
                    var linkmanCmp = $("#linkmanListInfo").yxeditgrid(
                        "getCmpByCol", "linkmanName");
                    linkmanCmp.yxcombogrid_linkman("remove");
                    $("#linkmanListInfo").yxeditgrid('remove');
                    linkmanList(data.id);

                    $("#parentCode").yxcombogrid_allcontract("remove");
                    contractSelect();

                    setAreaInfo();
                    setProExeDept();
                }
            }
        }
    });

    $("#customerTypeWrap").children(".clear-trigger").click(function () {
        initCustomerInfo();
    });

    //��˾
    $("#businessBelongName").yxcombogrid_branch({
        hiddenId: 'businessBelong',
        height: 250,
        isFocusoutCheck: false,
        gridOptions: {
            showcheckbox: false,
            event: {
                //                'row_dblclick': function (e, row, data) {
                //                    $("#areaName").val("");
                //                    $("#areaCode").val("");
                //                    $("#areaPrincipal").val("");
                //                    $("#areaPrincipalId").val("");
                //
                //                    $("#areaName").yxcombogrid_area("remove");
                ////							regionList();
                //                }
            }
        }
    });
    $("#signSubjectName").yxcombogrid_branch({
        hiddenId: 'signSubject',
        height: 250,
        isFocusoutCheck: false,
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {
                    //                    $("#areaName").val("");
                    //                    $("#areaCode").val("");
                    //                    $("#areaPrincipal").val("");
                    //                    $("#areaPrincipalId").val("");

                    $("#areaName").yxcombogrid_area("remove");
                    setAreaInfo();
                    //							regionList();
                }
            }
        }
    });
    //		    regionList();
    //������Ŀ
    $("#trialprojectCode").yxcombogrid_trialproject({
        hiddenId: 'trialprojectId',
        gridOptions: {
            //						    param : {'createId' : $("#userId").val()},
            //							param : {'isFail' : '0'},
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#trialprojectId").val(data.id);
                    $("#trialprojectName").val(data.projectName);
                }
            }
        }
    });
    // ���ұ�
    $("#currency").yxcombogrid_currency({
        hiddenId: 'id',
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#rate").val(data.rate);
                    conversion();
                    checkConMoney();
                }
            }
        }
    });
    createFormatOnClick("contractMoney_c");
});

// ���ʼ���
function conversion() {
    var currency = $("#currency").val();

    if (currency != '�����') {
        document.getElementById("currencyRate").style.display = "";
        $("#cur").html("(" + currency + ")");
        $("#cur1").html("(" + currency + ")");
        $("#contractTempMoney_v").attr('class', "readOnlyTxtNormal");
        //        $("#contractMoney_v").attr('class', "readOnlyTxtNormal");

        var tempMoney = $("#contractTempMoneyCur").val();
        var Money = $("#contractMoney").val();
        var rate = $("#rate").val();
        $("#contractTempMoney_v").val(moneyFormat2(tempMoney * rate));
        $("#contractTempMoney").val(tempMoney * rate);
        $("#contractMoneyCur_v").val(moneyFormat2(Money / rate));
        $("#contractMoneyCur").val(Money / rate);
    } else if (currency == '�����') {
        $("#contractMoney_v").attr('class', "txt");
        $("#contractMoney_v").attr('readOnly', false);
        $("#contractTempMoney_v").attr('class', "txt");
        $("#contractTempMoney_v").attr('readOnly', false);
        $("#currencyRate").hide();
    }
}

// Ԥ�ƽ�����
function setContractMoney() {
    var money = $("#contractMoneyCur").val();
    var rate = $("#rate").val();
    $("#contractMoney_v").val(moneyFormat2(money * rate));
    $("#contractMoney").val(money * rate);
}

$(function () {
    // �ύ��֤
    $("#form1").validationEngine({
        inlineValidation: false,
        success: function () {
            var country = $("#countryName").val();
            var province = $("#province").val();
            var city = $("#city").val();
            // if (country == '�й�') {
            //     validate({
            //         "province": {
            //             required: true
            //         },
            //         "city": {
            //             required: true
            //         }
            //
            //     })
            // }
            var isNeedStamp = $("input[name='contract[isNeedStamp]']:checked").val();
            if (isNeedStamp == '1') {
                validate({
                    "stampType": {
                        required: true
                    }
                })
            }
            sub();
            //		   $("#form1").trigger("onsubmit");
            $("#form1").submit();//������֤�����ύ���������Ҫ����������ΰ�ť�����ύ����bug

        },
        failure: false
    });

    //alert($("#contractName").validationEngine.buildPrompt)
    //$("#contractName").buildPrompt(null,"aaaaa");
    /**
     * ��֤��Ϣ
     */
    validate({
        "winRate": {
            required: true
        }, "currency": {
            required: true
        }, "prinvipalName": {
            required: true
        },
        "contractType": {
            required: true
        },
        "contractName": {
            required: true
        },
        "customerName": {
            required: true
        },
        "deliveryDate": {
            required: true
        },
        "shipCondition": {
            required: true
        },
        "warrantyClause": {
            required: true
        },
        "contractMoney_v": {
            moneyA: true
        },
        "areaCode": {
            required: true
        },
        "areaName": {
            required: true
        },
        "contractSigner": {
            required: true
        },
        "businessBelongName": {
            required: true
        },
        "isRenewed": {
            required: true
        }
    });
});

function sub() {
    $("form").bind("submit", function () {
        if ($("#contractType").val() == 'HTLX-FWHT' || $("#contractType").val() == 'HTLX-ZLHT') {
            if ($("#beginDate").val() == '' || $("#endDate").val() == '') {
                alert("����ȷ��д��ͬ  ��ʼ���������ڡ�");
                return false;
            }
        }
        // ��֤�˻����������Ƿ�Ϊ100%
        var advance = $("#advanceA").val();
        var delivery = $("#deliveryA").val();
        var initialpayment = $("#initialpaymentA").val();
        var finalpayment = $("#finalpaymentA").val();

        var addArr = [advance, delivery, initialpayment, finalpayment]
        //xxxxxxxxxxxxx
        var progressNum = $("input[id^='progresspaymentPro']").length;

        var otherNum = $("input[id^='otherpaymentPro']").length;
        if (progressNum > '0') {
            for (i = 1; i <= progressNum; i++) {
                addArr.push($('#progresspaymentPro' + i).val());
            }
        }
        if (otherNum > '0') {
            for (i = 1; i <= otherNum; i++) {
                addArr.push($('#otherpaymentPro' + i).val());
            }
        }
        //xxxxxx end xxxxxxx
        //		var allNum = accAddMore(addArr);
        //		if (allNum != '100') {
        //			alert("������ĸ�������ռ��֮��Ϊ��" + allNum + "%�� �뽫ռ��֮�͵���Ϊ 100% ");
        //			return false;
        //		}
        //
        //	   //���㸶�������ٷֱ��ܺ�
        //	    var cmps = $("#paymentListInfo").yxeditgrid("getCmpByCol", "paymentPer");
        //		var paymentNum = 0;
        //		cmps.each(function(i,n) {
        //			//���˵�ɾ������
        //			if($("#contract[payment][_" + i +"_isDelTag").length == 0){
        //				paymentNum = accAdd($(this).val() , paymentNum);
        //			}
        //		});
        //		if(paymentNum != '100'){
        //		   alert("��������ռ��֮��Ϊ��" + paymentNum + "%�� �뽫ռ��֮�͵���Ϊ 100% ");
        //            return false;
        //		}
        var dataCode = $("#dataCode").val();
        var itemArr = dataCode.split(',');
        var itemLength = itemArr.length;
        var contractMoney = $("#contractMoney").val();
        //�Ƿ��ܺ�ͬ���������ͬ�ǿ�ܺ�ͬ�����ͬ���Ϊ0
        var isFrame = $("#contractType").val() == 'HTLX-XSHT' && $("#isFrame").val() == '1' ? '1' : '0';
        if (contractMoney == "" || (contractMoney * 1 == 0 && isFrame == '0')) {
            alert('��ͬ����Ϊ�ջ�0');
            return false;
        }
        //        var contractMoneyCur = $("#contractMoneyCur").val();
        //        var currency = $("#currency").val();

        // �������ͬ������ PMS 594
        if($("#contractType").val() == 'HTLX-PJGH' && contractMoney > 10000){
            alert("�������ͬ���ܶ���1��Ԫ, �����1��Ԫ, ���߳������̡�");
            return false;
        }

        var allCost = 0;
        var j = 0;
        try {
            var notInvoice = true; // ����Ʊ
            var invoiceType, invoiceTypeMoney;
            for (var i = 0; i < itemLength; i++) {
                // ��Ʊ����
                invoiceType = $("#" + itemArr[i]);
                if (invoiceType.is(":checked")) {
                    if (itemArr[i] == "HTBKP") {
                        notInvoice = false;
                    }
                    invoiceTypeMoney = $("#" + itemArr[i] + "Money_v");
                    if (invoiceTypeMoney.val() == "") {
                        invoiceTypeMoney.addClass("validate[required]");
                    } else {
                        allCost = accAdd(allCost, invoiceTypeMoney.val(), 2);
                        invoiceTypeMoney.removeClass("validate[required]");
                    }
                } else {
                    j++;
                }
            }
            if (itemLength == j) {
                alert("��Ʊ���ͱ��빴ѡ");
                return false;
            }
            // �����ѡ�˿�Ʊ���ͣ�������ѡ�˲���Ʊ������ʾ����
            if (itemLength - j > 1 && notInvoice == false) {
                alert("ѡ�񲻿�Ʊʱ��������д������Ʊ����");
                return false;
            }
            if (notInvoice == true && allCost != contractMoney) {
                alert("��ͬ��������ڿ�Ʊ���ͽ��");
                return false;
            }
        } catch (e) {

        }

        var linkmanListInfoObj = $("#linkmanListInfo");
        var linkmanListRowNum = linkmanListInfoObj.yxeditgrid('getCurShowRowNum');
        if (linkmanListRowNum == '0') {
            alert("�ͻ���ϵ����Ϣ����Ϊ��");
            return false;
        } else {
            var pass = true;
            linkmanListInfoObj.yxeditgrid("getCmpByCol", "linkmanName").each(function() {
                if ($(this).val() == "") {
                    alert("��ѡ��ͻ���ϵ�ˣ�");
                    pass = false;
                    return false;
                }
            });
            if (pass == false) {
                return false;
            }
            linkmanListInfoObj.yxeditgrid("getCmpByCol", "telephone").each(function() {
                if ($(this).val() == "") {
                    alert("�ͻ���ϵ�˵绰����Ϊ�գ�");
                    pass = false;
                    return false;
                }
            });
            if (pass == false) {
                return false;
            }
        }

        var productInfoObj = $("#productInfo");
        var rowNum = productInfoObj.productInfoGrid('getCurShowRowNum');
        if (rowNum == '0') {
            alert("��Ʒ�嵥����Ϊ��");
            return false;
        } else {
            // ��Ʒ�ߴ���
            //        	var newProLineArr = [];
            var proLineAllSelected = true;
            productInfoObj.productInfoGrid("getCmpByCol", "newProLineCode").each(function () {
                if ($(this).val() == "") {
                    alert("��ѡ���Ʒ�Ĳ�Ʒ�ߣ�");
                    proLineAllSelected = false;
                    return false;
                    //                } else {
                    //                    var rowNum = $(this).data('rowNum');
                    //                    productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'newProLineName').
                    //                        val($(this).find("option:selected").text());
                    //
                    //                    if ($.inArray($(this).val(), newProLineArr) == -1) {
                    //                    	newProLineArr.push($(this).val());
                    //                    }
                }
            });
            if (proLineAllSelected == false) {
                return false;
            }
            // ִ�в��Ŵ���
            var exeDeptArr = [];
            var exeDeptAllSelected = true;
            productInfoObj.productInfoGrid("getCmpByCol", "exeDeptId").each(function () {
                if ($(this).val() == "") {
                    alert("��ѡ���Ʒ��ִ������");
                    exeDeptAllSelected = false;
                    return false;
                } else {
                    var rowNum = $(this).data('rowNum');
                    productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'exeDeptName').
                        val($(this).find("option:selected").text());

                    if ($.inArray($(this).val(), exeDeptArr) == -1) {
                        exeDeptArr.push($(this).val());
                    }
                }
            });
            if (exeDeptAllSelected == false) {
                return false;
            }
            var pros = productInfoObj.productInfoGrid("getCmpByCol", "money");
            var proMoney = 0;
            pros.each(function (i, n) {
                //���˵�ɾ������
                if ($("#contract[product][_" + i + "_isDelTag").length == 0) {
                    proMoney = accAdd($(this).val(), proMoney);
                }
            });
            //            var currency = $("#currency").val();
            //            if (currency != '�����') {
            //                var rate = $("#rate").val();
            //                proMoney = proMoney * rate;
            //            }
            if (contractMoney != proMoney) {
                alert("�뱣֤��Ʒ����ܺ����ͬ���һ�£�");
                return false;
            }
        }

        //��������
        var bowItemArr = $("#borrowConEquInfo").yxeditgrid("getCmpByCol", "onlyProductId");
        var bowFlag = 0;
        if (bowItemArr.length > 0) {
            //ѭ��
            bowItemArr.each(function () {
                if ($(this).val() == ' ') {
                    bowFlag = 1;
                }
            });
        }
        if (bowFlag == '1') {
            alert("��ѡ�����ת�������ϵĹ�����Ʒ");
            return false;
        }
        if ($("#contractType").val() != "HTLX-PJGH" && ($("#uploadfileList2").html() == "" || $("#uploadfileList2").html() == "�����κθ���")) {
            alert("���ϴ��������ļ���")
            return false;
        }
        // ������ת���۹����̻�����
        if (!showChance()) {
            tb_show(null, '#TB_inline?height=600&width=800&inlineId=showChance', false);
            return false;
        } else {
            return true;
        }
    });
}
$(function () {
    //���ƺ�ͬ���Ƿ��ֹ�����
    var contractInput = $("#contractInput").val();
    if (contractInput == '0') {
        $("#contractCodeHandle").show();
        $("#contractCodeHandleV").show();
        $("#contractCode").blur(
            function () {
                var contractCode = $("#contractCode").val();
                contractCode = strTrim(contractCode);
                if (contractCode != '') {
                    $.ajax({
                        type: 'POST',
                        url: "?model=contract_contract_contract&action=checkCode",
                        data: {
                            contractCode: contractCode
                        },
                        async: false,
                        success: function (data) {
                            if (data == '1') {
                                alert("��ͬ����ظ�������!")
                                $("#contractCode").focus();
                            }
                        }
                    });
                } else {
                    alert("����д��ͬ��!")
                    $("#contractCode").focus();
                }
            }
        );
    } else {
        $("#contractCodeHandle").hide();
        $("#contractCodeHandleV").hide();
    }
});

//����������ʾ
function changeShipCondition(obj) {
    if (obj.value == '1') {
        if (confirm('��ȷ��ѡ��֪ͨ������')) {
            return true;
        } else {
            $("#shipCondition").val("")
        }
    } else if (obj.value == '0') {
        if (confirm('��ȷ��ѡ������������')) {
            return true;
        } else {
            $("#shipCondition").val("")
        }
    }
}

$(function () {
    // ����ʵʱִ�������ʶ pms2313 ��
    var returnData = getDeptCode();
    if ($('#submitTag_').val() != undefined) {
        var edCode = '';
        var edName = '';
        if (returnData.length > 0 && returnData[0].exeDeptCode != '') {
            edCode = returnData[0].exeDeptCode;
            edName = returnData[0].exeDeptName;
        }
        var htmlStr = "<input type='hidden' id='defaultExeDeptId' value='" + edCode + "'>" +
            "<input type='hidden' id='defaultExeDeptName' value='" + edName + "'>";
        $('#submitTag_').after(htmlStr);
    }

    $("#province").change(function () {
        setAreaInfo();
        setProExeDept();
    });
    $("#customerType").change(function () {
        setAreaInfo();
        setProExeDept();
    });
    $("#signSubject").change(function () {
        setAreaInfo();
        setProExeDept();
    });
    $("#module").change(function () {
        setAreaInfo();
        setProExeDept();
    });
});

//�Զ����ҹ�������
function setAreaInfo() {
    // ֻ�����������Ϊ�����ݲ���
    if ($("#areaName").val() != "�����ݲ�") {
        var customerType = $("#customerType").val();
        var province = $("#province_Id").val();
        var businessBelong = $("#signSubject").val();
        var module = $("#module").val();
        if (customerType != '' && province != '' && businessBelong != '' && module != '') {
            var returnValue = $.ajax({
                type: 'POST',
                url: "?model=system_region_region&action=ajaxConRegion",
                data: {
                    customerType: customerType,
                    province: province,
                    businessBelong: businessBelong,
                    module: module,
                    getAll: 1
                },
                async: false,
                success: function (data) {
                }
            }).responseText;
            returnValue = eval("(" + returnValue + ")");
            if (returnValue['count'] != undefined && returnValue['count'] > 0) {
                var returnData = returnValue['data'];
                if (returnValue['count'] == 1) {//ֻ��һ��������ֱ�Ӵ���
                    $('#areaName').show();// ��ʾ�����
                    $('#chooseAreaName').hide();// ����������
                    $('#chooseAreaName').removeClass("validate[required]");
                    $("#areaName").val(returnData[0].areaName);
                    $("#areaCode").val(returnData[0].id);
                    $("#areaPrincipal").val(returnData[0].areaPrincipal);// ��������
                    $("#areaPrincipalId").val(returnData[0].areaPrincipalId);// ��������Id
                    $("#exeDeptCode").val(returnData[0].exeDeptCode);// ִ��������
                    $("#exeDeptName").val(returnData[0].exeDeptName);// ִ������
                } else {// ���ж�������,����������������Լ�ѡ
                    // ���������,����ʼ����Ӧ��Ϣ
                    $('#areaName').hide();
                    $("#areaName").val("");
                    $("#areaCode").val("");
                    $("#areaPrincipal").val("");// ��������
                    $("#areaPrincipalId").val("");// ��������Id
                    $("#exeDeptCode").val("");// ִ��������
                    $("#exeDeptName").val("");// ִ������

                    // ���ɶ�Ӧ��������
                    var optStr = '<option value="" title="...��ѡ��...">...��ѡ��...</option>';
                    $.each(returnData, function () {
                        var thisData = $(this)[0];
                        optStr += '<option value="' + thisData.id + '" data-areaPrincipal="' + thisData.areaPrincipal + '" data-areaPrincipalId="' + thisData.areaPrincipalId + '" data-exeDeptCode="' + thisData.exeDeptCode + '" data-exeDeptName="' + thisData.exeDeptName + '" title="' + thisData.areaName + '">' + thisData.areaName + '</option>';
                    });
                    $('#chooseAreaName').html(optStr);
                    $('#chooseAreaName').show();// ��ʾ������
                    $('#chooseAreaName').addClass("validate[required]");// ����Ϊ��ѡ��
                    // ѡ����������,������Ӧ������
                    $('#chooseAreaName').change(function () {
                        // console.log($(this).find("option:selected").text());
                        $("#areaName").val($(this).find("option:selected").text());
                        $("#areaCode").val($(this).find("option:selected").val());
                        $("#areaPrincipal").val($(this).find("option:selected").attr('data-areaPrincipal'));// ��������
                        $("#areaPrincipalId").val($(this).find("option:selected").attr('data-areaPrincipalId'));// ��������Id
                        $("#exeDeptCode").val($(this).find("option:selected").attr('data-exeDeptCode'));// ִ��������
                        $("#exeDeptName").val($(this).find("option:selected").attr('data-exeDeptName'));// ִ������
                        setProExeDept();
                    });
                }
            } else {
                $('#areaName').show();// ��ʾ�����
                $('#chooseAreaName').hide();// ����������
                $('#chooseAreaName').removeClass("validate[required]");
                $("#areaName").val("");
                $("#areaCode").val("");
                $("#areaPrincipal").val("");// ��������
                $("#areaPrincipalId").val("");// ��������Id
                $("#exeDeptCode").val("");// ִ��������
                $("#exeDeptName").val("");// ִ������
            }
        } else {
            return false;
        }
    }
}


/**
 * �Ƿ���ǩ
 */

$(function () {
    contractSelect();
    //������ǩ��ͬ�ţ���ǩ��ͬ����
    $("#parentCode").parent("td").hide().prev("td").hide();
    $("#parentName").parent("td").hide().prev("td").hide();
});
function contractSelect() {
    var param = '';
    param = {
        'states': '2,3,4,7',
        'ExaStatus': '���',
        'prinvipalId': $("#userId").val(),//ֻ�ܲ鿴���˺�ͬ
        'customerId': $("#customerId").val() != '' ? $("#customerId").val() : "null"
    };
    // ѡ���ͬԴ��
    $("#parentCode").yxcombogrid_allcontract({
        hiddenId: 'parentId',
        width: 980,
        height: 300,
        searchName: 'contractCode',
        gridOptions: {
            showcheckbox: false,
            param: param,
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#parentName").val(data.contractName);
                }
            }
        }
    });
}

//��ǩ�ֶ�
function changeRenewed(obj) {
    if (obj.value == '1') {//��ǩ
        $("#parentCode").parent("td").show().prev("td").show();
        $("#parentName").parent("td").show().prev("td").show();
    } else {
        $("#parentCode").parent("td").hide().prev("td").hide();
        $("#parentName").parent("td").hide().prev("td").hide();
        $("#parentId").val("");
        $("#parentName").val("");
        $("#parentCode").val("");

    }
}

// ��ȡ��Ʒִ������
function getProExeDept() {
    var province = $("#province_Id").val();
    var module = $("#module").val();
    if (province != '' && module != '') {
        var returnValue = $.ajax({
            type: 'POST',
            url: "?model=engineering_officeinfo_officeinfo&action=ajaxConProExeDept",
            data: {
                province: province,
                module: module
            },
            async: false
        }).responseText;
        returnValue = eval("(" + returnValue + ")");
        if (returnValue) {
            return returnValue;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

// �������в�Ʒִ������
//function setProExeDept() {
//	var productLineName = $("#exeDeptName").val();
//    if (productLineName !== undefined && productLineName !== "") {
//    	var productInfoObj = $("#productInfo");
//    	var exeDeptObj = productInfoObj.productInfoGrid("getCmpByCol", "exeDeptId");
//    	if(exeDeptObj.length > 0){
//    		exeDeptObj.each(function(){
//    			$(this).find("option:[text='"+ productLineName + "']").attr("selected",true);
//                var rowNum = $(this).data('rowNum');
//                productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'exeDeptName').val(productLineName);
//            });
//    	}
//    } else {
//        return false;
//    }
//}

/**
 * ���ݺ�ͬ���������ȡ��Ӧ��ִ������
 * ���һ�θ��£�2016-12-15 PMS 2313
 */
function getDeptCode() {
    var areaCode = $("#areaCode").val();//��ͬ��������ID
    // 2016-12-15 PMS2313 ͨ����ͬ��������ID��ѯ�������ִ������
    var returnData = $.ajax({
        type: 'POST',
        url: "?model=system_region_region&action=ajaxChkExeDept",
        data: {
            areaCode: areaCode,
        },
        async: false,
        success: function (data) {
        }
    }).responseText;
    returnData = eval("(" + returnData + ")");
    return returnData;
}


/**
 * ��鿪Ʊ��Ϣ
 * @returns {boolean}
 */
function invoiceChk(){
    var dataCode = $("#dataCode").val();
    var itemArr = dataCode.split(',');
    var itemLength = itemArr.length;
    var contractMoney = $("#contractMoney").val();
    var allCost = 0,j = 0,pass = true;
    try {
        var notInvoice = true; // ����Ʊ
        var invoiceType, invoiceTypeMoney;
        for (var i = 0; i < itemLength; i++) {
            // ��Ʊ����
            invoiceType = $("#" + itemArr[i]);
            if (invoiceType.is(":checked")) {
                if (itemArr[i] == "HTBKP") {
                    notInvoice = false;
                }
                invoiceTypeMoney = $("#" + itemArr[i] + "Money_v");
                if (invoiceTypeMoney.val() == "") {
                    invoiceTypeMoney.addClass("validate[required]");
                } else {
                    allCost = accAdd(allCost, invoiceTypeMoney.val(), 2);
                    invoiceTypeMoney.removeClass("validate[required]");
                }
            } else {
                j++;
            }
        }
        if (itemLength == j) {
            alert("��Ʊ���ͱ��빴ѡ");
            pass =  false;
        }
        // �����ѡ�˿�Ʊ���ͣ�������ѡ�˲���Ʊ������ʾ����
        if (itemLength - j > 1 && notInvoice == false && pass) {
            alert("ѡ�񲻿�Ʊʱ��������д������Ʊ����");
            pass =  false;
        }
        if (notInvoice == true && allCost != contractMoney && pass) {
            alert("��ͬ��������ڿ�Ʊ���ͽ��");
            pass =  false;
        }
    } catch (e) {

    }
    return pass;
}

/**
 * ���²�Ʒִ������
 * ���һ�θ��£�2016-12-15 PMS 2313
 */
function setProExeDept() {
    var productInfoObj = $("#productInfo");
    // ��ֹ����ҳ����������js�ļ���û�м��ص�productInfoObj����ļ���������ֱ���
    if (productInfoObj.productInfoGrid != undefined) {
        var exeDeptObj = productInfoObj.productInfoGrid("getCmpByCol", "exeDeptId");
        var returnData = getDeptCode();
        if (returnData.length > 0 && returnData[0].exeDeptCode != '') {
            returnData = returnData[0];
            $('#defaultExeDeptId').val(returnData.exeDeptCode);
            $('#defaultExeDeptName').val(returnData.exeDeptName);
            var productLineName = returnData.exeDeptName;
            // console.log(returnData);
            // �������в�Ʒ��ִ������
            if (exeDeptObj.length > 0) {
                exeDeptObj.each(function () {
                    var rowNum = $(this).data('rowNum');
                    var productId = productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'id').val();
                    if (productId == '' || productId == undefined) {
                        $(this).find("option:[value='" + returnData.exeDeptCode + "']").attr("selected", true);
                        productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'exeDeptName').val(productLineName);
                    }
                });
            }
        } else {
            $('#defaultExeDeptId').val('');
            $('#defaultExeDeptName').val('');
            // ����������Ʒ��ִ������Ĭ��Ϊ��
            if (exeDeptObj.length > 0) {
                exeDeptObj.each(function () {
                    var rowNum = $(this).data('rowNum');
                    var productId = productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'id').val();
                    if (productId == '' || productId == undefined) {// ���޸�ԭ���Ĳ�Ʒ��ִ������
                        $(this).find("option:[value='" + '' + "']").attr("selected", true);
                        productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'exeDeptName').val('');
                    }
                });
            }
        }
    }
}

//����ĳ����Ʒִ������
//function setProExeDeptByRow(i) {
//	var returnValue = getProExeDept();
//    if (returnValue) {
//    	var productLine = returnValue[0].productLine;
//    	var productLineName = returnValue[0].productLineName;
//    	var productInfoObj = $("#productInfo");
//    	productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptId')
//    		.find("option:[text='"+ productLineName + "']").attr("selected",true);
//    	productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptName').val(productLineName);
//    } else {
//        return false;
//    }
//}

function setProExeDeptByRow(i) {
    var productLineName = $("#exeDeptName").val();
    if (productLineName !== undefined && productLineName !== "") {
        var productInfoObj = $("#productInfo");
        productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptId')
            .find("option:[text='" + productLineName + "']").attr("selected", true);
        productInfoObj.productInfoGrid("getCmpByRowAndCol", i, 'exeDeptName').val(productLineName);
    } else {
        return false;
    }
}

//ά��ʱ�䣨�£�У��
function checkMaintenance(obj) {
    var val = obj.value;
    if (val != '') {
        var re = /^[0-9]\d*$/;
        if (!re.test(val) && val != '��') {
            alert('ά��ʱ�䣨�£�����д���ֻ���');
            obj.value = '';
        }
    }
}
/********** ������ת���۴��������̻����� **********/
// �̻���Ⱦ
function showChance() {
    var toBorrowIdArr = $("#borrowConEquInfo").yxeditgrid("getCmpByCol", "toBorrowId");
    var borrowIdArr = new Array();
    if (toBorrowIdArr.length > 0) {
        toBorrowIdArr.each(function () {
            borrowIdArr.push($(this).val());
        });
        // ��ȡ��ǰѡ�е��̻�����ֵ
        var chanceArr = $("#chanceArea input[id^='chanceId']:checked");
        var chanceIdArr = [];
        var chanceIds = '';
        if (chanceArr.length > 0) {
            chanceArr.each(function (i, n) {
                chanceIdArr.push(this.value);
            });
            chanceIds = chanceIdArr.toString();
        }
        // ��ȡ�̻�����
        $.ajax({
            url: '?model=contract_contract_contract&action=getChanceByBorrowIds',
            data: {'borrowIds': borrowIdArr.toString()},
            type: 'POST',
            success: function (data) {
                $("#chanceArea").html(data);
                if (chanceIds != '') {
                    var chanceNum = 0;
                    // ��ֵ
                    for (var i = 0; i < chanceIdArr.length; i++) {
                        var chkObj = $("#chanceId-" + chanceIdArr[i]);
                        if (chkObj.length > 0) {
                            chkObj.attr('checked', true);
                            chanceNum++;
                        }
                    }
                    $("#chanceNum").html(chanceNum);
                    if (chanceNum == $("#chanceAllNum").html()) {
                        $("#allCheckbox").attr('checked', true);
                    }
                }
            }
        });
    } else {
        return true;
    }
}
//�ύ������������֤
function toSubmit() {
    //��ȡ��ǰѡ�е��̻�����ֵ
    var chanceArr = $("#chanceArea input[id^='chanceId']:checked");
    var chanceIdArr = [];
    var chanceIds = '';
    if (chanceArr.length > 0) {
        chanceArr.each(function (i, n) {
            chanceIdArr.push(this.value);
        });
        chanceIds = chanceIdArr.toString();
    }
    //�ύ
    var form = document.getElementById('form1');
    form.action = "index1.php?model=contract_contract_contract&action=add&act=app&turnChanceIds=" + chanceIds;
    form.submit();
}
// ȫѡ
function chanceCheckAll(obj) {
    if ($(obj).attr('checked') == false) {
        $("input[id^='chanceId-']").attr('checked', false);
        $("#chanceNum").html(0);
    } else {
        $("input[id^='chanceId-']").attr('checked', true);
        $("#chanceNum").html($("#chanceAllNum").html());
    }
}
// ��ѡ
function chanceCheckThis(obj) {
    var num = $("#chanceNum").html() * 1;
    if ($(obj).attr('checked') == false) {
        $("#chanceNum").html(num - 1);
    } else {
        $("#chanceNum").html(num + 1);
    }
}
// �鿴����
function chanceViewForm(objId) {
    showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id=" + objId, 1, objId);
}