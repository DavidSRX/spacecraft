<div class="gauge">
    <svg viewBox="0 0 36 36" class="circular-chart">
        <path class="circle-bg"
            d="M18 2.0845
               a 15.9155 15.9155 0 0 1 0 31.831
               a 15.9155 15.9155 0 0 1 0 -31.831"/>
        <path class="circle"
            stroke-dasharray="<?= $percent ?>, 100"
            d="M18 2.0845
               a 15.9155 15.9155 0 0 1 0 31.831
               a 15.9155 15.9155 0 0 1 0 -31.831"/>
        <text x="18" y="20.35" class="gauge-value">
            <?= $value ?>
        </text>
    </svg>
    <div class="gauge-label"><?= $label ?> (<?= $unit ?>)</div>
</div>

<path class="circle progress-circle"
      data-percent="<?= $percent ?>"
      stroke-dasharray="0, 100"
      d="M18 2.0845
         a 15.9155 15.9155 0 0 1 0 31.831
         a 15.9155 15.9155 0 0 1 0 -31.831"/>

<script>
document.addEventListener("DOMContentLoaded", () => {
    document.querySelectorAll(".progress-circle").forEach(circle => {
        let target = circle.dataset.percent;
        let current = 0;

        let anim = setInterval(() => {
            current += 2;
            if (current >= target) {
                current = target;
                clearInterval(anim);
            }
            circle.setAttribute("stroke-dasharray", current + ", 100");
        }, 20);
    });
});
</script>
