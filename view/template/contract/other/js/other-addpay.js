$(document).ready(function() {
    //ǩԼ��λ
    $("#signCompanyName").yxcombogrid_signcompany({
        hiddenId: 'signCompanyId',
        isFocusoutCheck: false,
        height: 300,
        gridOptions: {
            isTitle: true,
            event: {
                'row_dblclick': function(e, row, data) {
                    $("#proCode").val(data.proCode);
                    $("#proName").val(data.proName);
                    $("#linkman").val(data.linkman);
                    $("#phone").val(data.phone);
                    $("#address").val(data.address);
                }
            }
        }
    });

    //��ʡ������provinceArr��ֵ��proCode
    addDataToProvince(getProvince(), 'proCode');

    //��ʼ��ʡ������
    setProName();
    //��ѡ������
    $("#principalName").yxselect_user({
        hiddenId: 'principalId',
        isGetDept: [true, "deptId", "deptName"]
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
        }
    });

    //�ж�ϵͳ��ͬ�����ɹ���
    if ($("#isSysCode").val() == 0) {
        $("#orderCodeNeed").html("[*]");

        /**
         * ��ͬ��Ψһ����֤
         */
        $("#orderCode").attr("class", "txt").attr("readonly", false).ajaxCheck({
            url: "?model=contract_other_other&action=checkRepeat",
            alertText: "* �ú�ͬ���Ѵ���",
            alertTextOk: "* �ú�ͬ�ſ���"
        });
    }

    loadShareGrid();
});

// ���ط�̯�б�
function loadShareGrid() {
    var fundType = $("#fundType").val();
    if (fundType == 'KXXZB') {
        //��ʾ���÷�̯��ϸ
        var shareGridObj = $("#shareGrid");
        if (shareGridObj.length > 0) {
            $("#shareGridTr").show();
            $('#st').tabs();
            shareGridObj.costShareGrid({
                url: "?model=finance_cost_costshare&action=listjsonForFlight",
                param: {'projectId': $("#projectId").val(),"businessBelong" : $("#businessBelong").val()},
                type: 'view',
                isShowCountRow: true,
                title: '',
                event: {
                    'reloadData': function (e, g, data) {
                        if (!data) {
                            shareGridObj.find("tbody").append("<tr><td colspan='10'> -- ���޷�̯���� --</td></tr>");
                        } else {
                            g.costShareMoneyView(data);
                        }
                    }
                }
            });
        }
    }
}