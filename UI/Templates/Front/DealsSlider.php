<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('deals-main');
if($settings['conf_load_slick_slider_from_plugin'] == 1):
    wp_enqueue_script('slick-slider');
endif;

// Styles
if($settings['conf_load_font_awesome_from_plugin'] == 1):
    wp_enqueue_style('font-awesome');
endif;
if($settings['conf_load_slick_slider_from_plugin'] == 1):
    wp_enqueue_style('slick-slider');
    wp_enqueue_style('slick-theme');
endif;
wp_enqueue_style('deals-main');
?>
<div class="deals-wrapper deals-deals-slider">
    <?php if(sizeof($dealSlides) > 0): ?>
        <div class="responsive-deals-slider">
            <?php foreach($dealSlides as $slideId => $deals): ?>
                <div class="slide-container">
                    <div class="deals-slide deals-slide-<?=esc_attr($slideId);?>">
                        <div class="slides-wrapper flex-container">

<div class="slider-image-container slide<?=esc_attr($slideId);?>-deal-images">
<?php foreach($deals as $deal): ?>
        <div class="deal-thumbnail-container deal<?=esc_attr($deal['deal_id']);?>-thumbnail-container<?=($deal['selected'] ? ' is-current' : '');?>
<?=($deal['deal_thumb_url'] == "" ? ' no-image' : '');?>"
            <?php if($deal['translated_deal_description'] != ""): ?>
                 onmouseover="javascript:DealsMain.showDescription('deal-description-<?=esc_attr($deal['deal_id']);?>');"
                 onmouseleave="javascript:DealsMain.hideDescriptions('deals-slide-<?=esc_attr($slideId);?>');"
            <?php endif; ?>
            >
            <?php if($deal['deal_thumb_url'] != ""): ?>
                <?php if($deal['target_url'] != ""): ?>
                    <a href="<?=esc_attr($deal['target_url']);?>" target="_blank">
                <?php endif; ?>
                <img src="<?=esc_attr($deal['deal_thumb_url']);?>" class="deal<?=esc_attr($deal['deal_id']);?>-thumb"
                     alt="<?=esc_attr($lang['LANG_IMAGE_TEXT']);?>"
                     title="<?=esc_attr($deal['deal_title']);?>"
                />
                <?php if($deal['target_url'] != ""): ?>
                    </a>
                <?php endif; ?>
            <?php endif; ?>
            <?php if($deal['translated_deal_description'] != ""): ?>
                <div class="deal-description deal-description-<?=esc_attr($deal['deal_id']);?> flex-container">
                    <?=esc_br_html($deal['translated_deal_description']);?>
                    <?php if($deal['target_url'] != ""): ?>
                        <a href="<?=esc_attr($deal['target_url']);?>" class="deal-link"> <?=esc_br_html($lang['LANG_DEAL_VIEW_TEXT']);?></a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</div>
<div class="slider-deals slide<?=esc_attr($slideId);?>-deal-names flex-container">
    <?php foreach($deals as $deal): ?>
        <div class="single-deal deal<?=esc_attr($deal['deal_id']);?>-name<?=($deal['selected'] ? ' selected' : '');?> flex-container"
             onmouseover="javascript:DealsMain.changeThumbnailAndDescription(
                     'slide<?=esc_attr($slideId);?>-deal-images', 'slide<?=esc_attr($slideId);?>-deal-names',
                     'deal<?=esc_attr($deal['deal_id']);?>-name', 'deal<?=esc_attr($deal['deal_id']);?>-thumbnail-container', 'deal-description-<?=esc_attr($deal['deal_id']);?>'
                     );"
             onmouseleave="javascript:DealsMain.hideDescriptions('deals-slide-<?=esc_attr($slideId);?>');"
        >
            <div class="deals-stripe"></div>
            <h3 class="flex-container">
                <i class="fa fontawesome-icon fa-chevron-circle-right circle-no"></i>
                <?php if($deal['target_url'] != ""): ?>
                    <a href="<?=esc_attr($deal['target_url']);?>" target="_blank"><span class="deal-target"><?=esc_html($deal['translated_short_deal_title']);?></span></a>
                <?php else: ?>
                    <?=esc_html($deal['translated_short_deal_title']);?>
                <?php endif; ?>
            </h3>
        </div>
    <?php endforeach; ?>
</div>

                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-deals-available"><?=esc_html($lang['LANG_DEALS_NONE_AVAILABLE_TEXT']);?></div>
    <?php endif; ?>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {

    jQuery('.responsive-deals-slider').slick({
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 1,
        slidesToScroll: 1,
        prevArrow: '<button type="button" class="deals-slider-prev"><?=esc_html($lang['LANG_PREVIOUS_TEXT']);?></button>',
        nextArrow: '<button type="button" class="deals-slider-next"><?=esc_html($lang['LANG_NEXT_TEXT']);?></button>',
        responsive: [
            {
                breakpoint: 584,
                settings: {
                    arrows: false,
                    dots: true
                }
            },
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });

    DealsMain.leftAlignAllStripes('responsive-deals-slider');

    jQuery(window).resize(function(){
        DealsMain.leftAlignAllStripes('responsive-deals-slider');
    });

    // This part is needed for when last slide has 1 deal and the previous slide button is not seen on a white background

    var responsiveDealsSliderLenght = <?=esc_js(sizeof($dealSlides)-1);?>;
    var objPrevButton = jQuery('.responsive-deals-slider .deals-slider-prev');
    var dealsOnLastSlide = <?=esc_js($dealsOnLastSlide);?>;

    if(dealsOnLastSlide < 2)
    {
        jQuery('.responsive-deals-slider').on('beforeChange', function (event, slick, currentSlide, nextSlide) {
            (nextSlide == responsiveDealsSliderLenght) ? objPrevButton.addClass('has-1deal') : objPrevButton.removeClass('has-1deal');
        });
    }
});
</script>