/**
 *�����ʲ���Ƭ������
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_assetinfo', {
		options : {
			isFocusoutCheck : false,
			gridOptions : {
				showcheckbox : false,
				model : 'asset_assetcard_assetcard',
				// ����Ϣ
				colModel : [{
						display : '�ʲ�����',
						name : 'assetName',
						width : '200'
					},{
						display : '�ֻ�Ƶ��',
						name : 'mobileBand',
						width : '80'
					},{
						display : '�ֻ�����',
						name : 'mobileNetwork',
						width : '80'
					}
				],
				// ��������
				searchitems : [{
						display : '�ʲ�����',
						name : 'assetName'
					},{
						display : '�ֻ�Ƶ��',
						name : 'mobileBand'
					},{
						display : '�ֻ�����',
						name : 'mobileNetwork'
					}				
				],
				// Ĭ�������ֶ���
				sortname : "id",
				// Ĭ������˳��
				sortorder : "ASC"
			}
		}
	});
})(jQuery);