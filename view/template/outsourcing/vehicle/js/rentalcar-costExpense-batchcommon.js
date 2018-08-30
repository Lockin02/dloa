var billTypeArr = [];
var billTypeStr = '';
var totalFee = 0;

//获取发票类型
var getBillType = function() {
    var responseText = $.ajax({
        url : 'index1.php?model=common_otherdatas&action=getBillType',
        type : "POST",
        async : false
    }).responseText;
    var o = eval("(" + responseText + ")");
    return o;
};

// 设置发票选项
var setBillTypeStr = function(billTypeArr,defaultVal,payInfoNum){
    payInfoNum = (payInfoNum == undefined)? '' : payInfoNum;
    var optionsStr = '';
    var title =  '此费用允许替票';
    for (var i = 0, l = billTypeArr.length; i < l; i++) {
        if(defaultVal == billTypeArr[i].id){
            optionsStr +='<option value="'+ billTypeArr[i].id +'" selected="selected" title="'+title+'">'+ billTypeArr[i].name+'</option>';
        }else{
            optionsStr +='<option value="'+ billTypeArr[i].id +'" title="'+title+'">'+ billTypeArr[i].name+'</option>';
        }
    }
    return optionsStr;
};

$(function(){
    var catchParentArr = window.parent.costCatchArrGlobal;
    var rowIds = (catchParentArr.rowIds != undefined)? catchParentArr.rowIds : '';
    var payInfoId = (catchParentArr.payInfoId != undefined)? catchParentArr.payInfoId.split(',') : '';
    var includeCarNums = (catchParentArr.carNum != undefined)? catchParentArr.carNum : '';
    var registerIds = (catchParentArr.registerIds != undefined)? catchParentArr.registerIds : '';
    var allregisterId = (catchParentArr.allregisterId != undefined)? catchParentArr.allregisterId : '';
    var useCarDate = (catchParentArr.useCarDate != undefined)? catchParentArr.useCarDate : '';
    var retalContractId = (catchParentArr.retalContractId != undefined)? catchParentArr.retalContractId : '';
    var payInfo1Info = {}, payInfo2Info = {}, payInfo1CostHtml = '', payInfo2CostHtml = '';

    billTypeArr = getBillType();
    billTypeStr = setBillTypeStr(billTypeArr);

    $.each(payInfoId,function(i,id){
        var payInfo = '', payInfoNum = i+1;

        //获取数据字典信息
        var responseText = $.ajax({
            url : "?model=outsourcing_vehicle_rentalcar&action=getCostExpenseInfo",
            type: "POST",
            data: {"payInfoId": id, "catchArr": catchParentArr},
            async: false
        }).responseText;

        if(responseText != ''){
            var costTypeHtmlStr = "";
            var result = eval("("+responseText+")");
            var payInfo = (result.payInfo)? result.payInfo : '';
            var payTypeCode = (payInfo != '')? payInfo.payTypeCode : '';

            if(result.costInfo.length > 0){
                totalFee = 0;
                $.each(result.costInfo,function(index,item){
                    if(item.costMoney > 0){// 只显示费用金额大于0的
                        costTypeHtmlStr += buildCostInfoForm(item.CostTypeID,item,index,payInfoNum);
                    }
                });
            }
            if(i == 0){
                payInfo1Info.baseInfo = (result.payInfo)? result.payInfo : false;
            }else{
                payInfo2Info.baseInfo = (result.payInfo)? result.payInfo : false;
            }
            if(payTypeCode != 'HETFK'){
                $("#costTypeInner"+payInfoNum).html(costTypeHtmlStr);
                $("#imgLoading"+payInfoNum).hide();
                $("#feeTbl"+payInfoNum).show();
            }else{
                $(".payinfo"+payInfoNum+"Wrap").remove();
            }
        }

        var costMoneyCost = countMoney(payInfoNum);
        $("#payInfoId"+payInfoNum).val(id);
        $("#countMoney"+payInfoNum).val(costMoneyCost);
        $("#feeRegular"+payInfoNum).val(costMoneyCost);
        countInvoiceInfo(payInfoNum);
    });

    // 支付方式1的基本信息
    if(payInfo1Info.baseInfo){
        var baseInfo = payInfo1Info.baseInfo;
        $("#payType1").text(baseInfo.payType);
        $("#bankName1").text(baseInfo.bankName);
        $("#bankAccount1").text(baseInfo.bankAccount);
        $("#bankReceiver1").text(baseInfo.bankReceiver);
        $("#includeFeeType1").text(baseInfo.includeFeeType);
        $("#includeCurNum1").text(includeCarNums);
        var deductInfoId1 = (baseInfo.deductInfoId == undefined)? '' : baseInfo.deductInfoId;
        $("#deductInfoId1").val(deductInfoId1);
    }

    // 支付方式2的基本信息
    if(payInfo2Info.baseInfo){
        var baseInfo = payInfo2Info.baseInfo;
        $("#payType2").text(baseInfo.payType);
        $("#bankName2").text(baseInfo.bankName);
        $("#bankAccount2").text(baseInfo.bankAccount);
        $("#bankReceiver2").text(baseInfo.bankReceiver);
        $("#includeFeeType2").text(baseInfo.includeFeeType);
        $("#includeCurNum2").text(includeCarNums);
        var deductInfoId2 = (baseInfo.deductInfoId == undefined)? '' : baseInfo.deductInfoId;
        $("#deductInfoId2").val(deductInfoId2);
    }

    if(payInfoId.length > 1){
        $(".payinfo2Wrap").show();
    }

    // 公用信息填充
    $("#rowIds").val(rowIds);
    $("#carNum").val(includeCarNums);
    $("#registerIds").val(registerIds);
    $("#allregisterId").val(allregisterId);
    $("#useCarDate").val(useCarDate);
    $("#rentalContractId").val(retalContractId);
    $("#payInfoNum").val(payInfoId.length);
});

