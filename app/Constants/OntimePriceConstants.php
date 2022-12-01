<?php

namespace App\Constants;

final class OntimePriceConstants
{
  public const TYPE_PRICE = [
    OntimeTypeConstants::TYPE_60 => 0.055 * 23000,
    OntimeTypeConstants::TYPE_70 => 0.065 * 23000,
    OntimeTypeConstants::TYPE_90 => 0.075 * 23000,
    OntimeTypeConstants::TYPE_120 => 0.08 * 23000,
    OntimeTypeConstants::TYPE_150 => 0.09 * 23000,
  ];
}
