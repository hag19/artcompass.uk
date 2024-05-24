<header>
    <?php
    echo '<script src="/accuel/visites/visits.js" defer></script>';
    if (isset($_COOKIE["darkMode"])) {
        echo '<div onload="darkMode()"></div>';
    }
    ?>
    <nav class="mobileheader">
        <ul class="act_nav indexBlueDark">

            <li>
                <a href="/index.php"><img src="/accuel/images/logo.png" , width="65rem"></a>
            </li>
            <li>
                <a href="/index.php" class="title">Art Compass Tours</a>
            </li>
            <?php
            if (isset($_SESSION['user']) && $_SESSION['user'] == 'admin') {
                echo '<li>
                    <a href="/admin/admin.php">Admin page</a>
                    </li>';
            }
            if (isset($_SESSION['user']) && $_SESSION['user'] == 'guide') {
                echo '<li>
                    <a href="/accuel/guide/guide.php></a>"
                    </li>';
            }

            ?>
            <li>
                <a href="/accuel/visites/visite.php"><?= lang('Our Tours') ?></a>
            </li>
            <li>
                <a href="/accuel/transferts/transferts.php"><?= lang('Transferts') ?></a>
            </li>
            <li>
                <a href="/ACM/blog/blog.php">Art Compass Media</a>
            </li>
            <li>
                <?php
                if (isset($_SESSION["id"])) {
                    echo "<a href='/accuel/profile/profile.php'><img src='/accuel/images/account.png', width='45rem'></a>";
                } else
                    echo "<a href='/accuel/connex/connexion.php'><img src='/accuel/images/account.png', width='45rem'></a>"
                        ?>
                </li>
                <li>
                    <div class="dropdown">
                        <a href="#"><img src="/accuel/images/lang.png" width='45rem'></a>
                        <div class="dropdown-content hide">
                            <div><a href="/index.php?lang=en"><img src="/accuel/images/england.png" width='45rem'></a></div>
                            <div><a href="/index.php?lang=zh"><img src="/accuel/images/china.png" width='45rem'></a></div>
                            <div><a href="/index.php?lang=ja"><img src="/accuel/images/japan.png" width='45rem'></a></div>
                        </div>
                    </div>
                </li>
                <li>
                    <button onclick="darkMode()" class="dark_mode indexBlueDark"><img src="/accuel/images/darkmode.png" ,
                            width="50rem"></button>
                </li>
                <li>
                    <a href="/accuel/visites/cart.php"><img src="/accuel/images/cart.png" , width="45rem"></a>
                </li>
                <li>
                    <?php
                if (isset($_SESSION["id"])) {
                    echo "<a href='/accuel/connex/deconnexion.php'><img src='/accuel/images/log_out.png' width='45rem'></a>";
                }
                ?>
            </li>
        </ul>
    </nav>
    <script>
        var dropdowns = document.querySelectorAll('.dropdown');
        var i;
        for(i = 0;i<dropdowns.length;++i){
            dropdowns[i].addEventListener('click',function(event){
                event.currentTarget.querySelector('.dropdown-content').classList.toggle('hide');
            })
        }
    </script>
</header>