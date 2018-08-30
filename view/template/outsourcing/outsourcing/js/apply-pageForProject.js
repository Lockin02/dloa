var show_page = function () {
    $("#grid").yxgrid("reload");
};

$(function () {
    // ������
    var createId = $("#createId").val();

    $("#grid").yxgrid({
        model: 'outsourcing_outsourcing_apply',
        param: {projectId: $("#projectId").val()},
        title: '�������',
        isAddAction: false,
        isDelAction: false,
        isEditAction: false,
        isViewAction: false,
        isOpAction: false,
        showcheckbox: false,
        //����Ϣ
        colModel: [{
            display: 'id',
            name: 'id',
            sortable: true,
            hide: true
        }, {
            name: 'applyCode',
            display: '������뵥��',
            width: 150,
            sortable: true,
            process: function (v, row) {
                return "<a href='#' onclick='showModalWin(\"?model=outsourcing_outsourcing_apply&action=toView&id=" + row.id + "\")'>" + v + "</a>";
            }
        }, {
            name: 'projecttName',
            display: '��Ŀ����',
            width: 120,
            sortable: true,
            hide: true
        }, {
            name: 'projectCode',
            display: '��Ŀ���',
            width: 120,
            sortable: true,
            hide: true
        }, {
            name: 'projectClientType',
            display: '��Ŀ�ͻ�����',
            width: 120,
            sortable: true,
            hide: true
        }, {
            name: 'projectCharge',
            display: '��Ŀ������',
            width: 120,
            sortable: true,
            hide: true
        }, {
            name: 'outType',
            display: '�����ʽ',
            width: 90,
            sortable: true,
            align: 'center',
            process: function (v, row) {
                if (row.outType == 1) {
                    return "����";
                } else if (row.outType == 2) {
                    return "�ְ�";
                } else {
                    return "��Ա����/������";
                }
            }
        }, {
            name: 'createName',
            display: '������',
            width: 100,
            align: 'center',
            sortable: true
        }, {
            name: 'createTime',
            display: '��������',
            width: 120,
            sortable: true
        }, {
            name: 'state',
            display: '���״̬',
            sortable: true,
            width: 100,
            align: 'center',
            process: function (v) {
                switch (v) {
                    case '0':
                        return 'δ�ύ';
                    case '1':
                        return '������';
                    case '2':
                        return '�Ѵ��';
                    case '3':
                        return '������';
                    case '4':
                        return '�������';
                    case '5':
                        return '�ر�';
                    case '6':
                        return 'ʵʩ��';
                    default:
                        return '';
                }
            }
        }, {
            name: 'exaStatus',
            display: '����״̬',
            sortable: true,
            width: 60,
            process: function (v, row) {
                if (row.exaStatus && row.exaStatus != 0) {
                    return row.exaStatus;
                } else {
                    return "δ�ύ";
                }
            },
            hide: true
        }],
        menusEx: [{
            text: "�鿴",
            icon: 'view',
            action: function (row) {
                showModalWin("?model=outsourcing_outsourcing_apply&action=toView&id=" + row.id, 1);
            }
        }, {
            text: "�༭",
            icon: 'edit',
            showMenuFn: function (row) {
                return row.createId == createId && row.state == '0'
            },
            action: function (row) {
                showModalWin("?model=outsourcing_outsourcing_apply&action=toEdit&id=" + row.id, 1);
            }
        }, {
            text: "�ύ����",
            icon: 'add',
            showMenuFn: function (row) {
                return row.createId == createId && row.state == '0';
            },
            action: function (row) {
            	//������
            	var isExemptReview = 0;
            	
            	//�������������
            	$.ajax({
            		url: "?model=outsourcing_outsourcing_apply&action=isExemptReview",
            		type: "POST",
                    async: false,
            		data:  "apply[projectId]="+row.projectId,
            		success: function(data) {
            			if(data == "1") {
            				isExemptReview = 1;
            			    $.ajax({
                        		url: "?model=outsourcing_outsourcing_apply&action=exemptReviewByList",
                        		type: "POST",
                                async: false,
                        		data:  "apply[id]="+row.id,
                        		success: function(data) {
                        			if(data == 1) {
                        				alert("������ɣ�");
                        				show_page();
                        			}
                        		}
                        	});
            			}
            		}
            	});
            	
            	if(isExemptReview == 0) {
                    $.ajax({
                        type: "POST",
                        url: "?model=engineering_officeinfo_range&action=getOfficeInfoForId",
                        data: {'provinceId': row.provinceId},
                        async: false,
                        success: function (data) {
                            if (data != '') {
                                var proObj = eval("(" + data + ")");
                                showThickboxWin('controller/outsourcing/outsourcing/ewf_index.php?actTo=ewfSelect&billId='
                                    + row.id + "&billArea=" + proObj.id
                                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                            } else {
                                showThickboxWin('controller/outsourcing/outsourcing/ewf_index.php?actTo=ewfSelect&billId=' + row.id + '&flowMoney=0&billDept=' + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
                            }
                        }
                    });
            	}
            }
        }, {
            name: 'aduit',
            text: '�������',
            icon: 'view',
            showMenuFn: function (row) {
                return row.exaStatus == '���' || row.exaStatus == '���' || row.exaStatus == '��������';
            },
            action: function (row, rows, grid) {
                if (row) {
                    showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_apply&pid="
                        + row.id
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                }
            }
        }, {
            text: 'ɾ��',
            icon: 'delete',
            showMenuFn: function (row) {
                return row.createId == createId && (row.exaStatus == '0' || row.exaStatus == '���');
            },
            action: function (row) {
                if (window.confirm(("ȷ��Ҫɾ��?"))) {
                    $.ajax({
                        type: "POST",
                        url: "?model=outsourcing_outsourcing_apply&action=ajaxdeletes",
                        data: {
                            id: row.id
                        },
                        success: function (msg) {
                            if (msg == 1) {
                                alert('ɾ���ɹ���');
                                $("#applyGrid").yxgrid("reload");
                            }
                        }
                    });
                }
            }
        }, {
            name: 'aduit',
            text: '�鿴���ԭ��',
            icon: 'view',
            showMenuFn: function (row) {
                return row.state == '2';
            },
            action: function (row, rows, grid) {
                if (row) {
                    showThickboxWin("?model=outsourcing_outsourcing_apply&action=toBackView&id="
                        + row.id
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                }
            }
        }, {
            name: 'aduit',
            text: '�鿴�ر�ԭ��',
            icon: 'view',
            showMenuFn: function (row) {
                return row.state == '5';
            },
            action: function (row, rows, grid) {
                if (row) {
                    showThickboxWin("?model=outsourcing_outsourcing_apply&action=toCloseView&id="
                        + row.id
                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
                }
            }
        }],
        searchitems: [{
            display: '���뵥��',
            name: 'applyCodeSearch'
        }, {
            display: '��Ŀ����',
            name: 'projecttNameLike'
        }, {
            display: '��Ŀ���',
            name: 'projectCodeLike'
        }, {
            display: '��Ŀ������',
            name: 'projectCharge'
        }, {
            display: '������',
            name: 'createNameLike'
        }, {
            display: '��������',
            name: 'createTimeLike'
        }]
    });
});