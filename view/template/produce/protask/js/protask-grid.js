var show_page = function(page) {
	$("#protaskGrid").yxgrid("reload");
};
$(function() {
	$("#protaskGrid").yxgrid({
		model : 'produce_protask_protask',
		title : '����������',
		showcheckbox :false,
		isAddAction : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		customCode : 'protaskGrid',
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'taskCode',
			display : '����������',
			width : '120',
			hide : true,
			sortable : true
		}, {
			name : 'issuedDate',
			display : '�´�����',
			width : '75',
			sortable : true
		}, {
			name : 'referDate',
			display : '��������',
			width : '75',
			sortable : true
		}, {
			name : 'customerName',
			display : '�ͻ�����',
			width : '180',
			sortable : true
		}, {
			name : 'relDocId',
			display : 'Դ��id',
			sortable : true,
			hide : true
		}, {
			name : 'rObjCode',
			display : '����ҵ����',
			width : '120',
			sortable : true
		}, {
			name : 'relDocCode',
			display : '��ͬ/�����ñ��',
			width : '180',
			hide : true,
			sortable : true
		}, {
			name : 'relDocType',
			display : 'Դ������',
			width : '60',
			sortable : true,
			process : function(v) {
				if (v == 'oa_sale_order') {
					return "���ۺ�ͬ";
				}else if (v == 'oa_sale_lease') {
					return "���޺�ͬ";
				}else if (v == 'oa_sale_service'){
				    return "�����ͬ";
				}else if (v == 'oa_sale_rdproject'){
				    return "�з���ͬ";
				}else if (v == 'oa_borrow_borrow'){
				    return "���ú�ͬ";
				}else if (v == 'oa_present_present'){
				    return "��������";
				}
			}
		}, {
			name : 'relDocName',
			display : 'Դ������',
			width : '180',
			hide : true,
			sortable : true
		}, {
			name : 'proStatus',
			display : '����״̬',
			width : '60',
			sortable : true,
			process : function(v){
				( v=='YWC' ) ? (v='�����') : ( v='δ���' );
				return v;
			}
		}, {
			name : 'issuedStatus',
			display : '�´�״̬',
			width : '60',
			sortable : true,
			process : function(v){
				( v==0 ) ? (v='δ�´�') : ( v='���´�' );
				return v;
			}
		}, {
			name : 'issuedDeptName',
			display : '�µ�����',
			hide : true,
			sortable : true
		}, {
			name : 'execDeptName',
			display : 'ִ�в���',
			hide : true,
			sortable : true
		}, {
			name : 'qualityType',
			display : '��֤����',
			sortable : true,
			hide : true
		}, {
			name : 'taskType',
			display : '����',
			sortable : true,
			hide : true
		}, {
			name : 'issuedman',
			display : '�´���',
			hide : true,
			sortable : true
		}],
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				showModalWin('?model=produce_protask_protask&action=toView&id='
						+ row.id
						+ '&relDocType='
						+ row.relDocType
						+ '&skey='
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		} ,{
			text : '�޸�',
			icon : 'edit',
			showMenuFn : function(row) {
				if( row.issuedStatus != '1' ){
					return true;
				}
				return false;
			},
			action : function(row) {
				showModalWin('?model=produce_protask_protask&action=toEdit&id='
						+ row.id
						+ '&relDocType='
						+ row.relDocType
						+ '&skey='
						+ row['skey_']
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=700');
			}
		},{
            text: "���",
			icon : 'delete',
			showMenuFn : function(row) {
				if( row.proStatus != 'YWC'){
					return true;
				}
				return false;
			},
            action: function(row){
            	if(confirm('ȷ��������������ɣ�')){
					 $.ajax({
						type : 'POST',
						url : '?model=produce_protask_protask&action=finishTask&skey='+row['skey_'],
						data : {
							id : row.id
						},
	//				    async: false,
						success : function(data) {
//							if( data == 2 ){
//								alert( 'û��Ȩ��,��Ҫ��ͨȨ������ϵoa����Ա' );
//							}else{
								alert("���������");
								show_page();
//							}
							return false;
						}
					});
            	}
            }
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '����������',
			name : 'taskCode'
		}, {
			display : '����ҵ�񵥱��',
			name : 'rObjCode'
		}, {
			display : '�ͻ�����',
			name : 'customerName'
		}, {
			display : 'Դ����',
			name : 'relDocCode'
		}],
		sortname : 'issuedDate',
		sortorder : 'DESC'
	});
});