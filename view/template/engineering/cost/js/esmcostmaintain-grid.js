$(function () {
    //��ȡȨ��
    $.ajax({
        type: 'POST',
        url: '?model=engineering_project_esmproject&action=getLimits',
        data: {
            'limitName': '��Ŀ����ά��'
        },
        async: false,
        success: function (data) {
            if (data == 1) {//ӵ�ж�ӦȨ��
                //����ɾ�������밴ť
                $("#showAllBtn").after("<hr style='margin: 3px 0'/>" +
                    "<input type='button' class='txt_btn_a' value='�� ��' style='cursor: pointer;' onclick='toImport();'/>");
            }
        }
    });
    initCostlog();
});

function show_page() {
    initCostlog();
}

//��ʼ����Ŀ����ά����
function initCostlog() {
    var projectCode = $("#projectCode").val();
    var costType = $("#costType").val();
    var startMonth = $("#startMonth").val();
    var endMonth = $("#endMonth").val();
    var startYear = $("#startYear").val();
    var endYear = $("#endYear").val();
    var ExaStatus = $("#ExaStatus").val();

    if (startMonth != "" && endMonth != "") {	//�����·ݹ���
        var s = DateDiff(startMonth + "-01", endMonth + "-01");//���ڸ�ʽ����ΪY-m-d
        if (s < 0) {
            alert("��ѯ��ʼ�·ݲ��ܱȲ�ѯ�����·���");
            return false;
        }
    }
    if (startYear != "" && endYear != "") { //������ݹ���
        if (startYear > endYear) {
            alert("��ѯ��ʼ��ݲ��ܱȲ�ѯ���������");
            return false;
        }
    }
    showLoading(); // ��ʾ����ͼ��
    //��������
    $.ajax({
        url: '?model=engineering_cost_esmcostmaintain&action=searchJson',
        data: {
            projectCode: projectCode,
            costType: costType,
            startMonth: startMonth,
            endMonth: endMonth,
            startYear: startYear,
            endYear: endYear,
            ExaStatus: ExaStatus
        },
        type: 'POST',
        success: function (data) {
            var objGrid = $("#esmcostmaintainGrid");
            if (objGrid.html() != "") {
                objGrid.empty();
            }
            objGrid.html(data);
            hideLoading(); // ���ؼ���ͼ��
        }
    });
}

//��ʾloading
function showLoading() {
    $("#loading").show();
}
//����
function hideLoading() {
    $("#loading").hide();
}
//���ص���
function currentMonth() {
    var currentMonth = $("#currentMonth").val();
    //�����·�����Ϊ��ǰ��
    $("#startMonth").val(currentMonth);
    $("#endMonth").val(currentMonth);
    //���������������
    $("#projectCode").val("");
    $("#costType").val("");
    $("#startYear").val("");
    $("#endYear").val("");
    $("#ExaStatus").val("");
    //�����б�
    initCostlog();
}
//��������
function showAll() {
    //���������������
    $("#projectCode").val("");
    $("#costType").val("");
    $("#startMonth").val("");
    $("#endMonth").val("");
    $("#startYear").val("");
    $("#endYear").val("");
    $("#ExaStatus").val("");
    //�����б�
    initCostlog();
}
//�鿴��Ŀ��ϸ
function searchDetail(projectId) {
    var skey = "";
    $.ajax({
        type: "POST",
        url: "?model=engineering_project_esmproject&action=md5RowAjax",
        data: { "id": projectId },
        async: false,
        success: function (data) {
            skey = data;
        }
    });
    showModalWin("?model=engineering_project_esmproject&action=viewTab&id=" + projectId + "&skey=" + skey, 1);
}
//ɾ��
function deleteChecked() {
    //����id����
    var ids = [];
    $("input[type='checkbox']:checked").each(function () {
        id = $(this).val();
        if (id != "") {
            ids.push(id);
        }
    });
    if (ids.length > 0) {
        //��id����ת�����Զ��Ÿ������ַ���
        ids = ids.join(",");
        if (confirm('ȷ��Ҫɾ����')) {
            $.ajax({
                type: 'POST',
                url: '?model=engineering_cost_esmcostmaintain&action=ajaxdeletes',
                data: {
                    id: ids
                },
                success: function (data) {
                    if (data == 1) {
                        alert("ɾ���ɹ�");
                        //�����б�
                        initCostlog();
                    } else {
                        alert("ɾ��ʧ��");
                    }
                }
            });
        }
    } else {
        alert("������ѡ��һ������");
    }
}
//����
function toImport() {
    showThickboxWin("?model=engineering_cost_esmcostmaintain&action=toImport"
        + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");
}
//ȫѡ
function checkAll(obj) {
    var checked = $(obj).attr("checked");
    $(obj).parents(".main_table:first").find("tbody input[type='checkbox']").each(function () {
        $(this).attr("checked", checked);
    });
}