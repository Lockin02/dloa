var show_page = function(page) {
	$("#chanceGrid").yxgrid("reload");
};
$(function (){
//	var g = $("#chanceGrid").data("grid");
//	g.each(function (){
//		alert($(this).val());
//	})
//	alert(g);
//	var chanceGridArr = $("input[id^='chanceGrid_cmp_predictContractDate']");
//	chanceGridArr.each(function (){
//		alert($(this).val());
//	})
})
$(function() {
	var d = new Date();
	var year = d.getFullYear();
	var month = d.getMonth() + 1; // �ǵõ�ǰ����Ҫ+1��
	var dt = d.getDate();
	var today = year + "-" + month + "-" + dt;

	function daysBetween(DateOne,DateTwo)
	{
	    var OneMonth = DateOne.substring(5,DateOne.lastIndexOf ('-'));
	    var OneDay = DateOne.substring(DateOne.length,DateOne.lastIndexOf ('-')+1);
	    var OneYear = DateOne.substring(0,DateOne.indexOf ('-'));

	    var TwoMonth = DateTwo.substring(5,DateTwo.lastIndexOf ('-'));
	    var TwoDay = DateTwo.substring(DateTwo.length,DateTwo.lastIndexOf ('-')+1);
	    var TwoYear = DateTwo.substring(0,DateTwo.indexOf ('-'));

	    var cha=((Date.parse(OneMonth+'/'+OneDay+'/'+OneYear)- Date.parse(TwoMonth+'/'+TwoDay+'/'+TwoYear))/86400000);
	    return cha;
	}
	$("#chanceGrid").yxgrid({
		model : 'projectmanagent_chance_chance',
		action : 'pageJsonMyChance',
		title : '�ҵ������̻�',
		event : {
			'afterloaddata': function(e,data) {
	        	$.ajax({
					type : 'POST',
					url : 'index1.php?model=projectmanagent_chance_chance&action=getChanceStaleDated',
//					data : {
//						dir : 'ASC'
//					},
				    async: false,
					success : function(data) {
						if (data && data != '0') {
						       alert("��ǰ�б����� ��"+data+"�� ���̻�������ú��ʾ��Ԥ�ƺ�ͬǩ�����ڡ���Ҫ����");
						}
					}
				});
	        },
			'row_dblclick' : function(e, row, data) {
				showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&id="
						+ data.id
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
			}
		},
		showcheckbox : false,
		formHeight : 600,
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
				var numberOfDays =daysBetween(row.predictContractDate,today);
				if (numberOfDays<0 && row.status == '5'){
					return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = 'red'>" + v + "</font>" + '</a>';
				}else{
					return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=projectmanagent_chance_chance&action=toViewTab&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
				}
			}
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			sortable : true
		}, {
			name : 'chanceName',
			display : '�̻�����',
			sortable : true
		}, {
			name : 'chanceMoney',
			display : 'Ԥ�ƽ��',
			sortable : true,
			process : function(v, row) {
				return moneyFormat2(v);
			}
		}, {
			name : 'chanceTypeName',
			display : '��Ŀ����',
			sortable : true
//		}, {
//			name : 'chanceStage',
//			display : '�̻��׶�',
//			datacode : 'SJJD',
//			sortable : true,
//			process : function(v, row) {
//				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=boostChanceStageInfo&id='
//						+ row.id
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600\')">'
//						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
//			}
		}, {
			name : 'winRate',
			display : '�̻�Ӯ��',
			datacode : 'SJYL',
			sortable : true,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=projectmanagent_chance_chance&action=winRateInfo&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=600\')">'
						+ "<font color = '#4169E1'>" + v + "</font>" + '</a>';
			}
		}, {
			name : 'predictContractDate',
			display : 'Ԥ�ƺ�ͬǩ������',
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
			name : 'progress',
			display : '��Ŀ��չ����',
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
			name : 'areaPrincipal',
			display : '��������',
			sortable : true
		}, {
			name : 'prinvipalName',
			display : '�̻�������',
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
			process : function(v) {
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
			name : 'rObjCode',
			display : 'oaҵ����',
			sortable : true
		}, {
			name : 'contractCode',
			display : '��ͬ��',
			sortable : true
		}, {
			name : 'signSubject',
			display : 'ǩԼ����',
			sortable : true,
			datacode : 'QYZT',
			width : 60
		}, {
			name : 'updateRecord',
			display : '�̻����¼�¼',
			sortable : true,
			width : 400

		}],

		comboEx : [{
			text : '�̻�����',
			key : 'chanceType',
			datacode : 'HTLX'
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
		}],
//		buttonsEx : [{
//						// ����EXCEL�ļ���ť
//						name : 'output',
//						text : "�̻����ټ�¼",
//						icon : 'excel',
//						action : function(row) {
//							var chanceId = $('#chanceId').val();
//							var i = 1;
//							var colId = "";
//							var colName = "";
//							$.ajax({
//								type : 'POST',
//								url : 'index1.php?model=projectmanagent_chance_chance&action=getChanceIds',
////								data : {
////									dir : 'ASC'
////								},
//							    async: false,
//								success : function(data) {
//									if(data !=""){
//										 $("#ids").val(data);
//									}
//								}
//							});
//							showThickboxWin("?model=projectmanagent_chance_chance&action=outputtExcel"
//							+ "&chanceId="
//							+ $("#ids").val()
//							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=270&width=500");
//
//					},

//            // ����̻�תΪ��ͬ(��HOLDס)
//			name : 'toContract',
//			text : "���̻�ת��ͬ",
//			icon : 'add',
//			action : function(row,rows,idArr) {
//                if(row){
//                    if (idArr.length > 1){//chanceCode
//                        var codes = '';
//                        for (var i = 0; i < rows.length; i++) {
//                            codes += rows[i].chanceCode + ',';
//                        }
//    //                    for(var i=0;i<=idArr.length;i++){
//    //                        codes += rows[i].chanceCode + ',';
//    //                    }
//                        showThickboxWin(
//                            codes
//                        );
//                    }else{
//                        alert('��ѡ�����������̻���');
//                    }
//                }else{
//                    alert('��ѡ�����������̻���');
//                }
//            }
//        }],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&perm=view&id="
							+ row.id + "&skey=" + row['skey_']);
				}
			}

		}, {
			text : '����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.status != '5') {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_chance_chance&action=updateChance&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900");
				}
			}
