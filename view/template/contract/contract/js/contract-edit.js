$(function () {
    // ��������
    fileTypeArr = getData('FJLX');
    addDataToSelect(fileTypeArr, 'fileType');
    // ��Ʊ����
    var added = $("#addedV").val();
    if (added == "on") {
        $("#addedC").attr("checked", true);
        $("#addedHide").show();
    } else {
        $("#addedC").attr("checked", false);
    }

    var exportInv = $("#exportInvV").val();
    if (exportInv == "on") {
        $("#exportInvC").attr("checked", true);
        $("#exportInvHide").show();
    } else {
        $("#exportInvC").attr("checked", false);
    }

    var serviceInv = $("#serviceInvV").val();
    if (serviceInv == "on") {
        $("#serviceInvC").attr("checked", true);
        $("#serviceInvHide").show();
    } else {
        $("#serviceInvC").attr("checked", false);
    }
    //���ۺ�ͬ��ά��ʱ�䣨�£���Ϊ����
    if ($("#contractType").val() == 'HTLX-XSHT') {
        $("#Maintenance").addClass("validate[required]");
        $("#maintenanceSpan").attr('style', "color:blue");
    }

    //��ͬ����ѡ���Ƿ����ͬʱ����Ʒ��������Ǳ���
    if ($("#contractType").val() == 'HTLX-FWHT') {
        $("#warrantyClauseSpan").attr('style', "color:black");
    } else {
        $('#warrantyClause').addClass("validate[required]");
        $("#warrantyClauseSpan").attr('style', "color:blue");
    }
})
/**
 * ��Ʊ���Ϳ���
 *
 * @return {Boolean}
 */
function Kcontrol(obj) {
    var KPLX = document.getElementById(obj + "C").checked
    var objHide = obj + "Hide";
    var objMoney = obj + "Money";
    if (KPLX == true) {
        document.getElementById(objHide).style.display = "";
        $("#" + obj + "").val("on");
    } else {
        document.getElementById(objHide).style.display = "none";
        $("#" + obj + "").val("");
        $("#" + objMoney + "").val("0.00");
        $("#" + objMoney + "_v").val("0.00");
    }
}

/**
 * �Ƿ���±����ж�
 */
