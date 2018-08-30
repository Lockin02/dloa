$(function () {

});

function search() {
    var grid = $("#esmbudgetGrid");
    var projectId = $("#projectId").val();
    var begin = $("#begin").val();
    var end = $("#end").val();

    if (grid.html() == "") {
        grid.yxeditgrid({
            url: '?model=engineering_records_esmfielddetail&action=viewHistory',
            type: 'view',
            param: {
                projectId: projectId,
                begin: begin,
                end: end
            },
            //列信息
            colModel: [{
                display: 'id',
                name: 'id',
                type: 'hidden'
            }, {
                name: 'budgetName',
                display: '费用类型',
                align: 'left',
                width: 200,
                process: function (v, row) {
                    switch (row.budgetType) {
                        case '' :
                            return v;
                        case 'budgetField' :
                            return '<span class="blue">' + v + '</span>';
                        case 'budgetPerson' :
                            return '<span class="green">' + v + '</span>';
                        case 'budgetOutsourcing' :
                            return '<span style="color:gray;">' + v + '</span>';
                        case 'budgetOther' :
                            return v;
                        case 'budgetEqu' :
                            return '<span style="color:brown;">' + v + '</span>';
                        case 'budgetTrial' :
                            return '<span style="color:orange;">' + v + '</span>';
                        case 'budgetFlights' :
                            return '<span style="color:lightseagreen;">' + v + '</span>';
                        default:
                            return v;
                    }
                }
            }, {
                name: 'fee',
                display: '决算',
                align: 'right',
                process: function (v) {
                    if (v == 0 || v == "") {
                        return '--';
                    } else {
                        return moneyFormat2(v);
                    }
                },
                width: 100
            }, {
                name: '',
                display: ''
            }],
            event: {
                reloadData: function(data) {
                    if (data) {
                        var i = 0;
                        $("input[id^='esmbudgetGrid_cmp_id']").each(function() {
                            var tr = $(this).parent().parent();
                            if ($(this).val() == "noId") {
                                // 渲染背景色
                                tr.css('background', "rgb(226, 237, 255)");
                                tr.children().eq(0).html("");
                            } else {
                                i++;
                                tr.children().eq(0).html(i);
                            }
                        });
                    } else {
                        alert('没有查询到相关数据');
                    }
                }
            }
        });
    } else {
        grid.yxeditgrid("setParam", {
            projectId: projectId,
            begin: begin,
            end: end
        }).yxeditgrid("processData");
    }
}