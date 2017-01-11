<br>
<form method="post">
    <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
    <input type="submit" value="delete" name="delete">
</form>
<pre>
    <?= $errorLog ?>
</pre>