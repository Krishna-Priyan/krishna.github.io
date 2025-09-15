document.addEventListener('DOMContentLoaded', () => {
    // Mode toggle functionality
    const modeToggle = document.querySelector('.mode-toggle');
    const body = document.body;

    // Check for user's preferred mode
    const currentMode = localStorage.getItem('mode') || 'dark';
    if (currentMode === 'light') {
        body.classList.add('light-mode');
        modeToggle.innerHTML = '<i class="fas fa-sun"></i>';
    } else {
        modeToggle.innerHTML = '<i class="fas fa-moon"></i>';
    }

    modeToggle.addEventListener('click', () => {
        body.classList.toggle('light-mode');
        if (body.classList.contains('light-mode')) {
            modeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            localStorage.setItem('mode', 'light');
        } else {
            modeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            localStorage.setItem('mode', 'dark');
        }
    });

    // Modal functionality for projects and blogs
    const modals = {
        'project1-modal': document.getElementById('project1-modal'),
        'project2-modal': document.getElementById('project2-modal'),
        'project3-modal': document.getElementById('project3-modal'),
        'blog1-modal': document.getElementById('blog1-modal'),
        'blog2-modal': document.getElementById('blog2-modal')
    };

    const triggers = {
        'project1-modal-trigger': modals['project1-modal'],
        'project2-modal-trigger': modals['project2-modal'],
        'project3-modal-trigger': modals['project3-modal'],
        'blog1-modal-trigger': modals['blog1-modal'],
        'blog2-modal-trigger': modals['blog2-modal']
    };

    const closeBtns = document.querySelectorAll('.close-btn');

    // Open Modals
    for (const triggerId in triggers) {
        const trigger = document.getElementById(triggerId);
        if (trigger) {
            trigger.addEventListener('click', () => {
                triggers[triggerId].style.display = 'flex';
            });
        }
    }

    // Close Modals
    closeBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            for (const modalId in modals) {
                modals[modalId].style.display = 'none';
            }
        });
    });

    window.addEventListener('click', (event) => {
        for (const modalId in modals) {
            if (event.target === modals[modalId]) {
                modals[modalId].style.display = 'none';
            }
        }
    });
});