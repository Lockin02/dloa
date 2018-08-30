$(function () {
    // 附件类型
    fileTypeArr = getData('FJLX');
    addDataToSelect(fileTypeArr, 'fileType');
    // 开票类型
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
    //销售合同，维保时间（月）设为必填
    if ($("#contractType").val() == 'HTLX-XSHT') {
        $("#Maintenance").addClass("validate[required]");
        $("#maintenanceSpan").attr('style', "color:blue");
    }

    //合同类型选择是服务合同时，产品保修条款非必填
    if ($("#contractType").val() == 'HTLX-FWHT') {
        $("#warrantyClauseSpan").attr('style', "color:black");
    } else {
        $('#warrantyClause').addClass("validate[required]");
        $("#warrantyClauseSpan").attr('style', "color:blue");
    }
})
/**
 * 开票类型控制
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
 * 是否盖章必填判断
 */
function changeRadio() {
    // 附件盖章验证
    if ($("#uploadfileList2").html() == ""
        || $("#uploadfileList2").html() == "暂无任何附件") {
        alert('申请盖章前需要上传合同文本!');
        $("#isNeedStampNo").attr("checked", true);
        //盖章类型渲染
        $("#radioSpan").attr('style', "color:black");
        var stampTypeObj = $("#stampType");
        stampTypeObj.yxcombogrid_stampconfig('remove');
        stampTypeObj.val('');
        return false;
    }
    // 显示必填项
    if ($("#isNeedStampYes").attr("checked")) {
        $("#radioSpan").attr('style', "color:blue");
        // 防止重复数理化下拉表格
        if ($("#stampType").yxcombogrid_stampconfig('getIsRender') == true)
            return false;

        // 盖章类型渲染
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
        // 盖章类型渲染
        var stampTypeObj = $("#stampType");
        stampTypeObj.yxcombogrid_stampconfig('remove');
        stampTypeObj.val('');
    }
}

