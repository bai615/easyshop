<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * 地区信息
 *
 * @author baihua <baihua_2011@163.com>
 */
class Areas extends ActiveRecord {

    /**
     * 根据传入的地域ID获取地域名称，获取的名称是根据ID依次获取的
     * @param int 地域ID 匿名参数可以多个id
     * @return array
     */
    public static function name() {
        $result = array();
        $paramArray = func_get_args();
        $areaDB = new Areas();
        $areaData = $areaDB->find()->where("area_id in (" . trim(join(',', $paramArray), ",") . ")")->all();

        foreach ($areaData as $value) {
            $result[$value['area_id']] = $value['area_name'];
        }
        return $result;
    }

}
