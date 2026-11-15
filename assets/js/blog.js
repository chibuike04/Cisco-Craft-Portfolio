
/* =========================================================
   CODECRAFT ADMIN DASHBOARD
   BLOG MANAGEMENT JAVASCRIPT
========================================================= */


/* =========================================================
   1. BLOG ELEMENT REFERENCES
========================================================= */

function getBlogElements() {

    return {
        form: document.getElementById("blogForm"),
        modal: document.getElementById("blogModal"),
        table: document.getElementById("blogTable"),
        search: document.getElementById("blogSearch"),
        categoryFilter: document.getElementById("categoryFilter"),
        statusFilter: document.getElementById("statusFilter")
    };

}


/* =========================================================
   2. BLOG API ENDPOINT
========================================================= */

function getBlogEndpoint() {

    const { form } = getBlogElements();

    if (form?.dataset.endpoint) {

        return form.dataset.endpoint.replace(/\/$/, "");

    }

    if (form?.getAttribute("action")) {

        return new URL(
            form.getAttribute("action"),
            window.location.origin
        ).pathname.replace(/\/$/, "");

    }

    const currentPath = window.location.pathname.replace(/\/$/, "");

    if (currentPath.endsWith("/admin/blogs")) {

        return currentPath;

    }

    console.error(
        "Unable to determine blog endpoint. Configure data-endpoint on blogForm."
    );

    return null;

}


/* =========================================================
   3. CSRF TOKEN
========================================================= */

function getBlogCsrfToken() {

    const { form } = getBlogElements();

    const tokenInput = form?.querySelector('[name="_token"]');

    return tokenInput?.value || "";

}


/* =========================================================
   4. BLOG API REQUEST
========================================================= */

/**
 * Handles JSON requests.
 *
 * Used for:
 * - Updating blog posts.
 * - Deleting blog posts.
 */
async function blogRequest(url, method, data = null) {

    const token = getBlogCsrfToken();

    if (!token) {

        throw new Error(
            "Security token is missing. Refresh the page and try again."
        );

    }

    const headers = {
        "Accept": "application/json",
        "X-CSRF-Token": token,
        "X-Requested-With": "XMLHttpRequest"
    };

    const options = {
        method,
        headers,
        credentials: "same-origin",
        redirect: "follow"
    };

    if (data !== null) {

        headers["Content-Type"] = "application/json";

        options.body = JSON.stringify({
            ...data,
            _token: token
        });

    }

    const response = await fetch(url, options);

    return await handleBlogResponse(response);

}


/* =========================================================
   5. MULTIPART IMAGE REQUEST
========================================================= */

/**
 * Handles image uploads using FormData.
 *
 * IMPORTANT:
 * Do not manually set Content-Type here.
 * The browser must generate the multipart boundary.
 */
async function blogImageRequest(url, formData) {

    const token = getBlogCsrfToken();

    if (!token) {

        throw new Error(
            "Security token is missing. Refresh the page and try again."
        );

    }

    formData.set("_token", token);

    const response = await fetch(url, {

        method: "POST",

        headers: {
            "Accept": "application/json",
            "X-CSRF-Token": token,
            "X-Requested-With": "XMLHttpRequest"
        },

        body: formData,

        credentials: "same-origin",

        redirect: "follow"

    });

    return await handleBlogResponse(response);

}


/* =========================================================
   6. HANDLE API RESPONSES
========================================================= */

async function handleBlogResponse(response) {

    const contentType = response.headers.get("content-type") || "";

    if (contentType.includes("application/json")) {

        const result = await response.json();

        if (!response.ok || result.success === false) {

            throw new Error(
                result.message || "The blog request could not be completed."
            );

        }

        return result;

    }

    if (!response.ok) {

        throw new Error(
            "The request failed. Please refresh the page and try again."
        );

    }

    return {
        success: true,
        redirected: response.redirected
    };

}


/* =========================================================
   7. BLOG NOTIFICATIONS
========================================================= */

