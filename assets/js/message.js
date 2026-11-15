
/* =========================================================
   CODECRAFT ADMIN DASHBOARD
   MESSAGE MANAGEMENT JAVASCRIPT
========================================================= */

/* =========================================================
   1. MESSAGE STATE
========================================================= */

let currentMessageRow = null;

/* =========================================================
   2. MODAL CONTROLS
========================================================= */

function openModal(modalId) {
  const modal = document.getElementById(modalId);

  if (!modal) {
    console.error(`Modal not found: ${modalId}`);
    return;
  }

  modal.classList.add("active");
  document.body.style.overflow = "hidden";
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);

  if (!modal) return;

  modal.classList.remove("active");

  const anotherModalOpen = document.querySelector(
    ".msg-modal-overlay.active",
  );

  if (!anotherModalOpen) {
    document.body.style.overflow = "";
  }
}

/* =========================================================
   3. MESSAGE ELEMENT REFERENCES
========================================================= */

function getMessageTableBody() {
  return document.getElementById("messagesTableBody");
}

function getMessageRows() {
  const tableBody = getMessageTableBody();

  if (!tableBody) return [];

  return Array.from(
    tableBody.querySelectorAll("tr[data-id], tr[data-message-id]"),
  );
}

function getMessageId(row) {
  if (!row) return null;

  return row.dataset.id || row.dataset.messageId || null;
}

/* =========================================================
   4. MESSAGE API ENDPOINT
========================================================= */

function getMessageEndpoint() {
  const tableBody = getMessageTableBody();

  if (tableBody?.dataset.endpoint) {
    return tableBody.dataset.endpoint.replace(/\/$/, "");
  }

  const currentPath = window.location.pathname.replace(/\/$/, "");

  if (currentPath.endsWith("/admin/messages")) {
    return currentPath;
  }

  console.error(
    "Message endpoint not found. Add data-endpoint to messagesTableBody.",
  );

  return null;
}

/* =========================================================
   5. CSRF TOKEN
========================================================= */

function getMessageCsrfToken() {
  const tokenInput = document.querySelector(
    '#replyForm [name="_token"], input[name="_token"]',
  );

  return (
    tokenInput?.value ||
    window.adminCsrfToken ||
    ""
  ).trim();
}
/* =========================================================
   6. MESSAGE API REQUEST
========================================================= */

async function messageRequest(url, method, payload = null) {
  const token = getMessageCsrfToken();

  if (!token) {
    throw new Error(
      "Security token is missing. Refresh the page and try again.",
    );
  }

  const headers = {
    Accept: "application/json",
    "Content-Type": "application/json",
    "X-CSRF-Token": token,
    "X-Requested-With": "XMLHttpRequest",
  };

  const body = payload !== null
    ? JSON.stringify({
        ...payload,
        _token: token,
      })
    : undefined;

  const response = await fetch(url, {
    method,
    headers,
    body,
    credentials: "same-origin",
    redirect: "follow",
  });

  const responseText = await response.text();

  let result;

  try {
    result = JSON.parse(responseText);
  } catch {
    console.error("Unexpected server response:", {
      status: response.status,
      url: response.url,
      response: responseText.substring(0, 1500),
    });

    if (response.redirected) {
      throw new Error(
        "Your admin session may have expired. Log in again and retry.",
      );
    }

    throw new Error(
      `The server returned an unexpected response (HTTP ${response.status}). Check the browser console for details.`,
    );
  }

  if (!response.ok || result.success === false) {
    throw new Error(
      result.message || `Request failed (HTTP ${response.status}).`,
    );
  }

  return result;
}

/* =========================================================
   7. MESSAGE NOTIFICATIONS
========================================================= */

