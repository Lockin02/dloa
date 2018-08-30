$(function() {
    if ($("#isCategyAProject").val() == "1") $(".a_class").show();// A����Ŀ����ʾ�������
    var thisHeight = document.documentElement.clientHeight - 40;
    $('#esmactivityGrid').treegrid({
        url: '?model=engineering_activity_esmactivity&action=manageTreeJson&projectId=' + $("#projectId").val() +
            "&parentId=" + $("#parentId").val(),
        title: '��Ŀ����',
        width: '98%',
        height: thisHeight,
        nowrap: false,
        rownumbers: true,
        animate: true,
        collapsible: true,
        idField: 'id',
        treeField: 'activityName',//��������
        fitColumns: false,//�����Ӧ
        pagination: false,//��ҳ
        showFooter: true,//��ʾͳ��
        columns: [[
            {
                title: '��������',
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
                                return "<a href='javascript:void(0)' style='color:red;' title='����е�����' onclick='showOpenWin(\"?model=engineering_activity_esmactivity&action=toViewChange&id=" + row.uid + "\",1,650,1000," + row.uid + ")'>" + v + "</a>";
                            } else {
                                return "<a href='javascript:void(0)' style='color:red;' title='����е�����' onclick='showOpenWin(\"?model=engineering_activity_esmactivity&action=toViewNodeChange&id=" + row.uid + '&skey=' + row.skey_ + "\",1,650,1000," + row.uid + ")'>" + v + "</a>";
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
                title: '����ռ��',
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
                title: '��������',
                width: 70,
                formatter: formatProgress
            }, {
                field: 'waitConfirmProcess',
                title: '��ȷ�Ͻ���',
                width: 70,
                formatter: function(v) {
                    if (v) {
                        return v + " %";
                    }
                }
            }, {
                field: 'countProcess',
                title: '�ۼƽ���',
                width: 65,
                formatter: function(v) {
                    if (v) {
                        return v + " %";
                    }
                }
            }, {
                field: 'planProcess',
                title: '�ƻ�����',
                width: 65,
                formatter: function(v) {
                    if (v) {
                        return v + " %";
                    }
                }
            }, {
                field: 'diffProcess',
                title: '���Ȳ���',
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
                title: '����״̬',
                width: 60,
                formatter: function(v, row) {
                    switch (v) {
                        case '0' :
                            return '����';
                        case '1' :
                            return '<span class="blue">�ر�</span>';
                        case '2' :
                            return '<span class="red">��ͣ</span>';
                        default:
                            return row.id == 'noId' ? '' : '�����';
                    }
                }
            }, {
                field: 'planBeginDate',
                title: 'Ԥ�ƿ�ʼ',
                width: 80
            }, {
                field: 'planEndDate',
                title: 'Ԥ�ƽ���',
                width: 80
            }, {
                field: 'days',
                title: 'Ԥ�ƹ���',
                width: 60
            }, {
                field: 'workload',
                title: '������',
                width: 50,
                formatter: function(v, row) {
                    return row.isTrial == '1' ? '--' : v;
                }
            }, {
                field: 'workloadDone',
                title: '�����',
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
                                return '<span class="blue" style="font-weight:bold;" title="�����������ڣ�'
                                    + row.confirmDate + '\n�����ˣ�' + row.confirmName + '\n����ֵ��' + row.confirmDays + '">' + v + '</span>';
                            } else {
                                return v;
                            }
                        }
                    }
                }
            }, {
                field: 'workloadUnitName',
                title: '��λ',
                width: 50
            }, {
                field: 'workContent',
                title: '��������',
                width: 200
            }
        ]]
    });
});

//ԭҳ��ˢ�·���
function show_page() {
    reload();
}

//ˢ��
function reload() {
    $('#esmactivityGrid').treegrid('reload');
}

