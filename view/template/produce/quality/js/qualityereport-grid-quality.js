var show_page = function(page) {
	$("#qualityereportGrid").yxgrid("reload");
};
$(function() {
	$("#qualityereportGrid").yxgrid({
		model : 'produce_quality_qualityereport',
		title : '质检报告',
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
					return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=produce_quality_qualityereport&action=toView&id=" + row.id + '&skey=' + row.skey_ +",1,700,1000, " + row.docCode +"\")'>" + v + "</a>";
				}
			}, {
				name : 'mainCode',
				display : '源单编号',
				sortable : true,
				width : 110,
				process : function(v,row){
					return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=produce_quality_qualitytask&action=toView&id=" + row.mainId + '&skey=' + row.skey_ +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
				}
			}, {
				name : 'examineUserName',
				display : '报告人',
				sortable : true
			}, {
				name : 'docDate',
				display : '报告日期',
				width : 80,
				sortable : true
			}, {
				name : 'auditStatus',
				display : '审核结果',
				sortable : true,
				width : 80,
				process : function(v) {
					switch(v){
						case "BC" : return "保存"; break;
						case "WSH" : return "待审核"; break;
						case "YSH" : return "合格"; break;
						case "RBJS" : return "让步接收"; break;
						case "BHG" : return "不合格"; break;
						default : return "非法状态";
					}
				}
			}, {
				name : 'ExaStatus',
				display : '审批状态',
				width : 80,
				sortable : true
			}, {
				name : 'ExaDT',
				display : '审批日期',
				width : 80,
				sortable : true
			}, {
				name : 'remark',
				display : '问题描述',
				sortable : true,
				hide : true
			}],
		menusEx : [{
			text : '审核',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '待提交') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showOpenWin("?model=produce_quality_qualityereport&action=toConfirm&id=" + row.id + "&skey=" + row.skey_ ,1, 700 , 1000, row.docCode )
			}
		},{
			text : '重新审核',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus =="打回") {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				showOpenWin("?model=produce_quality_qualityereport&action=toConfirm&id=" + row.id + "&skey=" + row.skey_ ,1, 700 , 1000, row.docCode )
			}
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView',
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showOpenWin("?model=produce_quality_qualityereport&action=toView&id=" + rowData[p.keyField] ,1, 700 , 1000, rowData.docCode );
			}
		},
		// 审批状态数据过滤
		comboEx : [{
			text : '审核结果',
			key : 'auditStatus',
			data : [{
				text : '保存',
				value : 'BC'
			},{
				text : '待审核',
				value : 'WSH'
			},{
				text : '合格',
				value : 'YSH'
			}, {
				text : '让步接收',
				value : 'RBJS'
			}, {
				text : '不合格',
				value : 'BHG'
			}]
		}],
		searchitems : [{
			display : "单据编号",
			name : 'docCodeSearch'
		},{
			display : "源单编号",
			name : 'mainCodeSearch'
		},{
			display : "报告人",
			name : 'examineUserNameSearch'
		}]
	});
});