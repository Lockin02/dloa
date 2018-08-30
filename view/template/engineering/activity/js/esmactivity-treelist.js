$(function() {
    if ($("#isCategyAProject").val() == "1") $(".a_class").show();// A类项目才显示这个内容
    var thisHeight = document.documentElement.clientHeight - 40;
    $('#esmactivityGrid').treegrid({
        url: '?model=engineering_activity_esmactivity&action=manageTreeJson&projectId=' + $("#projectId").val() +
            "&parentId=" + $("#parentId").val(),
        title: '项目任务',
        width: '98%',
        height: thisHeight,
        nowrap: false,
        rownumbers: true,
        animate: true,
        collapsible: true,
        idField: 'id',
        treeField: 'activityName',//树级索引
        fitColumns: false,//宽度适应
        pagination: false,//分页
        showFooter: true,//显示统计
        columns: [[
            {
                title: '任务名称',
                field: 'activityName',
                width: 210,
                formatter: function(v, row) {
                    if (row.id == 'noId') return v;
                    if (row.thisType == "0") {
                        if ((row.rgt - row.lft) == 1) {
                            return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_activity_esmactivity&action=toView&id=" + row.id + '&skey=' + row.skey_ + "\",1,650,1000," + row.id + ")'>" + v + "</a>";
                        } else {
                            return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_activity_esmactivity&action=toViewNode&id=" + row.id + '&skey=' + row.skey_ + "\",1,650,1000," + row.id + ")'>" + v + "</a>";
                        }
                    } else {
                        if (row.isChanging == "1") {
                            if ((row.rgt - row.lft) == 1) {
                                return "<a href='javascript:void(0)' style='color:red;' title='变更中的任务' onclick='showOpenWin(\"?model=engineering_activity_esmactivity&action=toViewChange&id=" + row.uid + "\",1,650,1000," + row.uid + ")'>" + v + "</a>";
                            } else {
                                return "<a href='javascript:void(0)' style='color:red;' title='变更中的任务' onclick='showOpenWin(\"?model=engineering_activity_esmactivity&action=toViewNodeChange&id=" + row.uid + '&skey=' + row.skey_ + "\",1,650,1000," + row.uid + ")'>" + v + "</a>";
                            }
                        } else {
                            if ((row.rgt - row.lft) == 1) {
                                return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_activity_esmactivity&action=toViewChange&id=" + row.id + '&skey=' + row.skey_ + "\",1,650,1000," + row.id + ")'>" + v + "</a>";
                            } else {
                                return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=engineering_activity_esmactivity&action=toViewNodeChange&id=" + row.id + '&skey=' + row.skey_ + "\",1,650,1000," + row.id + ")'>" + v + "</a>";
                            }
                        }
                    }
                }
            }, {
                field: 'workRate',
                title: '工作占比',
                width: 65,
                formatter: function(v, row) {
                    if (!row._parentId) {
                        return "<span style='font-weight:bold;'>" + v + " %</span>";
                    } else {
                        return v + " %";
                    }
                }
            }, {
                field: 'process',
                title: '工作进度',
                width: 70,
                formatter: formatProgress
            }, {
                field: 'waitConfirmProcess',
                title: '待确认进度',
                width: 70,
                formatter: function(v) {
                    if (v) {
                        return v + " %";
                    }
                }
            }, {
                field: 'countProcess',
                title: '累计进度',
                width: 65,
                formatter: function(v) {
                    if (v) {
                        return v + " %";
                    }
                }
            }, {
                field: 'planProcess',
                title: '计划进度',
                width: 65,
                formatter: function(v) {
                    if (v) {
                        return v + " %";
                    }
                }
            }, {
                field: 'diffProcess',
                title: '进度差异',
                width: 65,
                formatter: function(v) {
                    if (v) {
                        v = ($("#isACatWithFallOutsourcing").val() == "1")? 0 : v;
                        if (v * 1 > 0) {
                            return "<span class='red'>" + v + " %</span>";
                        } else {
                            return v + " %";
                        }
                    }
                }
            }, {
                field: 'status',
                title: '任务状态',
                width: 60,
                formatter: function(v, row) {
                    switch (v) {
                        case '0' :
                            return '正常';
                        case '1' :
                            return '<span class="blue">关闭</span>';
                        case '2' :
                            return '<span class="red">暂停</span>';
                        default:
                            return row.id == 'noId' ? '' : '变更中';
                    }
                }
            }, {
                field: 'planBeginDate',
                title: '预计开始',
                width: 80
            }, {
                field: 'planEndDate',
                title: '预计结束',
                width: 80
            }, {
                field: 'days',
                title: '预计工期',
                width: 60
            }, {
                field: 'workload',
                title: '工作量',
                width: 50,
                formatter: function(v, row) {
                    return row.isTrial == '1' ? '--' : v;
                }
            }, {
                field: 'workloadDone',
                title: '完成量',
                width: 50,
                formatter: function(v, row) {
                    if($("#isACatWithFallOutsourcing").val() == "1"){
                        return row.workloadCount;
                    }else{
                        if (row.isTrial == '1') {
                            return '--';
                        }
                        if ((row.rgt - row.lft) == 1) {
                            if (row.confirmDays * 1 != 0) {
                                return '<span class="blue" style="font-weight:bold;" title="进度修正日期：'
                                    + row.confirmDate + '\n修正人：' + row.confirmName + '\n修正值：' + row.confirmDays + '">' + v + '</span>';
                            } else {
                                return v;
                            }
                        }
                    }
                }
            }, {
                field: 'workloadUnitName',
                title: '单位',
                width: 50
            }, {
                field: 'workContent',
                title: '工作内容',
                width: 200
            }
        ]]
    });
});

