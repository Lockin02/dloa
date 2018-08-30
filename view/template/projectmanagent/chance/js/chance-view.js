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
			display : '客户联系人',
			name : 'linkmanName',
			tclass : 'txt'
		}, {
			display : '联系人ID',
			name : 'linkmanId',
			type : 'hidden'
		}, {
			display : '电话',
			name : 'telephone',
			tclass : 'txt'
		}, {
			display : 'QQ',
			name : 'QQ',
			tclass : 'txt'
		}, {
			display : '邮箱',
			name : 'Email',
			tclass : 'txt'
		}, {
			display : '角色',
			name : 'roleName',
			type : 'select',
			datacode : 'ROLE',
			tclass : 'txtmiddle',
			sortable : true
		}, {
			display : '是否关键联系人',
			name : 'isKeyMan',
			tclass : 'txtmin',
			process : function(v,row){
				if(v=='on'){
				   return "√";
				}else{
				   return "×";
				}
			}
		}]
	});

	//产品清单
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
            display: '产品线',
            width: 100
        },{
			display : '产品名称',
			name : 'conProductName',
			tclass : 'txt'
		}, {
			display : '产品Id',
			name : 'conProductId',
			tclass : 'txt',
			type : 'hidden'
		}, {
			display : '产品描述',
			name : 'conProductDes',
			tclass : 'txt'
		}, {
			display : '数量',
			name : 'number',
			tclass : 'txtshort'
		}, {
			display : '单价',
			name : 'price',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v);
			}
		}, {
			display : '金额',
			name : 'money',
			tclass : 'txtshort',
			process : function(v) {
				return moneyFormat2(v);
			}
				//		}, {
				//			display : '保修期',
				//			name : 'warrantyPeriod',
				//			tclass : 'txtshort'
				}, {
					display : '加密配置Id',
					name : 'license',
					type : 'hidden'
				}, {
					name : 'licenseButton',
					display : '加密配置',
					process : function(v, row) {
						if (row.license != "") {
							return "<a href='javascript:void(0)' onclick='showLicense(\""
									+ row.license + "\")'>查看</a>";
						}
					}
				}, {
					display : '产品配置Id',
					name : 'deploy',
					type : 'hidden'
				}, {
					name : 'deployButton',
					display : '产品配置',
					process : function(v, row) {
						if (row.deploy != "") {
							return "<a href='javascript:void(0)' onclick='showGoods(\""
									+ row.deploy
									+ "\",\""
									+ row.conProductName
									+ "\")'>查看</a>";
						}
					}
				}],
		event : {
			'reloadData' : function(e) {
				initCacheInfo();
			}
		}
	});
	//对手信息
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
			display : '竞争对手',
			name : 'competitor',
			tclass : 'txt'
		}, {
			display : '竞争优势',
			name : 'superiority',
			tclass : 'txt'
		}, {
			display : '竞争劣势',
			name : 'disadvantaged',
			tclass : 'txt'
		}, {
			display : '备注',
			name : 'remark',
			tclass : 'txt'
		}]
	});
});

/**
*  处理权限控制字段
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

