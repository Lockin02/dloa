$(function () {
    var isOnlyCurDept = $("#isOnlyCurDept").val() == "true" ? 1 : 0;
    var deptIds = $("#deptIds").val();
    var tabType = 2; // tab类型 2为部门 3为角色 与searchType对应
    var mode = $("#mode").val();
    var formCode = $("#formCode").val();

    // 人员选择添加 人员编号 update on 2012-6-6 by kuangzw 暂时不支持多选
    var userNo = $("#userNo").val();
    // 人员选择是否带出职位信息
    var isNeedJob = $("#isNeedJob").val();
    // 是否显示离职人员
    var isShowLeft = $("#isShowLeft").val();
    // 是否往部门追加人员
    var isDeptAddedUser = $("#isDeptAddedUser").val() == "true" ? 1 : 0;
    // 部门是否设置人员选择范围
    var isDeptSetUserRange = $("#isDeptSetUserRange").val() == "true" ? 1 : 0;

    // 处理常用选择人
    if (formCode) {
        var $select = $("<select  class='select'><option value=''>请选择常用人...</option></select>");
        $select.change(function () {
            if ($(this).val()) {
                if (mode == 'single') {
                    $("#selectedUser").empty();
                }
                var $selected = $("#selectedUser").find('option[value=' + $(this).val() + ']');
                if ($selected.size() == 0) {
                    var $selected = $(this).find("option:selected");
                    var text = $selected.text();
                    var $option = $("<option value='" + $(this).val() + "'>" + text + "</option>");
                    $("#selectedUser").append($option);
                    $("#deptId").val($selected.attr("deptId")); // 获取部门ID与部门名称
                    $("#deptName").val($selected.attr("deptName"));
                    // 获取人员职位id update on 2012-6-11 by kuangzw
                    $("#jobId").val($selected.attr("jobId")); // 获取人员职位Id
                }
            }
        });
        // $("#searchTd").append("常用人员选择：");
        $("#searchTd").append($select);
        $.ajax({
            type: 'POST',
            dataType: 'json',
            data: {
                formCode: formCode
            },
            url: "?model=deptuser_user_userselect&action=getCurUserModelSelect",
            success: function (data) {
                var $options = '';
                for (var i = 0; i < data.length; i++) {
                    var d = data[i];
                    if (d.selectUserName != "") {
                        $options += "<option deptName='" + d.deptName
                            + "' deptId='" + d.deptId
                            + "' value='" + d.selectUserId
                            + "' jobId='" + d.jobId + "'>" + d.selectUserName
                            + "</option>";
                    }
                }
                $select.append($options);
            }
        });
    }
    // 当只能选择当前登录人所在部门或指定部门时，隐藏“角色”Tab跟下拉选择 add by suxc 2011-08-18
    if (isOnlyCurDept || deptIds != '') {
        $("#jobsTab").hide();
        $("#searchType option:eq(2)").hide();
    }

    // 获取数据 searchType 1:人员 2:部门
    function getDeptTreeData(searchType, searchVal) {
        var param = {
            url: "?model=deptuser_user_user&action=deptusertree&isOnlyCurDept=" + isOnlyCurDept
                + "&deptIds=" + deptIds
                + "&isShowLeft=" + isShowLeft,
            type: 'POST',
            async: false
        };
        if (searchVal) {
            param.data = {};
            if (searchType == 1) { // 人员
                param.data.userName = searchVal;
            } else if (searchType == 2) { // 部门
                param.data.deptName = searchVal;
            }

        }
        var data = $.ajax(param).responseText;
        data = eval("(" + data + ")");
        return data;
    }

    // 获取角色数据
    function getJobsTreeData(searchType, searchVal) {
        var param = {
            url: "?model=deptuser_user_user&action=jobsusertree&isShowLeft=" + isShowLeft,
            type: 'POST',
            async: false
        };
        if (searchVal) {
            param.data = {};
            if (searchType == 1) { // 人员
                param.data.userName = searchVal;
            } else { // 角色
                param.data.jobsName = searchVal;
            }

        }
        var data = $.ajax(param).responseText;
        data = eval("(" + data + ")");
        return data;
    }

    var lastSelectNode;
    $("#deptTree").yxtree({
        checkable: mode != 'single',
        height: 360,
        // data : getDeptTreeData(),
        url: "?model=deptuser_user_user&action=deptusertree&isOnlyCurDept=" + isOnlyCurDept
            + "&deptIds=" + deptIds
            + "&isShowLeft=" + isShowLeft
            + "&isDeptAddedUser=" + isDeptAddedUser
            + "&isDeptSetUserRange=" + isDeptSetUserRange,
        param: ['id', 'Depart_x', 'Dflag', 'comCode'],
        nameCol: "name",
        event: {
            node_click: function (e, treeId, node) {
                if (mode == 'single') {
                    var $selectedUserObj = $("#selectedUser");
                    if (node.type == 'user') {
                        $selectedUserObj.empty();
                        $("#deptId").val(node.DEPT_ID); // 获取部门ID与部门名称
                        $("#deptName").val(node.DEPT_NAME);
                        // 获取人员职位id update on 2012-6-11 by kuangzw
                        $("#jobId").val(node.jobs_id); // 获取人员职位Id
                        lastSelectNode = node;
                    }
                    var $selected = $selectedUserObj.find("option[value='" + node.id + "']");
                    if ($selected.size() == 0 && node.type == 'user') {
                        $selectedUserObj.append("<option value='" + node.id + "'>" + node.USER_NAME +
                            "</option>");
                    }
                }
            },
            node_dblclick: function (e, treeId, node) {
                if (mode == 'single' && node.type == 'user') {
                    $("#confirmButton").click();
                }
            },
            node_change: function (event, treeId, node) {
                if (mode != 'single') {
                    if (!node.checked) {
                        $("#selectedUser option[value='" + node.id + "']").remove();
                        if (node.nodes) {
                            for (var i = 0; i < node.nodes.length; i++) {
                                $("#selectedUser option[value='" + node.nodes[i].id + "']").remove();
                            }
                        }
                    } else { // 如果是单选，清除所有选中的数据
                        var $selectedUserObj = $("#selectedUser");
                        var $selected = $selectedUserObj.find("option[value='" + node.id + "']");
                        var $options = '';
                        if ($selected.size() == 0 && node.type == 'user') {
                            $options += "<option value='" + node.id + "'>" + node.USER_NAME +
                                "</option>";
                        }
                        $selectedUserObj.append($options);
                    }
                }
            }
        }
    });
    // 防止重叠
    var offset = $("#deptTree").offset();
    var showOffset = {
        top: offset.top,
        left: offset.left
    };
    var hideOffset = {
        top: 1000,
        left: 1000
    };
    var isFistClickDept = true; // 第一次点击部门tab标识
    var isFistClickJobs = true; // 第一次点击角色tab
    $("#jobsTree").offset(hideOffset).hide();
    // 点击组织机构tab
    $("#deptTab").bind('click', function () {
        if (!isFistClickDept && !isFistClickJobs) {
            tabType = 2;
            $("#deptTree").show().offset(showOffset);
            $("#jobsTree").offset(hideOffset).hide();
        } else {
            isFistClickDept = false;
        }
    });

    $("#jobsTab").bind('click', function () {
        tabType = 3;
        $("#jobsTree").show().offset(showOffset);
        $("#deptTree").offset(hideOffset).hide().offset();
        // 如果是第一次点击，渲染角色树
        if (isFistClickJobs) {
            isFistClickJobs = false;
            $("#jobsTree").yxtree({
                height: 360,
                url: '?model=deptuser_user_user&action=jobsusertree&isShowLeft=' + isShowLeft,
                param: ['id'],
                nameCol: "name",
                event: {
                    node_click: function (e, treeId, node) {
                        var $selectedUserObj = $("#selectedUser");
                        // 如果是单选，清除所有选中的数据
                        if (mode == 'single' && node.type == 'user') {
                            $selectedUserObj.empty();
                            $("#deptId").val(node.DEPT_ID); // 获取部门ID与部门名称
                            $("#deptName").val(node.DEPT_NAME);
                            // 获取人员职位id update on 2012-6-11 by kuangzw
                            $("#jobId").val(node.jobs_id); // 获取人员职位Id
                            lastSelectNode = node;
                        }
                        var $selected = $selectedUserObj.find('option[value=' + node.id +
                            ']');
                        if ($selected.size() == 0 && node.type == 'user') {
                            $selectedUserObj.append("<option value='" + node.id + "'>" +
                                node.USER_NAME + "</option>");
                        }
                    },
                    node_dblclick: function (e, treeId, node) {
                        if (mode == 'single' && node.type == 'user') {
                            $("#confirmButton").click();
                        }
                    }
                }
            });

        }
    });

    var searchFn = function () {
        var searchVal = $('#searchVal').val();
        var searchType = $('#searchType').val();
        if (searchVal != "") {
            if (searchType == 1) {
                if (tabType == 2) {
                    $("#deptTree").yxtree('reloadData',
                        getDeptTreeData(searchType, searchVal));
                } else {
                    $("#jobsTree").yxtree('reloadData',
                        getJobsTreeData(searchType, searchVal));
                }
            } else if (searchType == 2) { // 搜索机构
                if (searchType != tabType) {
                    // 点击
                    $("#deptTab").trigger('click');
                }
                $("#deptTree").yxtree('reloadData',
                    getDeptTreeData(searchType, searchVal));
            } else if (searchType == 3) {
                if (searchType != tabType) { // 搜索角色
                    // 点击
                    $("#jobsTab").trigger('click');
                }
                $("#jobsTree").yxtree('reloadData',
                    getJobsTreeData(searchType, searchVal));
            }

        }
    };

    $("#searchVal").bind('keyup', function (e) {
        if (e.keyCode == 13) { // 回车
            searchFn();
        }
    });
    // 搜索事件
    $("#searchButton").bind('click', searchFn);
    // 清除搜索事件
    $("#clearButton").bind('click', function () {
        $("#searchVal").val('');
        $("#deptTree").yxtree('reloadData', getDeptTreeData());
        $("#jobsTree").yxtree('reloadData', getJobsTreeData());
    });
    // 清除选中人员
    $("#clearSelectedButton").bind('click', function () {
        $("#selectedUser").empty();
    });

    $("#selectedUser").bind('dblclick', function () {
        $('#selectedUser option:selected').remove();
    });

    // 确定按钮事件
    $("#confirmButton").bind('click', function () {
        var valArr = [];
        var textArr = [];
        $("#selectedUser option").each(function () {
            if ($(this).val() != "" && $(this).text() != "") {
                valArr.push($(this).val());
                textArr.push($(this).text());
            }
        });

        // 人员选择添加 人员编号 update on 2012-6-6 by kuangzw
        var uesrNoValue = "";
        if (userNo && mode == 'single') {
            $.ajax({
                url: '?model=common_otherdatas&action=getUserNo',
                type: 'POST',
                async: false,
                data: {
                    userAccount: valArr.toString()
                },
                success: function (data) {
                    uesrNoValue = data;
                }
            });
        }

        // 人员选择添加 人员编号 update on 2012-6-6 by kuangzw
        var jobId = $("#jobId").val();
        var jobName = "";
        if (isNeedJob && mode == 'single') {
            $.ajax({
                url: '?model=common_otherdatas&action=getJobName',
                type: 'POST',
                async: false,
                data: {
                    jobId: jobId
                },
                success: function (data) {
                    jobName = data;
                }
            });
        }
        var companyCode;
        var companyName;
        if (lastSelectNode) {
            companyCode = lastSelectNode.Company;
            companyName = lastSelectNode.companyName;
        }

        // 返选择结果
        var returnValue = {
            text: textArr.toString(),
            val: valArr.toString(),
            deptId: $("#deptId").val(),
            deptName: $("#deptName").val(),
            userNo: uesrNoValue, // 人员选择添加 人员编号 update on 2012-6-6 by kuangzw
            jobId: jobId, // 人员选择添加 职位信息 update on 2012-6-11 by kuangzw
            jobName: jobName,
            companyCode: companyCode,
            companyName: companyName
        };

        // 返回
        var targetId = $("#targetId").val();
        if (targetId != "") {
            try {
                window.parent.$("#" + targetId).yxselect_user('setData', returnValue);
            } catch (e) {
                console.log(e);
            }
        } else {
            window.returnValue = returnValue;
        }

        // 保存选择人
        if (formCode) {
            $.ajax({
                url: '?model=deptuser_user_userselect&action=saveSelectedUser',
                type: 'POST',
                data: {
                    selectedUserIds: valArr.toString(),
                    selectedUserNames: textArr.toString(),
                    formCode: formCode
                }
            });
        }
        close();
    });

    // 关闭按钮处理
    $('#closeButton').bind('click', function () {
        close();
    });

    // 通用关闭方法
    var close = function () {
        try {
            // var windowId = window.parent.$.window.getSelectedWindow().getWindowId();
            window.parent.$.window.getSelectedWindow().close();
        } catch (e) {
            console.log(e);

            // 特殊关闭 - 针对jquery ui
            window.parent.$("div[id^='window_']").each(function() {
                $(this).remove();
            });
        }
    };

    // 如果有选择值，遍历树，把选中的节点放置到右边选中面板上
    var userVal = $("#userVal").val();
    if (userVal != "") {
        var userArr = userVal.split(",");
        var options = '';
        for (var i = 0; i < userArr.length; i++) {
            var v = userArr[i];
            if (v) {
                $.ajax({
                    type: 'POST',
                    url: "?model=deptuser_user_user&action=getUserName",
                    async: false,
                    data: "userId=" + v,
                    success: function (data) {
                        options += "<option value='" + v + "'>" + data + "</option>";
                    }
                });
            }
        }
        $("#selectedUser").append(options);
    }
});