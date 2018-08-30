$(function () {
    $("#receiveTable").yxeditgrid({
        objName: 'receive[receiveItem]',
        title: '������ϸ',
        isAdd: false,
        url: '?model=asset_purchase_receive_receiveItem&action=getApplyItemPage',
        param: {
            "applyId": $("#applyId").val(),
            "isDel": '0'
        },
        event: {
            reloadData: function (e, g, data) {
                var isCanAdd = false;
                if (data) {
                    for (var i = 0; i < data.length; i++) {
                        if (data[i].checkAmount > 0) {
                            isCanAdd = true;
                        }
                    }
                }
                if (!isCanAdd) {
                    alert("�òɹ�����������Ѿ�ȫ������.")
                    self.parent.tb_remove();
                }
                check_all();
            },
            removeRow: function (t, rowNum, rowData) {
                check_all();
            }
        },
        colModel: [{
            display: '�ɹ�����id',
            name: 'applyId',
            type: 'hidden'
        }, {
            display: '�ɹ��������',
            name: 'applyCode',
            type: 'hidden'
        }, {
            display: '�ɹ�������ϸ��id',
            name: 'applyEquId',
            type: 'hidden'
        }, {
            display: '�ʲ�����',
            name: 'assetName',
            tclass: 'readOnlyTxtItem',
            readonly: true,
            process: function ($input, row) {
                if (row.productName == '') {
                    $input.val(row.inputProductName);
                } else {
                    $input.val(row.productName);
                }
            }
        }, {
            display: '���',
            name: 'spec',
            tclass: 'readOnlyTxtItem',
            readonly: true,
            process: function ($input, row) {
                $input.val(row.pattem);
            }
        }, {
            display: '�������',
            name: 'maxNum',
            process: function ($input, row) {
                $input.val(row.checkAmount);
            },
            type: 'hidden'
        }, {
            display: '����',
            name: 'checkAmount',
            tclass: 'txtshort',
            event: {
                blur: function () {
                    var rownum = $(this).data('rowNum');// �ڼ���
                    var grid = $(this).data('grid');// ������
                    //��֤�����Ƿ�Ϸ�
                    var maxNum = grid.getCmpByRowAndCol(rownum, 'maxNum').val();
                    var checkAmount = $(this).val();
                    if (checkAmount != "" && !isNum(checkAmount)) {
                        alert("��������������");
                        $(this).val(maxNum).focus();
                        return false;
                    } else if (accSub(checkAmount, maxNum) > 0) {
                        alert("�����������ܴ��ڲɹ�����������" + maxNum + "��");
                        $(this).val(maxNum).focus();
                        return false;
                    }

                    var price = grid.getCmpByRowAndCol(rownum, 'price').val();
                    var $amount = grid.getCmpByRowAndCol(rownum, 'amount');
                    var checkAmount = $(this).val();
                    var amountId = $amount.attr('id').replace('_v', '');
                    var amount = accMul(price, checkAmount);
                    setMoney(amountId, amount);
                    check_all();
                }
            },
            validation: {
                custom: ['onlyNumber']
            }
        }, {
            display: '����',
            name: 'price',
            tclass: 'txtshort',
            type: 'money',
            event: {
                blur: function () {
                    var rownum = $(this).data('rowNum');// �ڼ���
                    var grid = $(this).data('grid');// ������
                    var checkAmount = grid.getCmpByRowAndCol(rownum, 'checkAmount').val();
                    var price = $(this).val();
                    var $amount = grid.getCmpByRowAndCol(rownum, 'amount');
                    var amountId = $amount.attr('id').replace('_v', '');
                    var amount = accMul(price, checkAmount);
                    setMoney(amountId, amount);
                    check_all();
                }
            }
        }, {
            display: '���',
            name: 'amount',
            tclass: 'readOnlyTxtItem',
            type: 'money',
            readonly: true,
            process: function ($input, row) {
                $input.val(row.checkAmount * row.price);
            }
        }, {
            display: '��ע',
            name: 'remark',
            tclass: 'txt'
        }]
    });
    // ѡ����Ա���
    $("#salvage").yxselect_user({
        hiddenId: 'salvageId',
        isGetDept: [true, "deptId", "deptName"],
        event: {
            select: function (e, returnValue) {
                if (returnValue) {
                    $('#company').val(returnValue.companyCode)
                    $('#companyName').val(returnValue.companyName)
                }
            }
        }
    });

    /**
     * ��֤��Ϣ
     */
    validate({
        "code": {
            required: true
        },
        "salvage": {
            required: true
        },
        "limitYears": {
            custom: ['date']
        },
        "amount": {
            required: true
        },
        "result": {
            required: true
        }
    });
});

// ���ݴӱ�Ľ�̬�����ܽ��
function check_all() {
    var rowAmountVa = 0;
    var cmps = $("#receiveTable").yxeditgrid("getCmpByCol", "amount");
    cmps.each(function () {
        rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
    });
    $("#amount").val(rowAmountVa);
    $("#amount_v").val(moneyFormat2(rowAmountVa));
    return false;
}

/*
 * ���ȷ��
 */
function confirmAudit() {
    if (confirm("��ȷ��Ҫ�ύ�����?")) {
        $("#form1").attr("action", "?model=asset_purchase_receive_receive&action=add&actType=audit").submit();
    } else {
        return false;
    }
}

/**
 * ��֤���ս�����
 */
function checkform() {
    if ($("#amount").val() == "") {
        alert("��д�������ս�");
        return false;
    }
    return true;
}