function showBlogMessage(message, type = "success") {

    let notification = document.getElementById("blogNotification");

    if (!notification) {

        notification = document.createElement("div");

        notification.id = "blogNotification";

        notification.style.position = "fixed";
        notification.style.top = "90px";
        notification.style.right = "24px";
        notification.style.zIndex = "99999";
        notification.style.padding = "14px 20px";
        notification.style.borderRadius = "8px";
        notification.style.maxWidth = "360px";
        notification.style.fontSize = "14px";
        notification.style.boxShadow = "0 8px 24px rgba(0,0,0,0.25)";

        document.body.appendChild(notification);

    }

    notification.textContent = message;

    notification.style.background =
        type === "error" ? "#7f1d1d" : "#14532d";

    notification.style.color = "#ffffff";

    if (notification.hideTimeout) {

        clearTimeout(notification.hideTimeout);

    }

    notification.hideTimeout = setTimeout(() => {

        notification.remove();

    }, 4000);

}


/* =========================================================
   8. RESET BLOG FORM
========================================================= */

function resetBlogForm() {

    const { form } = getBlogElements();

    if (!form) return;

    form.reset();

    const editMode = document.getElementById("blogEditMode");

    const submitText = document.getElementById("blogSubmitText");

    const modalTitle = document.getElementById("blogModalTitle");

    if (editMode) {

        editMode.value = "";

    }

    if (submitText) {

        submitText.textContent = "Save Post";

    }

    if (modalTitle) {

        modalTitle.textContent = "Create New Blog Post";

    }

    const slug = document.getElementById("blogSlug");

    if (slug) {

        slug.dataset.manuallyEdited = "false";

    }

    const preview = document.getElementById("blogImagePreview");

    if (preview) {

        preview.style.display = "none";

    }

    const previewImage = document.getElementById("blogPreviewImg");

    if (previewImage) {

        previewImage.removeAttribute("src");

    }

    updateBlogCharacterCounts();

}


/* =========================================================
   9. OPEN AND CLOSE BLOG MODAL
========================================================= */

function openBlogModal() {

    const { form } = getBlogElements();

    if (!form) {

        console.error("Blog form was not found.");

        return;

    }

    resetBlogForm();

    openModal("blogModal");

}


function closeBlogModal() {

    closeModal("blogModal");

}


/* =========================================================
   10. GENERATE BLOG SLUG
========================================================= */

function generateBlogSlug(title) {

    return title

        .toLowerCase()

        .trim()

        .replace(/[^a-z0-9\s-]/g, "")

        .replace(/\s+/g, "-")

        .replace(/-+/g, "-")

        .replace(/^-|-$/g, "");

}


/* =========================================================
   11. AUTO-GENERATE BLOG SLUG
========================================================= */

function initializeBlogSlug() {

    const title = document.getElementById("blogTitle");

    const slug = document.getElementById("blogSlug");

    if (!title || !slug) return;

    title.addEventListener("input", () => {

        if (slug.dataset.manuallyEdited === "true") {

            return;

        }

        slug.value = generateBlogSlug(title.value);

    });

    slug.addEventListener("input", () => {

        slug.dataset.manuallyEdited = "true";

    });

}


/* =========================================================
   12. BLOG CHARACTER COUNTS
========================================================= */

function updateBlogCharacterCounts() {

    const excerpt = document.getElementById("blogExcerpt");

    const metaDescription = document.getElementById("blogMetaDescription");

    const excerptCount = document.getElementById("excerptCount");

    const metaDescriptionCount = document.getElementById(
        "metaDescriptionCount"
    );

    if (excerpt && excerptCount) {

        excerptCount.textContent = excerpt.value.length;

    }

    if (metaDescription && metaDescriptionCount) {

        metaDescriptionCount.textContent = metaDescription.value.length;

    }

}


/* =========================================================
   13. EDIT BLOG POST
========================================================= */