/**
 * 从新盖章选择
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
//// 借试用转销售物料
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
//			// 循环拆分数组
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
//				// 插入数据
//				$("#customerIdtest").val(returnValue[i].customerId);
//				g.addRow(j, outJson);
//				j++;
//			}
//		}
//	}
//});
// 表单收缩
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
        $("#moneyName").html("签约金额(￥)：");
        document.getElementById("signDateNone").style.display = "";
    } else {
        $("#moneyName").html("预计金额(￥)：");
        document.getElementById("signDateNone").style.display = "none";
    }
});
// 判断是否签约
function isSign(obj) {
    if (obj.value == '100%') {
        document.getElementById("signDateNone").style.display = "";
        $("#moneyName").html("签约金额(￥)：");
        $("#signDate").val("");
    } else {
        $("#signDate").val("");
        $("#moneyName").html("预计金额(￥)：");
        document.getElementById("signDateNone").style.display = "none";
    }
}

// 根据合同类型改变合同属性的加载项
function changeNature(obj) {
    $('#contractNature').empty();
    var objV = document.getElementById('contractNature');
    if (obj.value != "") {
        contractNatureCodeArr = getData(obj.value);
        objV.options.add(new Option("...请选择...", "")); // 这个兼容IE与firefox
        addDataToSelect(contractNatureCodeArr, 'contractNature');
    } else {
        objV.options.add(new Option("...请选择...", "")); // 这个兼容IE与firefox
    }
    //销售合同
    if (obj.value == 'HTLX-XSHT') {
        //去除合同开始截止日期
        $('#beginDate').removeClass("validate[required]");
        $('#endDate').removeClass("validate[required]");
        $("#beginSpan").attr('style', "color:black");
        $("#endSpan").attr('style', "color:black");
        //维保时间（月）设为必填
        $('#Maintenance').addClass("validate[required]");
        $("#maintenanceSpan").attr('style', "color:blue");
        // 纸质合同相关
        $("#paperContractSpan").attr('style', "color:blue");
        $('#paperContract').addClass("validate[required]");

        if($('#paperContract').val() == '无'){
            $("#paperContractRemark").addClass("validate[required]");
            $("#paperContractRemarkSpan").attr('style', "color:blue");
        }else{
            $("#paperContractRemark").removeClass("validate[required]");
        }

        // 验收文件
        $("#checkFileSpan").attr('style', "color:blue");
        $('#checkFile').addClass("validate[required]");
    } else if (obj.value == 'HTLX-PJGH') {
        //维保时间（月）设为非必填
        $("#Maintenance").removeClass("validate[required]");
        $("#maintenanceSpan").attr('style', "color:black");
        //去除合同开始截止日期
        $('#beginDate').removeClass("validate[required]");
        $('#endDate').removeClass("validate[required]");
        $("#beginSpan").attr('style', "color:black");
        $("#endSpan").attr('style', "color:black");
        // 纸质合同相关
        $("#paperContractSpan").attr('style', "color:black");
        $('#paperContract').removeClass("validate[required]").val('无');
        $("#paperContractRemark").removeClass("validate[required]");
        $("#paperContractRemarkSpan").attr('style', "color:black");
        $("#paperReason").show();
        // 验收文件
        $("#checkFileSpan").attr('style', "color:black");
        $('#checkFile').removeClass("validate[required]").val('无');
        // 是否续签
        $("#isRenewed").val("0");
    }  else {
        $('#beginDate').addClass("validate[required]");
        $('#endDate').addClass("validate[required]");
        $("#beginSpan").attr('style', "color:blue");
        $("#endSpan").attr('style', "color:blue");
        //维保时间（月）设为非必填
        $('#Maintenance').removeClass("validate[required]");
        $("#maintenanceSpan").attr('style', "color:black");
        // 纸质合同相关
        $("#paperContractSpan").attr('style', "color:blue");
        $('#paperContract').addClass("validate[required]");
        if($('#paperContract').val() == '无'){
            $("#paperContractRemark").addClass("validate[required]");
            $("#paperContractRemarkSpan").attr('style', "color:blue");
        }else{
            $("#paperContractRemark").removeClass("validate[required]");
        }

        // 验收文件
        $("#checkFileSpan").attr('style', "color:blue");
        $('#checkFile').addClass("validate[required]");
    }
    // 改变希望交货日期 必填验证
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
    //合同类型选择是服务合同时，产品保修条款非必填
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
        //去除合同开始截止日期
        $('#beginDate').removeClass("validate[required]");
        $('#endDate').removeClass("validate[required]");
        $("#beginSpan").attr('style', "color:black");
        $("#endSpan").attr('style', "color:black");
        // 纸质合同相关
        $("#paperContractSpan").attr('style', "color:black");
        $('#paperContract').removeClass("validate[required]");
        $("#paperContractRemark").removeClass("validate[required]");
        $("#paperContractRemarkSpan").attr('style', "color:black");
        // 验收文件
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
//加载区域
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
// 组织机构选择
$(function () {
    $("#prinvipalName").yxselect_user({
        hiddenId: 'prinvipalId',
        isGetDept: [true, "depId", "depName"]
    });
    $("#contractSigner").yxselect_user({
        hiddenId: 'contractSignerId'
    });
});

// 客户信息初始化
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

// 加载下拉列表
$(function () {
    // 客户
    $("#customerName").yxcombogrid_customer({
        hiddenId: 'customerId',
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {
                    initCustomerInfo();
                    // 带出关联的客户类型
                    $("#customerType").val(data.TypeOne);
                    $("#customerTypeName").val(data.TypeOneName);

                    // 带出关联客户的国家/省份/城市信息
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

//                    if ($("#countryName").val() == "中国") {
//                        $("#province").val(data.ProvId);// 所属省份Id
//                        $("#province").trigger("change");
//                        $("#provinceName").val(data.Prov);// 所属省份
//                        $("#city").val(data.CityId);// 城市ID
//                        $("#cityName").val(data.City);// 城市名称
//                    }
                    $("#customerId").val(data.id);
//					$("#areaPrincipal").val(data.AreaLeader);// 区域负责人
//					$("#areaPrincipalId_v").val(data.AreaLeaderId);// 区域负责人Id
//					$("#areaName").val(data.AreaName);// 合同所属区域
//					$("#areaCode").val(data.AreaId);// 合同所属区域
                    $("#address").val(data.Address);// 客户地址
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
                    //客户类型是运营商类的，带出客户类型及省份
                    // var typeName = data.TypeOne_name;
                    // if(typeName != undefined && typeName.indexOf("运营商") != -1){
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

    //公司
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
                    if ($("#originalAreaName").val() != "大数据部") {
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
    //试用项目
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

            // 带出关联客户的国家/省份/城市信息
            $("#contractContryId").val(cusInfo[0].CountryId);
            $("#contractProvinceId").val(cusInfo[0].ProvId);
            $("#contractCityId").val(cusInfo[0].CityId);
        }
    }

});

// 汇率计算
function conversion() {
    var currency = $("#currency").val();

    if (currency != '人民币') {
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
    } else if (currency == '人民币') {
        $("#contractMoney_v").attr('class', "txt").attr('readOnly', false);
        $("#contractTempMoney_v").attr('class', "txt").attr('readOnly', false);
        $("#currencyRate").hide();
    }
}

//预计金额计算
function setContractMoney() {
    var money = $("#contractMoneyCur").val();
    var rate = $("#rate").val();
    $("#contractMoney_v").val(moneyFormat2(money * rate));
    $("#contractMoney").val(money * rate);
}

// 编辑页 发货条件
$(function () {
    var shipCondition = $("#shipConditionV").val();
    if (shipCondition == '') {
        document.getElementById("shipCondition").options.add(new Option("不发货",
            ""));
        document.getElementById("shipCondition").options.add(new Option("立即发货",
            "0"));
    } else if (shipCondition == '0') {
        document.getElementById("shipCondition").options.add(new Option("立即发货",
            "0"));
        document.getElementById("shipCondition").options.add(new Option("不发货",
            ""));
    } else if (shipCondition == '1') {
        document.getElementById("shipCondition").options.add(new Option("立即发货",
            "0"));
        document.getElementById("shipCondition").options.add(new Option("不发货",
            ""));
    }
    // 是否续签
    var isRenewed = $("#isRenewedV").val();
    if (typeof(isRenewed) != 'undefined') {
        if (isRenewed == '') {
            document.getElementById("isRenewed").options.add(new Option("..请选择..",
                ""));
            document.getElementById("isRenewed").options.add(new Option("新签合同",
                "0"));
            document.getElementById("isRenewed").options.add(new Option("续签合同",
                "1"));
        } else if (isRenewed == '0') {
            document.getElementById("isRenewed").options.add(new Option("新签合同",
                "0"));
            document.getElementById("isRenewed").options.add(new Option("续签合同",
                "1"));
        } else if (isRenewed == '1') {
            document.getElementById("isRenewed").options.add(new Option("续签合同",
                "1"));
            document.getElementById("isRenewed").options.add(new Option("新签合同",
                "0"));
        }
    }
    // 是否框架合同
    var isFrame = $("#isFrameV").val();
    if (typeof(isFrame) != 'undefined') {
        if (isFrame == '0') {
            document.getElementById("isFrame").options.add(new Option("否",
                "0"));
            document.getElementById("isFrame").options.add(new Option("是",
                "1"));
        } else if (isFrame == '1') {
            document.getElementById("isFrame").options.add(new Option("是",
                "1"));
            document.getElementById("isFrame").options.add(new Option("否",
                "0"));
        }
    }

    // 纸质合同
    var paperContract = $("#paperContractV").val();
    if (typeof(paperContract) != 'undefined') {
        $("#paperReason").hide();
        if (paperContract == '有') {
            document.getElementById("paperContract").options.add(new Option("有",
                "有"));
            document.getElementById("paperContract").options.add(new Option("无",
                "无"));
        } else if (paperContract == '无') {
            $("#paperReason").show();
            document.getElementById("paperContract").options.add(new Option("无",
                "无"));
            document.getElementById("paperContract").options.add(new Option("有",
                "有"));
        }
    }

    // 配件订单不需要填写无合同原因
    if ($("#paperContract").val() == '无') {
        $("#paperReason").show();
        if ($("#contractType").val() != "HTLX-PJGH") {
            $("#paperContractRemark").addClass("validate[required]");
        }
    } else {
        $("#paperReason").hide();
        $("#paperContractRemark").val('').removeClass("validate[required]");	//隐藏前清空输入框
    }

    var checkFile = $("#checkFileViewV").val();
    if (checkFile == '有') {
        document.getElementById("checkFile").options.add(new Option("有",
            "有"));
        document.getElementById("checkFile").options.add(new Option("无",
            "无"));
        $("#checkFileView1").show();
        $("#checkFileView2").show();
    } else {
        if (document.getElementById("checkFile")) {
            document.getElementById("checkFile").options.add(new Option("无",
                "无"));
            document.getElementById("checkFile").options.add(new Option("有",
                "有"));
            $("#checkFileView1").hide();
            $("#checkFileView2").hide();
        }
    }
});

// 加载金额
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

// 省市
$(function () {
    var countryId = $("#contractContryId").val();
    var proId = $("#contractProvinceId").val();
    var cityId = $("#contractCityId").val();
    $("#country").val(countryId);// 所属国家Id
    $("#country").trigger("change");
    $("#province_Id").val(proId);// 所属省份Id
    $("#province").val(proId);// 所属省份Id
    $("#province").trigger("change");
    $("#city").val(cityId);// 城市ID
    $("#city").trigger("change");

});

$(function () {
    // 提交验证
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
                    //变更提交方法
                    var subUrl;
                    $.ajax({
                        type: 'POST',
                        url: "?model=contract_contract_contract&action=changeSubAjax",
                        data: $("form").serialize(),
                        async: false,
                        success: function (data) {
                            if (data == '0') {
                                //2016年10月17 PMS2123 隐藏该功能
//                                if (confirm('检测到当前变更无需提交审批，是否手工选择提交变更审批流程')) {
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
            $("#form1").submit();// 加上验证后再提交表单，解决需要连续点击两次按钮才能提交表单的bug
        },
        failure: false
    })
    /**
     * 验证信息
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
 * 合同付款条件控制
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
//按进度付款列表
function paymentList(flag) {
    var listObj = $("#progresspaymentList");
    var str = '<tr id="paymentTR1">' +
        '<td ><input class="rimless_text_normal" id="protem1"  name="contract[progresspaymentterm][1]"/></td>' +
        '<td><input class="rimless_text_short realNum" id="progresspaymentPro1" name="contract[progresspayment][1]" />%</td>' +
        '<td><img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_paymentlist();"/></td>' +
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
        '<td><img style="cursor:pointer;" src="images/removeline.png" title="删除行" onclick="delete_paymentlist(this);"/></td>' +
        '</tr>';
    listObj.append(str);

}
//其他付款条件
function otherpaymenttermList(flag) {
    var listObj = $("#otherpaymenttermList");
    var str = '<tr id="otherTR1">' +
        '<td><input class="rimless_text_normal" id="ohterprotem1"  name="contract[otherpaymentterm][1]"/></td>' +
        '<td><input class="rimless_text_short realNum" id="otherpaymentPro1" name="contract[otherpayment][1]"/>%</td>' +
        '<td><img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_otherpaymenttermList();"/></td>' +
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
        '<td><img style="cursor:pointer;" src="images/removeline.png" title="删除行" onclick="delete_paymentlist(this);"/></td>' +
        '</tr>';
    listObj.append(str);

}
//删除行
function delete_paymentlist(obj) {
    if (confirm('确定要删除该行？')) {
        $(obj).parent().parent().remove();
    }
}
function sub() {
    $("form").bind("submit", function () {
        if(!browserChk()){
            return false;
        }

        // 验证退货付款条件是否为100%
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
        //是否框架合同，销售类合同是框架合同允许合同金额为0
        var isFrame = $("#contractType").val() == 'HTLX-XSHT' && $("#isFrame").val() == '1' ? '1' : '0';
        if (contractMoney == "" || (contractMoney * 1 == 0 && isFrame == '0')) {
            alert('合同金额不能为空或0');
            return false;
        }

        // 零配件合同金额检验 PMS 594
        if($("#contractType").val() == 'HTLX-PJGH' && contractMoney > 10000){
            alert("零配件合同金额不能多于1万元, 如多于1万元, 请走常规流程。");
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
                alert("您变更的合同金额小于已开票金额+不开票金额+扣款金额，请麻烦申请扣款或者退票，谢谢！");
                return false;
            }
        }

//        var contractMoneyCur = $("#contractMoneyCur").val();
//        var currency = $("#currency").val();
        var allCost = 0;
        var j = 0;
        try {
            var notInvoice = true; // 不开票
            var invoiceType, invoiceTypeMoney;
            for (i = 0; i < itemLength; i++) {
                // 发票类型
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
                alert("开票类型必须勾选");
                return false;
            }
            // 如果勾选了开票类型，但是又选了不开票，则提示错误
            if (itemLength - j > 1 && notInvoice == false) {
                alert("选择不开票时，不能填写其他发票类型");
                return false;
            }
            if (notInvoice == true && allCost != contractMoney) {
                alert("合同金额必须等于开票类型金额");
                return false;
            }
        } catch (e) {

        }

        var linkmanListInfoObj = $("#linkmanListInfo");
        var linkmanListRowNum = linkmanListInfoObj.yxeditgrid('getCurShowRowNum');
        if (linkmanListRowNum == '0') {
            alert("客户联系人信息不能为空");
            return false;
        } else {
            var pass = true;
            linkmanListInfoObj.yxeditgrid("getCmpByCol", "linkmanName").each(function() {
                if ($(this).val() == "") {
                    alert("请选择客户联系人！");
                    pass = false;
                    return false;
                }
            });
            if (pass == false) {
                return false;
            }
            linkmanListInfoObj.yxeditgrid("getCmpByCol", "telephone").each(function() {
                if ($(this).val() == "") {
                    alert("客户联系人电话不得为空！");
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
            alert("产品清单不能为空");
            return false;
        } else {
            // 产品线处理
//        	var newProLineArr = [];
            var proLineAllSelected = true;
            productInfoObj.productInfoGrid("getCmpByCol", "newProLineCode").each(function () {
                if ($(this).val() == "") {
                    alert("请选择产品的产品线！");
                    proLineAllSelected = false;
                    return false;
                }
            });
            if (proLineAllSelected == false) {
                return false;
            }
            // 执行部门处理
            var exeDeptArr = [];
            var exeDeptAllSelected = true;
            productInfoObj.productInfoGrid("getCmpByCol", "exeDeptId").each(function () {
                if ($(this).val() == "") {
                    alert("请选择产品的执行区域！");
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
                //过滤掉删除的行
                if ($("#contract[product][_" + i + "_isDelTag").length == 0) {
                    proMoney = accAdd($(this).val(), proMoney);
                }
            });
            if (contractMoney != proMoney) {
                alert("请保证产品金额总和与合同金额一致！");
                return false;
            }
        }

        //借用物料
        var bowItemArr = $("#borrowConEquInfo").yxeditgrid("getCmpByCol", "onlyProductId");
        var bowFlag = 0;
        if (bowItemArr.length > 0) {
            //循环
            bowItemArr.each(function () {
                if ($(this).val() == ' ') {
                    bowFlag = 1;
                }
            });
        }
        if (bowFlag == '1') {
            alert("请选择借用转销售物料的归属产品");
            return false;
        }
        if ($("#contractType").val() != "HTLX-PJGH" && ($("#uploadfileList2").html() == "" || $("#uploadfileList2").html() == "暂无任何附件")) {
            alert("请上传加密区文件！");
            return false;
        }
        // 加入变更判断
        if ($("#isChange").val() == 1) {
            isFormChange();
        }
        // 借试用转销售关联商机处理，变更页面无需处理
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
 *新开票类型控制
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
 * 检查开票信息
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
        var notInvoice = true; // 不开票
        var invoiceType, invoiceTypeMoney;
        for (i = 0; i < itemLength; i++) {
            // 发票类型
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
            alert("开票类型必须勾选");
            pass = false;
        }
        // 如果勾选了开票类型，但是又选了不开票，则提示错误
        if (itemLength - j > 1 && notInvoice == false) {
            alert("选择不开票时，不能填写其他发票类型");
            pass = false;
        }
        if (notInvoice == true && allCost != contractMoney) {
            alert("合同金额必须等于开票类型金额");
            pass = false;
        }
    } catch (e) {

    }
    return pass;
}

/**
 * 判断新开票类型选择后金额控制
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
//    if (currency == '人民币') {
    setMoney("contractMoney", allCost);
//    } else {
//        setMoney("contractMoneyCur", allCost);
//    }
    conversion();
//    deliveryCon();
}

//发货条件提示
function changeShipCondition(obj) {
    if (obj.value == '1') {
        if (confirm('您确定选择通知发货吗？')) {
            return true;
        } else {
            $("#shipCondition").val("")
        }
    } else if (obj.value == '0') {
        if (confirm('您确定选择立即发货吗？')) {
            return true;
        } else {
            $("#shipCondition").val("")
        }
    }
}


$(function () {
    // 插入实时执行区域标识 pms2313 用
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
    // 只处理归属区域不为大数据部的
    if ($("#areaName").val() != "大数据部") {
        var defaultAreaName = $('#originalAreaName').val();
        // 设置执行区域信息
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
//自动查找归属区域
function setAreaInfo(defaultAreaName) {
    // 只处理归属区域不为大数据部的
    if ($("#originalAreaName").val() != "大数据部") {
        var customerType = $("#customerType").val();
        var province = $("#province_Id").val();
        if (province == "") {
            province = $("#contractProvinceId").val();// 用于初始化
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
                if (returnValue['count'] == 1) {//只有一条数据则直接传入
                    $('#areaName').show();// 显示输入框
                    $('#chooseAreaName').hide();// 隐藏下拉框
                    $('#chooseAreaName').removeClass("validate[required]");
                    $("#areaName").val(returnData[0].areaName);
                    $("#areaCode").val(returnData[0].id);
                    $("#areaPrincipal").val(returnData[0].areaPrincipal);// 区域负责人
                    $("#areaPrincipalId").val(returnData[0].areaPrincipalId);// 区域负责人Id
                    $("#exeDeptCode").val(returnData[0].exeDeptCode);// 执行区域编号
                    $("#exeDeptName").val(returnData[0].exeDeptName);// 执行区域
                } else {// 若有多条数据,则变下拉框让销售自己选
                    // 隐藏输入框,并初始化对应信息
                    $('#areaName').hide();
                    $("#areaName").val("");
                    $("#areaCode").val("");
                    $("#areaPrincipal").val("");// 区域负责人
                    $("#areaPrincipalId").val("");// 区域负责人Id
                    $("#exeDeptCode").val("");// 执行区域编号
                    $("#exeDeptName").val("");// 执行区域

                    // 生成对应的下拉框
                    var optStr = '<option value="" title="...请选择...">...请选择...</option>';
                    $.each(returnData, function () {
                        var selected = "";
                        var thisData = $(this)[0];
                        if (defaultAreaName && defaultAreaName == thisData.areaName) {
                            selected = "selected = 'selected'";
                            $("#areaName").val(thisData.areaName);
                            $("#areaCode").val(thisData.id);
                            $("#areaPrincipal").val(thisData.areaPrincipal);// 区域负责人
                            $("#areaPrincipalId").val(thisData.areaPrincipalId);// 区域负责人Id
                            $("#exeDeptCode").val(thisData.exeDeptCode);// 执行区域编号
                            $("#exeDeptName").val(thisData.exeDeptName);// 执行区域
                        }
                        optStr += '<option value="' + thisData.id + '" data-areaPrincipal="' + thisData.areaPrincipal + '" data-areaPrincipalId="' + thisData.areaPrincipalId + '" data-exeDeptCode="' + thisData.exeDeptCode + '" data-exeDeptName="' + thisData.exeDeptName + '" title="' + thisData.areaName + '" ' + selected + '>' + thisData.areaName + '</option>';
                    });
                    $('#chooseAreaName').html(optStr);
                    $('#chooseAreaName').show();// 显示下拉框
                    $('#chooseAreaName').addClass("validate[required]");// 设置为必选项
                    // 选择归属区域后,更新相应的数据
                    $('#chooseAreaName').change(function () {
                        // console.log($(this).find("option:selected").text());
                        $("#areaName").val($(this).find("option:selected").text());
                        $("#areaCode").val($(this).find("option:selected").val());
                        $("#areaPrincipal").val($(this).find("option:selected").attr('data-areaPrincipal'));// 区域负责人
                        $("#areaPrincipalId").val($(this).find("option:selected").attr('data-areaPrincipalId'));// 区域负责人Id
                        $("#exeDeptCode").val($(this).find("option:selected").attr('data-exeDeptCode'));// 执行区域编号
                        $("#exeDeptName").val($(this).find("option:selected").attr('data-exeDeptName'));// 执行区域
                        setProExeDept();
                    });
                }
            } else {
                $('#areaName').show();// 显示输入框
                $('#chooseAreaName').hide();// 隐藏下拉框
                $('#chooseAreaName').removeClass("validate[required]");
                $("#areaName").val("");
                $("#areaCode").val("");
                $("#areaPrincipal").val("");// 区域负责人
                $("#areaPrincipalId").val("");// 区域负责人Id
                $("#exeDeptCode").val("");// 执行区域编号
                $("#exeDeptName").val("");// 执行区域
            }

        } else {
            return false;
        }
    }
}


/**
 * 是否续签
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
        'ExaStatus': '完成',
        'prinvipalId': $("#userId").val(),//只能查看个人合同
        'customerId': $("#customerId").val() != '' ? $("#customerId").val() : "null"
    };
    // 选择合同源单
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

//续签字段
function changeRenewed(obj) {
    if (obj.value == '1') {//续签
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

//获取产品执行区域
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

// 设置所有产品执行区域
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
 * 根据合同归属区域获取对应的执行区域
 * 最后一次更新：2016-12-15 PMS 2313
 */
