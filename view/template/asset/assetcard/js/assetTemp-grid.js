var show_page = function(page) {
	$("#assetTempGrid").yxgrid("reload");
};
$(function() {
	//��ʼ����ť
	var buttonsArr = [];
	importArr = {
			name : 'import',
			text : '��Ƭ��¼����',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=asset_assetcard_assetTemp&action=toImport"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=550");
			}
		}
	//��ȡ��Ƭ����Ȩ��
	$.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=getLimits',
		data : {
			'limitName' : '��Ƭ����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(importArr);
			}
		}
	});
	$("#assetTempGrid").yxgrid({
		model : 'asset_assetcard_assetTemp',
		title : '��Ƭ����',
		showcheckbox : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		//����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
		}, {
			name : 'assetCode',
			display : '��Ƭ���',
			sortable : true,
			width : '150'
		}, {
			name : 'assetName',
			display : '�ʲ�����',
			sortable : true
		}, {
//			name : 'englishName',
//			display : 'Ӣ������',
//			sortable : true
//		}, {
			name : 'machineCode',
			display : '������',
			sortable : true
		}, {
//			name : 'assetTypeId',
//			display : '�ʲ����id',
//			sortable : true
//		}, {
			name : 'assetTypeName',
			display : '�ʲ����',
			sortable : true,
			process : function(v,row){
				if(row.assetTypeName == "�ֻ�" && (row.mobileBand != "" || row.mobileNetwork != "")){
					return v+" <img src='images/icon/msg.png' style='width:14px;height:14px;' title='�ֻ�Ƶ��: " +
					row.mobileBand+"���ֻ�����:"+row.mobileNetwork+"'/>";
				}
				return v;
			}
		}, {
			name : 'mobileBand',
			display : '�ֻ�Ƶ��',
			sortable : true,
			hide : true
		}, {
			name : 'mobileNetwork',
			display : '�ֻ�����',
			sortable : true,
			hide : true
		}, {
//			name : 'unit',
//			display : '������λ',
//			sortable : true
//		}, {
			name : 'buyDate',
			display : '��������',
			sortable : true
		}, {
//			name : 'place',
//			display : '��ŵص�',
//			sortable : true
//		}, {
			name : 'brand',
			display : 'Ʒ��',
			sortable : true
		}, {
//			name : 'useType',
//			display : '��;',
//			sortable : true
//		}, {
			name : 'spec',
			display : '����ͺ�',
			sortable : true
		}, {
			name : 'deploy',
			display : '����',
			sortable : true
		}, {
//			name : 'origin',
//			display : '����',
//			sortable : true
//		}, {
//			name : 'supplierName',
//			display : '��Ӧ������',
//			sortable : true
//		}, {
//			name : 'supplierId',
//			display : '��Ӧ��id',
//			sortable : true
//		}, {
//			name : 'manufacturers',
//			display : '������',
//			sortable : true
//		}, {
//			name : 'remark',
//			display : '��ע',
//			sortable : true
//		}, {
////			name : 'assetabbrev',
////			display : '�ʲ�������д',
////			sortable : true
////		}, {
//			name : 'belongManId',
//			display : '������Id',
//			sortable : true
//		}, {
			name : 'belongMan',
			display : '������',
			sortable : true
		}, {
//			name : 'userId',
//			display : 'ʹ����Id',
//			sortable : true
//		}, {
//			name : 'userName',
//			display : 'ʹ��������',
//			sortable : true
//		}, {
//			name : 'useOrgId',
//			display : 'ʹ�ò���id',
//			sortable : true
//		}, {
//			name : 'useOrgName',
//			display : 'ʹ�ò�������',
//			sortable : true
//		}, {
//			name : 'useProId',
//			display : 'ʹ����ĿId',
//			sortable : true
//		}, {
//			name : 'useProCode',
//			display : 'ʹ����Ŀ����',
//			sortable : true
//		}, {
//			name : 'useProName',
//			display : 'ʹ����Ŀ����',
//			sortable : true
//		}, {
//			name : 'orgId',
//			display : '��������id',
//			sortable : true
//		}, {
//			name : 'orgName',
//			display : '������������',
//			sortable : true
//		}, {
//			name : 'companyCode',
//			display : '������˾����',
//			sortable : true
//		}, {
//			name : 'companyName',
//			display : '������˾����',
//			sortable : true
//		}, {
//			name : 'belongErea',
//			display : '��������',
//			sortable : true
//		}, {
//			name : 'assetSourse',
//			display : '�ʲ���Դ',
//			sortable : true
//		}, {
			name : 'assetSourceName',
			display : '�ʲ���Դ����',
			sortable : true
		}, {
//			name : 'productId',
//			display : '��������Id',
//			sortable : true
//		}, {
//			name : 'productCode',
//			display : '�������ϱ���',
//			sortable : true
//		}, {
			name : 'productName',
			display : '������������',
			sortable : true
		}, {
//			name : 'agencyCode',
//			display : '�����������',
//			sortable : true
//		}, {
			name : 'agencyName',
			display : '��������',
			sortable : true
		}, {
			name : 'property',
			display : '�ʲ�����',
			sortable : true,
			process : function(v){
				if( v=='0' ){
					return '�̶��ʲ�';
				}else{
					return '��ֵ����Ʒ'
				}
			}
		}],
		buttonsEx : buttonsArr,
		toAddConfig : {
			text : '����',
			/**
			 * Ĭ�ϵ��������ť�����¼�
			 */
			toAddFn : function(p) {
				showThickboxWin("?model=asset_assetcard_assetTemp&action=toadd"
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=1000');
			}
		},
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				window.open('?model=asset_assetcard_assetTemp&action=init&perm=view&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.isCreate=='0'){
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				showThickboxWin('?model=asset_assetcard_assetTemp&action=init&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.isCreate=='0'){
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				if (confirm('ȷ��Ҫɾ���ÿ�Ƭ��')) {
					$.ajax({
						type : 'POST',
						url : '?model=asset_assetcard_assetTemp&action=ajaxdeletes&skey='
								+ row['skey_'],
						data : {
							id : row.id
						},
						// async: false,
						success : function(data) {
							if (data == 0) {
								alert('ɾ��ʧ��');
							} else {
								alert("ɾ���ɹ�");
								show_page();
							}
							return false;
						}
					});
				}
			}
		}],
//		comboEx : [{
//			text : '�Ƿ�ȷ��',
//			key : 'isCreate',
//			data : [{
//				text : '��',
//				value : '1'
//			}, {
//				text : '��',
//				value : '0'
//			}],
//			value : '0'
//		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '�ʲ�����',
			name : 'assetName'
		}, {
			display : '��������',
			name : 'agencyName'
		}, {
			display : '������',
			name : 'belongMan'
		}, {
			display : '��������',
			name : 'orgName'
		}, {
			display : '��Ƭ���',
			name : 'assetCodeSearch'
		}, {
			display : '������',
			name : 'machineCode'
		}, {
			display : '�ʲ���Դ',
			name : 'assetSourceName'
		}]
	});
});