function showMessageNotification(message, type = "success") {
  let notification = document.getElementById("messageNotification");

  if (!notification) {
    notification = document.createElement("div");

    notification.id = "messageNotification";
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
   8. MESSAGE STATISTICS
========================================================= */

function updateMessageStats() {
  const rows = getMessageRows();

  const total = rows.length;

  const unread = rows.filter((row) => {
    return row.dataset.status === "unread";
  }).length;

  const read = total - unread;

  const totalElement = document.getElementById("totalMessages");
  const unreadElement = document.getElementById("unreadMessages");
  const readElement = document.getElementById("readMessages");

  if (totalElement) totalElement.textContent = total;
  if (unreadElement) unreadElement.textContent = unread;
  if (readElement) readElement.textContent = read;
}

/* =========================================================
   9. UPDATE MESSAGE STATUS IN THE INTERFACE
========================================================= */

function updateMessageRowStatus(row, status) {
  if (!row) return;

  row.dataset.status = status;

  row.dataset.readAt =
    status === "read" ? new Date().toISOString() : "";

  const badge = row.querySelector(
    ".status-badge, [data-status-label], .message-status",
  );

  if (badge) {
    badge.textContent = status === "read" ? "Read" : "Unread";

    badge.classList.remove("read", "unread");
    badge.classList.add(status);
  }

  if (currentMessageRow === row) {
    const detailStatus = document.getElementById("detailStatus");

    if (detailStatus) {
      detailStatus.textContent = status === "read" ? "Read" : "Unread";
      detailStatus.classList.remove("read", "unread");
      detailStatus.classList.add(status);
    }

    updateMessageStatusButton();
  }

  updateMessageStats();
  filterMessages();
}

/* =========================================================
   10. UPDATE MESSAGE STATUS IN DATABASE
========================================================= */

async function updateMessageStatus(row, desiredStatus) {
  if (!row) return false;

  const currentStatus = row.dataset.status;

  if (currentStatus === desiredStatus) {
    return true;
  }

  const id = getMessageId(row);
  const endpoint = getMessageEndpoint();

  if (!id || !endpoint) {
    showMessageNotification("Unable to identify this message.", "error");
    return false;
  }

  try {
    const result = await messageRequest(
      `${endpoint}/${encodeURIComponent(id)}/status`,
      "PATCH",
    );

    const actualStatus = result.data?.status || desiredStatus;

    updateMessageRowStatus(row, actualStatus);

    return actualStatus === desiredStatus;
  } catch (error) {
    console.error("Message status update failed:", error);

    showMessageNotification(
      error.message || "Unable to update message status.",
      "error",
    );

    return false;
  }
}

/* =========================================================
   11. VIEW MESSAGE DETAILS
========================================================= */

async function viewMessage(buttonOrRow) {
  let row = buttonOrRow;

  if (
    buttonOrRow instanceof HTMLElement &&
    buttonOrRow.tagName !== "TR"
  ) {
    row = buttonOrRow.closest("tr");
  }

  if (!row) return;

  currentMessageRow = row;

  const data = row.dataset;

  const setText = (id, value) => {
    const element = document.getElementById(id);

    if (element) {
      element.textContent = value || "Not provided";
    }
  };

  setText("detailName", data.name);
  setText("detailEmail", data.email);
  setText("detailSubject", data.subject);
  setText("detailMessage", data.message);
  setText("detailDate", data.createdAt || data.date);

  const statusElement = document.getElementById("detailStatus");

  if (statusElement) {
    const isRead = data.status === "read";

    statusElement.textContent = isRead ? "Read" : "Unread";
    statusElement.classList.remove("read", "unread");
    statusElement.classList.add(isRead ? "read" : "unread");
  }

  updateMessageStatusButton();

  openModal("messageModal");

  // Automatically mark unread messages as read.
  if (row.dataset.status === "unread") {
    await updateMessageStatus(row, "read");
  }
}

/* =========================================================
   12. UPDATE STATUS BUTTON
========================================================= */

function updateMessageStatusButton() {
  const button = document.getElementById("toggleReadButton");

  if (!button || !currentMessageRow) return;

  const isRead = currentMessageRow.dataset.status === "read";

  button.innerHTML = isRead
    ? '<i class="fas fa-envelope"></i> Mark as Unread'
    : '<i class="fas fa-check"></i> Mark as Read';
}

/* =========================================================
   13. TOGGLE CURRENT MESSAGE STATUS
========================================================= */

async function toggleCurrentMessageStatus() {
  if (!currentMessageRow) {
    showMessageNotification("Please select a message first.", "error");
    return;
  }

  const currentStatus = currentMessageRow.dataset.status;

  const newStatus = currentStatus === "read" ? "unread" : "read";

  const updated = await updateMessageStatus(
    currentMessageRow,
    newStatus,
  );

  if (!updated) return;

  showMessageNotification(
    newStatus === "read"
      ? "Message marked as read."
      : "Message marked as unread.",
  );
}

/* =========================================================
   14. CLOSE MESSAGE MODAL
========================================================= */

function closeMessageModal() {
  closeModal("messageModal");
  currentMessageRow = null;
}

/* =========================================================
   15. SEARCH AND FILTER MESSAGES
========================================================= */

function filterMessages() {
  const searchInput = document.getElementById("messageSearch");
  const filterInput = document.getElementById("messageFilter");

  const rows = getMessageRows();

  const searchValue = searchInput
    ? searchInput.value.toLowerCase().trim()
    : "";

  const filterValue = filterInput
    ? filterInput.value.toLowerCase()
    : "all";

  rows.forEach((row) => {
    const searchableText = row.textContent.toLowerCase();

    const matchesSearch = searchableText.includes(searchValue);

    const matchesFilter =
      filterValue === "all" || row.dataset.status === filterValue;

    row.style.display =
      matchesSearch && matchesFilter ? "" : "none";
  });
}

/* =========================================================
   16. DELETE MESSAGE
========================================================= */

async function deleteMessage(buttonOrRow) {
  let row = buttonOrRow;

  if (
    buttonOrRow instanceof HTMLElement &&
    buttonOrRow.tagName !== "TR"
  ) {
    row = buttonOrRow.closest("tr");
  }

  if (!row) return;

  const id = getMessageId(row);

  if (!id) {
    showMessageNotification("Message ID is missing.", "error");
    return;
  }

  const confirmed = confirm(
    "Are you sure you want to delete this message?",
  );

  if (!confirmed) return;

  const endpoint = getMessageEndpoint();

  if (!endpoint) return;

  const button =
    buttonOrRow instanceof HTMLElement &&
    buttonOrRow.tagName !== "TR"
      ? buttonOrRow
      : null;

  if (button) button.disabled = true;

  try {
    await messageRequest(
      `${endpoint}/${encodeURIComponent(id)}`,
      "DELETE",
    );

    const wasCurrentMessage = currentMessageRow === row;

    row.remove();

    if (wasCurrentMessage) {
      closeMessageModal();
    }

    updateMessageStats();
    filterMessages();

    showMessageNotification("Message deleted successfully.");
  } catch (error) {
    console.error("Message deletion failed:", error);

    showMessageNotification(
      error.message || "Unable to delete this message.",
      "error",
    );

    if (button) button.disabled = false;
  }
}

/* =========================================================
   17. OPEN REPLY MODAL
========================================================= */

function openReplyModal() {
  if (!currentMessageRow) {
    showMessageNotification(
      "Please open a message before replying.",
      "error",
    );

    return;
  }

  const data = currentMessageRow.dataset;

  const recipient = document.getElementById("replyTo");
  const subject = document.getElementById("replySubject");
  const message = document.getElementById("replyMessage");

  if (recipient) {
    recipient.value = data.email || "";
  }

  if (subject) {
    const originalSubject = data.subject || "Your message";

    subject.value = originalSubject.startsWith("Re:")
      ? originalSubject
      : `Re: ${originalSubject}`;
  }

  if (message) {
    message.value = "";
  }

  openModal("replyModal");
}

/* =========================================================
   18. SEND EMAIL REPLY THROUGH PHP / PHPMailer
========================================================= */

async function sendEmailReply() {
  if (!currentMessageRow) {
    showMessageNotification(
      "Please open a message before replying.",
      "error",
    );

    return;
  }

  const id = getMessageId(currentMessageRow);
  const endpoint = getMessageEndpoint();

  const email = document.getElementById("replyTo")?.value.trim();

  const emailSubject =
    document.getElementById("replySubject")?.value.trim();

  const emailBody =
    document.getElementById("replyMessage")?.value.trim();

  if (!id || !endpoint) {
    showMessageNotification(
      "Unable to identify the original message.",
      "error",
    );

    return;
  }

  if (!email) {
    showMessageNotification(
      "Recipient email address is missing.",
      "error",
    );

    return;
  }

  if (!emailSubject) {
    showMessageNotification(
      "Please enter an email subject.",
      "error",
    );

    return;
  }

  if (!emailBody) {
    showMessageNotification(
      "Please write your reply before sending.",
      "error",
    );

    return;
  }

  const replyForm = document.getElementById("replyForm");

  const submitButton =
    replyForm?.querySelector('[type="submit"]') ||
    document.getElementById("sendReplyButton");

  if (submitButton) {
    submitButton.disabled = true;
    submitButton.dataset.originalText =
      submitButton.innerHTML;

    submitButton.innerHTML =
      '<i class="fas fa-spinner fa-spin"></i> Sending...';
  }

  try {
    const result = await messageRequest(
      `${endpoint}/${encodeURIComponent(id)}/reply`,
      "POST",
      {
        subject: emailSubject,
        message: emailBody,
      },
    );

    closeModal("replyModal");

    showMessageNotification(
      result.message || "Your email reply was sent successfully.",
    );

    const messageField = document.getElementById("replyMessage");

    if (messageField) {
      messageField.value = "";
    }
  } catch (error) {
    console.error("Email reply failed:", error);

    showMessageNotification(
      error.message || "Unable to send your email reply.",
      "error",
    );
  } finally {
    if (submitButton) {
      submitButton.disabled = false;

      if (submitButton.dataset.originalText) {
        submitButton.innerHTML =
          submitButton.dataset.originalText;

        delete submitButton.dataset.originalText;
      }
    }
  }
}

/* =========================================================
   19. INITIALIZE MESSAGE MANAGEMENT
========================================================= */

document.addEventListener("DOMContentLoaded", function () {
  const messageSearch =
    document.getElementById("messageSearch");

  const messageFilter =
    document.getElementById("messageFilter");

  const messageStatusButton =
    document.getElementById("messageStatusButton") ||
    document.getElementById("toggleReadButton");

  const replyForm =
    document.getElementById("replyForm");

  const replyButton =
    document.getElementById("sendReplyButton");

  /* -----------------------------------------------
     INITIAL MESSAGE STATISTICS
  ----------------------------------------------- */

  updateMessageStats();

  /* -----------------------------------------------
     INITIAL SEARCH AND FILTER
  ----------------------------------------------- */

  if (messageSearch) {
    messageSearch.addEventListener("input", filterMessages);
  }

  if (messageFilter) {
    messageFilter.addEventListener("change", filterMessages);
  }

  /* -----------------------------------------------
     MESSAGE STATUS BUTTON
  ----------------------------------------------- */

  if (messageStatusButton) {
    messageStatusButton.addEventListener(
      "click",
      toggleCurrentMessageStatus,
    );
  }

  /* -----------------------------------------------
     REPLY FORM
  ----------------------------------------------- */

  if (replyForm) {
    replyForm.addEventListener("submit", function (event) {
      event.preventDefault();
      sendEmailReply();
    });
  } else if (replyButton) {
    replyButton.addEventListener("click", function (event) {
      event.preventDefault();
      sendEmailReply();
    });
  }
});
