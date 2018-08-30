/** 物料信息列表* */

var show_page = function() {
	$("#proTypeTree").yxtree("reload");
	$("#documentGrid").yxgrid("reload");
};

$(function() {
	$("#documentTypeTree").yxtree({
		//传入本栏目默认顶级分类id，用于栏目的分离，1为规范文档，2为日常报表……
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

	if($("#topId").val() == '1'){//规范文档
		$("#documentGrid").yxgrid({
			model: 'produce_document_document',
			title: '生产文档管理',
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
				display: '文档分类id',
				name: 'typeId',
				sortable: true,
				hide: true
			}, {
				display: '文档分类',
				name: 'typeName',
				width: 80,
				sortable: true
			}, {
				display: '附件名',
				name: 'originalName',
				width: 300,
				sortable: true,
				process: function (v, row) {
					return "<a href='?model=file_uploadfile_management&action=toDownFileById&fileId=" + row.id + "' taget='_blank' title='点击下载< " + v + 
						" >，此附件由 " + row.createName + " 于 " + row.createTime + "上传'>" + v + "</a>";
				}
			}],
			buttonsEx: [{
				text: '上传规范文档',
				icon: 'edit',
				action: function () {
					showThickboxWin('?model=produce_document_document&action=toUploadFile&title=上传规范文档&typeId=' + $('#typeId').val() 
						+ '&typeName=' + $('#typeName').val() + '&topId=' + $('#topId').val()
						+ '&serviceType=produce_document_document&styleOne=specification&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=550');
				}
			}],
			menusEx: [{
				name: 'view',
				text: "查看",
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
				display: '附件名',
				name: 'originalName'
			}],
			sortname: "id",
			// 默认搜索顺序
			sortorder: "asc"
		});
	}else{//日常报表
		$("#documentGrid").yxgrid({
			model: 'produce_document_document',
			title: '生产文档管理',
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
				display: '文档分类id',
				name: 'typeId',
				sortable: true,
				hide: true
			}, {
				display: '文档分类',
				name: 'typeName',
				width: 80,
				sortable: true
			}, {
				display: '附件名',
				name: 'originalName',
				width: 300,
				sortable: true,
				process: function (v, row) {
					return "<a href='?model=file_uploadfile_management&action=toDownFileById&fileId=" + row.id + "' taget='_blank' title='点击下载< " + v + 
						" >，此附件由 " + row.createName + " 于 " + row.createTime + "上传'>" + v + "</a>";
				}
			}, {
				display: '源单业务',
				name: 'styleOne',
				width: 80,
				sortable: true,
	            process: function (v,row) {
	            	if(row.serviceType == 'oa_produce_produceplan'){
	            		if(v == 'firstInspection'){
	            			return '首件产品确认';
	            		}else if(v == 'organize'){
	            			return '组织生产';
	            		}
	            	}else if(row.serviceType == 'oa_produce_quality_ereport'){
	            		return '质检报告';
	            	}else if(row.serviceType == 'produce_document_document'){
	            		return '日常文档';
	            	}
	            }
			}, {
				display: '源单id',
				name: 'serviceId',
				sortable: true,
				hide: true
			}, {
				display: '源单编号',
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
				display: '合同号',
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
				display: '客户名称',
				name: 'customerName',
				width: 80,
				sortable: true
			}, {
				display: '销售负责人',
				name: 'saleUserName',
				width: 80,
				sortable: true
			}, {
				display: '生产入库完成日期',
				name: 'auditDate',
				width: 120,
				sortable: true
			}, {
				display: '指引文件',
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
							html += '<div class="upload"><a title="点击下载" href="index1.php?model=file_uploadfile_management&action=toDownFileById&fileId=' + idArr[i] 
							+ '">' + nameArr[i] + '</a></div>';
						}
					}
					return html;
				}
			}],
			buttonsEx: [
				//{
				//	text: '上传日常文档',
				//	icon: 'edit',
				//	action: function () {
				//		showThickboxWin('?model=produce_document_document&action=toUploadFile&title=上传日常文档&typeId=' + $('#typeId').val()
				//			+ '&typeName=' + $('#typeName').val() + '&topId=' + $('#topId').val()
				//			+ '&serviceType=produce_document_document&styleOne=specification&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=600&width=900');
				//	}
				//}
			],
			menusEx: [{
				name: 'view',
				text: "查看",
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
				display: '附件名',
				name: 'originalName'
			}, {
				display: '源单编号',
				name: 'serviceNo'
			}, {
				display: '合同号',
				name: 'contractCode'
			}, {
				display: '客户名称',
				name: 'customerName'
			}, {
				display: '销售负责人',
				name: 'saleUserName'
			}, {
				display: '生产入库完成日期',
				name: 'auditDate'
			}],
			sortname: "id",
			// 默认搜索顺序
			sortorder: "asc"
		});
	}
});