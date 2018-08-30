$(function() {
    // 初始化项目主体表
    initProjectList();
});

var listData;

// 项目主体表构建
var initProjectList = function() {
    var t = $("#t").val();
    var arr = ["101","102"];
    var inArrayChk = jQuery.inArray( t, arr );

    // 基本列表，根据类型不同加载后面的列
    var defaultModel = [{
        display: '区域',
        name: (inArrayChk >= 0)? 'areaName' : 'officeName',
        width: 100,
        align: 'left'
    }, {
        display: (inArrayChk >= 0)? '合同名称' : '项目名称',
        name: (inArrayChk >= 0)? 'contractName' : 'projectName',
        width: 140,
        align: 'left'
    }, {
        display: (inArrayChk >= 0)? '合同编号' : '项目编号',
        name: (inArrayChk >= 0)? 'contractCode' : 'projectCode',
        width: 140,
        align: 'left',
        process: function (v, row) {
            var projectCodeStr = ($("#t").val() == "100" || $("#t").val() == "99")?
                "<a href='javascript:void(0)' " +
                "onclick='window.open(\"?model=contract_conproject_conproject&action=viewTab&id=" +
                row.proId + '&skey=' + row.skey_ + "\")'>" + v + "</a>"
                : "<a href='javascript:void(0)' " +
                "onclick='window.open(\"?model=engineering_project_esmproject&action=viewTab&id=" +
                row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
            if(inArrayChk >= 0){
                projectCodeStr = "<a href='javascript:void(0)' " +
                    "onclick='window.open(\"?model=contract_contract_contract&action=toViewTab&id=" +
                    row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
            }
            return projectCodeStr;
        }
    }, {
        display: (inArrayChk >= 0)? '合同负责人' : '项目经理',
        name: (inArrayChk >= 0)? 'prinvipalName' : 'managerName',
        width: 100,
        align: 'left'
    }, {
        display: '状态',
        name: 'statusName',
        width: 70
    }];

    var t = $("#t").val();
    var officeId = $("#officeId").val();
    var province = $("#province").val();
    var statusArr = $("#statusArr").val();
    // 参数传入
    var p = {
        officeId: officeId,
        province: province,
        ids: $("#ids").val(),
        projectCodes: $("#projectCodes").val(),
        t: t
    };

    switch (t) {
        case "0" :
        case "5" :
        case "6" :
        case "7" :
        case "8" :
            defaultModel.push({
                display: '实际开始',
                name: 'actBeginDate',
                width: 100
            }, {
                display: '实际结束',
                name: 'actEndDate',
                width: 100
            });
            break;
        case "1" :
            defaultModel.push({
                display: '预算',
                name: 'budgetAll',
                width: 100,
                align: 'right',
                process: function (v) {
                    return moneyFormat2(v);
                }
            }, {
                display: '决算',
                name: 'feeAll',
                width: 100,
                align: 'right',
                process: function (v) {
                    return moneyFormat2(v);
                }
            }, {
                display: '超支',
                name: 'feeAll',
                width: 100,
                align: 'right',
                process: function(v, row) {
                    return moneyFormat2(accSub(v, row.budgetAll, 2));
                }
            });
            break;
        case "2" :
        case "3" :
            defaultModel.push({
                display: '预计毛利率',
                name: 'budgetExgross',
                width: 100,
                process: function(v) {
                    return v + " %";
                }
            }, {
                display: '当前毛利率',
                name: 'exgross',
                width: 100,
                process: function(v) {
                    if (v < 0) {
                        return "<span class='red'>" + v + " %</span>";
                    } else
                        return v + " %";
                }
            }, {
                display: '当前毛利',
                name: 'grossProfit',
                width: 100,
                process: function(v) {
                    if (v < 0) {
                        return "<span class='red'>" + moneyFormat2(v) + "</span>";
                    } else
                        return moneyFormat2(v);
                }
            });
            break;
        case "4" :
            defaultModel.push({
                display: '上月报销',
                name: 'prevPeriodFee',
                width: 100,
                align: 'right'
            }, {
                display: '本月报销',
                name: 'thisPeriodFee',
                width: 100,
                align: 'right'
            }, {
                display: '报销费用环比变化率',
                name: 'changeRate',
                width: 120
            });
            break;
        case "9" :
            defaultModel.push({
                display: '预算',
                name: 'budgetAll',
                width: 80,
                align: 'right',
                process: function(v) {
                    return moneyFormat2(v);
                }
            }, {
                display: '决算',
                name: 'feeAll',
                width: 80,
                align: 'right',
                process: function(v) {
                    return moneyFormat2(v);
                }
            }, {
                display: 'CPI',
                name: 'CPI',
                width: 80
            });
            break;
        case "10" :
            defaultModel.push({
                display: '计划进展',
                name: 'planProcess',
                width: 80,
                align: 'right',
                process: function(v) {
                    return v + " %";
                }
            }, {
                display: '实际进展',
                name: 'projectProcess',
                width: 80,
                align: 'right',
                process: function(v) {
                    return v + " %";
                }
            }, {
                display: 'SPI',
                name: 'SPI',
                width: 80
            });
            break;
        default :
    }

    // 补充长度
    defaultModel.push({
        display: '',
        name: ''
    });

    if (p == undefined || p.length == 0) {
        alert('列表参数错误');
    } else {
        $("#grid").yxeditgrid({
            url: '?model=engineering_project_esmproject&action=showDetailJson',
            param: p,
            type: 'view',
            event: {
                reloadData: function(e, g, data) {
                    if (data.length == 0) {
                        alert("没有查询到相关项目");
                        hideLoading();
                    } else {
                        listData = data;
                        // 开始加载业务数据
                        loadData();
                    }
                }
            },
            colModel: defaultModel
        });
    }
};

/**
 * 业务数据加载
 */
function loadData() {
    var t = $("#t").val();
    var year = $("#year").val();
    var month = $("#month").val();

    switch (t) {
        case "4" :
            for (var i = 0; i < listData.length; i++) {
                // 参数
                var p = {
                    year: year,
                    month: month,
                    projectNos: listData[i].projectCode,
                    projectIds: listData[i].id,
                    k: i
                };
                // 费用预警获取
                $.ajax({
                    url: "?model=finance_expense_expense&action=getWarning",
                    data: p,
                    type: 'post',
                    dataType: 'json',
                    success: function(rst) {
                        $("#grid_cmp_prevPeriodFee" + rst.k).append(moneyFormat2(rst.prevPeriodFee));
                        $("#grid_cmp_thisPeriodFee" + rst.k).append(moneyFormat2(rst.thisPeriodFee));
                        $("#grid_cmp_changeRate" + rst.k).append(rst.changeRate + ' %');
                    }
                });
            }
            break;
        default :
    }
    hideLoading();
}

function hideLoading(){
    $("#loadingWrap").hide();
}