//原页面刷新方法
function show_page() {
    reload();
}

//刷新
function reload() {
    $('#esmactivityGrid').treegrid('reload');
}

//变更任务
function editActivity() {
    var node = $('#esmactivityGrid').treegrid('getSelected');
    if (node) {
        var canChange = true;
        //判断项目是否可以进行变更
        $.ajax({
            type: "POST",
            url: "?model=engineering_change_esmchange&action=hasChangeInfo",
            data: {
                projectId: $("#projectId").val()
            },
            async: false,
            success: function(data) {
                if (data * 1 == -1) {
                    canChange = false;
                }
            }
        });

        //如果不可变更
        if (canChange == false) {
            alert('项目变更审批中，请等待审批完成后再进行变更操作！');
        } else {
            if (node.isTrial == 1) {
                if (node.isChanging == "0") {
                    if (node.changeId) {
                        showOpenWin("?model=engineering_activity_esmactivity&action=toEditTrial&id="
                        + node.activityId
                        + "&skey=" + node.skey_, 1, 400, 800, node.id);
                    } else {
                        showOpenWin("?model=engineering_activity_esmactivity&action=toEditTrial&id="
                        + node.id
                        + "&skey=" + node.skey_, 1, 400, 800, node.id);
                    }
                } else {
                    showOpenWin("?model=engineering_activity_esmactivity&action=toEditTrialChange&id="
                    + node.uid
                    + "&skey=" + node.skey_, 1, 400, 800, node.id);
                }
            } else {
                if ((node.rgt - node.lft) == 1) {
                    if (node.isChanging == "0") {
                        if (node.changeId) {
                            showOpenWin("?model=engineering_activity_esmactivity&action=toEdit&id="
                            + node.activityId
                            + "&skey=" + node.skey_, 1, 650, 1000, node.id);
                        } else {
                            showOpenWin("?model=engineering_activity_esmactivity&action=toEdit&id="
                            + node.id
                            + "&skey=" + node.skey_, 1, 650, 1000, node.id);
                        }
                    } else {
                        showOpenWin("?model=engineering_activity_esmactivity&action=toEditChange&id="
                        + node.uid
                        + "&skey=" + node.skey_, 1, 650, 1000, node.id);
                    }
                } else {
                    if (node.isChanging == "0") {
                        if (node.changeId) {
                            showOpenWin("?model=engineering_activity_esmactivity&action=toEditNode&id="
                            + node.activityId
                            + "&skey=" + node.skey_, 1, 650, 1000, node.id);
                        } else {
                            showOpenWin("?model=engineering_activity_esmactivity&action=toEditNode&id="
                            + node.id
                            + "&skey=" + node.skey_, 1, 650, 1000, node.id);
                        }
                    } else {
                        showOpenWin("?model=engineering_activity_esmactivity&action=toEditNodeChange&id="
                        + node.uid
                        + "&skey=" + node.skey_, 1, 650, 1000, node.id);
                    }
                }
            }
        }
    } else {
        alert('请选择一个任务');
    }
}

