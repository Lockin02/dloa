//2012-12-27备份
/**
 * 判断字符串长度
 * @return {Boolean}
 */
function strlength(obj) {
    if (obj.length > 300) {
        alert("您输入的内容过长!")
    }
}
//开始时间与结束时间差验证
function timeCheck($t) {
    var s = plusDateInfo('beginDate', 'endDate');
    if (s < 0) {
        alert("开始时间不能比结束时间晚！");
        $t.value = "";
        return false;
    }
}
/**
 * 开票类型控制
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
}


/**
 * 合同付款条件控制
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

//合同盖章

/**
 * 是否盖章必填判断
 */
function changeRadio() {
    //附件盖章验证
    if ($("#uploadfileList2").html() == "" || $("#uploadfileList2").html() == "暂无任何附件") {
        alert('申请盖章前需要上传合同文本!');
        $("#isNeedStampNo").attr("checked", true);
        //盖章类型渲染
        $("#radioSpan").attr('style', "color:black");
        var stampTypeObj = $("#stampType");
        stampTypeObj.yxcombogrid_stampconfig('remove');
        stampTypeObj.val('');
        return false;
    }
    //显示必填项
    if ($("#isNeedStampYes").attr("checked")) {
        $("#radioSpan").attr('style', "color:blue");
        //防止重复数理化下拉表格
        if ($("#stampType").yxcombogrid_stampconfig('getIsRender') == true) return false;

        //盖章类型渲染
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

        //盖章类型渲染
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

// 加载数据字典项
$(function () {
    // 合同类型
    contractTypeArr = getData('HTLX');
    addDataToSelect(contractTypeArr, 'contractType');
    // 附件类型
    fileTypeArr = getData('FJLX');
    addDataToSelect(fileTypeArr, 'fileType');
    // 客户类型
    customerTypeArr = getData('KHLX');
    addDataToSelect(customerTypeArr, 'customerType');
    // 签约主体
    signSubjectTypeArr = getData('QYZT');
    addDataToSelect(signSubjectTypeArr, 'signSubject');
    // 所属板块
    moduleArr = getData('HTBK');
    addDataToSelect(moduleArr, 'module');

});

//借试用转销售物料
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
            //循环拆分数组
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
                //插入数据
                $("#customerIdtest").val(returnValue[i].customerId);
                drawCustomerInfo();
                g.addRow(i, outJson);
                //带出归属产品信息，默认选中。onlyProductId为空，选择产品信息后再渲染。
                if (typeof (returnValue[i].conProductId) != 'undefined') {
                    var obj = document.getElementById("borrowConEquInfo_cmp_onlyProductId" + i);
                    obj.add(new Option("" + returnValue[i].conProductName + "", "" + '' + "", true, true));
                }

                // 带出已执行数量,并添加此行数量框的变动监听
                if (typeof (returnValue[i].executedNum) != 'undefined') {
                    var executedNum = returnValue[i].executedNum;
                    var exeNum = returnValue[i].executedNum - returnValue[i].backNum;
                    var obj = $("#borrowConEquInfo_cmp_number" + i);
                    obj.after("<input type='hidden' id='borrowConEquInfo_cmp_executedNum" + i + "' value='" + returnValue[i].executedNum + "'/>");
                    obj.change(function () {
                        var inputVal = $(this).val();
                        if (isNaN(inputVal) || parseInt(inputVal) <= 0) {
                            alert("请输入大于0的整数。");
                            $(this).val(exeNum);
                        } else if (parseInt(inputVal) > parseInt(exeNum)) {
                            alert("转销售数量请控制在可行性数量范围内。");
                            $(this).val(exeNum);
                        }
                    });
                }
            }
        }
    }

});
//根据ID 获取渲染页面客户信息
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

        // 客户地址
        var linkmanCmp = $("#linkmanListInfo").yxeditgrid(
            "getCmpByCol", "linkmanName");
        linkmanCmp.yxcombogrid_linkman("remove");
        $("#linkmanListInfo").yxeditgrid('remove');
        linkmanList(cusInfo[0].id);
    }

    // 加载客户类型(解决客户类型没加载出来的问题 ID2232 2016-11-18)
    if (cusInfo != false && cusInfo[0].TypeOne != '') {
        customerTypeArr = getData('KHLX');
        addDataToSelect(customerTypeArr, 'customerType', cusInfo[0].TypeOne);
    } else {
        customerTypeArr = getData('KHLX');
        addDataToSelect(customerTypeArr, 'customerType');
    }

    if (cusInfo != false) {
        $("#province_Id").val(cusInfo[0].ProvId);// 所属省份Id
        $("#province").val(cusInfo[0].ProvId);// 所属省份Id
        $("#province").trigger("change");
        $("#provinceName").val(cusInfo[0].Prov);// 所属省份
        $("#city_Id").val(cusInfo[0].CityId);// 城市ID
        $("#city").val(cusInfo[0].CityId);// 城市ID
        $("#cityName").val(cusInfo[0].City);// 城市名称
        $("#customerId").val(cusInfo[0].id);
        //$("#areaPrincipal").val(cusInfo[0].AreaLeader);// 区域负责人
        //$("#areaPrincipalId").val(cusInfo[0].AreaLeaderId);// 区域负责人Id
        // $("#areaPrincipal").val(cusInfo[0].AreaLeaderNow);// 最新区域负责人 (ID2232 2016-11-18)
        // $("#areaPrincipalId").val(cusInfo[0].AreaLeaderIdNow);// 最新区域负责人Id  (ID2232 2016-11-18)
        // $("#areaName").val(cusInfo[0].AreaName);// 合同所属区域
        // $("#areaCode").val(cusInfo[0].AreaId);// 合同所属区域

        $("#address").val(cusInfo[0].Address);// 客户地址
    }

    // 最新合同所属区域 (ID2232 2016-11-18)
    if (cusInfo != false && cusInfo[0].AreaIdNow != "") {
        $("#areaCode").val(cusInfo[0].AreaIdNow);
    }

    setAreaInfo();
    setProExeDept();
}
// 直接提交审批
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
//保存，不做必填验证
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
// 判断是否签约
function isSign(obj) {
    if (obj.value == '100%') {
        document.getElementById("signDateNone").style.display = "";
        $('#signDate').addClass("validate[required]");
        $("#moneyName").html("签约金额(￥)：");
        $("#signDate").val("");
    } else {
        $("#signDate").val("");
        $("#moneyName").html("预计金额：");
        document.getElementById("signDateNone").style.display = "none";
        $('#signDate').removeClass("validate[required]");
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
        $("#Maintenance").addClass("validate[required]");
        $("#maintenanceSpan").attr('style', "color:blue");
        // 纸质合同相关
        if($('#paperContract').val() == '无'){
            $("#paperContractSpan").attr('style', "color:blue");
            $('#paperContract').addClass("validate[required]");
            $("#paperContractRemark").addClass("validate[required]");
            $("#paperContractRemarkSpan").attr('style', "color:blue");
            $("#paperReason").show();
        }else{
            $("#paperReason").hide();
            $("#paperContractRemark").val('').removeClass("validate[required]");	//隐藏前清空输入框
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
    } else {
        $('#beginDate').addClass("validate[required]");
        $('#endDate').addClass("validate[required]");
        $("#beginSpan").attr('style', "color:blue");
        $("#endSpan").attr('style', "color:blue");
        //维保时间（月）设为非必填
        $("#Maintenance").removeClass("validate[required]");
        $("#maintenanceSpan").attr('style', "color:black");
        // 纸质合同相关
        $("#paperContractSpan").attr('style', "color:blue");
        $('#paperContract').addClass("validate[required]");
        $("#paperContractRemarkSpan").attr('style', "color:blue");

        // 纸质合同相关
        if($('#paperContract').val() == '无'){
            $("#paperContractRemark").addClass("validate[required]");
            $("#paperReason").show();
        }else{
            $("#paperReason").hide();
            $("#paperContractRemark").val('').removeClass("validate[required]");	//隐藏前清空输入框
        }

        // 验收文件
        $("#checkFileSpan").attr('style', "color:blue");
        $('#checkFile').addClass("validate[required]");
    }
    // 改变希望交货日期 必填验证
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
    //合同类型选择是服务合同时，产品保修条款非必填
    if (obj.value == 'HTLX-FWHT') {
        $('#warrantyClause').removeClass("validate[required]");
        $("#warrantyClauseSpan").attr('style', "color:black");
    } else {
        $('#warrantyClause').addClass("validate[required]");
        $("#warrantyClauseSpan").attr('style', "color:blue");
    }
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
// 客户信息初始化
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

// 加载下拉列表
$(function () {
    // 客户
    $("#customerName").yxcombogrid_customer({
        hiddenId: 'customerId',
        height: 250,
        gridOptions: {
            isTitle: true,
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
                    $("#customerId").val(data.id);
                    $("#address").val(data.Address);
                    // 客户地址
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

    //公司
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
    //试用项目
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
    // 金额币别
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
        $("#contractMoney_v").attr('class', "txt");
        $("#contractMoney_v").attr('readOnly', false);
        $("#contractTempMoney_v").attr('class', "txt");
        $("#contractTempMoney_v").attr('readOnly', false);
        $("#currencyRate").hide();
    }
}

// 预计金额计算
function setContractMoney() {
    var money = $("#contractMoneyCur").val();
    var rate = $("#rate").val();
    $("#contractMoney_v").val(moneyFormat2(money * rate));
    $("#contractMoney").val(money * rate);
}

$(function () {
    // 提交验证
    $("#form1").validationEngine({
        inlineValidation: false,
        success: function () {
            var country = $("#countryName").val();
            var province = $("#province").val();
            var city = $("#city").val();
            // if (country == '中国') {
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
            $("#form1").submit();//加上验证后再提交表单，解决需要连续点击两次按钮才能提交表单的bug

        },
        failure: false
    });

    //alert($("#contractName").validationEngine.buildPrompt)
    //$("#contractName").buildPrompt(null,"aaaaa");
    /**
     * 验证信息
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
                alert("请正确填写合同  开始、结束日期。");
                return false;
            }
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
        //			alert("您输入的付款条件占比之和为【" + allNum + "%】 请将占比之和调整为 100% ");
        //			return false;
        //		}
        //
        //	   //计算付款条件百分比总和
        //	    var cmps = $("#paymentListInfo").yxeditgrid("getCmpByCol", "paymentPer");
        //		var paymentNum = 0;
        //		cmps.each(function(i,n) {
        //			//过滤掉删除的行
        //			if($("#contract[payment][_" + i +"_isDelTag").length == 0){
        //				paymentNum = accAdd($(this).val() , paymentNum);
        //			}
        //		});
        //		if(paymentNum != '100'){
        //		   alert("付款条件占比之和为【" + paymentNum + "%】 请将占比之和调整为 100% ");
        //            return false;
        //		}
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
        //        var contractMoneyCur = $("#contractMoneyCur").val();
        //        var currency = $("#currency").val();

        // 零配件合同金额检验 PMS 594
        if($("#contractType").val() == 'HTLX-PJGH' && contractMoney > 10000){
            alert("零配件合同金额不能多于1万元, 如多于1万元, 请走常规流程。");
            return false;
        }

        var allCost = 0;
        var j = 0;
        try {
            var notInvoice = true; // 不开票
            var invoiceType, invoiceTypeMoney;
            for (var i = 0; i < itemLength; i++) {
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
            //            var currency = $("#currency").val();
            //            if (currency != '人民币') {
            //                var rate = $("#rate").val();
            //                proMoney = proMoney * rate;
            //            }
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
            alert("请上传加密区文件！")
            return false;
        }
        // 借试用转销售关联商机处理
        if (!showChance()) {
            tb_show(null, '#TB_inline?height=600&width=800&inlineId=showChance', false);
            return false;
        } else {
            return true;
        }
    });
}
$(function () {
    //控制合同号是否手工输入
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
                                alert("合同编号重复请修正!")
                                $("#contractCode").focus();
                            }
                        }
                    });
                } else {
                    alert("请填写合同号!")
                    $("#contractCode").focus();
                }
            }
        );
    } else {
        $("#contractCodeHandle").hide();
        $("#contractCodeHandleV").hide();
    }
});

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

//自动查找归属区域
function setAreaInfo() {
    // 只处理归属区域不为大数据部的
    if ($("#areaName").val() != "大数据部") {
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
                        var thisData = $(this)[0];
                        optStr += '<option value="' + thisData.id + '" data-areaPrincipal="' + thisData.areaPrincipal + '" data-areaPrincipalId="' + thisData.areaPrincipalId + '" data-exeDeptCode="' + thisData.exeDeptCode + '" data-exeDeptName="' + thisData.exeDeptName + '" title="' + thisData.areaName + '">' + thisData.areaName + '</option>';
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
    contractSelect();
    //隐藏续签合同号，续签合同名称
    $("#parentCode").parent("td").hide().prev("td").hide();
    $("#parentName").parent("td").hide().prev("td").hide();
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

// 获取产品执行区域
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
 * 检查开票信息
 * @returns {boolean}
 */
function invoiceChk(){
    var dataCode = $("#dataCode").val();
    var itemArr = dataCode.split(',');
    var itemLength = itemArr.length;
    var contractMoney = $("#contractMoney").val();
    var allCost = 0,j = 0,pass = true;
    try {
        var notInvoice = true; // 不开票
        var invoiceType, invoiceTypeMoney;
        for (var i = 0; i < itemLength; i++) {
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
            pass =  false;
        }
        // 如果勾选了开票类型，但是又选了不开票，则提示错误
        if (itemLength - j > 1 && notInvoice == false && pass) {
            alert("选择不开票时，不能填写其他发票类型");
            pass =  false;
        }
        if (notInvoice == true && allCost != contractMoney && pass) {
            alert("合同金额必须等于开票类型金额");
            pass =  false;
        }
    } catch (e) {

    }
    return pass;
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
            // console.log(returnData);
            // 更新所有产品的执行区域
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

//设置某个产品执行区域
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
/********** 借试用转销售带出关联商机处理 **********/
// 商机渲染
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
    form.action = "index1.php?model=contract_contract_contract&action=add&act=app&turnChanceIds=" + chanceIds;
    form.submit();
}
// 全选
function chanceCheckAll(obj) {
    if ($(obj).attr('checked') == false) {
        $("input[id^='chanceId-']").attr('checked', false);
        $("#chanceNum").html(0);
    } else {
        $("input[id^='chanceId-']").attr('checked', true);
        $("#chanceNum").html($("#chanceAllNum").html());
    }
}
// 单选
function chanceCheckThis(obj) {
    var num = $("#chanceNum").html() * 1;
    if ($(obj).attr('checked') == false) {
        $("#chanceNum").html(num - 1);
    } else {
        $("#chanceNum").html(num + 1);
    }
}
// 查看单据
function chanceViewForm(objId) {
    showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id=" + objId, 1, objId);
}