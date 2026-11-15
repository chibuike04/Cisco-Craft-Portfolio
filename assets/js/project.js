
/* =========================================================
   CODECRAFT ADMIN DASHBOARD
   PROJECT MANAGEMENT JAVASCRIPT
========================================================= */


/* =========================================================
   1. PROJECT STATE
========================================================= */

let projectTechnologies = [];
let projectIsSubmitting = false;


/* =========================================================
   2. PROJECT ELEMENT REFERENCES
========================================================= */

function getProjectElements() {
    return {
        form: document.getElementById("projectForm"),
        modal: document.getElementById("projectModal"),
        table: document.getElementById("projectsTableBody"),
        search: document.getElementById("projectSearch"),
        categoryFilter: document.getElementById("projectCategoryFilter"),
        statusFilter: document.getElementById("projectStatusFilter")
    };
}


/* =========================================================
   3. API HELPERS
========================================================= */

function getProjectCsrfToken() {
    return document.querySelector(
        '#projectForm input[name="_token"]'
    )?.value || "";
}


function getProjectBaseUrl() {
    return "/Cisco_Craft/admin/projects";
}


async function parseProjectResponse(response) {
    const contentType = response.headers.get("content-type") || "";

    if (!contentType.includes("application/json")) {
        throw new Error(
            "The server returned an unexpected response. Please check your session and backend routes."
        );
    }

    const data = await response.json();

    if (!response.ok || data.success === false) {
        throw new Error(
            data.message || data.error || "The request could not be completed."
        );
    }

    return data;
}


async function projectApiRequest(url, options = {}) {
    const response = await fetch(url, {
        ...options,
        headers: {
            "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            ...(options.headers || {})
        },
        credentials: "same-origin"
    });

    return parseProjectResponse(response);
}


/* =========================================================
   4. SHOW PROJECT MODAL
========================================================= */

function showProjectModal() {
    const { modal } = getProjectElements();

    if (!modal) {
        console.error("Project modal was not found.");
        return;
    }

    modal.style.display = "flex";
    modal.classList.add("active");

    document.body.style.overflow = "hidden";
}


/* =========================================================
   5. OPEN PROJECT MODAL
========================================================= */

function openProjectModal() {
    const { form } = getProjectElements();

    if (!form) {
        console.error("Project form was not found.");
        return;
    }

    form.reset();

    projectTechnologies = [];

    const editingRow = document.getElementById("projectEditingRow");
    const modalTitle = document.getElementById("projectModalTitle");
    const saveButtonText = document.getElementById("projectSaveButtonText");
    const slug = document.getElementById("projectSlug");

    if (editingRow) {
        editingRow.value = "";
    }

    if (modalTitle) {
        modalTitle.textContent = "Add New Project";
    }

    if (saveButtonText) {
        saveButtonText.textContent = "Save Project";
    }

    if (slug) {
        slug.dataset.manuallyEdited = "false";
    }

    renderProjectTechnologies();
    resetProjectImagePreview();

    showProjectModal();
}


/* =========================================================
   6. CLOSE PROJECT MODAL
========================================================= */

function closeProjectModal() {
    const { modal } = getProjectElements();

    if (!modal) return;

    modal.classList.remove("active");
    modal.style.display = "none";

    document.body.style.overflow = "";
}


/* =========================================================
   7. RESET PROJECT IMAGE PREVIEW
========================================================= */

function resetProjectImagePreview(imageUrl = "") {
    const preview = document.getElementById("projectImagePreview");
    const imageInput = document.getElementById("projectImage");

    if (imageInput) {
        imageInput.value = "";
    }

    if (!preview) return;

    preview.innerHTML = "";

    if (imageUrl) {
        const image = document.createElement("img");

        image.src = imageUrl;
        image.alt = "Current project thumbnail";
        image.style.width = "100%";
        image.style.height = "100%";
        image.style.maxHeight = "200px";
        image.style.objectFit = "cover";
        image.style.borderRadius = "10px";

        preview.appendChild(image);
        return;
    }

    preview.innerHTML = `
        <i class="fas fa-image"></i>
        <span>Image preview</span>
    `;
}


