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
    } else {
        $("#isInvoiceNo").attr('checked', true);
    }

    // ��Ʊ�¼���
    $("#invoiceType").bind("change", invoiceTypeChange);
});

// ���ط�̯�б�
function loadShareGrid() {
    //��ʾ���÷�̯��ϸ
    $("#shareGridTr").show();
    $("#shareGrid").costShareGrid({
        objName: 'other[costshare]',
        url: "?model=finance_cost_costshare&action=listjson",
        param: {objType: 2, objId: $("#id").val()},
        isShowCountRow: true,
		isShowExcelBtn: true,
        countKey: getMoneyKey()
    });
}