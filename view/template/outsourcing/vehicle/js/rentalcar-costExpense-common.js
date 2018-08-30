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
var setBillTypeStr = function(billTypeArr,defaultVal){
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

//���ӷ�Ʊ��Ϣ
var add_lnvoice = function(id){
    //���ôӱ�
    var tableObj = $("#table_" + id);
    //�ӱ��ж���
    var tableTrObj = $("#table_" + id + " tr");
    //�ӱ�����
    var tableTrLength = tableTrObj.length;
    //����Id��׺
    var countStr = id + "_" + tableTrLength;


    var lastMoney = '';
    var invoiceNumber = '';

    var str = '<tr id="tr_' + countStr + '">' +
        '<td width="32%"><select id="select_' + countStr + '" class="invoiceTypesIdVal" rowCount="'+ countStr +'" name="expenseTmp[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][BillTypeID]" style="width:90px"><option value="">��ѡ��Ʊ</option>' + billTypeStr +'</select></td>' +
        '<td width="29%"><input id="invoiceMoney_' + countStr + '" costTypeId="'+ id +'" rowCount="'+ countStr +'" name="expenseTmp[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][Amount]" type="text" class="txtshort formatMoney invoiceMoneyVal" value="'+lastMoney+'" onblur="countInvoiceInfo()"/></td>' +
        '<td width="27%"><input id="invoiceNumber_' + countStr + '" costTypeId="'+ id +'" rowCount="'+ countStr +'"  name="expenseTmp[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][invoiceNumber]" type="text" class="txtshort invoiceNumVal" value="'+ invoiceNumber +'" onblur="countInvoiceInfo()"/>' +
        '<input type="hidden" id="invIsSubsidy_' + countStr + '" name="expenseTmp[expensedetail]['+ id + '][expenseinv][' + tableTrLength + '][isSubsidy]" value="0"/></td>' +
        '<td width="15%"><img style="cursor:pointer;" src="images/removeline.png" title="ɾ�����з�Ʊ" onclick="delete_lnvoice(' + id + ',this)"/></td>' +
        '</tr>';
    tableObj.append(str);
};

//ɾ����Ʊ��Ϣ
var delete_lnvoice = function(id,obj){
    if (confirm('ȷ��Ҫɾ�����У�')) {
        var rowNo = obj.parentNode.parentNode.rowIndex;
        $(obj).parent().parent().hide();
        $(obj).parent().append('<input type="hidden" name="expenseTmp[expensedetail]['+
            id +'][expenseinv][' +
            rowNo + '][isDelTag]" id="isDelTag_'+ id +'_'+rowNo +'" value="1"/>');

        //��ע�߶ȵ���
        var remarkObj = $("#remark" + id);
        remarkObj.animate({height:"-=33"},100);


        //��ϸ����
        countInvoiceInfo();
    }
};

// ��Ⱦ������Ϣ�б�
var setCustomCostType = function(thisCostType,costTypeObj,indexKey){
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
            var ctrlBtn = (i > 0)? '<img style="cursor:pointer;" src="images/removeline.png" title="ɾ�����з�Ʊ" onclick="delete_lnvoice(' + thisCostType + ',this)"/>' :
            '<img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_lnvoice('+ thisCostType +')"/>';
            str += '<tr id="tr_' + thisI + '">' +
                '<td width="32%">' +
                '<select id="select_' + thisI + '" class="invoiceTypesIdVal" name="expenseTmp[expensedetail]['+ thisCostType +'][expenseinv]['+i+'][BillTypeID]" rowCount="'+ thisI +'" style="width:90px"><option value="">��ѡ��Ʊ</option>' + selfBillTypeStr +'</select>' +
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
            '<select id="select_' + thisI + '" class="invoiceTypesIdVal" name="expenseTmp[expensedetail]['+ thisCostType +'][expenseinv][0][BillTypeID]" rowCount="'+ thisI +'" style="width:90px"><option value="">��ѡ��Ʊ</option>' + selfBillTypeStr +'</select>' +
            '</td>' +
            '<td width="29%">' +
            '<input  type="text" id="invoiceMoney_' + thisI + '" name="expenseTmp[expensedetail]['+ thisCostType +'][expenseinv][0][Amount]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort formatMoney invoiceMoneyVal" value="'+costMoney+'" onblur="countInvoiceInfo()"/>' +
            '</td>' +
            '<td width="27%">' +
            '<input type="text" id="invoiceNumber_' + thisI + '" name="expenseTmp[expensedetail]['+ thisCostType +'][expenseinv][0][invoiceNumber]" costTypeId="'+ thisCostType +'" rowCount="'+ thisI +'" class="txtshort invoiceNumVal" value="1" onblur="countInvoiceInfo()"/>' +
            '<input type="hidden" id="invIsSubsidy_' + thisI + '" name="expenseTmp[expensedetail]['+ thisCostType +'][expenseinv][0][isSubsidy]" value="0"/>' +
            '</td>' +
            '<td width="15%">' +
            '<img style="cursor:pointer;" src="images/add_item.png" title="�����" onclick="add_lnvoice('+ thisCostType +')"/>' +
            '</td>' +
            '</tr>' +
            '</table>' +
            '</td>';
    }
    str += '</tr>';

    return str;
};

