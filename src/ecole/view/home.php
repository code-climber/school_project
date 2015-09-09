<h1 class="page-header">Bienvenue sur le site de l'école des nains !</h1>

<div class="row">
    <section class="col-md-12">
        <p>
            Vous trouverez ci-dessous la liste de tous nos élèves. Vous pouvez consulter
            la fiche élève de chacun d'eux. Vous y trouverez leurs informations générales
            ainsi que leurs notes de l'année et leur moyenne générale.
            Bonne visite !
        </p>
    </section
</div>

<div class="row">
    <!--    BARRE DE RECHERCHE D ELEVE    -->
    <section class="col-md-6 col-md-offset-1 table-responsive top-buffer">
        <form method="post" action="index.php?page=home">
            <label for="search">Rechercher un élève : </label>
            <input type="text" name="search" id="search">
            <button type="submit" class="btn btn-default">rechercher</button>
        </form>

        <!--    TABLEAU DE TOUS LES ELEVES    -->


        <table class="table table-striped table-condensed">
            <thead>
            <th>Elève</th>
            <th>Age</th>
            <th>Classe</th>
            <?php if (array_key_exists('role', $_SESSION)): ?>
                <th>Administration</th>
            <?php endif; ?>
            </thead>

            <tbody>
                <?php foreach ($aEleves as $oEleve): ?>
                    <tr>
                        <td>
                            <a href="index.php?page=Eleve&idEleve=<?php echo $oEleve->getId(); ?>"><?php echo $oEleve->getNom() . " " . $oEleve->getPrenom(); ?></a>
                        </td>
                        <td><?php echo $oEleve->getAge(); ?></td>

                        <?php $classe = $oEleve->getClasse(); ?>
                        <td> <?php echo $classe; ?> </td>

                        <?php if (array_key_exists('role', $_SESSION)): ?>
                            <td><a href="index.php?page=Eleve&idEleve=<?php echo $oEleve->getId(); ?>"><i class="icon-edit"></i></a>
                            <a href="index.php?page=deleteOneEleve&idEleve=<?php echo $oEleve->getId(); ?>"><i class="icon-remove-sign"></i></a></td>
                        <?php endif; ?>
                    </tr>

                <?php endforeach; ?>

            </tbody>
        </table>
    </section>
</div>




