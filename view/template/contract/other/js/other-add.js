$(document).ready(function () {
    //ǩԼ��λ
    $("#signCompanyName").yxcombogrid_signcompany({
        hiddenId: 'signCompanyId',
        height: 250,
        isFocusoutCheck: false,
        gridOptions: {
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#proCode").val(data.proCode);
                    $("#proName").val(data.proName);
                    $("#linkman").val(data.linkman);
                    $("#phone").val(data.phone);
                    $("#address").val(data.address);
                    $("#payee").val(data.signCompanyName);
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
                'row_dblclick': function () {
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

    //��ʼ��ʡ������
    setProName();
    //��ѡ������
    $("#principalName").yxselect_user({
        hiddenId: 'principalId',
        isGetDept: [true, "deptId", "deptName"],
        event: {
            select: function (e, obj) {
                if ($("#isNeedPayapply").attr("checked")) {
                    $("#feeDeptId").val(obj.deptId);
                    $("#feeDeptName").val(obj.deptName);
                }
                $("#otherFeeDeptId").val(obj.deptId);
                $("#otherFeeDeptName").val(obj.deptName);
            }
        }
    });

    // ���ұ�
    $("#currency").yxcombogrid_currency({
        valueCol: 'currency',
        isFocusoutCheck: false,
        gridOptions: {
            showcheckbox: false
        }
    });

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

    //�ж�ϵͳ��ͬ�����ɹ���
    if ($("#isSysCode").val() == 0) {
        $("#orderCodeNeed").html("[*]");
        // ��֤��Ϣ
        validate({
            orderCode: {
                required: true,
                length: [0, 100]
            }
        });
        var url = "?model=contract_other_other&action=checkRepeat";
        $("#orderCode").attr("class", "txt").attr("readonly", false).ajaxCheck({
            url: url,
            alertText: "* �ú�ͬ���Ѵ���",
            alertTextOk: "* �ú�ͬ�ſ���"
        });
    }

    // ��Ʊ�¼���
    $("#invoiceType").bind("change", invoiceTypeChange);
});

// ���ط�̯�б�
function loadShareGrid() {
    //��ʾ���÷�̯��ϸ
    $("#shareGrid").costShareGrid({
        objName: 'other[costshare]',
        isShowExcelBtn: true,
        isShowCountRow: true,
        countKey: getMoneyKey()
    });
}