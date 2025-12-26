<?php include 'head.php'; ?>
<style>
    .navbar-nav .nav-item:hover .dropdown-menu {
        display: block;
        position: absolute;
    }

    html {
        scrollbar-width: none;
    }

    .dropdown-menu {
        margin-top: 0;
    }

    .icon {
        font-size: 24px;
        margin: 0 10px;
    }

    .custom-border {
        border-bottom: 2px solid #000;
    }

    .navbar-nav {
        display: flex;
        width: 100%;
    }

    header {
        width: 100%;
        right: 0;
        top: 0;
        z-index: 10;
    }

    .nav-height {
        height: 120px;
        position: relative;
        z-index: 3;
    }

    .navbar-brand {
        margin: 0;
    }

    @media screen and (max-width: 949px) {
        .navbar-nav {
            flex-direction: column;
            align-items: flex-start;
        }
    }

    @media (min-width: 950px) {
        .navbar-expand-lg .navbar-nav .dropdown-menu {
            position: absolute;
        }
    }

    .me5 {
        margin-right: 3rem;
    }

    @media screen and (min-width: 876px) and (max-width: 900px) {
        .ml_125 {
            margin-left: 145px;
        }
    }

    @media screen and (min-width: 901px) and (max-width: 949px) {
        .cu-position {
            position: relative;
            top: -80px;
        }

        .ml_125 {
            margin-left: 145px;
        }
    }

    @media (min-width: 992px) {
        .cu-position {
            position: relative;
            top: 0 !important;
        }

        .ml_125 {
            margin-left: 0 !important;
        }
    }

    @media screen and (min-width: 950px) and (max-width: 992px) {
        .navbar-nav {
            flex-direction: row !important;
            /* justify-content: space-between; */
            flex-wrap: wrap;
        }

        .nav-item {
            margin-right: 1rem;
        }

        .fs5 {
            font-size: 18px !important;
        }

        .me5 {
            margin-right: 1.25rem;
        }

        .custom-me {
            margin-right: 25px !important;
        }

        .cu-position {
            position: relative;
            top: -80px;
        }

        .ml_125 {
            margin-left: 145px;
        }
    }

    .navbar-toggler {
        border: none !important;
        cursor: pointer;
        color: #000;
        height: 46px;
    }

    .blackstar-font {
        font-size: 32px;
        font-family: "Fjalla One", sans-serif;
        font-optical-sizing: auto;
        font-weight: 500;
        color: #000;
        z-index: 9999;
    }

    .custom-ms {
        margin-left: 30px;
    }

    @media (min-width: 992px) {
        .custom-ms {
            margin-left: 0;
        }
    }

    @media (min-width: 1700px) {
        .blackstar {
            margin-left: 300px;
        }

        .custom-img {
            margin-right: 250px;
        }

        .align-left {
            margin-left: 250px !important;
        }
    }

    @media screen and (min-width: 1400px) and (max-width: 1700px) {
        .align-left {
            margin-left: 200px !important;
        }
    }

    @media (max-width: 875px) {
        .custom-img {
            margin-left: 100px;
        }
    }

    @media (max-width: 1700px) {
        .blackstar {
            margin-left: 33px;
        }

        .custom-stars {
            margin-left: 20px;
        }
    }

    @media (max-width: 900px) {
        .blackstar {
            display: none;
        }
    }

    @media (max-width: 400px) {
        .custom-img {
            margin-left: 110px;
        }
    }

    @media (max-width: 768px) {
        body {
            width: 100%;
        }

        .navbar-brand svg {
            width: 100%;
            width: 20px;
        }

        .bi-person-fill {
            margin: 5px;
            width: 25px !important;
        }

        .bi-heart {
            margin-top: 3px;
        }

        .custom-img2 {
            margin-top: 15px;
        }

        .mb {
            margin-bottom: 10px;
        }

    }

    .top-icon {
        width: 85px;
        height: 85px;
    }

    .no-link {
        text-decoration: none !important;
        color: inherit;
        cursor: pointer;
    }

    .no-link:hover {
        color: inherit;
    }

    .badge {
        display: inline-block;
        padding: 0.2em 0.6em;
        border-radius: 50%;
        background-color: red;
        color: white;
        font-size: 0.75rem;
        font-weight: bold;
        text-align: center;
        line-height: 1;
        min-width: 20px;
        /* バッジの最小幅を設定 */
    }

    @media (max-width: 600px) {
        .custom-stars {
            margin-left: 0 !important;
        }

        .m0 {
            margin-right: 2px;
        }

        .he-80 {
            height: 80px;
        }

        .he-50 {
            height: 50px;
        }

        .bi-list-stars {
            height: 25px;
            margin-bottom: 10px;
        }

        .sm-star {
            width: 60px;
        }
    }

    .navbar-nav-container {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.9s ease-out;
    }

    .static {
        position: static !important;
    }

    .navbar-nav-container.open {
        max-height: 1000px;
    }

    .custom-dropdown {
        top: 100%;
        left: auto;
        right: 0;
        margin-top: 0.5rem;
    }


    .navbar-brand[data-bs-toggle="dropdown"]::after {
        display: none;
    }

    @media (min-width: 768px) {

        .container,
        .container-md,
        .container-sm {
            max-width: 900px !important;
        }
    }


    @media (min-width: 949px) {
        .navbar-nav-container {
            display: block;
            max-height: none;
            overflow: visible;
            transition: none;
        }

        .dropdown-menu {
            display: block;
        }

        .navbar-toggler {
            display: none;
        }

        .static {
            position: absolute !important;
        }
    }

    .nav-item {
        display: inline-block;
    }

    .nav-link {
        white-space: nowrap;
        display: inline-block;
    }

    .dropdown-menu {
        display: none;
    }

    .dropdown:hover .dropdown-menu {
        display: block;/
    }

    .navbar-nav .nav-item:hover .dropdown-menu {
        display: block;
        position: absolute;
    }

    .dropdown-menu {
        margin-top: 0;
    }

    .icon {
        font-size: 24px;
        margin: 0 10px;
    }

    .custom-border {
        border-bottom: 2px solid #000;
    }

    .navbar-nav {
        display: flex;
        width: 100%;
    }

    @media (max-height: 800px) {
        header {
            display: none;
        }

        body,
        .container {
            padding-top: 0 !important;
        }

        .otaku-title {
            font-size: 85px;
        }
    }

    .pd-t {
        padding-top: 150px;
    }

    header {
        width: 100%;
        position: fixed;
        right: 0;
        top: 0;
        z-index: 1000;
    }

    .nav-height {
        height: 120px;
        position: relative;
        z-index: 3;
    }

    .svg {
        top: -10px;
        left: -60px;
    }

    @media(max-width: 991px) {
        .svg {
            top: 14px;
            left: 100px;
        }
    }

    .navbar-brand {
        margin: 0;
    }

    .headfont {
        font-family: "WDXL Lubrifont TC", sans-serif;
        font-size: 20px;
    }
