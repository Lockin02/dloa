var t;
var listData;
var done = 0;
var year;
var month;
var isInit = false;
var ajaxCache = [];

$(function () {
    // 初始化
    t = $("#t").val();

    // 如果是启用类型的时候才加载项目主体表
    if ($.inArray(t, ["2", "3"]) !== -1) {
        // 启用查询表
        $("#searchTbl").show();

        // 初始化项目主体表
        initProjectList(true);
    }
});

// 项目主体表构建
var initProjectList = function (initThisTime) {
    // 默认值
    initThisTime = initThisTime ? initThisTime : false;
    // 全局变量赋值
    isInit = initThisTime;
    // 如果这次是初始化
    if (!isInit) {
        // 加载loading
        showLoading();
    }

    // 表头处理
    var lockCols = [{
        title: '板块',
        field: 'moduleName',
        width: 100
    }, {
        title: '归属部门id',
        field: 'feeDeptId',
        hidden: true,
        width: 100
    }, {
        title: '执行区域id',
        field: 'officeId',
        hidden: true,
        width: 100
    }, {
        title: '执行区域',
        field: 'officeName',
        width: 100
    }, {
        title: '省份',
        field: 'proName',
        width: 100
    }];

    var t = $("#t").val();
    var colModel;
    switch (t) {
        case "2":
            // 空列
            colModel = [{
                title: '未完成日志',
                field: 'workLogWarning',
                width: 80,
                align: 'right',
                formatter: function (value, row, index) {
                    if (row.officeId == "countRow") {
                        return "<span id='workLogWarning_countRow'></span>";
                    } else {
                        return "<span id='workLogWarning" + index + "'></span>";
                    }
                }
            }, {
                title: '未完成周报',
                field: 'weekLogWarning',
                width: 80,
                align: 'right',
                formatter: function (value, row, index) {
                    if (row.officeId == "countRow") {
                        return "<span id='weekLogWarning_countRow'></span>";
                    } else {
                        return "<span id='weekLogWarning" + index + "'></span>";
                    }
                }
            }, {
                title: '报销费用环比变化率',
                field: 'feeWarning',
                width: 120,
                align: 'right',
                formatter: function (value, row, index) {
                    if (row.officeId == "countRow") {
                        return "<span id='feeWarning_countRow'></span>";
                    } else {
                        return "<span id='feeWarning" + index + "'></span>";
                    }
                }
            }];
            break;
        case "3":
            // 空列
            colModel = [{
                title: 'CPI告警',
                field: 'CPIWarning',
                width: 80,
                align: 'right',
                formatter: function (value, row, index) {
                    if (row.officeId == "countRow") {
                        return "<span id='CPIWarning_countRow'></span>";
                    } else {
                        return "<span id='CPIWarning" + index + "'></span>";
                    }
                }
            }, {
                title: 'SPI告警',
                field: 'SPIWarning',
                width: 80,
                align: 'right',
                formatter: function (value, row, index) {
                    if (row.officeId == "countRow") {
                        return "<span id='SPIWarning_countRow'></span>";
                    } else {
                        return "<span id='SPIWarning" + index + "'></span>";
                    }
                }
            }, {
                title: '决算超支',
                field: 'feeOutOfLimit',
                width: 80,
                align: 'right',
                formatter: function (value, row, index) {
                    if (row.officeId == "countRow") {
                        return "<span id='feeOutOfLimit_countRow'></span>";
                    } else {
                        return "<span id='feeOutOfLimit" + index + "'></span>";
                    }
                }
            }, {
                title: '负毛利',
                field: 'negativeExgross',
                width: 80,
                align: 'right',
                formatter: function (value, row, index) {
                    if (row.officeId == "countRow") {
                        return "<span id='negativeExgross_countRow'></span>";
                    } else {
                        return "<span id='negativeExgross" + index + "'></span>";
                    }
                }
            }, {
                title: '低毛利',
                field: 'lowExgross',
                width: 80,
                align: 'right',
                formatter: function (value, row, index) {
                    if (row.officeId == "countRow") {
                        return "<span id='lowExgross_countRow'></span>";
                    } else {
                        return "<span id='lowExgross" + index + "'></span>";
                    }
                }
            }];
            break;
    }

    var thisHeight = document.documentElement.clientHeight - 40;

    $('#grid').datagrid({
        url: '?model=engineering_officeinfo_range&action=showRange&needCountRow=1&t=' + t,
        onLoadSuccess: function (data) {
            listData = data;
            done = 0;
            // 非初始化的时候加载数据
            if (!isInit) {
                loadData();
            }
        },
        loadMsg: '加载中，请稍后...',
        emptyMsg: '没有查询到相关数据...',
        height: thisHeight,
        columns: [colModel],
        frozenColumns: [lockCols]
    });
};