function getDeptCode() {
    var areaCode = $("#areaCode").val();//合同归属区域ID
    // 2016-12-15 PMS2313 通过合同归属区域ID查询该区域的执行区域
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
 * 更新产品执行区域
 * 最后一次更新：2016-12-15 PMS 2313
 */
function setProExeDept() {
    var productInfoObj = $("#productInfo");
    // 防止其他页面加载类这个js文件而没有加载到productInfoObj相关文件的情况出现报错
    if (productInfoObj.productInfoGrid != undefined) {
        var exeDeptObj = productInfoObj.productInfoGrid("getCmpByCol", "exeDeptId");
        var returnData = getDeptCode();
        if (returnData.length > 0 && returnData[0].exeDeptCode != '') {
            returnData = returnData[0];
            $('#defaultExeDeptId').val(returnData.exeDeptCode);
            $('#defaultExeDeptName').val(returnData.exeDeptName);
            var productLineName = returnData.exeDeptName;
            // console.log( returnData);
            // 更新所有产品的执行区域
            if (exeDeptObj.length > 0) {
                exeDeptObj.each(function () {
                    var rowNum = $(this).data('rowNum');
                    var productId = productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'id').val();
                    if (productId == '' || productId == undefined) {// 不修改原来的产品的执行区域
                        $(this).find("option:[value='" + returnData.exeDeptCode + "']").attr("selected", true);
                        productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'exeDeptName').val(productLineName);
                    }
                });
            }
        } else {
            $('#defaultExeDeptId').val('');
            $('#defaultExeDeptName').val('');
            // 所有新增产品的执行区域默认为空
            if (exeDeptObj.length > 0) {
                exeDeptObj.each(function () {
                    var rowNum = $(this).data('rowNum');
                    var productId = productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'id').val();
                    if (productId == '' || productId == undefined) {// 不修改原来的产品的执行区域
                        $(this).find("option:[value='" + '' + "']").attr("selected", true);
                        productInfoObj.productInfoGrid("getCmpByRowAndCol", rowNum, 'exeDeptName').val('');
                    }
                });
            }
        }
    }
}