//删除任务
function removeActivity() {
    var node = $('#esmactivityGrid').treegrid('getSelected');
    if (node) {
        if (node.isTrial == 1) {
            alert("该任务不能删除！");
        } else {
            var canChange = true;
            //判断项目是否可以进行变更
            $.ajax({
                type: "POST",
                url: "?model=engineering_change_esmchange&action=hasChangeInfo",
                data: {
                    projectId: $("#projectId").val()
                },
                async: false,
                success: function(data) {
                    if (data * 1 == -1) {
                        canChange = false;
                    }
                }
            });

            //如果不可变更
            if (canChange == false) {
                alert('项目变更审批中，请等待审批完成后再进行变更操作！');
            } else {
                var nodeId; //缓存任务原始id ，用于判断任务是否存在日志
                var changeId = "";//变更申请单id
                if (node.changeId) {
                    nodeId = node.activityId;
                    changeId = node.changeId;
                    //存在变更id
                    if (!node.activityId) {
                        //判断提示信息
                        if ((node.rgt - node.lft) == 1) {
                            var alertText = '确认要删除？';
                        } else {
                            var alertText = '删除此任务，会将下级任务一并删除，确认要执行此操作吗？';
                        }
                        //确认
                        if (confirm(alertText)) {
                            //异步删除任务
                            $.ajax({
                                type: "POST",
                                url: "?model=engineering_activity_esmactivity&action=ajaxdeletes",
                                data: {
                                    id: node.id,
                                    changeId: changeId,
                                    projectId: $("#projectId").val()
                                },
                                async: false,
                                success: function(data) {
                                    if (data == "1") {
                                        alert('删除成功');
                                        reload();
                                    } else {
                                        alert('删除失败');
                                        return false;
                                    }
                                }
                            });
                        }
                        return false;
                    }
                } else {
                    nodeId = node.id;
                }

                //判断任务是否可以删除
                $.ajax({
                    type: "POST",
                    url: "?model=engineering_worklog_esmworklog&action=checkActAndParentLog",
                    data: {
                        activityId: nodeId
                    },
                    async: false,
                    success: function(data) {
                        if (data == "1") {
                            alert('此任务或其下级任务已经包含日志信息，不能进行删除');
                        } else {
                            //判断提示信息
                            if ((node.rgt - node.lft) == 1) {
                                var alertText = '确认要删除？';
                            } else {
                                var alertText = '删除此任务，会将下级任务一并删除，确认要执行此操作吗？';
                            }
                            //确认
                            if (confirm(alertText)) {
                                //异步删除任务
                                $.ajax({
                                    type: "POST",
                                    url: "?model=engineering_activity_esmactivity&action=ajaxdeletes",
                                    data: {
                                        id: node.id,
                                        changeId: changeId,
                                        projectId: $("#projectId").val()
                                    },
                                    async: false,
                                    success: function(data) {
                                        if (data == "1") {
                                            alert('删除成功');
                                            reload();
                                        } else {
                                            alert('删除失败');
                                            return false;
                                        }
                                    }
                                });
                            }
                        }
                    }
                });
            }
        }
    } else {
        alert('请选择一个任务');
    }
}

//取消选中
function cancelSelect() {
    $('#esmactivityGrid').treegrid('unselectAll');
}

//新增任务 -- 变更
function addActivity() {
    var canChange = true;
    //判断项目是否可以进行变更
    $.ajax({
        type: "POST",
        url: "?model=engineering_change_esmchange&action=hasChangeInfo",
        data: {
            projectId: $("#projectId").val()
        },
        async: false,
        success: function(data) {
            if (data * 1 == -1) {
                canChange = false;
            }
        }
    });

    //如果不可变更
    if (canChange == false) {
        alert('项目变更审批中，请等待审批完成后再进行变更操作！');
    } else {
        var node = $('#esmactivityGrid').treegrid('getSelected');
        if (node) {
            if (node.isTrial == 1) {
                alert('不能在此任务下新增任务！');
            } else {
                if (node.process * 1 > 0 && (node.rgt - node.lft) == 1) {
                    alert('任务已经包含相关日志，不能新增下级任务');
                    return false;
                }
                if (node.changeId) {
                    //如果选中任务没有子任务，则提示
                    if ((node.rgt - node.lft) == 1) {
                        if (confirm("新增第一个下级任务会将【" + node.activityName + "】的相关内容转入新任务中，确认建立吗？")) {
                            showOpenWin("?model=engineering_activity_esmactivity&action=toMove&parentId="
                            + node.id
                            + "&changeId=" + node.changeId
                            + "&projectId=" + $("#projectId").val(), 1, 650, 1000, node.id);
                        }
                    } else {
                        showOpenWin("?model=engineering_activity_esmactivity&action=toAdd"
                        + "&parentId=" + node.uid
                        + "&changeId=" + node.changeId
                        + "&projectId=" + $("#projectId").val()
                        + "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=750", 1, 650, 1000, node.id);
                    }
                } else {
                    //如果选中任务没有子任务，则提示
                    if ((node.rgt - node.lft) == 1) {
                        if (confirm("新增第一个下级任务会将【" + node.activityName + "】的相关内容、预算转入新任务中，确认建立吗？")) {
                            showOpenWin("?model=engineering_activity_esmactivity&action=toMove&parentId="
                            + node.id + "&projectId=" + $("#projectId").val(), 1, 650, 1000, node.id);
                        }
                    } else {
                        showOpenWin("?model=engineering_activity_esmactivity&action=toAdd"
                        + "&parentId=" + node.id
                        + "&projectId=" + $("#projectId").val(), 1, 650, 1000, node.id);
                    }
                }
            }
        } else {
            showOpenWin("?model=engineering_activity_esmactivity&action=toAdd"
            + "&parentId=-1"
            + "&projectId=" + $("#projectId").val(), 1, 650, 1000, 'AAA');
        }

    }
}