//增加发票信息
var add_lnvoice = function(id,payInfoNum){
    payInfoNum = (payInfoNum == undefined)? '' : payInfoNum;
    var payInfoNumStr = payInfoNum >= 0? '['+payInfoNum+']' : '';

    //内置从表
    var tableObj = $("#table_" + id + payInfoNum);
    //从表行对象
    var tableTrObj = $("#table_" + id + payInfoNum +" tr");
    //从表行数
    var tableTrLength = tableTrObj.length;
    //内容Id后缀
    var countStr = id + "_" + tableTrLength;


    var lastMoney = '';
    var invoiceNumber = '';

    var str = '<tr id="tr_' + countStr + payInfoNum+'">' +
        '<td width="32%"><select id="select_' + countStr + payInfoNum+'" class="invoiceTypesIdVal'+ payInfoNum+'" rowCount="'+ countStr +'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][BillTypeID]" style="width:90px"><option value="">请选择发票</option>' + billTypeStr +'</select></td>' +
        '<td width="29%"><input id="invoiceMoney_' + countStr + payInfoNum+'" costTypeId="'+ id +'" rowCount="'+ countStr +'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][Amount]" type="text" class="txtshort formatMoney invoiceMoneyVal'+ payInfoNum+'" value="'+lastMoney+'" onblur="countInvoiceInfo('+payInfoNum+')"/></td>' +
        '<td width="27%"><input id="invoiceNumber_' + countStr + payInfoNum+'" costTypeId="'+ id +'" rowCount="'+ countStr +'"  name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][invoiceNumber]" type="text" class="txtshort invoiceNumVal'+ payInfoNum+'" value="'+ invoiceNumber +'" onblur="countInvoiceInfo('+payInfoNum+')"/>' +
        '<input type="hidden" id="invIsSubsidy_' + countStr + payInfoNum+'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][isSubsidy]" value="0"/></td>' +
        '<td width="15%"><img style="cursor:pointer;" src="images/removeline.png" title="删除本行发票" onclick="delete_lnvoice(' + id + ',this,'+ payInfoNum+')"/></td>' +
        '</tr>';
    tableObj.append(str);
};

//删除发票信息
var delete_lnvoice = function(id,obj,payInfoNum){
    payInfoNum = (payInfoNum == undefined)? '' : payInfoNum;
    var payInfoNumStr = payInfoNum >= 0? '['+payInfoNum+']' : '';

    if (confirm('确定要删除该行？')) {
        var rowNo = obj.parentNode.parentNode.rowIndex;
        $(obj).parent().parent().hide();
        var rowNum = id +'_'+rowNo;
        $(obj).parent().append('<input type="hidden" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+
            id +'][expenseinv][' +
            rowNo + '][isDelTag]" id="isDelTag_'+ rowNum +payInfoNum+'" value="1"/>');

        //备注高度调整
        var remarkObj = $("#remark" + id + payInfoNum);
        remarkObj.animate({height:"-=33"},100);


        //明细计算
        countInvoiceInfo(payInfoNum);
    }
};

