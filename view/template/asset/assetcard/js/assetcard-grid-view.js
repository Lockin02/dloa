var show_page = function(page) {
	$("#assetcardGrid").yxgrid("reload");
};
$(function() {
	//�ʲ������������
	var typeDate = $.ajax({
		type : 'POST',
		url : "?model=asset_basic_directory&action=getSelection",
		async : false
	}).responseText;
	typeDate = eval("(" + typeDate + ")");
	var typeDate2 = [];
	if (typeDate) {
		for (var k = 0, kl = typeDate.length; k < kl; k++) {
			var o = {
				value : typeDate[k].value,
				text : typeDate[k].text
			};
			typeDate2.push(o);
		}
	}

//	//ʹ��״̬��������
//	var statusDate = $.ajax({
//		type : 'POST',
//		url : "?model=asset_basic_directory&action=getSelection",
//		async : false
//	}).responseText;
//	statusDate = eval("(" + statusDate + ")");
//	var statusDate2 = [];
//	if (statusDate) {
//		for (var k = 0, kl = statusDate.length; k < kl; k++) {
//			var o = {
//				value : statusDate[k].value,
//				text : statusDate[k].text
//			};
//			statusDate2.push(o);
//		}
//	}


	$("#assetcardGrid").yxgrid({
		model : 'asset_assetcard_assetcard',
		title : '�̶��ʲ���Ƭ',
		customCode : 'assetcardGridView',
		showcheckbox : false,
		isEditAction : false,
		isViewAction : false,
		isDelAction : false,
		isAddAction : false,
		// ����Ϣ
		colModel : [{
			display : 'id',
			name : 'id',
			sortable : true,
			hide : true
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
		}, {
			name : 'assetTypeId',
			display : '�ʲ����id',
			sortable : true,
			hide : true
		}, {
			name : 'assetTypeName',
			display : '�ʲ����',
			sortable : true
		}, {
			name : 'assetCode',
			display : '��Ƭ���',
			sortable : true
		}, {
			display : '�ʲ���������id',
			name : 'requireId',
			sortable : true,
			hide : true
		}, {
			display : '�ʲ�����������',
			name : 'requireCode',
			width : '150',
			sortable : true,
			 process : function(v,row){
				 if(v != ""){
					 return "<a href='javascript:void(0)' onclick='showModalWin(\"?model=asset_require_requirement&action=toViewTab&requireId=" + row.requireId + '&requireCode=' + v +"\")'>" + v + "</a>";
				 }
			 }
		}, {
			name : 'machineCode',
			display : '������',
			width : '150',
			sortable : true
		}, {
			name : 'brand',
			display : 'Ʒ��',
			sortable : true
		}, {
			name : 'assetName',
			display : '�ʲ�����',
			sortable : true,
			process : function(v,row){
				if(v == "�ֻ�" && (row.mobileBand != "" || row.mobileNetwork != "")){
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
			name : 'englishName',
			display : 'Ӣ������',
			hide : true,
			sortable : true
		}, {
			name : 'unit',
			display : '������λ',
			hide : true,
			sortable : true
		}, {
			name : 'buyDate',
			display : '��������',
			sortable : true
		}, {
			name : 'userId',
			display : 'ʹ����id',
			sortable : true,
			hide : true
		}, {
			name : 'userName',
			display : 'ʹ����',
			sortable : true
		}, {
			name : 'useStatusCode',
			display : 'ʹ��״̬����',
			sortable : true
		}, {
			name : 'useStatusName',
			display : 'ʹ��״̬',
			sortable : true
		}, {
			name : 'changeTypeCode',
			display : '�䶯��ʽ����',
			sortable : true,
			hide : true
		}, {
			name : 'changeTypeName',
			display : '�䶯��ʽ',
			sortable : true
		}, {
			name : 'useOrgId',
			display : 'ʹ�ò���id',
			sortable : true,
			hide : true
		}, {
			name : 'useOrgName',
			display : 'ʹ�ò�������',
			sortable : true
		}, {
			name : 'agencyName',
			display : '��������',
			sortable : true
		}, {
			name : 'orgId',
			display : '��������id',
			sortable : true,
			hide : true
		}, {
			name : 'orgName',
			display : '������������',
			sortable : true
		}, {
			name : 'belongManId',
			display : '������Id',
			sortable : true,
			hide : true
		}, {
			name : 'belongMan',
			display : '������',
			sortable : true
		}, {
			name : 'useProId',
			display : 'ʹ����ĿId',
			sortable : true,
			hide : true
		}, {
			name : 'useProName',
			display : 'ʹ����Ŀ',
			sortable : true
		}, {
			name : 'spec',
			display : '����ͺ�',
			sortable : true,
			hide : true
		}, {
			name : 'deprName',
			display : '�۾ɷ�ʽ����',
			sortable : true,
			hide : true
		}, {
			name : 'subName',
			display : '�̶��ʲ���Ŀ����',
			sortable : true,
			hide : true
		}, {
			name : 'depSubName',
			display : '�ۼ��۾ɿ�Ŀ����',
			sortable : true,
			hide : true
		}, {
			name : 'origina',
			display : '����ԭֵ',
			hide : true,
			sortable : true
		}, {
			name : 'buyDepr',
			display : '�����ۼ��۾�',
			hide : true,
			sortable : true
		}, {
			name : 'beginTime',
			display : '��ʼʹ������',
			sortable : true,
			hide : true
		}, {
			name : 'estimateDay',
			display : 'Ԥ��ʹ���ڼ���',
			sortable : true,
			hide : true
		}, {
			name : 'alreadyDay',
			display : '��ʹ���ڼ���',
			sortable : true,
			hide : true
		}, {
			name : 'depreciation',
			display : '�ۼ��۾�',
			hide : true,
			sortable : true
		}, {
			name : 'salvage',
			display : 'Ԥ�ƾ���ֵ',
			hide : true,
			sortable : true
		}, {
			name : 'netValue',
			display : '��ֵ',
			hide : true,
			sortable : true
		}, {
			name : 'version',
			display : '�汾��',
			sortable : true
		}, {
			name : 'belongTo',
			display : '�����ʲ�',
			hide : true,
			sortable : true
		}, {
			name : 'belongToCode',
			display : '�����ʲ�����',
			sortable : true
		}, {
			name : 'isBelong',
			display : '�Ƿ���',
			hide : true,
			sortable : true
		}, {
			name : 'isDel',
			display : '�Ƿ�����',
			hide : true,
			sortable : true,
				process : function(val) {
						if (val == "0") {
							return "δ����";
						}
						if (val == "1") {
							return "������";
						}
					}
		}, {
		    name : 'isScrap',
			display : '����״̬',
			hide : true,
			sortable : true,
				process : function(val) {
						if (val == "0") {
							return "δ����";
						}
						if (val == "1") {
							return "�ѱ���";
						}
					}
		}],
		comboEx : [{
			text : '�ʲ�����',
			key : 'property',
			data : [{
				text : '�̶��ʲ�',
				value : '0'
			}, {
				text : '��ֵ����Ʒ',
				value : '1'
			}]
		}, {
			text : '�ʲ����',
			key : 'assetTypeId',
			data : typeDate2
		},{
			text : 'ʹ��״̬',
			key : 'useStatusCode',
			datacode : 'SYZT'
		},{
			text : '�Ƿ���',
			key : 'isBelong',
			value : '0',
			data : [{
				text : '��',
				value : '0'
			}, {
				text : '��',
				value : '1'
			}]
		}],
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				window.open('?model=asset_assetcard_assetcard&action=init&perm=view&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : '�����豸',
			icon : 'add',
			action : function(row) {
				window.location='?model=asset_assetcard_equip&action=page&assetId='
						+ row.id
						+ '&assetCode='
						+ row.assetCode
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900';
			}
		}, {
			text : '�䶯��¼',
			icon : 'add',
			action : function(row) {
				window.location='?model=asset_change_assetchange&action=page&assetId='
						+ row.id
						+ '&assetCode='
						+ row.assetCode
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900';
			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��Ƭ���',
			name : 'assetCode'
		}, {
			display : '������',
			name : 'machineCodeSer'
		}, {
			display : '�ʲ�����',
			name : 'assetName'
		}, {
			display : 'ʹ����',
			name : 'userName'
		}, {
			display : 'ʹ�ò���',
			name : 'useOrgName'
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
			display : '�ʲ���Դ',
			name : 'assetSourceNameSer'
		}, {
			display : '�ʲ�������',
			name : 'requireCode'
		}]
	});
});