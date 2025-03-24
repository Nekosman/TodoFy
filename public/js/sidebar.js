document.addEventListener('DOMContentLoaded', function () {
    const isOpen = ref(true);

    function toggleSidebar() {
        isOpen.value = !isOpen.value;
        updateSidebar();
    }

    function updateSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const logo = document.querySelector('.logo');
        const textElements = document.querySelectorAll('.sidebar-text');
        const arrowIcon = document.querySelector('.arrow-icon');
        const navlink = document.querySelector('.navlink');

        if (isOpen.value) {
            sidebar.classList.remove('w-25');
            sidebar.classList.add('w-60');
            logo.classList.remove('rotate-[360deg]');
            logo.classList.add('ml-5');
            navlink.classList.add('ml-5');
            arrowIcon.classList.remove('fa-flip-horizontal');
            textElements.forEach(el => el.classList.remove('scale-0'));
        } else {
            sidebar.classList.remove('w-60');
            sidebar.classList.add('w-25');
            logo.classList.remove('ml-5');
            navlink.classList.remove('ml-5');
            logo.classList.add('rotate-[360deg]');
            arrowIcon.classList.add('fa-flip-horizontal');
            textElements.forEach(el => el.classList.add('scale-0'));
        }
    }

    function ref(initialValue) {
        return { value: initialValue };
    }

    document.querySelector('.arrow-icon').addEventListener('click', toggleSidebar);
});