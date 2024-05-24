<header>
    <script src="/accuel/visites/visits.js"></script>
    <nav>
        <ul class="act_nav indexBlueDark">
            <li>
                <a href="/ACM/forum.php"><img src="/accuel/images/logo.png", width="65rem"></a>
            </li>
            <li>
                <a href="/ACM/forum.php" class="title">Art Compass Media</a>
            </li>
            <li>
                <a href="/ACM/forum.php">Main page</a>
            </li>
            <li>
                <a href="tickets.php">Messages</a>
            </li>
            <li>
                <a href="transferts.php">Create a post</a>
            </li>
            <li>
                <a href="/ACM/dm.php">dm</a><img src="/accuel/images/dm.webp", width="45rem">
            </li>
            <li>
                <?php
                    if(isset($_SESSION["user"])){
                        echo "<a href='/accuel/profile/profile.php'><img src='/accuel/images/account.png', width='45rem'></a>";
                    } else echo "<a href='/accuel/connex/connexion.php'><img src='/accuel/images/account.png', width='45rem'></a>"
                ?>
            </li>
            <li>
                <button onclick="darkMode()", class="dark_mode indexBlueDark"><img src="/accuel/images/darkmode.png", width="50rem"></button>
            </li>
            <li>
                <a href="/accuel/visites/cart.php"><img src="/accuel/images/cart.png", width="45rem"></a>
            </li>
            <li>
            <?php
                if(isset($_SESSION["user"])){
                    echo "<a href='/accuel/connex/deconnexion.php'><img src='/accuel/images/log_out.png' width='45rem'></a>";
                }
            ?>
            </li>
        </ul>
    </nav>
</header>