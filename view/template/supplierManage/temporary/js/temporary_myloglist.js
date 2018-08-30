// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".contactGrid").yxgrid("reload");
};
$(function() {
	$(".contactGrid").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		// url :
		// '?model=customer_customer_customer&action=pageJson',
		model : 'supplierManage_temporary_temporary',
		action : 'mylogpageJson',
		title : "我注册的供应商",
		isAddAction:true,
		isEditAction:false,
		isViewAction:false,
		isDelAction:false,
		showcheckbox : false,
		// 列信息
		colModel : [
				{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '供应商名称',
					name : 'suppName',
					sortable : true,
					// 特殊处理字段函数
					process : function(v, row) {
						return row.suppName;
					}
				}, {
					display : '供应商编号',
					name : 'busiCode',
					sortable : true
				}, {
					display : '主要产品',
					name : 'products',
					sortable : true,
					width : 200
				}, {
					display : '地址',
					name : 'address',
					hide : true,
					width : 200
				}, {
					display : '传真',
					name : 'fax',
					sortable : true
				}, {
					display : '审核状态',
					name : 'ExaStatus',
					sortable : true
				}, {
					display : '状态',
					name : 'status',
					sortable : true,
					process : function(v) {
						if (v == 3) {
							return "已进入运营库";
						} else {
							return "未进入运营库";
						}
					}
				}, {
					display : '供货生效日期',
					name : 'effectDate',
					sortable : true
				}, {
					display : '供货失效日期',
					name : 'failureDate',
					sortable : true
				}, {
					display : '创建时间',
					name : 'createTime',
					sortable : true,
					width : 150
				}],
		toAddConfig : {
			toAddFn : function(p) {
				var c = p.toAddConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				showThickboxWin("?model="
						+ p.model
						+ "&action="
						+ c.action
						+ "&flag=1"
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ h + "&width=" + w);
			},

			formWidth : 1200,
			/**
			 * 新增表单默认高度
			 */
			formHeight : 500

		},
		// 扩展右键菜单
		menusEx : [
		{
			text : '查看',
			icon : 'view',
			action :function(row,rows,grid) {
				showThickboxWin("?model=supplierManage_temporary_temporary&action=init"
					+ "&id="
					+ row.id
					+"&skey="+row['skey_']
					+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
					+ 600 + "&width=" + 840);
			}
		},
		{
			text : '编辑',
			icon : 'edit',
			showMenuFn : function(row){
				if(row.ExaStatus == '完成' || row.ExaStatus == '部门审批'){
					return false;
				}
				return true;
			},
			action : function(row,rows,grid) {
				if(row){
					showThickboxWin("?model=supplierManage_temporary_temporary&action=init"
						+ "&id="
						+ row.id
						+"&skey="+row['skey_']
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ 600 + "&width=" + 900);
				}else{
					alert("请选中一条数据");
				}
			}
		},
//		{
//			text : '删除',
//			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.ExaStatus == '未提交') {
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if(confirm('确认删除？')){
//					$.ajax({
//						type : "POST",
//						url : "?model=supplierManage_temporary_temporary&action=ajaxdeletes",
//						data : {
//							id : row.id
//						},
//						success : function(msg) {
//							if (msg == 1) {
//								alert('删除成功！');
//								$(".supplierGrid").yxgrid("reload");
//							}else{
//								alert('删除失败!');
//							}
//						}
//					});
//				}
//			}
//		},
			{
			name : 'exam',
			text : "提交审批",
			icon : 'edit',
			showMenuFn : function(row) {
					if (row.ExaStatus=='打回'||row.ExaStatus=='未提交') {
						return true;
					}
					return false;
			},
			action : function(row, rows, rowIds) {
				location = 'controller/supplierManage/temporary/ewf_index.php?actTo=ewfSelect&formName=供应商审核&examCode=oa_supp_lib_temp&billId='
						+ row.id
			}
		},

		{
			name : 'approval',
			text : "录入运营库",
			icon : 'add',
			showMenuFn : function(row) {
					if (row.ExaStatus == '完成'&& row.status!='3') {
						return true;
					}
					return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					if (row.ExaStatus == '完成') {
						if (confirm("确定要将供应商【" + row.suppName + "】录入正式库吗？")) {
							showThickboxWin("?model=supplierManage_temporary_temporary&action=putInFormal&id="
											+ row.id
											+ "&objCode="
											+ row.objCode
											+ "&placeValuesBefore&TB_iframe=true&modal=false&height=150&width=400");
//							$.ajax({
//									type:"POST",
//									url:"?model=supplierManage_temporary_temporary&action=putInFormal",
//									data:{id:row.id},
//							});
						}
					} else {
						alert("未通过审批的供应商不能录入正式库");
					}
				} else {
					alert('请选择一行记录！');
				}
			}
		}
		,{
			text : '删除供应商',
			icon : 'delete',
//			showMenuFn : function(row) {
//				if (row.ExaStatus == '完成') {
//					return true;
//				}
//				return false;
//			},
			action : function(row) {
				if(confirm('确认删除？')){
					$.ajax({
						type : "POST",
						url : "?model=supplierManage_temporary_temporary&action=delSupplier",
						data : {
							id : row.id
						},
						success : function(msg) {
							if (msg == 1) {
								alert('删除成功！');
								$(".contactGrid").yxgrid("reload");
							}else{
								alert('删除失败!');
							}
						}
					});
				}
			}
		}],
		// 快速搜索
		searchitems : [{
					display : '供应商名称',
					name : 'suppName'
				}, {
					display : '主营产品',
					name : 'productName'
				}, {
					display : '状态',
					name : 'status'
				}],
		// title : '客户信息',
		// 业务对象名称
		boName : '供应商名称',
		// 默认搜索字段名
		sortname : "updateTime",
		// 默认搜索顺序
		sortorder : "DESC"
//		// 显示查看按钮
//		isViewAction : true,
//		// 隐藏添加按钮
//		isAddAction : false,
//		// 隐藏添加按钮
//		isEditAction : true,
//		// 隐藏删除按钮
//		isDelAction : false,
		// 查看扩展信息
//		toViewConfig : {
//			text : '查看',
//			/**
//			 * 默认点击查看按钮触发事件
//			 */
//			toViewFn : function(p, g) {
//				var c = p.toViewConfig;
//				var w = c.formWidth ? c.formWidth : p.formWidth;
//				var h = c.formHeight ? c.formHeight : p.formHeight;
//				var rowObj = g.getSelectedRow();
//				if (rowObj) {
//					showThickboxWin("?model="
//							+ p.model
//							+ "&action="
//							+ p.toViewConfig.action
//							+ c.plusUrl
//							+ "&id="
//							+ rowObj.data('data').id
//							+ "&objCode="
//							+ rowObj.data('data').objCode
//							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
//							+ 600 + "&width=" + 800);
//				} else {
//					alert('请选择一行记录！');
//				}
//			},
//			/**
//			 * 加载表单默认调用的后台方法
//			 */
//			action : 'init'
//		},

		// 修改扩展信息
//		toEditConfig : {
//			text : '编辑',
//			/**
//			 * 默认点击编辑按钮触发事件
//			 */
//			toEditFn : function(p, g) {
//				var c = p.toEditConfig;
//				var w = c.formWidth ? c.formWidth : p.formWidth;
//				var h = c.formHeight ? c.formHeight : p.formHeight;
//				var rowObj = g.getSelectedRow();
//				if (rowObj) {
//					showThickboxWin("?model="
//							+ p.model
//							+ "&action="
//							+ c.action
//							+ c.plusUrl
//							+ "&id="
//							+ rowObj.data('data').id
//							+ "&objCode="
//							+ rowObj.data('data').objCode
//							+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
//							+ 600 + "&width=" + 800);
//				} else {
//					alert('请选择一行记录！');
//				}
//			},
//			/**
//			 * 加载表单默认调用的后台方法
//			 */
//			action : 'init'
//
//		}
	});

});