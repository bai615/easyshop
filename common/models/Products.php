<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * 货品表
 *
 * @author baihua <baihua_2011@163.com>
 */
class Products extends ActiveRecord {

    public $maxSellPrice; //最高售价
    public $minSellPrice; //最低售价
    public $maxMarketPrice; //最高市场价
    public $minMarketPrice; //最低市场价

}