// 重新统计当前页面内的发票数据
var countInvoiceInfo = function(payInfoNum){
    payInfoNum = (payInfoNum == undefined)? '' : payInfoNum;
    var totalInvoiceMoney = 0, totalInvoiceNum = 0;
    // 统计发票金额
    $.each($(".invoiceMoneyVal"+payInfoNum),function(i,item){
        var selfVal = $(item).val().replaceAll(",", "");
        var rowNum = $(item).attr("rowcount");
        var isDelTag = $("#isDelTag_" + rowNum + payInfoNum).val();
        if(isDelTag != 1){
            totalInvoiceMoney += (selfVal == '' || !Number(selfVal))? 0 : Number(selfVal);
            $("#invoiceMoney_" + rowNum + payInfoNum).val(moneyFormat2(selfVal,2,2));
        }
    });

    // 统计发票数量
    $.each($(".invoiceNumVal"+payInfoNum),function(i,item){
        var selfVal = $(item).val().replaceAll(",", "");
        var rowNum = $(item).attr("rowcount");
        var isDelTag = $("#isDelTag_" + rowNum + payInfoNum).val();
        if(isDelTag != 1){
            totalInvoiceNum += (selfVal == '' || !Number(selfVal))? 0 : Number(selfVal);
        }
    });

    // 更新页面显示
    $("#invoiceMoney"+payInfoNum+"_v").val(totalInvoiceMoney);
    $("#countInvoiceNum"+payInfoNum+"_v").val(totalInvoiceNum);
    $("#invoiceMoney"+payInfoNum).val(totalInvoiceMoney);
    $("#countInvoiceNum"+payInfoNum).val(totalInvoiceNum);
    $("#invoiceMoney"+payInfoNum+"_v").blur();
};

// 统计费用金额
var countMoney = function(payInfoNum){
    payInfoNum = (payInfoNum == undefined)? '' : payInfoNum;
    var totalCostMoney = 0;
    $.each($(".costMoneyVal"+payInfoNum),function(i,item){
        var selfVal = $(item).val().replaceAll(",", "");
        totalCostMoney += (selfVal == '' || !Number(selfVal))? 0 : Number(selfVal);
    });
    return totalCostMoney;
};

