<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Cadastro - Devsbook</title>
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

        <form method="POST" action="<?= $base ?>/cadastro">
            <div style="width: 100%; text-align:center; margin-bottom:15px;">
                <h3>Cadastro</h3>
            </div>
            <?php if (!empty($flash)) : ?>
                <p style="color: red;"><?= $flash; ?></p>
            <?php endif; ?>
            <input placeholder="Digite seu Nome Completo" class="input" type="text" name="name" />

            <input placeholder="Digite seu e-mail" class="input" type="email" name="email" />

            <input placeholder="Digite sua senha" class="input" type="password" name="password" />

            <input id="birthdate" placeholder="Digite sua Data de Nascimento" class="input" type="text" name="birthdate" />

            <input class="button" type="submit" value="Fazer cadastro" />

            <a href="<?= $base ?>/login">Já tem conta? Faça o login</a>
        </form>
    </section>
</body>
<script src="https://unpkg.com/imask"></script>
<script>
    IMask(
        document.getElementById('birthdate'), {
            mask: '00/00/0000'
        }
    );
</script>

</html>