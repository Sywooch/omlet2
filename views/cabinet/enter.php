<?php
$this->title = 'Omlet Вхід';

?>
<br>
<!-- AUTH FORM-->
<div class="row">
    <?php
    if (\Yii::$app->session->hasFlash('login_error')) { ?>
        <div class="alert alert-warning" role="alert"><?=\Yii::$app->session->getFlash('login_error')?></div>
    <?php } ?>
</div>
    <div class="container">
        <div class="row">
            <div role="tabpanel" class="col-md-4 col-xs-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation"<?=!Yii::$app->session['registration']?' class="active"':'' ?>><a href="#login" aria-controls="home" role="tab" data-toggle="tab">Вхід</a></li>
                    <li role="presentation"<?=!Yii::$app->session['registration']?'':' class="active"' ?>><a href="#reg" aria-controls="profile" role="tab" data-toggle="tab">Реєстрація</a></li>
                </ul>
                <br>
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane fade<?=!Yii::$app->session['registration']?' in active':'' ?>" id="login">
                        <form class="form-horizontal" role="form" method="post" action="/cabinet/login">
                            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                            <div class="form-group">
                                <label for="inputEmail3" class="col-sm-3 control-label">Імейл</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" id="inputEmail3" placeholder="Імейл" maxlength="50" name="User[email]">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword3" class="col-sm-3 control-label">Пароль</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="inputPassword3" placeholder="Пароль" maxlength="100" name="User[password]">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">Увійти</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div role="tabpanel" class="tab-pane fade<?=!Yii::$app->session['registration']?'':' in active' ?>" id="reg">
                        <form class="form-horizontal" role="form" method="post" action="/cabinet/registration">
                            <input type="hidden" name="_csrf" value="<?=Yii::$app->request->getCsrfToken()?>" />
                            <div class="form-group">
                                <label for="inputEmail2" class="col-sm-3 control-label">Імейл</label>
                                <div class="col-sm-9">
                                    <input type="email" class="form-control" id="inputEmail2" placeholder="Імейл" maxlength="50" name="User[email]">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword2" class="col-sm-3 control-label">Пароль</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="inputPassword2" placeholder="Пароль" maxlength="100" name="User[password]">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="inputPassword1" class="col-sm-3 control-label">Ще раз</label>
                                <div class="col-sm-9">
                                    <input type="password" class="form-control" id="inputPassword1" placeholder="Пароль" maxlength="100" name="User[password2]">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="username" class="col-sm-3 control-label">Ім`я</label>
                                <div class="col-sm-9">
                                    <input type="text" class="form-control" id="username" placeholder="Ім`я"  maxlength="50" name="User[username]">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <button type="submit" class="btn btn-default">Зареєструватись</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>