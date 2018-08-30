function updateUsingState(id){
	var sltVal = $("#useStateUpdateBtn_"+id).val();
	$.ajax({
		type : 'POST',
		url : '?model=customer_customer_customer&action=updateUsingState',
		data : {
			id : id,
			newVal : sltVal
		},
		success : function(data) {
			if(data == 'true'){
				alert("更新成功!");
			}else{
				alert("更新失败!")
			}
		}
	});
}

(function($) {

	// 初始化表头按钮数组
	buttonsArr = [{
		// 导入EXCEL文件按钮
		name : 'import',
		text : "导入EXCEL",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=customer_customer_customer&action=toUploadExcel"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=200&width=500")
		}
	}];

	mergeArr = {
		text : "合并客户",
		icon : 'edit',
		action : function(rowData, rows, rowIds, g) {
			if (rowData && rows.length > 1) {
				if (confirm("确定要合并选中的客户?合并后将更新所有关联的业务单据信息!此操作不可恢复,请先做好数据库备份.")) {
					var objectCode = prompt("请输入合并后的客户编码.", rowData.objectCode);
					if (objectCode) {
						$.ajax({
							type : 'POST',
							url : '?model=customer_customer_customer&action=mergerCustomer',
							data : {
								objectCode : objectCode,
								mergerIdArr : rowIds
							},
							success : function(data) {
								if (data == 1) {
									alert("合并客户成功.");
									g.reload();
								} else if (data == 2) {
									alert("没有权限对客户进行合并,请联系管理员添加权限.");
								} else {
									alert("合并客户失败.失败原因:" + data);
								}
							}
						});
					}
				}
			} else {
				alert("请至少选择两条客户记录进行合并.");

			}
		}
	};

	$.ajax({
				type : 'POST',
				url : '?model=customer_customer_customer&action=getLimits',
				data : {
					'limitName' : '合并客户'
				},
				async : false,
				success : function(data) {
					if (data == 1) {
						buttonsArr.push(mergeArr);
					}
				}
			});

	updateCodeArr = {
		// 导入EXCEL文件按钮
		text : "更新客户编码",
		icon : 'edit',
		action : function(rowData, rows, rowIds, g) {
			$.ajax({
				url : '?model=customer_customer_customer&action=updateCustomersCode',
				success : function(data) {
					if (data == '1') {
						alert("更新客户编码成功.");
						g.reload();
					} else {
						alert('更新客户编码失败');
					}
				}
			});
		}
	}

	$.ajax({
				type : 'POST',
				url : '?model=customer_customer_customer&action=getLimits',
				data : {
					'limitName' : '更新客户编码'
				},
				async : false,
				success : function(data) {
					if (data == 1) {
						buttonsArr.push(updateCodeArr);
					}
				}
			});

	$.woo.yxgrid.subclass('woo.yxgrid_customer', {
		options : {
			model : 'customer_customer_customer',
			// 表单
			colModel : [{
						display : 'id',
						name : 'id',
						sortable : true,
						hide : true
					}, {
						display : '客户编号',
						name : 'objectCode',
						sortable : true
					}, {
						display : '客户名称',
						name : 'Name',
						sortable : true
					}, {
						display : '销售工程师',
						name : 'SellMan',
						sortable : true,
						width : 150
					}, {
						display : '客户性质',
						name : 'TypeOne',
						sortable : true,
						datacode : 'KHLX'
					}, {
						display : '国家',
						name : 'Country',
						sortable : true
					}, {
						display : '省份',
						name : 'Prov',
						sortable : true
					}, {
						display : '城市',
						name : 'City',
						sortable : true
					}, {
						display : '使用状态',
						name : 'isUsing',
						// align: center,
						process: function (v,row) {
							var optStr = "";
							optStr += (v == 1)? "<option value='1' selected>开启</option>" : "<option value='1'>开启</option>";
							optStr += (v == 1)? "<option value='0'>关闭</option>" : "<option value='0' selected>关闭</option>";
							return "<div style='text-align: center;'><select class='useStateUpdateBtn' id='useStateUpdateBtn_"+row.id+"' onchange='javascript:updateUsingState("+row.id+")'>"+optStr+"</select></div>";
						},
						sortable : true
					}],
			/**
			 * 快速搜索
			 */
			searchitems : [{
						display : '客户名称',
						name : 'Name'
					}, {
						display : '客户编号',
						name : 'objectCodeLike'
					}],
			toAddConfig : {
				formWidth : 900,
				formHeight : 500
			},
			buttonsEx : buttonsArr,
			toViewConfig : {
				formWidth : 900,
				formHeight : 500,
				action : 'viewTab'
			},
			toEditConfig : {
				formWidth : 900,
				formHeight : 500
			},
			menusEx : [{
				text : '增加联系人',
				icon : 'add',
				action : function(row) {
					showThickboxWin('?model=customer_linkman_linkman&action=toAdd&id='
							+ row.id
							+ "&customerName="
							+ row.Name
							+ "&skey="
							+ row['skey_']
							+ '&isFromCustomer=1&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
				}

			},{
				text : '更新',
				icon : 'edit',
				showMenuFn : function(row){
					//判断是否有更新权限
					unAudit = $('#unAudit').length;
					if(unAudit == 0){
						$.ajax({
						    type: "POST",
						    url: "?model=customer_customer_customer&action=getLimits",
						    data : {
								'limitName' : '更新'
							},
						    async: false,
						    success: function(data){
						   		if(data == 1){
						   	   		unAudit = 1;
						   	   		$("#customerGrid").after("<input type='hidden' id='unAudit' value='1'/>");
								}else{
						   	   		$("#customerGrid").after("<input type='hidden' id='unAudit' value='0'/>");
								}
							}
						});
					}
					if($('#unAudit').val()*1 == 0){
						return false;
					}
					return true;
				},
				action : function(row) {
					showThickboxWin('?model=customer_customer_customer&action=toUpdate&id='
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ '&isFromCustomer=1&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
				}
			}, {
				text : '检测关联',
				icon : 'view',
				action : function(row) {
					showThickboxWin('?model=customer_customer_customer&action=toViewRelation&id='
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ '&isFromCustomer=1&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=900');
				}
			}

			],
			sortorder : "DESC",
			sortname : "id",
			title : '客户信息'
		}
	});
})(jQuery);