/**
 * �����̶��ʲ���Ƭ
 */
(function($) {
	$.woo.yxcombogrid.subclass('woo.yxcombogrid_asset', {
				options : {
					hiddenId : 'assetId',
					nameCol : 'assetName',
					isDown : false,
					openPageOptions : {
						url : '?model=asset_assetcard_assetcard&action=selectAsset'
					},
					gridOptions : {
						showcheckbox : false,
						model : 'asset_assetcard_assetcard',
						// ����Ϣ
						colModel : [{
									display : '��Ƭ����',
									name : 'assetName'
								}]
					}
				}
			});
})(jQuery);