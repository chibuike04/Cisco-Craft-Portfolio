
/* =========================================================
   CODECRAFT ADMIN - NEWSLETTER MANAGEMENT
   File: assets/js/newsletter.js

   Features:
   1. Load subscribers supplied by PHP
   2. Subscriber statistics
   3. Search subscribers
   4. Filter subscribers
   5. Activate / unsubscribe subscribers
   6. Delete subscribers
   7. Select individual active subscribers
   8. Select all visible active subscribers
   9. Newsletter composer validation
   10. Newsletter sending request
========================================================= */

"use strict";


/* =========================================================
   1. CONFIGURATION
========================================================= */

const newsletterTableBody = document.getElementById("subscriberTableBody");

const NEWSLETTER_ENDPOINT = (
    newsletterTableBody?.dataset.endpoint || ""
).replace(/\/+$/, "");

let newsletterSubscribers = Array.isArray(window.newsletterSubscribers)
    ? window.newsletterSubscribers
    : [];

// Keep selected subscriber IDs when the table is re-rendered.
const selectedNewsletterSubscribers = new Set();


/* =========================================================
   2. ELEMENT REFERENCES
========================================================= */

function getNewsletterElements() {
    return {
        tableBody: document.getElementById("subscriberTableBody"),
        search: document.getElementById("subscriberSearch"),
        filter: document.getElementById("subscriberFilter"),

        total: document.getElementById("totalSubscribers"),
        active: document.getElementById("activeSubscribers"),
        unsubscribed: document.getElementById("unsubscribedSubscribers"),

        selectAll: document.getElementById("selectAllSubscribers"),

        form: document.getElementById("newsletterForm")
    };
}


/* =========================================================
   3. CSRF TOKEN
========================================================= */

function getNewsletterCsrfToken() {
    return (
        window.adminCsrfToken ||
        document.querySelector('input[name="_token"]')?.value ||
        ""
    );
}


/* =========================================================
   4. API REQUEST
========================================================= */

async function newsletterRequest(url, method = "GET", data = null) {
    const csrfToken = getNewsletterCsrfToken();

    const headers = {
        "Accept": "application/json",
        "X-Requested-With": "XMLHttpRequest"
    };

    const options = {
        method,
        headers,
        credentials: "same-origin"
    };

    if (method !== "GET") {
        if (!csrfToken) {
            throw new Error(
                "Security token is missing. Refresh the page and try again."
            );
        }

        headers["Content-Type"] = "application/json";
        headers["X-CSRF-Token"] = csrfToken;

        options.body = JSON.stringify({
            ...(data || {}),
            _token: csrfToken
        });
    }

    const response = await fetch(url, options);
    const responseText = await response.text();

    let result = {};

    try {
        result = responseText ? JSON.parse(responseText) : {};
    } catch {
        console.error(
            "Newsletter server returned a non-JSON response:",
            responseText
        );

        throw new Error(
            `Server returned an invalid response (HTTP ${response.status}). Check the PHP error log.`
        );
    }

    if (!response.ok || result.success === false) {
        throw new Error(
            result.message ||
            `Request failed with HTTP ${response.status}.`
        );
    }

    return result;
}


/* =========================================================
   5. NOTIFICATIONS
========================================================= */

function showNewsletterMessage(message, type = "success") {
    let notification = document.getElementById("newsletterNotification");

    if (!notification) {
        notification = document.createElement("div");

        notification.id = "newsletterNotification";

        notification.style.cssText = `
            position: fixed;
            top: 90px;
            right: 25px;
            z-index: 9999;
            max-width: 350px;
            padding: 14px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            box-shadow: 0 8px 25px rgba(0,0,0,0.25);
        `;

        document.body.appendChild(notification);
    }

    notification.textContent = message;

    notification.style.background =
        type === "error" ? "#991b1b" : "#166534";

    notification.style.color = "#ffffff";
    notification.style.display = "block";

    if (notification.hideTimer) {
        clearTimeout(notification.hideTimer);
    }

    notification.hideTimer = setTimeout(() => {
        notification.style.display = "none";
    }, 3500);
}


/* =========================================================
   6. FORMAT DATE
========================================================= */

