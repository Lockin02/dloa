var show_page = function(page) {
	$("#invpurchaseGrid").yxsubgrid("reload");
};

$(function() {
	var listType = $("#listType").val();
	var paramArr = (listType != '')? {'listType' : listType} : {};

	var titleName = '采购发票';
	switch (listType){
		case'assetPurOnly':
			titleName = '固定资产发票';
			break;
		default:
			titleName = '采购发票';
			break;
	}

	$("#invpurchaseGrid").yxsubgrid({
		model: 'finance_invpurchase_invpurchase',
		param: {'listType' : listType},
		title: titleName,
		isEditAction : false,
		isDelAction : false,
		isViewAction : false,
		showcheckbox :false,
		customCode : 'invpurchaseGrid',
		noCheckIdValue : 'noId',
		//列信息
		colModel: [{
				display: 'id',
				name: 'id',
				sortable: true,
				hide: true,
				process : function(v,row){
					return v + "<input type='hidden' id='isBreak"+ row.id+"' value='unde'>";
				}
			},
			{
				name: 'objCode',
				display: '单据编号',
				sortable: true,
				width : 130,
				process : function(v,row){
					if(row.formType == "blue"){
						return v;
					}else if(row.formType == "red"){
						return "<span class='red'>"+ v +"</span>";
					}else{
						return v;
					}
				}
			},
			{
				name: 'objNo',
				display: '发票号码',
				sortable: true,
				process : function(v,row){
					if(row.formType == "blue"){
						return v;
					}else if(row.formType == "red"){
						return "<span class='red'>"+ v +"</span>";
					}
				}
			},
			{
				name: 'supplierName',
				display: '供应商名称',
				sortable: true,
				width : 150
			},
			{
				name: 'invType',
				display: '发票类型',
				sortable: true,
				width : 80,
				datacode : 'FPLX'
			},
			{
				name: 'formNumber',
				display: '数量',
				sortable: true,
				width : 60
			},
			{
				name: 'taxRate',
				display: '税率(%)',
				sortable: true,
				width : 60
			},
			{
				name: 'formAssessment',
				display: '单据税额',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'amount',
				display: '总金额',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'formCount',
				display: '价税合计',
				sortable: true,
				process : function(v){
					return moneyFormat2(v);
				},
				width : 80
			},
			{
				name: 'formDate',
				display: '单据日期',
				sortable: true,
				width : 80
			},
			{
				name: 'payDate',
				display: '付款日期',
				sortable: true,
				width : 80
			},{
				name : 'purcontCode',
				display : '采购订单编号',
				width : 130,
				hide : true
			},
			{
				name: 'departments',
				display: '部门',
				sortable: true,
				width : 80
			},
			{
				name: 'salesman',
				display: '业务员',
				sortable: true,
				width : 80
			},
			{
				name : 'businessBelongName',
				display : '归属公司',
				sortable : true,
				width : 80
			},
			{
				name: 'ExaStatus',
				display: '审核状态',
				sortable: true,
				width : 60,
				process : function(v){
					if(v == 1){
						return '已审核';
					}else if(v == "0"){
						return '未审核';
					}
				}
			},
			{
				name: 'exaMan',
				display: '审核人',
				sortable: true,
				width : 80
			},
			{
				name: 'ExaDT',
				display: '审核日期',
				sortable: true,
				width : 80
			},
			{
				name: 'status',
				display: '钩稽状态',
				sortable: true,
				width : 60,
				process : function(v){
					if(v == 1){
						return '已钩稽';
					}else if(v == "0"){
						return '未钩稽';
					}
				}
			},{
				name : 'createName',
				display : '创建人',
				width : 90,
				hide : true
			},
			{
				name: 'belongId',
				display: '所属原发票id',
				hide: true
			},
			{
				name: 'updateTime',
				display: '最近更新时间',
				hide : true,
				width : 130
			}
		],

		// 主从表格设置
		subGridOptions : {
			url : '?model=finance_invpurchase_invpurdetail&action=pageJson',// 获取从表数据url
			// 传递到后台的参数设置数组
			param : [
				{
					paramId : 'invPurId',// 传递给后台的参数名称
					colId : 'id'// 获取主表行数据的列名称
				}
			],
			// 显示的列
			colModel : [{
					name : 'productNo',
					display : '物料编号',
					width : 80
				},{
					name : 'productName',
					display : '物料名称',
					width : 140
				},{
				    name : 'number',
				    display : '数量',
				    width : 50
				},{
					name : 'price',
					display : '单价',
					process : function(v,row,parentRow){
						return moneyFormat2(v,6,6);
					}
				},{
					name : 'taxPrice',
					display : '含税单价',
					process : function(v){
						return moneyFormat2(v,6,6);
					}
				},{
				    name : 'assessment',
				    display : '税额',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 70
				},{
				    name : 'amount',
				    display : '金额',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
				    name : 'allCount',
				    display : '价税合计',
					process : function(v){
						return moneyFormat2(v);
					},
				    width : 80
				},{
				    name : 'objCode',
				    display : '源单编号',
				    width : 120
				},{
				    name : 'contractCode',
				    display : '订单编号',
				    width : 120
				}
			]
		},
		toAddConfig : {
			toAddFn : function(p) {
				showModalWin("?model=finance_invpurchase_invpurchase&action=toAdd",1);
			}
		},
		buttonsEx : [{
			name : 'Add',
			text : "上查",
			icon : 'search',
			action : function(row, rows, idArr) {
				if (row) {
					if(idArr.length >1 ) {
						alert('一次只能对一条记录进行上查');
						return false;
					}
					$.ajax({
					    type: "POST",
					    url: "?model=common_search_searchSource&action=checkUp",
					    data: {"objId" : row.id , 'objType' : 'invpurchase'},
					    async: false,
					    success: function(data){
					   		if(data != ""){
					   			var dataObj = eval("(" + data +")");
					   			for(t in dataObj){
					   				var thisType = t;
					   				var thisIds = dataObj[t];
					   			}
								showModalWin("?model=common_search_searchSource&action=upList&objType=invpurchase&orgObj="+ thisType +"&ids=" + thisIds);
					   	    }else{
								alert('没有相关联的单据');
					   	    }
						}
					});
				} else {
					alert('请先选择记录');
				}
			}
		},{
			name : 'Add',
			text : "下查",
			icon : 'search',
			action : function(row, rows, idArr) {
				if (row) {
					if(idArr.length >1 ) {
						alert('一次只能对一条记录进行下查');
						return false;
					}

					$.ajax({
					    type: "POST",
					    url: "?model=common_search_searchSource&action=checkDown",
					    data: {"objId" : row.id , 'objType' : 'invpurchase'},
					    async: false,
					    success: function(data){
					   		if(data != ""){
								showModalWin("?model=common_search_searchSource&action=downList&objType=invpurchase&orgObj="+data+"&objId=" + row.id);
					   	    }else{
								alert('没有相关联的单据');
					   	    }
						}
					});
				} else {
					alert('请先选择记录');
				}
			}
		},{
			name : 'add',
			text : "高级搜索",
			icon : 'search',
			action : function(row) {
				showThickboxWin("?model=finance_invpurchase_invpurchase&action=toSearch"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700")
			}
		},{
			name : 'add',
			text : "打开列表",
			icon : 'search',
			action : function(row) {
				showModalWin('?model=finance_invpurchase_invpurchase&action=viewlist',1);
			}
		}],
		menusEx : [
			{
				text: "查看",
				icon: 'view',
				showMenuFn : function(row){
					if(row.id == 'noId'){
						return false;
					}
				},
				action: function(row) {
					showModalWin('?model=finance_invpurchase_invpurchase&action=init&perm=view&id=' + row.id + "&skey=" + row.skey_);
				}
			},
			{
				text: "审核",
				icon: 'edit',
				showMenuFn : function(row){
					if(row.ExaStatus == 0){//判断是否有审核权限
                        var  auditLimit = 0;
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_invpurchase_invpurchase&action=hasLimitToAudit",
                            data: "",
                            async: false,
                            success: function(data){
                                if(data == 1){
                                    auditLimit=1;
                                }
                            }
                        });

                        if(auditLimit*1 == 1){
                            return true;
                        }else{
                            return false;
                        }
						return true;
					}
					return false;
				},
				action: function(row) {
					showModalWin('?model=finance_invpurchase_invpurchase&action=init&id=' + row.id + "&skey=" + row.skey_ );
				}
			},
			{
				text: "删除",
				icon: 'delete',
				showMenuFn : function(row){
					if(row.ExaStatus == 0){
                        var  deleteLimit = 0;
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_invpurchase_invpurchase&action=hasLimitToDelete",
                            data: "",
                            async: false,
                            success: function(data){
                                if(data == 1){
                                    deleteLimit=1;
                                }
                            }
                        });

                        if(deleteLimit*1 == 1){
                            return true;
                        }else{
                            return false;
                        }
					}
					return false;
				},
				action: function(row) {
					if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_invpurchase_invpurchase&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('删除成功！');
									show_page(1);
								}else{
									alert("删除失败! ");
								}
							}
						});
					}
				}
			},
