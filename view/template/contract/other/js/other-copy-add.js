$(document).ready(function () {
    //������Ϣ��Ⱦ
    var isNeedPayapplyObj = $("#isNeedPayapply");
    if (isNeedPayapplyObj.val() == 1) {
        isNeedPayapplyObj.trigger('click');
        showPayapplyInfo(isNeedPayapplyObj[0]);
    }

    //ǩԼ��λ
    $("#signCompanyName").yxcombogrid_signcompany({
        hiddenId: 'signCompanyId',
        isFocusoutCheck: false,
        gridOptions: {
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#proCode").val(data.proCode);
                    $("#proName").val(data.proName);
                    $("#linkman").val(data.linkman);
                    $("#phone").val(data.phone);
                    $("#address").val(data.address);
                }
            }
        }
    });

    //������˾
    $("#businessBelongName").yxcombogrid_branch({
        hiddenId: 'businessBelong',
        height: 250,
        isFocusoutCheck: false,
        gridOptions: {
            showcheckbox: false,
            event: {
                'row_dblclick': function (e, row, data) {
                    //��ʼ�����ṹ
                    initTree();
                    //�������η�Χ
                    reloadManager();
                }
            }
        }
    });

    //��ȡʡ�����鲢��ֵ��provinceArr
    var provinceArr = getProvince();

    //��ʡ������provinceArr��ֵ��proCode
    addDataToProvince(provinceArr, 'proCode');

    //��ѡ������
    $("#principalName").yxselect_user({
        hiddenId: 'principalId',
        isGetDept: [true, "deptId", "deptName"]
    });

    // ���ұ�
    $("#currency").yxcombogrid_currency({
        valueCol: 'currency',
        isFocusoutCheck: false,
        gridOptions: {
            showcheckbox: false
        }
    });

    //��ʼ����Ŀ��Ϣ
    //changeProjectClear();

    // ��֤��Ϣ
    validate({
        orderName: {
            required: true,
            length: [0, 100]
        },
        signCompanyName: {
            required: true,
            length: [0, 100]
        },
        principalName: {
            required: true,
            length: [0, 20]
        },
        phone: {
            required: true
        },
        linkman: {
            required: true,
            length: [0, 100]
        },
        signDate: {
            custom: ['date']
        },
        proCode: {
            required: true
        },
        businessBelongName: {
            required: true
        }
    });

    //�Ƿ�ί�и���
    if ($("#isEntrust").val() == "1") {
        $("#isEntrustYes").attr('checked', true);
        $("#bank").val('���д���').attr('class', 'readOnlyTxtNormal').attr('readonly', true);
        $("#account").val('���д���').attr('class', 'readOnlyTxtNormal').attr('readonly', true);
    } else {
        $("#isEntrustNo").attr('checked', true);
    }

    //�Ƿ񿪾ݷ�Ʊ
    if ($("#isInvoice").val() == "1") {
        $("#isInvoiceYes").attr('checked', true);
    } else if ($("#isInvoice").val() == "0") {
        $("#isInvoiceNo").attr('checked', true);
    }

    // ��Ʊ�¼���
    $("#invoiceType").bind("change", invoiceTypeChange);

    // ���ø���ҵ������ѡ��
    var mainTypeSlted = $("#payForBusinessMain option");
    var payForBusinessVal = $("#payForBusinessVal").val();
    $.each(mainTypeSlted,function(i,item){
        if($(item).val() == payForBusinessVal){
            $(item).attr("selected","selected");
            setPayFoeBusinessValue('payType',true);
        }
    });

    // ����ԭ�������б�����Ϣ
    if(payForBusinessVal == 'FKYWLX-03' || payForBusinessVal == 'FKYWLX-04'){
        var isBankbackLetterVal = $("#isBankbackLetterVal").val();
        var defalutBackLetterEndDate = $("#isBankbackLetterVal").attr("defalut-date");
        if(isBankbackLetterVal == 1){
            $("#isBankbackLetterYes").attr("checked","checked");
            $("#backLetterEndDate").val(defalutBackLetterEndDate);
            // $(".backLetterEndDateWrap").show();
            // $("#backLetterEndDate").addClass("validate[required]");
        }else if(isBankbackLetterVal == 0){
            $("#isBankbackLetterNo").attr("checked","checked");
            $("#backLetterEndDate").val('');
            // $(".backLetterEndDateWrap").hide();
        }
        $("#backLetterEndDate").removeClass("validate[required]");
    }

    var isNeedRelativeContract = $("#isNeedRelativeContract").val();
    var hasRelativeContract = $("#hasRelativeContract").val();
    if(hasRelativeContract == 2){
        $("#relativeContract").val("");
        $("#relativeContractId").val("");
        $(".sltRelativeContractWrap").hide();
    }
    if(isNeedRelativeContract == 1){
        $(".relativeContract").show();
    }
});

// ���ط�̯�б�
function loadShareGrid() {
    //��ʾ���÷�̯��ϸ
    $("#shareGridTr").show();
	$('#st').tabs();
    $("#shareGrid").costShareGrid({
        objName: 'other[costshare]',
        url: "?model=finance_cost_costshare&action=pageJsonForEdit",
        param: {objType: 2, objId: $("#id").val()},
		title: '',
        isShowCountRow: true,
        countKey: getMoneyKey()
    });
}