/**
 * Plugin Admin JS
 * License: Licensed under the AGPL license.
 */

// Dynamic variables
if(typeof DealsVars === "undefined")
{
    // The values here will come from WordPress script localizations,
    // but in case if they wouldn't, we have a backup initializer below
    var DealsVars = {};
}

// Dynamic language
if(typeof DealsLang === "undefined")
{
    // The values here will come from WordPress script localizations,
    // but in case if they wouldn't, we have a backup initializer below
    var DealsLang = {};
}

// NOTE: For object-oriented language experience, this variable name should always match current file name
var DealsAdmin = {
    vars: DealsVars,
    lang: DealsLang,

    getValidCode: function(paramCode, paramDefaultValue, paramToUppercase, paramSpacesAllowed, paramDotsAllowed)
    {
        var regexp = '';
        if(paramDotsAllowed)
        {
            regexp = paramSpacesAllowed ? /[^-_0-9a-zA-Z. ]/g : /[^-_0-9a-zA-Z.]/g; // There is no need to escape dot char
        } else
        {
            regexp = paramSpacesAllowed ?  /[^-_0-9a-zA-Z ]/g : /[^-_0-9a-zA-Z]/g;
        }
        var rawData = Array.isArray(paramCode) === false ? paramCode : paramDefaultValue;
        var validCode = rawData.replace(regexp, '');

        if(paramToUppercase)
        {
            validCode = validCode.toUpperCase();
        }

        return validCode;
    },

    getValidPrefix: function(paramPrefix, paramDefaultValue)
    {
        var rawData = Array.isArray(paramPrefix) === false ? paramPrefix : paramDefaultValue;
        return rawData.replace(/[^-_0-9a-z]/g, '');
    },

    deleteDeal: function(paramDealId)
    {
        var approved = confirm(this.lang['LANG_DEAL_DELETING_DIALOG_TEXT']);
        if (approved)
        {
            window.location = 'admin.php?page=deals-add-edit-deal&noheader=true&delete_deal=' + paramDealId;
        }
    }
};