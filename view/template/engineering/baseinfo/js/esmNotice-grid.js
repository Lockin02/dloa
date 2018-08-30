$(function(){
    // ����й���Ȩ�ޣ��������ҳ��
    if ($("#t").val() != "1") {
        // ��ʼ�������б�
        initGrid();
    } else {
        // ��ʾ��ѯ��
        $("#searchTbl").show();

        // ��ʼ����ѯ�б�
        initView();
    }
});

function show_page() {
    $("#grid").yxgrid('reload');
}

/**
 * ��ʼ��������
 */
function initGrid() {
    //��񲿷ִ���
    $("#grid").yxgrid({
        model : 'engineering_baseinfo_esmNotice',
        title : '֪ͨ����',
        isDelAction : false,
        //����Ϣ
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }, {
            display: '����',
            name: 'noticeDate',
            width: 80,
            align: 'center'
        }, {
            display: '���',
            name: 'categoryName',
            width: 80,
            align: 'center'
        }, {
            display: '����',
            name: 'noticeTitle',
            width: 200,
            align: 'left',
            process: function(v, row) {
                var url = "?model=engineering_baseinfo_esmNotice&action=toView&id=" + row.id;
                return "<a href='javascript:void(0)' onclick='window.open(\"" + url + "\");'>" + v + "</a>";
            }
        }, {
            display: '����',
            name: 'content',
            width: 300,
            align: 'left'
        }, {
            display: '����',
            name: 'hasFile',
            width: 50,
            align: 'center',
            process: function(v) {
                return v == "1" ? "��" : "��";
            }
        }, {
            display: '������',
            name: 'createName',
            width: 80
        }, {
            display: '������Χ',
            name: 'officeNames',
            width: 130
        }],
        toEditConfig : {
            action : 'toEdit'
        },
        toViewConfig : {
            action : 'toView'
        },
        menusEx : [{
            text : 'ɾ��',
            icon : 'delete',
            action : function(rowData, rows, rowIds, g) {
                $.ajax({
                    type : "POST",
                    url : "?model=engineering_baseinfo_eperson&action=deleteCheck",
                    data : { "rowData" : rowData },
                    success : function(msg) {
                        if( msg == 1 ){
                            alert('�����Ѿ������ã�������ɾ����');
                        }else{
                            g.options.toDelConfig.toDelFn(g.options,g);
                            $("#epersonGrid").yxgrid("reload");
                        }
                    }
                });
            }
        }],
        searchitems : [{
            display : "����",
            name : 'noticeTitleSearch'
        }, {
            display : "����",
            name : 'contentSearch'
        }],
        sortorder : "DESC",
        sortname : "noticeDate"
    });
}

// ��ѯ�б�
function initView() {
    // �����б��������Ͳ�ͬ���غ������
    $("#grid").yxeditgrid('remove').yxeditgrid({
        url: '?model=engineering_baseinfo_esmNotice&action=listJson',
        param: {
            period: $("#period").val()
        },
        type: 'view',
        event: {
            reloadData: function(e, g, data) {
                if (data.length == 0) {
                    $("#grid tbody").append("<tr class='tr_even'><td colspan='8'>��������</td></tr>");
                }
            }
        },
        colModel: [{
            display: '����',
            name: 'noticeDate',
            width: 80
        }, {
            display: '���',
            name: 'categoryName',
            width: 80
        }, {
            display: '����',
            name: 'noticeTitle',
            width: 200,
            align: 'left',
            process: function(v, row) {
                var url = "?model=engineering_baseinfo_esmNotice&action=toView&id=" + row.id;
                return "<a href='javascript:void(0)' onclick='window.open(\"" + url + "\");'>" + v + "</a>";
            }
        }, {
            display: '����',
            name: 'content',
            width: 300,
            align: 'left'
        }, {
            display: '����',
            name: 'hasFile',
            width: 50,
            process: function(v) {
                return v == "1" ? "��" : "��";
            }
        }, {
            display: '������',
            name: 'createName',
            align: 'left',
            width: 80
        }, {
            display: '',
            name: ''
        }]
    });
}