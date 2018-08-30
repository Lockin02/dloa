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
			display: '���ϱ���',
			process: function (v, row) {
				if (row.isChildren == 1) {
					return '';
				} else {
					return v;
				}
			}
		}, {
			name: 'name',
			display: '��������',
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
								//�����������������
								$("#childrenTable" + rowNum + " > table > thead > tr").children().eq(0).hide(); //���ر�ͷ
								$("#childrenTable" + rowNum + " > table > tbody > tr").each(function () {
									$(this).children().eq(0).hide(); //����ÿһ�����ݵ����
								});
							}
						},
						colModel: [{
							name: 'code',
							display: '���ϱ���'
						}, {
							name: 'name',
							display: '��������'
						}, {
							name: 'pattern',
							display: '����ͺ�'
						}, {
							name: 'unitName',
							display: '��λ'
						}, {
							name: 'num',
							display: '��������'
						}, {
							name: 'inventory',
							display: '�������'
						}, {
							name: 'JSBC',
							display: '���豸��'
						}, {
							name: 'KCSP',
							display: '�����Ʒ'
						}, {
							name: 'SCC',
							display: '������'
						}, {
							name: 'onwayAmount',
							display: '��;����'
						}, {
							name: 'simplifiedNum',
							display: '������',
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
			display: '����ͺ�'
		}, {
			name: 'unitName',
			display: '��λ'
		}, {
			name: 'num',
			display: '��������'
		}, {
			name: 'inventory',
			display: '�������'
		}, {
			name: 'JSBC',
			display: '���豸��'
		}, {
			name: 'KCSP',
			display: '�����Ʒ'
		}, {
			name: 'SCC',
			display: '������'
		}, {
			name: 'onwayAmount',
			display: '��;����'
		}, {
			name: 'simplifiedNum',
			display: '������',
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