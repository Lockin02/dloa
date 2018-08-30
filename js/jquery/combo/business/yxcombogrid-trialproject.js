/**
 * 物料基本信息下拉表格组件
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_trialproject', {
		options : {
			hiddenId : 'id',
			nameCol : 'projectCode',
			gridOptions : {
				showcheckbox : false,
				model : 'projectmanagent_trialproject_trialproject',
				action : 'pageJsonCombogrid',
				//列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'projectCode',
			display : '项目编号',
			sortable : true
		}, {
			name : 'projectName',
			display : '项目名称',
			sortable : true
		},{
		    name : 'serCon',
		    display : '提交状态',
		    sortable : true,
		    process : function(v,row){
		       if(row.id == "noId"){
					return '';
				}
				switch(v){
					case '0' : return '未提交';break;
					case '1' : return '已提交';break;
					case '2' : return '打回';break;
					case '3' : return '延期申请';break;
					case '4' : return '延期申请打回';break;
					default : return v;
				}
		    }
		}, {
			name : 'beginDate',
			display : '试用开始时间',
			sortable : true
		}, {
			name : 'closeDate',
			display : '试用结束时间',
			sortable : true
		}, {
			name : 'budgetMoney',
			display : '预计金额',
			sortable : true,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'affirmMoney',
			display : '确认预算金额',
			sortable : true,
			process : function(v){
				return moneyFormat2(v);
			}
		}, {
			name : 'ExaStatus',
			display : '审批状态',
			sortable : true
		}, {
			name : 'status',
			display : '项目状态',
			sortable : true,
			process : function(v,row){
				if(row.id == "noId"){
					return '';
				}
				switch(v){
					case '0' : return '未提交';break;
					case '1' : return '审批中';break;
					case '2' : return '待执行';break;
					case '3' : return '执行中';break;
					case '4' : return '已完成';break;
					case '5' : return '已关闭';break;
					default : return v;
				}
			}
		}, {
			name : 'applyName',
			display : '申请人',
			sortable : true
		}, {
			name : 'projectProcess',
			display : '项目进度',
			sortable : true,
			process : function(v){
				return moneyFormat2(v) + ' %';
			}
		}, {
			name : 'applyNameId',
			display : '申请人ID',
			sortable : true,
			hide : true
		}, {
			name : 'customerName',
			display : '客户名称',
			sortable : true
		}, {
			name : 'customerType',
			display : '客户类型Type',
			sortable : true,
			hide : true
		}, {
			name : 'customerTypeName',
			display : '客户类型',
			sortable : true
		}, {
			name : 'customerWay',
			display : '客户联系方式',
			sortable : true,
			width : 100,
			hide : true
		}, {
			name : 'province',
			display : '省份',
			sortable : true,
			hide : true
		}, {
			name : 'city',
			display : '城市',
			sortable : true,
			hide : true
		}, {
			name : 'areaName',
			display : '归属区域',
			sortable : true
		}, {
			name : 'areaPrincipal',
			display : '区域负责人',
			sortable : true
		}, {
			name : 'areaPrincipalId',
			display : '区域负责人Id',
			sortable : true,
			hide : true
		}, {
			name : 'areaCode',
			display : '区域编号（ID）',
			sortable : true,
			hide : true
		}, {
			name : 'projectDescribe',
			display : '试用要求描述',
			sortable : true,
			width : 100,
			hide : true
		}, {
			name : 'updateTime',
			display : '修改时间',
			sortable : true,
			hide : true
		}, {
			name : 'updateName',
			display : '修改人名称',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '修改人Id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '创建时间',
			sortable : true,
			hide : true
		}, {
			name : 'createName',
			display : '创建人名称',
			sortable : true,
			hide : true
		}, {
			name : 'createId',
			display : '创建人ID',
			sortable : true,
			hide : true
		}],
		searchitems : [{
					display : '项目编号',
					name : 'projectCode'
				}, {
					display : '项目名称',
					name : 'projectName'
				}, {
					display : '客户名称',
					name : 'customerName'
				}, {
					display : '申请人',
					name : 'applyName'
				}],
				// 默认搜索字段名
				sortname : "id",
				// 默认搜索顺序
				sortorder : "ASC"
			}
		}
	});
})(jQuery);