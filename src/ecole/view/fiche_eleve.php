<p>
    <?php if (!empty($error)): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo $error; ?>
    </div>
<?php endif; ?>
</p>

<form method="post" action="index.php?page=Eleve" class="form-horizontal col-md-6">

    <input type="hidden" name="idEleve" value="<?php echo $idEleve; ?>">
    <div class="form-group">
        <legend>Informations générales</legend>
    </div>
    <div class="row">
        <div class="form-group">
            <label for="nom" class="col-md-2 control-label">Nom</label>
            <div class="col-md-10">
                <input type="text" name="nom" id="nom" class="form-control" required <?php if (!empty($nom)) { ?> value="<?php echo $nom; ?>"<?php } ?>>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="prenom" class="col-md-2 control-label">Prénom</label>
            <div class="col-md-10">
                <input type="prenom" name="prenom" id="prenom" class="form-control" required <?php if (!empty($prenom)) { ?> value="<?php echo $prenom; ?>"<?php } ?> >
            </div>
        </div>
    </div>

    <div class="row">
        <div class="form-group">
            <label for="age" class="col-md-2 control-label">Age</label>
            <?php if (array_key_exists('role', $_SESSION)) { ?>
                <div class="col-md-10">
                    <input type="number" name="age" id="age" class="form-control" required <?php if (!empty($age)) { ?> value="<?php echo $age; ?>"<?php } ?> >
                </div>
            <?php } else { ?>
                <div class="col-md-10">
                    <input type="text" name="age" id="age" required <?php if (!empty($age)) { ?> value="<?php echo $age; ?>"<?php } ?> >
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="row">
        <div class="form-group" class="col-md-2 control-label">
            <label>Sexe</label>
            <div class="radio">
                <label><input type="radio" name="sexe" value="m" <?php if ($sexe == 'm') { ?>checked<?php } ?>>Garçon</label>
            </div>
            <div class="radio">
                <label><input type="radio" name="sexe" value="f" <?php if ($sexe == 'f') { ?>checked<?php } ?>>Fille</label>
            </div>
        </div>  
    </div>

    <div class="row">
        <div class="form-group">
            <?php if (array_key_exists('idEleve', $_GET)) { ?>
                <label for="currentClasse" class="col-md-2 control-label">Classe courante</label>
                <div class="col-md-10">
                    <input type="text" class="form-control" name="currentClasse" id="currentClasse" value="<?php echo $classe[0]; ?>">
                </div>
                
                <div class="form-group">
                    <?php if (array_key_exists('role', $_SESSION)): ?>    
                        <label for="classe">Mettre à jour la classe:</label>
                        <select class="form-control" id="classe" name="classe">
                            <?php foreach ($aClasses as $oClasse): ?>
                                <option value="<?php echo $oClasse->getId(); ?>">
                                    <?php echo $oClasse->getNomClasse(); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
            <?php } else { ?>
                <label for="classe">Classe:</label>
                <select class="form-control" id="classe" name="classe">
                    <?php foreach ($aClasses as $oClasse): ?>
                        <option value="<?php echo $oClasse->getId(); ?>">
                            <?php echo $oClasse->getNomClasse(); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            <?php } ?>
        </div>
    </div>

    <?php if (array_key_exists('role', $_SESSION)): ?>
        <button type="submit" class="btn btn-default">Soumettre</button>
    <?php endif; ?>
</form>

<!--    TABLEAU DE NOTES PAR MATIERE    -->

<?php if (array_key_exists('idEleve', $_GET)): ?>
    <h2>Notes de l'élève</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Matières</th>
                <th>Notes</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($aNotes as $oNote): ?>

                <tr>

                    <td><?php echo $oNote->getMatiere(); ?></td>
                    <?php
                    $aNotes = $oNote->getNote();
                    ?>
                    <td>
                        <?php
                        //pour utiliser implode sur un tableau associatif quand on n'a pas PHP 5.5 :
                        $result = array();
                        foreach ($aNotes as $row) {
                            $result[] = $row['note'];
                        }
                        echo implode(", ", $result);
                        ?>
                    </td>

                </tr>

            <?php endforeach; ?>
        </tbody>
    </table>

    <div>
        <h3>Moyenne générale</h5>
            <?php if (isset($totalAvg)) { ?>
                <span><?php echo $totalAvg['moyenne_generale']; ?></span>
            <?php } else { ?>
                <p> <?php echo "Cet élève n'a pas encore de notes"; ?> </p>
            <?php } ?>
    </div>


    <!--    AJOUTER DES NOTES    -->

    <?php if (array_key_exists('role', $_SESSION)): ?>
        <h2>Ajouter des notes</h2>
        <p>
            <?php if (!empty($errorNote)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorNote; ?>
            </div>
        <?php endif; ?>
        </p>
        <form method="post" action="index.php?page=AddNote&idEleve=<?php echo $idEleve; ?>">
            <input type="hidden" name="idEleve" value="<?php echo $idEleve; ?>">
            <div class="form-group">
                <label for="matiere">Choisir une matière :</label>
                <select class="form-control" id="matiere" name="matiere">
                    <?php foreach ($aMatieres as $oMatiere): ?>
                        <option value="<?php echo $oMatiere->getId(); ?>">
                            <?php echo $oMatiere->getLibelle(); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="note">Choisir une note :</label>
                <select class="form-control" id="note" name="note">
                    <?php for ($i = 0; $i <= 20; $i++): ?>
                        <option value="<?php echo $i ?>">
                            <?php echo $i ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-default">Ajouter la note</button>
        </form>
    <?php endif; ?>
    <?php










 endif;
