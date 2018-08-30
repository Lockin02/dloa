var show_page = function(page) {
	$("#contractGrid").yxsubgrid("reload");
};

$(function() {
	var shipCondition = $('#shipCondition').val();
	var typeDate = $.ajax({
		type : 'POST',
		url : "?model=projectmanagent_shipment_shipmenttype&action=getSelection",
		async : false
	}).responseText;
	var typeDate2 = [];
	if (typeDate) {
		typeDate = eval("(" + typeDate + ")");

		if (typeDate) {
			var o = {
				value : 0,
				text : '������'
			};
			typeDate2.push(o);
			for (var k = 0, kl = typeDate.length; k < kl; k++) {
				if (k == 0) {
				}
				o = {
					value : typeDate[k].value,
					text : typeDate[k].text
				};
				typeDate2.push(o);
			}
		}
	}
	$("#contractGrid").yxsubgrid({
		model : 'contract_contract_contract',
		action : 'shipmentsJson',
		title : '��������',
		subgridcheck : true,
		param : {
			'states' : '2,4',
			'shipCondition' : shipCondition,
			'DeliveryStatusArr' : 'WFH,BFFH',
			'lExaStatusArr' : '���',
			'ExaStatusArr' : "���"
		},

		title : '��������',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'contractShipInfo',
		event : {
			'afterload' : function(data, g) {
				$("#listSql").val(g.listSql);
			}
		},
		// ��չ�Ҽ��˵�
		/*
		 * event:{ row_success : function(){ var options = { max : 5, value :
		 * $(), image : 'js/jquery/raterstar/star2.gif', width : 16, height :
		 * 16, } $('.sign').rater(options); } },
		 */
//		buttonsEx : [{
//			name : 'export',
//			text : "�������ݵ���",
//			icon : 'excel',
//			action : function(row) {
//				var colId = "";
//				var colName = "";
//				$("#contractGrid_hTable").children("thead").children("tr")
//						.children("th").each(function() {
//							if ($(this).css("display") != "none"
//									&& $(this).attr("colId") != undefined) {
//								if ($(this).attr("colId") != 'test') {
//									colName += $(this).children("div").html()
//											+ ",";
//									colId += $(this).attr("colId") + ",";
//								}
//								i++;
//							}
//						})
//				window.open("?model=contract_contract_contract&action=contExportExcel&colId="
//								+ colId + "&colName=" + colName)
//			}
//		}],
		menusEx : [{
			text : '�鿴��ͬ',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=contract_contract_contract&action=toViewShipInfoTab&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ "&linkId="
						+ row.linkId);
			}
		}, {
			text : '���÷���',
			icon : 'edit',
			action : function(row) {
				showThickboxWin('?model=common_contract_allsource&action=toSetType&id='
						+ row.id
						+ "&docType=oa_contract_contract&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600');
			}
		}, {
			text : '�������',
			icon : 'lock',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���' && row.DeliveryStatus != 'YFH'
						&& row.DeliveryStatus != 'TZFH') {
					return true;
				}
				return false;
			},
			action : function(row) {
				var objCode = row.objCode;
				showOpenWin('?model=stock_lock_lock&action=toLokStock&id='
						+ row.id + "&objCode=" + objCode
						+ "&objType=oa_contract_contract&skey=" + row['skey_']);
			}
		}, {
			text : '�´﷢���ƻ�',
			icon : 'add',
			showMenuFn : function(row) {
				if ((row.dealStatus == 1 || row.dealStatus == 3)
						&& shipCondition != 1
						&& row.ExaStatus == '���'
						&& row.lExaStatus == '���'
						&& row.makeStatus != 'YXD'
						&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
					return true;
				}
				return false;
			},
			action : function(row, rows, rowIds, g) {
				var idArr = g.getSubSelectRowCheckIds(rows);
				showOpenWin("?model=stock_outplan_outplan&action=toAdd&id="
						+ row.id + "&equIds=" + idArr
						+ "&docType=oa_contract_contract" + "&skey="
						+ row['skey_']);
			}
		}, {
			text : '�´�ɹ�����',
			icon : 'add',
			showMenuFn : function(row) {
				if ((row.dealStatus == 1 || row.dealStatus == 3)
						&& row.ExaStatus == '���'
						&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
					return true;
				}
				return false;
			},
			action : function(row, rows, rowIds, g) {
				var idArr = g.getSubSelectRowCheckIds(rows);
				showOpenWin("?model=purchase_external_external&action=toAddByContract&contractId="
						+ row.id
						+ "&purchType="
						+ row.contractType
						+ "&contractName="
						+ row.contractName
						+ "&contractCode="
						+ row.contractCode
						+ "&objCode="
						+ row.objCode
						+ "&equIds="
						+ idArr
						+ "&skey="
						+ row['skey_']);
			}
		}, {
			text : '�´���������',
			icon : 'add',
			showMenuFn : function(row) {
				if ((row.dealStatus == 1 || row.dealStatus == 3)
						&& row.ExaStatus == '���'
						&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
					return true;
				}
				return false;
			},
			action : function(row, rows, rowIds, g) {
				var eqIdArr = g.getSubSelectRowCheckIds(rows);
				showOpenWin("?model=produce_apply_produceapply&action=toApply&relDocId="
						+ row.id
						+ "&equIds="
						+ eqIdArr
						+ "&relDocType=CONTRACT" + "&skey=" + row['skey_']);
			}
		}, {
			text : '�´����������',
			icon : 'add',
			showMenuFn : function(row) {
				if ((row.dealStatus == 1 || row.dealStatus == 3)
						&& row.ExaStatus == '���'
						&& (row.DeliveryStatus == 'WFH' || row.DeliveryStatus == 'BFFH')) {
					return true;
				}
				return false;
			},
			action : function(row, rows, rowIds, g) {
				var eqIdArr = g.getSubSelectRowCheckIds(rows);
				showModalWin("?model=stock_delivery_encryption&action=toAdd&sourceDocId="
						+ row.id
						+ "&equIds="
						+ eqIdArr
						+ "&skey=" + row['skey_']);
			}
		}, {
			text : "�ر�����",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.DeliveryStatus != 'TZFH') {
					return true;
				}
				return false;
			},
			action : function(row) {
				$.ajax({
					type : 'POST',
					url : '?model=common_contract_allsource&action=closeCont&skey='
							+ row['skey_'],
					data : {
						id : row.id,
						type : 'oa_contract_contract'
					},
					// async: false,
					success : function(data) {
						alert("�رճɹ�");
						show_page();
						return false;
					}
				});
			}
		}
