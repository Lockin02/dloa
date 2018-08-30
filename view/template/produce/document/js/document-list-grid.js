/** ������Ϣ�б�* */

var show_page = function() {
	$("#proTypeTree").yxtree("reload");
	$("#documentGrid").yxgrid("reload");
};

$(function() {
	$("#documentTypeTree").yxtree({
		//���뱾��ĿĬ�϶�������id��������Ŀ�ķ��룬1Ϊ�淶�ĵ���2Ϊ�ճ�������
		url : '?model=produce_document_documenttype&action=getTreeDataByParentId&typeId=' + $("#topId").val(),
		event : {
			"node_click" : function(event, treeId, treeNode) {
				var documentGrid = $("#documentGrid").data('yxgrid');
				documentGrid.options.extParam['typeId'] = treeNode.id;
				$("#typeName").val(treeNode.name);
				$("#typeId").val(treeNode.id);
				documentGrid.reload();
			}
		}
	});

	if($("#topId").val() == '1'){//�淶�ĵ�
		$("#documentGrid").yxgrid({
			model: 'produce_document_document',
			title: '�����ĵ�����',
			isToolBar: true,
			isViewAction: false,
			showcheckbox: false,
			isAddAction: false,
			isEditAction: false,
			isDelAction: false,
			isOpButton: false,
			param : {
				typeId: $("#topId").val()
			},
			colModel: [{
				display: 'id',
				name: 'id',
				sortable: true,
				hide: true
			}, {
				display: '�ĵ�����id',
				name: 'typeId',
				sortable: true,
				hide: true
			}, {
				display: '�ĵ�����',
				name: 'typeName',
				width: 80,
				sortable: true
			}, {
				display: '������',
				name: 'originalName',
				width: 300,
				sortable: true,
				process: function (v, row) {
					return "<a href='?model=file_uploadfile_management&action=toDownFileById&fileId=" + row.id + "' taget='_blank' title='�������< " + v + 
						" >���˸����� " + row.createName + " �� " + row.createTime + "�ϴ�'>" + v + "</a>";
				}
			}],
			buttonsEx: [{
				text: '�ϴ��淶�ĵ�',
				icon: 'edit',
				action: function () {
					showThickboxWin('?model=produce_document_document&action=toUploadFile&title=�ϴ��淶�ĵ�&typeId=' + $('#typeId').val() 
						+ '&typeName=' + $('#typeName').val() + '&topId=' + $('#topId').val()
						+ '&serviceType=produce_document_document&styleOne=specification&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=550');
				}
			}],
			menusEx: [{
				name: 'view',
				text: "�鿴",
				icon: 'view',
				showMenuFn: function (row) {
					return row.serviceId != '';
				},
				action: function (row) {
					if(row.serviceType == 'oa_produce_produceplan'){
						showModalWin('?model=produce_plan_produceplan&action=toViewTab&id=' + row.serviceId);
					}else if(row.serviceType == 'oa_produce_quality_ereport'){
						showModalWin('?model=produce_quality_qualityereport&action=toView&id=' + row.serviceId);
					}
				}
			}],
			searchitems: [{
				display: '������',
				name: 'originalName'
			}],
			sortname: "id",
			// Ĭ������˳��
			sortorder: "asc"
		});
	}else{//�ճ�����
		$("#documentGrid").yxgrid({
			model: 'produce_document_document',
			title: '�����ĵ�����',
			isToolBar: true,
			isViewAction: false,
			showcheckbox: false,
			isAddAction: false,
			isEditAction: false,
			isDelAction: false,
			isOpButton: false,
			param : {
				typeId: $("#topId").val()
			},
			colModel: [{
				display: 'id',
				name: 'id',
				sortable: true,
				hide: true
			}, {
				display: '�ĵ�����id',
				name: 'typeId',
				sortable: true,
				hide: true
			}, {
				display: '�ĵ�����',
				name: 'typeName',
				width: 80,
				sortable: true
			}, {
				display: '������',
				name: 'originalName',
				width: 300,
				sortable: true,
				process: function (v, row) {
					return "<a href='?model=file_uploadfile_management&action=toDownFileById&fileId=" + row.id + "' taget='_blank' title='�������< " + v + 
						" >���˸����� " + row.createName + " �� " + row.createTime + "�ϴ�'>" + v + "</a>";
				}
			}, {
				display: 'Դ��ҵ��',
				name: 'styleOne',
				width: 80,
				sortable: true,
	            process: function (v,row) {
	            	if(row.serviceType == 'oa_produce_produceplan'){
	            		if(v == 'firstInspection'){
	            			return '�׼���Ʒȷ��';
	            		}else if(v == 'organize'){
	            			return '��֯����';
	            		}
	            	}else if(row.serviceType == 'oa_produce_quality_ereport'){
	            		return '�ʼ챨��';
	            	}else if(row.serviceType == 'produce_document_document'){
	            		return '�ճ��ĵ�';
	            	}
	            }
			}, {
				display: 'Դ��id',
				name: 'serviceId',
				sortable: true,
				hide: true
			}, {
				display: 'Դ�����',
				name: 'serviceNo',
				width: 110,
				sortable: true,
				process: function (v, row) {
					if (row.serviceId != '') {
						if(row.serviceType == 'oa_produce_produceplan'){
							return "<a href='#' onclick='showModalWin(\"?model=produce_plan_produceplan&action=toViewTab&id=" + row.serviceId +
								"\",1)'>" + v + "</a>";
						}else if(row.serviceType == 'oa_produce_quality_ereport'){
							return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=produce_quality_qualityereport&action=toView&id=" + row.serviceId + 
								"\",1)'>" + v + "</a>";
						}
					}
					return v;
				}
			},{
				name: 'contractCode',
				display: '��ͬ��',
				sortable: true,
	            process: function (v, row) {
	            	if(v){
	                    return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
	                    + row.contractId
	                    + '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
	                    + "<font color = '#4169E1'>"
	                    + v
	                    + "</font>"
	                    + '</a>';
	            	}
	            	return v;
	            }
			}, {
				display: '�ͻ�����',
				name: 'customerName',
				width: 80,
				sortable: true
			}, {
				display: '���۸�����',
				name: 'saleUserName',
				width: 80,
				sortable: true
			}, {
				display: '��������������',
				name: 'auditDate',
				width: 120,
				sortable: true
			}, {
				display: 'ָ���ļ�',
				name: 'styleThree',
				width: 300,
				sortable: true,
				process: function (v, row) {
					var idArr = row.styleTwo.split(',');
					var nameArr = v.split(',');
					var html = '';
					var len = idArr.length;
					if(len > 0){
						for(var i = 0; i < len; i++){
							html += '<div class="upload"><a title="�������" href="index1.php?model=file_uploadfile_management&action=toDownFileById&fileId=' + idArr[i] 
							+ '">' + nameArr[i] + '</a></div>';
						}
					}
					return html;
				}
			}],
			buttonsEx: [
				//{
				//	text: '�ϴ��ճ��ĵ�',
				//	icon: 'edit',
				//	action: function () {
				//		showThickboxWin('?model=produce_document_document&action=toUploadFile&title=�ϴ��ճ��ĵ�&typeId=' + $('#typeId').val()
				//			+ '&typeName=' + $('#typeName').val() + '&topId=' + $('#topId').val()
				//			+ '&serviceType=produce_document_document&styleOne=specification&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
				//	}
				//}
			],
			menusEx: [{
				name: 'view',
				text: "�鿴",
				icon: 'view',
				showMenuFn: function (row) {
					return row.serviceId != '';
				},
				action: function (row) {
					if(row.serviceType == 'oa_produce_produceplan'){
						showModalWin('?model=produce_plan_produceplan&action=toViewTab&id=' + row.serviceId);
					}else if(row.serviceType == 'oa_produce_quality_ereport'){
						showModalWin('?model=produce_quality_qualityereport&action=toView&id=' + row.serviceId);
					}
				}
			}],
			searchitems: [{
				display: '������',
				name: 'originalName'
			}, {
				display: 'Դ�����',
				name: 'serviceNo'
			}, {
				display: '��ͬ��',
				name: 'contractCode'
			}, {
				display: '�ͻ�����',
				name: 'customerName'
			}, {
				display: '���۸�����',
				name: 'saleUserName'
			}, {
				display: '��������������',
				name: 'auditDate'
			}],
			sortname: "id",
			// Ĭ������˳��
			sortorder: "asc"
		});
	}
});