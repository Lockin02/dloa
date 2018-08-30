var show_page = function() {
	$("#esmprojectGrid").yxgrid("reload");
};

$(function() {
	var combogrid = window.dialogArguments[0];
	var p = combogrid.options;
	var eventStr = jQuery.extend(true, {}, p.gridOptions.event);
	if (eventStr.row_dblclick) {
		var dbclickFunLast = eventStr.row_dblclick;
		eventStr.row_dblclick = function(e, row, data) {
			dbclickFunLast(e, row, data);
			window.returnValue = row.data('data');
			window.close();
		};
	} else {
		eventStr.row_dblclick = function(e, row, data) {
			window.returnValue = row.data('data');
			window.close();
		};
	}

	var gridOptions = combogrid.options.gridOptions;
	//项目状态下拉值，默认为在建
	var statusComboExVal = 'GCXMZT02';
	if(gridOptions.param != undefined){
		var statusArr = gridOptions.param.statusArr;
		if(statusArr != undefined){
			if(statusArr.indexOf(',') != -1){//传入值有多个，则下拉默认值为所有
				statusComboExVal = '';
			}else{
				statusComboExVal = statusArr;
			}
		}
	}
	
	// 设置只显示在建和完工状态的情况 PMS2600
    var comboExArr = (gridOptions.param != undefined && gridOptions.param.setStatusComboEx == 'true')?
        // 设置过滤选项为完工和在建
        [{
            text: "项目状态",
            key: 'status',
            data : [
                {
                    text: '在建',
                    value: 'GCXMZT02'
                },
                {
                    text: '完工',
                    value: 'GCXMZT04'
                }
            ],
            value : statusComboExVal
        }]
        :
        // 默认显示所有状态选项
        [{
            text: "项目状态",
            key: 'status',
            datacode : 'GCXMZT',
            value : statusComboExVal
        }];

    $("#esmprojectGrid").yxgrid({
        model : 'engineering_project_esmproject',
		action : gridOptions.action,
        param : gridOptions.param,
		title : "双击选择项目",
		isDelAction : false,
		isAddAction : false,
		isViewAction : false,
		isEditAction : false,
		showcheckbox : false,
        isOpButton : false,
        autoload: false,
		customCode : 'esmprojectGridSelect',
        //列信息
        colModel : [{
            display : 'id',
            name : 'id',
            sortable : true,
            hide : true
        },{
            name : 'officeId',
            display : '办事处ID',
            sortable : true,
            hide : true
        },{
            name : 'officeName',
            display : '区域',
            sortable : true
        },{
            name : 'projectName',
            display : '项目名称',
            sortable : true,
            width : 150
        },{
            name : 'projectCode',
            display : '项目编号',
            sortable : true,
            width : 120
        },{
            name : 'status',
            display : '项目状态',
            sortable : true,
			datacode : 'GCXMZT',
            width : 70
        },{
            name : 'contractId',
            display : '鼎利合同id',
            sortable : true,
            hide : true
        },{
            name : 'contractCode',
            display : '鼎利合同编号',
            sortable : true,
            width : 160,
            hide : true
        },{
            name : 'contractTempCode',
            display : '临时合同编号',
            sortable : true,
            width : 160,
            hide : true
        },{
            name : 'rObjCode',
            display : '业务编号',
            sortable : true,
            width : 120,
            hide : true
        },{
            name : 'customerId',
            display : '客户id',
            sortable : true,
            hide : true
        },{
            name : 'customerName',
            display : '客户名称',
            sortable : true,
            hide : true
        },{
            name : 'province',
            display : '所属省份',
            width : 80,
            sortable : true
        },{
            name : 'deptName',
            display : '所属部门',
            sortable : true,
            hide : true
        },{
            name : 'managerName',
            display : '项目经理',
            sortable : true
        },{
            name : 'ExaStatus',
            display : '审批状态',
            sortable : true,
            width : 80,
            hide : true
        },{
            name : 'ExaDT',
            display : '审批日期',
            sortable : true,
            hide : true,
            width : 80
        },{
            name : 'peopleNumber',
            display : '人数',
            sortable : true,
            width : 80,
            hide : true
        },{
            name : 'nature',
            display : '网络性质',
            sortable : true,
            datacode : 'GCXMXZ',
            hide : true
        },{
            name : 'outsourcing',
            display : '外包类型',
            sortable : true,
			datacode : 'WBLX',
            width : 80,
            hide : true
        },{
            name : 'cycle',
            display : '长/短期',
            sortable : true,
            width : 80,
            datacode : 'GCCDQ',
            hide : true
        },{
            name : 'category',
            display : '项目类别',
            sortable : true,
            width : 80,
            datacode : 'XMLB',
            hide : true
        }],
		searchitems : [{
			display : '区域',
			name : 'officeName'
		}, {
			display : '项目编号',
			name : 'projectCodeSearch'
		}, {
			display : '项目名称',
			name : 'projectName'
		}, {
			display : '项目经理',
			name : 'managerName'
		}],
		// 审批状态数据过滤
		comboEx : comboExArr,
		sortname : gridOptions.sortname,
		sortorder : gridOptions.sortorder,
		// 把事件复制过来
		event : eventStr
    });
});