</style>

<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-white custom-border nav-height he-80">
        <div class="align-items-center" style="z-index: 999;">
            <p class="text-align-left">
                <a class="no-link" href="/top" style="margin: 0;">
                    <div class="blackstar-font mt-4 blackstar" style="width: 100%;">otaku type</div>
                </a>
            </p>
        </div>
        <div class="container d-flex align-items-center justify-content-between cu-position">
            <div class="d-flex justify-content-center flex-grow-1 z-index custom-img">
                <a class="navbar-brand" href="/" style="margin: 0; display: none;">
                    <img src="/assets/img/blackstar.jpg" class="img-fluid top-icon .carousel-control-next sm-star ml_125">
                </a>
            </div>
            <div class="d-flex align-items-center custom-img2">
                <?php if (isset($_SESSION['USER'])) : ?>
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="navbar-brand" id="navbarDropdownLadies" role="button" data-bs-toggle="dropdown" aria-expanded="false" style="margin: 0;">
                                <svg xmlns="http://www.w3.org/2000/svg" style="position: absolute;;" class="svg" width="35" height="35" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
                                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                                </svg>
                            </a>
                            <ul class="dropdown-menu custom-dropdown" aria-labelledby="navbarDropdownLadies">
                                <li><a class="dropdown-item" href="profile">マイページ</a></li>
                                <li><a class="dropdown-item" href="logout">ログアウト</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php else : ?>
                    <a class="navbar-brand" href="login" style="margin: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" class="bi bi-person-fill m0" viewBox="0 0 16 16">
                            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                        </svg>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <div class="text-center">
        <nav class="navbar navbar-expand-lg navbar-light bg-light he-50">
            <div class="container align-items-center custom-stars align-left" style="padding-left: 5px; background-color: rgb(248 249 250) !important;">
                <div class="ms-3">
                    <button class="navbar-toggler" type="button" id="navbarToggler" aria-label="Toggle navigation" style="box-shadow: none; padding: 0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-list-stars" viewBox="0 0 16 16">
                            <path fill-rule="evenodd" d="M5 11.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5z" />
                            <path d="M2.242 2.194a.27.27 0 0 1 .516 0l.162.53c.035.115.14.194.258.194h.551c.259 0 .37.333.164.493l-.468.363a.277.277 0 0 0-.094.3l.173.569c.078.256-.213.462-.423.3l-.417-.324a.267.267 0 0 0-.328 0l-.417.323c-.21.163-.5-.043-.423-.299l.173-.57a.277.277 0 0 0-.094-.299l-.468-.363c-.206-.16-.095-.493.164-.493h.55a.271.271 0 0 0 .259-.194l.162-.53zm0 4a.27.27 0 0 1 .516 0l.162.53c.035.115.14.194.258.194h.551c.259 0 .37.333.164.493l-.468.363a.277.277 0 0 0-.094.3l.173.569c.078.255-.213.462-.423.3l-.417-.324a.267.267 0 0 0-.328 0l-.417.323c-.21.163-.5-.043-.423-.299l.173-.57a.277.277 0 0 0-.094-.299l-.468-.363c-.206-.16-.095-.493.164-.493h.55a.271.271 0 0 0 .259-.194l.162-.53zm0 4a.27.27 0 0 1 .516 0l.162.53c.035.115.14.194.258.194h.551c.259 0 .37.333.164.493l-.468.363a.277.277 0 0 0-.094.3l.173.569c.078.255-.213.462-.423.3l-.417-.324a.267.267 0 0 0-.328 0l-.417.323c-.21.163-.5-.043-.423-.299l.173-.57a.277.277 0 0 0-.094-.299l-.468-.363c-.206-.16-.095-.493.164-.493h.55a.271.271 0 0 0 .259-.194l.162-.53z" />
                        </svg>
                    </button>
                </div>
                <div id="navbarNav" class="navbar-nav-container custom-ms" style="flex-basis: 100%;">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link fs5 me5 headfont" href="/top">トッフ*</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle fs5 me5 headfont" href="ranking?d=ea" id="navbarDropdownLadies" role="button" aria-expanded="false">
                                ランキング
                            </a>
                            <ul class="dropdown-menu static" aria-labelledby="navbarDropdownLadies">
                                <li><a class="dropdown-item" href="ranking?d=ea">改級</a></li>
                                <li><a class="dropdown-item" href="ranking?d=no">真級</a></li>
                                <li><a class="dropdown-item" href="ranking?d=ha">絶級</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</header>
<script>
</script>