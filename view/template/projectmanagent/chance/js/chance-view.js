$(function() {
	$("#linkmanListInfo").yxeditgrid({
		objName : 'chance[linkman]',
		url : '?model=projectmanagent_chance_linkman&action=listJson',
		type : 'view',
		param : {
			'chanceId' : $("#chanceId").val(),
			'isTemp' : '0',
			'isDel' : '0'
		},
		tableClass : 'form_in_table',
		colModel : [{
			display : '�ͻ���ϵ��',
			name : 'linkmanName',
			tclass : 'txt'
		}, {
			display : '��ϵ��ID',
			name : 'linkmanId',
			type : 'hidden'
		}, {
			display : '�绰',
			name : 'telephone',
			tclass : 'txt'
		}, {
			display : 'QQ',
			name : 'QQ',
			tclass : 'txt'
		}, {
			display : '����',
			name : 'Email',
			tclass : 'txt'
		}, {
			display : '��ɫ',
			name : 'roleName',
			type : 'select',
			datacode : 'ROLE',
			tclass : 'txtmiddle',
			sortable : true
		}, {
			display : '�Ƿ�ؼ���ϵ��',
			name : 'isKeyMan',
			tclass : 'txtmin',
			process : function(v,row){
				if(v=='on'){
				   return "��";
				}else{
				   return "��";
				}
			}
		}]
	});

	//��Ʒ�嵥
	$("#productInfo").yxeditgrid({
		objName : 'chance[product]',
		url : '?model=projectmanagent_chance_product&action=listJson',
		type : 'view',
		tableClass : 'form_in_table',
		param : {
			'chanceId' : $("#chanceId").val(),
			'isTemp' : '0',
			'isDel' : '0'
		},
		colModel : [{
            name: 'newProLineName',
            display: '��Ʒ��',
            width: 100
        },{
			display : '��Ʒ����',
			name : 'conProductName',
			tclass : 'txt'
		}, {
			display : '��ƷId',
			name : 'conProductId',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : '��Ʒ����',
			name : 'conProductDes',
			tclass : 'txt'
		}, {
			display : '����',
			name : 'number',
			tclass : 'txtshort'
		}, {
			display : '����',
			name : 'price',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '���',
			name : 'money',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v);
			}
				//		}, {
				//			display : '������',
				//			name : 'warrantyPeriod',
				//			tclass : 'txtshort'
				}, {
					display : '��������Id',
					name : 'license',
					type : 'hidden'
				}, {
					name : 'licenseButton',
					display : '��������',
					process : function(v, row) {
						if (row.license != "") {
							return "<a href='javascript:void(0)' onclick='showLicense(\""
									+ row.license + "\")'>�鿴</a>";
						}
					}
				}, {
					display : '��Ʒ����Id',
					name : 'deploy',
					type : 'hidden'
				}, {
					name : 'deployButton',
					display : '��Ʒ����',
					process : function(v, row) {
						if (row.deploy != "") {
							return "<a href='javascript:void(0)' onclick='showGoods(\""
									+ row.deploy
									+ "\",\""
									+ row.conProductName
									+ "\")'>�鿴</a>";
						}
					}
				}],
		event : {
			'reloadData' : function(e) {
				initCacheInfo();
			}
		}
	});
	//������Ϣ
	$("#competitorList").yxeditgrid({
		objName : 'chance[competitor]',
		isAddOneRow : false,
		url : '?model=projectmanagent_chance_competitor&action=listJson',
		param : {
			'chanceId' : $("#chanceId").val()
		},
		type : 'view',
		tableClass : 'form_in_table',
		colModel : [{
			display : 'id',
			name : 'id',
			type : 'hidden'
		},{
			display : '��������',
			name : 'competitor',
			tclass : 'txt'
		}, {
			display : '��������',
			name : 'superiority',
			tclass : 'txt'
		}, {
			display : '��������',
			name : 'disadvantaged',
			tclass : 'txt'
		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});
});

/**
*  ����Ȩ�޿����ֶ�
**/
$(function (){
   if(strTrim($("#winRate").html()) != '******'){
      $("#winRate").html("<span style='color:blue;cursor:pointer;' onclick='winRateInfo();'>"+$("#winRate").html()+"</span>");
   }
   if(strTrim($("#chanceStage").html()) != '******'){
      $("#chanceStage").html("<span style='color:blue;cursor:pointer;' onclick='chanceStageInfo();'>"+$("#chanceStage").html()+"</span>");
   }
   if(strTrim($("#chanceMoney").html()) == 'NaN'){
      $("#chanceMoney").html("******");
      $("span[id^='goodsMoney']").html("******");
   }

})

