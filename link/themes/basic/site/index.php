
<style type="text/css">
    @media screen and (max-width: 800px) {
        .link-body {
            text-align: center;
        }
    }
</style>
<?php
$themeUrl = Yii::$app->request->getHostInfo() . $this->theme->baseUrl;

foreach (YII::$app->link->getCategoryListTop() as $key => $firstCat) :
    ?>
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><?php echo $firstCat['name']; ?></h3>
        </div>
        <div class="panel-body">
            <?php
            foreach (YII::$app->link->getCategoryByParentid($firstCat['id']) as $key => $secondCat) :
                ?>
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?php echo $secondCat['name']; ?></h3>
                    </div>
                    <div class="panel-body link-body">
                        <?php
                        foreach (YII::$app->link->getLinkByCategoryId($secondCat['id']) as $key => $linkInfo) :
                            ?>
                            <a title="<?= $linkInfo['name']; ?>" style="display: inline-block;width: 150px;text-align: left;margin: 2px 0;overflow: hidden;" href="<?= $linkInfo['url']; ?>" target="_blank" class="btn btn-default">
                                <img style="border:1px solid #ccc;border-radius: 20px;height: 40px;width: 40px;" data-original="<?= $linkInfo['ico_path']; ?>" src="<?= $themeUrl ?>/images/loading.gif"/>
                                <?= $linkInfo['name']; ?>
                            </a>
                            <?php
                        endforeach;
                        ?>
                    </div>
                </div>
                <?php
            endforeach;
            ?>
        </div>
    </div>
    <?php
endforeach;
?>