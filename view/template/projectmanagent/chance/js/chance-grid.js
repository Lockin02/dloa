var show_page = function(page) {
	$("#chanceGrid").yxsubgrid("reload");
};
$(function() {
	buttonsArr = [
//   {
//		text : "�����̻����ʱ��",
//		icon : 'add',
//		action : function(row) {
//			showThickboxWin("?model=projectmanagent_chance_chance&action=updateDateExcel"
//					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
//		}
//
//	},
	{
		text : "����",
		icon : 'delete',
		action : function(row) {
			var listGrid = $("#chanceGrid").data('yxsubgrid');
			listGrid.options.extParam = {};
			$("#caseListWrap tr").attr('style',"background-color: rgb(255, 255, 255); ");
			listGrid.reload();
		}
	}, {
		name : 'view',
		text : "��ʷ�̻���Ϣ",
		icon : 'view',
		action : function(row) {
			// var url =
			// "?model=projectmanagent_chance_chance&action=chanceInfoList";
			// showModalDialog(url,
			// '',"dialogWidth:1000px;dialogHeight:600px;");
			showModalWin("?model=projectmanagent_chance_chance&action=chanceInfoList"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
		}
	},{
		// ����EXCEL�ļ���ť
		name : 'output',
		text : "�̻����ټ�¼",
		icon : 'excel',
		action : function(row) {
			var chanceId = $('#chanceId').val();
			var i = 1;
			var colId = "";
			var colName = "";
			var status = $("#status").val();
			showThickboxWin("?model=projectmanagent_chance_chance&action=toOutputtExcel"
			+ "&placeValuesBefore&TB_iframe=true&modal=false&height=270&width=500");

	}
}], SJDC = {
		name : 'export',
		text : "����",
		icon : 'excel',
		action : function(row) {
			var searchConditionKey = "";
			var searchConditionVal = "";
			for (var t in $("#chanceGrid").data('yxsubgrid').options.searchParam) {
				if (t != "") {
					searchConditionKey += t;
					searchConditionVal += $("#chanceGrid").data('yxsubgrid').options.searchParam[t];
				}
			}
			var status = $("#status").val();
			var chanceType = $("#chanceType").val();
			var chanceLevel = $("#chanceLevel").val();
			var winRate = $("#winRate").val();
			var chanceStage = $("#chanceStage").val();
			var i = 1;
			var colId = "";
			var colName = "";
			$("#chanceGrid_hTable").children("thead").children("tr")
					.children("th").each(function() {
						if ($(this).css("display") != "none"
								&& $(this).attr("colId") != undefined
								&& $(this).children("div").text() != "+") {

							colName += $(this).children("div").html() + ",";
							colId += $(this).attr("colId") + ",";
							i++;
						}
					})
			window
					.open("?model=projectmanagent_chance_chance&action=exportExcel&colId="
							+ colId
							+ "&colName="
							+ colName
							+ "&status="
							+ status
							+ "&chanceType="
							+ chanceType
							+ "&chanceLevel="
							+ chanceLevel
							+ "&winRate="
							+ winRate
							+ "&chanceStage="
							+ chanceStage
							+ "&searchConditionKey="
							+ searchConditionKey
							+ "&searchConditionVal="
							+ searchConditionVal
							+ "&1width=200,height=200,top=200,left=200,resizable=yes")
		}
	}, importExcel = {
		text : "�̻�����",
		icon : 'add',
		action : function(row) {
			showThickboxWin("?model=projectmanagent_chance_chance&action=toExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
		}
	}, importGoodsExcel = {
		text : "�̻���Ʒ����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=projectmanagent_chance_chance&action=toGoodsExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=450&width=700")
		}
	};
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_chance_chance&action=getLimits',
		data : {
			'limitName' : '�̻�����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(importExcel);
				buttonsArr.push(importGoodsExcel);
			}
		}
	});
	$.ajax({
		type : 'POST',
		url : '?model=projectmanagent_chance_chance&action=getLimits',
		data : {
			'limitName' : '�̻���Ϣ�б���'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(SJDC);
			}
		}
	});
	$("#chanceGrid").yxsubgrid({
		model : 'projectmanagent_chance_chance',
		action : 'chanceGridJson',
		title : '�����̻�',
		leftLayout : true,
		customCode : 'chanceInfogrid',
		event : {
			'row_dblclick' : function(e, row, data) {
				showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id="
						+ data.id
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},
		lockCol:['flag'],//����������
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'flag',
			display : '��ͨ��',
			sortable : true,
			width : 40,
			process : function(v, row) {
			 if (row.id == "allMoney" || row.id == undefined) {
				 return "�ϼ�";
			 }
			  if(v == ''){
			     return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=listremark&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
						+ "<img src='images/icon/icon139.gif' />" + '</a>';
			  }else{
				  return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=listremark&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900\')">'
						+ "<img src='images/icon/icon095.gif' />" + '</a>';
			  }

			}
		}, {
			name : 'createTime',
			display : '����ʱ��',
			sortable : true
		}, {
			name : 'newUpdateDate',
			display : '�������ʱ��',
			sortable : true
		}, {
			name : 'chanceCode',
			display : '�̻����',
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true
		}, {
			name : 'chanceName',
			display : '��Ŀ����',
			sortable : true
		}, {
			name : 'chanceMoney',
			display : '��Ŀ�ܶ�',
			sortable : true,
			process : function(v, row) {
				return moneyFormat2(v);
			}
		}, {
			name : 'chanceTypeName',
			display : '��Ŀ����',
			sortable : true
		}, {
			name : 'chanceNatureName',
			display : '��Ŀ����',
			sortable : true
//		}, {
//			name : 'chanceStage',
//			display : '�̻��׶�',
//			datacode : 'SJJD',
//			sortable : true,
//			process : function(v, row) {
//			    if(row.id == undefined){
//			    	return "";
//			    }
//					return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=boostChanceStageInfo&id='
//							+ row.id
//							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600\')">'
//							+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
//			}
		}, {
			name : 'winRate',
			display : '�̻�Ӯ��(%)',
			datacode : 'SJYL',
			sortable : true,
			process : function(v, row) {
				if(row.id == undefined){
			    	return "";
			    }
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=winRateInfo&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'progress',
			display : '��Ŀ��չ����',
			sortable : true
		},{
		    name : 'goodsNameStr',
		    display : '��Ʒ����',
		    width : 200,
		    sortable : false
		}, {
			name : 'predictContractDate',
			display : 'Ԥ�ƺ�ͬǩ������',
			sortable : true
		}, {
			name : 'Province',
			display : '����ʡ',
			sortable : true
		}, {
			name : 'City',
			display : '������',
			sortable : true
		}, {
			name : 'areaName',
			display : '��������',
			sortable : true
		}, {
			name : 'areaPrincipal',
			display : '��������',
			sortable : true
		}, {
			name : 'prinvipalName',
			display : '�̻�������',
			sortable : true
		}, {
			name : 'customerType',
			display : '�ͻ�����',
			datacode : 'KHLX',
			sortable : true
		}, {
			name : 'status',
			display : '�̻�״̬',
			process : function(v,row) {
				if(row.id == undefined){
			    	return "";
			    }
				if (v == 0) {
					return "������";
				} else if (v == 3) {
					return "�ر�";
				} else if (v == 4) {
					return "�����ɺ�ͬ";
				} else if (v == 5) {
					return "������"
				} else if (v == 6) {
					return "��ͣ"
				}
			},
			sortable : true
		}, {
			name : 'predictExeDate',
			display : 'Ԥ�ƺ�ִͬ������',
			sortable : true
		}, {
			name : 'contractPeriod',
			display : '��ִͬ�����ڣ��£�',
			sortable : true
		}, {
			name : 'contractTurnDate',
			display : 'ת��ͬ����',
			sortable : true
		}, {
			name : 'rObjCode',
			display : 'oaҵ����',
			sortable : true
		}, {
			name : 'signSubject',
			display : 'ǩԼ����',
			sortable : true,
			datacode : 'QYZT',
			width : 60
		}, {
			name : 'boostTime',
//			display : 'Ӯ��/�׶θ���ʱ��',
			display : 'Ӯ�ʸ���ʱ��',
			sortable : true,
			width : 120
		}
//		, {
//			name : 'isTurn',
//			display : '�Ƿ�ת��ͬ',
//			sortable : true,
//			width : 60,
//			process : function(v){
//			    if(v == '1'){
//			       return "��";
//			    }else {
//			       return "-";
//			    }
//			}
//		}
		, {
			name : 'contractCode',
			display : '��ͬ��',
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toContractViewTab&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}
//		, {
//			name : 'CExaStatus',
//			display : '��ͬ����״̬',
//			sortable : true,
//			width : 60
//		}
		, {
			name : 'closeRegard',
			display : '�ر�ԭ��',
			sortable : true,
			width : 60
		}, {
			name : 'updateRecord',
			display : '�̻����¼�¼',
			sortable : true,
			width : 400

		}],
		buttonsEx : buttonsArr,
		comboEx : [{
			text : '�̻�����',
			key : 'chanceType',
			datacode : 'SJLX'
		}, {
			text : '�̻�״̬',
			key : 'status',
			value : '5',
			data : [{
				text : '������',
				value : '5'
			}, {
				text : '��ͣ',
				value : '6'
			}, {
				text : '�ر�',
				value : '3'
			}, {
				text : '�����ɺ�ͬ',
				value : '4'
			}]
		}, {
			text : '�̻�Ӯ��',
			key : 'winRate',
			datacode : 'SJYL'

//		}, {
//			text : '�̻��׶�',
//			key : 'chanceStage',
//			datacode : 'SJJD'
		}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
				}
			}

		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=projectmanagent_chance_goods&action=pageJson',// ��ȡ�ӱ�����url
			// ���ݵ���̨�Ĳ�����������
			param : [{
				paramId : 'chanceId',// ���ݸ���̨�Ĳ�������
				colId : 'id'// ��ȡ���������ݵ�������

			}],
			// ��ʾ����
			colModel : [{
				name : 'goodsName',
				width : 200,
				display : '��Ʒ����'
			}, {
				name : 'number',
				display : '����',
				width : 80
			}, {
				name : 'money',
				display : '���',
				width : 80,
				process : function(v, row) {
					if (v == '') {
						return "0.00";
					} else {
						return moneyFormat2(v);
					}
				}
			}]
		},
		// �߼�����
		advSearchOptions : {
			modelName : 'chancegrid',
			// ѡ���ֶκ��������ֵ����
			selectFn : function($valInput) {
				$valInput.yxcombogrid_area("remove");
				$valInput.yxselect_user("remove");
			},
			searchConfig : [{
				name : '��������',
				value : 'c.createTime',
				changeFn : function($t, $valInput) {
					$valInput.click(function() {
						WdatePicker({
							dateFmt : 'yyyy-MM-dd'
						});
					});
				}
			},{
				name : 'Ԥ�ƺ�ͬǩ������',
				value : 'c.predictContractDate',
				changeFn : function($t, $valInput) {
					$valInput.click(function() {
						WdatePicker({
							dateFmt : 'yyyy-MM-dd'
						});
					});
				}
			}, {
				name : '�ͻ�����',
				value : 'c.customerType',
				type : 'select',
				datacode : 'KHLX'
			}, {
					name : '��Ŀ����',
					value : 'c.chanceType',
					type : 'select',
					datacode : 'HTLX'
				}, {
					name : '������Ŀ����',
					value : 'c.chanceNature',
					type : 'select',
					datacode : 'HTLX-XSHT'
				}, {
					name : '������Ŀ����',
					value : 'c.chanceNature',
					type : 'select',
					datacode : 'HTLX-FWHT'
				}, {
					name : '������Ŀ����',
					value : 'c.chanceNature',
					type : 'select',
					datacode : 'HTLX-ZLHT'
				}, {
					name : '�з���Ŀ����',
					value : 'c.chanceNature',
					type : 'select',
					datacode : 'HTLX-YFHT'
				}, {
				name : '�̻�״̬',
				value : 'c.status',
				type : 'select',
				options : [{
					'dataName' : '������',
					'dataCode' : '5'
				}, {
					'dataName' : '��ͣ',
					'dataCode' : '6'
				}, {
					'dataName' : '�ر�',
					'dataCode' : '3'
				}, {
					'dataName' : '�����ɺ�ͬ',
					'dataCode' : '4'
				}]
//			}, {
//				name : '�̻��׶�',
//				value : 'c.chanceStage',
//				type : 'select',
//				datacode : 'SJJD'
			}, {
				name : '�̻�Ӯ��',
				value : 'c.winRate',
				type : 'select',
				datacode : 'SJYL'
			}, {
				name : '��������',
				value : 'c.areaPrincipal',
				changeFn : function($t, $valInput, rowNum) {
					$valInput.yxcombogrid_area({
						hiddenId : 'areaPrincipalId' + rowNum,
						nameCol : 'areaPrincipal',
						height : 200,
						width : 550,
						gridOptions : {
							showcheckbox : true
						}
					});
				}
			}, {
				name : '�̻�������',
				value : 'c.prinvipalName',
				changeFn : function($t, $valInput, rowNum) {

					$valInput.yxselect_user({
						hiddenId : 'prinvipalId' + rowNum,
						nameCol : 'prinvipalName',
						height : 200,
						width : 550,
						gridOptions : {
							showcheckbox : true
						}
					});
				}
			}, {
				name : 'ʡ��',
				value : 'c.province'
			}, {
				name : '����',
				value : 'c.city'
			}]
		},
		// ��������
		searchitems : [{
			display : '�̻����',
			name : 'chanceCode'
		}, {
			display : '�̻�����',
			name : 'chanceName'
		}, {
			display : '�ͻ�����',
			name : 'customerName'
		}, {
			display : '��Ʒ����',
			name : 'goodsName'
		}],
		// Ĭ������˳��
		sortorder : "DSC",
		sortname : "newUpdateDate",
		// ��ʾ�鿴��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false
	});
});
