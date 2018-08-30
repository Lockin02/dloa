$(function () {
    var mode = $("#mode").val();
    var deptFilter = $("#deptFilter").val();
    var unDeptFilter = $("#unDeptFilter").val();
    var unSltDeptFilter = $("#unSltDeptFilter").val();
    var unSltDeptFilterArr = unSltDeptFilter.split(",");
    var disableDeptLevel = $("#disableDeptLevel").val();

    // ��ȡ����
    function getTreeData(searchVal) {
        var param = {
            url: "?model=deptuser_dept_dept&action=alltree",
            type: 'POST',
            data: {
                deptFilter: deptFilter,
                unDeptFilter: unDeptFilter
            },
            async: false
        };
        if (searchVal != "") {
            param.data.deptName = searchVal;
        }
        var data = $.ajax(param).responseText;
        return eval("(" + data + ")");
    }

    var selectedNode;
    $("#tree").yxtree({
        height: 360,
        url: "?model=deptuser_dept_dept&action=tree",
        param: ['id', 'Depart_x', 'Dflag', 'comCode'],
        paramOther: {
            deptFilter: deptFilter,
            unDeptFilter: unDeptFilter
        },
        nameCol: "DEPT_NAME",
        event: {
            node_click: function (e, treeId, node) {
                var selectedDeptObj = $("#selectedDept");
                selectedNode = node;
                if (disableDeptLevel != '') {
                    if (disableDeptLevel.split(",").indexOf(node.levelflag) == -1) {
                        // ����ǵ�ѡ���������ѡ�е�����
                        if (mode == 'single') {
                            selectedDeptObj.empty();
                        }
                        if (node.id) {
                            var $selected = selectedDeptObj.find('option[value=' + node.id + ']');
                            if ($selected.size() == 0) {
                                selectedDeptObj.append("<option value='" + node.id + "'>" + node.DEPT_NAME + "</option>");
                            }
                        }
                    }
                } else {
                    // ����ǵ�ѡ���������ѡ�е�����
                    if (mode == 'single') {
                        selectedDeptObj.empty();
                    }
                    if (node.id) {
                        var $selected = selectedDeptObj.find('option[value=' + node.id + ']');
                        if ($selected.size() == 0) {
                            selectedDeptObj.append("<option value='" + node.id + "'>" + node.DEPT_NAME + "</option>");
                        }
                    }
                }
                if(unSltDeptFilter != '' && unSltDeptFilterArr.indexOf(node.id) != -1){
                    selectedDeptObj.empty();
                }
            }
        }
    });
    var searchFn = function () {
        var searchVal = $('#searchVal').val();
        if (searchVal == '') {
            $("#tree").yxtree('reload');
        } else {
            $("#tree").yxtree('reloadData', getTreeData(searchVal));
        }
    };
    $("#searchVal").bind('keyup', function (e) {
        if (e.keyCode == 13) { //�س�
            searchFn();
        }
    });
    // �����¼�
    $("#searchButton").bind('click', searchFn);
    // ��������¼�
    $("#clearButton").bind('click', function () {
        $("#searchVal").val('');
        $("#tree").yxtree('reload');
    });
    // ���ѡ�л���
    $("#clearSelectedButton").bind('click', function () {
        $("#selectedDept").empty();
    });

    $("#selectedDept").bind('dblclick', function () {
        $('#selectedDept option:selected').remove();
    });

    // ȷ����ť�¼�
    $("#confirmButton").bind('click', function () {
        var valArr = [];
        var textArr = [];
        $('#selectedDept option').each(function () {
            valArr.push($(this).val());
            textArr.push($(this).text());
        });
        var returnValue = {
            text: textArr.toString(),
            val: valArr.toString(),
            dept: selectedNode
        };

        // ����
        var targetId = $("#targetId").val();
        if (targetId != "") {
            try {
                window.parent.$("#" + targetId).yxselect_dept('setData', returnValue);
            } catch (e) {
                console.log(e);
            }
        } else {
            window.returnValue = returnValue;
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
    var deptVal = $("#deptVal").val();
    if (deptVal != "") {
        var deptArr = deptVal.split(",");
        var $options = '';
        for (var i = 0; i < deptArr.length; i++) {
            var v = deptArr[i];
            if (v) {
                //ajax��ȡ��������
                $.ajax({
                    type: 'POST',
                    url: "?model=deptuser_dept_dept&action=getDeptName",
                    async: false,
                    data: "deptId=" + v,
                    success: function (data) {
                        $options += "<option value='" + v + "'>" + data + "</option>";
                    }
                });
            }
        }
        $("#selectedDept").append($options);
    }
});