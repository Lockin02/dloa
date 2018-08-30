var show_page = function(page) {
	$("#qualityapplyGrid").yxsubgrid("reload");
};
$(function() {
	$("#qualityapplyGrid").yxsubgrid({
		model : 'produce_quality_qualityapply',
		title : '我的质检申请单',
		isAddAction : false,
		isEditAction : false,
		param : {
			applyUserCode : $("#userId").val()
		},
		isOpButton : false,
		showcheckbox : false,
		isDelAction : false,
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
				width : 110,
				process : function(v,row){
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualityapply&action=toView&id=" + row.id + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
				}
			}, {
				name : 'relDocType',
				display : '源单类型',
				sortable : true,
				width : 80,
				datacode : 'ZJSQDYD'
			}, {
				name : 'relDocCode',
				display : '源单编号',
				sortable : true,
				width : 110
			}, {
				name : 'supplierName',
				display : '供应商',
				sortable : true,
				width : 130
			}, {
				name : 'status',
				display : '单据状态',
				sortable : true,
				width : 70,
				process : function(v){
					switch(v){
						case '0' : return "待执行";
						case '1' : return "部分执行";
						case '2' : return "执行中";
						case '3' : return "已关闭";
						case "4" : return "未处理";
						default : return '<span class="red">非法状态</span>';
					}
				}
			}, {
				name : 'applyUserName',
				display : '申请人',
				sortable : true
			}, {
				name : 'applyUserCode',
				display : '申请人Code',
				hide : true,
				sortable : true
			}, {
				name : 'createTime',
				display : '申请时间',
				width : 130,
				sortable : true
			}, {
				name : 'closeUserName',
				display : '关闭人',
				sortable : true
			}, {
				name : 'closeUserId',
				display : '关闭人id',
				hide : true,
				sortable : true
			}, {
				name : 'closeTime',
				display : '关闭时间',
				width : 130,
				sortable : true
			}, {
				name : 'workDetail',
				display : '描述',
				width : 200,
				hide : true,
				sortable : true
		}],
		// 主从表格设置
		subGridOptions : {
			url : '?model=produce_quality_qualityapplyitem&action=pageItemJson',
			param : [{
						paramId : 'mainId',
						colId : 'id'
					}],
			colModel : [{
				name : 'productCode',
				display : '物料编号',
				width : 80
			}, {
				name : 'productName',
				display : '物料名称',
				width : 120
			}, {
				name : 'pattern',
				display : '型号'
			}, {
				name : 'unitName',
				display : '单位',
				width : 50
			}, {
				name : 'checkTypeName',
				display : '质检方式',
				width : 60
			}, {
				name : 'qualityNum',
				display : '报检数量',
				width : 60
			}, {
				name : 'assignNum',
				display : '已下达数量',
				width : 60
			}, {
				name : 'complatedNum',
				display : '质检完成数',
				width : 65
			},{
				name : 'standardNum',
				display : '合格数量',
				width : 60
			},{
				name : 'status',
				display : '处理结果',
				width : 60,
				process : function(v){
					switch(v){
						case "0" : return "质检放行";
						case "1" : return "部分处理";
						case "2" : return "处理中";
						case "3" : return "质检完成";
						default : return "";
					}
				}
			},{
				name : 'dealUserName',
				display : '处理人',
				width : 80
			},{
				name : 'dealTime',
				display : '处理时间',
				width : 130
			}]
		},
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView',
			formWidth : 900,
			formHeight : 500,
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showThickboxWin("?model=produce_quality_qualityapply&action=toView&id=" + row.id + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
			}
		},
		// 审批状态数据过滤
		comboEx : [{
			text : '单据状态',
			key : 'statusArr',
			value : '0,1,2',
			data : [{
				text : '--未完成--',
				value : '0,1,2'
			},{
				text : '待执行',
				value : '0'
			},{
				text : '部分执行',
				value : '1'
			}, {
				text : '执行中',
				value : '2'
			}, {
				text : '已关闭',
				value : '3'
			}]
		}],
		searchitems : [{
			display : "单据编号",
			name : 'docCodeSearch'
		},{
			display : "源单编号",
			name : 'relDocCodeSearch'
		},{
			display : "申请人",
			name : 'createNameSearch'
		},{
			display : "供应商",
			name : 'supplierNameSearch'
		}]
	});
});