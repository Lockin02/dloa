$(document).ready(function () {
    // ��ʼ���������
    var outType = $(".otypename:checked").val();
    changeType(outType);

    //select_project
    $("#projectCode").yxcombogrid_esmproject({
        isDown: false,
        hiddenId: 'projectId',
        nameCol: 'projectCode',
        height: 250,
        isFocusoutCheck: true,
        gridOptions: {
            isTitle: true,
            showcheckbox: false,
            autoload: false,
            param: {'statusArr': 'GCXMZT01,GCXMZT02'},
            event: {
                'row_dblclick': function (e, row, data) {
                    $("#projecttName").val(data.projectName);
                    $("#projectCharge").val(data.managerName);
                    $("#projectClientType").val(data.customerTypeName);
                    $("#projectAddress").val(data.place);
                    $("#projectStartTime").val(data.planBeginDate);
                    $("#projectEndTime").val(data.planEndDate);
                    $("#projectExpectedDuration").val(data.expectedDuration);
                    $("#period").val(data.cycleName);
                    $("#projectAttribute").val(data.attributeName);
                    $("#projectCategory").val(data.categoryName);
                    $("#projectStatus").val(data.statusName);

                    // ��ȡ��Ŀ��ϸ��Ϣ
                    $.ajax({
                        url: "?model=engineering_project_esmproject&action=ajaxGetProject",
                        data: {id: data.id},
                        dataType: 'json',
                        type: 'POST',
                        success: function(msg) {
                            if (msg.id) {
                                setMoney("projectMoneyWithTax", msg.projectMoneyWithTax);
                                setMoney("estimates", msg.estimates);
                                setMoney("budgetAll", msg.budgetAll);
                            } else {
                                setMoney("projectMoneyWithTax", 0);
                                setMoney("estimates", 0);
                                setMoney("budgetAll", 0);
                            }
                        }
                    });
                }
            }
        },
        event: {
            'clear': function () {
                $("#projecttName").val("");
                $("#projectCharge").val("");
                $("#projectClientType").val("");
                $("#projectAddress").val("");
                $("#projectStartTime").val("");
                $("#projectEndTime").val("");
                $("#projectExpectedDuration").val("");
                $("#period").val("");
                $("#projectAttribute").val("");
                $("#projectCategory").val("");
                $("#projectStatus").val("");
                setMoney("projectMoneyWithTax", "");
                setMoney("estimates", "");
                setMoney("budgetAll", "");
            }
        }
    });

    // �������֤
    validate({
        "whyDescription": {
            required: true
        },
        "projectCode": {
            required: true
        }
    });
});

// ��ʼ��
function initPerson() {
    $("#wapdiv").yxeditgrid('remove').yxeditgrid({
        objName: 'apply[person]',
        type: '',
        tableClass: 'form_in_table',
        width: 1080,
        isAddOneRow: true,
        event: {
            'removeRow': function () {
                //changeSl();
                //changeCB();
                //changeWB();
            }
        },
        colModel: [{
            display: '�豸����/��ʽ',
            name: 'content',
            tclass: 'txtshort',
            validation: {
                required: true
            }
        }, {
            display: '����',
            name: 'riskCode',
            tclass: 'txtshort',
            readonly: true,
            process: function ($input, rowData) {
                var rowNum = $input.data("rowNum");
                var g = $input.data("grid");
                $input.yxcombogrid_eperson({
                    hiddenId: 'wapdiv_cmp_budgetId' + rowNum,
                    width: 600,
                    height: 300,
                    gridOptions: {
                        showcheckbox: false,
                        event: {
                            row_dblclick: (function (rowNum) {
                                return function (e, row, rowData) {
                                    g.getCmpByRowAndCol(rowNum, 'inBudgetPrice').val(rowData.price);
                                    //countPerson(rowNum);
                                    //changeCB();
                                    //changeWB();
                                }
                            })(rowNum)
                        }
                    }
                });
            },
            validation: {
                required: true
            }
        }, {
            display: '����',
            name: 'peopleCount',
            tclass: 'txtshort person_sl',
            event: {
                blur: function () {
                    var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
                    if (!re.test(this.value)) { //�ж��Ƿ�Ϊ����
                        if (isNaN(this.value)) {
                            this.value = '';
                        } else {
                        }
                    }
                    //changeSl();
                    //countPerson($(this).data("rowNum"));
                    //changeCB();
                    //countPersonOut($(this).data("rowNum"));
                    //changeWB();
                }
            },
            validation: {
                required: true
            }
        }, {
            display: 'Ԥ�ƿ�ʼ����',
            name: 'startTime',
            tclass: 'txtshort',
            readonly: true,
            type: 'date',
            event: {
                blur: function () {
                    countDate('startTime', $(this).data("rowNum"));
                    countPerson($(this).data("rowNum"));
                    changeCB();
                    countPersonOut($(this).data("rowNum"));
                    changeWB();
                }
            },
            validation: {
                required: true
            }
        }, {
            display: 'Ԥ�ƽ�������',
            name: 'endTime',
            tclass: 'txtshort',
            readonly: true,
            type: 'date',
            event: {
                blur: function () {
                    countDate('endTime', $(this).data("rowNum"));
                    countPerson($(this).data("rowNum"));
                    changeCB();
                    countPersonOut($(this).data("rowNum"));
                    changeWB();
                }
            },
            validation: {
                required: true
            }
        }, {
            display: 'ʹ������',
            name: 'totalDay',
            tclass: 'txtshort',
            event: {
                blur: function () {
                    countPerson($(this).data("rowNum"));
                    changeCB();
                    countPersonOut($(this).data("rowNum"));
                    changeWB();
                }
            },
            validation: {
                required: true
            }
        }, {
            display: '�������޼�<br/>(Ԫ/��/�ˣ�',
            name: 'outBudgetPrice',
            tclass: 'txtshort',
            event: {
                blur: function () {
                    countPersonOut($(this).data("rowNum"));
                    changeWB();

                }
            },
            validation: {
                required: true
            }
        }, {
            display: '�۸�����',
            name: 'priceContent',
            tclass: 'txt',
            align: 'left',
            validation: {
                required: true
            }
        }, {
            display: '��Ա���ܼ�������������',
            name: 'skill',
            tclass: 'txt',
            align: 'left',
            validation: {
                required: true
            }
        }]
    });
}


//ֱ���ύ
function toSubmit() {
    document.getElementById('form1').action = "?model=outsourcing_outsourcing_apply&action=add&actType=staff";
}