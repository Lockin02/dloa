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
    $("#customerName").val(cusInfo[0].Name)
    $("#customerId").val(cusInfo[0].id)
//    $("#customerType").val(cusInfo[0].TypeOne);
//	$("#province").val(cusInfo[0].ProvId);// ����ʡ��Id
//	$("#province").trigger("change");
//	$("#provinceName").val(cusInfo[0].Prov);// ����ʡ��
//	$("#city").val(cusInfo[0].CityId);// ����ID
//	$("#cityName").val(cusInfo[0].City);// ��������
    $("#customerId").val(cusInfo[0].id);
//	$("#areaPrincipal").val(cusInfo[0].AreaLeader);// ��������
//	$("#areaPrincipalId").val(cusInfo[0].AreaLeaderId);// ��������Id
//	$("#areaName").val(cusInfo[0].AreaName);// ��ͬ��������
//	$("#areaCode").val(cusInfo[0].AreaId);// ��ͬ��������
    $("#address").val(cusInfo[0].Address);// �ͻ���ַ
}
function updateCustomerInfo() {
    var customerId = $("#customerId").val();
    var cusInfo = $.ajax({
        type: 'POST',
        url: "?model=customer_customer_customer&action=getCusInfo",
        data: {
            id: customerId
        },
        async: false,
        success: function (data) {
        }
    }).responseText;
    cusInfo = eval("(" + cusInfo + ")");
//    $("#customerType").val(cusInfo[0].TypeOne);
	$("#Province_Id").val(cusInfo[0].ProvId);// ����ʡ��Id
	// $("#province").trigger("change");
	$("#provinceName").val(cusInfo[0].Prov);// ����ʡ��
	$("#City_Id").val(cusInfo[0].CityId);// ����ID
	$("#cityName").val(cusInfo[0].City);// ��������
    $("#customerId").val(cusInfo[0].id);
//	$("#areaPrincipal").val(cusInfo[0].AreaLeader);// ��������
//	$("#areaPrincipalId").val(cusInfo[0].AreaLeaderId);// ��������Id
//	$("#areaName").val(cusInfo[0].AreaName);// ��ͬ��������
//	$("#areaCode").val(cusInfo[0].AreaId);// ��ͬ��������
    $("#address").val(cusInfo[0].Address);// �ͻ���ַ
}
// ֱ���ύ����
function toApp() {
    if(browserChk()) {
        document.getElementById('form1').action = "index1.php?model=contract_contract_contract&action=add&act=app";
        $("#form1").submit();
    }
}
// ���棬����������֤
function toSave() {
    if(browserChk()){
        var form = document.getElementById('form1');
        form.action = "index1.php?model=contract_contract_contract&action=add";

        // �������ͬ������ PMS 594
        var contractMoney = $("#contractMoney").val();
        if($("#contractType").val() == 'HTLX-PJGH' && contractMoney > 10000){
            alert("�������ͬ���ܶ���1��Ԫ, �����1��Ԫ, ���߳������̡�");
            return false;
        }

        // �����Ʒ�嵥
        var productInfoObj = $("#productInfo");
        var rowNum = productInfoObj.productInfoGrid('getCurShowRowNum');
        var productPass = true;
        if (rowNum == '0') {
            alert("��Ʒ�嵥����Ϊ��");
            productPass = false;
        }else {
            // ��Ʒ�ߴ���
            var proLineAllSelected = true;
            productInfoObj.productInfoGrid("getCmpByCol", "newProLineCode").each(function () {
                if ($(this).val() == "") {
                    alert("��ѡ���Ʒ�Ĳ�Ʒ�ߣ�");
                    proLineAllSelected = false;
                    productPass = false;
                }
            });
            if (proLineAllSelected == false) {
                productPass = false;
            }
            // ִ��������
            var exeDeptArr = [];
            var exeDeptAllSelected = true;
            productInfoObj.productInfoGrid("getCmpByCol", "exeDeptId").each(function () {
                if (productPass && $(this).val() == "") {
                    alert("��ѡ���Ʒ��ִ������");
                    exeDeptAllSelected = false;
                    productPass = false;
                } else {
                    var rowNum = $(this).data('rowNum');
                    productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'exeDeptName').
                    val($(this).find("option:selected").text());

                    if ($.inArray($(this).val(), exeDeptArr) == -1) {
                        exeDeptArr.push($(this).val());
                    }
                }
            });
            $("#exeDeptStr").val(exeDeptArr.toString());
            if (exeDeptAllSelected == false) {
                productPass = false;
            }

            var pros = productInfoObj.productInfoGrid("getCmpByCol", "money");
            var proMoney = 0;
            pros.each(function (i, n) {
                //���˵�ɾ������
                if ($("#contract[product][_" + i + "_isDelTag").length == 0) {
                    proMoney = accAdd($(this).val(), proMoney);
                }
            });
            if (productPass && contractMoney != proMoney) {
                alert("�뱣֤��Ʒ����ܺ����ͬ���һ�£�");
                productPass = false;
            }
        }

        if (productPass && checkExeDept()) {
            $('#form1').submit();
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
        $("#moneyName").html("ǩԼ���(��)��");
        $("#signDate").val("");
    } else {
        $("#signDate").val("");
        $("#moneyName").html("Ԥ�ƽ�");
        document.getElementById("signDateNone").style.display = "none";
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
        $('#Maintenance').addClass("validate[required]");
        $("#maintenanceSpan").attr('style', "color:blue");
        // ֽ�ʺ�ͬ���
        // if($('#paperContract').val() == '��'){
        //     $("#paperContractSpan").attr('style', "color:blue");
        //     $('#paperContract').addClass("validate[required]");
        //     $("#paperContractRemark").addClass("validate[required]");
        //     $("#paperContractRemarkSpan").attr('style', "color:blue");
        // }else{
        //     $("#paperReason").hide();
        //     $("#paperContractRemark").val('').removeClass("validate[required]");	//����ǰ��������
        // }
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
        // $("#paperContractSpan").attr('style', "color:black");
        // $('#paperContract').removeClass("validate[required]").val('��');
        // $("#paperContractRemark").removeClass("validate[required]");
        // $("#paperContractRemarkSpan").attr('style', "color:black");
        // $("#paperReason").show();
        // �����ļ�
        $("#checkFileSpan").attr('style', "color:black");
        $('#checkFile').removeClass("validate[required]").val('��');
        // �Ƿ���ǩ
        $("#isRenewed").val("0");
    }  else {
        $('#beginDate').addClass("validate[required]");
        $('#endDate').addClass("validate[required]");
        $("#beginSpan").attr('style', "color:blue");
        $("#endSpan").attr('style', "color:blue");
        //ά��ʱ�䣨�£���Ϊ�Ǳ���
        $('#Maintenance').removeClass("validate[required]");
        $("#maintenanceSpan").attr('style', "color:black");
        // ֽ�ʺ�ͬ���
        // $("#paperContractSpan").attr('style', "color:blue");
        // $('#paperContract').addClass("validate[required]");
        // if($('#paperContract').val() == '��'){
        //     $("#paperReason").show();
        //     $("#paperContractRemark").addClass("validate[required]");
        //     $("#paperContractRemarkSpan").attr('style', "color:blue");
        // }else{
        //     $("#paperReason").hide();
        //     $("#paperContractRemark").val('').removeClass("validate[required]");	//����ǰ��������
        // }

        // �����ļ�
        $("#checkFileSpan").attr('style', "color:blue");
        $('#checkFile').addClass("validate[required]");
    }
    // �ı�ϣ���������� ������֤
    if (obj.value == 'HTLX-FWHT' || obj.value == 'HTLX-YFHT') {
//        $("#shipConditionSpan").attr('style', "color:black");
        $("#deliveryDateSpan").attr('style', "color:black");
        $('#deliveryDate').removeClass("validate[required]");
        $('#shipCondition').removeClass("validate[required]");
    } else {
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

    // changepaperContract("#paperContract");
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

    // �ͻ���ַ
    var linkmanCmp = $("#linkmanListInfo").yxeditgrid(
        "getCmpByCol", "linkmanName");
    linkmanCmp.yxcombogrid_linkman("remove");
    $("#linkmanListInfo").yxeditgrid('remove');
    linkmanList($("#customerId").val());
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
    // $("#country").find("option[value='1']").attr("selected", "selected").trigger("change");
    // $("#province").find("option[value='']").attr("selected", "selected").trigger("change");
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
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {
                    // var customerIdtest = $("#customerIdtest").val();
                    // if (customerIdtest != '' && !typeof(customerIdtest)) {
                    //     if (data.id != customerIdtest) {
                    //         alert("��ѡ��Ŀͻ��������ת���۵Ŀͻ������")
                    //     }
                    // }

                    initCustomerInfo();
                    // ���������Ŀͻ�����
                    $("#customerType").val(data.TypeOne);
                    $("#customerTypeName").val(data.TypeOneName);

                    // ���������ͻ��Ĺ���/ʡ��/������Ϣ
                    $("#country").find("option[value='" + data.CountryId + "']").attr("selected", "selected").trigger("change");
                    $("#province").find("option[value='" + data.ProvId + "']").attr("selected", "selected").trigger("change");
                    $("#country_Id").val(data.CountryId);
                    $("#countryName").val(data.CountryId);
                    $("#Province_Id").val(data.ProvId);
                    $("#provinceName").val(data.Prov);
                    $("#City_Id").val(data.CityId);
                    $("#cityName").val(data.City);

                    if ($("#city").find("option[value='" + data.CityId + "']").length > 0) {
                        $("#city").find("option[value='" + data.CityId + "']").attr("selected", "selected").trigger("change");
                    } else {
                        $("#city").find("option[value='']").attr("selected", "selected").trigger("change");
                    }


//							$("#customerType").val(data.TypeOne);
//							if($("#countryName").val() == "�й�"){
//							  $("#province").val(data.ProvId);// ����ʡ��Id
//							  $("#province").trigger("change");
//							  $("#provinceName").val(data.Prov);// ����ʡ��
//							  $("#city").val(data.CityId);// ����ID
//							  $("#cityName").val(data.City);// ��������
//							}
                    $("#customerId").val(data.id);
//							$("#areaPrincipal").val("");// ��������
//							$("#areaPrincipalId").val("");// ��������Id
//							$("#areaName").val("");// ��ͬ��������
//							$("#areaCode").val("");// ��ͬ��������
                    $("#address").val(data.Address);// �ͻ���ַ
                    // $("#linkmanListInfo").yxeditgrid('remove');
                    //
                    var linkmanCmp = $("#linkmanListInfo").yxeditgrid(
                        "getCmpByCol", "linkmanName");
                    linkmanCmp.yxcombogrid_linkman("remove");
                    $("#linkmanListInfo").yxeditgrid('remove');
                    linkmanList(data.id);

                    // linkmanCmp.each(function() {
                    // var cmp = $(this)
                    // .data("yxcombogrid_linkman");
                    //
                    // if (cmp.grid) {
                    // cmp.grid.options['extParam'] = {
                    // customerId : data.id
                    // };
                    // cmp.grid.reload();
                    // } else {
                    // cmp.options.gridOptions['extParam'] = {
                    // customerId : data.id
                    // };
                    // }
                    // })
                    //�ͻ���������Ӫ����ģ������ͻ����ͼ�ʡ��
                    // var typeName = data.TypeOne_name;
                    // if(typeName != undefined && typeName.indexOf("��Ӫ��") != -1){
                    // 	$("#customerType").find("option[text='" + typeName + "']").attr("selected","selected");
                    // 	$("#province").find("option[text='" + data.Prov + "']").attr("selected","selected").trigger("change");
                    // }else{
                    // 	$("#customerType").val("");
                    // 	$("#province").val("").trigger("change");
                    // }
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
                'row_dblclick': function (e, row, data) {
//                    $("#areaName").val("");
//                    $("#areaCode").val("");
//                    $("#areaPrincipal").val("");
//                    $("#areaPrincipalId").val("");
//
//                    $("#areaName").yxcombogrid_area("remove");
//							regionList();
                }
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

//Ԥ�ƽ�����
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
            var winRate = $("#winRate").val();
            var signDate = $("#signDate").val();
            if (winRate == '100%' && signDate == '') {
                validate({
                    "signDate": {
                        required: true
                    }
                })
            }
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
            $("#form1").submit();//������֤�����ύ���������Ҫ����������ΰ�ť�����ύ����bug
        },
        failure: false
    });
    /**
     * ��֤��Ϣ
     */
    validate({
        "winRate": {
            required: true
        }, "currency": {
            required: true
        }
//						,"contractCode" : {
//							required : true
//						}
        ,
        "contractType": {
            required: true
        },
        "contractName": {
            required: true
        },
        "customerName": {
            required: true
        },
//						"beginDate" : {
//							required : true
//						},
//						"endDate" : {
//							required : true
//						},
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
            money: true
        },
        "customerType": {
            required: true
        },
        "areaCode": {
            required: true
        },
        "areaName": {
            required: true
        },
        "businessBelongName": {
            required: true
        },
        "isRenewed": {
            required: true
        }
    });

    //���ۺ�ͬ ȥ�� ��ͬ��ʼ��ֹ����
    if ($("#contractType").val() == 'HTLX-XSHT') {
        $('#beginDate').removeClass("validate[required]");
        $('#endDate').removeClass("validate[required]");
        $("#beginSpan").attr('style', "color:black");
        $("#endSpan").attr('style', "color:black");
    } else {
        $('#beginDate').addClass("validate[required]");
        $('#endDate').addClass("validate[required]");
        $("#beginSpan").attr('style', "color:blue");
        $("#endSpan").attr('style', "color:blue");
    }
    // �ı�ϣ���������� ������֤
    if ($("#contractType").val() == 'HTLX-FWHT' || $("#contractType").val() == 'HTLX-YFHT') {
//        $("#shipConditionSpan").attr('style', "color:black");
        $("#deliveryDateSpan").attr('style', "color:black");
        $('#deliveryDate').removeClass("validate[required]");
        $('#shipCondition').removeClass("validate[required]");
    } else {
        $('#deliveryDate').addClass("validate[required]");
        $('#shipCondition').addClass("validate[required]");
//        $("#shipConditionSpan").attr('style', "color:blue");
        $("#deliveryDateSpan").attr('style', "color:blue");
    }
});
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
function sub() {
    $("form").bind("submit", function () {
        if(!browserChk()){
            return false;
        }

        if ($("#contractType").val() == 'HTLX-FWHT' || $("#contractType").val() == 'HTLX-ZLHT') {
            if ($("#beginDate").val() == '' || $("#endDate").val() == '') {
                alert("����ȷ��д��ͬ  ��ʼ/�������ڡ�");
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
        var allNum = accAddMore(addArr);
//		if (allNum != '100') {
//			alert("������ĸ�������ռ��֮��Ϊ��" + allNum + "%�� �뽫ռ��֮�͵���Ϊ 100% ");
//			return false;
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

        // �������ͬ������ PMS 594
        if($("#contractType").val() == 'HTLX-PJGH' && contractMoney > 10000){
            alert("�������ͬ���ܶ���1��Ԫ, �����1��Ԫ, ���߳������̡�");
            return false;
        }

//        var contractMoneyCur = $("#contractMoneyCur").val();
//        var currency = $("#currency").val();
        var allCost = 0;
        var j = 0;
        var notInvoice = true; // ����Ʊ
        for (i = 0; i < itemLength; i++) {
            if ($("#" + itemArr[i]).is(":checked")) {
                if (itemArr[i] == "HTBKP") {
                    notInvoice = false;
                }
                if ($("#" + itemArr[i] + "Money_v").val() == "") {
                    $("#" + itemArr[i] + "Money_v").addClass("validate[required]");
                } else {
                    allCost = accAdd(allCost, $("#" + itemArr[i] + "Money_v").val(), 2);
                    $("#" + itemArr[i] + "Money_v").removeClass("validate[required]");
                }
            } else {
                j++;
            }
        }

        if (j == itemLength) {
            alert("��Ʊ���ͱ��빴ѡ");
            return false;
        }
//        if (currency == "�����") {
        if (notInvoice && allCost != contractMoney) {
            alert("��ͬ��������ڿ�Ʊ���ͽ��");
            return false;
        }
//        } else {
//            if (allCost != contractMoneyCur) {
//                alert("��ͬ��������ڿ�Ʊ���ͽ��");
//                return false;
//            }
//        }

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
            // ִ��������
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
            $("#exeDeptStr").val(exeDeptArr.toString());
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
        if ($("#contractType").val() != "HTLX-PJGH" && $("#uploadfileList2").html() == "" || $("#uploadfileList2").html() == "�����κθ���") {
            alert("���ϴ���ͬ�ı����ļ���");
            return false;
        }
        return true;
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
 * ����Ʊ����ѡ�����
 * @param {} obj
 */
function isBKPCheck(obj) {
    var invoiceCodeChkBoxs = $("input[name^='contract[invoiceCode][]']");
    var isChecked = $("#" + obj).is(':checked');
    if (isChecked) {
        $.each(invoiceCodeChkBoxs,function(i,obj){
            if($(obj).val() != 'HTBKP'){
                $(obj).attr("checked",false);
                isCheckType($(obj).val());
                $(obj).attr("disabled",true);
            }
        });
    } else {
        $.each(invoiceCodeChkBoxs,function(i,obj){
            if($(obj).val() != 'HTBKP'){
                if($(obj).attr('data-isdisable') != 1){
                    $(obj).removeAttr("disabled");
                }
            }
        });
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
    // $("#province").val($("#ProvinceId").val())
    // $("#provinceName").val($("#ProvinceName2").val())
    //
    // $("#city").val($("#CityID").val())
    // $("#cityName").val($("#cityName2").val())
    //
    // var countryId = $("#country").val();
    // var proId = $("#province").val();
    // var cityId = $("#CityID").val();
    // $("#country").val(countryId);// ��������Id
    // $("#country").trigger("change");
    // $("#province").val(proId);// ����ʡ��Id
    // $("#province").trigger("change");
    // $("#city").val(cityId);// ����ID
    // $("#city").trigger("change");

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

    // ��������
    fileTypeArr = getData('FJLX');
    addDataToSelect(fileTypeArr, 'fileType');

    // ֻ�����������Ϊ�����ݲ���
    if ($("#areaName").val() != "�����ݲ�") {
        var defaultAreaName = $('#originalAreaName').val();
        // ����ִ��������Ϣ
        setAreaInfo(defaultAreaName);
    }

    // $("#province").change(function () {
    //     setAreaInfo();
    //     setProExeDept();
    // });
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

    var defaultAreaName = $('#originalAreaName').val();
    setAreaInfo(defaultAreaName);
    setProExeDept();
})

//�Զ����ҹ�������
function setAreaInfo(defaultAreaName) {
    // ֻ�����������Ϊ�����ݲ���
    if ($("#originalAreaName").val() != "�����ݲ�") {
        var customerType = $("#customerType").val();
        var province = $("#Province_Id").val();
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
                        var selected = "";
                        var thisData = $(this)[0];
                        if (defaultAreaName && defaultAreaName == thisData.areaName) {
                            selected = "selected = 'selected'";
                            $("#areaName").val(thisData.areaName);
                            $("#areaCode").val(thisData.id);
                            $("#areaPrincipal").val(thisData.areaPrincipal);// ��������
                            $("#areaPrincipalId").val(thisData.areaPrincipalId);// ��������Id
                            $("#exeDeptCode").val(thisData.exeDeptCode);// ִ��������
                            $("#exeDeptName").val(thisData.exeDeptName);// ִ������
                        }
                        optStr += '<option value="' + thisData.id + '" data-areaPrincipal="' + thisData.areaPrincipal + '" data-areaPrincipalId="' + thisData.areaPrincipalId + '" data-exeDeptCode="' + thisData.exeDeptCode + '" data-exeDeptName="' + thisData.exeDeptName + '" title="' + thisData.areaName + '" ' + selected + '>' + thisData.areaName + '</option>';
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

$(function () {
    // ���к�ͬȨ�޲�ͬ�����˲���param��ͬ
    var param = '';
    param = {
        'states': '2,3,4,7',
        'ExaStatus': '���',
        'prinvipalId': $("#userId").val(),//ֻ�ܲ鿴���˺�ͬ
        'customerId': $("#customerId").val()
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
    //������ǩ��ͬ�ţ���ǩ��ͬ����
    $("#parentCode").parent("td").hide().prev("td").hide();
    $("#parentName").parent("td").hide().prev("td").hide();
});

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

//��ȡ��Ʒִ������
function getProExeDept() {
    var province = $("#province").val();
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
//    var returnValue = getProExeDept();
//    if (returnValue) {
//    	var productInfoObj = $("#productInfo");
//    	var exeDeptObj = productInfoObj.productInfoGrid("getCmpByCol", "exeDeptId");
//    	if(exeDeptObj.length > 0){
//        	var productLine = returnValue[0].productLine;
//        	var productLineName = returnValue[0].productLineName;
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
                    if (productId == '' || productId == undefined) {// ���޸�ԭ���Ĳ�Ʒ��ִ������
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

//��������ĳ����Ʒִ������
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

