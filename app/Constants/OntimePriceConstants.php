<?php 

namespace App\Constants;

final class OntimePriceConstants {
    public const TYPE_PRICE = [
        OntimeTypeConstants::TYPE_60 => 0.055,
        OntimeTypeConstants::TYPE_70 => 0.065,
        OntimeTypeConstants::TYPE_90 => 0.075,
        OntimeTypeConstants::TYPE_120 => 0.08,
        OntimeTypeConstants::TYPE_150 => 0.09,
    ];
}
