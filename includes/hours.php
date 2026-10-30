<?php if ($HOURS): ?>
    <dl class="hours">
        <?php foreach ($HOURS as [$days, $time]): ?>
            <div><dt><?= e($days) ?></dt><dd><?= e($time) ?></dd></div>
        <?php endforeach; ?>
    </dl>
<?php else: ?>
    <p class="hours-soon">Hours coming soon</p>
<?php endif; ?>
