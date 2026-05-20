document.addEventListener("DOMContentLoaded", () => {
    const loadingScreen = document.getElementById("loading-screen");
    const enterBtn = document.getElementById("enter-btn");
    const bgMusic = document.getElementById("bg-music");
    const musicBtn = document.getElementById("music-btn-toggle");
    
    // Matikan screen loading
    if(enterBtn) {
        enterBtn.addEventListener("click", () => {
            loadingScreen.style.opacity = "0";
            setTimeout(() => {
                loadingScreen.style.display = "none";
            }, 500);
            
            if(bgMusic) {
                bgMusic.play().catch(() => console.log("Autoplay musik diblokir sistem browser."));
            }
        });
    }

    // Toggle Music Play/Pause
    if(musicBtn && bgMusic) {
        musicBtn.addEventListener("click", () => {
            if(bgMusic.paused) {
                bgMusic.play();
                musicBtn.innerHTML = '<i class="fas fa-volume-up"></i>';
            } else {
                bgMusic.pause();
                musicBtn.innerHTML = '<i class="fas fa-volume-mute"></i>';
            }
        });
    }

    // Filter Kategori
    const tabBtns = document.querySelectorAll(".tab-btn");
    const productCards = document.querySelectorAll(".product-card");

    tabBtns.forEach(btn => {
        btn.addEventListener("click", () => {
            tabBtns.forEach(b => b.classList.remove("active"));
            btn.classList.add("active");
            const filterValue = btn.getAttribute("data-filter");

            productCards.forEach(card => {
                if(filterValue === "all" || card.getAttribute("data-category") === filterValue) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        });
    });

    // Fitur Live Search
    const searchInput = document.getElementById("search-input");
    if(searchInput) {
        searchInput.addEventListener("input", (e) => {
            const term = e.target.value.toLowerCase();
            productCards.forEach(card => {
                const title = card.querySelector("h3").innerText.toLowerCase();
                if(title.includes(term)) {
                    card.style.display = "block";
                } else {
                    card.style.display = "none";
                }
            });
        });
    }

    // FAQ Accordion
    const faqTriggers = document.querySelectorAll(".faq-trigger");
    faqTriggers.forEach(trigger => {
        trigger.addEventListener("click", () => {
            const parent = trigger.parentElement;
            const content = trigger.nextElementSibling;
            
            if(parent.classList.contains("active")) {
                parent.classList.remove("active");
                content.style.maxHeight = "0";
            } else {
                document.querySelectorAll(".faq-box").forEach(b => {
                    b.classList.remove("active");
                    b.querySelector(".faq-content").style.maxHeight = "0";
                });
                parent.classList.add("active");
                content.style.maxHeight = content.scrollHeight + "px";
            }
        });
    });
});
