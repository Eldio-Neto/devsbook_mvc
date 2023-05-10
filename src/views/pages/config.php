<?= $render('header', ['loggedUser' => $loggedUser]); ?>
<section class="container main">
    <?= $render('sidebar', ['activeMenu' => 'search']); ?>
    <section class="feed mt-10">
        <?php if (!empty($flash)) : ?>
            <p><?= $flash ?></p>
        <?php endif; ?>
        <h1>Configurações</h1>
        <div class="row">
            <form action="<?= $base ?>/config" enctype="multipart/form-data" method="post" class="config-form">
                <label for="">
                    Novo Avatar: <br>
                    <div class="flex-label-config">
                        <div>
                            <img class="mini" src="<?= $base ?>/media/avatars/<?= $user->avatar ?>" alt="">
                        </div>

                        <div>
                            <input type="file" name="avatar">
                        </div>
                    </div>
                </label>
                <label for="">
                    Nova Capa:<br>
                    <div class="flex-label-config">
                        <div>
                            <img class="mini" src="<?= $base ?>/media/covers/<?= $user->cover ?>" alt="">
                        </div>

                        <div>
                            <input type="file" name="cover">
                        </div>
                    </div>
                </label>
                <hr>
                <label for="">
                    Nome Completo:<br>
                    <input type="text" name="name" value="<?= $loggedUser->name ?>">
                </label>
                <label for="">
                    E-mail:<br>
                    <input type="email" name="email" value="<?= $user->email ?>">
                </label>
                <label for="">
                    Data de nascimento:<br>
                    <input type="text" name="birthdate" id="birthdate" value="<?= date('d/m/Y', strtotime($loggedUser->birthdate)) ?>">
                </label>
                <label for="">
                    Cidade:<br>
                    <input type="text" name="city" value="<?= $user->city ?>">
                </label>
                <label for="">
                    Trabalho:<br>
                    <input type="text" name="work" value="<?= $user->work ?>">
                </label>
                <hr>
                <label for="">
                    Nova Senha:<br>
                    <input type="password" name="password">
                </label>

                <label for="">
                    Confirmar Senha:<br>
                    <input type="password" name="password_confirmation">
                </label>

                <button class="button">Salvar</button>
            </form>
        </div>
    </section>
</section>
<script src="https://unpkg.com/imask"></script>
<script>
    IMask(
        document.getElementById('birthdate'), {
            mask: '00/00/0000'
        }
    );
</script>
<?= $render('footer') ?>