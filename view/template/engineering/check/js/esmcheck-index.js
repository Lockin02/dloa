/**
 * Created by Kuangzw on 2017/8/31.
 */
$(function () {
    // 加载loading
    showLoading();

    // 表头处理
    var colModel = [{
        field: 'id',
        checkbox: true,
        width: 100
    }, {
        title: '栏目',
        field: 'category',
        width: 100
    }, {
        title: '检查项',
        field: 'item',
        width: 150
    }, {
        title: '检查数',
        field: 'checkNum',
        align: 'center',
        width: 100,
        formatter: function (value, row, index) {
            return "<span id='checkNum" + row.id + "'></span>";
        }
    }, {
        title: '正确数',
        field: 'correctNum',
        align: 'center',
        width: 100,
        formatter: function (value, row, index) {
            return "<span id='correctNum" + row.id + "'></span>";
        }
    }, {
        title: '异常数',
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
        loadMsg: '加载中，请稍后...',
        emptyMsg: '没有查询到相关数据...',
        height: thisHeight,
        columns: [colModel]
    });
});

// 开始检查方法
var startCheck = function () {
    // 选中行
    var checkedRows = $('#grid').datagrid('getChecked');
    if (checkedRows.length > 0) {
        var status = $("input[name='projectStatus']:checked").val();
        checkNum = checkedRows.length;
        done = 0;

        // 加载loading
        showLoading();

        for (var i = 0; i < checkedRows.length; i++) {

            // 清除原来的检查信息
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
                        // 类型处理
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

                    // 检测完成
                    checkDone();
                }
            });
        }
    } else {
        alert("请至少选中一项要检查的内容。");
    }
};

var checkNum = 0;
var done = 0;

// 检测完成
var checkDone = function () {
    done++;
    if (done == checkNum) {
        hideLoading();
    }
};

// 清除原来的检查信息
var cleanRow = function (id) {
    $("#checkNum" + id).html('');
    $("#correctNum" + id).html('');
    $("#errorNum" + id).html('');
};

//显示loading
var showLoading = function () {
    $("#loading").show();
    $("#search").attr('disabled', true);
};

// 隐藏loading
var hideLoading = function () {
    $("#loading").hide();
    $("#search").attr('disabled', false);
};