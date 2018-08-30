var show_page = function(page) {
	$("#requireinGrid").yxsubgrid("reload");
};
$(function() {
	$("#requireinGrid").yxsubgrid({
		model : 'asset_require_requirein',
		title : '��ȷ���ʲ�����',
		param : {'status':'��ȷ��'},
		isToolBar : false,
		showcheckbox : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'billNo',
			display : '���ݱ��',
			sortable : true,
            process : function(v,row){
            	return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=asset_require_requirein&action=toView&id=" + row.id
            		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850" + "\")'>" + v + "</a>";
            },
			width : 130
		}, {
			display : '����id',
			name : 'requireId',
			sortable : true,
			hide : true
		}, {
			name : 'requireCode',
			display : '������',
			sortable : true,
            process : function(v,row){
            	return "<a href='javascript:void(0)' onclick='showThickboxWin(\"?model=asset_require_requirement&action=toView&id=" + row.requireId
            		+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850" + "\")'>" + v + "</a>";
            },
			width : 130
		}, {
			name : 'applyName',
			display : '������',
			sortable : true,
			width : 100
		}, {
			name : 'applyDeptName',
			display : '���벿��',
			sortable : true,
			width : 100
		}, {
			name : 'applyDate',
			display : '��������',
			sortable : true,
			width : 80
		}, {
			name : 'status',
			display : '����״̬',
			sortable : true,
			width : 60
		}, {
			name : 'remark',
			display : '��ע',
			sortable : true,
			width : 250
		}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=asset_require_requireinitem&action=pageJson',
			param : [{
				paramId : 'mainId',
				colId : 'id'
			}],
			colModel : [{
				name : 'name',
				display : '�豸����',
				width : 120
			}, {
				name : 'description',
				display : '�豸����',
				width : 120
			}, {
				name : 'productName',
				display : '��������',
				width : 120
			}, {
				name : 'productCode',
				display : '���ϱ��',
				width : 120
			}, {
				name : 'productPrice',
				display : '���Ͻ��',
				width : 80,
				process : function(v){
					return moneyFormat2(v);
				}
			}, {
				name : 'brand',
				display : '����Ʒ��',
				width : 80
			}, {
				name : 'spec',
				display : '����ͺ�',
				width : 80
			}, {
				name : 'number',
				display : '����',
				width : 60
			}]
		},
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				if (row) {
					showOpenWin("?model=asset_require_requirein&action=toView&id="
							+ row.id
							+ "&skey=" + row['skey_']);

				}
			}
		}, {
			text : '���Ʋֿ����',
			icon : 'edit',
			action : function(row) {
				if (confirm('ȷ��Ҫ���Ƹ��ֿ�����ʲ����⣿')) {
					$.ajax({
						type : 'POST',
						url : '?model=asset_require_requirein&action=ajaxConfirm',
						data : {
							id : row.id
						},
						// async: false,
						success : function(data) {
							if( data==1 ){
								alert('���Ƴɹ�')
								show_page();
							}else{
								alert('����ʧ��')
							}
							return false;
						}
					});
				}
			}
		},{
			text : '���',
			icon : 'delete',
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=asset_require_requirein&action=toBack&id="
						+ row.id
						+ "&skey=" + row.skey_
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=750");
				} else {
					alert("��ѡ��һ������");
				}
			}
		}],
		searchitems : [{
			display : "���ݱ��",
			name : 'billNo'
		}, {
			display : "������",
			name : 'requireCode'
		}, {
			display : "������",
			name : 'applyName'
		}, {
			display : "���벿��",
			name : 'applyDeptName'
		}]
	});
});