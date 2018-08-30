var show_page = function(page) {
	$("#pagebyequGrid").yxsubgrid("reload");
};
function planRate(id){
	showThickboxWin('?model=stock_outplan_outplanrate&action=page&id='
			+ id
			+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800');
}
$(function() {
	var docTypeArr = $("#docTypeArr").val();
	$("#pagebyequGrid").yxsubgrid({
		model : 'stock_outplan_outplan',
		param : {
			"docStatusArr" : docTypeArr
		},
		title : '�����ƻ�',
		showcheckbox : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		customCode : 'pagebyequGrid',
		event : {
			afterloaddata : function(e, data) {
				if (data) {
					for (var i = 0; i < data.collection.length; i++) {
						if(data.collection[i].changeTips==1){
							$('#row' + data.collection[i].id).css('color', 'red');
						}
					}
				}
			}
		},
		comboEx : [{
			text : '��������',
			key : 'docType',
			data : [{
				text : '��ͬ����',
				value : 'oa_contract_contract'
			}, {
				text : '���÷���',
				value : 'oa_borrow_borrow'
			}, {
				text : '���ͷ���',
				value : 'oa_present_present'
			}, {
				text : '��������',
				value : 'oa_contract_exchangeapply'
			}]
		}, {
			text : '����״̬',
			key : 'docStatus',
			data : [{
				text : 'δ����',
				value : 'WFH'
			}, {
				text : '���ַ���',
				value : 'BFFH'
			}, {
				text : '�����',
				value : 'YWC'
			}, {
				text : '�ѹر�',
				value : 'YGB'
			}]
		}, {
			text : '���ܹ���ɽ���',
			key : 'dongleRate',
			data : [{
				text : 'δ���',
				value : '0'
			}, {
				text : '�����',
				value : '1'
			}]
		}],
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'dongleRate',
			display : '���ܹ���ɽ���',
			sortable : false,
			width : '20',
			align : 'center',
			// hide : aaa,
			process : function(v, row) {
				if (row.dongleRate == 0) {
					return "<img src='images/icon/icon057.gif' />";
				}else{
					return "";
				}
			}
		}, {
			name : 'status2',
			display : 'ִ��״̬��ʶ',
			sortable : false,
			width : '20',
			align : 'center',
			// hide : aaa,
			process : function(v, row) {
				if (row.status == 'YZX') {
					return "<img src='images/icon/icon073.gif' />";
				} else {
					return "<img src='images/icon/green.gif' />";
				}
			}
		}, {
			name : 'planCode',
			display : '�ƻ����',
			width : 90,
			process : function(v, row) {
				if (row.changeTips == 1) {
					return "<font class='changeTipsRow' color=red>" + v
							+ "</font>";
				} else {
					return v;
				}
			},
			sortable : true
		}, {
			name : 'week',
			display : '�ܴ�',
			width : 50,
			hide : true,
			sortable : true
		}, {
			name : 'docApplicant',
			display : 'Դ��������',
			width : 80,
			sortable : true
		}, {
			name : 'docApplicantId',
			display : 'Դ��������Id',
			width : 50,
			hide : true,
			sortable : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			width : 150,
			sortable : true
		}, {
			name : 'docId',
			display : '��ͬId',
			hide : true,
			sortable : true
		}, {
			name : 'rObjCode',
			display : '����ҵ����',
			width : 120,
			sortable : true
		}, {
			name : 'docCode',
			display : 'Դ����',
			width : 180,
			sortable : true
		}, {
			name : 'reterStart',
			display : '�ȼ�',
			width : 180,
			sortable : true,
			process:function(v,row){
				return "<div id='sign"+row.id+"'></div><input type='hidden' id='star"
				+row.id+
				"' value="
				+ v +
				" name='outplan[reterStart]'></input><script>$('#sign"
				+row.id+
				"').rater({value:$('#star"
				+row.id+
				"')[0].value,image:'js/jquery/raterstar/star.gif',max:5,url:'index1.php?model=stock_outplan_outplan&action=editstar&id="
				+row.id+
				"',before_ajax: function(ret) {if(confirm('Ҫ�޸ĵȼ���')==false){$('#sign"
				+row.id+
				"').rater(this);return false;}}});</script>";
			}
		}, {
			name : 'docName',
			display : 'Դ������',
			width : 180,
			hide : true,
			sortable : true
		}, {
			name : 'docType',
			display : '��������',
			sortable : true,
			width : 60,
			process : function(v) {
				if (v == 'oa_contract_exchangeapply') {
					return "��������";
				} else if (v == 'oa_contract_contract') {
					return "��ͬ����";
				} else if (v == 'oa_borrow_borrow') {
					return "���÷���";
				} else if (v == 'oa_present_present') {
					return "���ͷ���";
				}
			}
		}, {
			name : 'isTemp',
			display : '�Ƿ���',
			width : 60,
			process : function(v) {
				(v == '1') ? (v = '��') : (v = '��');
				return v;
			},
			sortable : true
		}, {
			name : 'planIssuedDate',
			display : '�´�����',
			width : 75,
			sortable : true,
			hide : true
		}, {
			name : 'stockName',
			display : '�����ֿ�',
			sortable : true,
			hide : true
		}, {
			name : 'type',
			display : '����',
			datacode : 'FHXZ',
			width : 70,
			sortable : true,
			hide : true
		}, {
			name : 'purConcern',
			display : '�ɹ���Ա��ע�ص�',
			hide : true,
			sortable : true
		}, {
			name : 'rate',
			display : '����',
			sortable : false,
			process : function(v,row){
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_outplanrate&action=updateRate&id='
						+row.id+'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">��ע��'+"<font color='gray'>"+v+"</font>"+'</a>';
			}
		}, {
			name : 'shipConcern',
			display : '������Ա��ע',
			hide : true,
			sortable : true
		}, {
			name : 'deliveryDate',
			display : '��������',
			width : 75,
			sortable : true
		}, {
			name : 'shipPlanDate',
			display : '�ƻ���������',
			width : 75,
			sortable : true
		}, {
			name : 'status',
			display : '����״̬',
			width : 60,
			process : function(v) {
				if (v == 'YZX') {
					return "��ִ��";
				} else if (v == 'BFZX') {
					return "����ִ��";
				} else if (v == 'WZX') {
					return "δִ��";
				} else {
					return "δִ��";
				}
			},
			sortable : true
		}, {
			name : 'isOnTime',
			display : '��ʱ����',
			width : 60,
			process : function(v) {
				(v == '1') ? (v = '��') : (v = '��');
				return v;
			},
			sortable : true
		}, {
			name : 'issuedStatus',
			display : '�´�״̬',
			width : 60,
			process : function(v) {
				(v == '1') ? (v = '���´�') : (v = 'δ�´�');
				return v;
			},
			sortable : true
		}, {
			name : 'docStatus',
			display : '����״̬',
			width : 70,
			process : function(v) {
				if (v == 'YWC') {
					return "�ѷ���";
				} else if (v == 'BFFH') {
					return "���ַ���";
				} else if (v == 'YGB') {
					return "�ѹر�";
				} else
					return "δ����";
			},
			sortable : true
		}, {
			name : 'delayType',
			display : '����ԭ�����',
			hide : true,
			sortable : true
		}, {
			name : 'delayReason',
			display : 'δ������ԭ��',
			hide : true,
			sortable : true
		}, {
			name : 'changeTips',
			display : '�����ʶ',
			hide : true,
			sortable : true
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=stock_outplan_outplan&action=byOutplanJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'mainId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������
			}],
			// ��ʾ����
			colModel : [{
				// name : 'productLineName',
				// width : 80,
				// display : '��Ʒ����'
				// }, {
				name : 'productNo',
				width : 150,
				display : '��Ʒ���',
				process : function(v, row) {
					if (row.changeTips == 1) {
						return "<font color=red>" + v + "</font>";
					} else {
						return v;
					}
				}
			}, {
				name : 'productName',
				width : 200,
				display : '��Ʒ����',
				process : function(v, data, rowData,$row) {
					if (data.changeTips == 1) {
						if (data.BToOTips == 1) {
							$row.attr("title", "������Ϊ������ת���۵�����");
							return "<img src='images/icon/icon147.gif' />"+"<font color=red>" + v + "</font>";
						}else{
							return "<font color=red>" + v + "</font>";
						}
					} else {
						if (data.BToOTips == 1) {
							$row.attr("title", "������Ϊ������ת���۵�����");
							return "<img src='images/icon/icon147.gif' />"+ v ;
						}else{
							return  v ;
						}
					}
					return "<font color=red>" + v + "</font>";
				}
			}, {
				name : 'number',
				display : '����',
				width : 50,
				process : function(v, row) {
					if (row.changeTips == 1) {
						return "<font color=red>" + v + "</font>";
					} else {
						return v;
					}
				}
			}, {
				name : 'unitName',
				display : '��λ',
				width : 50,
				process : function(v, row) {
					if (row.changeTips == 1) {
						return "<font color=red>" + v + "</font>";
					} else {
						return v;
					}
				}
			}, {
				name : 'executedNum',
				display : '�ѷ�������',
				width : 60,
				process : function(v, row) {
					if (row.changeTips == 1) {
						return "<font color=red>" + v + "</font>";
					} else {
						return v;
					}
				}
			}, {
				name : 'shipNum',
				display : '�������������',
				process : function(v,row) {
					if (v == '') {
						v = "0";
					}
					if (row.changeTips == 1) {
						return "<font color=red>" + v + "</font>";
					} else {
						return v;
					}
				},
				width : 120
			}]
		},

		menusEx : [{
			text : '�鿴��ϸ',
			icon : 'view',
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=outplandetailTab&planId='
						+ row.id
						+ '&docType='
						+ row.docType
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		}, {
			text : "ȡ���������",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.changeTips == 1) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('ȡ��������Ѻ󣬸÷����ƻ���ɫ����ָ�������ȷ��ȡ�����ѣ�')) {
					$.ajax({
						type : 'POST',
						url : '?model=stock_outplan_outplan&action=cancleTips&skey='
								+ row['skey_'],
						data : {
							id : row.id
						},
						success : function(data) {
							if (data == 2) {
								alert('û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա');
							} else {
								alert("���������ȡ����");
								show_page();
							}
							return false;
						}
					});
				}
			}
		}, {
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.docStatus != 'YGB' && row.docStatus != 'YWC'
						&& row.issuedStatus == 0) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=toEdit&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1100');
			}
		}, {
			name : 'issued',
			text : '�´�',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.issuedStatus != 1 && row.docStatus != 'YGB') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					$.ajax({
						type : "POST",
						url : "?model=stock_outplan_outplan&action=issuedFun&skey="
								+ row['skey_'],
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 2) {
								alert('û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա');
							} else {
								alert('�´�ɹ���');
								$("#pagebyequGrid").yxsubgrid("reload");
							}
						}
					});
				} else {
					alert("��ѡ��һ������");
				}
			}
		},{
			text : '��д������',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.issuedStatus == 1 && row.docStatus != 'YGB'
						&& row.status != 'YZX') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=stock_outplan_ship&action=toAdd&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1100');
			}
		}, {
			showMenuFn : function(row) {
				if (row.issuedStatus == '1') {
					return true;
				}
				return false;
			},
			text : '�ƻ�������',
			icon : 'edit',
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=toFeedBack&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1100');
			}
		}, {
			text : '���',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.issuedStatus == 1 && row.docStatus != 'YGB') {
					return true;
				}
				return false;
			},
			action : function(row) {
				showOpenWin('?model=stock_outplan_outplan&action=toChange&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&docType='
						+ row.docType
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=1100');
			}
		}, {
			text : "��ɼ��ܹ�����",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.dongleRate == 0) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('ȷ��Ҫ�����ܹ�����������Ϊ����ɣ���')) {
					$.ajax({
						type : 'POST',
						url : '?model=stock_outplan_outplan&action=selectDongleRate&skey='
								+ row['skey_'],
						data : {
							id : row.id
						},
						// async: false,
						success : function(data) {
							if (data == 2) {
								alert('û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա');
							} else {
								alert("���ܹ����������");
								show_page();
							}
							return false;
						}
					});
				}
			}
		}, {
			text : "�������ܹ�����",
			icon : 'add',
			showMenuFn : function(row) {
				if (row.dongleRate == 1) {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('ȷ��Ҫ�����ܹ�����������Ϊδ��ɣ���')) {
					$.ajax({
						type : 'POST',
						url : '?model=stock_outplan_outplan&action=resetDongleRate&skey='
								+ row['skey_'],
						data : {
							id : row.id
						},
						// async: false,
						success : function(data) {
							if (data == 2) {
								alert('û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա');
							} else {
								alert("���ܹ����������");
								show_page();
							}
							return false;
						}
					});
				}
			}
		}, {
			text : "�ر�",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.docStatus != 'YGB') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if (confirm('ȷ��Ҫ�رշ����ƻ���')) {
					$.ajax({
						type : 'POST',
						url : '?model=stock_outplan_outplan&action=closePlan&skey='
								+ row['skey_'],
						data : {
							id : row.id,
							docType : row.docType
						},
						// async: false,
						success : function(data) {
							if (data == 2) {
								alert('û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա��');
							}else if ( data == 0 ) {
								alert('�ر�ʧ�ܣ�');
							} else {
								alert("�رճɹ���");
								show_page();
							}
							return false;
						}
					});
				}
			}
//		}, {
//			text : "�ָ�",
//			icon : 'add',
//			showMenuFn : function(row) {
//				if (row.docStatus == 'YGB') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if (confirm('ȷ��Ҫ�������������ƻ���')) {
//					$.ajax({
//						type : 'POST',
//						url : '?model=stock_outplan_outplan&action=reopenPlan&skey='
//								+ row['skey_'],
//						data : {
//							id : row.id
//						},
//						// async: false,
//						success : function(data) {
//							if (data == 2) {
//								alert('û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա');
//							} else {
//								alert("�ָ��ɹ�");
//								show_page();
//							}
//							return false;
//						}
//					});
//				}
//			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '�ƻ����',
			name : 'planCode'
		}, {
			display : '����ҵ�񵥱��',
			name : 'rObjCode'
		}, {
			display : 'Դ����',
			name : 'docCode'
		}]
	});
});