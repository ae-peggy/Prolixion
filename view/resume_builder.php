<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resume Builder</title>
    <link href="/portfolio_b/assets/css/resume_builder.css?v=<?php echo time(); ?>" rel="stylesheet">
    <!-- Flatpickr CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
    
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
</head>
<body>
    <video autoplay muted loop id="myVideo">
        <source src="../assets/images/BG3.mp4" type="video/mp4">
    </video>

    <div class="hamburger" onclick="toggleMenu()">☰</div>
    <nav class="side-menu" id="sideMenu">
        <div class="menu-header">
            <div class="hamburger" onclick="toggleMenu()">☰</div>
            <h1>Portfolio Builder</h1>
        </div>
        <ul class="menu-items">
            <li><a href="../view/dashboard.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="../view/profile.php"><i class="fas fa-user"></i> Profile</a></li>
            <li><a href="../view/feedback.php"><i class="fas fa-comments"></i> Feedback</a></li>
            <li><a href="#" onclick="event.preventDefault(); alert('Please use the Preview Portfolio button in the dashboard to preview your portfolio after it has been created 😊.');"><i class="fas fa-eye"></i> Portfolio Preview</a></li>
            <li><a href="../view/resume_builder.php"><i class="fas fa-file-alt"></i> Resume Builder</a></li>
            <li><a href="../actions/logout.php" class="logout-link"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </nav>

    <div class="dashboard-container">
        <div class="resume-builder-section">
            <h1>Resume Builder</h1>

            <div class="form-grid">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" id="fname" name="fname">
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" id="lname" name="lname">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="email" name="email">
                </div>
                <div class="form-group">
                    <label>Desired Job Title</label>
                    <input type="text" id="job-title" name="job_title">
                </div>
            </div>

            <!-- Education Section -->
            <div id="education-section" class="section">
                <h2>Education</h2>
                <div id="education-container"></div>
                <button id="add-education" class="add-section-btn">Add Education</button>
            </div>

            <!-- Achievements/Awards Section -->
            <div id="achievement-section" class="section">
                <h2>Achievements/Awards</h2>
                <div id="achievement-container"></div>
                <button id="add-achievement" class="add-section-btn">Add Achievement</button>
            </div>

            <!-- Work Experience Section -->
            <div id="experience-section" class="section">
                <h2>Work Experience</h2>
                <div id="experience-container"></div>
                <button id="add-experience" class="add-section-btn">Add Experience</button>
            </div>

            <!-- Projects and Research Section -->
            <div id="project-section" class="section">
                <h2>Projects and Research</h2>
                <div id="project-container"></div>
                <button id="add-project" class="add-section-btn">Add Project</button>
            </div>

            <!-- Co-curricular Activities Section -->
            <div id="activity-section" class="section">
                <h2>Co-curricular Activities</h2>
                <div id="activity-container"></div>
                <button id="add-activity" class="add-section-btn">Add Activity</button>
            </div>

            <!-- Skills Section -->
            <div id="skills-section" class="section">
                <h2>Skills</h2>
                <div id="skills-container"></div>
                <div class="form-group">
                    <label>Add Skill</label>
                    <input type="text" id="skill-input">
                    <button id="add-skills" class="add-section-btn">Add Skill</button>
                </div>
            </div>

            <div class="form-actions">
                <form action="../actions/resume_handler.php" method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="generate">
                    <input type="text" name="resume_name" id="resume-name" placeholder="Resume Name" required style="margin-right: 10px;">
                    <button type="submit" class="submit-btn">Generate Resume</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Education Modal Template -->
    <template id="education-modal-template">
        <div class="experience-modal">
            <div class="modal-content">
                <h3>Add Education</h3>
                <input type="text" class="institution-name" placeholder="Institution Name">
                <input type="text" class="degree" placeholder="Degree/Qualification">
                <input type="text" class="start-date" placeholder="Start Date">
                <input type="text" class="end-date" placeholder="End Date">
                <textarea class="education-description" placeholder="Additional Details"></textarea>
                <div class="modal-actions">
                    <button class="save-education">Save</button>
                    <button class="cancel-education">Cancel</button>
                </div>
            </div>
        </div>
    </template>

    <!-- Achievements Modal Template -->
    <template id="achievements-modal-template">
        <div class="experience-modal">
            <div class="modal-content">
                <h3>Add Achievement</h3>
                <input type="text" class="achievement-title" placeholder="Achievement Title">
                <input type="text" class="achievement-date" placeholder="Date">
                <textarea class="achievement-description" placeholder="Achievement Details"></textarea>
                <div class="modal-actions">
                    <button class="save-achievement">Save</button>
                    <button class="cancel-achievement">Cancel</button>
                </div>
            </div>
        </div>
    </template>

    <!-- Work Experience Modal Template -->
    <template id="experience-modal-template">
        <div class="experience-modal">
            <div class="modal-content">
                <h3>Add Work Experience</h3>
                <input type="text" class="company-name" placeholder="Company Name">
                <input type="text" class="job-title" placeholder="Job Title">
                <input type="text" class="start-date" placeholder="Start Date">
                <input type="text" class="end-date" placeholder="End Date">
                <textarea class="job-description" placeholder="Job Responsibilities"></textarea>
                <div class="modal-actions">
                    <button class="save-experience">Save</button>
                    <button class="cancel-experience">Cancel</button>
                </div>
            </div>
        </div>
    </template>

    <!-- Projects Modal Template -->
    <template id="projects-modal-template">
        <div class="experience-modal">
            <div class="modal-content">
                <h3>Add Project</h3>
                <input type="text" class="project-name" placeholder="Project Name">
                <input type="text" class="project-role" placeholder="Your Role">
                <input type="text" class="start-date" placeholder="Start Date">
                <input type="text" class="end-date" placeholder="End Date">
                <textarea class="project-description" placeholder="Project Details"></textarea>
                <div class="modal-actions">
                    <button class="save-project">Save</button>
                    <button class="cancel-project">Cancel</button>
                </div>
            </div>
        </div>
    </template>

    <!-- Co-curricular Activities Modal Template -->
    <template id="activities-modal-template">
        <div class="experience-modal">
            <div class="modal-content">
                <h3>Add Co-curricular Activity</h3>
                <input type="text" class="activity-name" placeholder="Activity Name">
                <input type="text" class="role" placeholder="Your Role">
                <input type="text" class="start-date" placeholder="Start Date">
                <input type="text" class="end-date" placeholder="End Date">
                <textarea class="activity-description" placeholder="Activity Details"></textarea>
                <div class="modal-actions">
                    <button class="save-activity">Save</button>
                    <button class="cancel-activity">Cancel</button>
                </div>
            </div>
        </div>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Flatpickr for all date inputs
            function initializeDatePickers() {
                document.querySelectorAll('.start-date, .end-date, .achievement-date').forEach(input => {
                    flatpickr(input, {
                        dateFormat: "Y-m-d",
                        altFormat: "F Y",
                        altInput: true,
                        allowInput: true
                    });
                });
            }

            // Function to save section data
            async function saveSectionData(action, formData) {
                try {
                    const response = await fetch('section_handler.php', {
                        method: 'POST',
                        body: formData
                    });
                    const result = await response.json();
                    if (!result.success) {
                        throw new Error(result.message);
                    }
                    return result;
                } catch (error) {
                    console.error('Error saving data:', error);
                    alert('Already in Resume');
                    throw error;
                }
            }

            // Education Section
            document.getElementById('add-education').addEventListener('click', function() {
                const template = document.getElementById('education-modal-template');
                const modal = template.content.cloneNode(true);
                document.body.appendChild(modal);
                initializeDatePickers();

                const modalElement = document.querySelector('.experience-modal');
                modalElement.querySelector('.save-education').addEventListener('click', async function() {
                    const formData = new FormData();
                    formData.append('action', 'education');
                    formData.append('institution', modalElement.querySelector('.institution-name').value);
                    formData.append('degree', modalElement.querySelector('.degree').value);
                    formData.append('start_date', modalElement.querySelector('.start-date').value);
                    formData.append('end_date', modalElement.querySelector('.end-date').value);
                    formData.append('description', modalElement.querySelector('.education-description').value);

                    try {
                        await saveSectionData('education', formData);
                        const container = document.getElementById('education-container');
                        const entry = document.createElement('div');
                        entry.className = 'entry';
                        entry.innerHTML = `
                            <h4>${modalElement.querySelector('.institution-name').value}</h4>
                            <p>${modalElement.querySelector('.degree').value}</p>
                            <p>${modalElement.querySelector('.start-date').value} - ${modalElement.querySelector('.end-date').value}</p>
                        `;
                        container.appendChild(entry);
                        modalElement.remove();
                    } catch (error) {
                        // Error handling is done in saveSectionData
                    }
                });

                modalElement.querySelector('.cancel-education').addEventListener('click', function() {
                    modalElement.remove();
                });
            });

            // Experience Section
            document.getElementById('add-experience').addEventListener('click', function() {
                const template = document.getElementById('experience-modal-template');
                const modal = template.content.cloneNode(true);
                document.body.appendChild(modal);
                initializeDatePickers();

                const modalElement = document.querySelector('.experience-modal');
                modalElement.querySelector('.save-experience').addEventListener('click', async function() {
                    const formData = new FormData();
                    formData.append('action', 'experience');
                    formData.append('company', modalElement.querySelector('.company-name').value);
                    formData.append('position', modalElement.querySelector('.job-title').value);
                    formData.append('start_date', modalElement.querySelector('.start-date').value);
                    formData.append('end_date', modalElement.querySelector('.end-date').value);
                    formData.append('description', modalElement.querySelector('.job-description').value);

                    try {
                        await saveSectionData('experience', formData);
                        const container = document.getElementById('experience-container');
                        const entry = document.createElement('div');
                        entry.className = 'entry';
                        entry.innerHTML = `
                            <h4>${modalElement.querySelector('.company-name').value}</h4>
                            <p>${modalElement.querySelector('.job-title').value}</p>
                            <p>${modalElement.querySelector('.start-date').value} - ${modalElement.querySelector('.end-date').value}</p>
                        `;
                        container.appendChild(entry);
                        modalElement.remove();
                    } catch (error) {
                        // Error handling is done in saveSectionData
                    }
                });

                modalElement.querySelector('.cancel-experience').addEventListener('click', function() {
                    modalElement.remove();
                });
            });

            // Projects Section
            document.getElementById('add-project').addEventListener('click', function() {
                const template = document.getElementById('projects-modal-template');
                const modal = template.content.cloneNode(true);
                document.body.appendChild(modal);
                initializeDatePickers();

                const modalElement = document.querySelector('.experience-modal');
                modalElement.querySelector('.save-project').addEventListener('click', async function() {
                    const formData = new FormData();
                    formData.append('action', 'project');
                    formData.append('title', modalElement.querySelector('.project-name').value);
                    formData.append('role', modalElement.querySelector('.project-role').value);
                    formData.append('start_date', modalElement.querySelector('.start-date').value);
                    formData.append('end_date', modalElement.querySelector('.end-date').value);
                    formData.append('description', modalElement.querySelector('.project-description').value);

                    try {
                        await saveSectionData('project', formData);
                        const container = document.getElementById('project-container');
                        const entry = document.createElement('div');
                        entry.className = 'entry';
                        entry.innerHTML = `
                            <h4>${modalElement.querySelector('.project-name').value}</h4>
                            <p>${modalElement.querySelector('.project-role').value}</p>
                            <p>${modalElement.querySelector('.start-date').value} - ${modalElement.querySelector('.end-date').value}</p>
                        `;
                        container.appendChild(entry);
                        modalElement.remove();
                    } catch (error) {
                        // Error handling is done in saveSectionData
                    }
                });

                modalElement.querySelector('.cancel-project').addEventListener('click', function() {
                    modalElement.remove();
                });
            });

            // Skills Section
            document.getElementById('add-skills').addEventListener('click', async function() {
                const skillInput = document.getElementById('skill-input');
                const skillName = skillInput.value.trim();
                
                if (skillName) {
                    const formData = new FormData();
                    formData.append('action', 'skill');
                    formData.append('skill_name', skillName);

                    try {
                        await saveSectionData('skill', formData);
                        const container = document.getElementById('skills-container');
                        const skill = document.createElement('span');
                        skill.className = 'skill-tag';
                        skill.textContent = skillName;
                        container.appendChild(skill);
                        skillInput.value = '';
                    } catch (error) {
                        // Error handling is done in saveSectionData
                    }
                }
            });

            // Activities Section
            document.getElementById('add-activity').addEventListener('click', function() {
                const template = document.getElementById('activities-modal-template');
                const modal = template.content.cloneNode(true);
                document.body.appendChild(modal);
                initializeDatePickers();

                const modalElement = document.querySelector('.experience-modal');
                modalElement.querySelector('.save-activity').addEventListener('click', async function() {
                    const formData = new FormData();
                    formData.append('action', 'activity');
                    formData.append('activity_name', modalElement.querySelector('.activity-name').value);
                    formData.append('role', modalElement.querySelector('.role').value);
                    formData.append('start_date', modalElement.querySelector('.start-date').value);
                    formData.append('end_date', modalElement.querySelector('.end-date').value);
                    formData.append('description', modalElement.querySelector('.activity-description').value);

                    try {
                        await saveSectionData('activity', formData);
                        const container = document.getElementById('activity-container');
                        const entry = document.createElement('div');
                        entry.className = 'entry';
                        entry.innerHTML = `
                            <h4>${modalElement.querySelector('.activity-name').value}</h4>
                            <p>${modalElement.querySelector('.role').value}</p>
                            <p>${modalElement.querySelector('.start-date').value} - ${modalElement.querySelector('.end-date').value}</p>
                        `;
                        container.appendChild(entry);
                        modalElement.remove();
                    } catch (error) {
                        // Error handling is done in saveSectionData
                    }
                });

                modalElement.querySelector('.cancel-activity').addEventListener('click', function() {
                    modalElement.remove();
                });
            });

            // Achievements Section
            document.getElementById('add-achievement').addEventListener('click', function() {
                const template = document.getElementById('achievements-modal-template');
                const modal = template.content.cloneNode(true);
                document.body.appendChild(modal);
                initializeDatePickers();

                const modalElement = document.querySelector('.experience-modal');
                modalElement.querySelector('.save-achievement').addEventListener('click', async function() {
                    const formData = new FormData();
                    formData.append('action', 'achievement');
                    formData.append('title', modalElement.querySelector('.achievement-title').value);
                    formData.append('date', modalElement.querySelector('.achievement-date').value);
                    formData.append('description', modalElement.querySelector('.achievement-description').value);

                    try {
                        await saveSectionData('achievement', formData);
                        const container = document.getElementById('achievement-container');
                        const entry = document.createElement('div');
                        entry.className = 'entry';
                        entry.innerHTML = `
                            <h4>${modalElement.querySelector('.achievement-title').value}</h4>
                            <p>${modalElement.querySelector('.achievement-date').value}</p>
                        `;
                        container.appendChild(entry);
                        modalElement.remove();
                    } catch (error) {
                        // Error handling is done in saveSectionData
                    }
                });

                modalElement.querySelector('.cancel-achievement').addEventListener('click', function() {
                    modalElement.remove();
                });
            });
        });

        function toggleMenu() {
            const menu = document.getElementById('sideMenu');
            menu.classList.toggle('active');
        }

        function exportToPDF() {
            const element = document.getElementById('resume');
            const resumeName = document.getElementById('resume-name').value || 'resume';
            const opt = {
                margin: [10, 10, 10, 10],
                filename: `${resumeName}.pdf`,
                image: { type: 'jpeg', quality: 1 },
                html2canvas: { 
                    scale: 3,
                    useCORS: true,
                    logging: false,
                    letterRendering: true,
                    width: element.scrollWidth,
                    height: element.scrollHeight
                },
                jsPDF: { 
                    unit: 'mm', 
                    format: 'a4', 
                    orientation: 'portrait' 
                }
            };

            // Prevent screen from going blank
            const originalOverflow = document.body.style.overflow;
            document.body.style.overflow = 'visible';

            // Ensure full visibility
            element.style.width = '100%';
            element.style.height = 'auto';
            element.style.overflow = 'visible';
            
            // Wait for any images to load
            Promise.all(Array.from(document.images).map(img => {
                if (img.complete) return Promise.resolve(img.naturalHeight !== 0);
                return new Promise(resolve => {
                    img.addEventListener('load', () => resolve(true));
                    img.addEventListener('error', () => resolve(false));
                });
            }))
            .then(() => {
                // Generate PDF
                html2pdf().set(opt).from(element).save()
                    .then(() => {
                        // Reset styles after export
                        element.style.width = '';
                        element.style.height = '';
                        element.style.overflow = '';
                        document.body.style.overflow = originalOverflow;
                    })
                    .catch(error => {
                        console.error('PDF Export Error:', error);
                        alert('An error occurred while exporting the PDF. Please try again.');
                    });
            });
        }
    </script>
</body>
</html>