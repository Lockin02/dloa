var show_page = function (page) {
	$("#contractGrid").yxgrid("reload");
};

var date=new Date;
var year=date.getFullYear();
var yearArr =  [{
			text : year + '��',
			value : year
		}, {
			text : year-1 + '��',
			value : year-1
		}, {
			text : year-2 + '��',
			value : year-2
		}, {
			text : year-3 + '��',
			value : year-3
		}];

$(function () {
	$("#contractGrid").yxgrid({
		model: 'contract_contract_confirm',
		//action: 'pageJsonBasic',
		title: 'δȷ����Ϣ<span style="color:red">  '+ $("#num").val() + '  </span>��',
		isViewAction: false,
		isEditAction: false,
		isDelAction: false,
		isAddAction: false,
		showcheckbox: false,
		buttonsEx : [
			{
				name : 'import',
				text : "����",
				icon : 'excel',
				action: function (row) {
					var searchConditionKey = "";
					var searchConditionVal = "";
					for (var t in $("#contractGrid").data('yxgrid').options.searchParam) {
						if (t != "") {
							searchConditionKey += t;
							searchConditionVal += $("#contractGrid").data('yxgrid').options.searchParam[t];
						}
					}
					var type = $('#type').val();
					var state = $('#state').val();
					var createYear = $('#createYear').val();
					var createMonth = $('#createMonth').val();
					var i = 1;
					var colId = "";
					var colName = "";
					$("#contractGrid_hTable").children("thead").children("tr")
						.children("th").each(function () {
							if ($(this).css("display") != "none"
								&& $(this).attr("colId") != undefined) {
								colName += $(this).children("div").html() + ",";
								colId += $(this).attr("colId") + ",";
								i++;
							}
						})
					var searchSql = $("#contractGrid").data('yxgrid').getAdvSql();
					var searchArr = [];
					searchArr[0] = searchSql;
					searchArr[1] = searchConditionKey;
					searchArr[2] = searchConditionVal;


					window.open("?model=contract_contract_confirm&action=exportExcel&"
						+ "&colId="+colId + "&colName="+colName
						+ "&state="
						+ state
						+ "&type="
						+ type
						+ "&createYear="
						+ createYear
						+ "&createMonth="
						+ createMonth
						+ "&searchConditionKey="
						+ searchConditionKey
						+ "&searchConditionVal=" + searchConditionVal)
				}
			}],
		// ����Ϣ
		colModel: [
			{
				display: 'id',
				name: 'id',
				sortable: true,
				hide: true
			},
			{
				name: 'type',
				display: "���",
				sortable: true,
				width: 80
			},
			{
				name: 'contractCode',
				display: '��ͬ���',
				sortable: true,
				width: 120,
				process: function (v, row) {
					return '<a href="javascript:void(0)" onclick="javascript:showModalWin(\'?model=contract_contract_contract&action=toViewTab&id='
						+ row.contractId
						+ '&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800\')">'
						+ "<font color = '#4169E1'>"
						+ v
						+ "</font>"
						+ '</a>';
				},
				rclick : function() {
							 alert(222)
				}
			},{
				name: 'money',
				display: '�漰���',
				sortable: true,
				width: 80,
				process: function (v) {
					return moneyFormat2(v);
				}
			},
			{
				name: 'state',
				display: 'ȷ��״̬',
				sortable: true,
				align: 'center',
				width: 80,
				process: function (v, row) {
					if(v == '��ȷ��'){
						return "<div style='width:80px;cursor:pointer;' onclick='checkTip(\""+ row.id +"\",0)'>��</div>";
					}else{
						return "<div style='width:80px;cursor:pointer;' onclick='checkTip(\""+ row.id +"\",1)'>-</div>";
					}
				}
			},
			{
				name: 'handleName',
				display: 'ȷ����',
				sortable: true,
				width: 80
			},
			{
				name: 'handleDate',
				display: 'ȷ��ʱ��',
				sortable: true,
				width: 80
			},
			{
				name: 'createTime',
				display: '����ʱ��',
				sortable: true,
				width: 80
			}
		],
		comboEx : [{
			text: "���",
			key: 'type',
			data : [{
				text : '�쳣�ر�',
				value : '�쳣�ر�'
			}, {
				text : '��ͬ�ۿ�',
				value : '��ͬ�ۿ�'
			}, {
				text : '��ͬ���(����)',
				value : '��ͬ���(����)'
			},{
				text : '����Ʊ',
				value : '����Ʊ'
			}]
		},{
			text: "����״̬",
			key: 'state',
			value :"δȷ��",
			data : [{
				text : 'δȷ��',
				value : 'δȷ��'
			}, {
				text : '��ȷ��',
				value : '��ȷ��'
			}]
		},{
			text: "����ʱ��(��)",
			key: 'createYear',
			//value :"δȷ��",
			data : yearArr
		},{
			text: "����ʱ��(��)",
			key: 'createMonth',
			data : [{
				text : 'һ��',
				value : '01'
			}, {
				text : '����',
				value : '02'
			}, {
				text : '����',
				value : '03'
			}, {
				text : '����',
				value : '04'
			}, {
				text : '����',
				value : '05'
			}, {
				text : '����',
				value : '06'
			}, {
				text : '����',
				value : '07'
			}, {
				text : '����',
				value : '08'
			}, {
				text : '����',
				value : '09'
			}, {
				text : 'ʮ��',
				value : '10'
			}, {
				text : 'ʮһ��',
				value : '11'
			}, {
				text : 'ʮ����',
				value : '12'
			}]
		}],
		searchitems : [{
			display : '��ͬ���',
			name : 'contractCode'
		}]
	});
});



function checkTip(id,val){
	   $.ajax({
	    type: "POST",
	    url: "?model=contract_contract_confirm&action=ajaxCheckTip",
	    data: { "id" : id , "val" : val},
	    async: false,
	    success: function(data){
	   	   skey = data;
			$("#contractGrid").yxgrid("reload");
			location.reload();
		}
	});
}