function formatSubscriberDate(dateValue) {
    if (!dateValue) {
        return "Not available";
    }

    const normalizedDate = String(dateValue)
        .trim()
        .replace(" ", "T");

    const date = new Date(normalizedDate);

    if (Number.isNaN(date.getTime())) {
        return "Not available";
    }

    return date.toLocaleDateString(undefined, {
        year: "numeric",
        month: "short",
        day: "numeric"
    });
}


/* =========================================================
   7. SUBSCRIBER SELECTION HELPERS
========================================================= */

function isActiveSubscriber(subscriber) {
    return String(subscriber.status || "").toLowerCase() === "active";
}


function getSelectedActiveSubscriberIds() {
    const activeIds = new Set(
        newsletterSubscribers
            .filter(isActiveSubscriber)
            .map(subscriber => Number(subscriber.id))
    );

    // Remove IDs that are no longer active or no longer exist.
    for (const id of selectedNewsletterSubscribers) {
        if (!activeIds.has(id)) {
            selectedNewsletterSubscribers.delete(id);
        }
    }

    return Array.from(selectedNewsletterSubscribers);
}


function updateSelectAllCheckbox() {
    const elements = getNewsletterElements();

    if (!elements.selectAll) {
        return;
    }

    const visibleCheckboxes = Array.from(
        document.querySelectorAll(
            '#subscriberTableBody input[data-select-subscriber]'
        )
    );

    const enabledCheckboxes = visibleCheckboxes.filter(
        checkbox => !checkbox.disabled
    );

    const checkedCount = enabledCheckboxes.filter(
        checkbox => checkbox.checked
    ).length;

    elements.selectAll.checked =
        enabledCheckboxes.length > 0 &&
        checkedCount === enabledCheckboxes.length;

    elements.selectAll.indeterminate =
        checkedCount > 0 &&
        checkedCount < enabledCheckboxes.length;

    elements.selectAll.disabled = enabledCheckboxes.length === 0;
}


/* =========================================================
   8. UPDATE STATISTICS
========================================================= */

function updateNewsletterStats() {
    const elements = getNewsletterElements();

    const total = newsletterSubscribers.length;

    const active = newsletterSubscribers.filter(
        isActiveSubscriber
    ).length;

    const unsubscribed = newsletterSubscribers.filter(subscriber =>
        String(subscriber.status || "").toLowerCase() === "unsubscribed"
    ).length;

    if (elements.total) {
        elements.total.textContent = total;
    }

    if (elements.active) {
        elements.active.textContent = active;
    }

    if (elements.unsubscribed) {
        elements.unsubscribed.textContent = unsubscribed;
    }
}


/* =========================================================
   9. RENDER SUBSCRIBERS
========================================================= */

