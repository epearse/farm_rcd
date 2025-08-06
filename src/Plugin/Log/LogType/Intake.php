<?php

declare(strict_types=1);

namespace Drupal\farm_sli\Plugin\Log\LogType;

use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\farm_entity\Attribute\LogType;
use Drupal\farm_entity\Plugin\Log\LogType\FarmLogType;

/**
 * Provides the SLI Intake log type.
 */
#[LogType(
  id: 'sli_intake',
  label: new TranslatableMarkup('Intake'),
)]
class Intake extends FarmLogType {

}
