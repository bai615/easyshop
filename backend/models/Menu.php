<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace backend\models;

/**
 * Description of Menu
 *
 * @author baihua <baihua_2011@163.com>
 */
class Menu {
//http://fontawesome.dashgame.com/
    private static $_menu = [
        '1' => [
            'name' => '后台首页',
            'controller' => 'site',
            'icon' => 'fa-home',
            'url' => '/site/index',
            'children' => []
        ],
        '2' => [
            'name' => '商品管理',
            'controller'=>'goods',
            'icon' => 'fa-shopping-cart',
            'url' => '/goods/index',
            'children' => [
                '1' => [
                    'name' => '商品列表',
                    'action'=>'goods-list',
                    'url' => '/goods/list',
                ],
                '2' => [
                    'name' => '添加商品',
                    'action'=>'goods-create',
                    'url' => '/goods/create',
                ]
            ]
        ],
        '3' => [
            'name' => '商品分类',
            'controller'=>'category',
            'icon' => 'fa-shopping-cart',
            'url' => '/category/index',
            'children' => [
                '1' => [
                    'name' => '分类列表',
                    'action'=>'category-list',
                    'url' => '/category/list',
                ],
                '2' => [
                    'name' => '添加分类',
                    'action'=>'category-create',
                    'url' => '/category/create',
                ]
            ]
        ],
        '4' => [
            'name' => '品牌管理',
            'controller'=>'brand',
            'icon' => 'fa-shopping-cart',
            'url' => '/brand/index',
            'children' => [
                '1' => [
                    'name' => '品牌分类',
                    'action'=>'brand-category',
                    'url' => '/brand/category',
                ],
                '2' => [
                    'name' => '品牌列表',
                    'action'=>'brand-list',
                    'url' => '/brand/list',
                ],
            ]
        ],
        '5' => [
            'name' => '模型管理',
            'controller'=>'model',
            'icon' => 'fa-cube',
            'url' => '/model/index',
            'children' => [
                '1' => [
                    'name' => '模型列表',
                    'action'=>'model-list',
                    'url' => '/model/list',
                ],
                '2' => [
                    'name' => '规格列表',
                    'action'=>'list',
                    'url' => '/goods/create',
                ],
                '3' => [
                    'name' => '规格图库',
                    'action'=>'list',
                    'url' => '/goods/create',
                ]
            ]
        ],
        '6' => [
            'name' => '订单管理',
            'controller'=>'order',
            'icon' => 'fa-shopping-cart',
            'url' => '/order/index',
            'children' => [
                '1' => [
                    'name' => '订单列表',
                    'action'=>'order-list',
                    'url' => '/order/list',
                ],
                '2' => [
                    'name' => '添加订单',
                    'action'=>'order-create',
                    'url' => '/order/create',
                ]
            ]
        ],
        '7' => [
            'name' => '会员管理',
            'controller'=>'users',
            'icon' => 'fa-user',
            'url' => '/users/index',
            'children' => [
                '1' => [
                    'name' => '会员列表',
                    'action'=>'users-list',
                    'url' => '/users/list',
                ],
                '2' => [
                    'name' => '会员组列表',
                    'action'=>'users-group',
                    'url' => '/users/group',
                ]
            ]
        ],
        '8' => [
            'name' => '系统管理',
            'controller'=>'system',
            'icon' => 'fa-cogs',
            'url' => '/system/index',
            'children' => [
                '1' => [
                    'name' => '导航设置',
                    'action'=>'list',
                    'url' => '/goods/list',
                ],
                '2' => [
                    'name' => '首页幻灯',
                    'action'=>'list',
                    'url' => '/goods/create',
                ]
            ]
        ],
    ];

    public static function getMenu() {
        return self::$_menu;
    }

}
