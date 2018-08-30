$(function () {
    $("#innerTable").width(document.documentElement.clientWidth - 10);

    //��Ⱦ���ź�ҵ��Ա��Ϣ
    if ($("#salesmanId").length != 0) {
        $("#salesman").yxselect_user({
            hiddenId: 'salesmanId',
            isGetDept: [true, "departmentsId", "departments"]
        });
        $("#departments").yxselect_dept({
            hiddenId: 'departmentsId'
        });
    }

    //����id�жϵ���˰��ѡ����
    if ($("#id").length == 1) {
//		changeTaxRate('invType');
        // ������Ⱦ
        if ($("#isRed").val() == "1") {
            $("#formTitle").attr('style','color:red');
        }
    } else {
        changeTaxRateWithOutCount('invType');

        // ����ҳ����ݺ���Ȩ����ʾ��������ѡ��
        if (redLimit == "1") {
            $('#redSpan').show();
        }
    }

    /**
     * ��֤��Ϣ
     */
    validate({
        "supplierName": {
            required: true
        },
        "invoiceNo": {
            required: true
        },
        "formDate": {
            required: true
        },
        "salesman": {
            required: true
        },
        "departments": {
            required: true
        },
        "businessBelongName": {
            required: true
        }
    });

    if ($("#TO_NAME").length != 0) {
        $("#TO_NAME").yxselect_user({
            hiddenId: 'TO_ID',
            mode: 'check'
        });
    }
});

// �������л�ʱ�ı�����Ⱦ
function changeTitle(thisVal){
    if(thisVal == '1'){
        $("#formTitle").attr('style','color:red');
    }else{
        $("#formTitle").attr('style','');
    }
}

//�ı䵥��˰�� - �������ӱ���㷽��
function changeTaxRateWithOutCount(thisVal) {
    var taxRateObj = $("#taxRate");
    var taxRate = 0;
    if ($("#" + thisVal).find("option:selected").attr("e1") != "") {
        taxRate = $("#" + thisVal).find("option:selected").attr("e1");
    }
    taxRateObj.val(taxRate);
}

//�ı䵥��˰�� - �����ӱ���㷽��
function changeTaxRate(thisVal) {
    var taxRateObj = $("#taxRate");
    var taxRate = 0;
    if ($("#" + thisVal).find("option:selected").attr("e1") != "") {
        taxRate = $("#" + thisVal).find("option:selected").attr("e1");
    }
    taxRateObj.val(taxRate);
    countAll2();
}

//���㵥�ݽ��
function countAll(rowNum, thisId) {
    //���㱾��
    countThisRow(rowNum, thisId);
    //�����
    countForm();
    countFinalAmount();
}

//���ڼ��� - ��������������
function countThisRow(rowNum, thisId) {
    //�ӱ�ǰ���ַ���
    var beforeStr = "innerTable_cmp_";
    //����
    var price = 0;
    //��˰����
    var taxPrice = 0;
    //˰��
    var taxRate = $("#taxRate").val();
    //���н��
    var thisAmount = 0;
    //���м�˰�ϼ�
    var thisAllCount = 0;

    //��ȡ��ǰ����
    var number = $("#" + beforeStr + "number" + rowNum).val();

    if ($("#" + beforeStr + "price" + rowNum + "_v").val() == "" && $("#" + beforeStr + "taxPrice" + rowNum + "_v").val() == "") {
        return false;
    }

    if (thisId == 'price') {
        //��ȡ��ǰ����
        price = $("#" + beforeStr + "price" + rowNum + "_v").val();

        //���㱾�н�� - ����˰
        thisAmount = accMul(price, number, 2);
        setMoney(beforeStr + "amount" + rowNum, thisAmount);

        //���㺬˰����
        taxPrice = accMul(price, accAdd(1, accDiv(taxRate, 100, 2), 2), 2);
        setMoney(beforeStr + "taxPrice" + rowNum, taxPrice);

        //�����˰�ϼ�
        thisAllCount = accMul(taxPrice, number, 2);
        setMoney(beforeStr + "allCount" + rowNum, thisAllCount);

        setMoney(beforeStr + "assessment" + rowNum, accSub(thisAllCount, thisAmount, 2));

    } else if (thisId == 'taxPrice') {
        //��ȡ��ǰ����
        taxPrice = $("#" + beforeStr + "taxPrice" + rowNum + "_v").val();

        //�����˰�ϼ�
        thisAllCount = accMul(taxPrice, number, 2);
        setMoney(beforeStr + "allCount" + rowNum, thisAllCount);

        //���㲻��˰����
        price = accDiv(taxPrice, accAdd(1, accDiv(taxRate, 100, 2), 2), 2);
        setMoney(beforeStr + "price" + rowNum, price);

        //���㱾�н�� - ����˰
        thisAmount = accMul(price, number, 2);
        setMoney(beforeStr + "amount" + rowNum, thisAmount);

        setMoney(beforeStr + "assessment" + rowNum, accSub(thisAllCount, thisAmount, 2));

    } else {
        //��ȡ��ǰ����
        taxPrice = $("#" + beforeStr + "taxPrice" + rowNum + "_v").val();

        //�����˰�ϼ�
        thisAllCount = accMul(taxPrice, number, 2);
        setMoney(beforeStr + "allCount" + rowNum, thisAllCount);

        //���㲻��˰����
        price = accDiv(taxPrice, accAdd(1, accDiv(taxRate, 100, 2), 2), 2);
        setMoney(beforeStr + "price" + rowNum, price);

        //���㱾�н�� - ����˰
        thisAmount = accMul(price, number, 2);
        setMoney(beforeStr + "amount" + rowNum, thisAmount);

        setMoney(beforeStr + "assessment" + rowNum, accSub(thisAllCount, thisAmount, 2));
    }
}

