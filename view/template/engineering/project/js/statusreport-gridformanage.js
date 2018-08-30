var show_page = function() {
	$("#statusreportGrid").yxgrid("reload");
};

$(function() {
	$("#statusreportGrid").yxgrid({
		model : 'engineering_project_statusreport',
		action : 'jsonForProject',
		title : '项目周报    <span style="color:red">温馨提示：请确认项目组成员都已提交日志，并完成日志审批后再提交周报。</span>',
		param : { "projectId" : $("#projectId").val() },
		isDelAction : false,
		isAddAction : false,
		usepager : false,
		sortname : 'weekNo',
		//列信息
		colModel : [{
				display : 'id',
				name : 'id',
				hide : true
			}, {
				name : 'projectId',
				display : '项目id',
				hide : true
			}, {
				name : '',
				display : '',
				align :'center',
				width : 40,
				process : function(v,row){
					if(row.ExaStatus == '完成'){
						return "<img src='images/icon/cicle_green.png'/>";
					}else if(row.ExaStatus == '部门审批'){
						return "<img src='images/icon/cicle_blue.png'/>";
					}else{
						return "<img src='images/icon/cicle_grey.png'/>";
					}
				}
			}, {
				name : 'weekNo',
				display : '周次',
				sortable : true,
				width : 80
			}, {
				name : 'handupDate',
				display : '汇报日期',
				process : function(v,row){
					return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=engineering_project_statusreport&action=toView&id=" + row.id + '&skey=' + row.skey_ +"\",1)'>" + v + "</a>";
				}
			}, {
				name : 'beginDate',
				display : '开始日期'
			}, {
				name : 'endDate',
				display : '结束日期'
			}, {
				name : 'projectProcess',
				display : '项目进度',
				process : function(v){
                    return v != "" ? v + " %" : "";
				}
			}, {
				name : 'weekProcess',
				display : '本周进度',
				process : function(v){
                    return v != "" ? v + " %" : "";
				}
			}, {
				name : 'createName',
				display : '提交人',
				hide : true
			}, {
				name : 'status',
				display : '报告状态',
				datacode : 'XMZTBG',
				hide : true,
				width : 80
			}, {
				name : 'ExaStatus',
				display : '审批状态',
				width : 80
			}, {
				name : 'confirmName',
				display : '审批人'
			}, {
				name : 'ExaDT',
				display : '审批日期',
				hide : true
			}, {
				name : 'confirmDate',
				display : '审批日期'
			}, {
				name : 'createTime',
				display : '创建时间',
				width : 140
			}
		],
		toEditConfig : {
			showMenuFn : function(row) {
				return row.ExaStatus == "待提交" || row.ExaStatus == '打回';
			},
			toEditFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				showModalWin("?model=engineering_project_statusreport&action=toEdit&id=" + row[p.keyField] ,1,row.weekNo);
			}
		},
		toViewConfig : {
			showMenuFn : function(row) {
				return row.id *1 == row.id;
			},
			toViewFn : function(p,g) {
				var rowObj = g.getSelectedRow();
				var row = rowObj.data('data');
				if(row.id *1 == row.id){
					showModalWin("?model=engineering_project_statusreport&action=toView&id=" + row[p.keyField] ,1,row.weekNo);
				}
			}
		},
		buttonsEx : [{
			text : ' 刷新',
			icon : 'edit',
			action : function(row,rows,idArr,g) {
				g.reload();
			}
		}],
		// 扩展右键菜单
		menusEx : [{
				text : ' 填写周报',
				icon : 'add',
				showMenuFn:function(row){
					return row.id *1 != row.id;
				},
				action : function(row, rows, rowIds, g) {
					showModalWin("?model=engineering_project_statusreport&action=toAdd&weekNo="
						+ row.weekNo
						+ "&projectId=" + $("#projectId").val(),1,row.weekNo);
				}
			},{
				text : ' 提交审批',
				icon : 'edit',
				showMenuFn:function(row){
					return row.ExaStatus == "待提交" || row.ExaStatus == '打回';
				},
				action : function(row, rows, rowIds, g) {
					$.ajax({
					    type: "POST",
					    url: "?model=engineering_project_esmproject&action=getRangeId",
					    data: {'projectId' : row.projectId },
					    async: false,
					    success: function(data){
					   		if(data != ''){
								showThickboxWin('controller/engineering/project/statusreport_ewf.php?actTo=ewfSelect&billId='
									+ row.id + "&billArea=" + data
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
							}else{
								showThickboxWin('controller/engineering/project/statusreport_ewf.php?actTo=ewfSelect&billId='
									+ row.id
									+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=750");
							}
						}
					});
				}
			},{
				text : '删除',
				icon : 'delete',
				showMenuFn:function(row){
					return row.ExaStatus == "待提交" || row.ExaStatus == '打回';
				},
				action : function(rowData, rows, rowIds, g) {
					g.options.toDelConfig.toDelFn(g.options,g);
				}
			}
		]
	});
});