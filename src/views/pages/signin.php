<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Login - Devsbook</title>
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1" />
    <link rel="stylesheet" href="<?= $base ?>/assets/css/login.css" />
</head>

<body>
    <header>
        <div class="container">
            <a href="<?= $base ?>/"><img src="<?= $base ?>/assets/images/devsbook_logo.png" /></a>
        </div>
    </header>
    <section class="container main">

        <form method="POST" action="<?= $base ?>/login" id="form-login">
        <div style="width: 100%; text-align:center; margin-bottom:15px;">
            <h3>Login</h3>
        </div>

            <?php if (!empty($flash)) : ?>
                <p style="color: red;"><?= $flash; ?></p>
            <?php endif; ?>
            <input placeholder="Digite seu e-mail" class="input" type="email" name="email" />

            <input placeholder="Digite sua senha" class="input" type="password" name="password" />

            <input class="button" type="submit" value="Acessar o sistema" />

            <a href="<?= $base ?>/cadastro">Ainda não tem conta? Cadastre-se</a>
        </form>
    </section>
</body>

</html>