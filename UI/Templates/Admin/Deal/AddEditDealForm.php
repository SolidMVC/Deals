<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-validate');
wp_enqueue_script('deals-admin');

// Styles
wp_enqueue_style('jquery-validate');
wp_enqueue_style('deals-admin');
?>
<p>&nbsp;</p>
<div id="container-inside" style="width:1000px;">
   <span style="font-size:16px; font-weight:bold"><?=esc_html($lang['LANG_DEAL_ADD_EDIT_TEXT']);?></span>
   <input type="button" value="<?=esc_attr($lang['LANG_DEAL_BACK_TO_LIST_TEXT']);?>" onClick="window.location.href='<?=esc_url($backToListURL);?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/>
   <hr style="margin-top:10px;"/>
   <form action="<?=esc_url($formAction);?>" method="POST" class="deals-add-edit-deal-form" enctype="multipart/form-data">
        <table cellpadding="5" cellspacing="2" border="0">
            <input type="hidden" name="deal_id" value="<?=esc_attr($dealId);?>"/>

<tr>
    <td width="95px"><strong><?=esc_html($lang['LANG_DEAL_TITLE_TEXT']);?>:</strong></td>
    <td colspan="2">
        <input type="text" name="deal_title" maxlength="15" value="<?=esc_attr($dealTitle);?>" class="required deal-title" style="width:350px;" title="<?=esc_attr($lang['LANG_DEAL_TITLE_TEXT']);?>" />
    </td>
</tr>
<tr>
    <td><strong><?=esc_html($lang['LANG_DEAL_IMAGE_TEXT']);?>:</strong></td>
    <td colspan="2">
        <input type="file" name="deal_image" style="width:250px;" title="<?=esc_attr($lang['LANG_IMAGE_TEXT']);?>" />
        <?php if($dealImageURL != ""): ?>
            <span>
    &nbsp;&nbsp;&nbsp;<a rel="collection" href="<?=esc_url($dealImageURL);?>" target="_blank">
        <strong><?=$lang[$demoDealImage ? 'LANG_IMAGE_VIEW_DEMO_TEXT' : 'LANG_IMAGE_VIEW_TEXT'];?></strong>
    </a>
    &nbsp;&nbsp;&nbsp;&nbsp;<span style="color: navy;">
        <strong><?=$lang[$demoDealImage ? 'LANG_IMAGE_UNSET_DEMO_TEXT' : 'LANG_IMAGE_DELETE_TEXT'];?></strong>
    </span> &nbsp;
    <input type="checkbox" name="delete_deal_image"
           title="<?=$lang[$demoDealImage ? 'LANG_IMAGE_UNSET_DEMO_TEXT' : 'LANG_IMAGE_DELETE_TEXT'];?>" />
</span>
        <?php else: ?>
            &nbsp;&nbsp;&nbsp;&nbsp; <strong><?=esc_html($lang['LANG_IMAGE_NONE_TEXT']);?></strong>
        <?php endif; ?>
    </td>
</tr>
<tr>
    <td><strong><?=esc_html($lang['LANG_DEAL_TARGET_URL_TEXT']);?>:</strong></td>
    <td colspan="2">
        <input type="text" name="target_url" maxlength="255" value="<?=esc_url($targetURL);?>" style="width:350px;" title="<?=esc_attr($lang['LANG_DEAL_TARGET_URL_TEXT']);?>" />
    </td>
</tr>
<tr>
    <td>
        <strong><?=esc_html($lang['LANG_DEAL_DESCRIPTION_TEXT']);?>:</strong><br />
    </td>
    <td colspan="2">
        <textarea name="deal_description" rows="3" cols="50" class="deal-description" title="<?=esc_attr($lang['LANG_DEAL_DESCRIPTION_TEXT']);?>"><?=esc_textarea($dealDescription);?></textarea><br />
        <em>(<?=esc_html($lang['LANG_DEAL_DESCRIPTION_OPTIONAL_TEXT']);?>)</em>
    </td>
</tr>
<tr>
    <td><strong><?=esc_html($lang['LANG_ENABLED_TEXT']);?>:</strong></td>
    <td colspan="2">
        <input type="checkbox" id="deal_enabled" name="deal_enabled" value="yes"<?=($dealEnabled ? ' checked="checked"' : '');?> title="<?=esc_attr($lang['LANG_ENABLED_TEXT']);?>" /> <?=esc_html($lang['LANG_YES_TEXT']);?>
    </td>
</tr>
<tr>
    <td><strong><?=esc_html($lang['LANG_DEAL_ORDER_TEXT']);?>:</strong></td>
    <td>
        <input type="text" name="deal_order" maxlength="11" value="<?=esc_attr($dealOrder);?>" class="deal-order" style="width:40px;" title="<?=esc_attr($lang['LANG_DEAL_ORDER_TEXT']);?>" />
    </td>
    <td>
        <em><?=($dealId > 0 ? '' : '('.esc_html($lang['LANG_DEAL_ORDER_OPTIONAL_TEXT']).')');?></em>
    </td>
</tr>
<tr>
    <td></td>
    <td colspan="2"><input type="submit" value="<?=esc_attr($lang['LANG_DEAL_SAVE_TEXT']);?>" name="save_deal" style="cursor:pointer;"/></td>
</tr>

        </table>
    </form>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
    // Validator
    jQuery('.deals-add-edit-deal-form').validate();
});
</script>