/* =========================================================
   8. EDIT PROJECT
========================================================= */

function editProject(button) {
    const row = button.closest("tr");

    if (!row) {
        console.error("Project row was not found.");
        return;
    }

    const data = row.dataset;
    const { form } = getProjectElements();

    if (!form) return;

    form.reset();

    projectTechnologies = [];

    const setValue = (id, value) => {
        const element = document.getElementById(id);

        if (element) {
            element.value = value ?? "";
        }
    };

    const setChecked = (id, value) => {
        const element = document.getElementById(id);

        if (element) {
            element.checked =
                value === "1" ||
                value === "true" ||
                value === "yes";
        }
    };

    // Project ID
    setValue("projectEditingRow", data.id);

    // Basic information
    setValue("projectTitle", data.title);
    setValue("projectSlug", data.slug);
    setValue("projectCategory", data.categoryId);
    setValue("projectClient", data.clientName);
    setValue("projectRole", data.role);
    setValue("projectSummary", data.summary);
    setValue("projectDescription", data.description);

    // Project URLs
    setValue("projectLiveUrl", data.projectUrl);
    setValue("projectRepositoryUrl", data.repositoryUrl);

    // Publishing settings
    setValue("projectStatus", data.status || "draft");
    setValue("projectSortOrder", data.sortOrder || "0");
    setValue("projectPublishedAt", data.publishedAt);

    // SEO information
    setValue("projectMetaTitle", data.metaTitle);
    setValue("projectMetaDescription", data.metaDescription);

    // Featured status
    setChecked("projectIsFeatured", data.isFeatured);

    // Technologies are currently frontend-only.
    renderProjectTechnologies();

    // Show the existing thumbnail.
    resetProjectImagePreview(data.thumbnailUrl || "");

    // Slug generation
    const slug = document.getElementById("projectSlug");

    if (slug) {
        slug.dataset.manuallyEdited = "false";
    }

    // Modal title and button
    const modalTitle = document.getElementById("projectModalTitle");
    const saveButtonText = document.getElementById("projectSaveButtonText");

    if (modalTitle) {
        modalTitle.textContent = "Edit Project";
    }

    if (saveButtonText) {
        saveButtonText.textContent = "Update Project";
    }

    showProjectModal();
}


/* =========================================================
   9. COLLECT PROJECT FORM DATA
========================================================= */

function getProjectFormData() {
    const getValue = id => {
        return document.getElementById(id)?.value.trim() || "";
    };

    return {
        _token: getProjectCsrfToken(),
        title: getValue("projectTitle"),
        category_id: getValue("projectCategory"),
        client_name: getValue("projectClient"),
        role: getValue("projectRole"),
        summary: getValue("projectSummary"),
        description: getValue("projectDescription"),
        project_url: getValue("projectLiveUrl"),
        repository_url: getValue("projectRepositoryUrl"),
        status: getValue("projectStatus") || "draft",
        published_at: getValue("projectPublishedAt"),
        sort_order: getValue("projectSortOrder") || "0",
        is_featured: document.getElementById("projectIsFeatured")?.checked ? 1 : 0,
        meta_title: getValue("projectMetaTitle"),
        meta_description: getValue("projectMetaDescription")
    };
}


/* =========================================================
   10. SUBMIT PROJECT
========================================================= */

