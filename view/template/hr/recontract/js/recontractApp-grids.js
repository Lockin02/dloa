var show_page = function(page) {
	$("#recontractGrid").yxgrid("reload");
};
$(function() {
	// ��ͷ��ť����
	buttonsArr = [];

	// ��ͷ��ť����
	excelOutArr1 = {
		name : 'exportIn',
		text : "����",
		icon : 'edit',
		action : function(row,rowdata,ids) {
			$.ajax( {
				type : 'POST',
				url : '?model=hr_recontract_recontract&action=option',
				data : {
					'limitName' : ids
				},
				async : false,
				success : function(data) {
					if (data == 1) {
						buttonsArr.push(excelOutArr);
					}
				}
			});
		}
	},excelOutArr2 = {
		name : 'exportIns',
		text : "����",
		icon : 'excel',
		action : function(row) {
			showThickboxWin("?model=hr_recontract_recontract&action=toExcelIn"
					+ "&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=600")
		}
	};
	buttonsArr.push(excelOutArr1);
	buttonsArr.push(excelOutArr2);
	
	$.ajax( {
		type : 'POST',
		url : '?model=hr_recontract_recontract&action=getLimits',
		data : {
			'limitName' : '����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(excelOutArr);
			}
		}
	});
	$("#recontractGrid")
			.yxsubgrid(
					{
						model : 'hr_recontract_recontractapproval',
						action:'pageJsonAppList',
						title : '��ͬ��Ϣ',
						//isAddAction : true,
						isEditAction : false,
						isViewAction : false,
						isDelAction : false,
						// ����Ϣ
						colModel : [
								{
									display : 'id',
									name : 'id',
									sortable : true,
									hide : true
								},
								{
									name : 'userName',
									display : 'Ա������',
									sortable : true
								},
								{
									name : 'userNo',
									display : 'Ա�����',
									sortable : true
								},
								{
									name : 'conNo',
									display : '��ͬ���',
									sortable : true,
									process : function(v, row) {
										return "<a href='#' onclick='showThickboxWin(\"?model=hr_recontract_recontract&action=toView&id="
												+ row.id
												+ '&skey='
												+ row.skey_
												+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800\")'>"
												+ v + "</a>";
									}
								}, {
									name : 'conName',
									display : '��ͬ����',
									sortable : true
								}, {
									name : 'conTypeName',
									display : '��ͬ����',
									sortable : true
								}, {
									name : 'conStateName',
									display : '��ͬ״̬',
									sortable : true
								}, {
									name : 'beginDate',
									display : '��ʼʱ��',
									sortable : true
								}, {
									name : 'closeDate',
									display : '����ʱ��',
									sortable : true
								}, {
									name : 'conNumName',
									display : '��ͬ����',
									sortable : true
								}, {
									name : 'conContent',
									display : '��ͬ����',
									sortable : true
								} ],
						buttonsEx : buttonsArr,
						
						// ���ӱ������
						subGridOptions : {
							url : '?model=hr_recontract_recontractApproval&action=pageJson',// ��ȡ�ӱ�����url
							// ���ݵ���̨�Ĳ�����������
							param : [ {
								paramId : 'recontractId',// ���ݸ���̨�Ĳ�������
								colId : 'id'// ��ȡ���������ݵ�������
							} ],

							// ��ʾ����
							colModel : [ {
								name : 'createName',
								display : '������'
							},{
								name : 'isFlagName',
								display : '�������',
								
							}, {
								name : 'conNumName',
								display : '�ù���ʽ'
							}, {
								name : 'beginDate',
								display : '��ͬ��ʼ����',
								
							}, {
								name : 'closeDate',
								display : '��ͬ��������',
								
							}, {
								name : 'conContent',
								display : '�������',
								

							} ]
						},
						// ��չ�Ҽ��˵�
						menusEx : [{
							text : '����ٲ�',
							icon : 'add',
							showMenuFn : function(row) {
								if (row.id == "noId") {
									return false;
								}
								return true;
							},
							action : function(row, rows, grid) {
								if (row) {
									if(row.ExaStatus == '���ύ' || row.ExaStatus == '��������'){
										showThickboxWin("?model=hr_recontract_recontract&action=viewArbitra&id=" + row.id + '&skey=' + row.skey_ +'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800');
									}else{
										showThickboxWin("?model=hr_recontract_recontract&action=viewArbitra&id=" + row.id + '&skey=' + row.skey_ +'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800');
									}
								} else {
									alert("��ѡ��һ������");
								}
							}
						},{
							text : '�鿴��ϸ',
							icon : 'view',
							showMenuFn : function(row) {
								if (row.id == "noId") {
									return false;
								}
								return true;
							},
							action : function(row, rows, grid) {
								if (row) {
									if(row.ExaStatus == '���ύ' || row.ExaStatus == '��������'){
										showThickboxWin("?model=contract_outsourcing_outsourcing&action=viewAlong&id=" + row.id + '&skey=' + row.skey_ );
									}else{
										showThickboxWin("?model=contract_outsourcing_outsourcing&action=viewTab&id=" + row.id + '&skey=' + row.skey_ );
									}
								} else {
									alert("��ѡ��һ������");
								}
							}
						},{
							text : '�޸�',
							icon : 'edit',
							showMenuFn : function(row) {
								if (row.id == "noId") {
									return false;
								}
								return true;
							},
							action : function(row, rows, grid) {
								if (row) {
									showThickboxWin("?model=contract_outsourcing_outsourcing&action=toUpdateInfo&id="
										+ row.id
										+ "&skey=" + row.skey_
										+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
								} else {
									alert("��ѡ��һ������");
								}
							}
						},{
							text : '�ύ����',
							icon : 'view',
							showMenuFn : function(row) {
								if (row.id == "noId") {
									return false;
								}
								return true;
							},
							action : function(row, rows, grid) {
								if (row) {
									showThickboxWin("?model=hr_recontract_recontract&action=toApprove&id=" + row.id + '&skey=' + row.skey_ +'&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=800');									
								} else {
									alert("��ѡ��һ������");
								}
							}
						}],
						/**
						 * ��������
						 */
						searchitems : [ {
							display : 'Ա������',
							name : 'userName'
						}, {
							display : 'Ա�����',
							name : 'userNo'
						}, {
							display : '��ͬ���',
							name : 'conNo'
						}, {
							display : '��ͬ����',
							name : 'conName'
						}, {
							display : '��ͬ����',
							name : 'conTypeName'
						}, {
							display : '��ͬ״̬',
							name : 'conStateNames'
						} ],
						// ����״̬���ݹ���
						comboEx : [ {
							text : '��ͬ����',
							key : 'statusTYPE',
							data : [ {
								text : 'ʵϰЭ��',
								value : '1'
							}, {
								text : '��ѵЭ�� ',
								value : '2'
							}, {
								text : '����Э�� ',
								value : '3'
							}, {
								text : '��ҵЭ�� ',
								value : '4'
							}, {
								text : '�Ͷ���ͬ ',
								value : '5'
							} ]
						}, {
							text : '������״̬',
							key : 'status',
							data : [ {
								text : 'δ�ύ',
								value : '0'
							}, {
								text : '������',
								value : '1'
							}, {
								text : 'ִ����',
								value : '2'
							}, {
								text : '����',
								value : '3'
							}, {
								text : '�ѹر�',
								value : '4'
							} ]
						}, {
							text : '����״̬',
							key : 'ExaStatus',
							value : '���',
							data : [ {
								text : '���ύ',
								value : '���ύ'
							}, {
								text : '������',
								value : '������'
							}, {
								text : '���',
								value : '���'
							}, {
								text : '���',
								value : '���'
							} ]
						} ]

					});
});