// 渲染费用信息列表
var buildCostInfoForm = function(thisCostType,costTypeObj,indexKey,payInfoNum){
    payInfoNum = (payInfoNum == undefined)? '' : payInfoNum;
    var payInfoNumStr = payInfoNum >= 0? '['+payInfoNum+']' : '';

    var chkName = costTypeObj.CostTypeName;  //费用名称
    var chkParentName = costTypeObj.ParentCostType; //费用父类型名称
    var chkParentId = costTypeObj.ParentCostTypeID; //费用父类型id
    var chkShowDays = costTypeObj.showDays; //是否显示天数
    var chkInvoiceType = costTypeObj.invoiceType;//默认发票类型
    var chkInvoiceTypeName = costTypeObj.invoiceTypeName;//默认发票名称
    var chkIsSubsidy = costTypeObj.isSubsidy;//是否补贴
    var costMoney = costTypeObj.costMoney;// 费用项金额

    var thisI = thisCostType + "_0";

    var tr_class = indexKey % 2 == 0 ? 'tr_odd' : 'tr_even';

    totalFee += (costMoney != '' || costMoney != undefined)? Number(costMoney) : 0;

    var str = '<tr class="'+tr_class+'" id="tr' + thisCostType + payInfoNum+'">' +
        '<td valign="top" class="form_text_right">' +
        chkParentName +
        '<input type="hidden" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][MainType]" value="'+ chkParentName +'"/>' +
        '<input type="hidden" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][MainTypeId]" value="'+ chkParentId +'"/>' +
        '<input type="hidden" id="showDays'+ thisCostType +payInfoNum+'" value="'+ chkShowDays +'"/>' +
        '<input type="hidden" id="defaultInvoice'+ thisCostType +payInfoNum+'" value="'+ chkInvoiceType +'"/>' +
        '<input type="hidden" id="defaultInvoiceName'+ thisCostType +payInfoNum+'" value="'+ chkInvoiceTypeName +'"/>' +
        '<input type="hidden" id="isSubsidy'+ thisCostType +payInfoNum+'" value="'+ chkIsSubsidy +'"/>' +
        '</td>' +
        '<td valign="top" class="form_text_right">' +
        chkName +
        '<input type="hidden" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][costType]" id="costType'+ thisCostType +payInfoNum+'" value="'+ chkName + '"/>' +
        '<input type="hidden" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][costTypeId]" id="costTypeId'+ thisCostType +payInfoNum+'" value="' + thisCostType + '"/>' +
        '</td>' +
        '<td valign="top" class="form_text_right">';

    if(chkShowDays == 0){
        str += '<input type="text" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][costMoney]" id="costMoney'+ thisCostType +payInfoNum+'" style="width:146px" class="readOnlyTxtNormal costMoneyVal'+payInfoNum+' formatMoney" readonly value="'+costMoney+'"/>' +
            '<input type="hidden" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][days]" id="days'+ thisCostType +payInfoNum+'" value="1"/>';
    }

    var selfBillTypeStr = billTypeStr;

    str +='</td>';
    if(costTypeObj.invoiceData && costTypeObj.invoiceData.length > 0){
        str +='<td valign="top" colspan="4" class="innerTd">' +
            '<table class="form_in_table" id="table_'+ thisCostType +payInfoNum+'">';
        $.each(costTypeObj.invoiceData,function(i,item){
            selfBillTypeStr = setBillTypeStr(billTypeArr,item.BillTypeID);
            thisI = thisCostType + "_" + i;
            var ctrlBtn = (i > 0)? '<img style="cursor:pointer;" src="images/removeline.png" title="删除本行发票" onclick="delete_lnvoice(' + thisCostType + ',this,'+ payInfoNum+')"/>' :
            '<img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_lnvoice('+ thisCostType +','+ payInfoNum+')"/>';
            str += '<tr id="tr_' + thisI + payInfoNum+'">' +
                '<td width="32%">' +
                '<select id="select_' + thisI + payInfoNum+'" class="invoiceTypesIdVal'+ payInfoNum+'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][expenseinv]['+i+'][BillTypeID]" rowCount="'+ thisI +'" style="width:90px"><option value="">请选择发票</option>' + selfBillTypeStr +'</select>' +
                '</td>' +
                '<td width="29%">' +
                '<input  type="text" id="invoiceMoney_' + thisI + payInfoNum+'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][expenseinv]['+i+'][Amount]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort formatMoney invoiceMoneyVal'+ payInfoNum+'" value="'+item.Amount+'" onblur="countInvoiceInfo('+ payInfoNum+')"/>' +
                '</td>' +
                '<td width="27%">' +
                '<input type="text" id="invoiceNumber_' + thisI + payInfoNum+'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][expenseinv]['+i+'][invoiceNumber]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort invoiceNumVal'+ payInfoNum+'" value="'+item.invoiceNumber+'" onblur="countInvoiceInfo('+ payInfoNum+')"/>' +
                '<input type="hidden" id="invIsSubsidy_' + thisI + payInfoNum+'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][expenseinv]['+i+'][isSubsidy]" value="0"/>' +
                '</td>' +
                '<td width="15%">' + ctrlBtn +
                '</td>' +
                '</tr>';
        });
        str += '</table>' +
            '</td>';
    }else{
        str +='<td valign="top" colspan="4" class="innerTd">' +
            '<table class="form_in_table" id="table_'+ thisCostType +payInfoNum+'">' +
            '<tr id="tr_' + thisI + payInfoNum+'">' +
            '<td width="32%">' +
            '<select id="select_' + thisI + payInfoNum+'" class="invoiceTypesIdVal'+ payInfoNum+'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][expenseinv][0][BillTypeID]" rowCount="'+ thisI +'" style="width:90px"><option value="">请选择发票</option>' + selfBillTypeStr +'</select>' +
            '</td>' +
            '<td width="29%">' +
            '<input  type="text" id="invoiceMoney_' + thisI + payInfoNum+'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][expenseinv][0][Amount]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort formatMoney invoiceMoneyVal'+ payInfoNum+'" value="'+costMoney+'" onblur="countInvoiceInfo('+ payInfoNum+')"/>' +
            '</td>' +
            '<td width="27%">' +
            '<input type="text" id="invoiceNumber_' + thisI + payInfoNum+'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][expenseinv][0][invoiceNumber]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort invoiceNumVal'+ payInfoNum+'" value="1" onblur="countInvoiceInfo('+ payInfoNum+')"/>' +
            '<input type="hidden" id="invIsSubsidy_' + thisI + payInfoNum+'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][expenseinv][0][isSubsidy]" value="0"/>' +
            '</td>' +
            '<td width="15%">' +
            '<img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_lnvoice('+ thisCostType +','+ payInfoNum+')"/>' +
            '</td>' +
            '</tr>' +
            '</table>' +
            '</td>';
    }
    str += '</tr>';

    return str;
};

