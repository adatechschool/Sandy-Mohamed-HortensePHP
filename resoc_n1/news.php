<?php
    include 'header.php';    

?>
        <div id="wrapper">
            <aside>
                <img src="https://img.freepik.com/free-vector/tiny-people-beautiful-flower-garden-inside-female-head-isolated-flat-illustration_74855-11098.jpg?w=826&t=st=1677081124~exp=1677081724~hmac=ac2206dde303e4f32582f5588a7a845bb9a583095ac17674d05106cfcd19f9a4" alt="Portrait de l'utilisatrice"/>
                <section>
                    <h3>Présentation</h3>
                    <p>Sur cette page vous trouverez les derniers messages de
                        toutes les utilisatrices du site.</p>
                </section>
            </aside>
            <main>
                <!-- L'article qui suit est un exemple pour la présentation et 
                  @todo: doit etre retiré -->
             

                <?php
                /*
                  // C'est ici que le travail PHP commence
                  // Votre mission si vous l'acceptez est de chercher dans la base
                  // de données la liste des 5 derniers messsages (posts) et
                  // de l'afficher
                  // Documentation : les exemples https://www.php.net/manual/fr/mysqli.query.php
                  // plus généralement : https://www.php.net/manual/fr/mysqli.query.php
                 */

                // Etape 1: Ouvrir une connexion avec la base de donnée.
                $mysqli = new mysqli("localhost", "root", "", "socialnetwork");
                //verification
                if ($mysqli->connect_error)
                { 
                    echo "<article>";
                    echo("Échec de la connexion : " . $mysqli->connect_error);
                    echo("<p>Indice: Vérifiez les parametres de <code>new mysqli(...</code></p>");
                    echo "</article>";
                    exit();
                }

                // Etape 2: Poser une question à la base de donnée et récupérer ses informations
                // cette requete vous est donnée, elle est complexe mais correcte, 
                // si vous ne la comprenez pas c'est normal, passez, on y reviendra
                $laQuestionEnSql = "
                    SELECT posts.content,
                    posts.created,
                    posts.id,
                    users.id as author_id,
                    users.alias as author_name,  
                    count(likes.id) as like_number,  
                    GROUP_CONCAT(DISTINCT tags.label) AS taglist
                    FROM posts
                    JOIN users ON  users.id=posts.user_id
                    LEFT JOIN posts_tags ON posts.id = posts_tags.post_id  
                    LEFT JOIN tags       ON posts_tags.tag_id  = tags.id 
                    LEFT JOIN likes      ON likes.post_id  = posts.id 
                    GROUP BY posts.id
                    ORDER BY posts.created DESC  
                    LIMIT 5
                    ";

                // SELECT selectionne les colonnes demandées,
                // GROUP_CONCAT concatenne les chaines de charactère et renome avec AS dans une table temporaire.
                // FROM indique de quel table viennent les colonnes
                //JOIN prend la table user, et lie user.id avec post.user_id
                //GROUP BY regroupe les données récupérées par la requette
                //ORDER les organise 
                // LIMIT limite à 5 résultats

                $lesInformations = $mysqli->query($laQuestionEnSql);
                
                // Vérification
                if ( ! $lesInformations)
                {
                    echo "<article>";
                    echo("Échec de la requete : " . $mysqli->error);
                    echo("<p>Indice: Vérifiez la requete  SQL suivante dans phpmyadmin<code>$laQuestionEnSql</code></p>");
                    exit();
                }

                // Etape 3: Parcourir ces données et les ranger bien comme il faut dans du html
                // NB: à chaque tour du while, la variable post ci dessous reçois les informations du post suivant.
                while ($post = $lesInformations->fetch_assoc())
                {
                    //la ligne ci-dessous doit etre supprimée mais regardez ce 
                    //qu'elle affiche avant pour comprendre comment sont organisées les information dans votre 
                    //echo "<pre>" . print_r($post, 1) . "</pre>";

                    // @todo : Votre mission c'est de remplacer les AREMPLACER par les bonnes valeurs
                    // ci-dessous par les bonnes valeurs cachées dans la variable $post 
                    // on vous met le pied à l'étrier avec created
                    // 
                    // avec le ? > ci-dessous on sort du mode php et on écrit du html comme on veut... mais en restant dans la boucle

                    

                    ?>
                    <article>
                        <h3>
                            <time><?php echo $post['created'] ?></time>
                        </h3>
                        <address>par <a href="wall.php?user_id=<?php echo $post["author_id"] ?>"> <?php echo $post["author_name"] ?></a> </address>
                        <div>
                            <p><?php echo $post['content']?></p>
                            <?php 
                                $postId = $post['id'];
                                $new_like_count = $post['like_number'];
                                $otherButtonClick = isset($_POST[$postId]);
                                $like = "Like";
                                if ($otherButtonClick){
                                    include 'lastlike.php';
                                };
                            ?>
                            <form method='post'>
                                
                                <input type="hidden" name=<?php echo $postId ?>>
                                <input class="submit" name="like" type='submit' value=" ♥ <?php echo $new_like_count . $like ?>">
                               
                            </form>
                        </div>


                
                </article>
                    <?php
                    
                    // avec le <?php ci-dessus on retourne en mode php 
                };   // cette accolade ferme et termine la boucle while ouverte avant.
                   
                       
                                  
                ?>

                

                 
            </main>
        </div>
    </body>
</html>
