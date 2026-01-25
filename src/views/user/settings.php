<?php include 'views/layout/header.php'; ?>
<div class="container" style="max-width: 600px;">
    <h2>⚙️ Paramètres</h2>
    <div style="margin-top: 30px; border-bottom: 1px solid var(--border-color); padding-bottom: 20px;">
        <h3>Apparence</h3>
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div><strong>Mode Sombre</strong></div>
            <label class="switch">
                <input type="checkbox" id="darkModeToggle">
                <span class="slider"></span>
            </label>
        </div>
    </div>
</div>
<script>
    const toggle = document.getElementById('darkModeToggle');
    if (localStorage.getItem('theme') === 'dark') toggle.checked = true;
    toggle.addEventListener('change', function(e) {
        if (e.target.checked) {
            document.documentElement.setAttribute('data-theme', 'dark');
            localStorage.setItem('theme', 'dark');
        } else {
            document.documentElement.setAttribute('data-theme', 'light');
            localStorage.setItem('theme', 'light');
        }
    });
</script>

<?php include 'views/layout/footer.php'; ?>