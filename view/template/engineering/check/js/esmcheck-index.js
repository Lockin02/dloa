/**
 * Created by Kuangzw on 2017/8/31.
 */
$(function () {
    // ����loading
    showLoading();

    // ��ͷ����
    var colModel = [{
        field: 'id',
        checkbox: true,
        width: 100
    }, {
        title: '��Ŀ',
        field: 'category',
        width: 100
    }, {
        title: '�����',
        field: 'item',
        width: 150
    }, {
        title: '�����',
        field: 'checkNum',
        align: 'center',
        width: 100,
        formatter: function (value, row, index) {
            return "<span id='checkNum" + row.id + "'></span>";
        }
    }, {
        title: '��ȷ��',
        field: 'correctNum',
        align: 'center',
        width: 100,
        formatter: function (value, row, index) {
            return "<span id='correctNum" + row.id + "'></span>";
        }
    }, {
        title: '�쳣��',
        field: 'errorNum',
        align: 'center',
        width: 150,
        formatter: function (value, row, index) {
            return "<span id='errorNum" + row.id + "'></span>";
        }
    }];

    var thisHeight = document.documentElement.clientHeight - 40;

    $('#grid').datagrid({
        url: '?model=engineering_check_esmcheck&action=getItems',
        onLoadSuccess: function (data) {
            hideLoading();
        },
        loadMsg: '�����У����Ժ�...',
        emptyMsg: 'û�в�ѯ���������...',
        height: thisHeight,
        columns: [colModel]
    });
});

// ��ʼ��鷽��
var startCheck = function () {
    // ѡ����
    var checkedRows = $('#grid').datagrid('getChecked');
    if (checkedRows.length > 0) {
        var status = $("input[name='projectStatus']:checked").val();
        checkNum = checkedRows.length;
        done = 0;

        // ����loading
        showLoading();

        for (var i = 0; i < checkedRows.length; i++) {

            // ���ԭ���ļ����Ϣ
            cleanRow(checkedRows[i].id);

            $.ajax({
                url: "?model=engineering_check_esmcheck&action=dealCheck",
                data: {
                    "id": checkedRows[i].id,
                    "status": status
                },
                type: "post",
                dataType: "json",
                success: function (msg) {
                    $("#checkNum" + msg.id).html(msg.checkNum);
                    $("#correctNum" + msg.id).html(msg.correctNum);

                    var type = (msg.type == null || msg.type == undefined)? 'default' : msg.type;
                    if ((msg.errorNum * 1 > 0) || (msg.spcial != null && msg.spcial != undefined && msg.spcial == 1)) {
                        // ���ʹ���
                        if(type == "string"){
                            var a = "<a href='#' style='color: black;' title='"+msg.errorString+"'>" + msg.errorNum + "</a>"
                            var correctString = "<a href='#' style='color: black;' title='"+msg.correctString+"'>" + msg.correctNum + "</a>"
                            $("#correctNum" + msg.id).html(correctString);
                        }else{
                            var url = "?model=engineering_project_esmproject&action=showDetail&t="+type+"&ids=" + msg.errorProjectIds;
                            var a =
                                "<a href='javascript:void(0)' onclick='window.open(\"" + url + "\");'>" + msg.errorNum + "</a>";
                        }

                        $("#errorNum" + msg.id).html(a);
                    } else {
                        $("#errorNum" + msg.id).html(msg.errorNum);
                    }

                    // ������
                    checkDone();
                }
            });
        }
    } else {
        alert("������ѡ��һ��Ҫ�������ݡ�");
    }
};

var checkNum = 0;
var done = 0;

// ������
var checkDone = function () {
    done++;
    if (done == checkNum) {
        hideLoading();
    }
};

// ���ԭ���ļ����Ϣ
var cleanRow = function (id) {
    $("#checkNum" + id).html('');
    $("#correctNum" + id).html('');
    $("#errorNum" + id).html('');
};

//��ʾloading
var showLoading = function () {
    $("#loading").show();
    $("#search").attr('disabled', true);
};

// ����loading
var hideLoading = function () {
    $("#loading").hide();
    $("#search").attr('disabled', false);
};