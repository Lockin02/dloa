/**
 * 下拉固定资产卡片
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
						// 列信息
						colModel : [{
									display : '卡片名称',
									name : 'assetName'
								}]
					}
				}
			});
})(jQuery);