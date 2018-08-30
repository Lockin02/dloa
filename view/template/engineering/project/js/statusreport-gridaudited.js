$(function () {
    $("#loading").show();

    //�б��ʼ��
    initList();
});

/**
 * �б��ʼ��
 */
function initList() {
    //����
    var paramObj = {
        'wcode': 'oa_esm_project_statusreport',
        'pflag': '1',
        'pageSize': $("#pageSize").val()
    };
    var gridObj = $("#statusreportGrid");
    if (gridObj.children().length != 0) {
        //������־
        gridObj.yxeditgrid("setParam", paramObj);
        show_page()
    } else {
        gridObj.yxeditgrid({
            url: '?model=engineering_project_statusreport&action=auditedJson',
            param: paramObj,
            isAddAndDel: false,
            type: 'view',
            //����Ϣ
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
                    display: '��Ŀid',
                    type: 'hidden'
                },
                {
                    name: 'weekNo',
                    display: '�ܴ�',
                    process: function (v, row) {
                        return "<a href='javascript:void(0)' onclick='viewStatusreport(" + row.id + ")'>" + v + "</a>";
                    }
                },
                {
                    name: 'createName',
                    display: '�ύ��',
                    width: 90,
                    type: 'hidden'
                },
                {
                    name: 'handupDate',
                    display: '�ύ����',
                    width: 80
                },
                {
                    name: 'projectName',
                    display: '��Ŀ����',
                    width: 130,
                    align: 'left'
                },
                {
                    name: 'projectCode',
                    display: '��Ŀ���',
                    width: 130,
                    align: 'left',
                    process: function (v, row) {
                        return "<a href='javascript:void(0)' onclick='viewProject(" + row.projectId + ")'>" + v + "</a>";
                    }
                },
                {
                    name: 'projectProcess',
                    display: '��Ŀ����',
                    process: function (v) {
                        return v + ' %';
                    }
                },
                {
                    name: 'exgross',
                    display: 'ë����',
                    process: function (v) {
                        if (v == "") {
                            return "����";
                        }
                        return v + ' %';
                    },
                    type: 'statictext'
                },
                {
                    name: 'warningNum',
                    display: '�澯����',
                    process: function (v) {
                        if (v == "") {
                            return "����";
                        }
                        return v;
                    },
                    type: 'statictext'
                },
                {
                    name: 'score',
                    display: '���˷���'
                },
                {
                    name: 'content',
                    display: '���',
                    align: 'left'
                },
                {
                    name: 'Endtime',
                    display: '����ʱ��'
                }
            ],
            event: {
                'reloadData': function (e) {
                    $("#loading").hide();
                }
            }
        });

        //���ϱ�ͷ��ʼ��
        tableHead();
    }
}

/**
 * ˢ���б�
 */
function show_page() {
    //����ı�Ͳ�ѯ��ʽ���б�
    var gridObj = $("#statusreportGrid");
    gridObj.yxeditgrid('processData');

    //ȡ��ȫѡѡ��
    $("#all").attr('checked', false);
    $("#loading").show();
}

/**
 * ��д�ӱ��ͷ
 */
function tableHead() {
    var trHTML = '';
    var detailArr = [];
    var mergeArr = [];
    var lengthArr = [];
    //���ϱ�ͷ�������
    var detailTheadJson = {
        '�ܱ���Ϣ': ['�ܴ�', '�ύ����'],
        '��Ŀ��Ϣ': ['��Ŀ����', '��Ŀ���'],
        '��Ŀ��չ': ['��Ŀ����', 'ë����'],
        '�쳣���': ['�澯����'],
        '�������': ['���˷���', '���', '����ʱ��']
    };
    //ѭ�����������ϱ�ͷ����
    for (k in detailTheadJson) {
        mergeArr.push(k);
        length = 0;
        for (var prop in detailTheadJson[k]) {
            if (detailTheadJson[k].hasOwnProperty(prop))
                length++;
        }
        lengthArr.push(length);
        //��ϸ��ͷ
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
        if ($(this).text() == '���' || $(this).is(":hidden") == true) {
            $(this).attr("rowSpan", 2);
        } else {//����Ŵ���
            if ($.inArray($(this).text(), detailArr) != -1) {
                if (markMergeLength != 0) {//�ϲ�����
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

//�鿴��Ŀ
function viewStatusreport(id) {
    //��ȡ����Id
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

//�鿴��Ŀ
function viewProject(id) {
    //��ȡ����Id
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