var show_page = function(page) {
	$("#recordGrid").yxgrid("reload");
};
$(function() {
	$("#recordGrid").yxgrid({
		model : 'projectmanagent_track_track',
		title : '跟踪商机记录',
		param : {
			"chanceId" : $('#chanceId').val()
		},
		/**
		 * 是否显示工具栏
		 *
		 * @type Boolean
		 */
		isToolBar : false,
		/**
		 * 是否显示修改按钮/菜单
		 *
		 * @type Boolean
		 */
		isEditAction : false,
		/**
		 * 是否显示删除按钮/菜单
		 *
		 * @type Boolean
		 */
		isDelAction : false,
		/**
		 * 是否显示添加按钮/菜单
		 *
		 * @type Boolean
		 */
		isAddAction : false,
		// 列信息
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'trackName',
			display : '跟踪人姓名',
			sortable : true
		}, {
			name : 'trackDate',
			display : '跟踪日期',
			sortable : true
		}, {
			name : 'trackType',
			display : '跟踪类型',
			sortable : true,
			datacode : 'GZLX'
		}, {
			name : 'linkmanName',
			display : '联系人姓名',
			sortable : true
		}, {
			name : 'trackPurpose',
			display : '跟踪目的',
			sortable : true,
			width : 150
		}, {
			name : 'customerFocus',
			display : '客户关注点',
			sortable : true,
			width : 150
		}, {
			name : 'result',
			display : '接触结果',
			sortable : true,
			width : 150
		},{
			name : 'problem',
			display : '待解决问题',
			sortable : true,
			width : 150
		},{
			name : 'followPlan',
			display : '后续计划',
			sortable : true,
			width : 150
		},{
			name : 'chanceCode',
			display : '商机编号',
			sortable : true,
			width : 150
		},{
			name : 'chanceName',
			display : '商机名称',
			sortable : true,
			width : 150
		}],
		buttonsEx : [
					   {
						// 导出EXCEL文件按钮
						name : 'output',
						text : "导出EXCEL",
						icon : 'excel',
						action : function(row) {
//							var searchConditionKey = "";
//							var searchConditionVal = "";
//							for (var t in $("#chanceGrid").data('yxsubgrid').options.searchParam) {
//								if (t != "") {
//									searchConditionKey += t;
//									searchConditionVal += $("#chanceGrid").data('yxsubgrid').options.searchParam[t];
//								}
//							}
//							var status = $("#status").val();
//							var chanceType = $("#chanceType").val();
//							var chanceLevel = $("#chanceLevel").val();
//							var winRate = $("#winRate").val();
//							var chanceStage = $("#chanceStage").val();
							var chanceId = $('#chanceId').val();
							var i = 1;
							var colId = "";
							var colName = "";
							$("#recordGrid_hTable").children("thead").children("tr")
									.children("th").each(function() {
										if ($(this).css("display") != "none"
												&& $(this).attr("colId") != undefined
												&& $(this).children("div").text() != "+") {

											colName += $(this).children("div").html() + ",";
											colId += $(this).attr("colId") + ",";
											i++;
										}
									})
							showThickboxWin("?model=projectmanagent_track_track&action=outputtExcel&colId="
							+ colId
							+ "&colName="
							+ colName
							+ "&chanceId="
							+ chanceId							
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height=270&width=500");
						
//						window
//					.open("?model=projectmanagent_track_track&action=outputtExcel&colId="
//							+ colId
//							+ "&colName="
//							+ colName
//							+ "&chanceId="
//							+ chanceId						
//							+ "&1width=200,height=200,top=200,left=200,resizable=yes")
						}
					}],	
		menusEx : [

          	{
			text : '查看',
			icon : 'view',
			action: function(row){
               parent.location="?model=projectmanagent_track_track&action=init&id="
						+ row.id
                        + '&perm=view'

			}
		   },{
			text : '修改',
			icon : 'edit',
			action: function(row){
				showThickboxWin('?model=projectmanagent_track_track&action=init&id='
						+ row.id
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=1000');

			}
		   }],
		toAddConfig : {
			action : 'toCluesTrack',
			plusUrl : '&id=' + $('#cluesId').val()
		}
	});
});