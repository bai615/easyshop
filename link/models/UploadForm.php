<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace link\models;

use yii\base\Model;
use yii\web\UploadedFile;

/**
 * Description of UploadForm
 *
 * @author baihua <baihua_2011@163.com>
 */
class UploadForm extends Model {

    /**
     * @var UploadedFile file attribute
     */
    public $file;

    public function attributeLabels() {
        return [
            'file' => '图标：',
        ];
    }

    /**
     * @return array the validation rules.
     */
    public function rules() {
        return [
            [['file'], 'file'],
        ];
    }

}
