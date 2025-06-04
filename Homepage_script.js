document.addEventListener('DOMContentLoaded', function() {
    // === Carousel utama ===
    const carousel = document.querySelector('.carousel');
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.nav-dot');
  
    let currentIndex = 0;
    let intervalId;
  
    function startCarousel() {
      intervalId = setInterval(() => {
        currentIndex = (currentIndex + 1) % slides.length;
        updateCarousel();
      }, 5000);
    }
  
    function updateCarousel() {
      carousel.style.transform = `translateX(-${currentIndex * 100}%)`;
      dots.forEach((dot, index) => {
        dot.classList.toggle('active', index === currentIndex);
      });
    }
  
    dots.forEach(dot => {
      dot.addEventListener('click', () => {
        currentIndex = parseInt(dot.getAttribute('data-index'));
        clearInterval(intervalId);
        updateCarousel();
        startCarousel();
      });
    });
  
    startCarousel();
  
    // === Slider Event Cards ===
    const eventContainer = document.querySelector('.event-cards-container');
    const eventSlides = document.querySelectorAll('.event-cards-slide');
    const eventDots = document.querySelectorAll('.event-dot');
  
    let currentEventIndex = 0;
    let eventIntervalId;
  
    function startEventSlider() {
      eventIntervalId = setInterval(() => {
        currentEventIndex = (currentEventIndex + 1) % eventSlides.length;
        updateEventSlider();
      }, 6000);
    }
  
    function updateEventSlider() {
      eventContainer.style.transform = `translateX(-${currentEventIndex * 100}%)`;
      eventDots.forEach((dot, index) => {
        dot.classList.toggle('active', index === currentEventIndex);
      });
    }
  
    eventDots.forEach(dot => {
      dot.addEventListener('click', () => {
        currentEventIndex = parseInt(dot.getAttribute('data-index'));
        clearInterval(eventIntervalId);
        updateEventSlider();
        startEventSlider();
      });
    });
  
    startEventSlider();
  
    // === Sidebar Toggle & Klik di Luar Sidebar ===
    const burgerBtn = document.querySelector('.burger');
    const sidebar = document.getElementById('userSidebar');
    const logoutBtn = document.querySelector('.logout-btn');
  
    function handleClickOutside(event) {
      if (!sidebar.contains(event.target) && !burgerBtn.contains(event.target)) {
        sidebar.classList.add('hidden');
        document.removeEventListener('click', handleClickOutside);
      }
    }
  
    burgerBtn?.addEventListener('click', (e) => {
      e.stopPropagation(); // Hindari klik dianggap klik di luar
      sidebar.classList.toggle('hidden');
  
      if (!sidebar.classList.contains('hidden')) {
        setTimeout(() => {
          document.addEventListener('click', handleClickOutside);
        }, 0);
      } else {
        document.removeEventListener('click', handleClickOutside);
      }
    });
  
    logoutBtn?.addEventListener('click', () => {
      alert("Anda telah logout!");
      // window.location.href = "login.html"; // kalau ada halaman login
    });
  });
  