//ֻ���㵥�ݽ��
function countForm() {
    //�ӱ����
    var thisGrid = $("#innerTable");
    //���㵥�ݽ�� - ����˰
    var allAmount = 0;
    thisGrid.yxeditgrid("getCmpByCol", "amount").each(function (i, n) {
        allAmount = accAdd(allAmount, $(this).val(), 2);
    });

    setMoney('allAmount', allAmount);
    $("#listAmount").val(moneyFormat2(allAmount));

    //���㵥�ݼ�˰�ϼ�
    var allCount = 0;
    thisGrid.yxeditgrid("getCmpByCol", "allCount").each(function () {
        allCount = accAdd(allCount, $(this).val(), 2);
    });

    setMoney('formCount', allCount);
    $("#listCount").val(moneyFormat2(allCount));

    //���㵥��˰��
    var allAssessment = 0;
    thisGrid.yxeditgrid("getCmpByCol", "assessment").each(function () {
        allAssessment = accAdd(allAssessment, $(this).val(), 2);
    });

    setMoney('formAssessment', allAssessment);
    $("#listAssessment").val(moneyFormat2(allAssessment));

    //���㵥��˰��
    var allNumber = 0;
    thisGrid.yxeditgrid("getCmpByCol", "number").each(function () {
        allNumber = accAdd(allNumber, $(this).val(), 2);
    });
    setMoney('formNumber', allNumber);
    $("#listNumber").val(allNumber);
    countFinalAmount();
    return true;
}

//���㵥�ݽ��
function countAll2() {
    //�ӱ�ǰ���ַ���
    var beforeStr = "innerTable_cmp_";
    //�ӱ����
    var thisGrid = $("#innerTable");

    //����˰���
    var thisPrice = 0;

    //˰��
    var taxRate = $("#taxRate").val();

    //���㵥�ݽ�� - ����˰
    thisGrid.yxeditgrid("getCmpByCol", "taxPrice").each(function (i, n) {
        //���㵱ǰ����
        thisPrice = accDiv($(this).val(), accAdd(1, accDiv(taxRate, 100, 2), 2), 2);

        setMoney(beforeStr + "price" + i, thisPrice);

        $("#" + beforeStr + "taxPrice" + i + "_v").trigger('blur');
    });

    countForm();
}

