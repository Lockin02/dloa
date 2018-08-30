$(function() {
	$(".assworklogweekGrid").esmprojectgrid({
		action : 'pageJson',
		title : '考核信息',
		comboEx: [{
			text: "提交类型",
			key: 'subStatus',
			datacode: 'ZBZT'
		}],
		// 扩展按钮
		buttonsEx : [{

			name : 'excel',
			text : '导出EXCEL',
			icon : 'excel',
			action : function(row, rows, grid) {
				
				if(confirm('确定导出Excel')==true){
//						alert();
						showThickboxWin("?model=engineering_worklog_esmworklogweek&action=exportExcel&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600");

				}
			}
		}],
		// 扩展右键菜单
		menusEx : [{
			name : 'viewass',
			text : '查看详细',
			icon : 'edit',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=engineering_worklog_esmworklogweek&action=myassTaskview&id="
							+ row.id
							+ "&subStatus="
							+ row.subStatus
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=700&width=900");
				} else {
					alert("请选中一条数据");
				}
			}
		}]
	});

});