function editBlog(button) {

    const row = button.closest("tr");

    if (!row) {

        console.error("Blog row was not found.");

        return;

    }

    const data = row.dataset;

    resetBlogForm();

    const editMode = document.getElementById("blogEditMode");

    const modalTitle = document.getElementById("blogModalTitle");

    const submitText = document.getElementById("blogSubmitText");

    if (editMode) {

        editMode.value = data.id || "";

    }

    if (modalTitle) {

        modalTitle.textContent = "Edit Blog Post";

    }

    if (submitText) {

        submitText.textContent = "Update Post";

    }

    const setValue = (id, value) => {

        const element = document.getElementById(id);

        if (element) {

            element.value = value ?? "";

        }

    };

    setValue("blogTitle", data.title);

    setValue("blogSlug", data.slug);

    setValue("blogCategory", data.categoryId);

    setValue("blogExcerpt", data.excerpt);

    setValue("blogContent", data.content);

    setValue("blogImageAlt", data.imageAlt);

    setValue("blogStatus", data.status || "draft");

    setValue("blogPublishedAt", data.publishedAt);

    setValue("blogMetaTitle", data.metaTitle);

    setValue("blogMetaDescription", data.metaDescription);

    const featured = document.getElementById("blogFeatured");

    if (featured) {

        featured.checked = data.featured === "1";

    }

    const slug = document.getElementById("blogSlug");

    if (slug) {

        slug.dataset.manuallyEdited = "false";

    }

    updateBlogCharacterCounts();

    openModal("blogModal");

}


/* =========================================================
   14. COLLECT BLOG FORM DATA
========================================================= */

function getBlogFormData() {

    const getValue = id => {

        return document.getElementById(id)?.value.trim() || "";

    };

    const featured = document.getElementById("blogFeatured");

    return {

        title: getValue("blogTitle"),

        slug: getValue("blogSlug"),

        category_id: Number(getValue("blogCategory")),

        excerpt: getValue("blogExcerpt"),

        content: getValue("blogContent"),

        status: getValue("blogStatus") || "draft",

        is_featured: featured?.checked ? 1 : 0,

        meta_title: getValue("blogMetaTitle"),

        meta_description: getValue("blogMetaDescription")

    };

}


/* =========================================================
   15. VALIDATE BLOG FORM
========================================================= */

function validateBlogForm(data) {

    if (data.title.length < 3 || data.title.length > 255) {

        return "The title must be between 3 and 255 characters.";

    }

    if (!data.category_id || data.category_id < 1) {

        return "Please select a blog category.";

    }

    if (data.excerpt.length > 1000) {

        return "The excerpt cannot exceed 1000 characters.";

    }

    if (!data.content) {

        return "Article content is required.";

    }

    if (!["draft", "published"].includes(data.status)) {

        return "Please select a valid publication status.";

    }

    return null;

}


/* =========================================================
   16. VALIDATE BLOG IMAGE
========================================================= */

function validateBlogImage(file) {

    if (!file) return null;

    const allowedTypes = [
        "image/jpeg",
        "image/png",
        "image/webp",
        "image/gif"
    ];

    const maximumSize = 5 * 1024 * 1024;

    if (!allowedTypes.includes(file.type)) {

        return "Please upload a JPG, PNG, WebP, or GIF image.";

    }

    if (file.size > maximumSize) {

        return "The featured image must not exceed 5 MB.";

    }

    if (file.size === 0) {

        return "The selected image is empty.";

    }

    return null;

}


/* =========================================================
   17. BUILD BLOG FORM DATA
========================================================= */

/**
 * Converts blog fields into multipart/form-data.
 *
 * Used when creating a blog so the article and image
 * can be submitted in the same request.
 */
function buildBlogMultipartData(data) {

    const formData = new FormData();

    Object.entries(data).forEach(([key, value]) => {

        formData.append(key, value);

    });

    const imageInput = document.getElementById("blogImage");

    const imageFile = imageInput?.files?.[0];

    if (imageFile) {

        formData.append("featured_image", imageFile);

    }

    const imageAlt = document.getElementById("blogImageAlt");

    if (imageAlt) {

        formData.set("image_alt", imageAlt.value.trim());

    }

    return formData;

}


