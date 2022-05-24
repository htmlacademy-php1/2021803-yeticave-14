<?php

/**
 * @var array $categories
 * @var array $lot
 * @var array $time
 */

?>
<main>
    <nav class="nav">
        <ul class="nav__list container">
            <?php foreach ($categories as $val) : ?>
                <li class="nav__item">
                    <a href="/all-lots.php?category=<?= $val['symbol_code']; ?>"><?= htmlspecialchars($val['name']); ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
    </nav>
    <section class="lot-item container">
        <h2><?= htmlspecialchars($lot['name']); ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?= htmlspecialchars($lot['img_url']); ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?= htmlspecialchars($lot['cat_name']); ?></span></p>
                <p class="lot-item__description"><?= htmlspecialchars($lot['description']); ?></p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <?php $time = remaining_time(htmlspecialchars($lot['finished_date']), date('Y-m-d H:i:s')); ?>
                    <div class="lot-item__timer timer
                            <?php if ($time[0] < 1) {
                                echo 'timer--finishing';
                            } ?>">
                        <?= str_pad($time[0], 2, "0", STR_PAD_LEFT) ?>:<?= str_pad($time[1], 2, "0", STR_PAD_LEFT) ?>
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost"><?= price_format(htmlspecialchars($lot['max_price'])); ?></span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span><?= price_format(htmlspecialchars($lot['max_price']) + htmlspecialchars($lot['bid_step'])); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>