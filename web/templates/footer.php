<?php include 'head.php'; ?>
<style>
    footer {
        border-top: 2px solid #ccc;
        width: 100%;
        background-color: #f8f9fa;
        text-align: center;
        padding: 1rem;
        z-index: 21;
    }

    .col-auto a {
        color: #000;
        text-decoration: none;
    }

    html,
    body {
        height: 100%;
        margin: 0;
    }

    body {
        display: flex;
        flex-direction: column;
        min-height: 100vh;
    }

    main {
        flex: 1;
    }
</style>

<footer class="result">
    <div class="container-fluid">
        <div class="text-center fotter-py">
            <div class="mt-1 text-center">
                © <script>
                    document.write(new Date().getFullYear())
                </script> otaku Type
            </div>
        </div>
    </div>
</footer>