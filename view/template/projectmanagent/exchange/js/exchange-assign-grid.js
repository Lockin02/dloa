var show_page = function(page) {
	$("#shipmentsGrid").yxsubgrid("reload");
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
	$("#shipmentsGrid").yxsubgrid({
		model : 'projectmanagent_exchange_exchange',
		action : 'shipmentsPageJson',
		customCode : 'exchangeShipmentsGrid',
		param : {
			'ExaStatusArr':"完成,变更审批中"
		},
		title : '换货物料确认需求',
		// 按钮
		isViewAction : false,
		isAddAction : false,
		isEditAction : false,
		isDelAction : false,
		showcheckbox : false,
		// 列信息
		colModel : [{
					display : '物料审批状态',
					name : 'lExaStatus',
					sortable : true
				}, {
					display : '物料审批表Id',
					name : 'lid',
					sortable : true,
					hide : true
				}, {
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'ExaDT',
					display : '建立时间',
					width : 70,
					sortable : true
				}, {
					name : 'deliveryDate',
					display : '交货日期',
					width : 80,
					sortable : true
				}, {
					name : 'exchangeCode',
					display : '换货单编号',
					sortable : true
				}, {
					name : 'contractCode',
					display : '源单号',
					sortable : true
				}, {
					name : 'customerName',
					display : '客户名称',
					sortable : true
				}, {
					name : 'saleUserName',
					display : '销售负责人',
					sortable : true
				}, {
					name : 'dealStatus',
					display : '处理状态',
					process : function(v) {
						if (v == '0') {
							return "未处理";
						} else if (v == '1') {
							return "已处理";
						} else if (v == '2') {
							return "变更未处理";
						} else if (v == '3') {
							return "已关闭";
						}
					},
					width : '60',
					sortable : true
				}, {
					name : 'ExaStatus',
					display : '审批状态',
					width : 60,
					sortable : true
				}, {
					name : 'objCode',
					display : '业务编号',
					width : 120
				}, {
					name : 'rObjCode',
					display : '源单业务编号',
					width : 120
				}],
		// 主从表格设置
		subGridOptions : {
			subgridcheck:true,
			url : '?model=projectmanagent_exchange_exchangeproduct&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [{
				'isTemp' : '0','isDel' : '0'
			}, {
				paramId : 'exchangeId',// 传递给后台的参数名称
				colId : 'id'// 获取主表行数据的列名称
			}],
			// 显示的列
			colModel : [{
				name : 'conProductName',
				width : 200,
				display : '产品名称',
				process : function(v, row) {
					if (row.changeTips == 1) {
						return "<font color = 'red'>" + v + "</font>"
					} else
						return v;
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
		comboEx : [{
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

		menusEx : [{
			text : '查看发货物料',
			icon : 'view',
			showMenuFn : function(row) {
				if (row.linkId) {
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=projectmanagent_exchange_exchangeequ&action=toViewTab&id='
						+ row.linkId + "&skey=" + row['skey_']);
			}
		}, {
			text : '确认发货物料',
			icon : 'view',
			showMenuFn : function(row) {
				if ( row.lExaStatus == '' && row.ExaStatus == '') {
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open('?model=projectmanagent_exchange_exchangeequ&action=toEquAdd&id='
						+ row.id + "&skey=" + row['skey_'],'exchangeassign');
			}
		}, {
			text : '编辑发货物料',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.dealStatus == 0 && (row.lExaStatus == '未提交'||row.lExaStatus == '打回')) {
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open('?model=projectmanagent_exchange_exchangeequ&action=toEquEdit&id='
						+ row.id + "&skey=" + row['skey_'],'exchangeassign');
			}
		}, {
			text : '发货物料变更',
			icon : 'delete',
			showMenuFn : function(row) {
				if ( row.dealStatus != 0
//				&& proNumCount( row.id,'oa_contract_exchangeapply' )!= 0
				&& row.ExaStatus=='完成') {
					return true;
				}
				return false;
			},
			action : function(row) {
				window.open('?model=projectmanagent_exchange_exchangeequ&action=toEquChange&id='
						+ row.id + "&skey=" + row['skey_'],'exchangeassign');
			}
		}, {
			text : "关闭",
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.dealStatus != '1'&&row.dealStatus != '3') {
					return true;
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
							docType : 'oa_contract_exchangeapply'
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
//				showThickboxWin('controller/contract/contract/readview.php?itemtype=oa_exchange_equ_link&pid='
//						+ row.lid
//						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=600');
//			}
		}],
		/**
		 * 快速搜索
		 */
		searchitems : [{
					display : '编号',
					name : 'Code'
				}, {
					display : '业务编号',
					name : 'objCode'
				}, {
					display : '源单业务编号',
					name : 'rObjCode'
				}],
		sortname : 'ExaDT desc ,id',
		sortorder : 'DESC'
	});
});