//		}, {
//			text : '�ƽ��̻�',
//			icon : 'edit',
//			showMenuFn : function(row) {
//			var numberOfDays =daysBetween(row.predictContractDate,today);
//				if ( row.status == '4' || row.status == '6' ||numberOfDays<0) {
//					return false;
//				}
//				return true;
//			},
//			action : function(row) {
//				if (row) {
//					showThickboxWin("?model=projectmanagent_chance_chance&action=boostChance&id="
//							+ row.id
//							+ "&skey="
//							+ row['skey_']
//							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=600&width=1000");
//				}
//			}
		}, {
			text : 'ָ����Ŀ�Ŷ�',
			icon : 'edit',
			showMenuFn : function(row) {
				var numberOfDays =daysBetween(row.predictContractDate,today);
				if (row.status != '5' || numberOfDays<0) {
					return false;
				}
				return true;
			},
			action : function(row) {
				showThickboxWin('?model=projectmanagent_chance_chance&action=toTrackman&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700');

			}
//		}
				// ,{
				//
				// text : '���¶�����Ϣ',
				// icon : 'add',
				// showMenuFn : function(row) {
				// if (row.status == '3' || row.status == '4'|| row.status ==
				// '6') {
				// return false;
				// }
				// return true;
				// },
				//
				// action : function(row) {
				//
				// showThickboxWin('?model=projectmanagent_chance_chance&action=toCompetitor&chanceId='
				// + row.id + "&skey="+row['skey_']
				// +
				// '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=1000');
				//
				// }
				// }
//		, {
//
//			text : '������ϸ��Ʒ',
//			icon : 'add',
//			showMenuFn : function(row) {
//			var numberOfDays =daysBetween(row.predictContractDate,today);
//				if (row.status == '3' || row.status == '4' || row.status == '6' || numberOfDays<0) {
//					return false;
//				}
//				return true;
//			},
//
//			action : function(row) {
//
//				showThickboxWin('?model=projectmanagent_chance_chance&action=toProductinfo&chanceId='
//						+ row.id
//						+ "&skey="
//						+ row['skey_']
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=1000');
//
//			}
		}, {
			text : '�ƽ��̻�',
			icon : 'edit',
			showMenuFn : function(row) {
				var numberOfDays =daysBetween(row.predictContractDate,today);
				if (row.status != '5' || numberOfDays<0) {
					return false;
				}
				return true;
			},
			action : function(row) {
				showThickboxWin('?model=projectmanagent_chance_chance&action=transferChance&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700');

			}
		}, {
			text : '����֧��',
			icon : 'add',
			showMenuFn : function(row) {
				var numberOfDays =daysBetween(row.predictContractDate,today);
				if (row.status != '5' || numberOfDays<0) {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (row) {
					showThickboxWin("?model=projectmanagent_chance_chance&action=toAppSupport&objId="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ '&objCode='
							+ row.chanceCode
							+ '&objName='
							+ row.chanceName
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=400');
					// showOpenWin("?model=projectmanagent_borrow_borrow&action=toAdd&id="
					// + row.id);
				}
			}

		}, {
			text : '��д���ټ�¼',
			icon : 'add',
			showMenuFn : function(row) {
				var numberOfDays =daysBetween(row.predictContractDate,today);
				if (row.status != '5' || numberOfDays<0) {
					return false;
				}
				return true;
			},
			action : function(row) {
				showThickboxWin('?model=projectmanagent_track_track&action=toChanceTrack&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}
//		, {
//			text : '�ر��̻�',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.status == '0') {
//					return true;
//				}
//
//				return false;
//			},
//			action : function(row, rows, grid) {
//				if (row) {
//					showThickboxWin("?model=projectmanagent_chance_chance&action=toClose&id="
//							+ row.id
//							+ "&skey="
//							+ row['skey_']
//							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700");
//				} else {
//					alert("��ѡ��һ������");
//				}
//
//			}
//		}, {
//			text : '�ָ��̻�',
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if (row.status == '3') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//
//				showThickboxWin("?model=projectmanagent_chance_chance&action=toRecover&id="
//						+ row.id
//						+ "&skey="
//						+ row['skey_']
//						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700");
//			}
//		}
				//		          ,{
				// text : '��ͣ�̻�',
				// icon : 'delete',
				// showMenuFn : function(row){
				// if(row.status == '3' || row.status == '4' || row.status ==
				// '6'){
				// return false;
				// }
				// return true;
				// },
				// action : function(row,rows,grid){
				// showThickboxWin("?model=projectmanagent_chance_chance&action=toPause&id="
				// + row.id + "&skey="+row['skey_']
				// +
				// "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700"
				// );
				// }
				// }
		, {
			text : 'תΪ��ͬ',
			icon : 'add',
			showMenuFn : function(row) {
//                var numberOfDays =daysBetween(row.predictContractDate,today);
//                if ((row.winRate ==80 || row.winRate == 100) && ( row.chanceStage=='SJJD04' || row.chanceStage=='SJJD05') &&  numberOfDays>0) {
                if ((row.winRate == 80 || row.winRate == 100) && row.status != 4) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				$.ajax({
					type : 'POST',
					url : 'index1.php?model=projectmanagent_chance_chance&action=ajaxGetConTurn',
					data : {
						chanceId : row.id
					},
					async: false,
					success : function(data) {
						if (data && data != '0') {
							alert("���̻������ɺ�ͬ"+data+"���뵽[�ҵĺ�ͬ--�������ͬ��Ϣ]��Ŀ�в鿴��")
						}else{
							showModalWin("?model=contract_contract_contract&action=toAddchance&chanceId="
								+ row.id + "&skey=" + row['skey_']);
						}
					}
				});
			}
		}
		// , {
		// text : '��Ȩ',
		// icon : 'business',
		// showMenuFn : function(row) {
		// if (row.status == '3' || row.status == '4' || row.status == '6') {
		// return false;
		// }
		// return true;
		// },
		//
		// action : function(row, rows, grid) {
		// showThickboxWin("?model=projectmanagent_chance_chance&action=toAuthorize&id="
		// + row.id + "&skey=" + row['skey_']
		// +
		// '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
		// }
		// }
		],
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
		}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		// boName : '��Ӧ����ϵ��',
		// Ĭ�������ֶ���
		sortname : "id",
		// Ĭ������˳��
		sortorder : "ASC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false

			// toAddConfig : {
			// text : '�½�',
			// icon : 'add',
			// /**
			// * Ĭ�ϵ��������ť�����¼�
			// */
			//
			// toAddFn : function(p) {
			// self.location =
			// "?model=projectmanagent_chance_chance&action=toAdd";
			//			}
			//		}
	});
});