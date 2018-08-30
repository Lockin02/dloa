var show_page = function(page) {
	$("#contractGrid").yxsubgrid("reload");
};
//产品数量
function proNumCount(docId,type){
    var proNumCount = 0
     $.ajax({
          type : 'POST',
          url : '?model=common_contract_allsource&action=hasProduct',
          data : { id : docId,
                   type : type
                 },
          async: false,
          success : function (data){
                proNumCount = data;
                return false ;
          }
     })
     return proNumCount ;
}

$(function() {
	$("#contractGrid").yxsubgrid({
		model : 'contract_contract_contract',
		action : 'assignmentJson',
		param : {
			'states' : '0,2,4',
//			'ExaStatusArr' : "完成,变更审批中",

			'isSell' : '1'
		},

		title : '物料确认需求',
		isViewAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		isAddAction : false,
		customCode : 'contractAssignInfo',
		event : {
			afterloaddata : function(e, data) {
				if (data) {
					for (var i = 0; i < data.collection.length; i++) {
						if(data.collection[i].changeTips==1 ){
							$('#row' + data.collection[i].id).css('color', 'red');
						}
					}
				}
			}
		},
		// 扩展右键菜单
		/*event:{
			row_success : function(){
			var options = {
				max : 5,
				value	: $(),
				image	: 'js/jquery/raterstar/star2.gif',
				width	: 16,
				height	: 16,
			    }
				$('.sign').rater(options);
		}
		},*/
		menusEx : [{
			text : '查看合同',
			icon : 'view',
			action : function(row) {
				if(row.oldId != '' ){
					if(typeof(row.oldId) == "undefined"){
					   var cid = row.id;
					}else{
					   var cid = row.oldId;
					}
				}else{
				  var cid = row.id;
				}
				showModalWin('?model=contract_contract_contract&action=toViewShipInfoTab&id='
						+ cid + "&linkId=" + row.lid + "&skey=" + row['skey_']);
			}
		},{
			text : '变更查看',
			icon : 'view',
			showMenuFn : function(row) {
				// if (row && row.becomeNum != '0' && row.becomeNum != '') {
				if(row.isBecome != '0' || row.isSubAppChange == '1'){
					return true;
				}
				return false;
			},
			action : function(row) {
				if(row.oldId != '' ){
					if(typeof(row.oldId) == "undefined"){
						var cid = row.id;
					}else{
						var cid = row.oldId;
					}
				}else{
					var cid = row.id;
				}
				showModalWin('?model=contract_contract_contract&action=toViewShipInfoTab&id='
					+ cid + "&linkId=" + row.lid + "&viewType=change&skey=" + row['skey_']);
			}
		},{
//			text : '查看发货物料',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if (row.linkId) {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				showModalWin('?model=contract_contract_equ&action=toViewTab&id='
//						+ row.linkId + "&skey=" + row['skey_']);
//			}
//		}, {
			text : '确认发货物料',
			icon : 'add',
			showMenuFn : function(row) {
				if ( row.lExaStatus == '' && row.dealStatus == '0'
						&&( row.ExaStatus == '完成' || row.ExaStatus == '未审批' || row.ExaStatus == '打回') ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open('?model=contract_contract_equ&action=toEquAdd&id='
						+ row.id + "&skey=" + row['skey_'],'contractassign');
			}
		},{
			text : '重新确认',
			icon : 'add',
			showMenuFn : function(row) {
				if ( row.lExaStatus != '' && (row.ExaStatus == '未审批' || row.ExaStatus == '打回') && row.dealStatus == '1') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm(("确定重新确认吗?"))) {
                           $.ajax({
							type : "POST",
							url : "?model=contract_contract_contract&action=ajaxSubApp",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg) {
								   alert("操作成功，请选择未处理重新确认物料！");
								   $("#contractGrid").yxsubgrid("reload");
								}
							}
						});
                         }
			}
		}, {
			text : '编辑发货物料',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.dealStatus == 0
						&& (row.lExaStatus == '未提交' || row.lExaStatus == '打回')
						&&( row.ExaStatus == '完成' || row.ExaStatus == '未审批' || row.ExaStatus == '打回') ) {
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open('?model=contract_contract_equ&action=toEquEdit&id='
						+ row.id + "&skey=" + row['skey_'],'contractassign');
			}
		}, {
			text : '发货物料变更',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.dealStatus != 0 && row.dealStatus != 4
						&& row.ExaStatus == '完成' && (row.lExaStatus != '' || row.isSubAppChange == '1')
//						 && proNumCount( row.id,'oa_contract_contract' )!= 0
						) {
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open('?model=contract_contract_equ&action=toEquChange&id='
						+ row.id
						+ "&oldId=" + row.oldId
						+ "&isSubAppChange="
						+ row.isSubAppChange
						+ "&linkId="
						+ row.linkId
						+ "&skey=" + row['skey_'],'contractassign');
			}
		}, {
			text : "关闭",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.dealStatus != '1'&&row.dealStatus != '3' && row.ExaStatus == '完成' && row.dealStatus != 4) {
					return false;
				}
				return false;
			},
			action : function(row) {
				if (confirm('确定要关闭该物料确认需求？')) {
					$.ajax({
						type : 'POST',
						url : '?model=common_contract_allsource&action=closeConfirm&skey='
								+ row['skey_'],
						data : {
							id : row.id,
							docType : 'oa_contract_contract'
						},
						// async: false,
						success : function(data) {
							if( data==1 ){
								alert('关闭成功，该需求将放到已处理需求中。')
								show_page();
							}else{
								alert('关闭失败，请联系管理员。')
							}
							return false;
						}
					});
				}
			}
