var show_page = function() {
	$("#qualitytaskGrid").yxsubgrid("reload");
};
$(function() {
	if($("#relDocType").val() != ''){
		param = {
			chargeUserCode : $("#userId").val(),
			relDocType : $("#relDocType").val()
		};
	}else{
		param = {
			chargeUserCode : $("#userId").val()
		};
	}
	$("#qualitytaskGrid").yxsubgrid({
		model : 'produce_quality_qualitytask',
		title : '我的质检任务',
		isAddAction : false,
		isDelAction : false,
		isEditAction : false,
		param : param,
		isOpButton : false,
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
					case "YJS" : return "未完成"; break;
					case "YWC" : return "已完成"; break;
					default : return "非法状态";
				}
			},
			width : 70
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
		// 主从表格设置
		subGridOptions : {
			url : '?model=produce_quality_qualitytaskitem&action=pageItemJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'productCode',
				display : '物料编号'
			}, {
				name : 'productName',
				display : '物料名称',
				width : 150
			}, {
				name : 'pattern',
				display : '型号',
				width : 120
			}, {
				name : 'unitName',
				display : '单位',
				width : 70
			}, {
				name : 'checkTypeName',
				display : '质检方式',
				width : 80
			}, {
				name : 'assignNum',
				width : 80,
				display : '下达数量'
			}, {
				name : 'checkedNum',
				width : 80,
				display : '已质检数量'
			}, {
				name : 'standardNum',
				width : 70,
				display : '合格数量'
			}, {
                name : 'unStandardNum',
                display : '不合格数量',
                width : 70,
				process : function(v,row){
					var produceNum = 0;
					if(row.realCheckNum >= 0 && row.realCheckNum != ''){
						produceNum = row.realCheckNum-row.standardNum;
					}else{
						produceNum = row.checkedNum-row.standardNum;
					}
					return (row.checkStatus == "")? "-" : produceNum;
				}
            },{
                name : 'qualitedRate',
                display : '合格率',
                process : function(v,row){
                    if(v!=""){
                        return "<a href='javascript:void(0)' onclick='showOpenWin(\"?model=produce_quality_qualityereport&action=toItempage&type=task&sourceId=" + row.id  +"&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=900\")'>" + v + "</a>";
                    }else{
                        return v;
                    }
                }
            }, {
				name : 'checkStatus',
				width : 80,
				display : '检验状态',
				process : function(v) {
					if (v == "YJY") {
						return "已检验";
					} else if( v == ""){
						return "未检验";
					}else if(v == "BFJY"){
						return "部分检验";
					}
				}
			}]
		},
		menusEx : [{
			text : "接收",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.acceptStatus == 'WJS') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				if (confirm('确认接收任务？')) {
					$.ajax({
						type : 'POST',
						url : '?model=produce_quality_qualitytask&action=acceptTask&skey='
								+ row['skey_'],
						data : {
							id : row.id
						},
						success : function(data) {
							if(data == "1"){
								alert('任务已接收');
							}else{
								alert("任务接收失败");
							}
							show_page();
						}
					});
				}
			}
		},{
			text : "录入质检报告",
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.acceptStatus == 'YJS') {
					return true;
				} else {
					return false;
				}
			},
			action : function(row) {
				//已有报告处理 -- 未实现
				return showOpenWin("?model=produce_quality_qualityereport&action=toAdd&mainId=" + row.id
                    +'&mainCode=' +row.docCode
                    +'&relDocType=' +row.relDocType
                    +'&applyId=' +row.applyId
                    +'&skey=' + row.skey_ ,1,700,1000, row.docCode);
			}
		}],
		toEditConfig : {
			action : 'toEdit'
		},
		toViewConfig : {
			action : 'toView',
			formHeight : 500,
			formWidth : 900
		},
		// 审批状态数据过滤
		comboEx : [{
		text : '单据状态',
		key : 'acceptStatusArr',
		value : 'YJS',
		data : [{
				text : '未完成',
				value : 'YJS'
			}, {
				text : '已完成',
				value : 'YWC'
			}]
		}],
		searchitems : [{
			display : "单据编号",
			name : 'docCodeSearch'
		},{
			display : "质检申请单号",
			name : 'applyCodeSearch'
		}]
	});
});