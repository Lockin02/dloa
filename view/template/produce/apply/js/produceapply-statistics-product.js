$(document).ready(function () {
	var productInfoObj = $("#productInfo");
	productInfoObj.yxeditgrid({
		type: 'view',
		url: '?model=produce_apply_produceapply&action=statisticsListJson',
		param: {
			typeId: $('#typeId').val(),
			num: $('#num').val()
		},
		colModel: [{
			display: 'id',
			name: 'id',
			type: 'hidden'
		}, {
			name: 'code',
			display: '物料编码',
			process: function (v, row) {
				if (row.isChildren == 1) {
					return '';
				} else {
					return v;
				}
			}
		}, {
			name: 'name',
			display: '物料名称',
			process: function (v, row, $tr, g, $input, rowNum) {
				if (row.isChildren == 1) {
					var showHtml = '<td onclick="showAndHideDiv(\'' + rowNum + 'Img\',\'childrenTable' + rowNum +
						'\')">&nbsp;<img src="images/icon/info_up.gif" id="' + rowNum + 'Img"/></td>';
					htmlStr = '<tr>' + showHtml + '<td colspan="11"><div id=childrenTable' + rowNum + '></div></td></tr>';
					$tr.after(htmlStr);
					$tr.css('background-color', 'yellow');
					$("#childrenTable" + rowNum).yxeditgrid({
						type: 'view',
						url: '?model=produce_apply_produceapply&action=childrenListJson',
						param: {
							parentId: row.id,
							num: row.num,
							showNum: true
						},
						event: {
							reloadData: function () {
								//加载完数据隐藏序号
								$("#childrenTable" + rowNum + " > table > thead > tr").children().eq(0).hide(); //隐藏表头
								$("#childrenTable" + rowNum + " > table > tbody > tr").each(function () {
									$(this).children().eq(0).hide(); //隐藏每一行数据的序号
								});
							}
						},
						colModel: [{
							name: 'code',
							display: '物料编码'
						}, {
							name: 'name',
							display: '物料名称'
						}, {
							name: 'pattern',
							display: '规格型号'
						}, {
							name: 'unitName',
							display: '单位'
						}, {
							name: 'num',
							display: '需求数量'
						}, {
							name: 'inventory',
							display: '库存数量'
						}, {
							name: 'JSBC',
							display: '旧设备仓'
						}, {
							name: 'KCSP',
							display: '库存商品'
						}, {
							name: 'SCC',
							display: '生产仓'
						}, {
							name: 'onwayAmount',
							display: '在途数量'
						}, {
							name: 'simplifiedNum',
							display: '差异数',
							process: function (v) {
								if (v > 0) {
									return '<span class="green">' + v + '</span>';
								} else {
									return '<span class="red">' + v + '</span>';
								}
							}
						}]
					});
				}
				return v;
			}
		}, {
			name: 'pattern',
			display: '规格型号'
		}, {
			name: 'unitName',
			display: '单位'
		}, {
			name: 'num',
			display: '需求数量'
		}, {
			name: 'inventory',
			display: '库存数量'
		}, {
			name: 'JSBC',
			display: '旧设备仓'
		}, {
			name: 'KCSP',
			display: '库存商品'
		}, {
			name: 'SCC',
			display: '生产仓'
		}, {
			name: 'onwayAmount',
			display: '在途数量'
		}, {
			name: 'simplifiedNum',
			display: '差异数',
			process: function (v) {
				if (v > 0) {
					return '<span class="green">' + v + '</span>';
				} else {
					return '<span class="red">' + v + '</span>';
				}
			}
		}]
	});
});