/* =========================================================
   18. UPLOAD BLOG FEATURED IMAGE
========================================================= */

/**
 * Uploads a new featured image for an existing blog.
 *
 * Endpoint:
 * POST /admin/blogs/{id}/featured-image
 */
async function uploadBlogFeaturedImage(blogId) {

    const imageInput = document.getElementById("blogImage");

    const imageFile = imageInput?.files?.[0];

    // No newly selected image means nothing to upload.
    if (!imageFile) {

        return null;

    }

    const endpoint = getBlogEndpoint();

    if (!endpoint) {

        throw new Error("Blog endpoint could not be determined.");

    }

    const formData = new FormData();

    formData.append("featured_image", imageFile);

    const imageAlt = document.getElementById("blogImageAlt");

    if (imageAlt) {

        formData.append("image_alt", imageAlt.value.trim());

    }

    return await blogImageRequest(
        `${endpoint}/${encodeURIComponent(blogId)}/featured-image`,
        formData
    );

}


/* =========================================================
   19. SAVE BLOG POST
========================================================= */

async function saveBlog(event) {

    event.preventDefault();

    const { form } = getBlogElements();

    if (!form) return;

    const data = getBlogFormData();

    const validationError = validateBlogForm(data);

    if (validationError) {

        showBlogMessage(validationError, "error");

        return;

    }

    const imageInput = document.getElementById("blogImage");

    const imageFile = imageInput?.files?.[0] || null;

    const imageError = validateBlogImage(imageFile);

    if (imageError) {

        showBlogMessage(imageError, "error");

        return;

    }

    const endpoint = getBlogEndpoint();

    if (!endpoint) {

        showBlogMessage(
            "Blog endpoint could not be determined.",
            "error"
        );

        return;

    }

    const editMode = document.getElementById("blogEditMode");

    const blogId = editMode?.value.trim();

    const isEditing = Boolean(blogId);

    const submitButton = form.querySelector('[type="submit"]');

    const submitText = document.getElementById("blogSubmitText");

    const originalText = submitText?.textContent || "";

    if (submitButton) {

        submitButton.disabled = true;

    }

    if (submitText) {

        submitText.textContent = isEditing
            ? "Updating..."
            : "Saving...";

    }

    try {

        let result;

        if (isEditing) {

            /*
             * STEP 1:
             * Update blog text using JSON PUT.
             */

            result = await blogRequest(
                `${endpoint}/${encodeURIComponent(blogId)}`,
                "PUT",
                data
            );

            /*
             * STEP 2:
             * Upload a replacement image if one was selected.
             */

            if (imageFile) {

                if (submitText) {

                    submitText.textContent = "Uploading image...";

                }

                await uploadBlogFeaturedImage(blogId);

            }

        } else {

            /*
             * CREATE:
             * Send blog fields and image together using FormData.
             */

            if (submitText) {

                submitText.textContent = imageFile
                    ? "Saving post and image..."
                    : "Saving...";

            }

            const multipartData = buildBlogMultipartData(data);

            result = await blogImageRequest(
                endpoint,
                multipartData
            );

        }

        showBlogMessage(
            result?.message ||
            (
                isEditing
                    ? "Blog post updated successfully."
                    : "Blog post created successfully."
            )
        );

        closeBlogModal();

        // Reload to display the latest database records.
        window.location.reload();

    } catch (error) {

        console.error("Blog save error:", error);

        showBlogMessage(
            error.message || "Unable to save the blog post.",
            "error"
        );

    } finally {

        if (submitButton) {

            submitButton.disabled = false;

        }

        if (submitText) {

            submitText.textContent = originalText;

        }

    }

}


/* =========================================================
   20. DELETE BLOG POST
========================================================= */

