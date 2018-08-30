var show_page = function(page) {
	$("#esmresourcesGrid").yxgrid("reload");
};

$(function() {
	$("#esmresourcesGrid").yxgrid({
		model : 'engineering_resources_esmresources',
		action : 'viewListJson',
		title : '项目设备预算',
		param : {
			"projectId" : $("#projectId").val()
		},
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		showcheckbox : false,
		noCheckIdValue : 'noId',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'resourceTypeId',
				display : '设备类型id',
				sortable : true,
				hide : true
			}, {
				name : 'resourceTypeName',
				display : '设备类型',
				sortable : true
			},{
				name : 'resourceId',
				display : '设备id',
				sortable : true,
				hide : true
			}, {
				name : 'resourceCode',
				display : '设备编码',
				sortable : true,
				hide : true
			}, {
				name : 'resourceName',
				display : '设备名称',
				sortable : true,
				process : function(v,row){
					if(row.id == 'noId') return v;
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_resources_esmresources&action=init&perm=view&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "</a>";
				},
				width : 160
			}, {
				name : 'number',
				display : '数量',
				sortable : true,
				width : 60
			}, {
				name : 'unit',
				display : '单位',
				sortable : true,
				width : 60,
				hide : true
			}, {
				name : 'planBeginDate',
				display : '领用日期',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 80
			}, {
				name : 'planEndDate',
				display : '归还日期',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				width : 80
			}, {
				name : 'beignTime',
				display : '开始使用时间',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				hide : true,
				width : 80
			}, {
				name : 'endTime',
				display : '结束使用时间',
				sortable : true,
				process : function(v){
					if(v != '0000-00-00'){
						return v;
					}
				},
				hide : true,
				width : 80
			}, {
				name : 'useDays',
				display : '使用天数',
				sortable : true,
				width : 70
			}, {
				name : 'price',
				display : '单设备折旧',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'amount',
				display : '设备成本',
				sortable : true,
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'projectId',
				display : '项目id',
				sortable : true,
				hide : true
			}, {
				name : 'projectCode',
				display : '项目编号',
				sortable : true,
				hide : true
			}, {
				name : 'projectName',
				display : '项目名称',
				sortable : true,
				hide : true
			}, {
				name : 'activityId',
				display : '任务id',
				sortable : true,
				hide : true
			}, {
				name : 'activityName',
				display : '所属任务',
				sortable : true,
				hide : true
			}, {
				name : 'workContent',
				display : '工作内容',
				sortable : true,
            	width : 250,
				hide : true
			}, {
				name : 'remark',
				display : '备注说明',
				sortable : true,
				hide : true
			}, {
				name : 'applyNo',
				display : '申请单号',
				sortable : true,
				width : 120
			}, {
				name : 'status',
				display : '单据状态',
				sortable : true,
				width : 75,
				process : function(v){
					switch(v){
						case '0' : return '未处理';
						case '1' : return '处理中';
						case '2' : return '已处理';
						case '3' : return '已完成';
						default : return v;
					}
				},
				hide : true
			}, {
				name : 'ExaStatus',
				display : '审批状态',
				sortable : true,
				width : 75
			}, {
				name : 'sendNum',
				display : '已发出数量',
				sortable : true,
				width : 80
			}, {
				name : 'receviceNum',
				display : '确认接收数量',
				sortable : true,
				width : 80
			}
		],
		toViewConfig : {
			showMenuFn : function(row) {
				if (row.id == "noId") {
					return false;
				}
				return true;
			},
			formWidth : 900,
			formHeight : 400
		},
		searchitems : [{
				display : "设备名称",
				name : 'resourceNameSearch'
			}
		],
		sortname : 'activityId',
		sortorder : 'ASC'
	});
});