function renderNewsletterSubscribers() {
    const elements = getNewsletterElements();

    if (!elements.tableBody) {
        return;
    }

    const searchTerm = elements.search
        ? elements.search.value.trim().toLowerCase()
        : "";

    const selectedFilter = elements.filter
        ? elements.filter.value
        : "all";

    const filteredSubscribers = newsletterSubscribers.filter(subscriber => {
        const email = String(subscriber.email || "").toLowerCase();
        const name = String(subscriber.name || "").toLowerCase();
        const status = String(subscriber.status || "").toLowerCase();

        const matchesSearch =
            email.includes(searchTerm) ||
            name.includes(searchTerm);

        const matchesFilter =
            selectedFilter === "all" ||
            status === selectedFilter;

        return matchesSearch && matchesFilter;
    });

    elements.tableBody.replaceChildren();

    if (filteredSubscribers.length === 0) {
        const row = document.createElement("tr");
        const cell = document.createElement("td");

        cell.colSpan = 5;
        cell.textContent = "No subscribers found.";
        cell.style.textAlign = "center";
        cell.style.padding = "30px";

        row.appendChild(cell);
        elements.tableBody.appendChild(row);

        updateSelectAllCheckbox();

        return;
    }

    filteredSubscribers.forEach(subscriber => {
        const row = document.createElement("tr");

        const id = Number(subscriber.id);

        const status = String(
            subscriber.status || "unsubscribed"
        ).toLowerCase();

        const active = status === "active";

        // Selection checkbox column
        const checkboxCell = document.createElement("td");

        const checkbox = document.createElement("input");

        checkbox.type = "checkbox";
        checkbox.dataset.selectSubscriber = "true";
        checkbox.value = String(id);
        checkbox.checked = active && selectedNewsletterSubscribers.has(id);
        checkbox.disabled = !active;
        checkbox.setAttribute(
            "aria-label",
            `Select ${subscriber.email || "subscriber"}`
        );

        checkboxCell.appendChild(checkbox);

        // Email column
        const emailCell = document.createElement("td");

        const emailText = document.createElement("strong");
        emailText.textContent = subscriber.email || "No email";

        emailCell.appendChild(emailText);

        if (subscriber.name) {
            const name = document.createElement("small");

            name.textContent = subscriber.name;
            name.style.display = "block";
            name.style.color = "var(--text-muted)";
            name.style.marginTop = "4px";

            emailCell.appendChild(name);
        }

        // Date column
        const dateCell = document.createElement("td");

        dateCell.textContent = formatSubscriberDate(
            subscriber.subscribed_at
        );

        // Status column
        const statusCell = document.createElement("td");

        const statusBadge = document.createElement("span");

        statusBadge.textContent =
            status.charAt(0).toUpperCase() + status.slice(1);

        statusBadge.style.cssText = `
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            background: ${
                active
                    ? "rgba(34,197,94,0.15)"
                    : "rgba(249,115,22,0.15)"
            };
            color: ${
                active
                    ? "#4ade80"
                    : "#fb923c"
            };
        `;

        statusCell.appendChild(statusBadge);

        // Action column
        const actionCell = document.createElement("td");

        const statusButton = document.createElement("button");

        statusButton.type = "button";
        statusButton.className = "outline-btn";
        statusButton.dataset.action = "status";
        statusButton.dataset.id = String(id);

        statusButton.textContent =
            active ? "Unsubscribe" : "Activate";

        const deleteButton = document.createElement("button");

        deleteButton.type = "button";
        deleteButton.className = "outline-btn";
        deleteButton.dataset.action = "delete";
        deleteButton.dataset.id = String(id);
        deleteButton.textContent = "Delete";
        deleteButton.style.marginLeft = "8px";

        actionCell.append(statusButton, deleteButton);

        row.append(
            checkboxCell,
            emailCell,
            dateCell,
            statusCell,
            actionCell
        );

        elements.tableBody.appendChild(row);
    });

    updateSelectAllCheckbox();
}


/* =========================================================
   10. LOAD SUBSCRIBERS
========================================================= */

function loadNewsletterSubscribers() {
    const elements = getNewsletterElements();

    if (!elements.tableBody) {
        return;
    }

    newsletterSubscribers = Array.isArray(window.newsletterSubscribers)
        ? window.newsletterSubscribers
        : [];

    updateNewsletterStats();
    renderNewsletterSubscribers();

    if (!NEWSLETTER_ENDPOINT) {
        console.error(
            "Newsletter endpoint is missing. Subscriber display is available, but management actions are disabled."
        );
    }
}


/* =========================================================
   11. UPDATE SUBSCRIBER STATUS
========================================================= */

async function updateSubscriberStatus(subscriberId, button = null) {
    if (!NEWSLETTER_ENDPOINT) {
        showNewsletterMessage(
            "Newsletter endpoint is missing.",
            "error"
        );
        return;
    }

    const subscriber = newsletterSubscribers.find(item =>
        Number(item.id) === Number(subscriberId)
    );

    if (!subscriber) {
        showNewsletterMessage("Subscriber not found.", "error");
        return;
    }

    const currentStatus = String(
        subscriber.status || ""
    ).toLowerCase();

    const newStatus = currentStatus === "active"
        ? "unsubscribed"
        : "active";

    const confirmed = confirm(
        newStatus === "active"
            ? "Are you sure you want to activate this subscriber?"
            : "Are you sure you want to unsubscribe this person?"
    );

    if (!confirmed) {
        return;
    }

    if (button) {
        button.disabled = true;
    }

    try {
        const result = await newsletterRequest(
            `${NEWSLETTER_ENDPOINT}/${subscriberId}/status`,
            "PUT",
            { status: newStatus }
        );

        subscriber.status = result.status || newStatus;

        if (newStatus === "unsubscribed") {
            subscriber.unsubscribed_at =
                result.unsubscribed_at || new Date().toISOString();

            selectedNewsletterSubscribers.delete(Number(subscriberId));
        } else {
            subscriber.unsubscribed_at = null;
        }

        updateNewsletterStats();
        renderNewsletterSubscribers();

        showNewsletterMessage(
            result.message || "Subscriber status updated."
        );

    } catch (error) {
        console.error("Newsletter status error:", error);

        showNewsletterMessage(error.message, "error");

        if (button) {
            button.disabled = false;
        }
    }
}


