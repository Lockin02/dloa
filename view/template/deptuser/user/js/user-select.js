$(function () {
    var isOnlyCurDept = $("#isOnlyCurDept").val() == "true" ? 1 : 0;
    var deptIds = $("#deptIds").val();
    var tabType = 2; // tab���� 2Ϊ���� 3Ϊ��ɫ ��searchType��Ӧ
    var mode = $("#mode").val();
    var formCode = $("#formCode").val();

    // ��Աѡ����� ��Ա��� update on 2012-6-6 by kuangzw ��ʱ��֧�ֶ�ѡ
    var userNo = $("#userNo").val();
    // ��Աѡ���Ƿ����ְλ��Ϣ
    var isNeedJob = $("#isNeedJob").val();
    // �Ƿ���ʾ��ְ��Ա
    var isShowLeft = $("#isShowLeft").val();
    // �Ƿ�������׷����Ա
    var isDeptAddedUser = $("#isDeptAddedUser").val() == "true" ? 1 : 0;
    // �����Ƿ�������Աѡ��Χ
    var isDeptSetUserRange = $("#isDeptSetUserRange").val() == "true" ? 1 : 0;

    // ������ѡ����
    if (formCode) {
        var $select = $("<select  class='select'><option value=''>��ѡ������...</option></select>");
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
                    $("#deptId").val($selected.attr("deptId")); // ��ȡ����ID�벿������
                    $("#deptName").val($selected.attr("deptName"));
                    // ��ȡ��Աְλid update on 2012-6-11 by kuangzw
                    $("#jobId").val($selected.attr("jobId")); // ��ȡ��ԱְλId
                }
            }
        });
        // $("#searchTd").append("������Աѡ��");
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
    // ��ֻ��ѡ��ǰ��¼�����ڲ��Ż�ָ������ʱ�����ء���ɫ��Tab������ѡ�� add by suxc 2011-08-18
    if (isOnlyCurDept || deptIds != '') {
        $("#jobsTab").hide();
        $("#searchType option:eq(2)").hide();
    }

    // ��ȡ���� searchType 1:��Ա 2:����
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
            if (searchType == 1) { // ��Ա
                param.data.userName = searchVal;
            } else if (searchType == 2) { // ����
                param.data.deptName = searchVal;
            }

        }
        var data = $.ajax(param).responseText;
        data = eval("(" + data + ")");
        return data;
    }

    // ��ȡ��ɫ����
    function getJobsTreeData(searchType, searchVal) {
        var param = {
            url: "?model=deptuser_user_user&action=jobsusertree&isShowLeft=" + isShowLeft,
            type: 'POST',
            async: false
        };
        if (searchVal) {
            param.data = {};
            if (searchType == 1) { // ��Ա
                param.data.userName = searchVal;
            } else { // ��ɫ
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
                        $("#deptId").val(node.DEPT_ID); // ��ȡ����ID�벿������
                        $("#deptName").val(node.DEPT_NAME);
                        // ��ȡ��Աְλid update on 2012-6-11 by kuangzw
                        $("#jobId").val(node.jobs_id); // ��ȡ��ԱְλId
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
                    } else { // ����ǵ�ѡ���������ѡ�е�����
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
    // ��ֹ�ص�
    var offset = $("#deptTree").offset();
    var showOffset = {
        top: offset.top,
        left: offset.left
    };
    var hideOffset = {
        top: 1000,
        left: 1000
    };
    var isFistClickDept = true; // ��һ�ε������tab��ʶ
    var isFistClickJobs = true; // ��һ�ε����ɫtab
    $("#jobsTree").offset(hideOffset).hide();
    // �����֯����tab
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
        // ����ǵ�һ�ε������Ⱦ��ɫ��
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
                        // ����ǵ�ѡ���������ѡ�е�����
                        if (mode == 'single' && node.type == 'user') {
                            $selectedUserObj.empty();
                            $("#deptId").val(node.DEPT_ID); // ��ȡ����ID�벿������
                            $("#deptName").val(node.DEPT_NAME);
                            // ��ȡ��Աְλid update on 2012-6-11 by kuangzw
                            $("#jobId").val(node.jobs_id); // ��ȡ��ԱְλId
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
            } else if (searchType == 2) { // ��������
                if (searchType != tabType) {
                    // ���
                    $("#deptTab").trigger('click');
                }
                $("#deptTree").yxtree('reloadData',
                    getDeptTreeData(searchType, searchVal));
            } else if (searchType == 3) {
                if (searchType != tabType) { // ������ɫ
                    // ���
                    $("#jobsTab").trigger('click');
                }
                $("#jobsTree").yxtree('reloadData',
                    getJobsTreeData(searchType, searchVal));
            }

        }
    };

    $("#searchVal").bind('keyup', function (e) {
        if (e.keyCode == 13) { // �س�
            searchFn();
        }
    });
    // �����¼�
    $("#searchButton").bind('click', searchFn);
    // ��������¼�
    $("#clearButton").bind('click', function () {
        $("#searchVal").val('');
        $("#deptTree").yxtree('reloadData', getDeptTreeData());
        $("#jobsTree").yxtree('reloadData', getJobsTreeData());
    });
    // ���ѡ����Ա
    $("#clearSelectedButton").bind('click', function () {
        $("#selectedUser").empty();
    });

    $("#selectedUser").bind('dblclick', function () {
        $('#selectedUser option:selected').remove();
    });

    // ȷ����ť�¼�
    $("#confirmButton").bind('click', function () {
        var valArr = [];
        var textArr = [];
        $("#selectedUser option").each(function () {
            if ($(this).val() != "" && $(this).text() != "") {
                valArr.push($(this).val());
                textArr.push($(this).text());
            }
        });

        // ��Աѡ����� ��Ա��� update on 2012-6-6 by kuangzw
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

        // ��Աѡ����� ��Ա��� update on 2012-6-6 by kuangzw
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

        // ��ѡ����
        var returnValue = {
            text: textArr.toString(),
            val: valArr.toString(),
            deptId: $("#deptId").val(),
            deptName: $("#deptName").val(),
            userNo: uesrNoValue, // ��Աѡ����� ��Ա��� update on 2012-6-6 by kuangzw
            jobId: jobId, // ��Աѡ����� ְλ��Ϣ update on 2012-6-11 by kuangzw
            jobName: jobName,
            companyCode: companyCode,
            companyName: companyName
        };

        // ����
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

        // ����ѡ����
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

    // �رհ�ť����
    $('#closeButton').bind('click', function () {
        close();
    });

    // ͨ�ùرշ���
    var close = function () {
        try {
            // var windowId = window.parent.$.window.getSelectedWindow().getWindowId();
            window.parent.$.window.getSelectedWindow().close();
        } catch (e) {
            console.log(e);

            // ����ر� - ���jquery ui
            window.parent.$("div[id^='window_']").each(function() {
                $(this).remove();
            });
        }
    };

    // �����ѡ��ֵ������������ѡ�еĽڵ���õ��ұ�ѡ�������
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