var billTypeArr = [];
var billTypeStr = '';
var totalFee = 0;

//��ȡ��Ʊ����
var getBillType = function() {
    var responseText = $.ajax({
        url : 'index1.php?model=common_otherdatas&action=getBillType',
        type : "POST",
        async : false
    }).responseText;
    var o = eval("(" + responseText + ")");
    return o;
};

// ���÷�Ʊѡ��
var setBillTypeStr = function(billTypeArr,defaultVal,payInfoNum){
    payInfoNum = (payInfoNum == undefined)? '' : payInfoNum;
    var optionsStr = '';
    var title =  '�˷���������Ʊ';
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

        //��ȡ�����ֵ���Ϣ
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
                    if(item.costMoney > 0){// ֻ��ʾ���ý�����0��
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

    // ֧����ʽ1�Ļ�����Ϣ
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

    // ֧����ʽ2�Ļ�����Ϣ
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

    // ������Ϣ���
    $("#rowIds").val(rowIds);
    $("#carNum").val(includeCarNums);
    $("#registerIds").val(registerIds);
    $("#allregisterId").val(allregisterId);
    $("#useCarDate").val(useCarDate);
    $("#rentalContractId").val(retalContractId);
    $("#payInfoNum").val(payInfoId.length);
});

//���ӷ�Ʊ��Ϣ
var add_lnvoice = function(id,payInfoNum){
    payInfoNum = (payInfoNum == undefined)? '' : payInfoNum;
    var payInfoNumStr = payInfoNum >= 0? '['+payInfoNum+']' : '';

    //���ôӱ�
    var tableObj = $("#table_" + id + payInfoNum);
    //�ӱ��ж���
    var tableTrObj = $("#table_" + id + payInfoNum +" tr");
    //�ӱ�����
    var tableTrLength = tableTrObj.length;
    //����Id��׺
    var countStr = id + "_" + tableTrLength;


    var lastMoney = '';
    var invoiceNumber = '';

    var str = '<tr id="tr_' + countStr + payInfoNum+'">' +
        '<td width="32%"><select id="select_' + countStr + payInfoNum+'" class="invoiceTypesIdVal'+ payInfoNum+'" rowCount="'+ countStr +'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][BillTypeID]" style="width:90px"><option value="">��ѡ��Ʊ</option>' + billTypeStr +'</select></td>' +
        '<td width="29%"><input id="invoiceMoney_' + countStr + payInfoNum+'" costTypeId="'+ id +'" rowCount="'+ countStr +'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][Amount]" type="text" class="txtshort formatMoney invoiceMoneyVal'+ payInfoNum+'" value="'+lastMoney+'" onblur="countInvoiceInfo('+payInfoNum+')"/></td>' +
        '<td width="27%"><input id="invoiceNumber_' + countStr + payInfoNum+'" costTypeId="'+ id +'" rowCount="'+ countStr +'"  name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][invoiceNumber]" type="text" class="txtshort invoiceNumVal'+ payInfoNum+'" value="'+ invoiceNumber +'" onblur="countInvoiceInfo('+payInfoNum+')"/>' +
        '<input type="hidden" id="invIsSubsidy_' + countStr + payInfoNum+'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][isSubsidy]" value="0"/></td>' +
        '<td width="15%"><img style="cursor:pointer;" src="images/removeline.png" title="ɾ�����з�Ʊ" onclick="delete_lnvoice(' + id + ',this,'+ payInfoNum+')"/></td>' +
        '</tr>';
    tableObj.append(str);
};

//ɾ����Ʊ��Ϣ
var delete_lnvoice = function(id,obj,payInfoNum){
    payInfoNum = (payInfoNum == undefined)? '' : payInfoNum;
    var payInfoNumStr = payInfoNum >= 0? '['+payInfoNum+']' : '';

    if (confirm('ȷ��Ҫɾ�����У�')) {
        var rowNo = obj.parentNode.parentNode.rowIndex;
        $(obj).parent().parent().hide();
        var rowNum = id +'_'+rowNo;
        $(obj).parent().append('<input type="hidden" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+
            id +'][expenseinv][' +
            rowNo + '][isDelTag]" id="isDelTag_'+ rowNum +payInfoNum+'" value="1"/>');

        //��ע�߶ȵ���
        var remarkObj = $("#remark" + id + payInfoNum);
        remarkObj.animate({height:"-=33"},100);


        //��ϸ����
        countInvoiceInfo(payInfoNum);
    }
};

