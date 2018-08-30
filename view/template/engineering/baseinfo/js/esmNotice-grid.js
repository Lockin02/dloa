$(function(){
    // 如果有管理权限，切入管理页面
    if ($("#t").val() != "1") {
        // 初始化管理列表
        initGrid();
    } else {
        // 显示查询条
        $("#searchTbl").show();

        // 初始化查询列表
        initView();
    }
});

function show_page() {
    $("#grid").yxgrid('reload');
}

/**
 * 初始化管理表格
 */
function initGrid() {
    //表格部分处理
    $("#grid").yxgrid({
        model : 'engineering_baseinfo_esmNotice',
        title : '通知管理',
        isDelAction : false,
        //列信息
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        }, {
            display: '日期',
            name: 'noticeDate',
            width: 80,
            align: 'center'
        }, {
            display: '类别',
            name: 'categoryName',
            width: 80,
            align: 'center'
        }, {
            display: '主题',
            name: 'noticeTitle',
            width: 200,
            align: 'left',
            process: function(v, row) {
                var url = "?model=engineering_baseinfo_esmNotice&action=toView&id=" + row.id;
                return "<a href='javascript:void(0)' onclick='window.open(\"" + url + "\");'>" + v + "</a>";
            }
        }, {
            display: '内容',
            name: 'content',
            width: 300,
            align: 'left'
        }, {
            display: '附件',
            name: 'hasFile',
            width: 50,
            align: 'center',
            process: function(v) {
                return v == "1" ? "有" : "无";
            }
        }, {
            display: '发布人',
            name: 'createName',
            width: 80
        }, {
            display: '发布范围',
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
            text : '删除',
            icon : 'delete',
            action : function(rowData, rows, rowIds, g) {
                $.ajax({
                    type : "POST",
                    url : "?model=engineering_baseinfo_eperson&action=deleteCheck",
                    data : { "rowData" : rowData },
                    success : function(msg) {
                        if( msg == 1 ){
                            alert('对象已经被引用，不可以删除！');
                        }else{
                            g.options.toDelConfig.toDelFn(g.options,g);
                            $("#epersonGrid").yxgrid("reload");
                        }
                    }
                });
            }
        }],
        searchitems : [{
            display : "主题",
            name : 'noticeTitleSearch'
        }, {
            display : "内容",
            name : 'contentSearch'
        }],
        sortorder : "DESC",
        sortname : "noticeDate"
    });
}

// 查询列表
function initView() {
    // 基本列表，根据类型不同加载后面的列
    $("#grid").yxeditgrid('remove').yxeditgrid({
        url: '?model=engineering_baseinfo_esmNotice&action=listJson',
        param: {
            period: $("#period").val()
        },
        type: 'view',
        event: {
            reloadData: function(e, g, data) {
                if (data.length == 0) {
                    $("#grid tbody").append("<tr class='tr_even'><td colspan='8'>暂无数据</td></tr>");
                }
            }
        },
        colModel: [{
            display: '日期',
            name: 'noticeDate',
            width: 80
        }, {
            display: '类别',
            name: 'categoryName',
            width: 80
        }, {
            display: '主题',
            name: 'noticeTitle',
            width: 200,
            align: 'left',
            process: function(v, row) {
                var url = "?model=engineering_baseinfo_esmNotice&action=toView&id=" + row.id;
                return "<a href='javascript:void(0)' onclick='window.open(\"" + url + "\");'>" + v + "</a>";
            }
        }, {
            display: '内容',
            name: 'content',
            width: 300,
            align: 'left'
        }, {
            display: '附件',
            name: 'hasFile',
            width: 50,
            process: function(v) {
                return v == "1" ? "有" : "无";
            }
        }, {
            display: '发布人',
            name: 'createName',
            align: 'left',
            width: 80
        }, {
            display: '',
            name: ''
        }]
    });
}