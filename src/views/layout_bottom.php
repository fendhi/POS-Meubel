<?php if (auth_is_logged_in()): ?>
    </div>
  </main>
</div>

<script>
  (function () {
    const toggleBtn = document.getElementById('sidebarToggle');
    const desktopSidebar = document.getElementById('sidebarDesktop');
    const mobileSidebar = document.getElementById('sidebarMobile');
    const mobileOverlay = document.getElementById('sidebarOverlay');
    const closeMobileBtn = document.getElementById('sidebarCloseMobile');

    if (!toggleBtn) return;

    const isDesktop = () => window.matchMedia('(min-width: 768px)').matches;

    function openMobile() {
      mobileSidebar?.classList.remove('-translate-x-full');
      mobileOverlay?.classList.remove('hidden');
      document.body.style.overflow = 'hidden';
    }

    function closeMobile() {
      mobileSidebar?.classList.add('-translate-x-full');
      mobileOverlay?.classList.add('hidden');
      document.body.style.overflow = '';
    }

    toggleBtn.addEventListener('click', () => {
      if (isDesktop()) {
        // collapse/expand desktop sidebar
        desktopSidebar?.classList.toggle('md:hidden');
      } else {
        // toggle mobile drawer
        if (mobileSidebar?.classList.contains('-translate-x-full')) openMobile();
        else closeMobile();
      }
    });

    mobileOverlay?.addEventListener('click', closeMobile);
    closeMobileBtn?.addEventListener('click', closeMobile);
    window.addEventListener('resize', () => {
      if (isDesktop()) closeMobile();
    });
  })();
</script>
<?php else: ?>
  </div>
</div>
<?php endif; ?>
</body>
</html>
