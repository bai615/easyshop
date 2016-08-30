<?php

namespace frontend\controllers;

/**
 * Description of ShoppingController
 *
 * @author baihua <baihua_2011@163.com>
 */
class ShoppingController extends BaseController {

    public function actionConfirm() {
        echo \common\utils\CommonTools::randCode(16, 1);
        return;
    }

}
