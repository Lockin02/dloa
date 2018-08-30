$(document).ready(function () {
    // 初始化外包类型
    var outType = $(".otypename:checked").val();
    changeType(outType);

    // 初始化项目的一些信息
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

    // 表单验证
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
        title: '租借人员',
        colModel: [{
            display: '设备厂家/制式',
            name: 'content',
            tclass: 'txtshort',
            validation: {
                required: true
            }
        }, {
            display: '级别',
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
            display: '数量',
            name: 'peopleCount',
            tclass: 'txtshort person_sl',
            event: {
                blur: function () {
                    var re = /^(?:[1-9][0-9]*|0)(?:\.[0-9]+)?$/;
                    if (!re.test(this.value)) { //判断是否为数字
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
            display: '预计开始日期',
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
            display: '预计结束日期',
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
            display: '使用周期',
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
            display: '外包最高限价<br/>(元/月/人）',
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
            display: '价格描述',
            name: 'priceContent',
            tclass: 'txt',
            align: 'left',
            validation: {
                required: true
            }
        }, {
            display: '人员技能及工作内容描述',
            name: 'skill',
            tclass: 'txt',
            align: 'left',
            validation: {
                required: true
            }
        }]
    });
}

//直接提交
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