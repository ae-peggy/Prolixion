document.addEventListener('DOMContentLoaded', () => {
    const sections = {
        education: {
            container: 'education-container',
            addButton: 'add-education',
            modalTemplate: 'education-modal-template',
            titleClass: 'institution-name',
            roleClass: 'degree',
            descriptionClass: 'education-description',
            saveClass: 'save-education',
            cancelClass: 'cancel-education'
        },
        experience: {
            container: 'experience-container',
            addButton: 'add-experience',
            modalTemplate: 'experience-modal-template',
            titleClass: 'company-name',
            roleClass: 'job-title',
            descriptionClass: 'job-description',
            saveClass: 'save-experience',
            cancelClass: 'cancel-experience'
        },
        projects: {
            container: 'project-container',
            addButton: 'add-project',
            modalTemplate: 'projects-modal-template',
            titleClass: 'project-name',
            roleClass: 'project-role',
            descriptionClass: 'project-description',
            saveClass: 'save-project',
            cancelClass: 'cancel-project'
        },
        activities: {
            container: 'activity-container',
            addButton: 'add-activity',
            modalTemplate: 'activities-modal-template',
            titleClass: 'activity-name',
            roleClass: 'role',
            descriptionClass: 'activity-description',
            saveClass: 'save-activity',
            cancelClass: 'cancel-activity'
        },
        achievements: {
            container: 'achievement-container',
            addButton: 'add-achievement',
            modalTemplate: 'achievements-modal-template',
            titleClass: 'achievement-title',
            descriptionClass: 'achievement-description',
            saveClass: 'save-achievement',
            cancelClass: 'cancel-achievement'
        }
    };

    // Initialize each section
    Object.entries(sections).forEach(([sectionName, config]) => {
        const container = document.getElementById(config.container);
        const addButton = document.getElementById(config.addButton);
        const modalTemplate = document.getElementById(config.modalTemplate);

        if (!container || !addButton || !modalTemplate) {
            console.warn(`Missing elements for section: ${sectionName}`);
            return;
        }

        // Function to initialize flatpickr with Month-Year format
        function initializeFlatpickr(modal) {
            const dateInputs = modal.querySelectorAll('.start-date, .end-date, .achievement-date');
            dateInputs.forEach(input => {
                if (input) {
                    flatpickr(input, {
                        dateFormat: 'Y-m',
                        plugins: [new monthSelectPlugin({ shorthand: true, dateFormat: "Y-m", altFormat: "F Y" })]
                    });
                }
            });
        }

        // Function to create a confirmation modal
        function createConfirmationModal(onConfirm) {
            const modalHtml = `
                <div class="experience-modal confirmation-modal">
                    <div class="modal-content">
                        <h3>Confirm Deletion</h3>
                        <p>Are you sure you want to delete this entry?</p>
                        <div class="modal-actions">
                            <button class="confirm-delete">Delete</button>
                            <button class="cancel-delete">Cancel</button>
                        </div>
                    </div>
                </div>
            `;

            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = modalHtml.trim();
            const modal = tempDiv.firstChild;
            document.body.appendChild(modal);

            modal.querySelector('.confirm-delete').addEventListener('click', () => {
                onConfirm();
                document.body.removeChild(modal);
            });

            modal.querySelector('.cancel-delete').addEventListener('click', () => {
                document.body.removeChild(modal);
            });
        }

        // Function to create an entry for a section
        function createEntry(data) {
            const entry = document.createElement('div');
            entry.classList.add(`${sectionName}-entry`, 'entry');

            const details = `<strong>${data.role || ''}</strong> ${data.startDate ? `| ${data.startDate} - ${data.endDate}` : ''}`;

            entry.innerHTML = `
                <div class="entry-header">
                    <h3>${data.title}</h3>
                    <div class="entry-actions">
                        <button class="edit-entry" title="Edit"><i class="fas fa-edit"></i></button>
                        <button class="delete-entry" title="Delete"><i class="fas fa-trash" style="color: #ff4d4d;"></i></button>
                    </div>
                </div>
                <p>${details}</p>
                <p>${data.description || ''}</p>
            `;

            // Add delete functionality
            const deleteBtn = entry.querySelector('.delete-entry');
            deleteBtn.addEventListener('click', (e) => {
                e.stopPropagation(); // Prevent event bubbling
                createConfirmationModal(() => {
                    entry.remove(); // Use remove() instead of removeChild()
                });
            });

            // Edit entry functionality
            entry.querySelector('.edit-entry').addEventListener('click', () => {
                entry.classList.add('editing'); // Add editing class
                const modalContent = modalTemplate.content.cloneNode(true);
                const modal = document.createElement('div');
                modal.classList.add('experience-modal');
                modal.appendChild(modalContent);
                document.body.appendChild(modal);

                // Populate existing data
                modal.querySelector(`.${config.titleClass}`).value = data.title;
                if (data.role && modal.querySelector(`.${config.roleClass}`)) {
                    modal.querySelector(`.${config.roleClass}`).value = data.role;
                }
                if (data.startDate) modal.querySelector('.start-date').value = data.startDate;
                if (data.endDate) modal.querySelector('.end-date').value = data.endDate;
                if (data.description) modal.querySelector(`.${config.descriptionClass}`).value = data.description;

                initializeFlatpickr(modal);

                // Save button handler
                modal.querySelector(`.${config.saveClass}`).addEventListener('click', () => {
                    const updatedData = {
                        title: modal.querySelector(`.${config.titleClass}`).value,
                        role: modal.querySelector(`.${config.roleClass}`)?.value || '',
                        startDate: modal.querySelector('.start-date')?.value || '',
                        endDate: modal.querySelector('.end-date')?.value || '',
                        description: modal.querySelector(`.${config.descriptionClass}`)?.value || ''
                    };

                    // Update entry with new data
                    entry.querySelector('h3').textContent = updatedData.title;
                    entry.querySelector('p:nth-child(2)').innerHTML = 
                        `<strong>${updatedData.role}</strong> ${updatedData.startDate ? `| ${updatedData.startDate} - ${updatedData.endDate}` : ''}`;
                    entry.querySelector('p:nth-child(3)').textContent = updatedData.description;

                    entry.classList.remove('editing'); // Remove editing class
                    document.body.removeChild(modal);
                });

                // Cancel button handler
                modal.querySelector(`.${config.cancelClass}`).addEventListener('click', () => {
                    entry.classList.remove('editing'); // Remove editing class
                    document.body.removeChild(modal);
                });
            });

            return entry;
        }

        // Add Section Button Handler
        addButton.addEventListener('click', () => {
            const modalContent = modalTemplate.content.cloneNode(true);
            const modal = document.createElement('div');
            modal.classList.add('experience-modal');
            modal.appendChild(modalContent);
            document.body.appendChild(modal);

            initializeFlatpickr(modal);

            // Save button handler
            modal.querySelector(`.${config.saveClass}`).addEventListener('click', () => {
                const data = {
                    title: modal.querySelector(`.${config.titleClass}`).value,
                    role: modal.querySelector(`.${config.roleClass}`)?.value || '',
                    startDate: modal.querySelector('.start-date')?.value || '',
                    endDate: modal.querySelector('.end-date')?.value || '',
                    description: modal.querySelector(`.${config.descriptionClass}`)?.value || ''
                };

                const entry = createEntry(data);
                container.appendChild(entry);
                document.body.removeChild(modal);
            });

            // Cancel button handler
            modal.querySelector(`.${config.cancelClass}`).addEventListener('click', () => {
                document.body.removeChild(modal);
            });
        });
    });

    // Initialize Skills Section
    const skillsContainer = document.getElementById('skills-container');
    const addSkillButton = document.getElementById('add-skills');
    const skillInput = document.getElementById('skill-input');

    if (skillsContainer && addSkillButton && skillInput) {
        addSkillButton.addEventListener('click', () => {
            const skillText = skillInput.value.trim();
            if (skillText) {
                const skillEntry = document.createElement('div');
                skillEntry.classList.add('skill-entry');
                skillEntry.innerHTML = `
                    <span>${skillText}</span>
                    <button class="delete-skill"><i class="fas fa-times"></i></button>
                `;

                skillEntry.querySelector('.delete-skill').addEventListener('click', () => {
                    skillsContainer.removeChild(skillEntry);
                });

                skillsContainer.appendChild(skillEntry);
                skillInput.value = '';
            }
        });

        // Allow adding skills with Enter key
        skillInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                addSkillButton.click();
            }
        });
    }
});

// Toggle Menu Function (from previous dashboard)
function toggleMenu() {
    var sideMenu = document.getElementById("sideMenu");
    sideMenu.classList.toggle("show");
}