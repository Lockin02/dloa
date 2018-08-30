var show_page = function(page) {
	$("#assetcardGrid").yxgrid("reload");
};
function isRelated( assetId ){
	var equNum = 1;
	 $.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=isRelated',
		data : {
			id : assetId
		},
	    async: false,
		success : function(data) {
			equNum = data;
			return false;
		}
	})
	return equNum;
}
$(function() {
	//�ʲ������������
	var typeData = $.ajax({
		type : 'POST',
		url : "?model=asset_basic_directory&action=getSelection",
		async : false
	}).responseText;
	typeData = eval("(" + typeData + ")");
	var typeData2 = [];
	if (typeData) {
		for (var k = 0, kl = typeData.length; k < kl; k++) {
			var o = {
				value : typeData[k].value,
				text : typeData[k].text
			};
			typeData2.push(o);
		}
	}
	//����������������
	var agencyData = $.ajax({
		type : 'POST',
		url : "?model=asset_basic_agency&action=getSelection",
		async : false
	}).responseText;
	agencyData = eval("(" + agencyData + ")");
	var agencyData2 = [];
	if (agencyData) {
		for (var k = 0, kl = agencyData.length; k < kl; k++) {
			var o = {
				value : agencyData[k].value,
				text : agencyData[k].text
			};
			agencyData2.push(o);
		}
	}
	buttonsArr = [{
			text : "����",
			icon : 'delete',
			action : function(row) {
				var listGrid = $("#assetcardGrid").data('yxgrid');
				listGrid.options.extParam = {};
				$("#caseListWrap tr").attr('style',
				"background-color: rgb(255, 255, 255)");
				listGrid.reload();
			}
		},{
			name : 'import',
			text : '��������������',
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=asset_assetcard_assetcard&action=toUpdateBelongMan"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=550");
			}
	},{
		name : 'add',
		text : '��������',
		icon : 'add',
		action : function(row, rows, grid) {
			showModalWin("?model=asset_assetcard_assetcard&action=toUpdateCard",1,'update');
		}
	}]
	exportArr = {
	        name: 'excOut',
	        text: "����",
			icon : 'excel',
			items : [{
				name : 'export',
				text : "��Ƭ��Ϣ",
				icon : 'excel',
				action : function(row) {
					var colId = "";
					var colName = "";
					$("#assetcardGrid_hTable").children("thead").children("tr")
							.children("th").each(function() {
								if ($(this).css("display") != "none"
										&& $(this).attr("colId") != undefined) {
									if ($(this).attr("colId") != 'test') {
										colName += $(this).children("div").html()
												+ ",";
										colId += $(this).attr("colId") + ",";
									}
								}
							})
					window.open("?model=asset_assetcard_assetcard&action=exportExcel&colId="
									+ colId + "&colName=" + colName)
				}
			},{
				name : 'export',
				text : "��Ƭ��Ϣ(CSV)",
				icon : 'excel',
				action : function(row) {
					var colId = "";
					var colName = "";
					$("#assetcardGrid_hTable").children("thead").children("tr")
							.children("th").each(function() {
								if ($(this).css("display") != "none"
										&& $(this).attr("colId") != undefined) {
									if ($(this).attr("colId") != 'test') {
										colName += $(this).children("div").html()
												+ ",";
										colId += $(this).attr("colId") + ",";
									}
								}
							})
					window.open("?model=asset_assetcard_assetcard&action=exportCSV&colId="
									+ colId + "&colName=" + colName)
				}
			},{
				name : 'export',
				text : "�̵���Ϣ",
				icon : 'excel',
				action : function(row) {
					window.open(
						"?model=asset_assetcard_assetcard&action=exportCheckExcel");
				}
			},{
				name : 'export',
				text : "�̵���Ϣ(CSV)",
				icon : 'excel',
				action : function(row) {
					window.open(
						"?model=asset_assetcard_assetcard&action=exportCheckCSV");
				}
			}]
		}
	importArr = {
			name : 'import',
			text : "��Ƭ��Ϣ����",
			icon : 'excel',
			action : function(row, rows, grid) {
				showThickboxWin("?model=asset_assetcard_assetcard&action=toImport"
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=350&width=550");
			}
		}
	$.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=getLimits',
		data : {
			'limitName' : '��Ƭ����Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				buttonsArr.push(exportArr);
			}
		}
	});
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
	//�޸Ĳ�������Ȩ��
	var financialLimit = false;
	$.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=getLimits',
		data : {
			'limitName' : '�޸Ĳ�������Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				financialLimit = true;
			}
		}
	});
	//��Ƭɾ��Ȩ��
	var deleteLimit = false;
	$.ajax({
		type : 'POST',
		url : '?model=asset_assetcard_assetcard&action=getLimits',
		data : {
			'limitName' : '��Ƭɾ��Ȩ��'
		},
		async : false,
		success : function(data) {
			if (data == 1) {
				deleteLimit = true;
			}
		}
	});

	$("#assetcardGrid").yxgrid({
		model : 'asset_assetcard_assetcard',
		title : '�̶��ʲ���Ƭ',
		customCode : 'assetcardGrid',
		leftLayout : true,
		showcheckbox : false,
		isEditAction : false,
		isViewAction : false,
		isAddAction : false,
		isDelAction : false,
		isOpButton : false,
		event : {
			'afterload' : function(data, g) {
				$("#listSql").val(g.listSql);
			}
		},
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
			width : '150',
			sortable : true,
			process : function(v,row){
				if(row.remark != ""){
					return v+" <img src='images/icon/msg.png' style='width:14px;height:14px;' title='��ע : "+row.remark+"'/>";
				}
				return v;
			}
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
			name : 'assetSource',
			display : '�ʲ���Դ���',
			hide : true,
			sortable : true
		}, {
			name : 'assetSourceName',
			display : '�ʲ���Դ',
			sortable : true
		}, {
			name : 'unit',
			display : '��λ',
			hide : true,
			sortable : true
		}, {
			name : 'buyDate',
			display : '��������',
			sortable : true
		}, {
			name : 'wirteDate',
			display : '��������',
			sortable : true
		}, {
			name : 'deploy',
			display : '����',
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
			display : 'ʹ�ò���',
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
			display : '��������',
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
			hide : true,
			sortable : true
		}, {
			name : 'spec',
			display : '����ͺ�',
			sortable : true,
			hide : true
		}, {
			name : 'deprName',
			display : '�۾ɷ�ʽ',
			sortable : true,
			hide : true
		}, {
			name : 'subName',
			display : '�̶��ʲ���Ŀ',
			sortable : true,
			hide : true
		}, {
			name : 'depSubName',
			display : '�ۼ��۾ɿ�Ŀ',
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
			hide : true,
			sortable : true
		}, {
			name : 'belongTo',
			display : '�����ʲ�',
			hide : true,
			sortable : true
		}, {
			name : 'belongToCode',
			display : '�����ʲ�����',
			width : '160',
			sortable : true
		}, {
			name : 'isBelong',
			display : '�Ƿ���',
			width : '60',
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
		}, {
			name : 'remark',
			display : '��ע'
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
			text : '��������',
			key : 'agencyCode',
			data : agencyData2
		}, {
			text : '�ʲ����',
			key : 'assetTypeId',
			data : typeData2
		},{
			text : 'ʹ��״̬',
			key : 'useStatusCodeArr',
			value : 'SYZT-XZ,SYZT-SYZ,SYZT-DBF,SYZT-DTK,SYZT-YTK,SYZT-WCS,SYZT-YQL,SYZT-YCZ,SYZT-WXZ,SYZT-QT',
			data : [{
						text : '����',
						value : 'SYZT-XZ'
					}, {
						text : 'ʹ����',
						value : 'SYZT-SYZ'
					}, {
						text : '������',
						value : 'SYZT-DBF'
					}, {
						text : '�ѱ���',
						value : 'SYZT-YBF'
					}, {
						text : '���˿�',
						value : 'SYZT-DTK'
					}, {
						text : '���˿�',
						value : 'SYZT-YTK'
					}, {
						text : 'δ����',
						value : 'SYZT-WCS'
					}, {
						text : '������',
						value : 'SYZT-YQL'
					}, {
						text : '�ѳ���',
						value : 'SYZT-YCZ'
					}, {
						text : 'ά����',
						value : 'SYZT-WXZ'
					}, {
						text : '����',
						value : 'SYZT-QT'
					}, {
						text : '�Ǳ���',
						value : 'SYZT-XZ,SYZT-SYZ,SYZT-DBF,SYZT-DTK,SYZT-YTK,SYZT-WCS,SYZT-YQL,SYZT-YCZ,SYZT-WXZ,SYZT-QT'
					}]
		}, {
			text : '�ʲ���Դ',
			key : 'assetSource',
			data : [{
				text : '����',
				value : 'ZCLY-GM'
			}, {
				text : '����',
				value : 'ZCLY-ZS'
			}, {
				text : '����',
				value : 'ZCLY-ZL'
			}]
		}, {
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
		buttonsEx : buttonsArr,
		//		 ��չ��ť
		toAddConfig : {
			text : '����',
			/**
			 * Ĭ�ϵ��������ť�����¼�
			 */
			toAddFn : function(p) {
				showThickboxWin("?model=asset_assetcard_assetcard&action=toadd"
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=700&width=1000');
			}
		},
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row) {
				window.open('?model=asset_assetcard_assetcard&action=init&perm=view&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : '�༭',
			icon : 'edit',
//			Ӧ�೤��Ҫ��ȥ������2015.6.19
//			showMenuFn : function(row) {
//				if(row.version==1&&row.isBelong=='0'&&row.useStatusCode=='SYZT-XZ'){
//					return true;
//				}else{
//					return false;
//				}
//			},
			action : function(row) {
				showThickboxWin('?model=asset_assetcard_assetcard&action=toEditByAdmin&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=550&width=900');
			}
		}, {
			text : 'ɾ��',
			icon : 'delete',
			showMenuFn : function(row) {
				if(row.version == 1 && row.isBelong == '0' && row.useStatusCode == 'SYZT-XZ' && deleteLimit){
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				if (confirm('ȷ��Ҫɾ���ÿ�Ƭ��')) {
					$.ajax({
						type : 'POST',
						url : '?model=asset_assetcard_assetcard&action=ajaxdeletes&skey='
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
		}, {
			text : '�����豸',
			icon : 'add',
			showMenuFn : function(row) {
				//�����ϣ��ѱ��ϣ����˿⣬���˿⿨Ƭ��������ĸ����豸
				if(row.isBelong=='0' && (row.useStatusCode!='SYZT-DBF' && row.useStatusCode!='SYZT-YBF' && 
						row.useStatusCode!='SYZT-DTK' && row.useStatusCode!='SYZT-YTK')){
					return true;
				}else{
					return false;
				}
			},
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
			showMenuFn : function(row) {
				if(row.isBelong=='0'){
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				window.location='?model=asset_change_assetchange&action=page&assetId='
						+ row.id
						+ '&assetCode='
						+ row.assetCode
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900';
			}
		}, {
			text : '�䶯',
			icon : 'edit',
//			Ӧ�೤��Ҫ��ȥ������2015.6.19
//			showMenuFn : function(row) {
//				//�����ϣ��ѱ��ϣ����˿⣬���˿⿨Ƭ������䶯
//				if((isRelated(row.id)==1 || row.useStatusCode=='SYZT-XZ')&&row.isBelong=='0' && 
//						(row.useStatusCode!='SYZT-DBF' && row.useStatusCode!='SYZT-YBF' && 
//						 row.useStatusCode!='SYZT-DTK' && row.useStatusCode!='SYZT-YTK')){
//					return true;
//				}else{
//					return false;
//				}
//			},
			action : function(row) {
				showThickboxWin('?model=asset_assetcard_assetcard&action=tochange&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : '�ʲ�����',
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.useStatusCode=='SYZT-XZ'&&row.isBelong=='0'){
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				showThickboxWin('?model=asset_daily_allocation&action=toAddByCard&assetId='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : '�ʲ�����',
			icon : 'edit',
			showMenuFn : function(row) {
				if(row.useStatusCode=='SYZT-XZ'&&row.isBelong=='0'){
					return true;
				}else{
					return false;
				}
			},
			action : function(row) {
				showThickboxWin('?model=asset_disposal_scrap&action=toAddByCard&assetId='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=500&width=900');
			}
		}, {
			text : '�޸Ĳ�������',
			icon : 'edit',
			showMenuFn : function(row) {
				//�����ϣ��ѱ��ϣ����˿⣬���˿⿨Ƭ�������޸Ĳ�������
				if(row.useStatusCode=='SYZT-DBF' || row.useStatusCode=='SYZT-YBF' || 
						row.useStatusCode=='SYZT-DTK' || row.useStatusCode=='SYZT-YTK')
					return false;
				return financialLimit;
			},
			action : function(row) {
				showThickboxWin('?model=asset_assetcard_assetcard&action=toEditfinancial&id='
						+ row.id
						+ '&placeValuesBefore&TB_iframe=true&modal=false&height=400&width=400');
			}
		}],
		/**
		 * ��������
		 */
		searchitems : [{
			display : '��Ƭ���',
			name : 'assetCode'
		}, {
			display : '�ʲ�����',
			name : 'assetName'
		}, {
			display : '������',
			name : 'machineCodeSer'
		}, {
			display : '����ͺ�',
			name : 'spec'
		}, {
			display : 'ʹ����',
			name : 'userName'
		}, {
			display : 'ʹ�ò���',
			name : 'useOrgName'
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
		}],
		
		// �߼�����
		advSearchOptions : {
			modelName : 'assetcardInfo',
			// ѡ���ֶκ��������ֵ����
			selectFn : function($valInput) {
			},
			searchConfig : [{
				name : '��������',
				value : 'c.buyDate',
				changeFn : function($t, $valInput) {
					$valInput.click(function() {
						WdatePicker({
										dateFmt : 'yyyy-MM-dd'
									});
					});
				}
			},
			{
				name : '��ʼʹ������',
				value : 'c.BeginTime',
				changeFn : function($t, $valInput) {
					$valInput.click(function() {
						WdatePicker({
										dateFmt : 'yyyy-MM-dd'
									});
						});
				}
			},
			{	
				name : '��������',
				value : 'c.wirteDate',
				changeFn : function($t, $valInput) {
					$valInput.click(function() {
						WdatePicker({
										dateFmt : 'yyyy-MM-dd'
									});
						});
				}
			},
			{
				name : '�ʲ�����',
				value : 'c.property',
				type : 'select',
				options : [{
							'dataName' : '�̶��ʲ�',
							'dataCode' : '0'
						}, {
							'dataName' : '��ֵ����Ʒ',
							'dataCode' : '1'
						}]
			},
			{
				name : '�ʲ����',
				value : 'c.assetTypeName'
			},
			{
				name : '��Ƭ���',
				value : 'c.assetCode'
			},
			{
				name : '������',
				value : 'c.machineCodeSer'
			},
			{
				name : 'Ʒ��',
				value : 'c.brand'
			},
			{
				name : '����ͺ�',
				value : 'c.spec'
			},
			{
				name : '����',
				value : 'c.deploy'
			},
			{
				name : 'ʹ����',
				value : 'c.userName'
			},
			{
				name : 'ʹ��״̬',
				value : 'c.useStatusName'
			},
			{
				name : '�䶯��ʽ',
				value : 'c.changeTypeName'
			},
			{
				name : 'ʹ�ò�������',
				value : 'c.useOrgName'
			},
			{
				name : '��������',
				value : 'c.agencyName'
			},
			{
				name : '������������',
				value : 'c.orgName'
			},
			{
				name : '������',
				value : 'c.belongMan'
			},
			{
				name : '�����ʲ�����',
				value : 'c.belongToCode'
			},
			{
				name : '�ʲ���Դ',
				value : 'c.assetSource',
				type : 'select',
				options : [{
							'dataName' : '����',
							'dataCode' : 'ZCLY-GM'
						}, {
							'dataName' : '����',
							'dataCode' : 'ZCLY-ZS'
						}, {
							'dataName' : '����',
							'dataCode' : 'ZCLY-ZL'
						}]
			}]
		}
	});
});