async function submitProject(event) {
    event.preventDefault();

    if (projectIsSubmitting) return;

    const { form } = getProjectElements();

    if (!form) return;

    if (!form.reportValidity()) return;

    const projectId = document.getElementById("projectEditingRow")?.value.trim();
    const imageInput = document.getElementById("projectImage");
    const imageFile = imageInput?.files?.[0] || null;

    const submitButton = form.querySelector('button[type="submit"]');
    const saveButtonText = document.getElementById("projectSaveButtonText");

    const originalButtonText = saveButtonText?.textContent || "Save Project";

    projectIsSubmitting = true;

    if (submitButton) {
        submitButton.disabled = true;
    }

    if (saveButtonText) {
        saveButtonText.textContent = projectId
            ? "Updating..."
            : "Saving...";
    }

    try {
        const projectData = getProjectFormData();

        if (!projectData._token) {
            throw new Error("CSRF token is missing. Please refresh the page and try again.");
        }

        let result;

        if (projectId) {
            /*
             * UPDATE PROJECT
             *
             * Send JSON because PHP does not automatically
             * populate $_POST for multipart PUT requests.
             */

            result = await projectApiRequest(
                `${getProjectBaseUrl()}/${encodeURIComponent(projectId)}`,
                {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify(projectData)
                }
            );

            /*
             * UPLOAD REPLACEMENT THUMBNAIL
             *
             * The controller has a separate thumbnail endpoint.
             */

            if (imageFile) {
                if (saveButtonText) {
                    saveButtonText.textContent = "Uploading image...";
                }

                await uploadProjectThumbnail(projectId, imageFile);
            }

        } else {
            /*
             * CREATE PROJECT
             *
             * FormData allows the controller to receive
             * the project fields and optional image together.
             */

            const formData = new FormData();

            Object.entries(projectData).forEach(([key, value]) => {
                formData.append(key, value);
            });

            if (imageFile) {
                formData.append("projectImage", imageFile);
            }

            result = await projectApiRequest(
                getProjectBaseUrl(),
                {
                    method: "POST",
                    body: formData
                }
            );
        }

        showProjectMessage(
            result.message || (
                projectId
                    ? "Project updated successfully."
                    : "Project created successfully."
            )
        );

        closeProjectModal();

        // Reload to display the latest database records.
        window.location.reload();

    } catch (error) {
        console.error("Project submission error:", error);

        showProjectMessage(
            error.message || "Unable to save the project.",
            "error"
        );

    } finally {
        projectIsSubmitting = false;

        if (submitButton) {
            submitButton.disabled = false;
        }

        if (saveButtonText) {
            saveButtonText.textContent = originalButtonText;
        }
    }
}


/* =========================================================
   11. UPLOAD PROJECT THUMBNAIL
========================================================= */

async function uploadProjectThumbnail(projectId, imageFile) {
    const formData = new FormData();

    formData.append("_token", getProjectCsrfToken());
    formData.append("projectImage", imageFile);

    return projectApiRequest(
        `${getProjectBaseUrl()}/${encodeURIComponent(projectId)}/thumbnail`,
        {
            method: "POST",
            body: formData
        }
    );
}


/* =========================================================
   12. ADD PROJECT TECHNOLOGY
========================================================= */

function addProjectTechnology() {
    const input = document.getElementById("projectTechnologyInput");

    if (!input) return;

    const technology = input.value.trim();

    if (!technology) return;

    const alreadyExists = projectTechnologies.some(
        item => item.toLowerCase() === technology.toLowerCase()
    );

    if (alreadyExists) {
        showProjectMessage(
            "This technology has already been added.",
            "error"
        );

        return;
    }

    projectTechnologies.push(technology);

    renderProjectTechnologies();

    input.value = "";
}


/* =========================================================
   13. RENDER PROJECT TECHNOLOGIES
========================================================= */

function renderProjectTechnologies() {
    const container = document.getElementById(
        "projectSelectedTechnologies"
    );

    if (!container) return;

    container.innerHTML = "";

    if (projectTechnologies.length === 0) {
        container.innerHTML = `
            <span class="project-tech-empty">
                No technologies added yet.
            </span>
        `;

        return;
    }

    projectTechnologies.forEach((technology, index) => {
        const tag = document.createElement("span");

        tag.className = "project-tech-tag";

        const label = document.createElement("span");
        label.textContent = technology;

        const removeButton = document.createElement("button");

        removeButton.type = "button";
        removeButton.innerHTML = "&times;";
        removeButton.title = "Remove technology";

        removeButton.addEventListener("click", () => {
            projectTechnologies.splice(index, 1);
            renderProjectTechnologies();
        });

        tag.appendChild(label);
        tag.appendChild(removeButton);

        container.appendChild(tag);
    });
}


