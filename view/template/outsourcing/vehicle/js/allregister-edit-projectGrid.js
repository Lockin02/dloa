var show_page = function (page) {
	$("#allregisterGrid").yxgrid("reload");
};

$(function () {
	$("#allregisterGrid").yxgrid({
		model: 'outsourcing_vehicle_allregister',
		param: {
			'projectId': $("#projectId").val()
		},
		title: '�⳵�Ǽǻ���',
		bodyAlign: 'center',
		showcheckbox: false,
		isAddAction: false,
		isDelAction: false,
		isOpButton: false,
		//����Ϣ
		colModel: [{
			display: 'id',
			name: 'id',
			sortable: true,
			hide: true
		}, {
			name: 'state',
			display: '״̬',
			sortable: true,
			width: 60,
			process: function (v) {
				switch (v) {
				case '0':
					return 'δ�ύ';
					break;
				case '1':
					return '������';
					break;
				case '2':
					return '�������';
					break;
				case '3':
					return '���';
					break;
				default:
					return '';
				}
			}
		}, {
			name: 'useCarDate',
			display: '�ó�ʱ��',
			sortable: true,
			process: function (v) {
				return v.substr(0, 7);
			},
			width: 60
		}, {
			name: 'projectCode',
			display: '��Ŀ���',
			sortable: true,
			width: 200
		}, {
			name: 'projectName',
			display: '��Ŀ����',
			sortable: true,
			width: 200
		}, {
			name: 'projectManager',
			display: '��Ŀ����',
			sortable: true,
			width: 160
		}, {
			name: 'actualUseDay',
			display: 'ʵ���ó�����',
			sortable: true,
			width: 80
		}, {
			name: 'effectMileage',
			display: '��Ч���',
			sortable: true,
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'rentalCarCost',
			display: '�⳵�ѣ�Ԫ��',
			sortable: true,
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'reimbursedFuel',
			display: 'ʵ��ʵ���ͷѣ�Ԫ��',
			width: 120,
			type: 'statictext',
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'gasolineKMCost',
			display: '������Ƽ��ͷѣ�Ԫ��',
			width: 120,
			type: 'statictext',
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'parkingCost',
			display: 'ͣ���ѣ�Ԫ��',
			sortable: true,
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'tollCost',
			display: '·�ŷѣ�Ԫ��',
			sortable: true,
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'mealsCost',
			display: '�����ѣ�Ԫ��',
			sortable: true,
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'accommodationCost',
			display: 'ס�޷ѣ�Ԫ��',
			sortable: true,
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'overtimePay',
			display: '�Ӱ�ѣ�Ԫ��',
			sortable: true,
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'specialGas',
			display: '�����ͷѣ�Ԫ��',
			sortable: true,
			process: function (v) {
				return moneyFormat2(v, 2);
			}
		}, {
			name: 'allCost',
			display: '�ܷ��ã�Ԫ��',
			sortable: true,
			process: function (v, row) {
				var sum = parseFloat(row.rentalCarCost ? row.rentalCarCost : 0)
						+ parseFloat(row.reimbursedFuel ? row.reimbursedFuel : 0)
						+ parseFloat(row.gasolineKMCost ? row.gasolineKMCost : 0)
						+ parseFloat(row.parkingCost ? row.parkingCost : 0)
						+ parseFloat(row.mealsCost ? row.mealsCost : 0)
						+ parseFloat(row.accommodationCost ? row.accommodationCost : 0)
						+ parseFloat(row.overtimePay ? row.overtimePay : 0)
						+ parseFloat(row.specialGas ? row.specialGas : 0);
				return moneyFormat2(sum, 2);
			}
		}, {
			name: 'effectLogTime',
			display: '��ЧLOGʱ��',
			sortable: true
		}],

		menusEx: [{
			text: "�ύ",
			icon: 'add',
			showMenuFn: function (row) {
				if (row.state == '0' || row.state == '3') {
					var nowData = new Date();
					var nowYear = nowData.getFullYear(); //��ȡ��
					var nowMonth = nowData.getMonth() + 1; //��ȡ��
					var year = parseInt(row.useCarDate.substr(0, 4));
					var month = parseInt(row.useCarDate.substr(5, 2));
					if (nowYear < year) {
						return false;
					} else if (nowMonth <= month && ��nowYear == year) {
						return false;
					}
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				var rs = $.ajax({
					type: "POST",
					url: "?model=outsourcing_vehicle_allregister&action=isCanSubmit",
					data: {
						'id': row.id
					},
					async: false
				}).responseText;
				if (rs == 'true') {
					$.ajax({
						type: "POST",
						url: "?model=outsourcing_vehicle_rentalcar&action=getOfficeInfoForId",
						data: {
							'projectId': row.projectId
						},
						async: false,
						success: function (data) {
							if (data) {
								showThickboxWin('controller/outsourcing/vehicle/ewf_register.php?actTo=ewfSelect&billId=' + row.id +
									"&billArea=" + data +
									"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
							} else {
								showThickboxWin('controller/outsourcing/vehicle/ewf_register.php?actTo=ewfSelect&billId=' + row.id +
									'&billDept=' + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
							}
						}
					});
				} else {
					alert('���ּ�¼û�й����ĺ�ͬ��');
				}
			}
		}, {
			text: "���",
			icon: 'delete',
			showMenuFn: function (row) {
				if (row.state == '0' || row.state == '3') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin("?model=outsourcing_vehicle_allregister&action=toBack&id=" + row.id);
				}
			}
		}, {
			text: "�����뱨��",
			icon: 'add',
			showMenuFn: function (row) {
				if (row.state == '2' && row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showModalWin("?model=outsourcing_vehicle_allregister&action=toPayment&id=" + row.id);
				}
			}
		}, {
			name: 'aduit',
			text: '�������',
			icon: 'view',
			showMenuFn: function (row) {
				if (row.ExaStatus == '���' || row.ExaStatus == '���' || row.ExaStatus == '��������') {
					return true;
				}
				return false;
			},
			action: function (row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_outsourcing_allregister&pid=" + row.id +
						"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
				}
			}
		}],

		comboEx: [{
			text: "״̬",
			key: 'state',
			data: [{
				text: 'δ�ύ',
				value: '0'
			}, {
				text: '������',
				value: '1'
			}, {
				text: '�������',
				value: '2'
			}, {
				text: '���',
				value: '3'
			}]
		}],

		toEditConfig: {
			showMenuFn: function (row) {
				if (row.state == '0' || row.state == '3') {
					return true;
				}
				return false;
			},
			toEditFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_vehicle_allregister&action=toEdit&id=" + get[p.keyField], '1');
				}
			}
		},
		toViewConfig: {
			toViewFn: function (p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_vehicle_allregister&action=toView&id=" + get[p.keyField], '1');
				}
			}
		},
		searchitems: [{
			display: "�ó�ʱ��",
			name: 'useCarDateSea'
		}, {
			display: "ʵ���ó�����",
			name: 'actualUseDaySea'
		}]
	});

});