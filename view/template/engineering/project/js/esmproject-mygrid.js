var show_page = function() {
	$("#esmprojectGrid").yxgrid("reload");
};

$(function() {
	$("#esmprojectGrid").yxgrid({
		model: 'engineering_project_esmproject',
		action: 'myProjectListPageJson',
		title: '�ҵĹ�����Ŀ',
		isDelAction: false,
		isAddAction: false,
		isViewAction: false,
		showcheckbox: false,
		isOpButton: false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'projectName',
			display: '��Ŀ����',
			sortable: true,
			width: 140,
			process: function(v, row) {
				if (row.isManager == "1") {
					return "<span style='color:blue' title='�Ҹ������Ŀ'>" + v + "</span>";
				} else {
					return v;
				}
			}
		}, {
			name: 'projectCode',
			display: '��Ŀ���',
			sortable: true,
			width: 120,
			process: function(v, row) {
				if (row.status == 'GCXMZT01' && row.ExaStatus == '��������') {
					return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
				} else {
					switch (row.status) {
						case 'GCXMZT01' :
							return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=editTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
						case 'GCXMZT02' :
							return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=manageTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
						case 'GCXMZT03' :
						case 'GCXMZT04' :
						case 'GCXMZT05' :
                        case 'GCXMZT00' :
                            return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_esmproject&action=viewTab&id=" + row.id + '&skey=' + row.skey_ + "\")'>" + v + "</a>";
						default :
							return v;
					}
				}
			}
		}, {
			name: 'newProLineName',
			display: 'ִ������',
			sortable: true,
			width: 80
		}, {
			name: 'officeId',
			display: '����ID',
			sortable: true,
			hide: true
		}, {
			name: 'officeName',
			display: '����',
			width: 70,
			sortable: true
		}, {
			name: 'country',
			display: '����',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'province',
			display: 'ʡ��',
			sortable: true,
			width: 70
		}, {
			name: 'city',
			display: '����',
			sortable: true,
			width: 70,
			hide: true
		}, {
			name: 'attributeName',
			display: '��Ŀ����',
			width: 70,
			process: function(v, row) {
				switch (row.attribute) {
					case 'GCXMSS-01' :
						return "<span class='red'>" + v + "</span>";
					case 'GCXMSS-02' :
						return "<span class='blue'>" + v + "</span>";
					case 'GCXMSS-03' :
						return "<span class='green'>" + v + "</span>";
					default :
						return v;
				}
			}
		}, {
			name: 'categoryName',
			display: '��Ŀ���',
			sortable: true,
			width: 50
		}, {
			name: 'contractTypeName',
			display: 'Դ������',
			sortable: true,
			hide: true
		}, {
			name: 'contractId',
			display: '������ͬid',
			sortable: true,
			hide: true
		}, {
			name: 'contractCode',
			display: '������ͬ���(Դ�����)',
			sortable: true,
			width: 160,
			hide: true
		}, {
			name: 'rObjCode',
			display: 'ҵ����',
			sortable: true,
			width: 120,
			hide: true
		}, {
			name: 'customerId',
			display: '�ͻ�id',
			sortable: true,
			hide: true
		}, {
			name: 'customerName',
			display: '�ͻ�����',
			sortable: true,
			hide: true
		}, {
			name: 'depName',
			display: '��������',
			sortable: true,
			hide: true
		}, {
			name: 'planBeginDate',
			display: 'Ԥ����������',
			sortable: true,
			width: 80,
			hide: true
		}, {
			name: 'planEndDate',
			display: 'Ԥ�ƽ�������',
			sortable: true,
			width: 80
		}, {
			name: 'actBeginDate',
			display: 'ʵ�ʿ�ʼʱ��',
			sortable: true,
			width: 80
		}, {
			name: 'actEndDate',
			display: 'ʵ�����ʱ��',
			sortable: true,
			width: 80
		}, {
			name: 'projectProcess',
			display: '���̽���',
			sortable: true,
			process: function(v, row) {
				if (row.id == 'noId') return '';
				return v + ' %';
			},
			width: 70
		}, {
			name: 'statusName',
			display: '��Ŀ״̬',
			sortable: true,
			width: 70
		}, {
			name: 'ExaStatus',
			display: '����״̬',
			sortable: true,
			width: 70
		}, {
			name: 'ExaDT',
			display: '��������',
			sortable: true,
			hide: true,
			width: 80
		}, {
			name: 'updateTime',
			display: '�������',
			sortable: true,
			width: 120
		}],
		lockCol: ['projectName', 'projectCode'],//����������
		toEditConfig: {
			showMenuFn: function(row) {
				return (row.ExaStatus == "���ύ" || row.ExaStatus == "���");
			},
			toEditFn: function(p, g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showModalWin("?model=engineering_project_esmproject&action=editTab&id=" + row.id + "&skey=" + row.skey_, 1, row.id);
			}
		},
        buttonsEx : [{
            name : 'import',
            text : '<a style="color: red" href="#" title="��Ŀ�����ֲ�V2.1" taget="_blank" id="fileId" onclick="window.open(\'upfile/��Ŀ�����ֲ�V2.1.pdf\')">��Ŀ�����ֲ�V2.1</a>',
            icon : 'view',
            action : function(row) {

            }
        }],
		// ��չ�Ҽ��˵�
		menusEx: [{
			text: '�鿴��Ŀ',
			icon: 'view',
			showMenuFn: function(row) {
				return row.status == 'GCXMZT00' || row.status == 'GCXMZT03' ||
                    row.status == 'GCXMZT05' || (row.status == 'GCXMZT01' && row.ExaStatus == '��������');
			},
			action: function(row) {
				showModalWin("?model=engineering_project_esmproject&action=viewTab&id="
				+ row.id
				+ "&skey=" + row.skey_, 1, row.id);
			}
		}, {
			text: '������Ŀ',
			icon: 'view',
			showMenuFn: function(row) {
				return row.status == 'GCXMZT02' || row.status == 'GCXMZT04';
			},
			action: function(row) {
				showModalWin("?model=engineering_project_esmproject&action=manageTab&id=" + row.id + "&skey=" + row.skey_, 1, row.id);
			}
		}, {
			text: '�ύ����',
			icon: 'add',
			showMenuFn: function(row) {
				return row.ExaStatus == "���ύ" || row.ExaStatus == "���";
			},
			action: function(row) {
				if (row) {
					if (row.outsourcing == "") {
						alert('�벹����Ŀ�ſ��еı�����Ϣ�����ύ����');
						return false;
					}
					if (row.budgetAll * 1 == 0) {
						alert('��Ŀ��Ԥ�㲻��Ϊ0��������д��ĿԤ��');
						return false;
					}
					$.ajax({
						type: "POST",
						url: "?model=engineering_project_esmproject&action=submitCheck",
						data: {id: row.id},
						async: false,
						success: function(data) {
							data = eval("(" + data + ")");
							if (data.pass == "1") {
								showThickboxWin('controller/engineering/project/ewf_index.php?actTo=ewfSelect&billId='
								+ row.id + "&billArea=" + data.rangeId
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
							} else {
								alert(data.msg);
							}
						}
					});
				} else {
					alert("��ѡ��һ������");
				}
			}
//		}, {
//            text: '�ύ�깤����',
//            icon: 'add',
//            showMenuFn: function(row) {
//                return row.ExaStatus == "���ύ" || row.ExaStatus == "���";
//            },
//            action: function(row) {
//                if (row) {
//                    if (row.outsourcing == "") {
//                        alert('�벹����Ŀ�ſ��еı�����Ϣ�����ύ����');
//                    } else {
//                        $.ajax({
//                            type: "POST",
//                            url: "?model=engineering_project_esmproject&action=getRangeId",
//                            data: {projectId: row.id},
//                            async: false,
//                            success: function(data) {
//                                if (data == "") {
//                                    alert('û��ƥ�䵽��Ŀ���������벹�������Ϣ�����ύ');
//                                } else {
//                                    showThickboxWin('controller/engineering/project/ewf_index_completed.php?actTo=ewfSelect&billId='
//                                        + row.id + "&billArea=" + data
//                                        + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
//                                }
//                            }
//                        });
//                    }
//                } else {
//                    alert("��ѡ��һ������");
//                }
//            }
        }, {
			text: '�������',
			icon: 'view',
			showMenuFn: function(row) {
				return row.ExaStatus != "���ύ";
			},
			action: function(row) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_esm_project&pid="
					+ row.id
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600");
				}
			}
		}, {
			text: '��ͣ��Ŀ',
			icon: 'delete',
			showMenuFn: function(row) {
				return row.status == "GCXMZT02";
			},
			action: function(row) {
				if (row) {
					showThickboxWin("?model=engineering_project_esmproject&action=toStop&id="
					+ row.id + "&skey=" + row.skey_
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
				}
			}
		}, {
			text: 'ȡ����ͣ',
			icon: 'add',
			showMenuFn: function(row) {
				return row.status == "GCXMZT05";
			},
			action: function(row) {
				if (row) {
					showThickboxWin("?model=engineering_project_esmproject&action=toCancelStop&id="
					+ row.id + "&skey=" + row.skey_
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
				}
			}
		}, {
			text: '�����Ŀ',
			icon: 'edit',
			showMenuFn: function(row) {
				return row.status == "GCXMZT02";
			},
			action: function(row) {
				if (row) {
					showThickboxWin("?model=engineering_project_esmproject&action=toFinish&id="
					+ row.id + "&skey=" + row.skey_
					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
				}
			}
		}, {
			text: '�ر���Ŀ',
			icon: 'delete',
			showMenuFn: function(row) {
				return row.status == "GCXMZT04" || row.status == "GCXMZT00" || row.status == "GCXMZT01";
			},
			action: function(row) {
				if (row) {
					showOpenWin("?model=engineering_close_esmclose&action=toClose&projectId="
					+ row.id + "&skey=" + row.skey_);
				}
			}
		}],
		searchitems: [{
			display: '���´�',
			name: 'officeName'
		}, {
			display: '��Ŀ���',
			name: 'projectCodeSearch'
		}, {
			display: '��Ŀ����',
			name: 'projectName'
		}, {
			display: '��Ŀ����',
			name: 'managerName'
		}, {
			display: 'ҵ����',
			name: 'rObjCodeSearch'
		}, {
			display: '������ͬ��',
			name: 'contractCodeSearch'
		}, {
			display: '��ʱ��ͬ��',
			name: 'contractTempCodeSearch'
		}],
		// Ĭ�������ֶ���
		sortname: "c.updateTime",
		// Ĭ������˳�� ����DESC ����ASC
		sortorder: "DESC",
		// ����״̬���ݹ���
		comboEx: [{
			text: "����״̬",
			key: 'ExaStatus',
			type: 'workFlow'
		}, {
			text: "��Ŀ״̬",
			key: 'status',
			datacode: 'GCXMZT'
		}]
	});
});