<?php
/**
 * Created by liuFangShuo.
 * User: lfs
 * Date: 2018/2/22
 * Time: 17:52
 */

?>

<!-- Main content -->
<section class="content">
    <div class="error-page">
        <div class="error-content">
            <h2 class="headline text-yellow"><i class="fa fa-warning text-yellow"></i><?=$data['title']?></h2>

            <p>
                <?=$data['content']?>
                <a href="<?=$data['url']?>"><?=$data['button']?></a>
            </p>

        </div>
        <!-- /.error-content -->
    </div>
    <!-- /.error-page -->
</section>
<!-- /.content -->
