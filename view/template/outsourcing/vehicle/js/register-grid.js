var show_page = function(page) {
	$("#registerGrid").yxgrid("reload");
};

/**
 *
 * @param useCarDate �ó�����
 * @param projectCode ��Ŀ���
 * @param suppCode ��Ӧ�̱���
 * @param suppName ��Ӧ��
 * @param carNum ���ƺ���
 * @returns {boolean}
 */
var chkExistRecords = function(useCarDate,projectCode,suppCode,suppName,carNum){
	useCarMonth = (useCarDate != '')? useCarDate.substr(0,7) : '';// ��Ӧ�·�

	var chkResult = $.ajax({
		type : "POST",
		url : "?model=outsourcing_vehicle_register&action=ajaxChkRentCarRecord",
		data : {
			useCarMonth : useCarMonth,
			projectCode : projectCode,
			suppCode : suppCode,
			carNum : carNum
		},
		async: false
	}).responseText;

	if(chkResult == 'false' || chkResult == ''){
		return true;
	}else{
		alert("�� "+useCarMonth+" �·���, ��������ĿΪ��"+projectCode+"��,��Ӧ��Ϊ��"+suppName+"���ҳ��ƺ�Ϊ ��"+carNum+"���ĵǼǻ�����Ϣ������״̬Ϊ�����л���ɣ�, ��������������صļ�¼, ������Ŀ����ͨ�����");
		return false;
	}
};

