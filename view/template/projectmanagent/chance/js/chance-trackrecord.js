var show_page = function(page) {
	$("#recordGrid").yxgrid("reload");
};
$(function() {
	$("#recordGrid").yxgrid({
		model : 'projectmanagent_track_track',
		title : '�����̻���¼',
		param : {
			"chanceId" : $('#chanceId').val()
		},
		/**
		 * �Ƿ���ʾ������
		 *
		 * @type Boolean
		 */
		isToolBar : false,
		/**
		 * �Ƿ���ʾ�޸İ�ť/�˵�
		 *
		 * @type Boolean
		 */
		isEditAction : false,
		/**
		 * �Ƿ���ʾɾ����ť/�˵�
		 *
		 * @type Boolean
		 */
		isDelAction : false,
		/**
		 * �Ƿ���ʾ��Ӱ�ť/�˵�
		 *
		 * @type Boolean
		 */
		isAddAction : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'trackName',
			display : '����������',
			sortable : true
		}, {
			name : 'trackDate',
			display : '��������',
			sortable : true
		}, {
			name : 'trackType',
			display : '��������',
			sortable : true,
			datacode : 'GZLX'
		}, {
			name : 'linkmanName',
			display : '��ϵ������',
			sortable : true
		}, {
			name : 'trackPurpose',
			display : '����Ŀ��',
			sortable : true,
			width : 150
		}, {
			name : 'customerFocus',
			display : '�ͻ���ע��',
			sortable : true,
			width : 150
		}, {
			name : 'result',
			display : '�Ӵ����',
			sortable : true,
			width : 150
		},{
			name : 'problem',
			display : '���������',
			sortable : true,
			width : 150
		},{
			name : 'followPlan',
			display : '�����ƻ�',
			sortable : true,
			width : 150
		},{
			name : 'chanceCode',
			display : '�̻����',
			sortable : true,
			width : 150
		},{
			name : 'chanceName',
			display : '�̻�����',
			sortable : true,
			width : 150
		}],
		buttonsEx : [
					   {
						// ����EXCEL�ļ���ť
						name : 'output',
						text : "����EXCEL",
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
			text : '�鿴',
			icon : 'view',
			action: function(row){
               parent.location="?model=projectmanagent_track_track&action=init&id="
						+ row.id
                        + '&perm=view'

			}
		   },{
			text : '�޸�',
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