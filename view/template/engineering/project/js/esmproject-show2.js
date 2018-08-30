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
    if ($.inArray(t, ["2", "3", "4"]) !== -1) {
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

    // 空列
    var colModel = [{
        title: '筹备',
        field: 'prepare',
        width: 80,
        align: 'right',
        formatter: function (value, row, index) {
            if (row.officeId == "countRow") {
                return "<span id='prepare_countRow'></span>";
            } else {
                return "<span id='prepare" + index + "'></span>";
            }
        }
    }, {
        title: '在建',
        field: 'building',
        width: 80,
        align: 'right',
        formatter: function (value, row, index) {
            if (row.officeId == "countRow") {
                return "<span id='building_countRow'></span>";
            } else {
                return "<span id='building" + index + "'></span>";
            }
        }
    }, {
        title: '完工',
        field: 'completed',
        width: 80,
        align: 'right',
        formatter: function (value, row, index) {
            if (row.officeId == "countRow") {
                return "<span id='completed_countRow'></span>";
            } else {
                return "<span id='completed" + index + "'></span>";
            }
        }
    }, {
        title: '暂停',
        field: 'stop',
        width: 80,
        align: 'right',
        formatter: function (value, row, index) {
            if (row.officeId == "countRow") {
                return "<span id='stop_countRow'></span>";
            } else {
                return "<span id='stop" + index + "'></span>";
            }
        }
    }, {
        title: '逾期未关闭',
        field: 'unClose',
        width: 80,
        align: 'right',
        formatter: function (value, row, index) {
            if (row.officeId == "countRow") {
                return "<span id='unClose_countRow'></span>";
            } else {
                return "<span id='unClose" + index + "'></span>";
            }
        }
    }, {
        title: '关闭',
        field: 'closed',
        width: 80,
        align: 'right',
        formatter: function (value, row, index) {
            if (row.officeId == "countRow") {
                return "<span id='closed_countRow'></span>";
            } else {
                return "<span id='closed" + index + "'></span>";
            }
        }
    }, {
        title: '数量合计',
        field: 'count',
        width: 80,
        align: 'right',
        formatter: function (value, row, index) {
            if (row.officeId == "countRow") {
                return "<span id='count_countRow'></span>";
            } else {
                return "<span id='count" + index + "'></span>";
            }
        }
    }];

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
    // 查询时，重置ajax缓存空间
    ajaxCache = []; // 清空
    var i, p;
    // 逾期未关闭
    for (i = 0; i < listData.total; i++) {
        if (listData.rows[i].officeId != "countRow") {
            // 参数
            p = {
                officeId: listData.rows[i].officeId,
                province: listData.rows[i].proName,
                k: i,
                t: $("#t").val()
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

                    // 筹备项目
                    easyUrl(rst.k, 'prepare', rst.prepare, url, 5);

                    // 在建项目
                    easyUrl(rst.k, 'building', rst.building, url, 6);

                    // 完工
                    easyUrl(rst.k, 'completed', rst.completed, url, 7);

                    // 关闭
                    easyUrl(rst.k, 'closed', rst.closed, url, 8);

                    // 未关闭数量
                    easyUrl(rst.k, 'unClose', rst.unClose, url, 0);

                    // 暂停数量
                    easyUrl(rst.k, 'stop', rst.stop, url, 12);

                    // 合计数量
                    easyUrl(rst.k, 'count', rst.count, url, 11);

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
    if (done == listData.total - 1) {
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

    var countObj = $("#" + f + "_countRow");
    countObj.text(countObj.text() * 1 + v);
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