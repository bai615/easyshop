<?php

use yii\helpers\Url;

$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;
?>
<div class="container">
    <?php
    foreach ($categoryList as $key => $firstCat) :
        ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><a href="<?php echo Url::to(['/site/list', 'id' => $firstCat['id']]); ?>"><?php echo $firstCat['name']; ?></a></h3>
            </div>
            <div class="panel-body">
                <?php
                foreach ($firstCat['childs'] as $key => $secondCat) :
                    if (empty($secondCat['childs'])):
                        ?>
                        <a href="<?php echo Url::to(['/site/list', 'id' => $secondCat['id']]); ?>" class="btn btn-default" style="margin-bottom:10px;"><?php echo $secondCat['name']; ?></a>
                        <?php
                    else:
                        ?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title"><a href=""><?php echo $secondCat['name']; ?></a></h3>
                            </div>
                            <div class="panel-body">
                                <?php
                                foreach ($secondCat['childs'] as $catInfo) :
                                    ?>
                                    <a href="<?php echo Url::to(['/site/list', 'id' => $catInfo['id']]); ?>" class="btn btn-default"><?php echo $catInfo['name']; ?></a>
                                    <?php
                                endforeach;
                                ?>
                            </div>
                        </div>
                    <?php
                    endif;
                endforeach;
                ?>
            </div>
        </div>
        <?php
    endforeach;
    ?>
</div>
<script src="<?php echo $themeUrl; ?>/js/jquery.min.js" type="text/javascript"></script>