// ����ͳ�Ƶ�ǰҳ���ڵķ�Ʊ����
var countInvoiceInfo = function(payInfoNum){
    payInfoNum = (payInfoNum == undefined)? '' : payInfoNum;
    var totalInvoiceMoney = 0, totalInvoiceNum = 0;
    // ͳ�Ʒ�Ʊ���
    $.each($(".invoiceMoneyVal"+payInfoNum),function(i,item){
        var selfVal = $(item).val().replaceAll(",", "");
        var rowNum = $(item).attr("rowcount");
        var isDelTag = $("#isDelTag_" + rowNum + payInfoNum).val();
        if(isDelTag != 1){
            totalInvoiceMoney += (selfVal == '' || !Number(selfVal))? 0 : Number(selfVal);
            $("#invoiceMoney_" + rowNum + payInfoNum).val(moneyFormat2(selfVal,2,2));
        }
    });

    // ͳ�Ʒ�Ʊ����
    $.each($(".invoiceNumVal"+payInfoNum),function(i,item){
        var selfVal = $(item).val().replaceAll(",", "");
        var rowNum = $(item).attr("rowcount");
        var isDelTag = $("#isDelTag_" + rowNum + payInfoNum).val();
        if(isDelTag != 1){
            totalInvoiceNum += (selfVal == '' || !Number(selfVal))? 0 : Number(selfVal);
        }
    });

    // ����ҳ����ʾ
    $("#invoiceMoney"+payInfoNum+"_v").val(totalInvoiceMoney);
    $("#countInvoiceNum"+payInfoNum+"_v").val(totalInvoiceNum);
    $("#invoiceMoney"+payInfoNum).val(totalInvoiceMoney);
    $("#countInvoiceNum"+payInfoNum).val(totalInvoiceNum);
    $("#invoiceMoney"+payInfoNum+"_v").blur();
};

// ͳ�Ʒ��ý��
var countMoney = function(payInfoNum){
    payInfoNum = (payInfoNum == undefined)? '' : payInfoNum;
    var totalCostMoney = 0;
    $.each($(".costMoneyVal"+payInfoNum),function(i,item){
        var selfVal = $(item).val().replaceAll(",", "");
        totalCostMoney += (selfVal == '' || !Number(selfVal))? 0 : Number(selfVal);
    });
    return totalCostMoney;
};

// ��Ⱦ������Ϣ�б�
var buildCostInfoForm = function(thisCostType,costTypeObj,indexKey,payInfoNum){
    payInfoNum = (payInfoNum == undefined)? '' : payInfoNum;
    var payInfoNumStr = payInfoNum >= 0? '['+payInfoNum+']' : '';

    var chkName = costTypeObj.CostTypeName;  //��������
    var chkParentName = costTypeObj.ParentCostType; //���ø���������
    var chkParentId = costTypeObj.ParentCostTypeID; //���ø�����id
    var chkShowDays = costTypeObj.showDays; //�Ƿ���ʾ����
    var chkInvoiceType = costTypeObj.invoiceType;//Ĭ�Ϸ�Ʊ����
    var chkInvoiceTypeName = costTypeObj.invoiceTypeName;//Ĭ�Ϸ�Ʊ����
    var chkIsSubsidy = costTypeObj.isSubsidy;//�Ƿ���
    var costMoney = costTypeObj.costMoney;// ��������

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
            var ctrlBtn = (i > 0)? '<img style="cursor:pointer;" src="images/removeline.png" title="ɾ�����з�Ʊ" onclick="delete_lnvoice(' + thisCostType + ',this,'+ payInfoNum+')"/>' :
            '<img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_lnvoice('+ thisCostType +','+ payInfoNum+')"/>';
            str += '<tr id="tr_' + thisI + payInfoNum+'">' +
                '<td width="32%">' +
                '<select id="select_' + thisI + payInfoNum+'" class="invoiceTypesIdVal'+ payInfoNum+'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][expenseinv]['+i+'][BillTypeID]" rowCount="'+ thisI +'" style="width:90px"><option value="">��ѡ��Ʊ</option>' + selfBillTypeStr +'</select>' +
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
            '<select id="select_' + thisI + payInfoNum+'" class="invoiceTypesIdVal'+ payInfoNum+'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][expenseinv][0][BillTypeID]" rowCount="'+ thisI +'" style="width:90px"><option value="">��ѡ��Ʊ</option>' + selfBillTypeStr +'</select>' +
            '</td>' +
            '<td width="29%">' +
            '<input  type="text" id="invoiceMoney_' + thisI + payInfoNum+'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][expenseinv][0][Amount]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort formatMoney invoiceMoneyVal'+ payInfoNum+'" value="'+costMoney+'" onblur="countInvoiceInfo('+ payInfoNum+')"/>' +
            '</td>' +
            '<td width="27%">' +
            '<input type="text" id="invoiceNumber_' + thisI + payInfoNum+'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][expenseinv][0][invoiceNumber]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort invoiceNumVal'+ payInfoNum+'" value="1" onblur="countInvoiceInfo('+ payInfoNum+')"/>' +
            '<input type="hidden" id="invIsSubsidy_' + thisI + payInfoNum+'" name="expenseTmp'+payInfoNumStr+'[expensedetail]['+ thisCostType +'][expenseinv][0][isSubsidy]" value="0"/>' +
            '</td>' +
            '<td width="15%">' +
            '<img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_lnvoice('+ thisCostType +','+ payInfoNum+')"/>' +
            '</td>' +
            '</tr>' +
            '</table>' +
            '</td>';
    }
    str += '</tr>';

    return str;
};