//		, {
//			text : "�رշ�������",
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.DeliveryStatus != 'TZFH') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, rowIds, g) {
//				var idArr = g.getSubSelectRowCheckIds(rows);
//				showOpenWin("?model=stock_outplan_outplan&action=toCloseOutMat&id="
//						+ row.id + "&equIds=" + idArr
//						+ "&docType=oa_contract_contract" + "&skey="
//						+ row['skey_']);
//			}
//		}
		],

		// ����Ϣ
		colModel : [{
			name : 'status2',
			display : '�´�״̬',
			sortable : false,
			width : '20',
			align : 'center',
			// hide : aaa,
			process : function(v, row) {
				if (row.makeStatus == 'YXD') {
					return "<img src='images/icon/icon073.gif' />";
				} else {
					return "<img src='images/icon/green.gif' />";
				}
			}
		}, {
			name : 'isMeetProduction',
			display : '�Ƿ���������',
			sortable : false,
			width : 80,
			align : 'center',
			process : function(v) {
				if (v == '0') {
					return "<img src='images/icon/green.gif' title='����'/>";
				} else {
					return "<img src='images/icon/red.gif' title='������'/>";
				}
			}
		}, {
			display : '����ʱ��',
			name : 'createTime',
			sortable : true,
			hide : true,
			width : 70
		}, {
			display : '����ͨ������',
			name : 'ExaDTOne',
			sortable : true,
			width : 70
		}, {
			name : 'contractRate',
			display : '����',
			sortable : false,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_contractrate&action=updateRate&docId='
						+ row.id
						+ "&docType=oa_contract_contract"
						+ "&objCode="
						+ row.objCode
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">��ע��'
						+ "<font color='gray'>" + v + "</font>" + '</a>';
			}
		}, {
			display : '��ͬ��������״̬',
			name : 'lExaStatus',
			sortable : true,
			hide : true
		}, {
			display : '��ͬ����������Id',
			name : 'lid',
			sortable : true,
			hide : true
		}, {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'contractType',
			display : '��ͬ����',
			sortable : true,
			datacode : 'HTLX',
			width : 60
		}, {
			name : 'contractCode',
			display : '��ͬ���',
			sortable : true,
			width : 180,
			process : function(v, row) {
				if (row.isBecome != '0') {
					return "<font color = '#FF0000'>"
							+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=toViewShipInfoTab&id='
							+ row.id
							+ '&linkId='
							+ row.linkId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#FF0000'>" + v + "</font>"
							+ '</a>';
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=toViewShipInfoTab&id='
							+ row.id
							+ '&linkId='
							+ row.linkId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>"
							+ '</a>';
				}
			}
		}, {
			name : 'contractName',
			display : '��ͬ����',
			sortable : true,
			width : 150
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true,
			width : 150
		}, {
			name : 'customerId',
			display : '�ͻ�Id',
			sortable : true,
			width : 100,
			hide : true
		}, {
			// name : 'sign',
			// display : '�ȼ�',
			// width : 100,
			// process:function(v,row){
			// return "<div
			// id='sign"+row.id+"'></div><input
			// type='hidden' id='star"
			// +row.id+
			// "' value="
			// + v +
			// "
			// name='contract[sign]'></input><script>$('#sign"
			// +row.id+
			// "').rater({value:$('#star"
			// +row.id+
			// "')[0].value,image:'js/jquery/raterstar/star.gif',max:5,url:'index1.php?model=contract_contract_contract&action=editstar&id="
			// +row.id+
			// "',before_ajax: function(ret)
			// {if(confirm('Ҫ�޸ĵȼ���')==false){$('#sign"
			// +row.id+
			// "').rater(this);return
			// false;}}});</script>";
			// }
			// }, {
			name : 'ExaStatus',
			display : '����״̬',
			sortable : true,
			width : 60
		}, {
			name : 'dealStatus',
			display : '����״̬',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "δ����";
				} else if (v == '1') {
					return "�Ѵ���";
				} else if (v == '2') {
					return "���δ����";
				} else if (v == '3') {
					return "�ѹر�";
				}
			},
			width : 50
		}, {
			name : 'makeStatus',
			display : '�´�״̬',
			sortable : true,
			process : function(v) {
				if (v == 'BFXD') {
					return "�����´�";
				} else if (v == 'YXD') {
					return "���´�";
				} else {
					return "δ�´�"
				}
			},
			width : 50
		}, {
			name : 'DeliveryStatus',
			display : '����״̬',
			sortable : true,
			process : function(v) {
				if (v == 'BFFH') {
					return "���ַ���";
				} else if (v == 'YFH') {
					return "�ѷ���";
				} else if (v == 'WFH') {
					return "δ����"
				} else if (v == 'TZFH') {
					return "ֹͣ����"
				}
			},
			width : 50
		}, {
			name : 'objCode',
			display : 'ҵ����',
			sortable : true,
			width : 120,
			hide : true
		}, {
			name : 'customTypeName',
			display : '�Զ�������',
			sortable : true,
			width : 80
		}, {
			name : 'warnDate',
			display : '��������',
			sortable : true,
			width : 80
		}, {
            name : 'grandDays',
            display : '�ۼ�����',
            width : 80
        }, {
            name : 'maxShipPlanDate',
            display : 'Ԥ����ɽ�������',
            width : 80
        }],
		comboEx : [{
			text : '����',
			key : 'contractType',
			data : [{
				text : '���ۺ�ͬ',
				value : 'HTLX-XSHT'
			}, {
				text : '�����ͬ',
				value : 'HTLX-FWHT'
			}, {
				text : '���޺�ͬ',
				value : 'HTLX-ZLHT'
			}, {
				text : '�з���ͬ',
				value : 'HTLX-YFHT'
			}]
		}, {
			text : '�´�״̬',
			key : 'makeStatus',
			data : [{
				text : 'δ�´�',
				value : 'WXD'
			}, {
				text : '�����´�',
				value : 'BFXD'
			}, {
				text : '���´�',
				value : 'YXD'
			}]
		}, {
			text : '����״̬',
			key : 'DeliveryStatus',
			data : [{
				text : 'δ����',
				value : 'WFH'
			}, {
				text : '���ַ���',
				value : 'BFFH'
			}]
		}, {
			text : '�Զ�������',
			key : 'customTypeId',
			data : typeDate2
//			,value : '0'  // Edit By zengzx, bug��898
		}],
		// ���ӱ������
		subGridOptions : {
			subgridcheck : true,
			url : '?model=common_contract_allsource&action=equJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				'docType' : 'oa_contract_contract'
			}, {
				paramId : 'contractId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������
			}],
			// ��ʾ����
			afterProcess : function(data, rowDate, $tr) {
				if (data.number <= data.executedNum) {
					$tr.find("td").css("background-color", "#A1A1A1");
				}
			},
			colModel : [{
				name : 'productCode',
				display : '���ϱ��',
				process : function(v, data, rowData, $row) {
					if (data.changeTips == 1) {
						return '<img title="����༭������" src="images/changeedit.gif" />'
								+ v;
					} else if (data.changeTips == 2) {
						return '<img title="�������������" src="images/new.gif" />'
								+ v;
					} else {
						return v;
					}
				},
				width : 95
			}, {
				name : 'productName',
				width : 200,
				display : '��������',
				process : function(v, data, rowData, $row) {
					if (data.changeTips != 0) {
						if (data.isBorrowToorder == 1) {
							$row.attr("title", "������Ϊ������ת���۵�����");
							return "<img src='images/icon/icon147.gif' title='������ת��������'/>"
									+ "<font color=red>" + v + "</font>";
						} else {
							return "<font color=red>" + v + "</font>";
						}
					} else {
						if (data.isBorrowToorder == 1) {
							$row.attr("title", "������Ϊ������ת���۵�����");
							return "<img src='images/icon/icon147.gif'  title='������ת��������'/>"
									+ v;
						} else {
							return v;
						}
					}
					if (row.changeTips != 0) {
						return "<font color = 'red'>" + v + "</font>"
					} else
						return v;
				}
			}, {
				name : 'productModel',
				display : '����ͺ�'
					// ,process : function(v, data, rowData, $row, $tr) {
					// $tr.removeClass();
					// $tr.css({
					// "background" : "red"
					// });
					// $tr.find("td").css("backgroup-color", "red");
					// }
					}, {
						name : 'number',
						display : '����',
						width : 40
					}, {
						name : 'exeNum',
						display : '�������',
						width : 50,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'lockedNum',
						display : '������',
						width : 50,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'issuedShipNum',
						display : '���´﷢������',
						width : 90
					// ,process : function(v, row) {
					// return '<a href="javascript:void(0)"
					// onclick="javascript:showOpenWin'
					// +'(\'?model=contract_contract_contract&action=init&perm=view&id='
					// + row.contractId
					// +
					// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
					// + v
					// + '</a>';
					// }
					}, {
						name : 'executedNum',
						display : '�ѷ�������',
						// process : function(v, row) {
						// return '<a href="javascript:void(0)"
						// onclick="javascript:showOpenWin'
						// +'(\'?model=contract_contract_contract&action=init&perm=view&id='
						// + row.contractId
						// +
						// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						// + v
						// + '</a>';
						// },
						width : 60
					}, {
						name : 'issuedPurNum',
						display : '���´�ɹ�����',
						width : 90,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'issuedProNum',
						display : '���´���������',
						width : 90,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'isMeetProduction',
						display : '�Ƿ���������',
						sortable : false,
						width : 80,
						align : 'center',
						process : function(v,row) {
							if(row.meetProductionRemark != ''){
								if(v == '1'){
									return "<span class='red'>" + row.meetProductionRemark + "</span>";
								}else{
									return row.meetProductionRemark;
								}
							}else{
								if (v == '0') {
									return "��������";
								} else if (v == '1') {
									return "<span class='red'>����������</span>";
								}
							}
						}
					}, {
						name : 'backNum',
						display : '�˿�����',
						width : 60,
						process : function(v) {
							if (v == '') {
								return 0;
							} else
								return v;
						}
					}, {
						name : 'encryptionNum',
						display : '��������������',
						width : 90,
						process : function(v) {
							if (v == '') {
								return 0;
							} else {
								return v;
							}
						}
					}, {
						name : 'arrivalPeriod',
						display : '��׼������',
						width : 80,
						process : function(v) {
							if (v == null) {
								return '0';
							} else {
								return v;
							}
						}
					}]
		},
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��ͬ���',
			name : 'contractCode'
		}, {
			display : '��ͬ����',
			name : 'contractName'
		}, {
			display : '�ͻ�����',
			name : 'customerName'
		}, {
			display : 'ҵ����',
			name : 'objCode'
		}, {
			display : '������',
			name : 'createName'
		}],
		sortname : "id"
	});

});
/*
 * ).then(function(){ var options = { max : 5, value : 0, image :
 * 'js/jquery/raterstar/star2.gif', width : 16, height : 16, }
 * $('#star').rater(options); });
 */