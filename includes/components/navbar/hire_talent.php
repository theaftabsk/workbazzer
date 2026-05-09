<div class="mega-menu">
    <div class="mega-menu-left">
        <div class="cat-title">CATEGORIES</div>
        <a href="#" class="active" onmouseover="switchCategory(event, 'ai')">AI & Automation <i class="ri-arrow-right-s-line"></i></a>
        <a href="#" onmouseover="switchCategory(event, 'dev')">Development & IT <i class="ri-arrow-right-s-line"></i></a>
        <a href="#" onmouseover="switchCategory(event, 'marketing')">Marketing <i class="ri-arrow-right-s-line"></i></a>
        <a href="#" onmouseover="switchCategory(event, 'design')">Design & Creative <i class="ri-arrow-right-s-line"></i></a>
        <a href="#" onmouseover="switchCategory(event, 'video')">Video & Audio <i class="ri-arrow-right-s-line"></i></a>
        <a href="#" onmouseover="switchCategory(event, 'writing')">Writing & Content <i class="ri-arrow-right-s-line"></i></a>
        <a href="#" onmouseover="switchCategory(event, 'admin')">Admin & Support <i class="ri-arrow-right-s-line"></i></a>
        <a href="#" class="see-all">See all skills <i class="ri-arrow-right-s-line"></i></a>
    </div>
    <div class="mega-menu-right">
        <!-- AI & Automation -->
        <div id="cat-ai" class="category-content">
            <div class="sub-title">AI & AUTOMATION</div>
            <div class="mega-grid">
                <div class="mega-skill"><a href="#">Artificial Intelligence</a><p>Work on cutting-edge AI projects</p></div>
                <div class="mega-skill"><a href="#">AI Generated Video</a><p>Create AI-powered video content</p></div>
                <div class="mega-skill"><a href="#">AI Model Training</a><p>Label, train, fine-tune AI models</p></div>
                <div class="mega-skill"><a href="#">Prompt Engineering</a><p>Craft prompts for better AI outputs</p></div>
                <div class="mega-skill"><a href="#">AI Content Creation</a><p>Write and edit AI-assisted content</p></div>
                <div class="mega-skill"><a href="#">Generative AI</a><p>Build with generative AI tools</p></div>
                <div class="mega-skill"><a href="#">AI Writing</a><p>Write with and about AI</p></div>
                <div class="mega-skill"><a href="#">Automation</a><p>Workflows that cut manual work</p></div>
                <div class="mega-skill"><a href="#">Chatbot</a><p>Deploy conversational bots</p></div>
                <div class="mega-skill"><a href="#">ChatGPT</a><p>Projects using ChatGPT and OpenAI</p></div>
            </div>
        </div>

        <!-- Development & IT -->
        <div id="cat-dev" class="category-content" style="display:none;">
            <div class="sub-title">DEVELOPMENT & IT</div>
            <div class="mega-grid">
                <div class="mega-skill"><a href="#">Full-Stack Developers</a><p>Front and back end development</p></div>
                <div class="mega-skill"><a href="#">WordPress Developers</a><p>Build and maintain WordPress sites</p></div>
                <div class="mega-skill"><a href="#">Web Developers</a><p>Build and maintain websites/apps</p></div>
                <div class="mega-skill"><a href="#">Shopify Developers</a><p>Launch and customize Shopify stores</p></div>
                <div class="mega-skill"><a href="#">Mobile App Developers</a><p>Native and cross-platform apps</p></div>
                <div class="mega-skill"><a href="#">Webflow Developers</a><p>Build in Webflow, no code</p></div>
                <div class="mega-skill"><a href="#">Front-End Developers</a><p>Pixel-perfect interfaces and UX</p></div>
                <div class="mega-skill"><a href="#">Ecommerce Website Developers</a><p>Build stores that convert and scale</p></div>
                <div class="mega-skill"><a href="#">React JS Developers</a><p>Fast, dynamic front ends with React</p></div>
                <div class="mega-skill"><a href="#">Bubble.io Developers</a><p>No-code apps built on Bubble</p></div>
            </div>
        </div>
    </div>
</div>

<script>
function switchCategory(e, catId) {
    // Hide all
    document.querySelectorAll('.category-content').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.mega-menu-left a').forEach(el => el.classList.remove('active'));
    
    // Show selected
    const target = document.getElementById('cat-' + catId);
    if(target) target.style.display = 'block';
    e.currentTarget.classList.add('active');
}
</script>
