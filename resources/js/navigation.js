function initSpaNavigation() {
    const mainContent = document.getElementById("main-content");
    const pageTitle = document.querySelector("main .bg-white h2");

    if (!mainContent) {
        return;
    }

    function updateActiveState(currentUrl) {
        const currentPath = new URL(currentUrl, window.location.origin)
            .pathname;
        const navLinks = document.querySelectorAll("a[data-navigate]");

        navLinks.forEach((link) => {
            const linkHref = link.getAttribute("href") || "";
            if (!linkHref || linkHref === "#") {
                return;
            }

            const linkPath = new URL(linkHref, window.location.origin).pathname;
            const isSubmenu = link.classList.contains("submenu-link");
            const dataRoute = link.getAttribute("data-route") || "";

            link.classList.remove(
                "bg-blue-50",
                "text-blue-700",
                "bg-blue-500",
                "text-white",
            );
            if (isSubmenu) {
                link.classList.remove("text-gray-700");
                link.classList.add("text-gray-600");
            } else {
                link.classList.remove("text-gray-600");
                link.classList.add("text-gray-700");
            }
            link.classList.add("hover:bg-gray-100");

            const isActive =
                (dataRoute === "laporan" &&
                    currentPath.startsWith("/laporan")) ||
                currentPath === linkPath ||
                currentPath.startsWith(linkPath + "/");
            if (isActive) {
                link.classList.remove(
                    "text-gray-700",
                    "text-gray-600",
                    "hover:bg-gray-100",
                );
                link.classList.add("bg-blue-50", "text-blue-700");
            }
        });

        const parentButtons = document.querySelectorAll(
            "[data-sidebar-parent]",
        );
        parentButtons.forEach((button) => {
            const group = button.getAttribute("data-sidebar-parent");
            const isActive =
                (group === "master" && currentPath.startsWith("/master")) ||
                (group === "transaksi" &&
                    currentPath.startsWith("/transaksi")) ||
                (group === "laporan" && currentPath.startsWith("/laporan")) ||
                (group === "pengaturan" &&
                    currentPath.startsWith("/user-management"));

            button.classList.remove(
                "bg-blue-50",
                "text-blue-700",
                "text-gray-700",
                "hover:bg-gray-100",
            );
            if (isActive) {
                button.classList.add("bg-blue-50", "text-blue-700");
            } else {
                button.classList.add("text-gray-700", "hover:bg-gray-100");
            }
        });
    }

    async function handleNavigation(event) {
        event.preventDefault();
        const url = this.getAttribute("href");

        if (!url || url === "#") {
            return;
        }

        const targetPath = new URL(url, window.location.origin).pathname;
        if (window.location.pathname === targetPath) {
            return;
        }

        updateActiveState(url);
        mainContent.classList.add("page-loading");

        try {
            const response = await fetch(url, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");

            const newContent = doc.querySelector("#main-content");
            const newTitle = doc.querySelector("main .bg-white h2");
            const newPageTitle = doc.querySelector("title");

            if (newContent) {
                mainContent.innerHTML = newContent.innerHTML;
                mainContent.classList.remove("page-loading");
                mainContent.classList.add("content-fade-in");
                setTimeout(
                    () => mainContent.classList.remove("content-fade-in"),
                    300,
                );

                attachNavigationHandlers();
            }

            if (newTitle && pageTitle) {
                pageTitle.textContent = newTitle.textContent;
            }

            if (newPageTitle) {
                document.title = newPageTitle.textContent;
            }

            window.history.pushState({ url }, "", url);
            updateActiveState(url);

            const scripts = mainContent.querySelectorAll("script");
            scripts.forEach((script) => {
                const newScript = document.createElement("script");
                if (script.src) {
                    newScript.src = script.src;
                } else {
                    newScript.textContent = script.textContent;
                }
                document.body.appendChild(newScript);
                setTimeout(() => newScript.remove(), 100);
            });
        } catch (error) {
            console.error("Navigation error:", error);
            mainContent.classList.remove("page-loading");
            window.location.href = url;
        }
    }

    function attachNavigationHandlers() {
        const navLinks = document.querySelectorAll("a[data-navigate]");
        navLinks.forEach((link) => {
            link.removeEventListener("click", handleNavigation);
            link.addEventListener("click", handleNavigation);
        });
    }

    attachNavigationHandlers();
    updateActiveState(window.location.href);

    window.addEventListener("popstate", async () => {
        const url = window.location.href;
        mainContent.classList.add("page-loading");

        try {
            const response = await fetch(url, {
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
            });

            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, "text/html");

            const newContent = doc.querySelector("#main-content");
            const newTitle = doc.querySelector("main .bg-white h2");
            const newPageTitle = doc.querySelector("title");

            if (newContent) {
                mainContent.innerHTML = newContent.innerHTML;
                mainContent.classList.remove("page-loading");
                mainContent.classList.add("content-fade-in");
                setTimeout(
                    () => mainContent.classList.remove("content-fade-in"),
                    300,
                );

                attachNavigationHandlers();
            }

            if (newTitle && pageTitle) {
                pageTitle.textContent = newTitle.textContent;
            }

            if (newPageTitle) {
                document.title = newPageTitle.textContent;
            }

            updateActiveState(url);
        } catch (error) {
            console.error("Popstate navigation error:", error);
            mainContent.classList.remove("page-loading");
            window.location.reload();
        }
    });
}

function initMobileSidebar() {
    const sidebarToggle = document.getElementById("mobile-sidebar-toggle");
    const sidebar = document.getElementById("sidebar");
    const sidebarOverlay = document.getElementById("sidebar-overlay");

    if (!sidebarToggle || !sidebar || !sidebarOverlay) {
        return;
    }

    sidebarToggle.addEventListener("click", () => {
        sidebar.classList.toggle("mobile-open");
        sidebarOverlay.classList.toggle("hidden");
    });

    sidebarOverlay.addEventListener("click", () => {
        sidebar.classList.remove("mobile-open");
        sidebarOverlay.classList.add("hidden");
    });
}

document.addEventListener("DOMContentLoaded", () => {
    initSpaNavigation();
    initMobileSidebar();
});