//		}, {
//			text : '审批情况',
//			icon : 'view',
//			showMenuFn : function(row) {
//				if (row.lExaStatus != '') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//
//				showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_contract_equ_link&pid='
//						+ row.lid
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
//			}
		},{
			text : '打回',
			icon : 'delete',
			showMenuFn : function(row) {
				if ((( row.ExaStatus == '完成' || row.ExaStatus == '未审批' || row.ExaStatus == '打回')) &&  (row.dealStatus=='0' || row.dealStatus=='2') ) {
					return true;
				}
				return false;
			},
			action : function(row) {
                if(row.isSubAppChange == '1'){
				   var cid = row.oldId;
				}else{
				   var cid = row.id;
				}
//				if (window.confirm(("确定要打回?"))) {
//					$.ajax({
//						type : "POST",
//						url : "?model=contract_common_relcontract&action=ajaxBack",
//						data : {
//							id : cid
//						},
//						success : function(msg) {
//							if (msg == 1) {
//								alert('打回成功！');
//								show_page();
//							}
//						}
//					});
//				}
				showThickboxWin("?model=contract_common_relcontract&action=toRollBack&docType=oa_contract_contract&id="
						+ cid
						+ "&skey="
						+ row['skey_']
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700");
			}
		}],

		// 列信息
		colModel : [{
			display : '建立时间',
			name : 'ExaDTOne',
			sortable : true,
			width : 70
		}, {
			name : 'contractRate',
			display : '进度',
			sortable : false,
			process : function(v, row) {
				return '<a href="javascript:void(0)" onclick="javascript:showThickboxWin(\'?model=stock_outplan_assignrate&action=updateRate&docId='
						+ row.id
						+ "&docType=oa_contract_contract"
						+ "&objCode="
						+ row.objCode
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">备注：'
						+ "<font color='gray'>" + v + "</font>" + '</a>';
			}
		}, {
			display : '合同物料审批状态',
			name : 'lExaStatus',
			sortable : true
		}, {
			display : '合同物料审批表Id',
			name : 'lid',
			sortable : true,
			hide : true
		}, {
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : 'oldId',
			name : 'oldId',
			sortable : true,
			hide : true,
			show : false  // Edit By zengzx, bug：897
		}, {
			name : 'contractType',
			display : '合同类型',
			sortable : true,
			datacode : 'HTLX',
			width : 60
		}, {
			name : 'contractCode',
			display : '合同编号',
			sortable : true,
			width : 180,
			process : function(v, row) {
				if (row.isBecome != '0' || row.isSubAppChange == '1') {
					if(row.oldId != '' ){
						if(typeof(row.oldId) == "undefined"){
						   var cid = row.id;
						}else{
						   var cid = row.oldId;
						}
					}else{
					  var cid = row.id;
					}
					return "<font color = '#FF0000'>"
							+ '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=toViewShipInfoTab&id='
							+ cid
							+ '&linkId='
							+ row.linkId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#FF0000'>" + v
							+ "</font>" + '</a>';
				} else {
					return '<a href="javascript:void(0)" onclick="javascript:showOpenWin(\'?model=contract_contract_contract&action=toViewShipInfoTab&id='
							+ row.id
							+ '&linkId='
							+ row.linkId
							+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
							+ "<font color = '#4169E1'>"
							+ v
							+ "</font>" + '</a>';
				}
			}
		}, {
			name : 'contractName',
			display : '合同名称',
			sortable : true,
			width : 150
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true,
			width : 150
		}, {
			name : 'customerId',
			display : '客户Id',
			sortable : true,
			width : 100,
			hide : true
		}, {
			name : 'dealStatus',
			display : '处理状态',
			sortable : true,
			process : function(v) {
				if (v == '0') {
					return "未处理";
				} else if (v == '1') {
					return "已处理";
				} else if (v == '2') {
					return "变更未处理";
				} else if (v == '3') {
					return "已关闭";
				} else if (v == '4') {
					return "等待销售确认";
				}
			},
			width : 50
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true,
			width : 60
		}, {
			name : 'objCode',
			display : '业务编号',
			sortable : true,
			width : 120,
			hide : true
		}],
		comboEx : [{
			text : '类型',
			key : 'contractType',
			data : [{
				text : '销售合同',
				value : 'HTLX-XSHT'
			}, {
				text : '租赁合同',
				value : 'HTLX-ZLHT'
			}, {
				text : '服务合同',
				value : 'HTLX-FWHT'
			}, {
				text : '研发合同',
				value : 'HTLX-YFHT'
			}]
		}, {
			text : '处理状态',
			key : 'dealStatusArr',
			data : [{
				text : '未处理',
				value : '0,2'
			}, {
				text : '已处理',
				value : '1,3'
			}],
			value : '0,2'
		}],
		// 主从表格设置
		subGridOptions : {
			subgridcheck : true,
			url : '?model=contract_contract_product&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				'isTemp' : '0','isDel' : '0'
			}, {
				paramId : 'contractId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称
			}],

			// 显示的列
			colModel : [{
				name : 'conProductName',
				width : 200,
				display : '产品名称',
				process : function(v, row) {
					if( row.changeTips==1 ){
						return '<img title="变更编辑的产品" src="images/changeedit.gif" />' + v;
					}else if( row.changeTips==2 ){
						return '<img title="变更新增的产品" src="images/new.gif" />' + v;
					}else{
						return v;
					}
				}
			}, {
				name : 'conProductDes',
				width : 200,
				display : '产品描述'
			}, {
				name : 'number',
				display : '数量',
				width : 40
			}]
		},
		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '合同编号',
			name : 'contractCode'
		}, {
			display : '合同名称',
			name : 'contractName'
		}, {
			display : '客户名称',
			name : 'customerName'
		}, {
			display : '业务编号',
			name : 'objCode'
		},{
			display : '申请人',
			name : 'createName'
		}],
		sortname : 'ExaDT desc ,id',
		sortorder : 'DESC'
	});

});
