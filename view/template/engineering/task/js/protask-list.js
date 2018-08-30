var show_page = function(page) {
	$(".protasklist").yxgrid("reload");
};
$(function() {
	// var proIdValue = parent.document.getElementById("proId").value;
	var pjId = $('#projectId').val();
	$(".protasklist").yxgrid({
		// 如果传入url，则用传入的url，否则使用model及action自动组装



		model : 'engineering_task_protask',
            /**
			 * 是否显示查看按钮/菜单
			 */
			isViewAction : false,
			/**
			 * 是否显示修改按钮/菜单
			 */
			isEditAction : false,
			/**
			 * 是否显示删除按钮/菜单
			 */
            isDelAction : false,

		// 扩展右键菜单

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
							+ '&pjId='
							+ pjId
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
		menusEx : [

		{
			text : '删除',
			icon : 'delete',
			showMenuFn : function(row){
				if(	row.status == 'WFB'){
					return true;
				}
				return false;
			},
			action: function(row){
                showThickboxWin('?model=engineering_task_protask&action=deletes&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');
			}



		},
		 {
			name : 'publishTask',
			text : '发布',
			icon : 'add',
			showMenuFn : function (row){
			   if(row.status=='WFB'){
			       return true;
			   }
			       return false;
			},
			action : function(row) {
				$.get('index1.php', {
					model : 'engineering_task_protask',
					action : 'publishTask',
					id : row.id

				}, function(data) {
					alert(data);
					show_page();
					return;
				})
			}
		}, {

			text : '编辑',
			icon : 'edit',
			showMenuFn : function (row){
			   if(row.status=='WFB'){
			       return true;
			   }
			       return false;
			},
			action : function(row) {

				showThickboxWin('?model=engineering_task_protask&action=edit&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');

			}
		},{

			text : '变更',
			icon : 'edit',
			 showMenuFn : function (row){
			   if(row.status=='WFB'){
			       return false;
			   }else if(row.status=='QZZZ'){
			       return false;
			   }else if(row.status=='ZT'){
			       return false;
			   }
			       return true;
			},
			action : function(row) {

				showThickboxWin('?model=engineering_task_protask&action=editTab&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=900');

			}
		}, {
			name : 'pause',
			text : '暂停',
			icon : 'delete',
			showMenuFn : function (row){
			   if(row.status=='WFB'){
			       return false;
			   }else if(row.status=='QZZZ'){
			       return false;
			   }else if(row.status=='ZT') {
			       return false;
			   }
			       return true;
			},
			action : function(row) {
				showThickboxWin('?model=engineering_task_stophistory&action=toStopTask&id='
						+ row.id
						+ '&status='
						+ row.status
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=600');
				// alert("暂未开放")
			}
		}, {
			name : 'renew',
			text : '恢复',
			icon : 'edit',
			showMenuFn : function (row){
			   if(row.status=='ZT'){
			       return true;
			   }
			       return false;
			},
			action : function(row) {
				showThickboxWin('?model=engineering_task_stophistory&action=toReBackTask&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=600');
			}
		}, {
			name : '',
			text : '强制终止',
			icon : 'delete',
			showMenuFn : function (row){
			   if(row.status=='WFB'){
			       return false;
			   }else if(row.status=='QZZZ'){
			       return false;
			   }
			       return true;
			},
			action : function(row) {

				showThickboxWin('?model=engineering_task_tkover&action=toAdd&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=250&width=600');
			}
		}, {
			name : '',
			text : '查看',
			icon : 'view',
			action : function(row) {

				showThickboxWin('?model=engineering_task_protask&action=taskTab&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=800&width=850');
			}
		}],



		// 表单
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display : '任务名称',
			name : 'name',
			sortable : true,
			width : 80
		},

				// {
				// display : '所属项目',
				// name : 'projectName',
				// sortable : true,
				// width : 80
				// },

				{
					display : '优先级',
					name : 'priority',
					sortable : true,
					width : 100
				}, {
					display : '状态',
					name : 'status',
					sortable : true,
					datacode : 'XMRWZT',
					width : 100
				}, {
					display : '完成率',
					name : 'effortRate',
					sortable : true,
					width : 100
				}, {
					display : '偏差率',
					name : 'warpRate',
					sortable : true,
					width : 100
				}, {
					display : '责任人',
					name : 'chargeName',
					sortable : true,
					width : 100
				}, {
					display : '发布人',
					name : 'publishName',
					sortable : true,
					width : 100
				}, {
					display : '计划开始时间',
					name : 'planBeginDate',
					sortable : true,
					width : 100
				}, {
					display : '计划完成时间',
					name : 'planEndDate',
					sortable : true,
					width : 100
				}, {
					display : '任务类型',
					name : 'taskType',
					width : 100,
					sortable : true
				}],
		//根据pjId 过滤页面信息
		param : {
			"projectId" : $("#projectId").val()
		        },

		/**
		 * 快速搜索
		 */
		searchitems : [{
			display : '任务名称',
			name : 'name'
		}],
		sortorder : "ASC",
		title : '项目任务'
	});
});