// ͳ�Ʒ��ý��
var countMoney = function(){
    var totalCostMoney = 0;
    $.each($(".costMoneyVal"),function(i,item){
        var selfVal = $(item).val().replaceAll(",", "");
        totalCostMoney += (selfVal == '' || !Number(selfVal))? 0 : Number(selfVal);
    });
    return totalCostMoney;
};

// ����ͳ�Ƶ�ǰҳ���ڵķ�Ʊ����
var countInvoiceInfo = function(){
    var totalInvoiceMoney = 0, totalInvoiceNum = 0;
    // ͳ�Ʒ�Ʊ���
    $.each($(".invoiceMoneyVal"),function(i,item){
        var selfVal = $(item).val().replaceAll(",", "");
        var rowNum = $(item).attr("rowcount");
        var isDelTag = $("#isDelTag_" + rowNum).val();
        if(isDelTag != 1){
            totalInvoiceMoney += (selfVal == '' || !Number(selfVal))? 0 : Number(selfVal);
            $("#invoiceMoney_" + rowNum).val(moneyFormat2(selfVal,2,2));
        }
    });

    // ͳ�Ʒ�Ʊ����
    $.each($(".invoiceNumVal"),function(i,item){
        var selfVal = $(item).val().replaceAll(",", "");
        var rowNum = $(item).attr("rowcount");
        var isDelTag = $("#isDelTag_" + rowNum).val();
        if(isDelTag != 1){
            totalInvoiceNum += (selfVal == '' || !Number(selfVal))? 0 : Number(selfVal);
        }
    });

    // ����ҳ����ʾ
    $("#invoiceMoney_v").val(totalInvoiceMoney);
    $("#countInvoiceNum_v").val(totalInvoiceNum);
    $("#invoiceMoney").val(totalInvoiceMoney);
    $("#countInvoiceNum").val(totalInvoiceNum);
    $("#invoiceMoney_v").blur();
};

// ����
var checkForm = function(){
    var pass = true;
    // ��鷢Ʊ����
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
                alert( costType + ' ��Ʊ��ϸ�д��������͵��н��ķ�Ʊ��ϸ��');
                pass = false;
                return false;
            }else if(invoiceNumber * 1 == 0 || invoiceNumber == ''){
                alert( costType + ' ��Ʊ��ϸ�д��ڷ�Ʊ����Ϊ0��յ���');
                pass = false;
                return false;
            }
        }
    });

    $("#costTypeInner input[id^='costTypeId']").each(function(i,n){
        //����id
        var costTypeId = this.value*1;
        var delTag = $("#isDelTag_"+ costTypeId).length;

        //����
        var costMoney = $("#costMoney" + costTypeId).val();
        // var days = $("#days" + costTypeId).val();
        // costMoney = accMul(costMoney,days,2);

        //���ƻ���
        costType = $("#costType" + costTypeId).val();

        if(pass){
            //���³�ʼ�����
            detailAll = 0;
            $("#table_"+ costTypeId +" select[id^='select_" + costTypeId + "_']").each(function(i,n){
                delTag = $("#feeTbl #isDelTag_"+ costTypeId + "_" + i).length;
                if(delTag == 0){
                    //��ȡ���
                    detailMoney = $("#invoiceMoney_"+ costTypeId + "_" + i).val();
                    detailMoney = (detailMoney && detailMoney != undefined)? detailMoney.replaceAll(",", "") : 0;
                    //���㷢Ʊ���
                    detailAll = accAdd(detailAll,detailMoney,2);
                }
            });

            if(detailAll *1 != costMoney){
                alert( costType + ' �з��ý��' + costMoney + " �����ڷ�Ʊ�ϼƽ�� " + detailAll + ",���޸ĺ��ٽ��б������");
                pass = false;
                return false;
            }
        }
    });
    return pass;
};

// �ύ��
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
                    alert("����ɹ�!");
                    parent.parent.reloadList();
                    parent.closeFun();
                }else{
                    alert("����ʧ��,�����ԡ�");
                }
            }
        });
    }
};