var show_page = function(page) {
	$("#personrecordsGrid").yxgrid("reload");
};
$(function() {
	$("#personrecordsGrid").yxgrid({
		model : 'engineering_tempperson_personrecords',
		title : '临聘人员记录',
		param : { "projectId" : $("#projectId").val() },
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		showcheckbox : false,
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				sortable : true,
				hide : true
			}, {
				name : 'thisDate',
				display : '日期',
				sortable : true,
				width : 80,
				process : function(v,row){
					if(row.id == 'noId') return v;
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_tempperson_personrecords&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'personName',
				display : '临聘人员',
				sortable : true,
				width : 80,
				process : function(v,row){
					if(row.id == 'noId') return v;
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_tempperson_tempperson&action=toView&id=" + row.personId + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=800\")'>" + v + "</a>";
				}
			}, {
				name : 'money',
				display : '工资及补助',
				process : function(v){
					return moneyFormat2(v);
				},
				sortable : true
			}, {
				name : 'workContent',
				display : '工作内容',
				sortable : true,
				width : 150
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
				name : 'activityName',
				display : '活动名称',
				sortable : true,
				hide : true
			}, {
				name : 'createName',
				display : '创建人',
				sortable : true
			}, {
				name : 'createTime',
				display : '创建时间',
				sortable : true,
				width : 140
			}, {
				name : 'updateName',
				display : '修改人名称',
				sortable : true,
				hide : true
			}, {
				name : 'updateTime',
				display : '修改时间',
				sortable : true,
				hide : true
			}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView'
		},
		searchitems : [{
			display : "临聘人员",
			name : 'personNameSearch'
		}]
	});
});