// 业务数据加载
function loadData() {
    // 查询条件
    year = $("#year").val();
    month = $("#month").val();
    var i, p;
    var officeCache = [];
    ajaxCache = []; // 清空

    // 如果是录入预警，则开始加载日志、周报、报销环比数据
    if (t == 2) {
        for (i = 0; i < listData.total; i++) {
            // 区域预警
            if ($.inArray(listData.rows[i].officeId, officeCache) == -1) {
                // 放入缓存
                officeCache.push(listData.rows[i].officeId);

                // 日志预警获取
                ajaxCache.push($.ajax({
                    url: "?model=engineering_worklog_esmworklog&action=warnCount",
                    data: {
                        year: year,
                        month: month,
                        deptId: listData.rows[i].feeDeptId,
                        k: i
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function (rst2) {
                        // 基础url
                        var url = "?model=engineering_worklog_esmworklog&action=toWarnSummary&t=4&year=" + year + "&month="
                            + month + "&feeDeptId=" + rst2.deptId;

                        // 周报预警
                        easyFeeUrl(rst2.k, 'workLogWarning', rst2.warningNum, url);
                    }
                }));
            } else {
                $("#workLogWarning" + i).append('--');
            }

            if (!listData.rows[i].ext) {
                // 参数
                p = {
                    officeId: listData.rows[i].officeId,
                    province: listData.rows[i].proName,
                    statusArr: 'GCXMZT02,GCXMZT04,GCXMZT00',
                    k: i
                };
                // 获取区域相关项目
                ajaxCache.push($.ajax({
                    url: "?model=engineering_project_esmproject&action=getShowProjectIds",
                    data: p,
                    type: 'post',
                    dataType: 'json',
                    success: function (rst) {
                        if (rst.ids == "") {
                            $("#weekLogWarning" + rst.k).append('--');
                            $("#feeWarning" + rst.k).append('-- %');
                        } else {
                            // 公用查询参数
                            var pi = {
                                year: year,
                                month: month,
                                projectNos: rst.codes,
                                projectIds: rst.ids,
                                k: rst.k
                            };
                            // 周报预警获取
                            ajaxCache.push($.ajax({
                                url: "?model=engineering_project_statusreport&action=warnCount",
                                data: pi,
                                type: 'post',
                                dataType: 'json',
                                success: function (rst2) {
                                    // 基础url
                                    var url = "?model=engineering_project_statusreport&action=toWarnView&t=4&year=" + year + "&month="
                                        + month + "&ids=" + rst2.projectIds + "&projectCodes=" + rst2.projectNos;

                                    // 周报预警
                                    easyFeeUrl(rst2.k, 'weekLogWarning', rst2.warningNum, url);
                                }
                            }));

                            // 费用预警获取
                            ajaxCache.push($.ajax({
                                url: "?model=finance_expense_expense&action=getWarning",
                                data: pi,
                                type: 'post',
                                dataType: 'json',
                                success: function (rst2) {
                                    // 基础url
                                    var url = "?model=engineering_project_esmproject&action=showDetail&t=4&year=" + year + "&month="
                                        + month + "&ids=" + rst2.projectIds + "&projectCodes=" + rst2.projectNos;

                                    // 变化率
                                    easyFeeUrl(rst2.k, 'feeWarning', rst2.changeRate, url, ' %', true);
                                }
                            }));
                        }
                        // 检测完成
                        checkDone();
                    }
                }));
            } else {
                // 变化率
                $("#feeWarning" + i).append('--');
                $("#weekLogWarning" + i).append('--');
                // 检测完成
                checkDone();
            }
        }
    } else {
        // 逾期未关闭
        for (i = 0; i < listData.total; i++) {
            // 参数
            p = {
                officeId: listData.rows[i].officeId,
                province: listData.rows[i].proName,
                k: i
            };
            // 日志预警获取
            ajaxCache.push($.ajax({
                url: "?model=engineering_project_esmproject&action=showCount",
                data: p,
                type: 'post',
                dataType: 'json',
                success: function (rst) {
                    // 基础url
                    var url = "?model=engineering_project_esmproject&action=showDetail&t=";

                    // 超支数量
                    easyUrl(rst.k, 'feeOutOfLimit', rst.feeOutOfLimit, url, 1);

                    // 负毛利
                    easyUrl(rst.k, 'negativeExgross', rst.negativeExgross, url, 2);

                    // 低毛利
                    easyUrl(rst.k, 'lowExgross', rst.lowExgross, url, 3);

                    // CPI预警
                    easyUrl(rst.k, 'CPIWarning', rst.CPIWarning, url, 9);

                    // SPI预警
                    easyUrl(rst.k, 'SPIWarning', rst.SPIWarning, url, 10);

                    // 检测完成
                    checkDone();
                }
            }));
        }
    }
}

// 检测完成
function checkDone() {
    done++;
    if (done == listData.total) {
        hideLoading();
    }
}

// 中断ajax请求
function stopLoadData() {
    for (var i = 0; i < ajaxCache.length; i++) {
        ajaxCache[i].abort();
    }
    hideLoading();
}

/**
 *
 * @param k
 * @param f
 * @param v
 * @param url
 * @param a
 */
function easyFeeUrl(k, f, v, url, a, notNeed) {
    a = a == undefined ? "" : a;

    if (f == 'feeWarning') {
        var redStyle = v > 0 ? "style='color:red'" : "";
    }

    // 未关闭数量
    var vi = v == "0" ?
        v :
        "<a href='javascript:void(0)' " + redStyle + " onclick='window.open(\"" + url + "\");'>" + v + a + "</a>";
    $("#" + f + k).append(vi);

    if (!notNeed) {
        var countObj = $("#" + f + "_countRow");
        countObj.text(accAdd(countObj.text(), v));
    }
}

/**
 * 封装一下执行预警的数据回填方法
 */
function easyUrl(k, f, v, url, t) {
    // 类型处理
    url = url + t + "&officeId=" + listData.rows[k].officeId + "&province=" + listData.rows[k].proName;

    // 未关闭数量
    var vi = v == "0" ?
        v :
        "<a href='javascript:void(0)' onclick='window.open(\"" + url + "\");'>" + v + "</a>";
    $("#" + f + k).append(vi);

    if (v * 1 == v) {
        var countObj = $("#" + f + "_countRow");
        countObj.text(countObj.text() * 1 + v);
    }
}

//显示loading
function showLoading() {
    $("#loading").show();
    $("#search").attr('disabled', true);
}

// 隐藏loading
function hideLoading() {
    $("#loading").hide();
    $("#search").attr('disabled', false);
}