$(function() {
	$("#registerGrid").yxgrid({
		model : 'outsourcing_vehicle_register',
		param : {
			'createId' : $("#createId").val()
		},
		title : '�⳵�ǼǱ�',
		bodyAlign : 'center',
		isOpButton : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		},{
			name : 'state',
			display : '״̬',
			sortable : true,
			width : 60,
			process : function (v) {
				switch (v) {
					case '0' : return '����';break;
					case '1' : return '���ύ';break;
					case '2' : return '���';break;
					default : return '';
				}
			}
		},{
			name : 'driverName',
			display : '˾������',
			sortable : true,
			width : 70
		},{
			name : 'createName',
			display : '¼����',
			sortable : true,
			width : 80
		},{
			name : 'createTime',
			display : '¼��ʱ��',
			sortable : true,
			width : 120
		},{
			name : 'useCarDate',
			display : '�ó�����',
			sortable : true,
			width : 80
		},{
			name : 'projectName',
			display : '��Ŀ����',
			sortable : true,
			width : 200
		},{
			name : 'province',
			display : 'ʡ��',
			sortable : true,
			width : 80
		},{
			name : 'city',
			display : '����',
			sortable : true,
			width : 80
		},{
			name : 'carNum',
			display : '����',
			sortable : true,
			width : 80
		},{
			name : 'carModel',
			display : '����',
			sortable : true,
			width : 100
		},{
			name : 'startMileage',
			display : '��ʼ���',
			sortable : true,
			width : 80,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'endMileage',
			display : '�������',
			sortable : true,
			width : 80,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'effectMileage',
			display : '��Ч���',
			sortable : true,
			width : 80,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'gasolinePrice',
			display : '�ͼۣ�Ԫ/����',
			sortable : true,
			width : 80,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'gasolineKMPrice',
			display : '������Ƽ��ͷѵ��ۣ�Ԫ��',
			sortable : true,
			width : 150,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'reimbursedFuel',
			display : 'ʵ��ʵ���ͷѣ�Ԫ��',
			sortable : true,
			width : 120,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'gasolineKMCost',
			display : '������Ƽ��ͷѣ�Ԫ��',
			sortable : true,
			width : 120,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'parkingCost',
			display : 'ͣ���ѣ�Ԫ��',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'tollCost',
			display : '·�ŷѣ�Ԫ��',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'rentalCarCost',
			display : '�⳵�ѣ�Ԫ��',
			sortable : true,
			process : function (v ,row) {
				if (row.rentalPropertyCode == 'ZCXZ-02') {
					return moneyFormat2(row.shortRent ,2);
				} else {
					return '';
				}
			}
		},{
			name : 'mealsCost',
			display : '�����ѣ�Ԫ��',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'accommodationCost',
			display : 'ס�޷ѣ�Ԫ��',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'overtimePay',
			display : '�Ӱ�ѣ�Ԫ��',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'specialGas',
			display : '�����ͷѣ�Ԫ��',
			sortable : true,
			process : function (v) {
				return moneyFormat2(v ,2);
			}
		},{
			name : 'effectLogTime',
			display : '��ЧLOGʱ��',
			sortable : true
		}],

		buttonsEx : [{
			name : 'batchSub',
			text : "�����ύ",
			icon : 'add batchSubBtn',
			action : function (row,rows,idArr) {
				$(".batchSubBtn").css("background","url(./js/jquery/images/grid/load.gif) no-repeat 1px");
				setTimeout(function(){
					var disPassNum = 0;
					if(rows){
						$.each(rows,function(i,item){
							if(item.state == 1){
								disPassNum += 1;
							}
						});
					}else{
						alert("����ѡ��һ����¼��");
						$(".batchSubBtn").css("background","url(./js/jquery/images/grid/add.png) no-repeat 1px");
						return false;
					}

					if(disPassNum > 0){
						alert("�ύ��¼�к������ύ��¼,��������ԡ�");
						$(".batchSubBtn").css("background","url(./js/jquery/images/grid/add.png) no-repeat 1px");
					}else{
						var idStr = idArr.toString();
						var chkResult = $.ajax({
							type: "POST",
							url: "?model=outsourcing_vehicle_register&action=isCanBatchAdd",
							data: {
								'ids' : idStr
							},
							async: false
						}).responseText;
						chkResult = eval("("+chkResult+")");
						if(chkResult.error == '1'){
							alert(chkResult.msg);
							$(".batchSubBtn").css("background","url(./js/jquery/images/grid/add.png) no-repeat 1px");
						}else{
							$(".batchSubBtn").css("background","url(./js/jquery/images/grid/add.png) no-repeat 1px");
							if (window.confirm(("ȷ��Ҫ�ύ��ѡ�ļ�¼��?\n[ע��: ����ύ�����ݹ���Ļ����ܻ���Ҫ�ϳ���ʱ��,�����ĵȴ�,��Ҫ�رյ�ǰҳ��!]"))) {
								$(".batchSubBtn").css("background","url(./js/jquery/images/grid/load.gif) no-repeat 1px");
								$.ajax({
									type: "POST",
									url: "?model=outsourcing_vehicle_register&action=ajaxBatchSubmit",
									data: {'ids' : idStr },
									success : function(msg) {
										if (msg == 1) {
											alert('�ύ�ɹ���');
										}else {
											alert('�ύʧ�ܣ�');
										}
										$(".batchSubBtn").css("background","url(./js/jquery/images/grid/add.png) no-repeat 1px");
										$("#registerGrid").yxgrid("reload");
									}
								});
							}
						}
					}
				},200);
			}
		},{
			name : 'excelIn',
			text : "����",
			icon : 'excel',
			action : function(row) {
				showModalWin('?model=outsourcing_vehicle_register&action=toExcelIn');
			}
		},{
			name : 'excelOut',
			text : "����",
			icon : 'excel',
			action : function(row) {
				showThickboxWin('?model=outsourcing_vehicle_register&action=toExcelOut'
					+ '&createId=' + $("#createId").val()
					+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800');
			}
		}],

		menusEx : [{
			text : "�ύ",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.state == '0' || row.state == '2') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				var isCanAdd = $.ajax({
						type: "POST",
						url: "?model=outsourcing_vehicle_register&action=isCanAdd",
						data: {
							'projectId' : row.projectId,
							'useCarDate' : row.useCarDate,
							'carNum' : row.carNum
						},
						async: false
					}).responseText;

				if (isCanAdd == 0) {
					alert('��Ŀ�ϸó����Ѵ����ó�����Ϊ' + row.useCarDate + '�ļ�¼');
					return false;
				}else if(!chkExistRecords(row.useCarDate,row.projectCode,row.suppCode,row.suppName,row.carNum)){
					return false;
				}

				if (window.confirm(("ȷ��Ҫ�ύ?"))) {
					$.ajax({
						type: "POST",
						url: "?model=outsourcing_vehicle_register&action=ajaxSubmit",
						data: {'id' : row.id },
						success : function(msg) {
							if (msg == 1) {
								alert('�ύ�ɹ���');
							}else {
								alert('�ύʧ�ܣ�');
							}
							$("#registerGrid").yxgrid("reload");
						}
					});
				}
			}
        },{
			text : "���",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.state == '1') {
					var tmp = $.ajax({
									type: "POST",
									url: "?model=outsourcing_vehicle_register&action=isChange",
									data: {'allregisterId' : row.allregisterId },
									async: false,
									success : function(msg) {
									}
								}).responseText;

					if (tmp == 1) {
						return true;
					}
					return false;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showModalWin('?model=outsourcing_vehicle_register&action=toChange&id=' + row.id);
			}
        },{
			text : "���ԭ��",
			icon : 'view',
			showMenuFn : function(row) {
				if (row.changeReason) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin('?model=outsourcing_vehicle_register&action=toChangeReason&id=' + row.id
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=780&width=1000");
			}
		},{
			name : 'view',
			text : "������־",
			icon : 'view',
			showMenuFn : function(row) {
				if (row.changeReason) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
						+ row.id
						+ "&tableName=oa_outsourcing_register"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}],

		comboEx : [{
			text: "״̬",
			key: 'state',
			data : [{
				text : '����',
				value : '0'
			},{
				text : '���ύ',
				value : '1'
			},{
				text : '���',
				value : '2'
			}]
		}],

		toAddConfig : {
			toAddFn : function(p, g) {
				showModalWin("?model=outsourcing_vehicle_register&action=toAdd");
			}
		},
		toEditConfig : {
			showMenuFn : function(row) {
				if (row.state == '0' || row.state == '2') {
					return true;
				}
				return false;
			},
			toEditFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_vehicle_register&action=toEdit&id=" + get[p.keyField],'1');
				}
			}
		},
		toViewConfig : {
			toViewFn : function(p, g) {
				if (g) {
					var get = g.getSelectedRow().data('data');
					showModalWin("?model=outsourcing_vehicle_register&action=toView&id=" + get[p.keyField],'1');
				}
			}
		},
		toDelConfig : {
			showMenuFn : function(row) {
				if (row.state == '0' || row.state == '2') {
					return true;
				}
				return false;
			},
			toDelFn : function(p ,g) {
				var rowIds = g.getCheckedRowIds();
				var rowObjs = g.getCheckedRows();
				for (var i = 0 ;i < rowObjs.length ;i++) {
					if (rowObjs[i].state == '1') {
						var rowNum = $("#row" + rowObjs[i].id).children().eq(1).text();
						alert('��' + rowNum + '�е����ݲ���ɾ���������ύ�����ݲ���ɾ����');
						return false;
					}
				}
				if (rowIds[0]) {
					if (window.confirm("ȷ��Ҫɾ��?")) {
						$.ajax({
							type : "POST",
							url : "?model=outsourcing_vehicle_register&action=ajaxdeletes",
							data : {
								id : g.getCheckedRowIds().toString()
							},
							success : function(msg) {
								if (msg == 1) {
									g.reload();
									alert('ɾ���ɹ���');
								} else {
									alert('ɾ��ʧ��!');
								}
							}
						});
					}
				} else {
					alert('��ѡ��һ�м�¼��');
				}
			}
		},

		searchitems : [{
			display : "˾������",
			name : 'driverNameSea'
		},{
			display : "¼����",
			name : 'createNameSea'
		},{
			display : "¼��ʱ��",
			name : 'createTimeSea'
		},{
			display : "�ó�����",
			name : 'useCarDateSea'
		},{
			display : "��Ŀ����",
			name : 'projectNameSea'
		},{
			display : "ʡ��",
			name : 'provinceSea'
		},{
			display : "����",
			name : 'citySea'
		}]
	});
});