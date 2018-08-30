var show_page = function(page) {
	$("#planGrid").yxgrid("reload");
};
$(function() {
	$("#planGrid")
			.yxgrid(
					{
						isEditAction : false,
						isDelAction : false,
						model : 'hr_recruitplan_plan',
						action:	'MyPageJson',
						title : '��Ƹ�ƻ�',
						// ����Ϣ
						colModel : [
								{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},
								{
									name : 'formCode',
									display : '���ݱ��',
									sortable : true,
									width : 120,
									process : function(v, row) {
										return "<a href='#' onclick='showOpenWin(\"?model=hr_recruitplan_plan&action=toView&id="
												+ row.id + "\")'>" + v + "</a>";
									}
								}, {
									name : 'stateC',
									display : '״̬',
									sortable : true,
									width : 60
								}, {
									name : 'ExaStatus',
									display : '����״̬',
									sortable : true,
									width : 70
								}, {
									name : 'deptName',
									display : '������',
									sortable : true
								}, {
									name : 'positionName',
									display : '����ְλ',
									sortable : true
								}, {
									name : 'needNum',
									display : '��������',
									sortable : true,
									width : 60
								}, {
									name : 'entryNum',
									display : '����ְ����',
									sortable : true,
									width : 70
								}, {
									name : 'beEntryNum',
									display : '����ְ����',
									sortable : true,
									width : 70
								}, {
									name : 'hopeDate',
									display : 'ϣ������ʱ��',
									sortable : true
								}, {
									name : 'addType',
									display : '��Ա����',
									sortable : true
								}, {
									name : 'recruitManName',
									display : '��Ƹ������',
									sortable : true
								}, {
									name : 'assistManName',
									display : '��ƸЭ����',
									sortable : true,
									width : 300
								}

						],
						lockCol:['formCode','stateC','positionName'],//����������
						menusEx : [

								/*{
									text : '�޸�',
									icon : 'edit',
									showMenuFn : function(row) {
										if (row.ExaStatus == "���") {
											return true;
										}
										return false;
									},
									action : function(row, rows, grid) {
										if (row) {
											location = "?model=hr_recruitplan_plan&action=toAuditEdit&id="
													+ row.id
													+ "&skey="
													+ row['skey_'];
										}
									}
								},*/
								{
									text : '�޸�',
									icon : 'edit',
									showMenuFn : function(row) {
										if (row.ExaStatus == "δ�ύ" || row.ExaStatus == "���") {
											return true;
										}
										return false;
									},
									action : function(row, rows, grid) {
										if (row) {
											showModalWin("?model=hr_recruitplan_plan&action=toEdit&id="
													+ row.id
													+ "&skey="
													+ row['skey_']);
										}
									}
								},
								{
									text : 'ɾ��',
									icon : 'delete',
									showMenuFn : function(row) {
										if (row.ExaStatus == "δ�ύ") {
											return true;
										}
										return false;
									},
									action : function(row, rows, grid) {
										if (row) {
											if (window.confirm("ȷ��Ҫɾ��?")) {
												$
														.ajax( {
															type : "POST",
															url : "?model=hr_recruitplan_plan&action=ajaxdeletes",
															data : {
																id : row.id
															},
															success : function(
																	msg) {
																if (msg == 1) {
																	alert('ɾ���ɹ�!');
																	show_page();
																} else {
																	alert('ɾ��ʧ��!');
																	show_page();
																}
															}
														});
											}
										}
									}
								},
								{
									name : 'sumbit',
									text : '�ύ����',
									icon : 'edit',
									showMenuFn : function(row) {
										if (row.ExaStatus == 'δ�ύ'
												|| row.ExaStatus == '���') {
											return true;
										}
										return false;
									},
									action : function(row, rows, grid) {
										if (row) {
										//	console.info( row);
											showThickboxWin('controller/hr/recruitplan/ewf_index.php?actTo=ewfSelect&billDept='
													+ row.deptId+"&billId="+row.id+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=450&width=850");
										} else {
											alert("��ѡ��һ������");
										}
									}
								},
								{
									name : 'aduit',
									text : '�������',
									icon : 'view',
									showMenuFn : function(row) {
										if (row.ExaStatus == '���'
												|| row.ExaStatus == '���'
												|| row.ExaStatus == '��������') {
											return true;
										}
										return false;
									},
									action : function(row, rows, grid) {
										if (row) {
											showThickboxWin("controller/common/readview.php?itemtype=oa_hr_recruitplan_plan&pid="
													+ row.id
													+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800");
										}
									}
								}

						],

						toViewConfig : {
							toViewFn : function(p, g) {
								if (g) {
									var get = g.getSelectedRow().data('data');
									showModalWin("?model=hr_recruitplan_plan&action=toView&id="
											+ get[p.keyField]);
								}
							}
						},
						/*
						 * // ���ӱ������ subGridOptions : { url :
						 * '?model=hr_recruitplan_NULL&action=pageItemJson',
						 * param : [{ paramId : 'mainId', colId : 'id' }],
						 * colModel : [{ name : 'XXX', display : '�ӱ��ֶ�' }] },
						 */
						toAddConfig : {
							formHeight : 500,
							formWidth : 900,
							toAddFn : function(p, g) {
								showModalWin("?model=hr_recruitplan_plan&action=toAdd");
							}
						},
						toEditConfig : {
							action : 'toEdit'
						},
						toViewConfig : {
							action : 'toView'
						},
						searchitems : [ {
							display : "������",
							name : 'deptName'
						}, {
							display : "����ְλ",
							name : 'positionName'
						}, {
							display : '��Ա����',
							name : 'addType'
						} ],
						comboEx : [ {
							text : '����״̬',
							key : 'ExaStatus',
							data : [ {
								text : 'δ�ύ',
								value : 'δ�ύ'
							}, {
								text : '��������',
								value : '��������'
							}, {
								text : '���',
								value : '���'
							} ]
						} ]

					});
});