/* =========================================================
   CODECRAFT ADMIN DASHBOARD
   SHARED ADMIN JAVASCRIPT
========================================================= */


/* =========================================================
   1. SIDEBAR
========================================================= */

function toggleSidebar() {

    const sidebar = document.getElementById("sidebar");

    const overlay = document.getElementById("sidebarOverlay");

    if (!sidebar || !overlay) return;

    sidebar.classList.toggle("open");

    overlay.classList.toggle("show");

}


function closeSidebar() {

    const sidebar = document.getElementById("sidebar");

    const overlay = document.getElementById("sidebarOverlay");

    if (!sidebar || !overlay) return;

    sidebar.classList.remove("open");

    overlay.classList.remove("show");

}


/* =========================================================
   2. ADMIN PAGE NAVIGATION
========================================================= */

function showPage(pageName) {

    const sections = document.querySelectorAll(".page-section");

    sections.forEach(section => {

        section.classList.remove("active");

    });

    const selectedPage = document.getElementById(pageName);

    if (selectedPage) {

        selectedPage.classList.add("active");

    }

    document.querySelectorAll(".nav-item").forEach(link => {

        link.classList.remove("active");

    });

    const activeLink = document.querySelector(
        `.nav-item[data-page="${pageName}"]`
    );

    if (activeLink) {

        activeLink.classList.add("active");

    }

    const titles = {

        dashboard: "Dashboard",

        blog: "Blog Management",

        messages: "Messages & Contact",

        projects: "Project Management",

        newsletter: "Newsletter Management",

        settings: "Settings"

    };

    const pageTitle = document.getElementById("pageTitle");

    if (pageTitle) {

        pageTitle.textContent = titles[pageName] || "Admin Dashboard";

    }

    closeSidebar();

}


/* =========================================================
   3. GENERAL MODAL FUNCTIONS
========================================================= */

function openModal(modalId) {

    const modal = document.getElementById(modalId);

    if (!modal) return;

    modal.classList.add("show");

    document.body.classList.add("modal-open");

}


function closeModal(modalId) {

    const modal = document.getElementById(modalId);

    if (!modal) return;

    modal.classList.remove("show");

    const anotherModal = document.querySelector(
        ".modal-overlay.show"
    );

    if (!anotherModal) {

        document.body.classList.remove("modal-open");

    }

}


/* =========================================================
   4. CLOSE MODAL WHEN CLICKING OUTSIDE
========================================================= */

document.addEventListener("DOMContentLoaded", function () {

    document.querySelectorAll(".modal-overlay").forEach(overlay => {

        overlay.addEventListener("click", function (event) {

            if (event.target !== overlay) return;

            overlay.classList.remove("show");

            const anotherModal = document.querySelector(
                ".modal-overlay.show"
            );

            if (!anotherModal) {

                document.body.classList.remove("modal-open");

            }

        });

    });

});