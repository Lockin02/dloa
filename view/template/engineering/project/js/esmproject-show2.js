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
    if ($.inArray(t, ["2", "3", "4"]) !== -1) {
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

    // ����
    var colModel = [{
        title: '�ﱸ',
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
        title: '�ڽ�',
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
        title: '�깤',
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
        title: '��ͣ',
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
        title: '����δ�ر�',
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
        title: '�ر�',
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
        title: '�����ϼ�',
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
    // ��ѯʱ������ajax����ռ�
    ajaxCache = []; // ���
    var i, p;
    // ����δ�ر�
    for (i = 0; i < listData.total; i++) {
        if (listData.rows[i].officeId != "countRow") {
            // ����
            p = {
                officeId: listData.rows[i].officeId,
                province: listData.rows[i].proName,
                k: i,
                t: $("#t").val()
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

                    // �ﱸ��Ŀ
                    easyUrl(rst.k, 'prepare', rst.prepare, url, 5);

                    // �ڽ���Ŀ
                    easyUrl(rst.k, 'building', rst.building, url, 6);

                    // �깤
                    easyUrl(rst.k, 'completed', rst.completed, url, 7);

                    // �ر�
                    easyUrl(rst.k, 'closed', rst.closed, url, 8);

                    // δ�ر�����
                    easyUrl(rst.k, 'unClose', rst.unClose, url, 0);

                    // ��ͣ����
                    easyUrl(rst.k, 'stop', rst.stop, url, 12);

                    // �ϼ�����
                    easyUrl(rst.k, 'count', rst.count, url, 11);

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
    if (done == listData.total - 1) {
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

    var countObj = $("#" + f + "_countRow");
    countObj.text(countObj.text() * 1 + v);
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