/* =========================================================
   14. GET PROJECT TECHNOLOGIES
========================================================= */

function getProjectTechnologies() {
    return [...projectTechnologies];
}


/* =========================================================
   15. GENERATE PROJECT SLUG
========================================================= */

function generateProjectSlug(title) {
    return title
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s-]/g, "")
        .replace(/\s+/g, "-")
        .replace(/-+/g, "-")
        .replace(/^-|-$/g, "");
}


/* =========================================================
   16. INITIALIZE PROJECT SLUG
========================================================= */

function initializeProjectSlug() {
    const title = document.getElementById("projectTitle");
    const slug = document.getElementById("projectSlug");

    if (!title || !slug) return;

    title.addEventListener("input", () => {
        if (slug.dataset.manuallyEdited === "true") {
            return;
        }

        slug.value = generateProjectSlug(title.value);
    });
}


/* =========================================================
   17. PROJECT IMAGE PREVIEW
========================================================= */

function initializeProjectImagePreview() {
    const imageInput = document.getElementById("projectImage");
    const preview = document.getElementById("projectImagePreview");

    if (!imageInput || !preview) return;

    imageInput.addEventListener("change", () => {
        const file = imageInput.files?.[0];

        if (!file) return;

        const allowedTypes = [
            "image/jpeg",
            "image/png",
            "image/webp",
            "image/gif"
        ];

        if (!allowedTypes.includes(file.type)) {
            showProjectMessage(
                "Please select a JPG, PNG, WEBP or GIF image.",
                "error"
            );

            imageInput.value = "";
            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            showProjectMessage(
                "Image size must not exceed 5MB.",
                "error"
            );

            imageInput.value = "";
            return;
        }

        const reader = new FileReader();

        reader.onload = event => {
            preview.innerHTML = "";

            const image = document.createElement("img");

            image.src = event.target.result;
            image.alt = "Project image preview";
            image.style.width = "100%";
            image.style.height = "100%";
            image.style.maxHeight = "200px";
            image.style.objectFit = "cover";
            image.style.borderRadius = "10px";

            preview.appendChild(image);
        };

        reader.onerror = () => {
            showProjectMessage(
                "Unable to preview this image.",
                "error"
            );
        };

        reader.readAsDataURL(file);
    });
}


/* =========================================================
   18. PROJECT NOTIFICATIONS
========================================================= */

function showProjectMessage(message, type = "success") {
    let notification = document.getElementById(
        "projectNotification"
    );

    if (!notification) {
        notification = document.createElement("div");

        notification.id = "projectNotification";

        notification.style.position = "fixed";
        notification.style.top = "90px";
        notification.style.right = "24px";
        notification.style.zIndex = "99999";
        notification.style.padding = "14px 20px";
        notification.style.borderRadius = "8px";
        notification.style.maxWidth = "360px";
        notification.style.fontSize = "14px";
        notification.style.boxShadow = "0 8px 24px rgba(0,0,0,0.25)";
        notification.style.color = "#ffffff";

        document.body.appendChild(notification);
    }

    notification.textContent = message;

    notification.style.background =
        type === "error" ? "#7f1d1d" : "#14532d";

    if (notification.hideTimeout) {
        clearTimeout(notification.hideTimeout);
    }

    notification.hideTimeout = setTimeout(() => {
        notification.remove();
    }, 4000);
}


/* =========================================================
   19. DELETE PROJECT
========================================================= */

