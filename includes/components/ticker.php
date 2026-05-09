<!-- Trending Skills Ticker Light -->
<div class="ticker-wrapper">
    <div class="ticker-title"><i class="ri-circle-fill"></i> TRENDING SKILLS</div>
    <div class="ticker-items">
        <div class="ticker-item">Higgsfield <span><i class="ri-line-chart-line"></i> +18350%</span></div>
        <div class="ticker-item">AEO <span><i class="ri-line-chart-line"></i> +2684%</span></div>
        <div class="ticker-item">Base44 <span><i class="ri-line-chart-line"></i> +1690%</span></div>
        <div class="ticker-item">AI UGC <span><i class="ri-line-chart-line"></i> +1138%</span></div>
        <div class="ticker-item">Claude <span><i class="ri-line-chart-line"></i> +438%</span></div>
        <div class="ticker-item negative">Open AI <span><i class="ri-line-chart-down-line"></i> -71%</span></div>
    </div>
</div>

<style>
    .ticker-wrapper {
        position: fixed; bottom: 0; width: 100%; background: rgba(255, 255, 255, 0.95);
        border-top: 1px solid var(--border-color); padding: 12px 40px;
        display: flex; align-items: center; gap: 32px; z-index: 50; backdrop-filter: blur(10px);
    }
    .ticker-title { color: var(--brand-green); font-weight: 700; font-size: 0.75rem; letter-spacing: 1px; display: flex; align-items: center; gap: 6px; white-space: nowrap; }
    .ticker-items { display: flex; gap: 20px; overflow: hidden; flex: 1; }
    .ticker-item { background: #f0f4f0; padding: 6px 16px; border-radius: 20px; font-size: 0.85rem; color: var(--text-dark); display: flex; align-items: center; gap: 10px; font-weight: 600; white-space: nowrap; }
    .ticker-item span { color: var(--brand-green); font-weight: 700; font-size: 0.8rem; }
    .ticker-item.negative span { color: #ef4444; }
</style>
