var show_page = function(page) {
	$("#esmmemberGrid").yxgrid("reload");
};
$(function() {

	var projectId = $("#projectId").val();
	$("#esmmemberGrid").yxgrid({
		model : 'engineering_member_esmmember',
		param : {
			"projectId" : $("#projectId").val()
		},
		title : '项目成员',
		isDelAction : false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		showcheckbox : false,
		noCheckIdValue : 'noId',
		isOpButton : false,
		//列信息
        //列信息
        colModel : [{
            display : 'id',
            name : 'id',
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
            name : 'memberName',
            display : '姓名',
            sortable : true,
            width : 80,
            process : function(v,row){
                if(row.id == 'noId' || row.memberId == 'SYSTEM') return v;
                return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=engineering_member_esmmember&action=toView&id=" + row.id + '&skey=' + row.skey_ + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=900\")'>" + v + "</a>";
            }
        }, {
            name : 'memberId',
            display : '成员id',
            sortable : true,
            hide : true
        }, {
            name : 'personLevel',
            display : '级别',
            sortable : true,
            width : 50,
            process : function(v,row){
                if(row.memberId != 'SYSTEM') return v;
            }
        }, {
            name : 'beginDate',
            display : '加入项目',
            sortable : true,
            width : 80,
            hide : true
        }, {
            name : 'endDate',
            display : '离开项目',
            sortable : true,
            width : 80,
            hide : true
        }, {
            name : 'roleName',
            display : '角色',
            sortable : true,
            width : 70
        }, {
            name : 'activityName',
            display : '工作内容',
            width : 150,
            sortable : true,
            hide : true
        }, {
            name : 'feeDay',
            display : '参与天数',
            sortable : true,
            process : function(v,row){
                if(row.beginDate == ''){
                    return '0.00';
                }
                if(row.endDate == ''){
                    return "<span class='green' title='即时信息'>" + moneyFormat2(v) + "</span>";
                }else{
                    return moneyFormat2(v);
                }
            },
            width : 70
        }, {
            name : 'status',
            display : '状态',
            process : function(v,row){
                if(row.id != 'noId'){
                    if(v == 1){
                        return '离开项目';
                    }else {
                        return '正常';
                    };
                }
            },
            width : 70
        }, {
            name : 'feePeople',
            display : '人力成本(天)',
            sortable : true,
            process : function(v,row){
                if(row.beginDate == ''){
                    return '0.00';
                }
                if(row.endDate == ''){
                    return "<span class='green' title='即时信息'>" + moneyFormat2(v) + "</span>";
                }else{
                    return moneyFormat2(v);
                }
            },
            width : 70,
            hide : true
        }, {
            name : 'feePerson',
            display : '人力成本(确认)',
            sortable : true,
            process : function(v,row){
                if(row.memberId == 'SYSTEM'){
                    return moneyFormat2(v);
                }else{
                    if(row.beginDate == ''){
                        return '0.00';
                    }
                    if(row.endDate == ''){
                        return "<span class='green' title='即时信息'>" + moneyFormat2(v) + "</span>";
                    }else{
                        return moneyFormat2(v);
                    }
                }
            },
            width : 80
        }, {
            name : 'feePersonCount',
            display : '人力成本(实时)',
            sortable : true,
            process : function(v,row){
                if(row.beginDate == ''){
                    return '0.00';
                }
                if(row.endDate == ''){
                    return "<span class='green' title='即时信息'>" + moneyFormat2(v) + "</span>";
                }else{
                    return moneyFormat2(v);
                }
            },
            width : 80,
            hide : true
        }, {
            name : 'costMoney',
            display : '录入费用',
            sortable : true,
            width : 70,
            process : function(v){
                return moneyFormat2(v);
            }
        }, {
            name : 'unconfirmMoney',
            display : '未确认费用',
            sortable : true,
            width : 70,
            process : function(v){
                return moneyFormat2(v);
            }
        }, {
            name : 'confirmMoney',
            display : '已确认费用',
            sortable : true,
            width : 70,
            process : function(v){
                return moneyFormat2(v);
            }
        }, {
            name : 'backMoney',
            display : '打回费用',
            sortable : true,
            width : 70,
            process : function(v){
                return moneyFormat2(v);
            }
        }, {
            name : 'unexpenseMoney',
            display : '未报销费用',
            sortable : true,
            width : 70,
            process : function(v){
                return moneyFormat2(v);
            }
        }, {
            name : 'expensingMoney',
            display : '在报销费用',
            sortable : true,
            width : 70,
            process : function(v){
                return moneyFormat2(v);
            }
        }, {
            name : 'expenseMoney',
            display : '已报销费用',
            sortable : true,
            width : 70,
            process : function(v){
                return moneyFormat2(v);
            }
        }, {
            name : 'remark',
            display : '备注说明',
            sortable : true,
            hide : true
        }],
		toViewConfig : {
			formWidth : 900,
			formHeight : 400,
			action : 'toView',
			showMenuFn : function(row){
				if(row.memberId != 'SYSTEM'){
					return true;
				}
				return false;
			}
		},
		searchitems : [{
			display : "成员名称",
			name : 'memberNameSearch'
		},{
			display : "成员等级",
			name : 'personLevelSearch'
		}],
        comboEx :[{
            text : "状态",
            key : 'status',
            value : '0',
            data : [{
                text : '正常',
                value : 0
            }, {
                text : '离开项目',
                value : 1
            }]
        }],
		sortorder : 'ASC',
		sortname : 'personLevel'
	});
});