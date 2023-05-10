<div class="row">
    <div class="box flex-1 border-top-flat">
        <div class="box-body">
            <div class="profile-cover" style="background-image: url('<?= $base; ?>/media/covers/<?= $user->cover; ?>');"></div>
            <div class="profile-info m-20 row">
                <div class="profile-info-avatar">
                    <img src="<?= $base; ?>/media/avatars/<?= $user->avatar; ?>" />
                </div>
                <div class="profile-info-name">
                    <div class="profile-info-name-text">
                        <a href="<?= $base; ?>/perfil/<?= $user->id?>"><?= $user->name; ?></a>
                    </div>
                    <?php if (!empty($user->city)) : ?>
                        <div class="profile-info-location"><?= $user->city; ?></div>
                    <?php endif ?>
                </div>
                <div class="profile-info-data row">
                    <?php if ($user->id !== $loggedUser->id) : ?>
                        <div class="profile-info-item m-width-20">

                            <a href="<?= $base ?>/perfil/<?= $user->id ?>/follow" class="button"> <?= $isFollowing ? 'Deixar de Seguir' : 'Seguir' ?></a>

                        </div>
                    <?php endif; ?>
                    <div class="profile-info-item m-width-20">
                        <div class="profile-info-item-n"><?= !empty($user->followers) ? count($user->followers) : '0' ?></div>
                        <div class="profile-info-item-s">Seguidores</div>
                    </div>
                    <div class="profile-info-item m-width-20">
                        <div class="profile-info-item-n"><?= !empty($user->following) ? count($user->following) : '0' ?></div>
                        <div class="profile-info-item-s">Seguindo</div>
                    </div>
                    <div class="profile-info-item m-width-20">
                        <div class="profile-info-item-n"><?= !empty($user->photos) ? count($user->photos) : '0' ?></div>
                        <div class="profile-info-item-s">Fotos</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>