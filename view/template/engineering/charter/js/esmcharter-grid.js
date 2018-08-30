var show_page = function(page) {
	$("#esmcharterGrid").yxgrid("reload");
};

$(function() {
    $("#esmcharterGrid").yxgrid({
        model : 'engineering_charter_esmcharter',
        title : '项目章程',
        isDelAction : false,
        isAddAction : false,
        //列信息
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        },{
            name : 'contractId',
            display : '合同id',
            sortable : true,
            hide : true
        },{
            name : 'contractCode',
            display : '合同编号',
            sortable : true,
            hide : true
        },{
            name : 'rObjCode',
            display : '业务编号',
            sortable : true,
            hide : true
        },{
            name : 'projectId',
            display : '项目id',
            sortable : true,
            hide : true
        },{
            name : 'projectCode',
            display : '项目编号',
            sortable : true
        },{
            name : 'projectName',
            display : '项目名称',
            sortable : true
        },{
            name : 'workRate',
            display : '工作占比',
            sortable : true,
			process : function(v){
				return v + ' %';
			}
        } ,{
            name : 'proName',
            display : '所属省份',
            sortable : true
        },{
            name : 'proCode',
            display : '省份编码',
            sortable : true,
            hide : true
        },{
            name : 'officeName',
            display : '办事处',
            sortable : true
        },{
            name : 'officeId',
            display : '办事处id',
            sortable : true,
            hide : true
        },{
            name : 'deptName',
            display : '部门名称',
            sortable : true
        },{
            name : 'deptId',
            display : '部门id',
            sortable : true,
            hide : true
        },{
            name : 'managerName',
            display : '项目经理',
            sortable : true
        },{
            name : 'managerId',
            display : '项目经理账号',
            sortable : true,
            hide : true
        },{
            name : 'planBeginDate',
            display : '预计启动日期',
            sortable : true
        },{
            name : 'planEndDate',
            display : '预计结束日期',
            sortable : true
        },{
            name : 'projectObjectives',
            display : '项目目标',
            sortable : true
        },{
            name : 'description',
            display : '项目描述',
            sortable : true,
            hide : true
        },{
            name : 'remark',
            display : '备注',
            sortable : true,
            hide : true
        },{
            name : 'createId',
            display : '创建人Id',
            sortable : true,
            hide : true
        },{
            name : 'createName',
            display : '创建人名称',
            sortable : true,
            hide : true
        },{
            name : 'createTime',
            display : '创建时间',
            sortable : true,
            hide : true
        },{
            name : 'updateId',
            display : '修改人Id',
            sortable : true,
            hide : true
        },{
            name : 'updateName',
            display : '修改人名称',
            sortable : true,
            hide : true
        },{
            name : 'updateTime',
            display : '修改时间',
            sortable : true,
            hide : true
        }],
        toAddConfig : {
			formWidth : 1000,
			formHeight : 550
		},
		toEditConfig : {
			formWidth : 800,
			formHeight : 500
		},
		toViewConfig : {
			formWidth : 1000,
			formHeight : 500
		},
		searchitems : [{
			display : '指定办事处',
			name : 'officeName'
		}, {
			display : '项目编号',
			name : 'projectCode'
		}, {
			display : '项目名称',
			name : 'projectName'
		}],
		// 默认搜索字段名
		sortname : "id",
		// 默认搜索顺序 降序
		sortorder : "DESC"
    });
});