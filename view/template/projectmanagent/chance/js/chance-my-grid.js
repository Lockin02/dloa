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
	var month = d.getMonth() + 1; // 记得当前月是要+1的
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
		title : '我的销售商机',
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
						       alert("当前列表内有 【"+data+"】 条商机，编号置红表示【预计合同签署日期】需要更新");
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
        lockCol:['flag'],//锁定的列名
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'flag',
			display : '沟通板',
			sortable : true,
			width : 40,
			process : function(v, row) {
			 if (row.id == "allMoney" || row.id == undefined) {
				 return "合计";
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
			display : '建立时间',
			sortable : true
		}, {
			name : 'newUpdateDate',
			display : '最近更新时间',
			sortable : true
		}, {
			name : 'chanceCode',
			display : '商机编号',
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
			display : '客户名称',
			sortable : true
		}, {
			name : 'chanceName',
			display : '商机名称',
			sortable : true
		}, {
			name : 'chanceMoney',
			display : '预计金额',
			sortable : true,
			process : function(v, row) {
				return moneyFormat2(v);
			}
		}, {
			name : 'chanceTypeName',
			display : '项目类型',
			sortable : true
//		}, {
//			name : 'chanceStage',
//			display : '商机阶段',
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
			display : '商机赢率',
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
			display : '预计合同签署日期',
			sortable : true
		}, {
			name : 'predictExeDate',
			display : '预计合同执行日期',
			sortable : true
		}, {
			name : 'contractPeriod',
			display : '合同执行周期（月）',
			sortable : true
		}, {
			name : 'progress',
			display : '项目进展描述',
			sortable : true
		}, {
			name : 'Province',
			display : '所属省',
			sortable : true
		}, {
			name : 'City',
			display : '所属市',
			sortable : true
		}, {
			name : 'areaPrincipal',
			display : '区域负责人',
			sortable : true
		}, {
			name : 'prinvipalName',
			display : '商机负责人',
			sortable : true
		}, {
			name : 'prinvipalName',
			display : '商机负责人',
			sortable : true
		}, {
			name : 'customerType',
			display : '客户类型',
			datacode : 'KHLX',
			sortable : true
		}, {
			name : 'status',
			display : '商机状态',
			process : function(v) {
				if (v == 0) {
					return "跟踪中";
				} else if (v == 3) {
					return "关闭";
				} else if (v == 4) {
					return "已生成合同";
				} else if (v == 5) {
					return "跟踪中"
				} else if (v == 6) {
					return "暂停"
				}
			},
			sortable : true
		}, {
			name : 'rObjCode',
			display : 'oa业务编号',
			sortable : true
		}, {
			name : 'contractCode',
			display : '合同号',
			sortable : true
		}, {
			name : 'signSubject',
			display : '签约主体',
			sortable : true,
			datacode : 'QYZT',
			width : 60
		}, {
			name : 'updateRecord',
			display : '商机更新记录',
			sortable : true,
			width : 400

		}],

		comboEx : [{
			text : '商机类型',
			key : 'chanceType',
			datacode : 'HTLX'
		}, {
			text : '商机状态',
			key : 'status',
			value : '5',
			data : [{
				text : '跟踪中',
				value : '5'
			}, {
				text : '暂停',
				value : '6'
			}, {
				text : '关闭',
				value : '3'
			}, {
				text : '已生成合同',
				value : '4'
			}]
		}],
//		buttonsEx : [{
//						// 导出EXCEL文件按钮
//						name : 'output',
//						text : "商机跟踪记录",
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

//            // 多个商机转为合同(先HOLD住)
//			name : 'toContract',
//			text : "多商机转合同",
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
//                        alert('请选择至少两个商机！');
//                    }
//                }else{
//                    alert('请选择至少两个商机！');
//                }
//            }
//        }],
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_chance_chance&action=toViewTab&perm=view&id="
							+ row.id + "&skey=" + row['skey_']);
				}
			}

		}, {
			text : '更新',
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
//			text : '推进商机',
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
			text : '指定项目团队',
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
				// text : '更新对手信息',
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
//			text : '更新详细产品',
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
			text : '移交商机',
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
			text : '申请支持',
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
			text : '填写跟踪记录',
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
//			text : '关闭商机',
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
//					alert("请选择一条数据");
//				}
//
//			}
//		}, {
//			text : '恢复商机',
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
				// text : '暂停商机',
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
			text : '转为合同',
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
							alert("该商机已生成合同"+data+"，请到[我的合同--销售类合同信息]栏目中查看！")
						}else{
							showModalWin("?model=contract_contract_contract&action=toAddchance&chanceId="
								+ row.id + "&skey=" + row['skey_']);
						}
					}
				});
			}
		}
		// , {
		// text : '授权',
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
		// 快速搜索
		searchitems : [{
			display : '商机编号',
			name : 'chanceCode'
		}, {
			display : '商机名称',
			name : 'chanceName'
		}, {
			display : '客户名称',
			name : 'customerName'
		}],
		// title : '客户信息',
		// 业务对象名称
		// boName : '供应商联系人',
		// 默认搜索字段名
		sortname : "id",
		// 默认搜索顺序
		sortorder : "ASC",
		// 显示查看按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false

			// toAddConfig : {
			// text : '新建',
			// icon : 'add',
			// /**
			// * 默认点击新增按钮触发事件
			// */
			//
			// toAddFn : function(p) {
			// self.location =
			// "?model=projectmanagent_chance_chance&action=toAdd";
			//			}
			//		}
	});
});