// ����
var checkForm = function(payInfoNum){
    payInfoNum = (payInfoNum == undefined)? '' : payInfoNum;
    var pass = true;
    // ��鷢Ʊ����
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
                    alert( '��֧����ʽ'+payInfoNum+'��'+ costType + ' ��Ʊ��ϸ�д��������͵��н��ķ�Ʊ��ϸ��');
                }else{
                    alert( costType + ' ��Ʊ��ϸ�д��������͵��н��ķ�Ʊ��ϸ��');
                }
                pass = false;
                return false;
            }else if(invoiceNumber * 1 == 0 || invoiceNumber == ''){
                if(payInfoNum != '' && payInfoNum > 0){
                    alert( '��֧����ʽ'+payInfoNum+'��'+ costType + ' ��Ʊ��ϸ�д��ڷ�Ʊ����Ϊ0��յ���');
                }else{
                    alert( costType + ' ��Ʊ��ϸ�д��ڷ�Ʊ����Ϊ0��յ���');
                }
                pass = false;
                return false;
            }
        }
    });

    $("#costTypeInner"+payInfoNum+" input[id^='costTypeId']").each(function(i,n){
        //����id
        var costTypeId = this.value*1;
        var delTag = $("#isDelTag_"+ costTypeId + payInfoNum).length;

        //����
        var costMoney = $("#costMoney" + costTypeId + payInfoNum).val();
        // var days = $("#days" + costTypeId).val();
        // costMoney = accMul(costMoney,days,2);

        //���ƻ���
        costType = $("#costType" + costTypeId + payInfoNum).val();

        if(pass){
            //���³�ʼ�����
            detailAll = 0;
            $("#table_"+ costTypeId + payInfoNum +" select[id^='select_" + costTypeId + "_']").each(function(i,n){
                delTag = $("#feeTbl"+payInfoNum+" #isDelTag_"+ costTypeId + "_" + i + payInfoNum).length;
                if(delTag == 0){
                    //��ȡ���
                    detailMoney = $("#invoiceMoney_"+ costTypeId + "_" + i + payInfoNum).val();
                    detailMoney = (detailMoney && detailMoney != undefined)? detailMoney.replaceAll(",", "") : 0;
                    //���㷢Ʊ���
                    detailAll = accAdd(detailAll,detailMoney,2);
                }
            });

            if(detailAll *1 != costMoney){
                if(payInfoNum != '' && payInfoNum > 0){
                    alert( '��֧����ʽ'+payInfoNum+'��'+ costType + ' �з��ý��' + costMoney + " �����ڷ�Ʊ�ϼƽ�� " + detailAll + ",���޸ĺ��ٽ��б������");
                }else{
                    alert( costType + ' �з��ý��' + costMoney + " �����ڷ�Ʊ�ϼƽ�� " + detailAll + ",���޸ĺ��ٽ��б������");
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
                    alert("����ɹ�!");
                    parent.parent.reloadList();
                    parent.closeFun();
                }else{
                    alert("����ʧ��,�����ԡ�");
                }
            }
        });
    }
}