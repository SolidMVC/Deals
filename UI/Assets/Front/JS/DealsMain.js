/**
 * Plugin Front End JS
 * License: Licensed under the AGPL license.
 */

// NOTE: For object-oriented language experience, this variable name should always match current file name
var DealsMain = {
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

    changeThumbnailAndDescription: function(thumbContainer, dealsNamesContainer, dealName, dealThumb, description)
    {

        var objDescription = jQuery('.' + description);
        this.changeSlideThumb(thumbContainer, dealsNamesContainer, dealName, dealThumb);

        if(objDescription.length > 0){
            this.showDescription(description);
        }
    },

    changeSlideThumb: function (thumbContainer, dealsNamesContainer, dealName, dealThumb)
    {
        var objDealsThumbContainer = jQuery('.responsive-deals-slider .' + thumbContainer + ' .deal-thumbnail-container');
        var objDealsNameContainer = jQuery('.responsive-deals-slider .' + dealsNamesContainer + ' .single-deal');
        var objDealName = jQuery('.responsive-deals-slider .' + dealName);
        var objDealThumb = jQuery('.responsive-deals-slider .' + dealThumb);

        // Reset 'is-current' class to current slides thumbnail
        objDealsThumbContainer.removeClass('is-current');
        objDealThumb.addClass('is-current');

        // Reset 'selected' class to current slides thumbnail
        objDealsNameContainer.removeClass('selected');
        objDealName.addClass('selected');
    },

    leftAlignAllStripes: function (sliderContainer)
    {

        // Timeout required for the browser to have time to adjust
        setTimeout(function() {

            var objDealStripes = jQuery('.' + sliderContainer + ' .deals-stripe');
            var offsetLeft = -Math.round(document.querySelector('.' + sliderContainer + ' .single-deal').offsetLeft);

            objDealStripes.css({
                    left : offsetLeft
                }
            );

        },150);
    },

    showDescription: function(description)
    {
        var objDescriptionsContainer = jQuery('.deal-description');
        var objDescription = jQuery('.' + description);

        objDescriptionsContainer.removeClass('is-current');
        objDescription.addClass('is-current');
    },

    hideDescriptions: function(descriptionsContainer)
    {
        var objDescriptions = jQuery('.' + descriptionsContainer + ' .deal-description');
        objDescriptions.removeClass('is-current');
    },

};