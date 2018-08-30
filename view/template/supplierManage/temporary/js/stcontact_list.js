// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".supplinkmanGrid").yxgrid("reload");
};
$(function() {
	$(".supplinkmanGrid").yxgrid({
		tittle : '添加联系人 －－ 添加基本信息（2/4）',
		//如果传入url，则用传入的url，否则使用model及action自动组装
		//url : '',
		// '?model=customer_customer_customer&action=pageJson',
		model : 'supplierManage_temporary_stcontact',
		//						action : 'getById',
		action : 'pageJson&parentId=' + $("#parentId").val(),
		//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '联系人姓名',
			name : 'name',
			sortable : true,
			//特殊处理字段函数
			process : function(v, row) {
				return row.name;
			}
		}
				//								,{
				//									display : '供应商编号',
				//									name : 'busiCode',
				//									sortable : true
				//								}
				, {
					display : '邮箱地址',
					name : 'email',
					sortable : true
				}, {
					display : '座机',
					name : 'plane',
					sortable : true
				}, {
					display : '传真',
					name : 'fax',
					sortable : true
				}],
		//扩展按钮
		buttonsEx : [{
			name : 'upgoon',
			text : "上一步",
			icon : 'edit',
			action : function(row) {
				location = "?model=supplierManage_temporary_temporary&action=toEdit1&id="
						+ $("#parentId").val()
						+ "&parentCode="
						+ $("#parentCode").val()
			}
		}, {
			name : 'goon',
			text : "下一步",
			icon : 'add',
			action : function(row) {
				location = "?model=supplierManage_temporary_stproduct&action=stpToAdd&parentId="
						+ $("#parentId").val()
						+ "&parentCode="
						+ $("#parentCode").val()
			}
		}],
		//扩展右键菜单
		menusEx : [],
		//快速搜索
		searchitems : [{
			display : '联系人姓名',
			name : 'name'
		}],
		// title : '客户信息',
		//业务对象名称
		boName : '供应商联系人',
		//默认搜索字段名
		sortname : "name",
		//默认搜索顺序
		sortorder : "ASC",
		//显示查看按钮
		isViewAction : true,
		//查看扩展信息
		toViewConfig : {
			action : 'toRead'
		},
		//修改扩展信息
		toEditConfig : {
			action : "toEdit"
		},

		//重写添加方法
		toAddConfig : {
			text : '新增',
			/**
			 * 默认点击新增按钮触发事件
			 */
			toAddFn : function(p) {
				var c = p.toAddConfig;
				var w = c.formWidth ? c.formWidth : p.formWidth;
				var h = c.formHeight ? c.formHeight : p.formHeight;
				showThickboxWin("?model="
						+ p.model
						+ "&action="
						+ c.action
						+ "&parentId="
						+ $("#parentId").val()
						+ "&parentCode="
						+ $("#parentCode").val()
						+ c.plusUrl
						+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
						+ h + "&width=" + w);
			},
			/**
			 * 新增表单调用的后台方法
			 */
			action : 'toAdd',
			/**
			 * 追加的url
			 */
			plusUrl : '',
			/**
			 * 新增表单默认宽度
			 */
			formWidth : 0,
			/**
			 * 新增表单默认高度
			 */
			formHeight : 0
		},

		toViewConfig : {
			text : '查看',
			/**
			 * 默认点击查看按钮触发事件
			 */
			toViewFn : function(p, g) {
				var c = p.toViewConfig;
				var rowObj = g.getSelectedRow();
				if (rowObj) {
					showThickboxWin("?model="
							+ p.model
							+ "&action="
							+ p.toViewConfig.action
							+ c.plusUrl
							+ "&id="
							+ rowObj.data('data').id
							+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ 300 + "&width=" + 500);
				} else {
					alert('请选择一行记录！');
				}
			},
			/**
			 * 加载表单默认调用的后台方法
			 */
			action : 'init',
			/**
			 * 追加的url
			 */
			plusUrl : '',
			/**
			 * 查看表单默认宽度
			 */
			formWidth : 0,
			/**
			 * 查看表单默认高度
			 */
			formHeight : 0
		}

	});

});