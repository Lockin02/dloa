var show_page = function(page) {
	$("#qualityapplyGrid").yxsubgrid("reload");
};
$(function() {
	var relDocType = $("#relDocType").val();
	$("#qualityapplyGrid").yxsubgrid({
		model : 'produce_quality_qualityapply',
		title : '质检申请单',
		param : {
			relDocTypeArr:relDocType
		},
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		isOpButton : false,
		showcheckbox : false,
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
					if(row.status == "3" || row.status == "2" || row.relDocType == "ZJSQDLBF"){
						return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualityapply&action=toView&id=" + row.id + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
					}else{
						return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualityapply&action=toQualityView&id=" + row.id + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
					}
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
						case "4" : return "未处理";
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
//		menusEx : [{
//			text : '下达任务',
//			icon : 'add',
//			showMenuFn : function(row) {
//				if(row.status != '3'){
//					return true;
//				}
//				return false;
//			},
//			action : function(row) {
//				if(row.status == '2'){
//					alert('单据需质检物料已经完全下达,不能继续进行此操作');
//					return false;
//				}else{
//					showThickboxWin("?model=produce_quality_qualitytask&action=toIssued&applyId="+ row.id
//						+ "&docType="
//						+ row.docType
//						+ "&skey="
//						+ row['skey_']
//						+"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
//				}
//			}
//		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toQualityView',
			formWidth : 900,
			formHeight : 500,
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				if(row.status == "3" || row.status == "2" || row.relDocType == "ZJSQDLBF"){
					showThickboxWin("?model=produce_quality_qualityapply&action=toView&id=" + row.id + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}else{
					return showThickboxWin("?model=produce_quality_qualityapply&action=toQualityView&id=" + row.id + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900");
				}
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