function changeRadio() {
    // ����������֤
    if ($("#uploadfileList2").html() == ""
        || $("#uploadfileList2").html() == "�����κθ���") {
        alert('�������ǰ��Ҫ�ϴ���ͬ�ı�!');
        $("#isNeedStampNo").attr("checked", true);
        //����������Ⱦ
        $("#radioSpan").attr('style', "color:black");
        var stampTypeObj = $("#stampType");
        stampTypeObj.yxcombogrid_stampconfig('remove');
        stampTypeObj.val('');
        return false;
    }
    // ��ʾ������
    if ($("#isNeedStampYes").attr("checked")) {
        $("#radioSpan").attr('style', "color:blue");
        // ��ֹ�ظ������������
        if ($("#stampType").yxcombogrid_stampconfig('getIsRender') == true)
            return false;

        // ����������Ⱦ
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
        // ����������Ⱦ
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
//// ������ת��������
//$(function() {
//	var ids = $("#ids").val();
//	if (ids != '') {
//		var returnValue = $.ajax({
//			type : 'POST',
//			url : "?model=projectmanagent_borrow_borrow&action=getBorrowequInfo",
//			data : {
//				ids : ids
//			},
//			async : false,
//			success : function(data) {
//			}
//		}).responseText;
//		returnValue = eval("(" + returnValue + ")");
//		if (returnValue) {
//			var g = $("#borrowConEquInfo").data("yxeditgrid");
//			var rn = $("#borrowConEquInfo").yxeditgrid('getAllAddRowNum');
//			var j = rn+1;
//			// ѭ���������
//			for (var i = 0; i < returnValue.length; i++) {
//				outJson = {
//					"productId" : returnValue[i].productId,
//					"productCode" : returnValue[i].productNo,
//					"productName" : returnValue[i].productName,
//					"productModel" : returnValue[i].productModel,
//					"number" : returnValue[i].number - returnValue[i].backNum,
//					"price" : returnValue[i].price,
//					"money" : returnValue[i].money,
//					"warrantyPeriod" : returnValue[i].warrantyPeriod,
//					"isBorrowToorder" : 1,
//					"toBorrowId" : returnValue[i].borrowId,
//					"toBorrowequId" : returnValue[i].id
//				};
//				// ��������
//				$("#customerIdtest").val(returnValue[i].customerId);
//				g.addRow(j, outJson);
//				j++;
//			}
//		}
//	}
//});
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
$(function () {
    var winRate = $("#winRate").val();
    if (winRate == '100%') {
        $("#moneyName").html("ǩԼ���(��)��");
        document.getElementById("signDateNone").style.display = "";
    } else {
        $("#moneyName").html("Ԥ�ƽ��(��)��");
        document.getElementById("signDateNone").style.display = "none";
    }
});
// �ж��Ƿ�ǩԼ
function isSign(obj) {
    if (obj.value == '100%') {
        document.getElementById("signDateNone").style.display = "";
        $("#moneyName").html("ǩԼ���(��)��");
        $("#signDate").val("");
    } else {
        $("#signDate").val("");
        $("#moneyName").html("Ԥ�ƽ��(��)��");
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
        $("#paperContractSpan").attr('style', "color:blue");
        $('#paperContract').addClass("validate[required]");

        if($('#paperContract').val() == '��'){
            $("#paperContractRemark").addClass("validate[required]");
            $("#paperContractRemarkSpan").attr('style', "color:blue");
        }else{
            $("#paperContractRemark").removeClass("validate[required]");
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
    }  else {
        $('#beginDate').addClass("validate[required]");
        $('#endDate').addClass("validate[required]");
        $("#beginSpan").attr('style', "color:blue");
        $("#endSpan").attr('style', "color:blue");
        //ά��ʱ�䣨�£���Ϊ�Ǳ���
        $('#Maintenance').removeClass("validate[required]");
        $("#maintenanceSpan").attr('style', "color:black");
        // ֽ�ʺ�ͬ���
        $("#paperContractSpan").attr('style', "color:blue");
        $('#paperContract').addClass("validate[required]");
        if($('#paperContract').val() == '��'){
            $("#paperContractRemark").addClass("validate[required]");
            $("#paperContractRemarkSpan").attr('style', "color:blue");
        }else{
            $("#paperContractRemark").removeClass("validate[required]");
        }

        // �����ļ�
        $("#checkFileSpan").attr('style', "color:blue");
        $('#checkFile').addClass("validate[required]");
    }
    // �ı�ϣ���������� ������֤
    if (obj.value == 'HTLX-FWHT' || obj.value == 'HTLX-YFHT') {
        $("#trialprojectNone").show();
        $('#deliveryDate').removeClass("validate[required]");
        $('#shipCondition').removeClass("validate[required]");
//        $("#shipConditionSpan").attr('style', "color:black");
        $("#deliveryDateSpan").attr('style', "color:black");
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
$(function () {
    var contractType = $("#contractType").val();
    if (contractType == 'HTLX-XSHT') {
        $('#beginDate').removeClass("validate[required]");
        $('#endDate').removeClass("validate[required]");
        $("#beginSpan").attr('style', "color:black");
        $("#endSpan").attr('style', "color:black");
    } else if (contractType == 'HTLX-PJGH') {
        //ȥ����ͬ��ʼ��ֹ����
        $('#beginDate').removeClass("validate[required]");
        $('#endDate').removeClass("validate[required]");
        $("#beginSpan").attr('style', "color:black");
        $("#endSpan").attr('style', "color:black");
        // ֽ�ʺ�ͬ���
        $("#paperContractSpan").attr('style', "color:black");
        $('#paperContract').removeClass("validate[required]");
        $("#paperContractRemark").removeClass("validate[required]");
        $("#paperContractRemarkSpan").attr('style', "color:black");
        // �����ļ�
        $("#checkFileSpan").attr('style', "color:black");
        $('#checkFile').removeClass("validate[required]");
    }else {
        $('#beginDate').addClass("validate[required]");
        $('#endDate').addClass("validate[required]");
        $("#beginSpan").attr('style', "color:blue");
        $("#endSpan").attr('style', "color:blue");
    }
    if (contractType == 'HTLX-FWHT' || contractType == 'HTLX-YFHT') {
        $("#trialprojectNone").show();
        $('#deliveryDate').removeClass("validate[required]");
        $('#shipCondition').removeClass("validate[required]");
//        $("#shipConditionSpan").attr('style', "color:black");
        $("#deliveryDateSpan").attr('style', "color:black");
    } else {
        $("#trialprojectNone").hide();
        $('#deliveryDate').addClass("validate[required]");
        $('#shipCondition').addClass("validate[required]");
//        $("#shipConditionSpan").attr('style', "color:blue");
        $("#deliveryDateSpan").attr('style', "color:blue");
    }
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

// �ͻ���Ϣ��ʼ��
function initCustomerInfo() {
    $("#customerType").val('');
    $("#customerTypeName").val('');
    $("#country").find("option[value='1']").attr("selected", "selected").trigger("change");
    $("#province").find("option[value='']").attr("selected", "selected").trigger("change");
    $("#chooseAreaName").find("option[value='']").attr("selected", "selected").trigger("change").hide();
    $("#areaName").val('').show();
    $("#areaCode").val('');
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

//                    if ($("#countryName").val() == "�й�") {
//                        $("#province").val(data.ProvId);// ����ʡ��Id
//                        $("#province").trigger("change");
//                        $("#provinceName").val(data.Prov);// ����ʡ��
//                        $("#city").val(data.CityId);// ����ID
//                        $("#cityName").val(data.City);// ��������
//                    }
                    $("#customerId").val(data.id);
//					$("#areaPrincipal").val(data.AreaLeader);// ��������
//					$("#areaPrincipalId_v").val(data.AreaLeaderId);// ��������Id
//					$("#areaName").val(data.AreaName);// ��ͬ��������
//					$("#areaCode").val(data.AreaId);// ��ͬ��������
                    $("#address").val(data.Address);// �ͻ���ַ
                    var linkmanCmp = $("#linkmanListInfo").yxeditgrid(
                        "getCmpByCol", "linkmanName");
                    linkmanCmp.yxcombogrid_linkman("remove");
                    $("#linkmanListInfo").yxeditgrid('remove');
                    linkmanList(data.id, 1);


                    $("#parentCode").yxcombogrid_allcontract("remove");
                    $("#parentId").val("");
                    $("#parentName").val("");
                    $("#parentCode").val("");
                    contractSelect();
                    //�ͻ���������Ӫ����ģ������ͻ����ͼ�ʡ��
                    // var typeName = data.TypeOne_name;
                    // if(typeName != undefined && typeName.indexOf("��Ӫ��") != -1){
                    // 	$("#customerType").find("option[text='" + typeName + "']").attr("selected","selected");
                    // 	$("#province").find("option[text='" + data.Prov + "']").attr("selected","selected");
                    // }else{
                    // 	$("#customerType").val("");
                    // 	$("#province").val("");
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
                    if ($("#originalAreaName").val() != "�����ݲ�") {
                        $("#areaName").val("");
                        $("#areaCode").val("");
                        $("#areaPrincipal").val("");
                        $("#areaPrincipalId").val("");
                    }

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
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#trialprojectId").val(data.id);
                    $("#trialprojectName").val(data.projectName);
                }
            }
        }
    });

    if ($("#customerId").val() != '') {
        var customerIdVal = $("#customerId").val();
        var cusInfo = $.ajax({
            type: 'POST',
            url: "?model=customer_customer_customer&action=getCusInfo",
            data: {
                id: customerIdVal
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

            // ���������ͻ��Ĺ���/ʡ��/������Ϣ
            $("#contractContryId").val(cusInfo[0].CountryId);
            $("#contractProvinceId").val(cusInfo[0].ProvId);
            $("#contractCityId").val(cusInfo[0].CityId);
        }
    }

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
        $("#contractMoney_v").attr('class', "txt").attr('readOnly', false);
        $("#contractTempMoney_v").attr('class', "txt").attr('readOnly', false);
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

// �༭ҳ ��������
$(function () {
    var shipCondition = $("#shipConditionV").val();
    if (shipCondition == '') {
        document.getElementById("shipCondition").options.add(new Option("������",
            ""));
        document.getElementById("shipCondition").options.add(new Option("��������",
            "0"));
    } else if (shipCondition == '0') {
        document.getElementById("shipCondition").options.add(new Option("��������",
            "0"));
        document.getElementById("shipCondition").options.add(new Option("������",
            ""));
    } else if (shipCondition == '1') {
        document.getElementById("shipCondition").options.add(new Option("��������",
            "0"));
        document.getElementById("shipCondition").options.add(new Option("������",
            ""));
    }
    // �Ƿ���ǩ
    var isRenewed = $("#isRenewedV").val();
    if (typeof(isRenewed) != 'undefined') {
        if (isRenewed == '') {
            document.getElementById("isRenewed").options.add(new Option("..��ѡ��..",
                ""));
            document.getElementById("isRenewed").options.add(new Option("��ǩ��ͬ",
                "0"));
            document.getElementById("isRenewed").options.add(new Option("��ǩ��ͬ",
                "1"));
        } else if (isRenewed == '0') {
            document.getElementById("isRenewed").options.add(new Option("��ǩ��ͬ",
                "0"));
            document.getElementById("isRenewed").options.add(new Option("��ǩ��ͬ",
                "1"));
        } else if (isRenewed == '1') {
            document.getElementById("isRenewed").options.add(new Option("��ǩ��ͬ",
                "1"));
            document.getElementById("isRenewed").options.add(new Option("��ǩ��ͬ",
                "0"));
        }
    }
    // �Ƿ��ܺ�ͬ
    var isFrame = $("#isFrameV").val();
    if (typeof(isFrame) != 'undefined') {
        if (isFrame == '0') {
            document.getElementById("isFrame").options.add(new Option("��",
                "0"));
            document.getElementById("isFrame").options.add(new Option("��",
                "1"));
        } else if (isFrame == '1') {
            document.getElementById("isFrame").options.add(new Option("��",
                "1"));
            document.getElementById("isFrame").options.add(new Option("��",
                "0"));
        }
    }

    // ֽ�ʺ�ͬ
    var paperContract = $("#paperContractV").val();
    if (typeof(paperContract) != 'undefined') {
        $("#paperReason").hide();
        if (paperContract == '��') {
            document.getElementById("paperContract").options.add(new Option("��",
                "��"));
            document.getElementById("paperContract").options.add(new Option("��",
                "��"));
        } else if (paperContract == '��') {
            $("#paperReason").show();
            document.getElementById("paperContract").options.add(new Option("��",
                "��"));
            document.getElementById("paperContract").options.add(new Option("��",
                "��"));
        }
    }

    // �����������Ҫ��д�޺�ͬԭ��
    if ($("#paperContract").val() == '��') {
        $("#paperReason").show();
        if ($("#contractType").val() != "HTLX-PJGH") {
            $("#paperContractRemark").addClass("validate[required]");
        }
    } else {
        $("#paperReason").hide();
        $("#paperContractRemark").val('').removeClass("validate[required]");	//����ǰ��������
    }

    var checkFile = $("#checkFileViewV").val();
    if (checkFile == '��') {
        document.getElementById("checkFile").options.add(new Option("��",
            "��"));
        document.getElementById("checkFile").options.add(new Option("��",
            "��"));
        $("#checkFileView1").show();
        $("#checkFileView2").show();
    } else {
        if (document.getElementById("checkFile")) {
            document.getElementById("checkFile").options.add(new Option("��",
                "��"));
            document.getElementById("checkFile").options.add(new Option("��",
                "��"));
            $("#checkFileView1").hide();
            $("#checkFileView2").hide();
        }
    }
});

// ���ؽ��
$(function () {
    conversion();
    $("#currency").yxcombogrid_currency({
        hiddenId: 'id',
        isFocusoutCheck: false,
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
    createFormatOnClick("orderTempMoney_c");
    createFormatOnClick("orderMoney_c");
});

// ʡ��
$(function () {
    var countryId = $("#contractContryId").val();
    var proId = $("#contractProvinceId").val();
    var cityId = $("#contractCityId").val();
    $("#country").val(countryId);// ��������Id
    $("#country").trigger("change");
    $("#province_Id").val(proId);// ����ʡ��Id
    $("#province").val(proId);// ����ʡ��Id
    $("#province").trigger("change");
    $("#city").val(cityId);// ����ID
    $("#city").trigger("change");

});

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
            sub();
            // $("#form1").trigger("sub");
            if ($("#isChangeTip").val() == "1") {
                if ($("#isSub").val() == "1") {
                    //����ύ����
                    var subUrl;
                    $.ajax({
                        type: 'POST',
                        url: "?model=contract_contract_contract&action=changeSubAjax",
                        data: $("form").serialize(),
                        async: false,
                        success: function (data) {
                            if (data == '0') {
                                //2016��10��17 PMS2123 ���ظù���
//                                if (confirm('��⵽��ǰ��������ύ�������Ƿ��ֹ�ѡ���ύ�����������')) {
//                                    $("#isChangeSub").val("1");
//                                }else{
//                                    $("#isChangeSub").val("0");
//                                }
                                $("#isChangeSub").val("1");
                            } else {
                                $("#isChangeSub").val("1");
                            }
                            subUrl = "?model=contract_contract_contract&action=change";
                        }
                    });
                    document.getElementById('form1').action = subUrl;
                } else {
                    document.getElementById('form1').action = "?model=contract_contract_contract&action=change";
                }
            }
            $("#form1").submit();// ������֤�����ύ���������Ҫ����������ΰ�ť�����ύ����bug
        },
        failure: false
    })
    /**
     * ��֤��Ϣ
     */
    validate({

        "winRate": {
            required: true
        },
        "currency": {
            required: true
        }
        // ,"contractCode" : {
        // required : true
        // }
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
//		"deliveryDate" : {
//			required : true
//		},
//		"shipCondition" : {
//			required : true
//		},
//        "warrantyClause": {
//            required: true
//        },
        "contractTempMoney_v": {
            required: true
        },
        "contractMoney_v": {
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
        }
    });
});
/**
 * ��ͬ������������
 *
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
            for (var i = 0; i <= progressNum; i++) {
                addArr.push($('#progresspaymentPro' + i).val());
            }
        }
        if (otherNum > '0') {
            for (var i = 0; i <= otherNum; i++) {
                addArr.push($('#otherpaymentPro' + i).val());
            }
        }
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

        if ($("#isChangeTip").val() == 1) {
            var returnValue = $.ajax({
                type: 'POST',
                url: "?model=contract_contract_contract&action=ajaxChkContractMoneyForChange",
                data: {
                    contractId: $("#oldId").val(),
                    contractMoney: contractMoney
                },
                async: false,
                success: function (data) {
                }
            }).responseText;

            if(returnValue != 'ok'){
                alert("������ĺ�ͬ���С���ѿ�Ʊ���+����Ʊ���+�ۿ�����鷳����ۿ������Ʊ��лл��");
                return false;
            }
        }

//        var contractMoneyCur = $("#contractMoneyCur").val();
//        var currency = $("#currency").val();
        var allCost = 0;
        var j = 0;
        try {
            var notInvoice = true; // ����Ʊ
            var invoiceType, invoiceTypeMoney;
            for (i = 0; i < itemLength; i++) {
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
            alert("���ϴ��������ļ���");
            return false;
        }
        // �������ж�
        if ($("#isChange").val() == 1) {
            isFormChange();
        }
        // ������ת���۹����̻��������ҳ�����账��
        if ($("#isChangeTip").val() == 1 && $("#ids").val() == '') {
            return true;
        } else {
            if (!showChance()) {
                tb_show(null, '#TB_inline?height=600&width=800&inlineId=showChance', false);
                return false;
            } else {
                return true;
            }
        }
    });
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
 * ��鿪Ʊ��Ϣ
 * @returns {boolean}
 */
function invoiceChk(){
    var dataCode = $("#dataCode").val();
    var contractMoney = $("#contractMoney").val();
    var itemArr = dataCode.split(',');
    var itemLength = itemArr.length;
    var allCost = 0;
    var j = 0;
    var pass = true;
    try {
        var notInvoice = true; // ����Ʊ
        var invoiceType, invoiceTypeMoney;
        for (i = 0; i < itemLength; i++) {
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
            pass = false;
        }
        // �����ѡ�˿�Ʊ���ͣ�������ѡ�˲���Ʊ������ʾ����
        if (itemLength - j > 1 && notInvoice == false) {
            alert("ѡ�񲻿�Ʊʱ��������д������Ʊ����");
            pass = false;
        }
        if (notInvoice == true && allCost != contractMoney) {
            alert("��ͬ��������ڿ�Ʊ���ͽ��");
            pass = false;
        }
    } catch (e) {

    }
    return pass;
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
//    deliveryCon();
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
// 	 $("#areaName").attr("readonly",true);
// 	 $("#areaName").attr('class',"readOnlyTxtNormal");
    // ֻ�����������Ϊ�����ݲ���
    if ($("#areaName").val() != "�����ݲ�") {
        var defaultAreaName = $('#originalAreaName').val();
        // ����ִ��������Ϣ
        setAreaInfo(defaultAreaName);
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
})
//�Զ����ҹ�������
function setAreaInfo(defaultAreaName) {
    // ֻ�����������Ϊ�����ݲ���
    if ($("#originalAreaName").val() != "�����ݲ�") {
        var customerType = $("#customerType").val();
        var province = $("#province_Id").val();
        if (province == "") {
            province = $("#contractProvinceId").val();// ���ڳ�ʼ��
        }
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


/**
 * �Ƿ���ǩ
 */

$(function () {
    contractSelect()
    if ($("#isRenewedV").val() == '0' || $("#isRenewedV").val() == '') {
        $("#parentCode").parent("td").hide().prev("td").hide();
        $("#parentName").parent("td").hide().prev("td").hide();
    } else {
        $("#parentCode").parent("td").show().prev("td").show();
        $("#parentName").parent("td").show().prev("td").show();
    }
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

//��ȡ��Ʒִ������
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
//	var returnValue = getProExeDept();
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
            // console.log( returnData);
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

//ֱ���ύ����
function toApp() {
    if(browserChk()){
        document.getElementById('form1').action = "index1.php?model=contract_contract_contract&action=edit&act=app";
        $("#form1").submit();
    }
}

//���棬����������֤
function toSave() {
    var form = document.getElementById('form1');
    form.action = "index1.php?model=contract_contract_contract&action=edit";

    // �������ͬ������ PMS 594
    var contractMoney = $("#contractMoney").val();
    if(browserChk() && invoiceChk()){
        if($("#contractType").val() == 'HTLX-PJGH' && contractMoney > 10000){
            alert("�������ͬ���ܶ���1��Ԫ, �����1��Ԫ, ���߳������̡�");
            return false;
        }else if (checkExeDept()) {
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

/********** ������ת���۴��������̻����� **********/
//�̻���Ⱦ
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
    if ($("#isChangeTip").val() == "1") {//���
        form.action = "index1.php?model=contract_contract_contract&action=change&turnChanceIds=" + chanceIds;
    } else {//�༭
        form.action = "index1.php?model=contract_contract_contract&action=edit&act=app&turnChanceIds=" + chanceIds;
    }
    form.submit();
}
//ȫѡ
function chanceCheckAll(obj) {
    if ($(obj).attr('checked') == false) {
        $("input[id^='chanceId-']").attr('checked', false);
        $("#chanceNum").html(0);
    } else {
        $("input[id^='chanceId-']").attr('checked', true);
        $("#chanceNum").html($("#chanceAllNum").html());
    }
}
//��ѡ
function chanceCheckThis(obj) {
    var num = $("#chanceNum").html() * 1;
    if ($(obj).attr('checked') == false) {
        $("#chanceNum").html(num - 1);
    } else {
        $("#chanceNum").html(num + 1);
    }
}
//�鿴����
function chanceViewForm(objId) {
    showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id=" + objId, 1, objId);
}