async function deleteProject(button) {
    const row = button.closest("tr");

    if (!row) return;

    const projectId = row.dataset.id;

    if (!projectId) {
        showProjectMessage(
            "Project ID is missing.",
            "error"
        );

        return;
    }

    const confirmed = confirm(
        "Are you sure you want to delete this project?"
    );

    if (!confirmed) return;

    const csrfToken = getProjectCsrfToken();

    if (!csrfToken) {
        showProjectMessage(
            "CSRF token is missing. Please refresh the page.",
            "error"
        );

        return;
    }

    button.disabled = true;

    try {
        const result = await projectApiRequest(
            `${getProjectBaseUrl()}/${encodeURIComponent(projectId)}`,
            {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    _token: csrfToken
                })
            }
        );

        showProjectMessage(
            result.message || "Project deleted successfully."
        );

        row.remove();

        filterProjects();

    } catch (error) {
        console.error("Project deletion error:", error);

        showProjectMessage(
            error.message || "Unable to delete this project.",
            "error"
        );

        button.disabled = false;
    }
}


/* =========================================================
   20. SEARCH AND FILTER PROJECTS
========================================================= */

function filterProjects() {
    const {
        table,
        search,
        categoryFilter,
        statusFilter
    } = getProjectElements();

    if (!table) return;

    const searchValue =
        search?.value.toLowerCase().trim() || "";

    const categoryValue =
        categoryFilter?.value.toLowerCase() || "";

    const statusValue =
        statusFilter?.value.toLowerCase() || "";

    const rows = table.querySelectorAll("tr");

    let visibleCount = 0;

    rows.forEach(row => {
        const searchableText = row.textContent.toLowerCase();

        const category =
            (row.dataset.category || "").toLowerCase();

        const status =
            (row.dataset.status || "").toLowerCase();

        const matchesSearch =
            searchableText.includes(searchValue);

        const matchesCategory =
            !categoryValue || category === categoryValue;

        const matchesStatus =
            !statusValue || status === statusValue;

        const isVisible =
            matchesSearch && matchesCategory && matchesStatus;

        row.style.display = isVisible ? "" : "none";

        if (isVisible) {
            visibleCount++;
        }
    });

    const emptyState = document.getElementById("projectEmptyState");

    if (emptyState) {
        emptyState.hidden = visibleCount > 0;
    }
}


/* =========================================================
   21. INITIALIZE PROJECT MANAGEMENT
========================================================= */

document.addEventListener("DOMContentLoaded", function () {
    const {
        form,
        modal,
        search,
        categoryFilter,
        statusFilter
    } = getProjectElements();


    // Project form submission.
    if (form) {
        form.addEventListener("submit", submitProject);
    }


    // Close modal when clicking outside the content.
    if (modal) {
        modal.addEventListener("click", function (event) {
            if (event.target === modal) {
                closeProjectModal();
            }
        });
    }


    // Close modal with Escape key.
    document.addEventListener("keydown", function (event) {
        if (
            event.key === "Escape" &&
            modal?.classList.contains("active")
        ) {
            closeProjectModal();
        }
    });


    // Project search.
    if (search) {
        search.addEventListener("input", filterProjects);
    }


    // Category filter.
    if (categoryFilter) {
        categoryFilter.addEventListener("change", filterProjects);
    }


    // Status filter.
    if (statusFilter) {
        statusFilter.addEventListener("change", filterProjects);
    }


    // Technology input: Enter key.
    const technologyInput = document.getElementById(
        "projectTechnologyInput"
    );

    if (technologyInput) {
        technologyInput.addEventListener("keydown", event => {
            if (event.key === "Enter") {
                event.preventDefault();
                addProjectTechnology();
            }
        });
    }


    // Slug generation.
    initializeProjectSlug();


    // Image preview.
    initializeProjectImagePreview();


    // Initial empty-state check.
    filterProjects();

    console.log("Project management JavaScript initialized.");
});


/* =========================================================
   22. GLOBAL FUNCTIONS
   Required by inline HTML onclick attributes.
========================================================= */

window.openProjectModal = openProjectModal;
window.closeProjectModal = closeProjectModal;
window.editProject = editProject;
window.deleteProject = deleteProject;
window.addProjectTechnology = addProjectTechnology;
