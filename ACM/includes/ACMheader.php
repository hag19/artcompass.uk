<header class="indexBlueDark">
    <script src="/accuel/visites/visits.js"></script>
    <nav>
        <ul class="acm_nav">
            <li>
                <a href="/ACM/blog/blog.php"><img src="/accuel/images/logo.png" , width="65rem"></a>
            </li>
            <li>
                <a href="/ACM/blog/blog.php" class="title">Art Compass Media</a>
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
            if (isset($_SESSION["id"])) {

                echo '<li>
                        <a href="/accuel/profile/profile.php">Profile</a>
                    </li>
                    <li>
                        <a href="/ACM/friend/main.php">Community</a>
                    </li>
                    <li>
                        <a href="/ACM/blog/add_post.php">Create a post</a>
                    </li>';
            } else {
                echo '<li>
                        <a href="/accuel/connex/connexion.php?message=please login or create an account">Community</a>
                    </li>';
            }
            ?>
            <li>
                <a href="/index.php">Art Compass Tours</a>
            </li>
            <?php
            if (isset($_SESSION["id"])) {
                echo '<li><a href="/ACM/dm/dm.php"><img src="/accuel/images/dm.png", width="45rem"></a></li>';
                echo "<li><a href='/accuel/profile/profile.php'><img src='/accuel/images/account.png', width='45rem'></a></li>";
            } else
                echo "<li><a href='/accuel/connex/connexion.php'><img src='/accuel/images/account.png', width='45rem'></a></li>"
                    ?>
                <li>
                    <div class="dropdown">
                        <a href="#"><img src="/accuel/images/lang.png" width='45rem'></a>
                        <div class="dropdown-content hide">
                            <div><a href="/ACM/blog/blog.php?lang=en"><img src="/accuel/images/england.png" width='45rem'></a></div>
                            <div><a href="/ACM/blog/blog.php?lang=zh"><img src="/accuel/images/china.png" width='45rem'></a></div>
                            <div><a href="/ACM/blog/blog.php?lang=ja"><img src="/accuel/images/japan.png" width='45rem'></a></div>
                        </div>
                    </div>
                </li>
                <li>
                    <button onclick="darkMode()" , class="dark_mode indexBlueDark"><img src="/accuel/images/darkmode.png" ,
                            width="50rem"></button>
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