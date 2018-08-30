$(function () {
    $("#loading").show();

    //列表初始化
    initList();
});

/**
 * 列表初始化
 */
function initList() {
    //参数
    var paramObj = {
        'wcode': 'oa_esm_project_statusreport',
        'pflag': '1',
        'pageSize': $("#pageSize").val()
    };
    var gridObj = $("#statusreportGrid");
    if (gridObj.children().length != 0) {
        //工作日志
        gridObj.yxeditgrid("setParam", paramObj);
        show_page()
    } else {
        gridObj.yxeditgrid({
            url: '?model=engineering_project_statusreport&action=auditedJson',
            param: paramObj,
            isAddAndDel: false,
            type: 'view',
            //列信息
            colModel: [
                {
                    display: 'id',
                    name: 'id',
                    type: 'hidden'
                },
                {
                    display: 'spid',
                    name: 'spid',
                    type: 'hidden'
                },
                {
                    name: 'projectId',
                    display: '项目id',
                    type: 'hidden'
                },
                {
                    name: 'weekNo',
                    display: '周次',
                    process: function (v, row) {
                        return "<a href='javascript:void(0)' onclick='viewStatusreport(" + row.id + ")'>" + v + "</a>";
                    }
                },
                {
                    name: 'createName',
                    display: '提交人',
                    width: 90,
                    type: 'hidden'
                },
                {
                    name: 'handupDate',
                    display: '提交日期',
                    width: 80
                },
                {
                    name: 'projectName',
                    display: '项目名称',
                    width: 130,
                    align: 'left'
                },
                {
                    name: 'projectCode',
                    display: '项目编号',
                    width: 130,
                    align: 'left',
                    process: function (v, row) {
                        return "<a href='javascript:void(0)' onclick='viewProject(" + row.projectId + ")'>" + v + "</a>";
                    }
                },
                {
                    name: 'projectProcess',
                    display: '项目进度',
                    process: function (v) {
                        return v + ' %';
                    }
                },
                {
                    name: 'exgross',
                    display: '毛利率',
                    process: function (v) {
                        if (v == "") {
                            return "暂无";
                        }
                        return v + ' %';
                    },
                    type: 'statictext'
                },
                {
                    name: 'warningNum',
                    display: '告警数量',
                    process: function (v) {
                        if (v == "") {
                            return "暂无";
                        }
                        return v;
                    },
                    type: 'statictext'
                },
                {
                    name: 'score',
                    display: '考核分数'
                },
                {
                    name: 'content',
                    display: '意见',
                    align: 'left'
                },
                {
                    name: 'Endtime',
                    display: '审批时间'
                }
            ],
            event: {
                'reloadData': function (e) {
                    $("#loading").hide();
                }
            }
        });

        //复合表头初始化
        tableHead();
    }
}

/**
 * 刷新列表
 */
function show_page() {
    //如果改变和查询方式，列表
    var gridObj = $("#statusreportGrid");
    gridObj.yxeditgrid('processData');

    //取消全选选中
    $("#all").attr('checked', false);
    $("#loading").show();
}

/**
 * 重写从表表头
 */
function tableHead() {
    var trHTML = '';
    var detailArr = [];
    var mergeArr = [];
    var lengthArr = [];
    //复合表头配置情况
    var detailTheadJson = {
        '周报信息': ['周次', '提交日期'],
        '项目信息': ['项目名称', '项目编号'],
        '项目进展': ['项目进度', '毛利率'],
        '异常情况': ['告警数量'],
        '审批情况': ['考核分数', '意见', '审批时间']
    };
    //循环解析出符合表头数组
    for (k in detailTheadJson) {
        mergeArr.push(k);
        length = 0;
        for (var prop in detailTheadJson[k]) {
            if (detailTheadJson[k].hasOwnProperty(prop))
                length++;
        }
        lengthArr.push(length);
        //明细表头
        for (j in detailTheadJson[k]) {
            if (j * 1 == j) {
                detailArr.push(detailTheadJson[k][j]);
            }
        }
    }

    var trObj = $("#statusreportGrid tr:eq(0)");
    var tdArr = trObj.children();
    var markMergeTitle = '';
    var markMergeLength = 0;
    var markMergeNo = 0;
    tdArr.each(function (i, n) {
        if ($(this).text() == '序号' || $(this).is(":hidden") == true) {
            $(this).attr("rowSpan", 2);
        } else {//非序号处理
            if ($.inArray($(this).text(), detailArr) != -1) {
                if (markMergeLength != 0) {//合并计数
                    markMergeNo++;
                    $(this).remove();
                    markMergeLength--;
                } else {
                    markMergeTitle = mergeArr[markMergeNo];
                    markMergeLength = lengthArr[markMergeNo];
                    $(this).attr('colSpan', markMergeLength).text(markMergeTitle);
                    if (markMergeLength != 1) {
                        markMergeLength--;
                    }
                }
            } else {
                $(this).attr("rowSpan", 2);
            }
        }
    });

    trHTML += '<tr class="main_tr_header">';
    for (m = 0; m < detailArr.length; m++) {
        trHTML += '<th><div class="divChangeLine" style="min-width:60px;">' + detailArr[m] + '</div></th>';
    }
    trHTML += '</tr>';
    trObj.after(trHTML);
}

//查看项目
function viewStatusreport(id) {
    //获取单据Id
    var skey = "";
    $.ajax({
        type: "POST",
        url: "?model=engineering_project_statusreport&action=md5RowAjax",
        data: { "id": id },
        async: false,
        success: function (data) {
            skey = data;
        }
    });
    showModalWin("?model=engineering_project_statusreport&action=toView&id=" + id + '&skey=' + skey, 1, id)
}

//查看项目
function viewProject(id) {
    //获取单据Id
    var skey = "";
    $.ajax({
        type: "POST",
        url: "?model=engineering_project_esmproject&action=md5RowAjax",
        data: { "id": id },
        async: false,
        success: function (data) {
            skey = data;
        }
    });
    showModalWin("?model=engineering_project_esmproject&action=viewTab&id=" + id + '&skey=' + skey, 1, id);
}