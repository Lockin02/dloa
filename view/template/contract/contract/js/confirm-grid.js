var show_page = function (page) {
	$("#contractGrid").yxgrid("reload");
};

var date=new Date;
var year=date.getFullYear();
var yearArr =  [{
			text : year + '年',
			value : year
		}, {
			text : year-1 + '年',
			value : year-1
		}, {
			text : year-2 + '年',
			value : year-2
		}, {
			text : year-3 + '年',
			value : year-3
		}];

$(function () {
	$("#contractGrid").yxgrid({
		model: 'contract_contract_confirm',
		//action: 'pageJsonBasic',
		title: '未确认信息<span style="color:red">  '+ $("#num").val() + '  </span>条',
		isViewAction: false,
		isEditAction: false,
		isDelAction: false,
		isAddAction: false,
		showcheckbox: false,
		buttonsEx : [
			{
				name : 'import',
				text : "导出",
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
		// 列信息
		colModel: [
			{
				display: 'id',
				name: 'id',
				sortable: true,
				hide: true
			},
			{
				name: 'type',
				display: "类别",
				sortable: true,
				width: 80
			},
			{
				name: 'contractCode',
				display: '合同编号',
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
				display: '涉及金额',
				sortable: true,
				width: 80,
				process: function (v) {
					return moneyFormat2(v);
				}
			},
			{
				name: 'state',
				display: '确认状态',
				sortable: true,
				align: 'center',
				width: 80,
				process: function (v, row) {
					if(v == '已确认'){
						return "<div style='width:80px;cursor:pointer;' onclick='checkTip(\""+ row.id +"\",0)'>√</div>";
					}else{
						return "<div style='width:80px;cursor:pointer;' onclick='checkTip(\""+ row.id +"\",1)'>-</div>";
					}
				}
			},
			{
				name: 'handleName',
				display: '确认人',
				sortable: true,
				width: 80
			},
			{
				name: 'handleDate',
				display: '确认时间',
				sortable: true,
				width: 80
			},
			{
				name: 'createTime',
				display: '发生时间',
				sortable: true,
				width: 80
			}
		],
		comboEx : [{
			text: "类别",
			key: 'type',
			data : [{
				text : '异常关闭',
				value : '异常关闭'
			}, {
				text : '合同扣款',
				value : '合同扣款'
			}, {
				text : '合同变更(减少)',
				value : '合同变更(减少)'
			},{
				text : '不开票',
				value : '不开票'
			}]
		},{
			text: "操作状态",
			key: 'state',
			value :"未确认",
			data : [{
				text : '未确认',
				value : '未确认'
			}, {
				text : '已确认',
				value : '已确认'
			}]
		},{
			text: "发生时间(年)",
			key: 'createYear',
			//value :"未确认",
			data : yearArr
		},{
			text: "发生时间(月)",
			key: 'createMonth',
			data : [{
				text : '一月',
				value : '01'
			}, {
				text : '二月',
				value : '02'
			}, {
				text : '三月',
				value : '03'
			}, {
				text : '四月',
				value : '04'
			}, {
				text : '五月',
				value : '05'
			}, {
				text : '六月',
				value : '06'
			}, {
				text : '七月',
				value : '07'
			}, {
				text : '八月',
				value : '08'
			}, {
				text : '九月',
				value : '09'
			}, {
				text : '十月',
				value : '10'
			}, {
				text : '十一月',
				value : '11'
			}, {
				text : '十二月',
				value : '12'
			}]
		}],
		searchitems : [{
			display : '合同编号',
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