/* =========================================================
   12. DELETE SUBSCRIBER
========================================================= */

async function deleteSubscriber(subscriberId, button = null) {
    if (!NEWSLETTER_ENDPOINT) {
        showNewsletterMessage(
            "Newsletter endpoint is missing.",
            "error"
        );
        return;
    }

    const subscriber = newsletterSubscribers.find(item =>
        Number(item.id) === Number(subscriberId)
    );

    if (!subscriber) {
        showNewsletterMessage("Subscriber not found.", "error");
        return;
    }

    const confirmed = confirm(
        `Are you sure you want to permanently delete ${subscriber.email}?`
    );

    if (!confirmed) {
        return;
    }

    if (button) {
        button.disabled = true;
    }

    try {
        const result = await newsletterRequest(
            `${NEWSLETTER_ENDPOINT}/${subscriberId}`,
            "DELETE"
        );

        newsletterSubscribers = newsletterSubscribers.filter(item =>
            Number(item.id) !== Number(subscriberId)
        );

        selectedNewsletterSubscribers.delete(Number(subscriberId));

        updateNewsletterStats();
        renderNewsletterSubscribers();

        showNewsletterMessage(
            result.message || "Subscriber deleted successfully."
        );

    } catch (error) {
        console.error("Newsletter delete error:", error);

        showNewsletterMessage(error.message, "error");

        if (button) {
            button.disabled = false;
        }
    }
}


/* =========================================================
   13. TABLE ACTIONS AND CHECKBOX SELECTION
========================================================= */

function initializeNewsletterTableActions() {
    const elements = getNewsletterElements();

    if (!elements.tableBody) {
        return;
    }

    elements.tableBody.addEventListener("click", event => {
        const button = event.target.closest("button[data-action]");

        if (!button || button.disabled) {
            return;
        }

        const subscriberId = Number(button.dataset.id);

        if (!Number.isInteger(subscriberId) || subscriberId <= 0) {
            return;
        }

        if (button.dataset.action === "status") {
            updateSubscriberStatus(subscriberId, button);
        }

        if (button.dataset.action === "delete") {
            deleteSubscriber(subscriberId, button);
        }
    });

    // Individual subscriber selection
    elements.tableBody.addEventListener("change", event => {
        const checkbox = event.target.closest(
            'input[data-select-subscriber]'
        );

        if (!checkbox) {
            return;
        }

        const subscriberId = Number(checkbox.value);

        const subscriber = newsletterSubscribers.find(item =>
            Number(item.id) === subscriberId
        );

        if (!subscriber || !isActiveSubscriber(subscriber)) {
            checkbox.checked = false;
            return;
        }

        if (checkbox.checked) {
            selectedNewsletterSubscribers.add(subscriberId);
        } else {
            selectedNewsletterSubscribers.delete(subscriberId);
        }

        updateSelectAllCheckbox();
    });

    // Select all visible active subscribers
    if (elements.selectAll) {
        elements.selectAll.addEventListener("change", () => {
            const checkboxes = document.querySelectorAll(
                '#subscriberTableBody input[data-select-subscriber]'
            );

            checkboxes.forEach(checkbox => {
                if (checkbox.disabled) {
                    return;
                }

                const subscriberId = Number(checkbox.value);

                if (elements.selectAll.checked) {
                    selectedNewsletterSubscribers.add(subscriberId);
                    checkbox.checked = true;
                } else {
                    selectedNewsletterSubscribers.delete(subscriberId);
                    checkbox.checked = false;
                }
            });

            updateSelectAllCheckbox();
        });
    }
}


