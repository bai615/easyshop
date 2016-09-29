<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\logics;

use common\models\Model;
use common\models\Attribute;

/**
 * Description of ModelLogic
 *
 * @author baihua <baihua_2011@163.com>
 */
class ModelLogic {

    /**
     * 商品模型添加/修改
     * @param type $modelId     模型编号
     * @param type $modelName   模型名字
     * @param type $attribute   表字段 数组格式,如Array ([name] 	=> Array ( [0] => '' )
     * 													[show_type] => Array ( [0] => '' )
     * 													[value] 	=> Array ( [0] => '' )
     * 													[is_seach] 	=> Array ( [0] => 1 ))
     * @return boolean  bool:true成功；false失败
     */
    public function updateModel($modelId, $modelName, $attribute) {
        if (empty($modelName)) {
            return false;
        }
        //初始化model商品模型表类对象
        $modelModel = new Model();

        if ($modelId) {
            //更新商品模型数据
            $modelModel->updateAll(['name' => $modelName], 'id = :modelId', [':modelId' => $modelId]);
        } else {
            //添加新商品模型
            $modelModel->name = $modelName;
            $modelModel->save();
            $modelId = $modelModel->id;
        }
        if ($modelId) {
            //新增、更新模型扩展属性
            if ($attribute) {
                $this->updateAttribute($attribute, $modelId);
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * 商品属性添加/修改
     * @param array $attribute 表字段 数组格式,如Array ([name] 		=> Array ( [0] => '' )
     * 													[show_type] => Array ( [0] => '' )
     * 													[value] 	=> Array ( [0] => '' )
     * 													[is_seach] 	=> Array ( [0] => 1 ))
     * @param int $modelId  模型编号
     */
    public function updateAttribute($attribute, $modelId) {
        //初始化attribute商品模型属性表类对象
        $attributeModel = new Attribute();
        $len = count($attribute['name']);
        $ids = "";
        for ($i = 0; $i < $len; $i++) {
            if (isset($attribute['name'][$i]) && isset($attribute['value'][$i])) {
                $options = str_replace('，', ',', $attribute['value'][$i]);

                //设置商品模型扩展属性 字段赋值
                $filedData = array();
                $filedData = array(
                    "model_id" => intval($modelId),
                    "type" => ($attribute['show_type'][$i]),
                    "name" => $attribute['name'][$i],
                    "value" => rtrim($options, ','),
                );

                $attributeId = intval($attribute['id'][$i]);
                if ($attributeId) {
                    //更新商品模型扩展属性
                    $attributeModel->updateAll($filedData, 'id = :attributeId', [':attributeId' => $attributeId]);
                } else {
                    //新增商品模型扩展属性
                    $attributeModel = new Attribute();
                    foreach ($filedData as $key => $value) {
                        $attributeModel->$key = $value;
                    }
                    $attributeModel->save();
                    $attributeId = $attributeModel->id;
                }
                $ids .= $attributeId . ',';
            }
        }

        if ($ids) {
            $ids = trim($ids, ',');

            //删除商品模型扩展属性
            $where = "model_id = $modelId  and id not in (" . $ids . ") ";
            $attributeModel->deleteAll($where);
        }
    }

    /**
     * 将$attribute的POST数组转换成正常数组
     * @param array $attribute 表字段 数组格式,如Array ([name] 		=> Array ( [0] => '' )
     * 													[show_type] => Array ( [0] => '' )
     * 													[value] 	=> Array ( [0] => '' )
     * @return array
     */
    public function postArrayChange($attribute) {
        $len = count($attribute['name']);
        $model_attr = array();
        for ($i = 0; $i < $len; $i++) {
            $model_attr[$i]['id'] = $attribute['id'][$i];
            $model_attr[$i]['name'] = $attribute['name'][$i];
            $model_attr[$i]['type'] = $attribute['show_type'][$i];
            $model_attr[$i]['value'] = $attribute['value'][$i];
        }
        return array('model_attr' => $model_attr);
    }
    
    /**
	 * 根据模型编号  获取模型详细信息
	 *
	 * @param int $modelId 模型编号
	 *
	 * @return array 数组格式 	Array ( [id] => '',[name] => '', [model_attr] => Array ( ),[model_spec] => Array ( ))
	 */
    public function getModelInfo($modelId)
    {
    	$modelId = intval($modelId);
    	//初始化model商品模型表类对象
		$modelObj = new Model();
		//根据模型编号  获取商品模型详细信息
		$modelInfo = $modelObj->find()->where('id=:modelId', [':modelId' => $modelId])->one();
		if($modelInfo)
		{
			//初始化attribute商品模型属性表类对象
			$attributeObj = new Attribute();
			//根据商品模型编号 获取商品模型扩展属性
			$modelAttr = $attributeObj->find()->where('model_id=:modelId', [':modelId' => $modelId])->all();
			$modelInfo['model_attr'] = $modelAttr;
		}
		return $modelInfo;
    }
    
    /**
     * 删除模型
     * @param type $modelId
     */
    public function delModel($modelId){
        $attributeObj = new Attribute();
        $attributeObj->deleteAll('model_id=:modelId', [':modelId' => $modelId]);
        $modelObj = new Model();
        $modelObj->deleteAll('id=:modelId', [':modelId' => $modelId]);
    }

}
