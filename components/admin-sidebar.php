<div class="sidebar mx-2">
    <div class="logo-details">
        <img src="../img/logo.svg" alt="logo" width="200px">
    </div>

    <ul class="nav-links mt-2 px-1">

        <p class="text-secondary fw-normal px-2 pb-2" style="font-size: 12px;">APPLICATION</p>

        <li>
            <a href="main-panel.php">
                <i class='bx bx-grid-alt fs-5'></i>
                <span class="link_name">Dashboard</span>
            </a>
        </li>

        <li>
            <a href="book-add.php">
                <i class='bx bx-collection fs-5'></i>
                <span class="link_name">Books adding</span>
            </a>
        </li>

        <p class="text-secondary fw-normal px-2 py-2" style="font-size: 12px;">MANAGEMENT</p>

        <li>
            <a href="manage-users.php">
                <i class='bx bx-user fs-5'></i>
                <span class="link_name">Manage Users</span>
            </a>
        </li>

        <li>
            <a href="manage-books.php">
                <i class='bx bx-book-alt fs-5'></i>
                <span class="link_name">Manage Books</span>
            </a>
        </li>

        <p class="text-secondary fw-normal px-2 py-2" style="font-size: 12px;">RECENT</p>

        <li>
            <a href="manage-invoices.php">
                <i class='bx bx-receipt fs-5'></i>
                <span class="link_name">Recent Invoices</span>
            </a>
        </li>

        <li>
            <a href="manage-reviews.php">
                <i class='bx bx-comment-detail fs-5'></i>
                <span class="link_name">Recent Reviews</span>
            </a>
        </li>

        <p class="text-secondary fw-normal px-2 py-2" style="font-size: 12px;">ANALYTICS</p>

        <li>
            <a href="analytics.php">
                <i class='bx bx-pie-chart-alt-2 fs-5'></i>
                <span class="link_name">Analytics</span>
            </a>
        </li>

        <li>
            <a href="#coming_soon">
                <i class='bx bx-history fs-5'></i>
                <span class="link_name">History</span>
            </a>
        </li>

        <li>
            <a href="#coming_soon">
                <i class='bx bx-cog fs-5'></i>
                <span class="link_name">Setting</span>
            </a>
        </li>

    </ul>
</div>

<style>
    /* base transition */
    .nav-links a {
        transition: background-color .15s ease, color .15s ease;
        position: relative;
        /* needed for the left bar pseudo-element */
        padding-left: 0.80rem;
        /* make room for the left bar + icon spacing */
        display: flex;
        align-items: center;
        gap: 0.60rem;
    }

    /* active background + rounded corners */
    .nav-links a.active-link {
        background-color: #f6f6f6;
        border-radius: 8px;
        color: inherit;
    }

    /* 5px left colored bar for active items */
    .nav-links a.active-link::before {
        content: "";
        position: absolute;
        left: 12px;
        top: 13px;
        /* adjust to keep bar inside rounded corners */
        bottom: 13px;
        width: 5px;
        background: rgb(255, 82, 82);
        /* change to desired accent color */
        border-radius: 8px;
    }

    /* style the icon when active — place inside small box look */
    .nav-links a i {
        font-size: 1.20rem;
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: background-color .15s ease, color .15s ease;
    }

    /* keep text spacing tidy */
    .nav-links .link_name {
        white-space: nowrap;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const links = Array.from(document.querySelectorAll('.nav-links a[href]')).filter(a => a.getAttribute('href') !== '#');
        const currentPath = (window.location.pathname.split('/').pop() || '').toLowerCase();
        const currentHref = window.location.href.toLowerCase();

        links.forEach(link => {
            const href = link.getAttribute('href') || '';
            const linkFile = href.split('/').pop().toLowerCase();

            // mark active when file matches OR when current URL contains the href (handles query strings & different levels)
            if ((linkFile && linkFile === currentPath) || (href && currentHref.includes(href.toLowerCase()))) {
                link.classList.add('active-link');
            }

            link.addEventListener('click', function() {
                links.forEach(l => l.classList.remove('active-link'));
                this.classList.add('active-link');
            });
        });
    });
</script>