<div class="container block_box">
    <div class="message_box">
        <p>
            <img alt="404" src="<?php echo $this->data['images_url'];?>/404.png">
        </p>
        <p>
            <span class="message_text">Page Not Found :(</span>
        </p>
        <p>
            您现在可以去：
            <?php if ($this->user['user_id']): ?>
                <a class="blue" href="{url:/ucenter/index}">个人中心 >></a>
            <?php endif; ?>

            <a class="blue" href="/">网站首页 >></a>
        </p>
    </div>
</div>
