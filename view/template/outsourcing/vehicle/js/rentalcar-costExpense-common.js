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
var setBillTypeStr = function(billTypeArr,defaultVal){
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

//增加发票信息
var add_lnvoice = function(id){
    //内置从表
    var tableObj = $("#table_" + id);
    //从表行对象
    var tableTrObj = $("#table_" + id + " tr");
    //从表行数
    var tableTrLength = tableTrObj.length;
    //内容Id后缀
    var countStr = id + "_" + tableTrLength;


    var lastMoney = '';
    var invoiceNumber = '';

    var str = '<tr id="tr_' + countStr + '">' +
        '<td width="32%"><select id="select_' + countStr + '" class="invoiceTypesIdVal" rowCount="'+ countStr +'" name="expenseTmp[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][BillTypeID]" style="width:90px"><option value="">请选择发票</option>' + billTypeStr +'</select></td>' +
        '<td width="29%"><input id="invoiceMoney_' + countStr + '" costTypeId="'+ id +'" rowCount="'+ countStr +'" name="expenseTmp[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][Amount]" type="text" class="txtshort formatMoney invoiceMoneyVal" value="'+lastMoney+'" onblur="countInvoiceInfo()"/></td>' +
        '<td width="27%"><input id="invoiceNumber_' + countStr + '" costTypeId="'+ id +'" rowCount="'+ countStr +'"  name="expenseTmp[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][invoiceNumber]" type="text" class="txtshort invoiceNumVal" value="'+ invoiceNumber +'" onblur="countInvoiceInfo()"/>' +
        '<input type="hidden" id="invIsSubsidy_' + countStr + '" name="expenseTmp[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][isSubsidy]" value="0"/></td>' +
        '<td width="15%"><img style="cursor:pointer;" src="images/removeline.png" title="删除本行发票" onclick="delete_lnvoice(' + id + ',this)"/></td>' +
        '</tr>';
    tableObj.append(str);
};

//删除发票信息
var delete_lnvoice = function(id,obj){
    if (confirm('确定要删除该行？')) {
        var rowNo = obj.parentNode.parentNode.rowIndex;
        $(obj).parent().parent().hide();
        $(obj).parent().append('<input type="hidden" name="expenseTmp[expensedetail]['+
            id +'][expenseinv][' +
            rowNo + '][isDelTag]" id="isDelTag_'+ id +'_'+rowNo +'" value="1"/>');

        //备注高度调整
        var remarkObj = $("#remark" + id);
        remarkObj.animate({height:"-=33"},100);


        //明细计算
        countInvoiceInfo();
    }
};

// 渲染费用信息列表
var setCustomCostType = function(thisCostType,costTypeObj,indexKey){
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

    var str = '<tr class="'+tr_class+'" id="tr' + thisCostType + '">' +
        '<td valign="top" class="form_text_right">' +
        chkParentName +
        '<input type="hidden" name="expenseTmp[expensedetail]['+ thisCostType +'][MainType]" value="'+ chkParentName +'"/>' +
        '<input type="hidden" name="expenseTmp[expensedetail]['+ thisCostType +'][MainTypeId]" value="'+ chkParentId +'"/>' +
        '<input type="hidden" id="showDays'+ thisCostType +'" value="'+ chkShowDays +'"/>' +
        '<input type="hidden" id="defaultInvoice'+ thisCostType +'" value="'+ chkInvoiceType +'"/>' +
        '<input type="hidden" id="defaultInvoiceName'+ thisCostType +'" value="'+ chkInvoiceTypeName +'"/>' +
        '<input type="hidden" id="isSubsidy'+ thisCostType +'" value="'+ chkIsSubsidy +'"/>' +
        '</td>' +
        '<td valign="top" class="form_text_right">' +
        chkName +
        '<input type="hidden" name="expenseTmp[expensedetail]['+ thisCostType +'][costType]" id="costType'+ thisCostType +'" value="'+ chkName + '"/>' +
        '<input type="hidden" name="expenseTmp[expensedetail]['+ thisCostType +'][costTypeId]" id="costTypeId'+ thisCostType +'" value="' + thisCostType + '"/>' +
        '</td>' +
        '<td valign="top" class="form_text_right">';

    if(chkShowDays == 0){
        str += '<input type="text" name="expenseTmp[expensedetail]['+ thisCostType +'][costMoney]" id="costMoney'+ thisCostType +'" style="width:146px" class="readOnlyTxtNormal costMoneyVal formatMoney" readonly value="'+costMoney+'"/>' +
            '<input type="hidden" name="expenseTmp[expensedetail]['+ thisCostType +'][days]" id="days'+ thisCostType +'" value="1"/>';
    }

    var selfBillTypeStr = billTypeStr;

    str +='</td>';
    if(costTypeObj.invoiceData && costTypeObj.invoiceData.length > 0){
        str +='<td valign="top" colspan="4" class="innerTd">' +
            '<table class="form_in_table" id="table_'+ thisCostType +'">';
        $.each(costTypeObj.invoiceData,function(i,item){
            selfBillTypeStr = setBillTypeStr(billTypeArr,item.BillTypeID);
            thisI = thisCostType + "_" + i;
            var ctrlBtn = (i > 0)? '<img style="cursor:pointer;" src="images/removeline.png" title="删除本行发票" onclick="delete_lnvoice(' + thisCostType + ',this)"/>' :
            '<img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_lnvoice('+ thisCostType +')"/>';
            str += '<tr id="tr_' + thisI + '">' +
                '<td width="32%">' +
                '<select id="select_' + thisI + '" class="invoiceTypesIdVal" name="expenseTmp[expensedetail]['+ thisCostType +'][expenseinv]['+i+'][BillTypeID]" rowCount="'+ thisI +'" style="width:90px"><option value="">请选择发票</option>' + selfBillTypeStr +'</select>' +
                '</td>' +
                '<td width="29%">' +
                '<input  type="text" id="invoiceMoney_' + thisI + '" name="expenseTmp[expensedetail]['+ thisCostType +'][expenseinv]['+i+'][Amount]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort formatMoney invoiceMoneyVal" value="'+item.Amount+'" onblur="countInvoiceInfo()"/>' +
                '</td>' +
                '<td width="27%">' +
                '<input type="text" id="invoiceNumber_' + thisI + '" name="expenseTmp[expensedetail]['+ thisCostType +'][expenseinv]['+i+'][invoiceNumber]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort invoiceNumVal" value="'+item.invoiceNumber+'" onblur="countInvoiceInfo()"/>' +
                '<input type="hidden" id="invIsSubsidy_' + thisI + '" name="expenseTmp[expensedetail]['+ thisCostType +'][expenseinv]['+i+'][isSubsidy]" value="0"/>' +
                '</td>' +
                '<td width="15%">' + ctrlBtn +
                '</td>' +
                '</tr>';
        });
        str += '</table>' +
            '</td>';
    }else{
        str +='<td valign="top" colspan="4" class="innerTd">' +
            '<table class="form_in_table" id="table_'+ thisCostType +'">' +
            '<tr id="tr_' + thisI + '">' +
            '<td width="32%">' +
            '<select id="select_' + thisI + '" class="invoiceTypesIdVal" name="expenseTmp[expensedetail]['+ thisCostType +'][expenseinv][0][BillTypeID]" rowCount="'+ thisI +'" style="width:90px"><option value="">请选择发票</option>' + selfBillTypeStr +'</select>' +
            '</td>' +
            '<td width="29%">' +
            '<input  type="text" id="invoiceMoney_' + thisI + '" name="expenseTmp[expensedetail]['+ thisCostType +'][expenseinv][0][Amount]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort formatMoney invoiceMoneyVal" value="'+costMoney+'" onblur="countInvoiceInfo()"/>' +
            '</td>' +
            '<td width="27%">' +
            '<input type="text" id="invoiceNumber_' + thisI + '" name="expenseTmp[expensedetail]['+ thisCostType +'][expenseinv][0][invoiceNumber]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort invoiceNumVal" value="1" onblur="countInvoiceInfo()"/>' +
            '<input type="hidden" id="invIsSubsidy_' + thisI + '" name="expenseTmp[expensedetail]['+ thisCostType +'][expenseinv][0][isSubsidy]" value="0"/>' +
            '</td>' +
            '<td width="15%">' +
            '<img style="cursor:pointer;" src="images/add_item.png" title="添加行" onclick="add_lnvoice('+ thisCostType +')"/>' +
            '</td>' +
            '</tr>' +
            '</table>' +
            '</td>';
    }
    str += '</tr>';

    return str;
};

// 统计费用金额
var countMoney = function(){
    var totalCostMoney = 0;
    $.each($(".costMoneyVal"),function(i,item){
        var selfVal = $(item).val().replaceAll(",", "");
        totalCostMoney += (selfVal == '' || !Number(selfVal))? 0 : Number(selfVal);
    });
    return totalCostMoney;
};

// 重新统计当前页面内的发票数据
var countInvoiceInfo = function(){
    var totalInvoiceMoney = 0, totalInvoiceNum = 0;
    // 统计发票金额
    $.each($(".invoiceMoneyVal"),function(i,item){
        var selfVal = $(item).val().replaceAll(",", "");
        var rowNum = $(item).attr("rowcount");
        var isDelTag = $("#isDelTag_" + rowNum).val();
        if(isDelTag != 1){
            totalInvoiceMoney += (selfVal == '' || !Number(selfVal))? 0 : Number(selfVal);
            $("#invoiceMoney_" + rowNum).val(moneyFormat2(selfVal,2,2));
        }
    });

    // 统计发票数量
    $.each($(".invoiceNumVal"),function(i,item){
        var selfVal = $(item).val().replaceAll(",", "");
        var rowNum = $(item).attr("rowcount");
        var isDelTag = $("#isDelTag_" + rowNum).val();
        if(isDelTag != 1){
            totalInvoiceNum += (selfVal == '' || !Number(selfVal))? 0 : Number(selfVal);
        }
    });

    // 更新页面显示
    $("#invoiceMoney_v").val(totalInvoiceMoney);
    $("#countInvoiceNum_v").val(totalInvoiceNum);
    $("#invoiceMoney").val(totalInvoiceMoney);
    $("#countInvoiceNum").val(totalInvoiceNum);
    $("#invoiceMoney_v").blur();
};

// 检查表单
var checkForm = function(){
    var pass = true;
    // 检查发票类型
    $.each($(".invoiceTypesIdVal"),function(i,item){
        var rowNum = $(item).attr("rowcount");
        var constypId = rowNum.split("_");
        constypId = constypId[0];
        var costType = $("#costType"+constypId).val();
        var invoiceMoney = $("#invoiceMoney_"+rowNum).val();
        var invoiceNumber = $("#invoiceNumber_"+rowNum).val();
        var isDelTag = $("#isDelTag_" + rowNum).val();

        if(isDelTag != 1){
            if(pass && $(item).val() == '' && (invoiceMoney * 1 != 0 || invoiceMoney != '')){
                alert( costType + ' 发票明细中存在无类型但有金额的发票明细项');
                pass = false;
                return false;
            }else if(invoiceNumber * 1 == 0 || invoiceNumber == ''){
                alert( costType + ' 发票明细中存在发票数量为0或空的项');
                pass = false;
                return false;
            }
        }
    });

    $("#costTypeInner input[id^='costTypeId']").each(function(i,n){
        //类型id
        var costTypeId = this.value*1;
        var delTag = $("#isDelTag_"+ costTypeId).length;

        //金额缓存
        var costMoney = $("#costMoney" + costTypeId).val();
        // var days = $("#days" + costTypeId).val();
        // costMoney = accMul(costMoney,days,2);

        //名称缓存
        costType = $("#costType" + costTypeId).val();

        if(pass){
            //重新初始化金额
            detailAll = 0;
            $("#table_"+ costTypeId +" select[id^='select_" + costTypeId + "_']").each(function(i,n){
                delTag = $("#feeTbl #isDelTag_"+ costTypeId + "_" + i).length;
                if(delTag == 0){
                    //获取金额
                    detailMoney = $("#invoiceMoney_"+ costTypeId + "_" + i).val();
                    detailMoney = (detailMoney && detailMoney != undefined)? detailMoney.replaceAll(",", "") : 0;
                    //计算发票金额
                    detailAll = accAdd(detailAll,detailMoney,2);
                }
            });

            if(detailAll *1 != costMoney){
                alert( costType + ' 中费用金额' + costMoney + " 不等于发票合计金额 " + detailAll + ",请修改后再进行保存操作");
                pass = false;
                return false;
            }
        }
    });
    return pass;
};

// 提交表单
var submitForm = function (act) {
    var chkResult = checkForm();
    var url = "?model=outsourcing_vehicle_rentalcar&action=";
    url += (act == "edit")? "editCostExpenseTmp" : "addCostExpenseTmp";
    if(chkResult){
        var formData = $("#form1").serialize();
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
};