<?php
if ($model->photo)
{
    $thumb = ImageHelper::thumb(
        News::PHOTOS_DIR,
        $model->photo,
        News::PHOTO_BIG_WIDTH,
        null,
        false
    );
}
?>

<?php if (isset($thumb)): ?>
    <?php echo $thumb; ?>
    <br/>
    <br/>
<?php endif ?>

<?php echo $model->content; ?>

<br clear='all' />

<?php if ($model->files): ?>
	<div style="margin-top:30px;margin-bottom:10px;font-weight:bold">
            <?php echo Yii::t('NewsModule.main', 'Файлы для скачивания') ?>:
        </div>

	<?php foreach ($model->files as $file): ?>
		<a href='<?php echo $file->url; ?>' class='link_13'><?php echo $file->title; ?></a> <br/>
	<?php endforeach ?>
<?php endif ?>





