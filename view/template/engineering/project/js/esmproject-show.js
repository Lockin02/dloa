var t;
var listData;
var done = 0;
var year;
var month;
var isInit = false;
var ajaxCache = [];

$(function () {
    // ��ʼ��
    t = $("#t").val();

    // ������������͵�ʱ��ż�����Ŀ�����
    if ($.inArray(t, ["2", "3"]) !== -1) {
        // ���ò�ѯ��
        $("#searchTbl").show();

        // ��ʼ����Ŀ�����
        initProjectList(true);
    }
});

// ��Ŀ�������
var initProjectList = function (initThisTime) {
    // Ĭ��ֵ
    initThisTime = initThisTime ? initThisTime : false;
    // ȫ�ֱ�����ֵ
    isInit = initThisTime;
    // �������ǳ�ʼ��
    if (!isInit) {
        // ����loading
        showLoading();
    }

    // ��ͷ����
    var lockCols = [{
        title: '���',
        field: 'moduleName',
        width: 100
    }, {
        title: '��������id',
        field: 'feeDeptId',
        hidden: true,
        width: 100
    }, {
        title: 'ִ������id',
        field: 'officeId',
        hidden: true,
        width: 100
    }, {
        title: 'ִ������',
        field: 'officeName',
        width: 100
    }, {
        title: 'ʡ��',
        field: 'proName',
        width: 100
    }];

    var t = $("#t").val();
    var colModel;
    switch (t) {
        case "2":
            // ����
            colModel = [{
                title: 'δ�����־',
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
                title: 'δ����ܱ�',
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
                title: '�������û��ȱ仯��',
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
            // ����
            colModel = [{
                title: 'CPI�澯',
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
                title: 'SPI�澯',
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
                title: '���㳬֧',
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
                title: '��ë��',
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
                title: '��ë��',
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
            // �ǳ�ʼ����ʱ���������
            if (!isInit) {
                loadData();
            }
        },
        loadMsg: '�����У����Ժ�...',
        emptyMsg: 'û�в�ѯ���������...',
        height: thisHeight,
        columns: [colModel],
        frozenColumns: [lockCols]
    });
};

// ҵ�����ݼ���
function loadData() {
    // ��ѯ����
    year = $("#year").val();
    month = $("#month").val();
    var i, p;
    var officeCache = [];
    ajaxCache = []; // ���

    // �����¼��Ԥ������ʼ������־���ܱ���������������
    if (t == 2) {
        for (i = 0; i < listData.total; i++) {
            // ����Ԥ��
            if ($.inArray(listData.rows[i].officeId, officeCache) == -1) {
                // ���뻺��
                officeCache.push(listData.rows[i].officeId);

                // ��־Ԥ����ȡ
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
                        // ����url
                        var url = "?model=engineering_worklog_esmworklog&action=toWarnSummary&t=4&year=" + year + "&month="
                            + month + "&feeDeptId=" + rst2.deptId;

                        // �ܱ�Ԥ��
                        easyFeeUrl(rst2.k, 'workLogWarning', rst2.warningNum, url);
                    }
                }));
            } else {
                $("#workLogWarning" + i).append('--');
            }

            if (!listData.rows[i].ext) {
                // ����
                p = {
                    officeId: listData.rows[i].officeId,
                    province: listData.rows[i].proName,
                    statusArr: 'GCXMZT02,GCXMZT04,GCXMZT00',
                    k: i
                };
                // ��ȡ���������Ŀ
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
                            // ���ò�ѯ����
                            var pi = {
                                year: year,
                                month: month,
                                projectNos: rst.codes,
                                projectIds: rst.ids,
                                k: rst.k
                            };
                            // �ܱ�Ԥ����ȡ
                            ajaxCache.push($.ajax({
                                url: "?model=engineering_project_statusreport&action=warnCount",
                                data: pi,
                                type: 'post',
                                dataType: 'json',
                                success: function (rst2) {
                                    // ����url
                                    var url = "?model=engineering_project_statusreport&action=toWarnView&t=4&year=" + year + "&month="
                                        + month + "&ids=" + rst2.projectIds + "&projectCodes=" + rst2.projectNos;

                                    // �ܱ�Ԥ��
                                    easyFeeUrl(rst2.k, 'weekLogWarning', rst2.warningNum, url);
                                }
                            }));

                            // ����Ԥ����ȡ
                            ajaxCache.push($.ajax({
                                url: "?model=finance_expense_expense&action=getWarning",
                                data: pi,
                                type: 'post',
                                dataType: 'json',
                                success: function (rst2) {
                                    // ����url
                                    var url = "?model=engineering_project_esmproject&action=showDetail&t=4&year=" + year + "&month="
                                        + month + "&ids=" + rst2.projectIds + "&projectCodes=" + rst2.projectNos;

                                    // �仯��
                                    easyFeeUrl(rst2.k, 'feeWarning', rst2.changeRate, url, ' %', true);
                                }
                            }));
                        }
                        // ������
                        checkDone();
                    }
                }));
            } else {
                // �仯��
                $("#feeWarning" + i).append('--');
                $("#weekLogWarning" + i).append('--');
                // ������
                checkDone();
            }
        }
    } else {
        // ����δ�ر�
        for (i = 0; i < listData.total; i++) {
            // ����
            p = {
                officeId: listData.rows[i].officeId,
                province: listData.rows[i].proName,
                k: i
            };
            // ��־Ԥ����ȡ
            ajaxCache.push($.ajax({
                url: "?model=engineering_project_esmproject&action=showCount",
                data: p,
                type: 'post',
                dataType: 'json',
                success: function (rst) {
                    // ����url
                    var url = "?model=engineering_project_esmproject&action=showDetail&t=";

                    // ��֧����
                    easyUrl(rst.k, 'feeOutOfLimit', rst.feeOutOfLimit, url, 1);

                    // ��ë��
                    easyUrl(rst.k, 'negativeExgross', rst.negativeExgross, url, 2);

                    // ��ë��
                    easyUrl(rst.k, 'lowExgross', rst.lowExgross, url, 3);

                    // CPIԤ��
                    easyUrl(rst.k, 'CPIWarning', rst.CPIWarning, url, 9);

                    // SPIԤ��
                    easyUrl(rst.k, 'SPIWarning', rst.SPIWarning, url, 10);

                    // ������
                    checkDone();
                }
            }));
        }
    }
}

// ������
function checkDone() {
    done++;
    if (done == listData.total) {
        hideLoading();
    }
}

// �ж�ajax����
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

    // δ�ر�����
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
 * ��װһ��ִ��Ԥ�������ݻ����
 */
function easyUrl(k, f, v, url, t) {
    // ���ʹ���
    url = url + t + "&officeId=" + listData.rows[k].officeId + "&province=" + listData.rows[k].proName;

    // δ�ر�����
    var vi = v == "0" ?
        v :
        "<a href='javascript:void(0)' onclick='window.open(\"" + url + "\");'>" + v + "</a>";
    $("#" + f + k).append(vi);

    if (v * 1 == v) {
        var countObj = $("#" + f + "_countRow");
        countObj.text(countObj.text() * 1 + v);
    }
}

//��ʾloading
function showLoading() {
    $("#loading").show();
    $("#search").attr('disabled', true);
}

// ����loading
function hideLoading() {
    $("#loading").hide();
    $("#search").attr('disabled', false);
}