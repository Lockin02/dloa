/** ��Ʒ������Ϣ�б�* */

var show_page = function(page) {
	$("#datadictList").yxgrid("reload");
    $("#tree").yxgrid("reload");
};

$(function() {
	$("#tree").yxtree({

	url : '?model=system_datadict_datadict&action=getChildren',
	event : {
		"node_click" : function(event, treeId, treeNode) {
			var datadictList = $("#datadictList").data('yxgrid');
			datadictList.options.param['parentId']=treeNode.id;

			datadictList.reload();
			$("#parentId").val(treeNode.id);


		}
	}
	});

	$("#datadictList").yxgrid({

		model : 'system_datadict_datadict',
		action:'DatadictPageJson',
        /**
			 * �Ƿ���ʾ�޸İ�ť/�˵�
			 *
			 * @type Boolean
			 */
			isEditAction : false,
		title : '�����ֵ�',
		isToolBar : true,
		isViewAction : false,
		showcheckbox : true,
        sortname : 'id',
		sortorder : 'ASC',
		buttonsEx : [{
			name : 'Add',
			// hide : true,
			text : "��ʼ��",
			icon : 'edit',

			action : function(row) {
				location='?model=system_datadict_datadict'
			}
		}],
       //����
		 toAddConfig : {
				toAddFn : function(p ,treeNode , treeId) {
			//	alert(treeNode.data('data')['id']);
					var c = p.toAddConfig;
					var w = c.formWidth ? c.formWidth : p.formWidth;
					var h = c.formHeight ? c.formHeight : p.formHeight;
					showThickboxWin("?model="
							+ p.model
							+ "&action="
							+ c.action
							+ c.plusUrl
							+ "&parentId=" +$("#parentId").val(treeNode.id)
							+ "&placeValuesBefore&TB_iframe=true&modal=false&height="
							+ h + "&width=" + w);
				}
			},

        // ��չ�Ҽ��˵�

		menusEx : [{
			text : '�༭',
			icon : 'edit',
			action : function(row) {
				showThickboxWin('?model=system_datadict_datadict&action=init&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=700');
			}
		}, {
			text : '����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.isUse == "1") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm("ȷ������?")) {
					$.ajax({
						type : "POST",
						url : "?model=system_datadict_datadict&action=ajaxUseStatus",
						data : {
							id : row.id,
							useStatus : '0'
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('���óɹ���');
							} else {
								alert('����ʧ��!');
							}
						}
					});
				}
			}
		}, {
			text : 'ͣ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if (row.isUse == "0" || row.isUse == "") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (window.confirm("ȷ��ͣ��?")) {
					$.ajax({
						type : "POST",
						url : "?model=system_datadict_datadict&action=ajaxUseStatus",
						data : {
							id : row.id,
							useStatus : '1'
						},
						success : function(msg) {
							if (msg == 1) {
								show_page();
								alert('�����ɹ���');
							} else {
								alert('����ʧ��!');
							}
						}
					});
				}
			}
		}],
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			display: '�������',
			name: 'module',
			width: 100,
			sortable: true,
			datacode: 'HTBK'
		}, {
			display : '����',
			name : 'dataName',
			sortable : true,
			width : 150
		}, {
			display : '����',
			name : 'dataCode',
			sortable : true,
			width : 150
		}, {
			display : '�ϼ�',
			name : 'parentName',
			sortable : true,
			width : 150
		}, {
			display : '���',
			name : 'orderNum',
			sortable : true
		}, {
			display : '��ע',
			name : 'remark',
			sortable : true,
			width : 200
		}, {
			name : 'isUse',
			display : '�Ƿ�����',
			sortable : true,
			process:function(v){
			   if(v == '0' || v == ''){
			      return "����";
			   }else if(v == '1'){
			      return "�ر�";
			   }
			}
		}],

		searchitems : [{
			display : '����',
			name : 'dataName'
		},{
			display : '�ϼ�',
			name : 'parentName'
		}]

	});
});