//设置所有某个产品执行区域
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

//维保时间（月）校验
function checkMaintenance(obj) {
    var val = obj.value;
    if (val != '') {
        var re = /^[0-9]\d*$/;
        if (!re.test(val) && val != '无') {
            alert('维保时间（月）请填写数字或无');
            obj.value = '';
        }
    }
}

//直接提交审批
function toApp() {
    if(browserChk()){
        document.getElementById('form1').action = "index1.php?model=contract_contract_contract&action=edit&act=app";
        $("#form1").submit();
    }
}

//保存，不做必填验证
function toSave() {
    var form = document.getElementById('form1');
    form.action = "index1.php?model=contract_contract_contract&action=edit";

    // 零配件合同金额检验 PMS 594
    var contractMoney = $("#contractMoney").val();
    if(browserChk() && invoiceChk()){
        if($("#contractType").val() == 'HTLX-PJGH' && contractMoney > 10000){
            alert("零配件合同金额不能多于1万元, 如多于1万元, 请走常规流程。");
            return false;
        }else if (checkExeDept()) {
            form.submit();
        }
    }
}

function checkExeDept() {
    var productInfoObj = $("#productInfo");
    // 防止其他页面加载类这个js文件而没有加载到productInfoObj相关文件的情况出现报错
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
            alert("请选择产品的执行区域！");
            return false;
        } else {
            return true;
        }
    }
}

