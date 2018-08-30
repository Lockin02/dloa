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
        'ExaStatusArr': '部门审批',
        'wcode': 'oa_esm_project_statusreport',
        'pflag': '0',
        'pageSize': $("#pageSize").val()
    };
    var gridObj = $("#statusreportGrid");
    if (gridObj.children().length != 0) {
        //工作日志
        gridObj.yxeditgrid("setParam", paramObj);
        show_page()
    } else {
        gridObj.yxeditgrid({
            url: '?model=engineering_project_statusreport&action=auditJson',
            param: paramObj,
            isAddAndDel: false,
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
                    },
                    type: 'statictext'
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
                    width: 80,
                    type: 'statictext'
                },
                {
                    name: 'projectName',
                    display: '项目名称',
                    width: 150,
                    align: 'left',
                    type: 'statictext'
                },
                {
                    name: 'projectCode',
                    display: '项目编号',
                    width: 150,
                    align: 'left',
                    process: function (v, row) {
                        return "<a href='javascript:void(0)' onclick='viewProject(" + row.projectId + ")'>" + v + "</a>";
                    },
                    type: 'statictext'
                },
                {
                    name: 'projectProcess',
                    display: '项目进度',
                    process: function (v) {
                        return v + ' %';
                    },
                    type: 'statictext'
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
                    display: '考核分数',
                    tclass: 'txtshort',
                    event: {
                        blur: function (e) {
                            if (isNaN($(this).val()) || ($(this).val() * 1 > 10 || $(this).val() * 1 < 0)) {
                                alert('请输入 0 到 10 以内的数字');
                                $(this).val('');
                            }
                            if ($(this).val() != "") {
                                var rowNum = $(this).data("rowNum");
                                var g = $(this).data("grid");
                                var id = g.getCmpByRowAndCol(rowNum, 'id').val();
                                //考核方法
                                setScore(id, $(this).val());
                            }
                        }
                    }
                },
                {
                    name: 'confirm',
                    display: "全选",
                    process: function (v, row, $tr, g, $input, rowNum) {
                        return "<input type='radio' name='okno" + row.id + "' id='ok" + row.id + "' value='" + row.id + "' spid='" + row.spid + "' rowNum='" + rowNum + "'/>";
                    },
                    type: 'statictext'
                },
                {
                    name: 'back',
                    display: "打回",
                    process: function (v, row, $tr, g, $input, rowNum) {
                        return "<input type='radio' name='okno" + row.id + "' id='no" + row.id + "' value='" + row.id + "' spid='" + row.spid + "' rowNum='" + rowNum + "'/>";
                    },
                    type: 'statictext'
                },
                {
                    name: 'content',
                    display: '意见',
                    process: function (v, row) {
                        return "<input class='txt' id='content" + row.id + "'/>";
                    },
                    type: 'statictext'
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

//设置考核分数
function setScore(id, score) {
    $.ajax({
        type: "POST",
        url: "?model=engineering_project_statusreport&action=updateScore",
        data: {'id': id, 'score': score},
        success: function (result) {

        }
    });
}

/**
 * 批量审核
 */
function auditBatch() {
    var checkArr = $("input:radio[id^='ok']:checked");
    var checkBackArr = $("input:radio[id^='no']:checked");
    if (checkArr.length == 0 && checkBackArr.length == 0) {
        alert('没有选中行');
        return false;
    }
    //第一次循环验证考核分数
    var canAudit = true;
    checkArr.each(function () {
        var scoreObj = $("#statusreportGrid_cmp_score" + $(this).attr('rowNum'));
        if ($(this).attr('checked') == true && scoreObj.val() == "") {
            alert('含有考核分数为空的审核项!');
            scoreObj.focus();
            canAudit = false;
            return false;
        }
    });

    if (canAudit == false) {
        return false;
    }

    //最后确认
    if (!confirm('确认审核吗')) {
        return false;
    }

    //第二次循环做审核操作
    checkArr.each(function () {
        var spid = $(this).attr('spid');
        var temp = $(this).attr('id');
        temp = temp.substring(2, temp.length);
        var content = $("#content" + temp).val();
//			alert(spid + " - " + content);
        //调用审核方法
        ajaxAudit(spid, content, 'ok');
    });

    checkBackArr.each(function () {
        var spid = $(this).attr('spid');
        var temp = $(this).attr('id');
        temp = temp.substring(2, temp.length);
        var content = $("#content" + temp).val();
//		alert(spid + " - " + content);
        //调用审核方法
        ajaxAudit(spid, content, 'no');
    });

    //刷新列表
    show_page();
}

//ajax审批 - 调用部分
function ajaxAudit(spid, content, result) {
    var rsVal = '审批完成';
    $.ajax({
        type: "POST",
        url: "?model=common_workflow_workflow&action=ajaxAudit",
        data: { "spid": spid, "result": result, "content": content, "isSend": 1, "isSendNext": 1},
        async: false,
        success: function (data) {
            if (data != "1") {
                rsVal = data;
            }
        }
    });
    return rsVal;
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
        '审批情况': ['考核分数', "全选", '打回', '意见']
    };
    //循环解析出符合表头数组
    var length = 0;
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
        if (detailArr[m] == '全选') {
            trHTML += '<th><div class="divChangeLine" style="min-width:60px;"><a href="javascript:void(0);" onclick="checkAll();">确认</a></div></th>';
        }
        else if (detailArr[m] == '打回') {
            trHTML += '<th><div class="divChangeLine" style="min-width:60px;"><a href="javascript:void(0);" onclick="checkAllBack();">打回</a></div></th>';
        }
        else {
            trHTML += '<th><div class="divChangeLine" style="min-width:60px;">' + detailArr[m] + '</div></th>';
        }
    }
    trHTML += '</tr>';
    trObj.after(trHTML);
}

//全选
function checkAll() {
    $("input:radio[id^='ok']").attr('checked', true);
}
function checkAllBack() {
    $("input:radio[id^='no']").attr('checked', true);
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