//�������
function editActivity() {
    var node = $('#esmactivityGrid').treegrid('getSelected');
    if (node) {
        var canChange = true;
        //�ж���Ŀ�Ƿ���Խ��б��
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

        //������ɱ��
        if (canChange == false) {
            alert('��Ŀ��������У���ȴ�������ɺ��ٽ��б��������');
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
        alert('��ѡ��һ������');
    }
}

//ɾ������
function removeActivity() {
    var node = $('#esmactivityGrid').treegrid('getSelected');
    if (node) {
        if (node.isTrial == 1) {
            alert("��������ɾ����");
        } else {
            var canChange = true;
            //�ж���Ŀ�Ƿ���Խ��б��
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

            //������ɱ��
            if (canChange == false) {
                alert('��Ŀ��������У���ȴ�������ɺ��ٽ��б��������');
            } else {
                var nodeId; //��������ԭʼid �������ж������Ƿ������־
                var changeId = "";//������뵥id
                if (node.changeId) {
                    nodeId = node.activityId;
                    changeId = node.changeId;
                    //���ڱ��id
                    if (!node.activityId) {
                        //�ж���ʾ��Ϣ
                        if ((node.rgt - node.lft) == 1) {
                            var alertText = 'ȷ��Ҫɾ����';
                        } else {
                            var alertText = 'ɾ�������񣬻Ὣ�¼�����һ��ɾ����ȷ��Ҫִ�д˲�����';
                        }
                        //ȷ��
                        if (confirm(alertText)) {
                            //�첽ɾ������
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
                                        alert('ɾ���ɹ�');
                                        reload();
                                    } else {
                                        alert('ɾ��ʧ��');
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

                //�ж������Ƿ����ɾ��
                $.ajax({
                    type: "POST",
                    url: "?model=engineering_worklog_esmworklog&action=checkActAndParentLog",
                    data: {
                        activityId: nodeId
                    },
                    async: false,
                    success: function(data) {
                        if (data == "1") {
                            alert('����������¼������Ѿ�������־��Ϣ�����ܽ���ɾ��');
                        } else {
                            //�ж���ʾ��Ϣ
                            if ((node.rgt - node.lft) == 1) {
                                var alertText = 'ȷ��Ҫɾ����';
                            } else {
                                var alertText = 'ɾ�������񣬻Ὣ�¼�����һ��ɾ����ȷ��Ҫִ�д˲�����';
                            }
                            //ȷ��
                            if (confirm(alertText)) {
                                //�첽ɾ������
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
                                            alert('ɾ���ɹ�');
                                            reload();
                                        } else {
                                            alert('ɾ��ʧ��');
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
        alert('��ѡ��һ������');
    }
}

//ȡ��ѡ��
function cancelSelect() {
    $('#esmactivityGrid').treegrid('unselectAll');
}

//�������� -- ���
function addActivity() {
    var canChange = true;
    //�ж���Ŀ�Ƿ���Խ��б��
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

    //������ɱ��
    if (canChange == false) {
        alert('��Ŀ��������У���ȴ�������ɺ��ٽ��б��������');
    } else {
        var node = $('#esmactivityGrid').treegrid('getSelected');
        if (node) {
            if (node.isTrial == 1) {
                alert('�����ڴ���������������');
            } else {
                if (node.process * 1 > 0 && (node.rgt - node.lft) == 1) {
                    alert('�����Ѿ����������־�����������¼�����');
                    return false;
                }
                if (node.changeId) {
                    //���ѡ������û������������ʾ
                    if ((node.rgt - node.lft) == 1) {
                        if (confirm("������һ���¼�����Ὣ��" + node.activityName + "�����������ת���������У�ȷ�Ͻ�����")) {
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
                    //���ѡ������û������������ʾ
                    if ((node.rgt - node.lft) == 1) {
                        if (confirm("������һ���¼�����Ὣ��" + node.activityName + "����������ݡ�Ԥ��ת���������У�ȷ�Ͻ�����")) {
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

//��������
function setWorkloadDone() {
    var node = $('#esmactivityGrid').treegrid('getSelected');
    if (node) {
        var canChange = true;
        //�ж���Ŀ�Ƿ���Խ��б��
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

        //�ж��Ƿ������������
        if (node.isTrial == 1) {
            alert('���ܶ���������Ŀ������������д˲�����');
        } else {
            if ((node.rgt - node.lft) == 1) {
                if (node.isChanging == "0") {
                    if (node.changeId) {
                        alert('��Ŀ����У���ȴ�������ɺ��ٽ��б��������');
                    } else {
                        showOpenWin("?model=engineering_activity_esmactivity&action=toEditWorkloadDone&id="
                        + node.id
                        + "&skey=" + node.skey_, 1, 400, 800, node.id);
                    }
                } else {
                    alert('��Ŀ����У���ȴ�������ɺ��ٽ��б��������');
                }
            } else {
                alert('������������������');
            }
        }
    } else {
        alert('��ѡ��һ������');
    }
}

//��ͣ����
function stopActivity() {
    var node = $('#esmactivityGrid').treegrid('getSelected');
    if (node) {
        var canChange = true;
        //�ж���Ŀ�Ƿ���Խ��б��
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

        //�ж��Ƿ������������
        if (node.isTrial == 1) {
            alert('���ܶ���������Ŀ������������д˲�����');
        } else {
            if ((node.rgt - node.lft) == 1) {
                if (node.isChanging == "0") {
                    if (node.changeId) {
                        alert('��Ŀ����У���ȴ�������ɺ��ٽ��в�����');
                    } else {
                        if (node.status == "0") {
                            if (confirm('ȷ��Ҫ��ͣ������')) {
                                $.ajax({
                                    type: "POST",
                                    url: "?model=engineering_activity_esmactivity&action=stop",
                                    data: {
                                        id: node.id
                                    },
                                    async: false,
                                    success: function(data) {
                                        if (data == "1") {
                                            alert('��ͣ�ɹ�');
                                            reload();
                                        } else {
                                            alert('��ͣʧ��');
                                        }
                                    }
                                });
                            }
                        } else {
                            alert('����״̬���������ִ����ͣ������');
                        }
                    }
                } else {
                    alert('��Ŀ����У���ȴ�������ɺ��ٽ��в�����');
                }
            } else {
                alert('���������ܽ�����ͣ������');
            }
        }
    } else {
        alert('��ѡ��һ������');
    }
}

//�ָ�����
function restartActivity() {
    var node = $('#esmactivityGrid').treegrid('getSelected');
    if (node) {
        var canChange = true;
        //�ж���Ŀ�Ƿ���Խ��б��
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

        //�ж��Ƿ������������
        if (node.isTrial == 1) {
            alert('���ܶ���������Ŀ������������д˲�����');
        } else {
            if ((node.rgt - node.lft) == 1) {
                if (node.isChanging == "0") {
                    if (node.changeId) {
                        alert('��Ŀ����У���ȴ�������ɺ��ٽ��в�����');
                    } else {
                        if (node.status != "2") {
                            alert('��������ͣ״̬');
                        } else {
                            if (confirm('ȷ��Ҫ�ָ�������')) {
                                $.ajax({
                                    type: "POST",
                                    url: "?model=engineering_activity_esmactivity&action=restart",
                                    data: {
                                        id: node.id
                                    },
                                    async: false,
                                    success: function(data) {
                                        if (data == "1") {
                                            alert('�ָ��ɹ�');
                                            reload();
                                        } else {
                                            alert('�ָ�ʧ��');
                                        }
                                    }
                                });
                            }
                        }
                    }
                } else {
                    alert('��Ŀ����У���ȴ�������ɺ��ٽ��в�����');
                }
            } else {
                alert('���������ܽ��лָ�������');
            }
        }
    } else {
        alert('��ѡ��һ������');
    }
}