//修正进度
function setWorkloadDone() {
    var node = $('#esmactivityGrid').treegrid('getSelected');
    if (node) {
        var canChange = true;
        //判断项目是否可以进行变更
        $.ajax({
            type: "POST",
            url: "?model=engineering_change_esmchange&action=hasChangeInfo",
            data: {
                projectId: $("#projectId").val()
            },
            async: false,
            success: function(data) {
                if (data * 1 == -1) {
                    canChange = false;
                }
            }
        });

        //判断是否可以修正进度
        if (node.isTrial == 1) {
            alert('不能对由试用项目产生的任务进行此操作！');
        } else {
            if ((node.rgt - node.lft) == 1) {
                if (node.isChanging == "0") {
                    if (node.changeId) {
                        alert('项目变更中，请等待审批完成后再进行变更操作！');
                    } else {
                        showOpenWin("?model=engineering_activity_esmactivity&action=toEditWorkloadDone&id="
                        + node.id
                        + "&skey=" + node.skey_, 1, 400, 800, node.id);
                    }
                } else {
                    alert('项目变更中，请等待审批完成后再进行变更操作！');
                }
            } else {
                alert('非子任务不能修正进度');
            }
        }
    } else {
        alert('请选择一个任务');
    }
}

//暂停任务
function stopActivity() {
    var node = $('#esmactivityGrid').treegrid('getSelected');
    if (node) {
        var canChange = true;
        //判断项目是否可以进行变更
        $.ajax({
            type: "POST",
            url: "?model=engineering_change_esmchange&action=hasChangeInfo",
            data: {
                projectId: $("#projectId").val()
            },
            async: false,
            success: function(data) {
                if (data * 1 == -1) {
                    canChange = false;
                }
            }
        });

        //判断是否可以修正进度
        if (node.isTrial == 1) {
            alert('不能对由试用项目产生的任务进行此操作！');
        } else {
            if ((node.rgt - node.lft) == 1) {
                if (node.isChanging == "0") {
                    if (node.changeId) {
                        alert('项目变更中，请等待审批完成后再进行操作！');
                    } else {
                        if (node.status == "0") {
                            if (confirm('确定要暂停任务吗？')) {
                                $.ajax({
                                    type: "POST",
                                    url: "?model=engineering_activity_esmactivity&action=stop",
                                    data: {
                                        id: node.id
                                    },
                                    async: false,
                                    success: function(data) {
                                        if (data == "1") {
                                            alert('暂停成功');
                                            reload();
                                        } else {
                                            alert('暂停失败');
                                        }
                                    }
                                });
                            }
                        } else {
                            alert('正常状态的任务才能执行暂停操作！');
                        }
                    }
                } else {
                    alert('项目变更中，请等待审批完成后再进行操作！');
                }
            } else {
                alert('非子任务不能进行暂停操作！');
            }
        }
    } else {
        alert('请选择一个任务');
    }
}

//恢复任务
function restartActivity() {
    var node = $('#esmactivityGrid').treegrid('getSelected');
    if (node) {
        var canChange = true;
        //判断项目是否可以进行变更
        $.ajax({
            type: "POST",
            url: "?model=engineering_change_esmchange&action=hasChangeInfo",
            data: {
                projectId: $("#projectId").val()
            },
            async: false,
            success: function(data) {
                if (data * 1 == -1) {
                    canChange = false;
                }
            }
        });

        //判断是否可以修正进度
        if (node.isTrial == 1) {
            alert('不能对由试用项目产生的任务进行此操作！');
        } else {
            if ((node.rgt - node.lft) == 1) {
                if (node.isChanging == "0") {
                    if (node.changeId) {
                        alert('项目变更中，请等待审批完成后再进行操作！');
                    } else {
                        if (node.status != "2") {
                            alert('任务不是暂停状态');
                        } else {
                            if (confirm('确认要恢复任务吗？')) {
                                $.ajax({
                                    type: "POST",
                                    url: "?model=engineering_activity_esmactivity&action=restart",
                                    data: {
                                        id: node.id
                                    },
                                    async: false,
                                    success: function(data) {
                                        if (data == "1") {
                                            alert('恢复成功');
                                            reload();
                                        } else {
                                            alert('恢复失败');
                                        }
                                    }
                                });
                            }
                        }
                    }
                } else {
                    alert('项目变更中，请等待审批完成后再进行操作！');
                }
            } else {
                alert('非子任务不能进行恢复操作！');
            }
        }
    } else {
        alert('请选择一个任务');
    }
}