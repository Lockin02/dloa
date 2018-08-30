// 用于新增/修改后回调刷新表格
var show_page = function(page) {
	$(".suppfileGrid").yxgrid("reload");
};
$(function() {
	$(".suppfileGrid").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装
		url : '?model=file_uploadfile_management&action=pageJson&objTable='
				+ $("#objTable").val() + '&objId=' + $("#objId").val(),
		// model : '?model=file_uploadfile_management',
		// action : 'pageJson&objTable=' + $('#objTable').val() + '&objId=' +
		// $('#objId').val(),
		showcheckbox : true,
		title : '附件信息',
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '上传人',
			name : 'createName',
			sortable : true,
			// 特殊处理字段函数
			process : function(v, row) {
				return row.createName;
			}
		}, {
			display : '上传时间',
			name : 'createTime',
			sortable : true,
			width : '150'
		}, {
			display : '文件名',
			name : 'originalName',
			sortable : true,
			width : '150'
		}, {
			display : '新文件名',
			name : 'newName',
			sortable : true,
			width : '150'
		}, {
			display : '文件大小',
			name : 'tFileSize',
			sortable : true,
			process : function(v) {
				return v + ' <font color="green">kb</font>';
			}
		}],

		/**
		 * 删除属性配置
		 */
		toDelConfig : {
			text : '删除',
			/**
			 * 默认点击删除按钮触发事件
			 */
			toDelFn : function(p, g) {
				var rowIds = g.getCheckedRowIds();
				if (rowIds[0]) {
					if (window.confirm("确认要删除?")) {
						$.ajax({
							type : "POST",
							url : "?model=file_uploadfile_management&action="
									+ p.toDelConfig.action
									+ p.toDelConfig.plusUrl,
							data : {
								id : g.getCheckedRowIds().toString()
							// 转换成以,隔开方式
							},
							success : function(msg) {
								if (msg == 1) {
									g.reload();
									alert('删除成功！');
								}
							}
						});
					}
				} else {
					alert('请选择一行记录！');
				}
			},
			/**
			 * 删除默认调用的后台方法
			 */
			action : 'ajaxdeletes',
			/**
			 * 追加的url
			 */
			plusUrl : ''
		},

		// 扩展按钮
		buttonsEx : [{
			name : 'onload',
			text : '上传',
			icon : 'add',
			action : function() {
				showThickboxWin("?model=file_uploadfile_management&action=editInfo&objId="
						+ $('#objId').val()
						+ "&objTable="
						+ $('#objTable').val()
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
			}
		}],
		// 扩展右键菜单
		menusEx : [{
			text : '下载',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					location = "?model=file_uploadfile_management&action=toDownFile&newName="
							+ row.newName
							+ "&originalName="
							+ row.originalName
							+ "&inDocument=" + row.inDocument;
				} else {
					alert("请选中一条数据");
				}
			}
		}, {
			text : '查看',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=file_uploadfile_management&action=readInfo&id="
							+ row.id
							+ "&objCode="
							+ row.objCode
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=600");
				} else {
					alert("请选中一条数据");
				}
			}
		}],
		// title : '客户信息',
		/**
		 * 是否显示工具栏
		 *
		 * @type Boolean
		 */
		isToolBar : true,
		// 业务对象名称
		boName : '附件',
		// 默认搜索字段名
		sortname : "createName",
		// 默认搜索顺序
		sortorder : "ASC",
		// 显示查看按钮
		isViewAction : false,
		// 隐藏添加按钮
		isAddAction : false,
		// 隐藏删除按钮
		isDelAction : true,
		isEditAction : false

	});

});