/* =========================================================
   14. SEARCH AND FILTER
========================================================= */

function initializeNewsletterFilters() {
    const elements = getNewsletterElements();

    if (elements.search) {
        elements.search.addEventListener("input", () => {
            renderNewsletterSubscribers();
        });
    }

    if (elements.filter) {
        elements.filter.addEventListener("change", () => {
            renderNewsletterSubscribers();
        });
    }
}


/* =========================================================
   15. NEWSLETTER COMPOSER
========================================================= */

function initializeNewsletterComposer() {
    const elements = getNewsletterElements();

    if (!elements.form) {
        return;
    }

    elements.form.addEventListener("submit", async event => {
        event.preventDefault();

        if (!NEWSLETTER_ENDPOINT) {
            showNewsletterMessage(
                "Newsletter endpoint is missing.",
                "error"
            );
            return;
        }

        const formData = new FormData(elements.form);

        const subject = String(
            formData.get("subject") || ""
        ).trim();

        const message = String(
            formData.get("message") || ""
        ).trim();

        const recipientMode = String(
            formData.get("recipients") || "all"
        );

        if (!subject) {
            showNewsletterMessage(
                "Please enter a newsletter subject.",
                "error"
            );
            return;
        }

        if (!message) {
            showNewsletterMessage(
                "Please write your newsletter message.",
                "error"
            );
            return;
        }

        if (recipientMode !== "all" && recipientMode !== "selected") {
            showNewsletterMessage(
                "Please choose a valid recipient option.",
                "error"
            );
            return;
        }

        const activeSubscribers = newsletterSubscribers.filter(
            isActiveSubscriber
        );

        let subscriberIds = [];
        let recipientCount = activeSubscribers.length;

        if (recipientMode === "selected") {
            subscriberIds = getSelectedActiveSubscriberIds();

            if (subscriberIds.length === 0) {
                showNewsletterMessage(
                    "Please select at least one active subscriber.",
                    "error"
                );
                return;
            }

            recipientCount = subscriberIds.length;
        }

        if (recipientCount === 0) {
            showNewsletterMessage(
                "There are no active subscribers to receive this newsletter.",
                "error"
            );
            return;
        }

        const confirmed = confirm(
            recipientMode === "all"
                ? `Send this newsletter to all ${recipientCount} active subscribers?`
                : `Send this newsletter to the ${recipientCount} selected subscribers?`
        );

        if (!confirmed) {
            return;
        }

        const submitButton = elements.form.querySelector(
            'button[type="submit"]'
        );

        const originalButtonText = submitButton
            ? submitButton.innerHTML
            : "";

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.innerHTML = "Sending...";
        }

        try {
            const payload = {
                recipients: recipientMode,
                subject,
                message
            };

            if (recipientMode === "selected") {
                payload.subscriber_ids = subscriberIds;
            }

            const result = await newsletterRequest(
                `${NEWSLETTER_ENDPOINT}/send`,
                "POST",
                payload
            );

            const sent = Number(result.sent ?? 0);
            const failed = Number(result.failed ?? 0);

            let resultMessage = result.message ||
                `Newsletter sending completed. Sent: ${sent}. Failed: ${failed}.`;

            showNewsletterMessage(
                resultMessage,
                failed > 0 ? "error" : "success"
            );

            // Clear selected subscribers after a successful request.
            selectedNewsletterSubscribers.clear();

            if (recipientMode === "selected") {
                renderNewsletterSubscribers();
            }

            // Clear the composer only when the request succeeded.
            elements.form.reset();

        } catch (error) {
            console.error("Newsletter sending error:", error);

            showNewsletterMessage(error.message, "error");

        } finally {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            }
        }
    });
}


/* =========================================================
   16. INITIALIZE NEWSLETTER
========================================================= */

document.addEventListener("DOMContentLoaded", () => {
    loadNewsletterSubscribers();

    initializeNewsletterTableActions();

    initializeNewsletterFilters();

    initializeNewsletterComposer();
});
