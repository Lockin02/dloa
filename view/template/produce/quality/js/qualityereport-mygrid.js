var show_page = function(page) {
	$("#qualityereportGrid").yxgrid("reload");
};
$(function() {
	$("#qualityereportGrid").yxgrid({
		model : 'produce_quality_qualityereport',
		title : '我的质检报告',
		isAddAction : false,
		isDelAction : false,
		param : {
			examineUserId : $("#userId").val()
		},
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
				name : 'checkNum',
				display : '实际检验数量',
				width : 80,
				sortable : true,
				hide : true
			}, {
				name : 'qualitedNum',
				display : '合格数',
				width : 80,
				sortable : true,
				hide : true
			}, {
				name : 'produceNum',
				display : '不合格数',
				width : 80,
				sortable : true,
				hide : true
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
						case "BH" : return "驳回"; break;
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
		menusEx : [
			{
				text: "删除",
				icon: 'delete',
				showMenuFn : function(row){
					if(row.auditStatus == 'BC'|| row.auditStatus == 'BH'){
						return true;
					}
					return false;
				},
				action: function(row) {
					if (window.confirm(("确定要删除?"))) {
						$.ajax({
							type : "POST",
							url : "?model=produce_quality_qualityereport&action=ajaxdeletes",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == 1) {
									alert('删除成功！');
									show_page(1);
								}else{
									alert("删除失败! ");
								}
							}
						});
					}
				}
			},{
				text: "报告撤销",//撤销合格
				icon: 'delete',
				showMenuFn : function(row){
					if((row.auditStatus == 'YSH' || row.auditStatus == 'WSH') && row.relDocType != "ZJSQDLBF"){
						return true;
					}
					return false;
				},
				action: function(row) {
					if (window.confirm(("确定要撤销此报告吗?"))) {
						$.ajax({
							type : "POST",
							url : "?model=produce_quality_qualityereport&action=backReport",
							data : {
								id : row.id
							},
							success : function(msg) {
								if (msg == "1") {
									alert('撤销成功！');
									show_page(1);
								}else if(row.relDocType == 'ZJSQDLBF'){
									alert("撤销失败，该呆料报废质检报告已进行审批处理。");
								}else if(msg == "-1"){
									alert("撤销失败，质检相关物料已入库");
								}else{
									alert("撤销失败! ");
								}
							}
						});
					}
				}
			},{
				text: "提交审批",
				icon: 'delete',
				showMenuFn : function(row){
					if(row.auditStatus == 'YSH' && row.ExaStatus == '待提交' && row.relDocType == "ZJSQDLBF"){
						return true;
					}
					return false;
				},
				action: function(row) {
					showThickboxWin("controller/produce/quality/ewf_bfzj_index.php?actTo=ewfSelect&billId="
						+ row.id
						+ "&relDocType=" + row.relDocType
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
				}
			}
		],
		toEditConfig : {
			action : 'toEdit',
			showMenuFn : function(row) {
				if (row.auditStatus == "BC"|| row.auditStatus == 'BH') {
					return true;
				}
				return false;
			},
			toEditFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var rowData = rowObj.data('data');
				showOpenWin("?model=produce_quality_qualityereport&action=toEdit&id=" + rowData[p.keyField] ,1, 700 , 1000, rowData.docCode );
			}
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
			}, {
				text : '驳回',
				value : 'BH'
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
		},{
			text : '审批状态',
			key : 'ExaStatus',
			data : [{
				text : '待提交',
				value : '待提交'
			},{
				text : '部门审批',
				value : '部门审批'
			},{
				text : '完成',
				value : '完成'
			}, {
				text : '打回',
				value : '打回'
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