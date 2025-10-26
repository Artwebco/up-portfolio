document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('up-modal-overlay');
    const modalTitle = modal.querySelector('.up-modal-title');
    const modalLeft = modal.querySelector('.up-modal-left');
    const modalRight = modal.querySelector('.up-modal-right');
    const closeBtn = modal.querySelector('.up-modal-close');
    const modalLink = modal.querySelector('.up-modal-link'); // 👈 new line

    // Function to open modal
    function openModal(btn) {
        const title = btn.dataset.title;
        const role = btn.dataset.role;
        const description = btn.dataset.description;
        const skillsHTML = btn.dataset.skillsHtml;
        const date = btn.dataset.date;
        const screenshots = JSON.parse(btn.dataset.screenshots || '[]');
        const projectLink = btn.dataset.link; // 👈 new line

        modalTitle.textContent = title;

        // Handle live site link visibility
        if (projectLink) {
            modalLink.href = projectLink;
            modalLink.style.display = 'inline-flex';
        } else {
            modalLink.style.display = 'none';
        }

        modalLeft.innerHTML = `
            <p><strong>My Role:</strong> ${role}</p>
            <p><strong>Description:</strong></p>
            <div class="modal-description">${description}</div>
            <p><strong>Skills:</strong></p>
            ${skillsHTML}
            <p><strong>Date of Project:</strong> ${date}</p>
        `;

        modalRight.innerHTML = '';
        screenshots.forEach(url => {
            const img = document.createElement('img');
            img.src = url;
            img.alt = title;
            img.style.width = '100%';
            img.style.marginBottom = '10px';
            modalRight.appendChild(img);
        });

        // ✅ Ensure it's visible before animation
        modal.style.display = 'flex';
        void modal.offsetWidth; // trigger reflow
        modal.classList.add('show');
    }

    // Function to close modal
    function closeModal() {
        modal.classList.remove('show');
        setTimeout(() => {
            modal.style.display = 'none';
        }, 300); // match transition duration
    }

    // Attach event listeners
    document.querySelectorAll('.up-view-project').forEach(btn => {
        btn.addEventListener('click', () => openModal(btn));
    });

    closeBtn.addEventListener('click', closeModal);

    modal.addEventListener('click', e => {
        if (e.target === modal) closeModal();
    });
});
