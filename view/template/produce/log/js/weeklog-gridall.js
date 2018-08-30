var show_page = function(page) {
	$("#weeklogGrid").yxgrid("reload");
};
$(function() {
	$("#weeklogGrid")
			.yxgrid(
					{
						model : 'produce_log_weeklog',
						title : '����Ա���܈�',
						showcheckbox : false,
						// ����Ϣ
						colModel : [
								{
									display : '���',
									name : 'id',
									sortable : true,
									hide : true
								},
								{
									name : 'weekTitle',
									display : '����',
									sortable : true,
									process : function(v, row) {
										return "<a href='?model=produce_log_worklog&action=pageall&id="
												+ row.id
												+ "'>"
												+ v
												+ "( "
												+ row.weekBeginDate
												+ "~"
												+ row.weekEndDate + " )</>";
									},
									width : 350

								}, {
									name : 'createName',
									display : '��Ա����',
									sortable : true
								}, {
									name : 'weekBeginDate',
									display : '��ʼʱ��',
									sortable : true
								}, {
									name : 'weekEndDate',
									display : '����ʱ��',
									sortable : true
								}, {
									name : 'updateTime',
									display : '�������ʱ��',
									sortable : true,
									width : 250
								} ],
						menusEx : [ {
							text : '���ܱ�',
							icon : 'view',
							action : function(row) {
								showThickboxWin("?model=produce_log_weeklog&action=toOpenall&id="
										+ row.id
										+ "&skey="
										+ row['skey_']
										+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900");
							}
						} ],
						// ���ӱ������
						subGridOptions : {
							url : '?model=produce_log_NULL&action=pageItemJson',
							param : [ {
								paramId : 'mainId',
								colId : 'id'
							} ],
							colModel : [ {
								name : 'XXX',
								display : '�ӱ��ֶ�'
							} ]
						},
						isAddAction : false,
						isEditAction : false,
						isDelAction : false,
						isViewAction : false,
						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [ {
							display : "����",
							name : 'weekTitle'
						}, {
							display : "ִ������",
							name : 'createTime'
						}, {
							display : "��Ա����",
							name : 'createName'
						} ]
					});
});