//��ʼ�������͵���
function initNumAndPrice(rowNum, thisType) {
    //�ӱ�ǰ���ַ���
    var beforeStr = "innerTable_cmp_";

    //��������
    var thisNumObj = $("#" + beforeStr + "number" + rowNum);
    //���е���
    var thisPriceObj = $("#" + beforeStr + "price" + rowNum + "_v");
    var thisTaxPriceObj = $("#" + beforeStr + "taxPrice" + rowNum + "_v");
    //���н��
    var thisAmountObj = $("#" + beforeStr + "amount" + rowNum + "_v");
    var thisCountObj = $("#" + beforeStr + "allCount" + rowNum + "_v");

    if ((thisNumObj.val() == "" || thisNumObj.val() * 1 == 1) && thisType == 'allCount') {
        thisNumObj.val(1);
        thisTaxPriceObj.val(thisCountObj.val());

        thisTaxPriceObj.trigger('blur');
    } else if ((thisNumObj.val() == "" || thisNumObj.val() * 1 == 1) && thisType == 'amount') {
        thisNumObj.val(1);
        thisPriceObj.val(thisAmountObj.val());

        thisPriceObj.trigger('blur');
    }
}

//�������˰��ϼ�
function countAccount(rowNum) {
    //�ӱ�ǰ���ַ���
    var beforeStr = "innerTable_cmp_";

    //��ȡ���
    var amount = $("#" + beforeStr + "amount" + rowNum + "_v").val();
    //��ȡ˰��
    var assessment = $("#" + beforeStr + "assessment" + rowNum + "_v").val();
    //�����ĺϼ�ֵ
    var allCount = accAdd(amount, assessment, 2);

    setMoney(beforeStr + "allCount" + rowNum, allCount);
}


function audit(thisType) {
    document.getElementById('form1').action = "?model=finance_invother_invother&action=add&act=" + thisType;
}

function auditEdit(thisType) {
    document.getElementById('form1').action = "?model=finance_invother_invother&action=edit&act=" + thisType;
}

//��ʼ���ϼ���
function initListCount() {
    $("#innerTable").find('tbody').after("<tr class='tr_count'><td colspan='3'>�ϼ�</td>"
    + "<td><input type='text' class='readOnlyTxtShortCount' id='listNumber' value='"
    + "' readonly='readonly'/></td>"
    + "<td colspan='2'></td>"
    + "<td><input type='text' class='readOnlyTxtMiddleCount' id='listAmount' value='"
    + "' readonly='readonly'/></td>"
    + "<td><input type='text' class='readOnlyTxtMiddleCount' id='listAssessment' value='"
    + "' readonly='readonly'/></td>"
    + "<td><input type='text' class='readOnlyTxtMiddleCount' id='listCount' value='"
    + "' readonly='readonly'/></td>"
    + "<td></td></tr>");
}

//����һ������֤
function checkform() {
    // ���÷�̯
    if ($("#isShare").val() == "1") {
        // ���ֵ�ʱ��ת���������
        var formMoney = $("#" + getMoneyKey()).val();

        if ($("#id").length == 0) {
            if ($("input:radio[name='invother[isRed]']:checked").val() == "1") {
                formMoney = -formMoney;
            }
        } else {
            if ($("#isRed").val() == "1") {
                formMoney = -formMoney;
            }
        }

        //��ʾ���÷�̯��ϸ
        if ($("#shareGrid").costShareGrid('checkForm', formMoney) == false) {
            return false;
        }
    }

    if ($("#sourceType").val() == 'YFQTYD03' && $("#period").val() == "") {
        alert('��ѡ������·�');
        return false;
    }
    return true;
}

// ��ȡ��Ʊ���Ͷ�Ӧ���ֶ�
function getMoneyKey() {
    var invoiceTypeE3 = $("#invType").find("option:selected").attr("e3");
    return invoiceTypeE3 && invoiceTypeE3 == "1" ? 'allAmount' : 'formCount';
}

// ��Ʊ���͸ı�ʱ�����¼� -- �˷���ͨ��jquery�󶨣�����ֱ����$(this)
var invoiceTypeChange = function () {
    $("#shareGrid").costShareGrid('changeCountKey', getMoneyKey());
};

//���������(�⳵ҵ��)
function countFinalAmount(){
    if($("#finalAmount").length>0){
        var invType=$("#invType").val();
        if(invType=="ZZSZYFP13"||invType=="ZZSZYFP6"){//Ϊ��ֵ˰ר�÷�Ʊȡ�ĳɱ����Ϊ�ܽ��
            $("#finalAmount_v").val( $("#allAmount_v").val());
            $("#finalAmount").val( $("#allAmount").val());
        }else{//��ר�÷�Ʊȡ���Ǽ�˰�ϼ�
            $("#finalAmount_v").val( $("#formCount_v").val());
            $("#finalAmount").val( $("#formCount").val());
        }
    }
}