//			{
//				text: "审核",
//				icon: 'edit',
//				showMenuFn : function(row){
//					if(row.ExaStatus ==0){
//						return true;
//					}
//					return false;
//				},
//				action: function(row) {
//					if (window.confirm(("确定要审核?"))) {
//						$.ajax({
//							type : "POST",
//							url : "?model=finance_invpurchase_invpurchase&action=audit",
//							data : {
//								"id" : row.id
//							},
//							success : function(msg) {
//								if (msg == 1) {
//									alert('审核成功！');
//									show_page(1);
//								}else{
//									alert('审核失败!');
//								}
//							}
//						});
//					}
//				}
//			},
			{
				text: "反审核",
				icon: 'edit',
				showMenuFn : function(row){
					if(row.ExaStatus == 1 && row.status == 0 && row.belongId == ""){
						//判断是否有审核权限
						unAudit = $('#unAudit').length;
						if(unAudit == 0){
							$.ajax({
							    type: "POST",
							    url: "?model=finance_invpurchase_invpurchase&action=hasLimitToUnaudit",
							    data: "",
							    async: false,
							    success: function(data){
							   		if(data == 1){
							   	   		$("#invpurchaseGrid").after("<input type='hidden' id='unAudit' value='1'/>");
									}else{
							   	   		$("#invpurchaseGrid").after("<input type='hidden' id='unAudit' value='0'/>");
									}
								}
							});
						}

						if($('#unAudit').val()*1 == 1){
							return true;
						}else{
							return false;
						}
						//判断是否为被拆分采购发票
						isBreak = $("#isBreak" + row.id);
						if( isBreak.val() == 'unde' ){
							$.ajax({
							    type: "POST",
							    url: "?model=finance_invpurchase_invpurchase&action=isBreak",
							    data: {"id" : row.id},
							    async: false,
							    success: function(data){
							   	   if(data == 1){
							   	   		isBreak.val(1);
									}else{
							   	   		isBreak.val(0);
									}
								}
							});
						}

						if(isBreak.val() == 1){
							return false;
						}
					}
					return false;
				},
				action: function(row) {
					if (window.confirm(("确定要反审核?"))) {
						$.ajax({
							type : "POST",
							url : "?model=finance_invpurchase_invpurchase&action=unaudit",
							data : {
								"id" : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('反审核成功！');
									show_page(1);
								}else{
									alert('反审核失败!');
								}
							}
						});
					}
				}
			},{
				text: "单据拆分",
				icon: 'edit',
				showMenuFn : function(row){
					if(row.ExaStatus == 1 && row.status == 0 && row.belongId == ""){

						isBreak = $("#isBreak" + row.id);
						if( isBreak.val() == 'unde' ){
							$.ajax({
							    type: "POST",
							    url: "?model=finance_invpurchase_invpurchase&action=isBreak",
							    data: {"id" : row.id},
							    async: false,
							    success: function(data){
							   	   if(data == 1){
							   	   		isBreak.val(1);
									}else{
							   	   		isBreak.val(0);
									}
								}
							});
						}
						if(isBreak.val() == 1){
							return false;
						}else{
							return true;
						}
					}
					return false;
				},
				action: function(row,rows) {
					showModalWin('?model=finance_invpurchase_invpurchase&action=init&perm=break&id=' + row.id + "&skey=" + row.skey_ );
				}
			},{
				text: "单据合并",
				icon: 'edit',
				showMenuFn : function(row){
					if(row.id == 'noId'){
						return false;
					}
					if(row.belongId != ''){
						return true;
					}
					return false;
				},
				action: function(row) {
					if(confirm('确定要合并?')){
						$.ajax({
							type : "POST",
							url : "?model=finance_invpurchase_invpurchase&action=merge",
							data : {
								"id" : row.id,
								"belongId" : row.belongId
							},
							success : function(msg) {
								if (msg == 1) {
									alert('合并成功！');
									show_page(1);
								}else{
									alert('合并失败!');
								}
							}
						});
					}
				}
			},
			{
				text: "钩稽",
				icon: 'edit',
				showMenuFn : function(row){
					if(row.status == 0 && row.ExaStatus == 1){
						return true;
					}
					return false;
				},
				action: function(row) {
					showModalWin('?model=finance_invpurchase_invpurchase&action=toHook&id=' + row.id );
				}
			},
			{
				text: "钩稽日志",
				icon: 'edit',
				showMenuFn : function(row){
					if(row.status == 1){
						return true;
					}
					return false;
				},
				action: function(row) {
					showOpenWin('?model=finance_related_baseinfo&action=toUnhook&invPurId=' + row.id );
				}
			},
			{
				text: "反钩稽",
				icon: 'delete',
				showMenuFn : function(row){
					if(row.status == 1){
                        var  unHookLimit = 0;
                        $.ajax({
                            type: "POST",
                            url: "?model=finance_invpurchase_invpurchase&action=hasLimitToUnHook",
                            data: "",
                            async: false,
                            success: function(data){
                                if(data == 1){
                                    unHookLimit=1;
                                }
                            }
                        });

                        if(unHookLimit*1 == 1){
                            return true;
                        }else{
                            return false;
                        }
					}
					return false;
				},
				action: function(row) {
					if(confirm('确定要反钩稽?')){
						$.ajax({
							type : "POST",
							url : "?model=finance_related_baseinfo&action=unHookByInv",
							data : {
								"invPurId" : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('反钩稽成功！');
									show_page(1);
								}else{
									alert('反钩稽失败!');
								}
							}
						});
					}
				}
			}
		],
		comboEx:
		[
			{
				text: "审核状态",
				key: 'ExaStatus',
				data: [{
					text : '已审核',
					value : '1'
				},{
					text : '未审核',
					value : '0'
				}]
			},{
				text: "钩稽状态",
				key: 'status',
				data: [{
					text : '已钩稽',
					value : '1'
				},{
					text : '未钩稽',
					value : '0'
				}]
			}
		],
		searchitems:[
	        {
	            display:'发票号码',
	            name:'objNo'
	        },
	        {
	            display:'供应商名称',
	            name:'supplierName'
	        },
	        {
	            display:'单据编号',
	            name:'objCodeSearch'
	        },
	        {
	            display:'源单编号',
	            name:'objCodeSearchDetail'
	        },
	        {
	            display:'采购订单编号',
	            name:'contractCodeSearch'
	        },
	        {
	            display:'物料编号',
	            name:'productNoSearch'
	        },
	        {
	            display:'物料名称',
	            name:'productNameSearch'
	        },
	        {
	            display:'物料型号',
	            name:'productModelSearch'
	        }
        ],
        sortname : 'c.updateTime'
	});
});