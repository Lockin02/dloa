var show_page = function(page) {
	$("#proborrowGrid").yxsubgrid("reload");
};
$(function() {
	buttonsArr = [
//	{
//		text : "重置",
//		icon : 'delete',
//			action : function(row) {
//				var listGrid= $("#proborrowGrid").data('yxsubgrid');
//				listGrid.options.extParam = {};
//				$("#proborrowGrid tr").attr('style',"background-color: rgb(255, 255, 255)");
//				listGrid.reload();
//			}
//		},{
//			name : 'advancedsearch',
//			text : "高级搜索",
//			icon : 'search',
//			action : function(row) {
//				showThickboxWin("?model=projectmanagent_borrow_borrow&action=search&gridName=proborrowGrid"
//				        + "&gridType=yxsubgrid"
//						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=700")
//			}
//		},
			{
			name : 'Add',
			// hide : true,
			text : "新增",
			icon : 'add',

			action : function(row) {
				showModalWin('?model=projectmanagent_borrow_borrow&action=toProAdd');
			}
		}]
	$("#proborrowGrid").yxsubgrid({
		model : 'projectmanagent_borrow_borrow',
		action : 'MyBorrowPageJson',
		param : {
			'limits' : '员工'
		},
		title : '我的借试用',
		//按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		customCode : 'proborrowlist',
		//列信息
		colModel : [
	     {
	    	display : '到期预警',
			name : 'endDate',
			sortable : true,
			width:30,
			process : function(v,row){
				if(row.backStatus == '1'){
				  return "<img src='images/icon/icon073.gif'></img>";
				}else
	    	 	if(v){
	    	 		var date=new Date();
	    	 		var time=date.format('yyyy-MM-dd');
	    	 		if(v<time)
	    	 			return "<a href='?model=projectmanagent_penalty_borrowPenalty&action=toMyPage' target='_blank''><img src='images/icon/icon070.gif'></img></a>";
	    	 		if(v>time)
	    	 			return "<img src='images/icon/green.gif'></img>";
	    	 		if(v=time)
	    	 			return "<img src='images/icon/hblue.gif'></img>";
	    	 	}
			}
		 },{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'Code',
			display : '编号',
			sortable : true,
			width : 180,
			process : function(v,row){
			    if(row.isExceed == '1'){
                     return "<span class='red'>"+ v + "</span>";
			    }else{
			         return v;
			    }
			}
		}, {
			name : 'Type',
			display : '类型',
			sortable : true,
			width : 60,
			hide : true
		}, {
			name : 'limits',
			display : '范围',
			sortable : true,
			width : 60
		},{
			name : 'timeType',
			display : '借用期限',
			sortable : true,
			width : 60
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 90,
				process: function (v,row) {
					if(row.lExaStatus != '变更审批中'){
						return v;
					}else{
						return '变更审批中';
					}
				}
		}, {
			name : 'createName',
			display : '申请人',
			sortable : true,
			hide : true
		}, {
			name : 'beginTime',
			display : '开始日期',
			sortable : true,
			width : 80
		}, {
			name : 'closeTime',
			display : '截止日期',
			sortable : true,
			width : 80
		}, {
			name : 'ExaDT',
			display : '审批时间',
			sortable : true,
				process : function(v,row) {
					if(row['ExaStatus'] == '部门审批'){
						return '';
					}else{
						return v;
					}
				}
		},{
			name : 'DeliveryStatus',
			display : '发货状态',
			sortable : true,
			process : function(v) {
				if (v == 'WFH') {
					return "未发货";
				} else if (v == 'YFH') {
					return "已发货";
				} else if (v == 'BFFH') {
					return "部分发货";
				} else if (v == 'TZFH') {
					return "停止发货";
				}
			},
			width : 80
		}, {
			name : 'status',
			display : '单据状态',
			sortable : true,
			process : function(v){
  				if( v == '0'){
  					return "正常";
  				}else if(v == '1'){
  					return "部分归还";
  				}else if(v == '2'){
  					return "关闭";
  				}else if(v == '3'){
  				    return "退回";
  				}else if(v == '4'){
  				    return "续借申请中"
  				}else if(v == '5'){
  				    return "转至执行部"
  				}else if(v == '6'){
  				    return "转借确认中"
  				}
  			}
		}
		,{
			name : 'backStatus',
			display : '归还状态',
			sortable : true,
			process : function(v){
  				if( v == '0'){
  					return "未归还";
  				}else if(v == '1'){
  					return "已归还";
  				}else if(v == '2'){
  					return "部分归还";
  				}
  			}
		}
		, {
			name : 'reason',
			display : '申请理由',
			sortable : true,
			width : 200
		},{
			name : 'renew',
			display : '续借次数',
			sortable : true
		}, {
			name : 'remark',
			display : '备注',
			sortable : true,
			width : 200
		}, {
			name : 'objCode',
			display : '业务编号',
			width : 120
		}],
		comboEx : [{
			text : '审批状态',
			key : 'ExaStatus',
			data : [{
				text : '未审批',
				value : '未审批'
			}, {
				text : '变更审批中',
				value : '变更审批中'
			}, {
				text : '部门审批',
				value : '部门审批'
			}, {
				text : '完成',
				value : '完成'
			}]
		},{
			text : '状态',
			key : 'status',
			data : [{
				text : '正常',
				value : '0'
			}, {
				text : '关闭',
				value : '2'
			}, {
				text : '退回',
				value : '3'
			}, {
				text : '续借申请中',
				value : '4'
			}, {
				text : '转至执行部',
				value : '5'
			}, {
				text : '转借确认中',
				value : '6'
			}]
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=projectmanagent_borrow_borrowequ&action=listPageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				paramId : 'borrowId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称

			}],
			// 显示的列
			colModel : [{
				name : 'productNo',
				width : 200,
				display : '产品编号',
				process : function(v,row){
					return v+"&nbsp;&nbsp;K3:"+row['productNoKS'];
				}
			},{
				name : 'productName',
				width : 200,
				display : '产品名称',
				process : function(v,row){
					return v+"&nbsp;&nbsp;K3:"+row['productNameKS'];
				}
			}, {
			    name : 'number',
			    display : '申请数量',
				width : 80
			}, {
			    name : 'executedNum',
			    display : '已执行数量',
				width : 80
			}, {
			    name : 'applyBackNum',
			    display : '已申请归还数量'
			}, {
			    name : 'backNum',
			    display : '已归还数量',
				width : 80
			}]
		},
		event : {
			afterloaddata : function(e, data) {
				if (data) {
					for (var i = 0; i < data.collection.length; i++) {
//						alert(data.collection[i].Code);
//						//判断如果借用已到期的显示红色
//						var myDate = new Date();
//						var newDate = myDate.getFullYear()+"-"+(myDate.getMonth()+1)+"-"+myDate.getDate();
//                        if(formatDate(newDate) > formatDate(data.collection[i].closeTime) && data.collection[i].status != '2'){
//                        	$('#row' + data.collection[i].Code).css('color', 'red');
//                        }
						 //判断如果为仓管确认中的 为蓝色
						if(data.collection[i].tostorage == 1){
							$('#row' + data.collection[i].id).css('color', 'blue');
						}
					}
				}
			}
		},
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '编号',
			name : 'Code'
		},{
			display : '申请人',
			name : 'createName'
		},{
			display : '申请日期',
			name : 'createTime'
		},{
		    display : 'K3物料名称',
		    name : 'productNameKS'
		},{
		    display : '系统物料名称',
		    name : 'productName'
		},{
		    display : 'K3物料编码',
		    name : 'productNoKS'
		},{
		    display : '系统物料编码',
		    name : 'productNo'
		},{
			display : '序列号',
			name : 'serialName2'
		}],
		// 扩展右键菜单
		menusEx : [{
			text : '查看',
			icon : 'view',
			action : function(row) {
				if (row) {
					showModalWin("?model=projectmanagent_borrow_borrow&action=proViewTab&id="
							+ row.id + "&skey=" + row['skey_']);
				}
			}

		}, {
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '物料确认' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批'  || row.ExaStatus == '变更审批中'|| row.ExaStatus == '免审' || row.tostorage == '1' ) {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("?model=projectmanagent_borrow_borrow&action=proEdit&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
				} else {
					alert("请选中一条数据");
				}
			}
		}, {

//			text : '归还物料',
//			icon : 'add',
//            showMenuFn : function(row) {
//				if(row.backStatus == 1 || row.status == 3 || row.status == 6 || row.ExaStatus == '未审批' ||row.ExaStatus == '打回'  ||  row.ExaStatus == '部门审批' || row.ExaStatus == '变更审批中' ){
//                   return false;
//                }
//                  return true;
//
//			},
//			action : function(row) {
//                showOpenWin('?model=projectmanagent_borrowreturn_borrowreturn&action=toAdd&id=' + row.id);
//			}
//		}
//		,{

			text : '变更',
			icon : 'edit',
            showMenuFn : function(row) {
				if (row.ExaStatus == '物料确认' || row.ExaStatus == '部门审批' || row.ExaStatus == '打回' || row.ExaStatus == '免审' || row.ExaStatus == '变更审批中' || row.lExaStatus == '变更审批中' || row.ExaStatus == '未审批' || row.status == '4'||row.status == '5'||row.status == '6') {
					return false;
				}
				return true;
			},
			action : function(row) {
				     location='?model=projectmanagent_borrow_borrow&action=toChange&change=proChange&id='+ row.id + "&skey="+row['skey_'];
			}
		}
		// ,{
		// 	text : '提交审核',
		// 	icon : 'add',
		// 	showMenuFn : function(row) {
		// 		if (row.ExaStatus == '物料确认' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批' || row.ExaStatus == '免审' || row.tostorage == '1' || row.timeType == '短期借用' || row.ExaStatus == '变更审批中') {
		// 			return false;
		// 		}
		// 		return true;
		// 	},
		// 	action : function(row, rows, grid) {
		// 		if (row) {
		// 			showThickboxWin('controller/projectmanagent/borrow/ewf_proborrow.php?actTo=ewfSelect&billId='
		// 					+ row.id
		// 					+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
		// 		}
		// 	}
		// }
		,{
			text : '提交申请',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.ExaStatus == '未审批') {
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				if (window.confirm(("确定提交吗?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_borrow_borrow&action=ajaxSubForm",
						data : {
							id : row.id
						},
						success : function(msg) {
							if(msg != ""){
								alert(msg);
							}else{
								alert("提交失败。请重试!");
							}
							$("#proborrowGrid").yxsubgrid("reload");
						}
					});
				}
			}
		}
		,{
				text : '审批情况',
				icon : 'view',
				showMenuFn : function(row) {
				if (row.ExaStatus == '免审' || row.timeType == '短期借用' ) {
					return false;
				}
				return true;
			},
				action : function(row) {
				         showThickboxWin('controller/projectmanagent/borrow/readview.php?itemtype=oa_borrow_borrow&pid='
							+ row.id
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
				}
			}, {
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.ExaStatus == '物料确认' || row.ExaStatus == '完成' || row.ExaStatus == '部门审批' || row.ExaStatus == '变更审批中' || row.ExaStatus == '免审'  || row.tostorage == '1') {
					return false;
				}
				return true;
			},
			action : function(row) {
				if (window.confirm(("确定要删除?"))) {
					$.ajax({
						type : "POST",
						url : "?model=projectmanagent_borrow_borrow&action=ajaxdeletes",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$("#proborrowGrid").yxsubgrid("reload");
							}
						}
					});
				}
			}

		},{
			text : '续借',
			icon : 'add',
            showMenuFn : function(row) {
				if(row.backStatus == 1 || row.status == 3 || row.status == 6 || row.ExaStatus == '未审批' ||row.ExaStatus == '打回'  ||  row.ExaStatus == '部门审批' || row.ExaStatus == '变更审批中' ){
                   return false;
                }
                  return true;

			},
			action : function(row, rows, grid) {
				if (row) {
					if (row) {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=borrowRenew&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
					} else {
						alert("请选中一条数据");
					}
				}
			}
		}
		,{
			text : '转借',
			icon : 'edit',
            showMenuFn : function(row) {
				if(row.backStatus == 1 || row.status == 3 || row.status == 6 || row.ExaStatus == '未审批' || row.ExaStatus == '部门审批' || row.ExaStatus == '打回' || row.ExaStatus == '变更审批中' ){
                   return false;
                }
                  return true;

			},
			action : function(row, rows, grid) {
				if (row) {
					if (row) {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=subtenancyApply&id="
							+ row.id
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
					} else {
						alert("请选中一条数据");
					}
				}
			}
		},{
			text : '转借修改',
			icon : 'edit',
            showMenuFn : function(row) {
				if(row.status == 6){
                   return true;
                }
                  return false;

			},
			action : function(row, rows, grid) {
				if (row) {
					if (row) {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=subtenancyEdit&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
					} else {
						alert("请选中一条数据");
					}
				}
			}
		},{
			text : '转借确认',
			icon : 'add',
            showMenuFn : function(row) {
				if(row.status == 6){
                   return true;
                }
                  return false;

			},
			action : function(row, rows, grid) {
				if (row) {
					if (row) {
					showOpenWin("?model=projectmanagent_borrow_borrow&action=subtenancyAff&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900")
					} else {
						alert("请选中一条数据");
					}
				}
			}
		}
		],
		buttonsEx : buttonsArr
	});

});
/**
 * 时间对象的格式化;
 */
Date.prototype.format = function(format) {
    /*
     * eg:format="YYYY-MM-dd hh:mm:ss";
     */
    var o = {
        "M+" :this.getMonth() + 1, // month
        "d+" :this.getDate(), // day
        "h+" :this.getHours(), // hour
        "m+" :this.getMinutes(), // minute
        "s+" :this.getSeconds(), // second
        "q+" :Math.floor((this.getMonth() + 3) / 3), // quarter
        "S" :this.getMilliseconds()
    // millisecond
    }

    if (/(y+)/.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + "")
                .substr(4 - RegExp.$1.length));
    }

    for ( var k in o) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? o[k]
                    : ("00" + o[k]).substr(("" + o[k]).length));
        }
    }
    return format;
}