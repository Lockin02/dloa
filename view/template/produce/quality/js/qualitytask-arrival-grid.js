var show_page = function(page) {
	$("#qualitytaskGrid").yxgrid("reload");
};
$(function() {
 var comBxArr=[/*{
		text : '单据状态',
		key : 'acceptStatusArr',
		//value : 'WJS,YJS',
		data : [{
	  		text : '--未完成--',
	  		value : 'WJS,YJS'
	  	},{
	  		text : '未接收',
	  		value : 'WJS'
	  	},{
	  		text : '未完成',
	  		value : 'YJS'
	  	}, {
	  		text : '已完成',
	  		value : 'YWC'
	  	}
	  	]
	  }*/];
var objItemId= $("#sourceId").val();
/*if(acceptStatusArr!='')
	 comBxArr=[];*/
	$("#qualitytaskGrid").yxgrid({
		model : 'produce_quality_qualitytask',
		action : 'pageJsonDetail',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		title : '质检任务单',
		isOpButton : false,
		showcheckbox : false,
		param : {
            objItemId : objItemId,
            objType:'ZJSQYDSL'
		},
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'docCode',
			display : '单据编号',
			sortable : true,
			width : 120,
			process : function(v,row){
				return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualitytask&action=toView&id=" + row.id + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
			}
		}, {
			name : 'applyCode',
			display : '质检申请单编号',
			sortable : true,
			width : 120,
			process : function(v,row){
				var applyCodeArr = v.split(',');
				var applyIdArr = row.applyId.split(',');
				var rtStr = '';
				for(var i = 0; i < applyCodeArr.length ; i++){
					if(rtStr == ""){
						rtStr = "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualityapply&action=toView&id=" + applyIdArr[i] + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + applyCodeArr[i] + "</a>";
					}else{
						rtStr += ",<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualityapply&action=toView&id=" + applyIdArr[i] + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + applyCodeArr[i] + "</a>";
					}
				}
				return rtStr;
//				return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualityapply&action=toView&id=" + row.applyId + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
			}
		},{
			name : 'productCode',
			display : '物料编号',
			width : 90
		}, {
			name : 'productName',
			display : '物料名称',
			width : 150
		}, {
			name : 'pattern',
			display : '型号',
			width : 100
		}/*, {
			name : 'unitName',
			display : '单位',
			width : 50
		}*/, {
			name : 'checkTypeName',
			display : '质检方式',
			width : 80
		},{
			name : 'checkStatus',
			display : '检验状态',
			process : function(v) {
				switch(v){
					case "YJY" : return "已检验"; break;
					case "" : return "未检验"; break;
					case "BFJY" : return "部分检验"; break;
				}
			}
		}, {
			name : 'createName',
			display : '下 达 人',
			sortable : true
		}, {
			name : 'createId',
			display : '创建人id',
			sortable : true,
			hide : true
		}, {
			name : 'createTime',
			display : '任务下达日期',
			sortable : true,
			width : 140
		}, {
			name : 'chargeUserName',
			display : '检 验 人',
			sortable : true
		}, {
			name : 'acceptStatus',
			display : '任务状态',
			sortable : true,
			process : function(v) {
				switch(v){
					//case "WJS" : return "未接收"; break;
					case "YJS" : return "未完成"; break;
					case "YWC" : return "已完成"; break;
					default : return "非法状态";
				}
			},
			width : 80
		}, {
			name : 'acceptTime',
			display : '接收日期',
			sortable : true,
			width : 140
		}, {
			name : 'complatedTime',
			display : '完成日期',
			sortable : true,
			width : 140
		}, {
			name : 'updateName',
			display : '修改人',
			sortable : true,
			hide : true
		}, {
			name : 'updateId',
			display : '修改人id',
			sortable : true,
			hide : true
		}, {
			name : 'updateTime',
			display : '修改日期',
			sortable : true,
			hide : true
		}],
		
//		menusEx : [{
//			name : 'edit',
//			text : "编辑",
//			icon : 'edit',
//			showMenuFn : function(row) {
//				if(row.status != '3'){
//					return true;
//				}
//				return false;
//			},
//			action : function(row, rows, grid) {
//				showModalWin("?model=produce_quality_qualitytask&action=toEdit&id="
//						+ row.id + "&skey=" + row['skey_']);
//			}
//		}],
		toViewConfig : {
			action : 'toView',
			formWidth : 850
		},
		// 审批状态数据过滤
		comboEx : comBxArr,
		searchitems : [{
			display : "单据编号",
			name : 'docCodeSearch'
		},{
			display : "质检申请单号",
			name : 'applyCodeSearch'
		},{
			display : "物料编码",
			name : 'productCodeSearch'
		},{
			display : "物料名称",
			name : 'productNameSearch'
		}]
	});

});