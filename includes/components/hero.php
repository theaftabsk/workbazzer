<!-- Hero Section -->
<div class="hero-bg">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="hero-toggle">
            <a href="#" class="active" onclick="toggleHero(event, 'hire')">Hire</a>
            <a href="#" onclick="toggleHero(event, 'work')">Work</a>
        </div>
        <h1 id="hero-headline">Grow at the speed <br>of your ambition</h1>
        <p id="hero-subtext">Hire experts who use AI to amplify their skills and impact — turning complex work into results</p>
        <form action="talent-marketplace" method="GET" class="hero-search">
            <input type="text" name="q" placeholder="I need a website built">
            <button type="submit">Find talent</button>
        </form>
    </div>
</div>

<script>
function toggleHero(e, mode) {
    e.preventDefault();
    document.querySelectorAll('.hero-toggle a').forEach(a => a.classList.remove('active'));
    e.currentTarget.classList.add('active');
    
    const h1 = document.getElementById('hero-headline');
    const p = document.getElementById('hero-subtext');
    
    if(mode === 'hire') {
        h1.innerHTML = 'Grow at the speed <br>of your ambition';
        p.innerText = 'Hire experts who use AI to amplify their skills and impact — turning complex work into results';
    } else {
        h1.innerHTML = 'Find opportunities <br>to grow your career';
        p.innerText = 'Work with world-class clients and grow your skills with the power of AI';
    }
}
</script>

<style>
    /* Hero Styles - Light Mode */
    .hero-bg {
        position: relative;
        background-image: url('https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80');
        background-size: cover; background-position: center;
        min-height: 85vh; display: flex; align-items: center; padding-bottom: 60px;
    }
    .hero-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(90deg, rgba(255,255,255,0.95) 0%, rgba(255,255,255,0.85) 40%, rgba(255,255,255,0.2) 100%);
    }
    .hero-content { position: relative; z-index: 10; padding: 0 60px; max-width: 1300px; width: 100%; margin: 0 auto; }
</style>
