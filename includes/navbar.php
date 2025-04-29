<!-- This php shows the current page for the navbar -->
<?php
$current_page = basename($_SERVER['PHP_SELF']);
$nav_items = [
    'index.php' => 'Home',
    'about.php' => 'About',
    'services' => [
        'title' => 'Services',
        'children' => [
            'informationHub.php' => 'Information Hub',
            'calculator.php' => 'Calculator',
            'appointments.php' => 'Appointments'
        ]
    ],
    'contact.php' => 'Contact'
];
?>

<nav class="navbar navbar-expand-lg bg-white navbar-light sticky-top p-0">
    <a href="index.php" class="navbar-brand d-flex align-items-center border-end px-4 px-lg-5">
        <h2 class="m-0 text-primary">Rolsa Technologies</h2>
    </a>
    <button  id = "button" title = "navigation" type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarCollapse">
        <div class="navbar-nav ms-auto p-4 p-lg-0">
            <?php foreach ($nav_items as $url => $label): ?>
                <?php if (is_array($label)): ?>
                    <!-- Dropdown Menu -->
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle <?= in_array($current_page, array_keys($label['children'])) ? 'active' : '' ?>" data-bs-toggle="dropdown">
                            <?= $label['title'] ?>
                        </a>
                        <div class="dropdown-menu bg-light m-0">
                            <?php foreach ($label['children'] as $child_url => $child_label): ?>
                                <a href="<?= $child_url ?>" class="dropdown-item <?= ($current_page === $child_url) ? 'active' : '' ?>">
                                    <?= $child_label ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <!-- Regular Link -->
                    <a href="<?= $url ?>" class="nav-item nav-link <?= ($current_page === $url) ? 'active' : '' ?>" <?= ($current_page === $url) ? 'aria-current="page"' : '' ?>>
                        <?= $label ?>
                    </a>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <!-- Profile Icon -->
            <a class="btn btn-square btn-link rounded-0" href="profile.php">
                <i class="fas fa-user icon-circle"></i>
            </a>
            
            <!-- Logout Button -->
            <a href="../logout.php" class="btn btn-primary nav-item nav-link rounded-0 py-2 px-lg-4 text-white">
                    Logout <i class="fa fa-arrow-right ms-2"></i>
                </a>
            </a>
        </div>
    </div>
</nav>