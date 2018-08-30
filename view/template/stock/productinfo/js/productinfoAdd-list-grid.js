/** 物料信息列表* */

var show_page = function(page) {
	$("#proTypeTree").yxtree("reload");
	$("#productinfoGrid").yxgrid("reload");
};

$(function() {

	$("#tree").yxtree({
		url : '?model=stock_productinfo_producttype&action=getTreeDataByParentId',
		event : {
			"node_click" : function(event, treeId, treeNode) {
				var productinfoGrid = $("#productinfoGrid").data('yxgrid');

				productinfoGrid.options.extParam['proTypeId'] = treeNode.id;
				$("#proTypeId").val(treeNode.id);
				$("#proType").val(treeNode.name);
				$("#arrivalPeriod").val(treeNode.submitDay);
				productinfoGrid.reload();
				// productinfoGrid.options.extParam['proTypeId'] = null;
			}
		}
	});

	$("#productinfoGrid").yxgrid({
		customCode : 'productinfolistgrid',
		model : 'stock_productinfo_productinfoAdd',
		action : 'myPageJson',
		title : '物料信息管理',
		isToolBar : true,
		isViewAction : false,
		showcheckbox : false,
		isEditAction : false,
		isDelAction : false,
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '物料类型',
					name : 'proType',
					sortable : true
				}, {
					display : '物料编码',
					name : 'productCode',
					sortable : true
				}, {
					display : 'k3编码',
					name : 'ext2',
					sortable : true,
					hide:true
				}, {
					display : '物料名称',
					name : 'productName',
					sortable : true,
					width : 250
				}, {
					display : '状态',
					name : 'ext1',
					process : function(v, row, g) {
//						alert(g)
						if (v == "WLSTATUSKF") {
							return "开放";
						} else {
//							alert($(this).html());
							return "关闭";
						}
					},
					sortable : true,
					width : 50
				}, 				
				{
					name : 'pattern',
					display : '规格型号',
					sortable : true
				},{
					name : 'status',
					display : '是否提交',
					sortable : true,
					process : function(v){
					if(v=='0')
						return '未提交';
					else if(v=='1')
						return '已提交';
				}
				}
				,{
					name : 'state',
					display : '是否确认',
					sortable : true
				}
				, {
					name : 'priCost',
					display : '单价',
					sortable : true,
					hide : true
				}, {
					name : 'unitName',
					display : '单位',
					sortable : true
				}, {
					name : 'aidUnit',
					display : '辅助单位',
					sortable : true,
					hide : true
				}, {
					name : 'converRate',
					display : '换算率',
					sortable : true,
					hide : true
				}, {
					name : 'warranty',
					display : '保修期(月)',
					hide : true,
					sortable : true
				}, {
					name : 'arrivalPeriod',
					display : '发货周期(天)',
					hide : true,
					sortable : true
				}, {
					name : 'purchPeriod',
					display : '采购周期(天)',
					sortable : true,
					hide : true
				}, {
					name : 'accountingCode',
					display : '会计科目代码',
					sortable : true,
					datacode : 'KJKM',
					hide : true
				}, {
					name : 'changeProductCode',
					display : '替代物料编码',
					sortable : true,
					hide : true
				}, {
					name : 'changeProductName',
					display : '替代物料名称',
					sortable : true,
					hide : true
				}, {
					name : 'closeReson',
					display : '关闭原因',
					sortable : true,
					hide : true
				}, {
					name : 'leastPackNum',
					display : '最小包装量',
					sortable : true,
					hide : true
				}, {
					name : 'leastOrderNum',
					display : '最小订单量',
					sortable : true,
					hide : true
				}, {
					name : 'material',
					display : '材料',
					sortable : true,
					hide : true
				}, {
					name : 'brand',
					display : '品牌',
					sortable : true,
					hide : true
				}, {
					name : 'color',
					display : '颜色',
					sortable : true,
					hide : true
				}, {
					name : 'purchUserName',
					display : '采购负责人',
					sortable : true,
					hide : true
				}, {
					display : '工程启用',
					name : 'esmCanUse',
					process : function(v) {
						if (v == "1") {
							return "是";
						} else {
							return "否";
						}
					},
					sortable : true,
					width : 50,
					hide : true
				}, {
					name : 'createName',
					display : '创建人',
					sortable : true,
					hide : true
				}, {
					name : 'updateName',
					display : '修改人',
					sortable : true,
					hide : true
				}],
		toAddConfig : {
			toAddFn : function(p) {
				showThickboxWin("?model=stock_productinfo_productinfoAdd&action=toAdd&proType="
						+ $("#proType").val()
						+ "&proTypeId="
						+ $("#proTypeId").val()
						+ "&arrivalPeriod="
						+ $("#arrivalPeriod").val()
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=590&width=900");
			}
		},
		menusEx : [{
			name : 'view',
			text : "查看",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=stock_productinfo_productinfoAdd&action=view&id="
						+ row.id
						+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		},{
			name : 'submit',
			text : "提交",
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.state=='打回')
					return false;
				if(row.status=='0')
					return true;			
				return false;
			},
			action : function(row, rows, grid) {
				$.ajax({
					url:"?model=stock_productinfo_productinfoAdd&action=submitStatus&id="+ row.id,
					type:'get',
					dataType:'json',
					success:function(msg){
						if(msg==1){
							alert('提交成功！');
							show_page();
						}else{
							alert('提交失败！');
							//show_page();
						}
					}					
				});				
			}
		},{
			name : 'edit',
			text : "修改",
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.status=='0')
					return true;				
				return false;
			},
			action : function(row, rows, grid) {
				var editTypeResult = false;
				$.ajax({
					type : "POST",
					async : false,
					url : "?model=stock_productinfo_productinfoAdd&action=checkProType",
					data : {
						typeId : row.proTypeId
					},
					success : function(cresult) {
						if (cresult == 1)
							editTypeResult = true;
					}
				})
				showThickboxWin("?model=stock_productinfo_productinfoAdd&action=init&id="
						+ row.id
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				/*if (editTypeResult) {// 物料类型权限
					if ($("#productUpdate").val() != '1') {// 更新权限
						var editReresult = false;
						$.ajax({
							type : "POST",
							async : false,
							url : "?model=stock_productinfo_productinfoAdd&action=checkExistBusiness",
							data : {
								id : row.id
							},
							success : function(result) {
								if (result == 1)
									editReresult = true;
							}
						})
						if (editReresult) {// 修改权限
							showThickboxWin("?model=stock_productinfo_productinfoAdd&action=init&id="
									+ row.id
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");

						} else {
							alert("此物料已经存在关联业务信息,不可以修改,请联系管理员!");
						}
					} else {
						showThickboxWin("?model=stock_productinfo_productinfoAdd&action=init&id="
								+ row.id
								+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");

					}
				} else {
					alert("此类型物料你没有管理权限!");
				}*/

			}
		},{
			name : 'del',
			text : "删除",
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.status=='0')
					return true;
				if(row.state=='打回')
					return true;
				return false;
			},
			action : function(row, rows, grid) {
				$.ajax({
					type : "POST",
					async : false,
					url : "?model=stock_productinfo_productinfoAdd&action=ajaxdeletes",
					data : {
						id : row.id
					},
					success : function(result) {
						if (result == 1){
							show_page();
							alert('删除成功！');
						}
							
						else
							alert('删除失败！');
					}
				})
			}
		}/*, {
			name : 'view',
			text : "操作日志",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
						+ row.id
						+ "&tableName=oa_stock_product_info"
						+ "&productId=$rs[productId]&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}, {
			text : '更新',
			icon : 'edit',
			showMenuFn : function(row) {
				if ($("#productUpdate").val() == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				var editTypeResult = false;
				$.ajax({
					type : "POST",
					async : false,
					url : "?model=stock_productinfo_productinfo&action=checkProType",
					data : {
						typeId : row.proTypeId
					},
					success : function(cresult) {
						if (cresult == 1)
							editTypeResult = true;
					}
				})
				if (editTypeResult) {// 物料类型权限
					showThickboxWin('?model=stock_productinfo_productinfo&action=toUpdate&id='
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
				} else {
					alert("你没有此物料类型的管理权限!")

				}
			}
		}, {
			text : '检测关联',
			icon : 'view',
			action : function(row) {
				showThickboxWin('?model=stock_productinfo_productinfo&action=toViewRelation&id='
						+ row.id
						+ "&skey="
						+ row['skey_']
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900');
			}
		}, {
			text : '工程启用',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.esmCanUse == '0') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(confirm('确定启用么？')){
					$.ajax({
						type : "POST",
						async : false,
						url : "?model=stock_productinfo_productinfo&action=openEsmCanUse",
						data : {
							id : row.id,
							typeId : row.proTypeId
						},
						success : function(data) {
							if (data == 1){
								alert('启用成功');
								$("#productinfoGrid").yxgrid("reload");
							}else{
								alert('启用失败');
							}
						}
					})
				}
			}
		}, {
			text : '工程关闭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.esmCanUse == '1') {
					return true;
				}
				return false;
			},
			action : function(row) {
				if(confirm('关闭后不能在项目中进行选择，确认关闭吗？')){
					$.ajax({
						type : "POST",
						async : false,
						url : "?model=stock_productinfo_productinfo&action=closeEsmCanUse",
						data : {
							id : row.id,
							typeId : row.proTypeId
						},
						success : function(data) {
							if (data == 1){
								alert('关闭成功');
								$("#productinfoGrid").yxgrid("reload");
							}else{
								alert('关闭失败');
							}
						}
					})
				}
			}
		}*/],
		buttonsEx : [/*{
			name : 'import',
			text : "导入物料",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=stock_productinfo_productinfo&action=toUploadExcel"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
			}
		},{
			name : 'import',
			text : "更新物料",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=stock_productinfo_productinfo&action=toUploadUpdateExcel"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
			}
		},{
			name : 'importprice',
			text : "更新成本",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=stock_productinfo_productinfo&action=toUpdatePriceExcel"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
			}
		},{
			name : 'importk3',
			text : "更新K3",
			icon : 'excel',
			action : function(row) {
				showThickboxWin("?model=stock_productinfo_productinfo&action=toUploadK3Excel"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
			}			
		}, {
			name : 'expport',
			text : "导出物料",
			icon : 'excel',
			action : function(row) {
				window
						.open(
								"?model=stock_productinfo_productinfo&action=toExportExcel",
								"", "width=200,height=200,top=200,left=200");
			}
		}, {
			name : 'expport',
			text : "导出物料配件",
			icon : 'excel',
			action : function(row) {
				var proTypeId = $("#proTypeId").val();
				window.open(
						"?model=stock_productinfo_productinfo&action=toArmatureExcel&proTypeId="
								+ proTypeId, "",
						"width=800,height=800,top=200,left=200");
			}
		},{
			name : 'editaccess',
			text : "更新配件",
			icon : 'edit',
			action : function(row) {
				if($("#proTypeId").val()!=""){
					showThickboxWin("?model=stock_productinfo_productinfo&action=toEditProAccess&proTypeId="+$("#proTypeId").val()
							+"&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=800");					
				}else{
					alert("请选择物料分类！");
				}
			}
		}*/],
		// 高级搜索
		advSearchOptions : {
			modelName : 'productinfolist',
			searchConfig : [{
				name : '物料编号',
				value : 'c.productCode'
			},{
				name : '物料名称',
				value : 'c.productName'
			},{
				name:'规格型号',
				value:'c.pattern'
			},{
				name:'物料类型',
				value:'c.proType'
			},{
				name:'物料状态',
				value:'c.ext1',
				type:'select',
				datacode:'WLSTATUS'
			},{
				name:'K3编码',
				value:'c.ext2'					
			}]
		},
		/*
		event:{
			afterloaddata:function(e,data,g){
//				alert(g)
//				alert(e)
				var rows=g.getRows();
//				alert(rows)
				rows.each(function(){
//					alert($(this).data("data"))
					var data=$(this).data("data");
					if(data.ext1=="WLSTATUSKF"){
//						alert()
						$(this).addClass("trSelected");
					}
//
				})
			}
		},
		*/
		
		comboEx : [ {
			text : '提交状态',
			key : 'status',
			data : [ {
				text : '未提交',
				value : '0'
			}, {
				text : '已提交',
				value : '1'
			} ]
		} ,{
			text : '确认状态',
			key : 'state',
			data : [ {
				text : '未确认',
				value : '未确认'
			}, {
				text : '已确认',
				value : '已确认'
			} ]
		} ],
		searchitems : [{
					display : '物料编码',
					name : 'productCode'
				}, {
					display : '物料名称',
					name : 'productName'

				}, {
					display : '归属类型',
					name : 'ext3'
				},{
					display : '品牌',
					name : 'brand'
				},{
					display : '规格型号',
					name : 'pattern'
				},{
					name:'ext2',
					display:'K3编码'					
				}],
				sortname : "updateTime",
				// 默认搜索顺序
				sortorder : "desc"
	});
});