// 检查表单
var checkForm = function(payInfoNum){
    payInfoNum = (payInfoNum == undefined)? '' : payInfoNum;
    var pass = true;
    // 检查发票类型
    $.each($(".invoiceTypesIdVal"+payInfoNum),function(i,item){
        var rowNum = $(item).attr("rowcount");
        var constypId = rowNum.split("_");
        constypId = constypId[0];
        var costType = $("#costType"+constypId+payInfoNum).val();
        var invoiceMoney = $("#invoiceMoney_"+rowNum+payInfoNum).val();
        var invoiceNumber = $("#invoiceNumber_"+rowNum+payInfoNum).val();
        var isDelTag = $("#isDelTag_" + rowNum + payInfoNum).val();

        if(isDelTag != 1){
            if(pass && $(item).val() == '' && (invoiceMoney * 1 != 0 || invoiceMoney != '')){
                if(payInfoNum != '' && payInfoNum > 0){
                    alert( '【支付方式'+payInfoNum+'】'+ costType + ' 发票明细中存在无类型但有金额的发票明细项');
                }else{
                    alert( costType + ' 发票明细中存在无类型但有金额的发票明细项');
                }
                pass = false;
                return false;
            }else if(invoiceNumber * 1 == 0 || invoiceNumber == ''){
                if(payInfoNum != '' && payInfoNum > 0){
                    alert( '【支付方式'+payInfoNum+'】'+ costType + ' 发票明细中存在发票数量为0或空的项');
                }else{
                    alert( costType + ' 发票明细中存在发票数量为0或空的项');
                }
                pass = false;
                return false;
            }
        }
    });

    $("#costTypeInner"+payInfoNum+" input[id^='costTypeId']").each(function(i,n){
        //类型id
        var costTypeId = this.value*1;
        var delTag = $("#isDelTag_"+ costTypeId + payInfoNum).length;

        //金额缓存
        var costMoney = $("#costMoney" + costTypeId + payInfoNum).val();
        // var days = $("#days" + costTypeId).val();
        // costMoney = accMul(costMoney,days,2);

        //名称缓存
        costType = $("#costType" + costTypeId + payInfoNum).val();

        if(pass){
            //重新初始化金额
            detailAll = 0;
            $("#table_"+ costTypeId + payInfoNum +" select[id^='select_" + costTypeId + "_']").each(function(i,n){
                delTag = $("#feeTbl"+payInfoNum+" #isDelTag_"+ costTypeId + "_" + i + payInfoNum).length;
                if(delTag == 0){
                    //获取金额
                    detailMoney = $("#invoiceMoney_"+ costTypeId + "_" + i + payInfoNum).val();
                    detailMoney = (detailMoney && detailMoney != undefined)? detailMoney.replaceAll(",", "") : 0;
                    //计算发票金额
                    detailAll = accAdd(detailAll,detailMoney,2);
                }
            });

            if(detailAll *1 != costMoney){
                if(payInfoNum != '' && payInfoNum > 0){
                    alert( '【支付方式'+payInfoNum+'】'+ costType + ' 中费用金额' + costMoney + " 不等于发票合计金额 " + detailAll + ",请修改后再进行保存操作");
                }else{
                    alert( costType + ' 中费用金额' + costMoney + " 不等于发票合计金额 " + detailAll + ",请修改后再进行保存操作");
                }
                pass = false;
                return false;
            }
        }
    });
    return pass;
};

var submitBatchForm = function(){
    var payinfoNum = $("#payInfoNum").val();
    var chkResult = true;
    for(var i = 0;i < payinfoNum;i ++){
        var num = i+1;
        var result = checkForm(num);
        if(!result){
            chkResult = false;
            return false;
        }
    }

    if(chkResult){
        var formData = $("#form1").serialize();
        var url = "?model=outsourcing_vehicle_rentalcar&action=batchAddCZCostExpenseTmp";
        $.ajax({
            type: 'POST',
            url: url,
            data: formData,
            async: false,
            success: function (data) {
                if(data == "ok"){
                    alert("保存成功!");
                    parent.parent.reloadList();
                    parent.closeFun();
                }else{
                    alert("保存失败,请重试。");
                }
            }
        });
    }
}