/********** 借试用转销售带出关联商机处理 **********/
//商机渲染
function showChance() {
    var toBorrowIdArr = $("#borrowConEquInfo").yxeditgrid("getCmpByCol", "toBorrowId");
    var borrowIdArr = new Array();
    if (toBorrowIdArr.length > 0) {
        toBorrowIdArr.each(function () {
            borrowIdArr.push($(this).val());
        });
        // 获取当前选中的商机并赋值
        var chanceArr = $("#chanceArea input[id^='chanceId']:checked");
        var chanceIdArr = [];
        var chanceIds = '';
        if (chanceArr.length > 0) {
            chanceArr.each(function (i, n) {
                chanceIdArr.push(this.value);
            });
            chanceIds = chanceIdArr.toString();
        }
        // 获取商机数据
        $.ajax({
            url: '?model=contract_contract_contract&action=getChanceByBorrowIds',
            data: {'borrowIds': borrowIdArr.toString()},
            type: 'POST',
            success: function (data) {
                $("#chanceArea").html(data);
                if (chanceIds != '') {
                    var chanceNum = 0;
                    // 设值
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
//提交，不做必填验证
function toSubmit() {
    //获取当前选中的商机并赋值
    var chanceArr = $("#chanceArea input[id^='chanceId']:checked");
    var chanceIdArr = [];
    var chanceIds = '';
    if (chanceArr.length > 0) {
        chanceArr.each(function (i, n) {
            chanceIdArr.push(this.value);
        });
        chanceIds = chanceIdArr.toString();
    }
    //提交
    var form = document.getElementById('form1');
    if ($("#isChangeTip").val() == "1") {//变更
        form.action = "index1.php?model=contract_contract_contract&action=change&turnChanceIds=" + chanceIds;
    } else {//编辑
        form.action = "index1.php?model=contract_contract_contract&action=edit&act=app&turnChanceIds=" + chanceIds;
    }
    form.submit();
}
//全选
function chanceCheckAll(obj) {
    if ($(obj).attr('checked') == false) {
        $("input[id^='chanceId-']").attr('checked', false);
        $("#chanceNum").html(0);
    } else {
        $("input[id^='chanceId-']").attr('checked', true);
        $("#chanceNum").html($("#chanceAllNum").html());
    }
}
//单选
function chanceCheckThis(obj) {
    var num = $("#chanceNum").html() * 1;
    if ($(obj).attr('checked') == false) {
        $("#chanceNum").html(num - 1);
    } else {
        $("#chanceNum").html(num + 1);
    }
}
//查看单据
function chanceViewForm(objId) {
    showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id=" + objId, 1, objId);
}