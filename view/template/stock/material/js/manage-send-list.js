var model='stock_material_management';

$(document).ready(function() {
	materialsTree();
});

function materialsTree() {

	$("#materials_tree").tree({
		url: publicUrl + "&action=load_history_tree",
		lines: true,
		checkbox: true,
		onlyLeafCheck: true,
		onClick: function(node) {
			if (typeof(node.state) == "undefined") {
				var id = node.id.split("_")[0];
//				var url = publicUrl + '&action=load_parts_SF';
//				$.post(url, {id:id}, function(data) {
//					$('#parts_table').datagrid({
//						title: data[0]['name'] +' /'+ data[0]['code'] + ' <a href=# onclick=editSF('+id+');>编辑</a>',
//
//					});
//				},'json');
				getHistoryDetail(id);
			}
		},
		onLoadSuccess: function (t, datas) {
			conditions = "id=";
			if(typeof datas == "undefined" || typeof datas[0] == "undefined" || typeof datas[0]["children"] == "undefined" || typeof datas[0]["children"][0] == "undefined") {
				return;
			}
		}
	});
}

function getHistoryDetail(id) {

//	if(state == 'but' || state == 'post'){
//		var vals = getInputVal();
//	}

	var width = $('#right').width();
	var height = $('#right').height()-30;

	url = publicUrl + "&action=view_history_detail";
	$.post(url,{id:id}, function (table) {
		$('#div_statistics').html('');
//		$("<input id='FId' value='"+id+"' type='hidden'>").appendTo($('#div_statistics'));
		$(table).appendTo($('#div_statistics'));
		$('#div_statistics').panel({
			  width:width,
			  height:height
		});
		$('#r_button').show();
//		$("#but").attr("onclick","getBomDetail("+id+",'but')");
		$("#picking").attr("onclick","picking("+id+")");
		$("#purchase").attr("onclick","purchase("+id+")");
//		$("#butRemove").attr("onclick","statisticsRemove("+id+")");

	});
}
function selectChecked(){
	if ($('#selectC').attr("checked")) {
		$("input[name=items]").each(function() {
			if( !$(this).attr("disabled")){
				$(this).attr("checked", true);
			}
		});
	} else {
	    $("input[name=items]").each(function() {
	    	$(this).attr("checked", false);
	    });
	}
}

























