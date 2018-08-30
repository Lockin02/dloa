/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    config.enterMode = CKEDITOR.ENTER_BR;
    config.shiftEnterMode = CKEDITOR.ENTER_P;
    //从WORD中复制是否去除样式（配置后可进行选择）
    CKEDITOR.config.pasteFromWordRemoveFontStyles = false;
    CKEDITOR.config.pasteFromWordPromptCleanup = true;
	config.skin = 'v2'
};
