<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace weibo\widget;

use yii\base\Widget;

/**
 * Description of LeftWidget
 *
 * @author baihua <baihua_2011@163.com>
 */
class LeftWidget extends Widget {

    public function run() {
        return $this->render('left');
    }

}