$(function() {
	 // exit();
	//默认显示的表头
	var default_columns = [
        { field: 'stock_code', title: '物料编号', align:'center', sortable:true, width:80 },
        { field: 'stock_name', title: '物料名称', align:'center', width:80 },
        { field: 'stock_model', title:'物料型号', align:'center', width:80 },
        { field: 'stock_packaging', title:'封装', align:'center', width:70 },
        { field: 'stock_factory', title:'厂商', align:'center', width:70 },
        { field: 'stock_total', align:'center', title:'实际需求', width:70 },
        { field: 'stock_loss_total', title:'总需求(含损耗)', align:'center',width:100 },
        { field: 'outStockNum', align:'center', title:'发料数', width:60 },
        { field: 'realOutNum', align:'center', title:'退库数', width:60, editor:"numberbox" },
        { field: 'useTotal', align:'center', title:'总用量', width:60 },
        { field: 'shortage', title:'未发数', align:'center', width:60, styler:function(value){if(value<0){return"background: #fcc"}} },
        { field: 'mustOutNum', align:'center', title:'', width:60 }
    ];
	//先复制一份默认的, 生成表格的时候用的是columns生成表头
	var columns = default_columns.slice(0, default_columns.length);

	//保存一份当前数据, 用于修改表头时不用再去请求数据
	var date_temp = [];

	//保存当前要显示的表头
	var field_temp = [];

	//当前ajax提交, 用于点击表单自定义字段checkbox很快的时候, 取消上一次的ajax提交
	var current_ajax = { abort: function () {} };

	//判断是否可以使用date_temp
	var isOldId = false;

	//生成表头选择checkbox
	createCheckBox(columns);

	//生成checkbox方法
	function createCheckBox(column) {
		/*********************表格自定义字段 begin************************/
		var tpl = $("#columnTemplate").html(),
			html =  template(column, tpl),
			clock;

		$("#column_div").html("<span>关闭</span>" + html);
		$("#column_div :checkbox").change(function () {
			clearTimeout(clock);
			clock = setTimeout(function () {
				columns = [];
				$("#column_div :checkbox:checked").each(function () {
					columns.push(default_columns[$(this).val()]);
				});

				beginShowList();
			}, 400);
		});
		$("#column_div span").click(function () {
			$("#column_div").hide();
		});
		/**********************表格自定义字段 end***********************/

		/********************导出自定义字段 beign*************************/
		tpl = $("#columnExportTemplate").html(),
		html = template(column, tpl);
		$("#columns_form").html(html);
		/********************导出自定义字段 end************************/


		//js模版替换
		function template(datas, tpl) {
			var reg = new RegExp("\{(.*?)\}", "g"),
				replace = [],
				match,
				result = "";
			while(match = reg.exec(tpl)) {
				replace.push([match[0], match[1]]);
			}

			var dataLength = datas.length;
			var replaceLength = replace.length;
			for(var i=0; i<dataLength; i++) {
				datas[i].index = i;
				var tplReplace = tpl;
				for(var j=0; j<replaceLength; j++) {
					tplReplace = tplReplace.replace(replace[j][0], datas[i][replace[j][1]]);
				}
				result += tplReplace;
			}

			return result;
		}
	}

	/*
	 * 列表显示方法
	 * 获取页面中的$("#current_list_id").val(), 作为id
	 * 再次生成列表
	 */
	function beginShowList() {
		var id = $("#current_list_id").val();

		if(!id) {
			return;
		}

		/* 这里判断是否多产品配置单, 如果是多产品配置先获取产品名, 最后生成详细列表 */
		if(id.split("_")[2] > 0) {
			getMoreConfigName(id);
		} else {
			loadMaterialSendDetail(id);
		}
	}

	//获取发料记录列表
	$.post("index1.php?model=" + model + "&action=load_send_tree", {}, function (datas) {
		//先对data时间做时区转换
		for(var i in datas) {
			var data = datas[i];
			if(typeof data.children == "undetined") {
				continue;
			}
			for(var j in data.children) {
				var date = new Date();
				date.setTime(Date.parse(data.children[j].text.replace(/-/g, "/")));

				var result = new Date();
				result.setUTCHours(date.getHours(), date.getMinutes(), date.getSeconds());
				result.setUTCFullYear(date.getFullYear(), date.getMonth(), date.getDate());

				var year = result.getFullYear(),
					month = 1 + result.getMonth(),
					date = result.getDate(),
					hours = result.getHours(),
					minutes = result.getMinutes(),
					seconds = result.getSeconds();

				data.children[j].text = year + "-" + (month > 9 ? month : "0" + month) + "-" + (date > 9 ? date : "0" + date) + " " + (hours > 9 ? hours : "0" + hours) + ":" + (minutes > 9 ? minutes : "0" + minutes) + ":" + (seconds > 9 ? seconds : "0" + seconds);
			}
		}

		//生成发料历史记录菜单
		$("#materials_tree").tree({
			data: datas,
			onClick: function(node) {
				if(typeof node.state != "undefined") {
					return;
				}

				var id = node.id;
				$("#current_list_id").val(id);

				isOldId = false;
				beginShowList();
			}
		});
	}, "json");

	//获取多配置单组件名
	function getMoreConfigName(id) {
		if(!isOldId) {
			current_ajax.abort();
			current_ajax = $.post("index1.php?model=" + model + "&action=get_product_name&id=" + id, {}, function (json) {
				if(typeof json.data == "undefined") {
					$.messager.alert("提示", "数据错误, 请重试");
					return;
				}

				success(json.data);
				field_temp = json.data;
			}, "json");
		} else {
			success(field_temp);
		}

		function success(datas) {
			var colunm = columns.slice(0, columns.length);
			var columns_pids = datas;
			var columns_add = [];
			for(var i in columns_pids) {
				var pid = i;
				columns_add.push({
					title: columns_pids[pid],
					field: pid,
					align: 'center',
					width: 60
				});
			}
			columns_add.unshift(colunm.length > 5 ? 5 : colunm.length, 0);
			Array.prototype.splice.apply(colunm, columns_add);

			loadMaterialSendDetail(id, colunm);
		}
	}

	var editorList = [],  //需要修改的行序号
		toChangeList = [];  //提交修改的数据

	//获取发料详细列表
	function loadMaterialSendDetail(id, column) {
		$("#bom_div").html("<table id='configuration_table'></table>");

		var table = $("#configuration_table");

		var column = column || columns;

		var changeDatas = {};

		var canPost = true;

		var error = [];

		var option = {
			idField: "id",
			columns: [column],
			title: "发料记录 - 双击修改",
			singleSelect: true,
			fitColumns: true,
			height: 800,
			toolbar: [{
				text: "导出",
				iconCls: "icon-ok",
				handler: function () {
					$("#columns_box").dialog({
						title: "导出",
						buttons: [
							{
								text: "导出",
								handler: function () {
									$("#columns_form").submit();
									$("#columns_form :checkbox").prop("checked", true);
									$("#columns_box").dialog("close");
								}
							},
							{
								text: "取消",
								handler: function () {
									$("#columns_box").dialog("close");
								}
							}
						]
					}).dialog("open");

					$("#columns_form").attr("action", "index1.php?model=" + model + "&action=export_send_list&id=" + id);
				}
			}, {
				text: "保存修改",
				iconCls: "icon-save",
				handler: function () {
					var editorLength = editorList.length;
					if(!editorLength) {
						$.messager.show({
	                        title: '提示',
	                        msg: '无修改！',
	                        timeout: 3000,
	                        showType: 'show'
	                    });

						return;
					}

					canPost = true;

					//循环结束修改, 自动触发onAfterEdit方法
					for(var i=0; i<editorLength; i++) {
						table.datagrid("endEdit", editorList[i]);
					}

					isOldId = false;

					if(!canPost) {
						$.messager.alert("提示", error.join("<br />"));

						error = [];
						return;
					}

					//提交修改数据
					$.post("index1.php?model=" + model + "&action=edit_send_list", { data: toChangeList }, function () {
						$.messager.alert("提示", "保存成功");

						//重新获取发料详细
						loadMaterialSendDetail(id);
					}, "json");

					editorList = [], toChangeList = [];  //重置修改数据
				}
			}, {
				text: "再次发料",
				iconCls: "icon-reload",
				handler: function () {
					createMergeList(id, function () {
						//确认发料后, 重新加载发料清单
						loadMaterialSendDetail(id);
					});
				}
			}, {
				text: "自定义字段",
				iconCls: "icon-edit",
				handler: function () {
					var el = $(this);
					var box = $("#column_div");

					box.show().offset(el.offset());
				}
			}, {
				text: "采购申请",
				iconCls: "icon-print",
				handler: function () {

					var datas = $("#configuration_table").datagrid("getData")["rows"],
						form = $("#buyForm").html(""),
						data,
						value,
						name,
						input,
						html = "";

					for(var i in datas) {
						data = datas[i];
						for(var filed in data) {
							name = "rows[" + i + "][" + filed + "]";
							value = data[filed];
							input = "<input type=\"hidden\" name=\"" + name + "\" value=\"" + value + "\" />";
							html += input;
						}
					}

					form.html(html);
					form.submit();
				}
			}],
			onDblClickRow: function (rowIndex, rowData) {
				//双击开始修改
				table.datagrid("beginEdit", rowIndex);

				//已经在修改, 不需要再添加
				if($.inArray(rowIndex, editorList) !== -1) {
					return;
				}

				//添加id进修改数组
				editorList.push(rowIndex);

				changeDatas[rowIndex] = {
					outStockNum: rowData.outStockNum,
					actNum: rowData.actNum,
					realOutNum: rowData.realOutNum
				};
			},
			onAfterEdit: function (rowIndex, rowData, changes) {
				if(typeof changes.realOutNum == "undefined") {
					return;
				}

				//获取要修改的数据
				var data = {
					id: rowData.id,
					realOutNum: changes.realOutNum
				};

				if(+changes.realOutNum > +rowData.outStockNum) {
					error.push("物料编号: " + rowData["stock_code"] + " 退库数不能大于发料数");

					canPost = false;

					table.datagrid("updateRow",{
						index: rowIndex,
						row: {
							realOutNum: changeDatas[rowIndex].realOutNum
						}
					});

					return;
				}

				toChangeList.push(data);

			},
			loadFilter: function (datas) {
				//本地计算损耗率
				for(var i in datas) {
					datas[i]["shortage"] = datas[i]["shortage"] != 0 ? -(+datas[i]["shortage"]) : 0;

					datas[i]["stock_loss_total"] = Math.ceil(datas[i]["stock_total"] * ((+datas[i]["sunhao"] + 100) / 100));

					datas[i]['useTotal'] = datas[i]['outStockNum'] - datas[i]['realOutNum'];
				}

				data_temp = datas;
				isOldId = true;

				return {
					rows: datas,
					total: datas.length
				};
			},
			onLoadSuccess: function () {
				changeDatas = {};
				error = [];
			}
		};

		if(isOldId) {
			option.data = data_temp;
		} else {
			option.url = "index1.php?model=stock_material_management&action=load_send_list&id=" + id;
		}

		//生成表格
		table.datagrid(option);
	}

	//生成发料清单
	function createMergeList(id, callback) {
		//打开独立窗口, 生成发料清单
		openDialog('public_dialog', '发料清单', 'full', 'full');

		$("#merge_list").datagrid({
			url: "index1.php?model=" + model + "&action=mergeDataSubAgain",
			queryParams: {
				id: id
			},
			idField: "id",
			columns: [
						[{
							field: 'stock_code',
							title: '物料编号',
							align: 'center',
							sortable: true,
							width: 110
						}, {
							field: 'stock_name',
							title: '物料名称',
							align: 'center',
							width: 111
						}, {
							field: 'stock_model',
							title: '物料型号',
							align: 'center',
							width: 110
						}, {
							field: 'stock_packaging',
							title: '封装',
							align: 'center',
							width: 100
						}, {
							field: 'stock_serial_number',
							title: '元件序号',
							align: 'center',
							width: 100
						}, {
							field: 'stock_factory',
							title: '厂商',
							align: 'center',
							width: 100
						}, {
							field: 'stock_total',
							align: 'center',
							title: '需求数',
							width: 90
						},{
							field: 'actNum',
							align: 'center',
							title: '现余库存',
							width: 90
						}, {
							field: 'outStockNum',
							align: 'center',
							title: '发料数',
							width: 100
						}, {
							field: 'shortage',
							title: '缺货数',
							align: 'center',
							width: 105,
							styler: function(value) {
								if (value > 0) {
									return "background: #fcc";
								}
							}
						}]
					],
			singleSelect: true,
			height: 600,
			toolbar: [{
				text: "确认发料",
				iconCls: "icon-ok",
				handler: function () {
					$("#merge_list").datagrid("loading");
					$.post("index1.php?model=" + model + "&action=confirmSecondSendStock", { id: id }, function () {
						$.messager.show({
	                        title: '提示',
	                        msg: '发料成功！',
	                        timeout: 3000,
	                        showType: 'show'
	                    });
						typeof callback == "function" && callback();
						closeDialog("public_dialog");
					}, "json");
				}
			}]
		});
	}
});