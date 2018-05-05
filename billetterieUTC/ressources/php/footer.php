        <div class="bandeau_noir_bas">
            <div class="couleur_noir">&nbsp;</div>
        </div>

        <script>
            $(document).ready(function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

        </script>


        <div id="fb-root"></div>
        <script>
            (function(d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id)) return;
                js = d.createElement(s);
                js.id = id;
                js.src = 'https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.11';
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));

        </script>

        <!--   Permet de figer le carousel artiste en hover  -->
        <script>
            $('.carousel').carousel({
                pause: "hover"
            })

        </script>

        <!--   Permet de faire apparaitre popup (cf artistes sur mobiles)  -->
        <script>
            $(document).ready(function() {
                $('[data-toggle="popover"]').popover();
            });

        </script>
        <script src="./JS/index.js"></script>
    </body>


    </html>