async function deleteBlog(button) {

    const row = button.closest("tr");

    if (!row) return;

    const blogId = row.dataset.id;

    if (!blogId) {

        showBlogMessage("Blog ID is missing.", "error");

        return;

    }

    const confirmed = confirm(
        "Are you sure you want to delete this blog post?"
    );

    if (!confirmed) return;

    const endpoint = getBlogEndpoint();

    if (!endpoint) {

        showBlogMessage(
            "Blog endpoint could not be determined.",
            "error"
        );

        return;

    }

    button.disabled = true;

    try {

        const result = await blogRequest(
            `${endpoint}/${encodeURIComponent(blogId)}`,
            "DELETE"
        );

        showBlogMessage(
            result.message || "Blog post deleted successfully."
        );

        window.location.reload();

    } catch (error) {

        console.error("Blog delete error:", error);

        showBlogMessage(
            error.message || "Unable to delete the blog post.",
            "error"
        );

        button.disabled = false;

    }

}


/* =========================================================
   21. SEARCH AND FILTER BLOG POSTS
========================================================= */

function filterBlogs() {

    const {
        table,
        search,
        categoryFilter,
        statusFilter
    } = getBlogElements();

    if (!table) return;

    const searchValue = search?.value.toLowerCase().trim() || "";

    const categoryValue = categoryFilter?.value.toLowerCase() || "all";

    const statusValue = statusFilter?.value.toLowerCase() || "all";

    const rows = table.querySelectorAll("tbody tr");

    rows.forEach(row => {

        const searchableText = row.textContent.toLowerCase();

        const rowCategory = (row.dataset.category || "").toLowerCase();

        const rowStatus = (row.dataset.status || "").toLowerCase();

        const matchesSearch = searchableText.includes(searchValue);

        const matchesCategory =
            categoryValue === "all" ||
            rowCategory === categoryValue;

        const matchesStatus =
            statusValue === "all" ||
            rowStatus === statusValue;

        row.style.display =
            matchesSearch && matchesCategory && matchesStatus
                ? ""
                : "none";

    });

}


/* =========================================================
   22. BLOG IMAGE PREVIEW
========================================================= */

function initializeBlogImagePreview() {

    const imageInput = document.getElementById("blogImage");

    const preview = document.getElementById("blogImagePreview");

    const previewImage = document.getElementById("blogPreviewImg");

    if (!imageInput || !preview || !previewImage) return;

    imageInput.addEventListener("change", () => {

        const file = imageInput.files?.[0];

        if (!file) {

            preview.style.display = "none";

            previewImage.removeAttribute("src");

            return;

        }

        const imageError = validateBlogImage(file);

        if (imageError) {

            showBlogMessage(imageError, "error");

            imageInput.value = "";

            preview.style.display = "none";

            previewImage.removeAttribute("src");

            return;

        }

        const reader = new FileReader();

        reader.onload = event => {

            previewImage.src = event.target.result;

            preview.style.display = "block";

        };

        reader.onerror = () => {

            showBlogMessage(
                "Unable to preview the selected image.",
                "error"
            );

        };

        reader.readAsDataURL(file);

    });

}


/* =========================================================
   23. INITIALIZE BLOG MANAGEMENT
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    const {
        form,
        search,
        categoryFilter,
        statusFilter
    } = getBlogElements();


    // Search
    if (search) {

        search.addEventListener("input", filterBlogs);

    }


    // Category filter
    if (categoryFilter) {

        categoryFilter.addEventListener("change", filterBlogs);

    }


    // Status filter
    if (statusFilter) {

        statusFilter.addEventListener("change", filterBlogs);

    }


    // Blog form submission
    if (form) {

        form.addEventListener("submit", saveBlog);

    }


    // Character counters
    const excerpt = document.getElementById("blogExcerpt");

    const metaDescription = document.getElementById(
        "blogMetaDescription"
    );

    if (excerpt) {

        excerpt.addEventListener(
            "input",
            updateBlogCharacterCounts
        );

    }

    if (metaDescription) {

        metaDescription.addEventListener(
            "input",
            updateBlogCharacterCounts
        );

    }

    updateBlogCharacterCounts();


    // Slug generation
    initializeBlogSlug();


    // Image preview
    initializeBlogImagePreview();

});
