/**
 * Tân Tiến Window theme main JS.
 */
(function () {
	'use strict';

	document.addEventListener('DOMContentLoaded', function () {
		initMobileMenu();
		initHeaderScroll();
		initHeroSlider();
		initCounters();
		initScrollReveal();
		initGalleryLightbox();
		initContactForm();
		initQuotesSlider();
		initProductCategoryFilter();
		initProjectFilter();
	});

	function initQuotesSlider() {
		var slider = document.querySelector('.ttw-quotes');
		var prevBtn = document.querySelector('.ttw-slider-prev');
		var nextBtn = document.querySelector('.ttw-slider-next');

		if (!slider) {
			return;
		}

		var autoSlideTimer = null;
		var slideInterval = 3000; // Tự động nhảy bài sau mỗi 4 giây

		function getScrollAmount() {
			var card = slider.querySelector('.ttw-quote');
			if (card) {
				return card.offsetWidth + 24; // Chiều rộng 1 card + gap
			}
			return 340;
		}

		function nextSlide() {
			var maxScrollLeft = slider.scrollWidth - slider.clientWidth;
			// Nếu đã cuộn tới cuối cùng thì xoay vòng về đầu
			if (Math.ceil(slider.scrollLeft) >= maxScrollLeft - 10) {
				slider.scrollTo({
					left: 0,
					behavior: 'smooth'
				});
			} else {
				slider.scrollBy({
					left: getScrollAmount(),
					behavior: 'smooth'
				});
			}
		}

		function prevSlide() {
			// Nếu đang ở đầu thì nhảy về cuối
			if (slider.scrollLeft <= 10) {
				slider.scrollTo({
					left: slider.scrollWidth,
					behavior: 'smooth'
				});
			} else {
				slider.scrollBy({
					left: -getScrollAmount(),
					behavior: 'smooth'
				});
			}
		}

		function startAutoSlide() {
			stopAutoSlide();
			autoSlideTimer = setInterval(nextSlide, slideInterval);
		}

		function stopAutoSlide() {
			if (autoSlideTimer) {
				clearInterval(autoSlideTimer);
				autoSlideTimer = null;
			}
		}

		if (nextBtn) {
			nextBtn.addEventListener('click', function () {
				nextSlide();
				startAutoSlide();
			});
		}

		if (prevBtn) {
			prevBtn.addEventListener('click', function () {
				prevSlide();
				startAutoSlide();
			});
		}

		// Tạm dừng tự chạy khi người dùng rê chuột vào slide để xem/đọc
		slider.parentElement.addEventListener('mouseenter', stopAutoSlide);
		slider.parentElement.addEventListener('mouseleave', startAutoSlide);
		slider.parentElement.addEventListener('touchstart', stopAutoSlide, { passive: true });
		slider.parentElement.addEventListener('touchend', startAutoSlide, { passive: true });

		// Khởi chạy tự động
		startAutoSlide();
	}



	function initMobileMenu() {
		var toggle = document.getElementById('ttw-menu-toggle');
		var nav = document.getElementById('ttw-mobile-nav');
		var close = document.getElementById('ttw-mobile-nav-close');
		if (!toggle || !nav) {
			return;
		}
		function openNav() {
			nav.classList.add('open');
			toggle.setAttribute('aria-expanded', 'true');
			document.body.style.overflow = 'hidden';
		}
		function closeNav() {
			nav.classList.remove('open');
			toggle.setAttribute('aria-expanded', 'false');
			document.body.style.overflow = '';
		}
		toggle.addEventListener('click', function () {
			if (nav.classList.contains('open')) {
				closeNav();
			} else {
				openNav();
			}
		});
		if (close) {
			close.addEventListener('click', closeNav);
		}
	}

	function initHeaderScroll() {
		var header = document.getElementById('ttw-header');
		if (!header) {
			return;
		}
		function onScroll() {
			if (window.scrollY > 10) {
				header.classList.add('scrolled');
			} else {
				header.classList.remove('scrolled');
			}
		}
		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();
	}

	function initHeroSlider() {
		var slides = document.querySelectorAll('.ttw-hero-slide');
		var dots = document.querySelectorAll('#hero-dots button');
		if (slides.length < 2) {
			return;
		}
		var current = 0;
		var timer = null;

		function goTo(index) {
			slides.forEach(function (s, i) {
				s.classList.toggle('active', i === index);
			});
			dots.forEach(function (d, i) {
				d.classList.toggle('active', i === index);
			});
			current = index;
		}

		function next() {
			goTo((current + 1) % slides.length);
		}

		function restart() {
			if (timer) {
				clearInterval(timer);
			}
			timer = setInterval(next, 6000);
		}

		dots.forEach(function (dot) {
			dot.addEventListener('click', function () {
				goTo(parseInt(dot.getAttribute('data-slide'), 10) || 0);
				restart();
			});
		});

		restart();
	}

	function initCounters() {
		var counters = document.querySelectorAll('.ttw-count');
		if (!counters.length) {
			return;
		}

		function animate(counter) {
			var target = parseInt(counter.getAttribute('data-count'), 10) || 0;
			var duration = 1600;
			var start = null;

			function step(timestamp) {
				if (!start) {
					start = timestamp;
				}
				var progress = Math.min((timestamp - start) / duration, 1);
				var eased = 1 - Math.pow(1 - progress, 3);
				counter.textContent = Math.floor(eased * target).toLocaleString('vi-VN');
				if (progress < 1) {
					window.requestAnimationFrame(step);
				} else {
					counter.textContent = target.toLocaleString('vi-VN');
				}
			}
			window.requestAnimationFrame(step);
		}

		if ('IntersectionObserver' in window) {
			var observer = new IntersectionObserver(function (entries) {
				entries.forEach(function (entry) {
					if (entry.isIntersecting) {
						animate(entry.target);
						observer.unobserve(entry.target);
					}
				});
			}, { threshold: 0.3 });
			counters.forEach(function (c) {
				observer.observe(c);
			});
		} else {
			counters.forEach(animate);
		}
	}

	function initScrollReveal() {
		var items = document.querySelectorAll('.ttw-animate');
		if (!items.length) {
			return;
		}
		if (!('IntersectionObserver' in window)) {
			items.forEach(function (el) {
				el.classList.add('ttw-in-view');
			});
			return;
		}
		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('ttw-in-view');
					observer.unobserve(entry.target);
				}
			});
		}, { threshold: 0.12 });
		items.forEach(function (el) {
			observer.observe(el);
		});
	}

	function initGalleryLightbox() {
		var items = document.querySelectorAll('[data-lightbox]');
		if (!items.length) {
			return;
		}
		items.forEach(function (item) {
			item.addEventListener('click', function (e) {
				e.preventDefault();
				var img = item.getAttribute('href');
				if (!img) {
					return;
				}
				var overlay = document.createElement('div');
				overlay.style.cssText = 'position:fixed;inset:0;background:rgba(20,20,32,.92);z-index:9999;display:flex;align-items:center;justify-content:center;cursor:zoom-out;';
				var image = document.createElement('img');
				image.src = img;
				image.style.cssText = 'max-width:92vw;max-height:92vh;border-radius:8px;';
				overlay.appendChild(image);
				overlay.addEventListener('click', function () {
					overlay.remove();
				});
				document.body.appendChild(overlay);
			});
		});
	}

	function initContactForm() {
		var form = document.getElementById('ttw-contact-form');
		if (!form) {
			return;
		}
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			var name = form.name.value.trim();
			var phone = form.phone.value.trim();
			var subject = (form.subject.value || '').trim() || 'Yêu cầu tư vấn';
			var message = form.message.value.trim();
			var body = 'Họ và tên: ' + name + '\nSố điện thoại: ' + phone + '\n\n' + message;
			var mailto = 'mailto:tantienwindow365@gmail.com?subject=' + encodeURIComponent(subject) + '&body=' + encodeURIComponent(body);
			var note = form.querySelector('.ttw-form-note');
			window.location.href = mailto;
			if (note) {
				note.textContent = 'Cảm ơn ' + name + '! Email đã được mở. Nếu cần hỗ trợ ngay, vui lòng gọi hotline 0907.247.111.';
			}
		});
	}

	function initProductCategoryFilter() {
		var filterNav = document.getElementById('ttw-category-filter');
		var grid = document.getElementById('ttw-product-grid');
		var emptyState = document.getElementById('ttw-filter-empty');
		if (!filterNav || !grid) {
			return;
		}

		var tabs = filterNav.querySelectorAll('.ttw-category-tab');
		var cards = grid.querySelectorAll('.ttw-card-bento');

		function applyFilter(filterVal) {
			var visibleCount = 0;
			cards.forEach(function (card) {
				var categories = (card.getAttribute('data-category') || '').split(' ');
				if (filterVal === 'all' || categories.indexOf(filterVal) !== -1) {
					card.style.display = '';
					card.style.opacity = '0';
					card.style.transform = 'translateY(12px)';
					setTimeout(function () {
						card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
						card.style.opacity = '1';
						card.style.transform = 'translateY(0)';
					}, 10);
					visibleCount++;
				} else {
					card.style.display = 'none';
				}
			});

			if (emptyState) {
				emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
			}
		}

		tabs.forEach(function (tab) {
			tab.addEventListener('click', function () {
				tabs.forEach(function (t) {
					t.classList.remove('active');
				});
				tab.classList.add('active');
				var filterVal = tab.getAttribute('data-filter') || 'all';
				applyFilter(filterVal);
			});
		});
	}

	function initProjectFilter() {
		var filterNav = document.getElementById('ttw-project-filter');
		var grid = document.getElementById('ttw-project-grid');
		var emptyState = document.getElementById('ttw-project-empty');
		var loadMoreBtn = document.getElementById('ttw-btn-loadmore');
		if (!filterNav || !grid) {
			return;
		}

		var tabs = filterNav.querySelectorAll('.ttw-project-filter-tab');
		var cards = grid.querySelectorAll('.ttw-project-card');

		function applyFilter(filterVal) {
			var visibleCount = 0;
			cards.forEach(function (card) {
				var categories = (card.getAttribute('data-category') || '').split(' ');
				if (filterVal === 'all' || categories.indexOf(filterVal) !== -1) {
					card.style.display = '';
					card.style.opacity = '0';
					card.style.transform = 'translateY(12px)';
					setTimeout(function () {
						card.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
						card.style.opacity = '1';
						card.style.transform = 'translateY(0)';
					}, 10);
					visibleCount++;
				} else {
					card.style.display = 'none';
				}
			});

			if (emptyState) {
				emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
			}
		}

		tabs.forEach(function (tab) {
			tab.addEventListener('click', function () {
				tabs.forEach(function (t) {
					t.classList.remove('active');
				});
				tab.classList.add('active');
				var filterVal = tab.getAttribute('data-filter') || 'all';
				applyFilter(filterVal);
			});
		});

		if (loadMoreBtn) {
			loadMoreBtn.addEventListener('click', function () {
				loadMoreBtn.textContent = 'ĐÃ TẢI TẤT CẢ CÔNG TRÌNH';
				loadMoreBtn.disabled = true;
				loadMoreBtn.style.opacity = '0.7';
				loadMoreBtn.style.cursor = 'default';
			});
		}
	}
})();


