$(document).ready(function () {
    // ��ʼ���������
    var outType = $(".otypename:checked").val();
    changeType(outType);

    // ��ʼ����Ŀ��һЩ��Ϣ
    $.ajax({
        url: "?model=engineering_project_esmproject&action=ajaxGetProject",
        data: {id: $("#projectId").val()},
        type: 'POST',
        dataType: 'json',
        success: function(msg) {
            if (msg.id) {
                $("#projectExpectedDuration").val(msg.expectedDuration);
                $("#projectAttribute").val(msg.attributeName);
                $("#projectCategory").val(msg.categoryName);
                $("#projectStatus").val(msg.statusName);
                setMoney("projectMoneyWithTax", msg.projectMoneyWithTax);
                setMoney("estimates", msg.estimates);
                setMoney("budgetAll", msg.budgetAll);
            }
        }
    });

    // ����֤
    validate({
        whyDescription: {
            required: true
        }
    });
});

function initPerson() {
    $("#wapdiv").yxeditgrid({
        objName: 'apply[person]',
        url: '?model=outsourcing_outsourcing_person&action=listJson',
        type: '',
        tableClass: 'form_in_table',
        width: 1080,
        param: {"applyId": $("#aid").val()},
        title: '�����Ա',
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
                                    countPerson(rowNum);
                                    changeCB();
                                    changeWB();
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
                    changeSl();
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
	$.ajax({
		url: "?model=outsourcing_outsourcing_apply&action=isExemptReview",
		type: "POST",
		data:  $('#form1').serialize(),
		success: function(data) {
			if(data == "1") {
			    document.getElementById('form1').action = "?model=outsourcing_outsourcing_apply&action=exemptReviewByEdit";
			    document.getElementById('form1').submit();
			}else {
			    document.getElementById('form1').action = "?model=outsourcing_outsourcing_apply&action=edit&actType=staff";
			    document.getElementById('form1').submit();
			}
		}
	});
}