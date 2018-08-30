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
        'ExaStatusArr': '��������',
        'wcode': 'oa_esm_project_statusreport',
        'pflag': '0',
        'pageSize': $("#pageSize").val()
    };
    var gridObj = $("#statusreportGrid");
    if (gridObj.children().length != 0) {
        //������־
        gridObj.yxeditgrid("setParam", paramObj);
        show_page()
    } else {
        gridObj.yxeditgrid({
            url: '?model=engineering_project_statusreport&action=auditJson',
            param: paramObj,
            isAddAndDel: false,
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
                    },
                    type: 'statictext'
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
                    width: 80,
                    type: 'statictext'
                },
                {
                    name: 'projectName',
                    display: '��Ŀ����',
                    width: 150,
                    align: 'left',
                    type: 'statictext'
                },
                {
                    name: 'projectCode',
                    display: '��Ŀ���',
                    width: 150,
                    align: 'left',
                    process: function (v, row) {
                        return "<a href='javascript:void(0)' onclick='viewProject(" + row.projectId + ")'>" + v + "</a>";
                    },
                    type: 'statictext'
                },
                {
                    name: 'projectProcess',
                    display: '��Ŀ����',
                    process: function (v) {
                        return v + ' %';
                    },
                    type: 'statictext'
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
                    display: '���˷���',
                    tclass: 'txtshort',
                    event: {
                        blur: function (e) {
                            if (isNaN($(this).val()) || ($(this).val() * 1 > 10 || $(this).val() * 1 < 0)) {
                                alert('������ 0 �� 10 ���ڵ�����');
                                $(this).val('');
                            }
                            if ($(this).val() != "") {
                                var rowNum = $(this).data("rowNum");
                                var g = $(this).data("grid");
                                var id = g.getCmpByRowAndCol(rowNum, 'id').val();
                                //���˷���
                                setScore(id, $(this).val());
                            }
                        }
                    }
                },
                {
                    name: 'confirm',
                    display: "ȫѡ",
                    process: function (v, row, $tr, g, $input, rowNum) {
                        return "<input type='radio' name='okno" + row.id + "' id='ok" + row.id + "' value='" + row.id + "' spid='" + row.spid + "' rowNum='" + rowNum + "'/>";
                    },
                    type: 'statictext'
                },
                {
                    name: 'back',
                    display: "���",
                    process: function (v, row, $tr, g, $input, rowNum) {
                        return "<input type='radio' name='okno" + row.id + "' id='no" + row.id + "' value='" + row.id + "' spid='" + row.spid + "' rowNum='" + rowNum + "'/>";
                    },
                    type: 'statictext'
                },
                {
                    name: 'content',
                    display: '���',
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

        //���ϱ�ͷ��ʼ��
        tableHead();
    }
}

//���ÿ��˷���
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
 * �������
 */
function auditBatch() {
    var checkArr = $("input:radio[id^='ok']:checked");
    var checkBackArr = $("input:radio[id^='no']:checked");
    if (checkArr.length == 0 && checkBackArr.length == 0) {
        alert('û��ѡ����');
        return false;
    }
    //��һ��ѭ����֤���˷���
    var canAudit = true;
    checkArr.each(function () {
        var scoreObj = $("#statusreportGrid_cmp_score" + $(this).attr('rowNum'));
        if ($(this).attr('checked') == true && scoreObj.val() == "") {
            alert('���п��˷���Ϊ�յ������!');
            scoreObj.focus();
            canAudit = false;
            return false;
        }
    });

    if (canAudit == false) {
        return false;
    }

    //���ȷ��
    if (!confirm('ȷ�������')) {
        return false;
    }

    //�ڶ���ѭ������˲���
    checkArr.each(function () {
        var spid = $(this).attr('spid');
        var temp = $(this).attr('id');
        temp = temp.substring(2, temp.length);
        var content = $("#content" + temp).val();
//			alert(spid + " - " + content);
        //������˷���
        ajaxAudit(spid, content, 'ok');
    });

    checkBackArr.each(function () {
        var spid = $(this).attr('spid');
        var temp = $(this).attr('id');
        temp = temp.substring(2, temp.length);
        var content = $("#content" + temp).val();
//		alert(spid + " - " + content);
        //������˷���
        ajaxAudit(spid, content, 'no');
    });

    //ˢ���б�
    show_page();
}

//ajax���� - ���ò���
function ajaxAudit(spid, content, result) {
    var rsVal = '�������';
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
        '�������': ['���˷���', "ȫѡ", '���', '���']
    };
    //ѭ�����������ϱ�ͷ����
    var length = 0;
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
        if (detailArr[m] == 'ȫѡ') {
            trHTML += '<th><div class="divChangeLine" style="min-width:60px;"><a href="javascript:void(0);" onclick="checkAll();">ȷ��</a></div></th>';
        }
        else if (detailArr[m] == '���') {
            trHTML += '<th><div class="divChangeLine" style="min-width:60px;"><a href="javascript:void(0);" onclick="checkAllBack();">���</a></div></th>';
        }
        else {
            trHTML += '<th><div class="divChangeLine" style="min-width:60px;">' + detailArr[m] + '</div></th>';
        }
    }
    trHTML += '</tr>';
    trObj.after(trHTML);
}

//ȫѡ
function checkAll() {
    $("input:radio[id^='ok']").attr('checked', true);
}
function checkAllBack() {
    $("input:radio[id^='no']").attr('checked', true);
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