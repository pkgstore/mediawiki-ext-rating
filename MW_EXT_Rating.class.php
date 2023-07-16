<?php

namespace MediaWiki\Extension\PkgStore;

use ConfigException;
use MWException;
use OutputPage, Parser, PPFrame, Skin;

/**
 * Class MW_EXT_Rating
 */
class MW_EXT_Rating
{
  /**
   * Register tag function.
   *
   * @param Parser $parser
   *
   * @return void
   * @throws MWException
   */
  public static function onParserFirstCallInit(Parser $parser): void
  {
    $parser->setFunctionHook('rating', [__CLASS__, 'onRenderTag'], Parser::SFH_OBJECT_ARGS);
  }

  /**
   * Render tag function.
   *
   * @param Parser $parser
   * @param PPFrame $frame
   * @param array $args
   *
   * @return string|null
   */
  public static function onRenderTag(Parser $parser, PPFrame $frame, array $args): ?string
  {
    // Get options parser.
    $getOption = MW_EXT_Kernel::extractOptions($frame, $args);

    // Argument: title.
    $getTitle = MW_EXT_Kernel::outClear($getOption['title'] ?? '' ?: '');
    $outTitle = $getTitle;

    // Argument: count.
    $getCount = MW_EXT_Kernel::outClear($getOption['count'] ?? '' ?: '');
    $outCount = $getCount;

    // Argument: icon-plus.
    $getIconPlus = MW_EXT_Kernel::outClear($getOption['icon-plus'] ?? '' ?: 'fas fa-star');
    $outIconPlus = $getIconPlus;

    // Argument: icon-minus.
    $getIconMinus = MW_EXT_Kernel::outClear($getOption['icon-minus'] ?? '' ?: 'far fa-star');
    $outIconMinus = $getIconMinus;

    // Setting: MW_EXT_Rating_minCount.
    $setMinCount = MW_EXT_Kernel::getConfig('MW_EXT_Rating_minCount');

    // Setting: MW_EXT_Rating_maxCount.
    $setMaxCount = MW_EXT_Kernel::getConfig('MW_EXT_Rating_maxCount');

    // Check rating title, count, set error category.
    if (empty($outTitle) || !ctype_digit($getCount) || $getCount > $setMaxCount) {
      $parser->addTrackingCategory('mw-rating-error-category');

      return null;
    }

    $outStars = '';

    // Out rating: icon-plus.
    for ($i = 1; $i <= $getCount; $i++) {
      $outStars .= '<span class="' . $outIconPlus . ' fa-fw mw-rating-star mw-rating-star-plus"></span>';
    }

    // Out rating: icon-minus.
    while ($i <= $setMaxCount) {
      $outStars .= '<span class="' . $outIconMinus . ' fa-fw mw-rating-star mw-rating-star-minus"></span>';
      $i++;
    }

    // Out HTML.
    $outHTML = '<div class="mw-rating mw-rating-count-' . $outCount . ' navigation-not-searchable" itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">';
    $outHTML .= '<div class="mw-rating-body"><div class="mw-rating-content">';
    $outHTML .= '<div class="mw-rating-text">' . $outTitle . '</div>';
    $outHTML .= '<div class="mw-rating-count">' . $outStars . '</div>';
    $outHTML .= '</div></div>';
    $outHTML .= '<meta itemprop="worstRating" content = "' . $setMinCount . '" />';
    $outHTML .= '<meta itemprop="ratingValue" content = "' . $outCount . '" />';
    $outHTML .= '<meta itemprop="bestRating" content = "' . $setMaxCount . '" />';
    $outHTML .= '</div>';

    // Out parser.
    return $outHTML;
  }

  /**
   * Load resource function.
   *
   * @param OutputPage $out
   * @param Skin $skin
   *
   * @return void
   */
  public static function onBeforePageDisplay(OutputPage $out, Skin $skin): void
  {
    $out->addModuleStyles(['ext.mw.rating.styles']);
  }
}
